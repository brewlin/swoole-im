<?php
/**
 * Created by PhpStorm.
 * User: yuzhang
 * Date: 2018/4/16
 * Time: 下午8:58
 */

namespace App\WebsocketController;


use App\Exception\Websocket\FriendException;
use App\Exception\Websocket\GroupException;
use App\Service\ChatService;
use App\Service\Common;
use App\Service\FriendService;
use App\Model\GroupMember as GroupMemberModel;
use App\Model\User;

class Chat extends BaseWs
{
    /*
     * 处理个人聊天
     * @param number
     * @param data
     *
     * 1. 验证用户是否存在，是否在线
     * 2. 检查是否是好友关系
     * 3. 异步给双方发送消息，做标记是自己的还是对方发的
     * 4. 异步存储消息记录
     */
    public function personalChat(){
        $content = $this->request()->getArg('content');
        $user = $this->getUserInfo();
        $to_number = User::getNumberById($content['id']);
        $data = Common::security($content['data']);
        /**
         * 验证好友在线情况
         */
        $to_user = $this->onlineValidate($content['id']);
        // 异步发送消息的数据结构
        $chat_data = [
            'from'  => $user,
            'to'    => $to_user,
            'data'  => $data,
            'is_read' => 1
        ];
        if(isset($to_user['errorCode']))
        {
            //不在线直接存储消息后退出
            $chat_data['to'] = ['user' => ['id' => $content['id']]];
            $chat_data['is_read'] = 0;

            // 异步存储消息
            ChatService::savePersonalMsg($chat_data);
            $this->response()->write(json_encode($to_user['msg']));
            return;
        }
        // 查二者是否已经是好友
        $isFriend = FriendService::checkIsFriend($user['user']['id'], $to_user['user']['id']);
        if(!$isFriend){
            $err = (new FriendException([
                'msg' => '非好友状态',
                'errorCode' => 40005
            ]))->getMsg();
            $this->response()->write(json_encode($err));
            return;
        }
        ChatService::sendPersonalMsg($chat_data);

        // 异步存储消息
        ChatService::savePersonalMsg($chat_data);
    }

    /*
     * 处理群组聊天
     * @param gnumber
     * @param data
     *
     * 1. 查询该组是否存在
     * 2. 查询此人是否在组中
     * 3. 异步给组内所有人发送消息，做标记是自己的还是对方发的
     * 4. 异步存储消息记录
     */
    public function groupChat(){
        $content = $this->request()->getArg('content');
        $user = $this->getUserInfo();
        $gnumber = isset($content['gnumber'])?$content['gnumber']:"";
        $data =  Common::security($content['data']);

        $is_in = GroupMemberModel::getGroups(['user_number'=>$user['user']['number'], 'gnumber'=>$gnumber]);
        if($is_in->isEmpty()){
            $msg = (new GroupException([
                'msg' => '用户不在此群中',
                'errorCode' => 70004
            ]))->getMsg();
            $this->response()->write(json_encode($msg));
            return;
        }

        // 异步发送消息
        $chat_data = [
            'user'      => $user,
            'gnumber'   => $gnumber,
            'data'      => $data
        ];

        // 发送群组消息
        ChatService::sendGroupMsg($chat_data);

        // 异步存储消息
        ChatService::saveGroupMsg($chat_data);
    }
}