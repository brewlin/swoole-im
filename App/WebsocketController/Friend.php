<?php
/**
 * Created by PhpStorm.
 * User: yuzhang
 * Date: 2018/4/14
 * Time: 下午3:40
 */

namespace App\WebsocketController;

use App\Common\Enum\MsgBoxEnum;
use App\Exception\Websocket\FriendException;
use App\Model\MsgBox;
use App\Model\User as UserModel;
use App\Model\User;
use App\Service\FriendService;
use App\Service\UserCacheService;
use App\Task\Task;
use App\Model\Friend as FriendModel;
use EasySwoole\Core\Swoole\Task\TaskManager;
use App\Service\MsgBoxServer;
use App\Service\GroupUserMemberService;
class Friend extends BaseWs
{
    /*
     * 发送好友请求
     * 1. 查看当前用户是存在/是否在线
     * 2. 发送好友请求
     */
    public function sendReq(){
        $content = $this->request()->getArg('content');
        $user = $this->getUserInfo();
        $toId = $content['id'];
        $toUser = $this->onlineValidate($toId);
        if(isset($to_user['errorCode']))
        {
            $this->response()->write(json_encode($toUser));
            return;
        }
        // 不可添加自己好友
        if($user['user']['number'] == $toId)
        {
            $err = (new FriendException([
                'msg' => '不可添加自己为好友',
                'errorCode' => 40006
            ]))->getMsg();
            $this->response()->write(json_encode($err));
            return;
        }

        // 查二者是否已经是好友
        $isFriend = FriendService::checkIsFriend($user['user']['id'], $toUser['user']['id']);
        if($isFriend){
            $err = (new FriendException([
                'msg' => '不可重复添加好友',
                'errorCode' => 40004
            ]))->getMsg();
            $this->response()->write(json_encode($err));
            return;
        }

        // 存储请求状态
        UserCacheService::saveFriendReq($user['user']['number'], $toUser['user']['number']);

        // 准备发送请求的数据
        $data = [
            'method'    => 'friendRequest',
            'data'      => [
                'from'  => $user['user']
            ]
        ];
        //写入msgbox记录
        $msgBox = [
            'type' => MsgBoxEnum::AddFriend,
            'from' => $user['user']['id'],
            'to' => $toId,
            'send_time' => time(),
            'remark' => $content['remark'],
            'group_user_id' => $content['group_user_id'],
        ];
        $msgId = MsgBox::addMsgBox($msgBox);
        $data['data']['from']['msg_id'] = $msgId;
        // 异步发送好友请求
        $fd = UserCacheService::getFdByNum($toUser['user']['number']);
        $taskData = [
            'method' => 'sendMsg',
            'data'  => [
                'fd'        => $fd,
                'data'      => $data
            ]
        ];
        $taskClass = new Task($taskData);
        TaskManager::async($taskClass);
        $this->sendMsg(['data'=>'好友请求已发送！']);
    }

    /*
     * 处理好友请求
     * @param number 对方号码
     * @param res    是否同意，1同意，0不同意
     */
    public function doReq()
    {
        $content = $this->request()->getArg('content');
        $fromUser = User::getUserById($content['friend_id']);
        $check = $content['check'];
        $user = $this->getUserInfo();

        // 缓存校验，删除缓存，成功表示有该缓存记录，失败则没有
        $cache = UserCacheService::delFriendReq($fromUser['number']);
        if(!$cache){
            $msg = (new FriendException([
                'msg' => '好友请求操作失败',
                'errorCode' => 40003
            ]))->getMsg();
            $this->response()->write(json_encode($msg));
            return;
        }

        // 若同意，
        //添加好友记录，
        //加入对方好友队列
        //异步通知双方，
        //更新消息状态
        //若不同意，在线则发消息通知
        if($check)
        {
            MsgBoxServer::updateStatus($content,$user['user']['id']);
            GroupUserMemberService::newFriends($content ,$user['user']['id']);
        }else
        {
            //更新为拒绝
            MsgBox::updateById($content['msg_id'] , ['type' => $content['msg_type'] ,'status' => $content['status'] ,'read_time' => time()]);
        }

        // 异步通知双方
        $data  = [
            'from_number'   => $fromUser['number'],
            'number'        => $user['user']['number'],
            'check'         => $check,
            'msg_id'        => $content['msg_id'],
        ];
        FriendService::doReq($data);
    }

    /*
     * 获取好友列表
     */
    public function getFriends(){
        $user = $this->getUserInfo();
        $friends = FriendModel::getAllFriends($user['user']['id']);
        $data = FriendService::getFriends($friends);
        $this->sendMsg(['method'=>'getFriends','data'=>$data]);
    }



}
