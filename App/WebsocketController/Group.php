<?php
/**
 * Created by PhpStorm.
 * User: xiaodo
 * Date: 2018/10/18
 */

namespace App\WebsocketController;

use App\Common\Enum\MsgBoxEnum;
use App\Exception\Websocket\GroupException;
use App\Exception\Websocket\WsException;
use App\Model\GroupMember;
use App\Model\MsgBox;
use App\Model\User;
use App\Service\Common;
use App\Model\Group as GroupModel;
use App\Model\GroupMember as GroupMemberModel;
use App\Service\GroupService;
use App\Service\MsgBoxServer;
use App\Service\UserCacheService;
use App\Task\Task;
use EasySwoole\Core\Component\Logger;
use EasySwoole\Core\Swoole\ServerManager;
use EasySwoole\Core\Swoole\Task\TaskManager;

class Group extends BaseWs
{
    protected $hidden = ['id','creater_time'];

    /*
     * 创建群组
     * 1. 验证此人创建了多少群组，不可超过3个
     * 2. 创建群号
     * 3. 保存群信息，此人加入该群
     * 4. 创建缓存
     * 5. 异步返回创建的群
     */
    public function create(){
        $content = $this->request()->getArg('content');
        $user = $this->getUserInfo();
        $gname = $content['gname'];
        $ginfo = isset($content['ginfo'])?$content['ginfo']:"";

        if(empty($gname)){
            $msg = (new WsException([
                'msg' => '参数异常',
                'errorCode' => 60002
            ]))->getMsg();
            $this->response()->write(json_encode($msg));
            return;
        }

        $count = GroupModel::getSum(['user_number'=>$user['user']['number']]);
        if($count>=3){
            $msg = (new GroupException([
                'msg' => '创建群组已达上限',
                'errorCode' => 70001
            ]))->getMsg();
            $this->response()->write(json_encode($msg));
            return;
        }

        // 生成唯一群号
        $number = Common::generate_code(8);
        while ( !GroupModel::getGroup(['gnumber'=>$number])->isEmpty() ){
            $number = Common::generate_code(8);
        }

        // 保存群信息，并加入群
        $group_data = [
            'gnumber'       => $number,
            'user_number'   => $user['user']['number'],
            'ginfo'         => $ginfo,
            'gname'         => $gname
        ];
        $member_data = [
            'gnumber'       => $number,
            'user_number'   => $user['user']['number'],
        ];
        try{
            $gid = GroupModel::newGroup($group_data);
            GroupMemberModel::newGroupMember($member_data);
        }catch (\Exception $e){
            Logger::getInstance()->log($e->getMessage(),'LTalk_debug');
            $msg = (new WsException())->getMsg();
            $this->response()->write(json_encode($msg));
            return;
        }
        // 创建缓存
        UserCacheService::setGroupFds($number, $user['fd']);

        // 异步通知
        $g_info = [
            'gname'  => $gname,
            'ginfo'  => $ginfo,
            'gnumber'=> $number,
            'gid'     => $gid,
        ];
        GroupService::sendNewGroupInfo($g_info, $user);
    }

    /*
     * 加入群组
     * 1. 查询群组是否存在
     * 2. 查询是否已在群组中
     * 3. 写入数据库，存缓存
     * 4. 发送群组信息
     */
    public function sendJoinGroupReq(){
        $content = $this->request()->getArg('content');
        $user = $this->getUserInfo();
        $id = $content['id'];
        $gnumber = $content['gnumber'];

        //查询群组是否存在
        $res = GroupModel::getGroup(['gnumber'=>$gnumber], true);
        if(!$res){
            $msg = (new GroupException([
                'msg' => '群组不存在',
                'errorCode' => 70002
            ]))->getMsg();
            $this->response()->write(json_encode($msg));
            return;
        }

        // 查询是否在群组中
        $is_in = GroupMemberModel::getGroups(['user_number'=>$user['user']['number'], 'gnumber'=>$gnumber]);
        if(!$is_in->isEmpty()){
            $msg = (new GroupException([
                'msg' => '您已在此群组中',
                'errorCode' => 70003
            ]))->getMsg();
            $this->response()->write(json_encode($msg));
            return;
        }
        // 准备发送请求的数据
        $data = [
            'method'    => 'groupRequest',
            'data'      => [
                'from'  => $user['user']
            ]
        ];
        //获取群主
        $toUser = \App\Model\Group::getGroupOwnById($id);
        $toId = $toUser['user']['id'];
        //写入msgbox记录
        $msgBox = [
            'type' => MsgBoxEnum::AddGroup,
            'from' => $user['user']['id'],
            'to' => $toId,
            'send_time' => time(),
            'remark' => $content['remark'],
        ];
        $msgId = MsgBox::addMsgBox($msgBox);
        $data['data']['from']['msg_id'] = $msgId;
        $data['data']['from']['gnumber'] = $gnumber;
        $data['data']['from']['gid'] = $id;

        // 异步加群要求
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
        $this->sendMsg(['data'=>'加群请求已发送！']);

    }
    /**
     * 群主处理加群请求
     */
    public function doJoinGroupReq()
    {
        $content = $this->request()->getArg('content');
        //申请人的信息
        $fromUser = User::getUserById($content['from_id']);
        $check = $content['check'];
        $user = $this->getUserInfo();
        $gid = $content['gid'];
        $gnumber = $content['gnumber'];
        $groupInfo = \App\Model\Group::getGroup(['id' => $gid],true);

        // 若同意，
            //添加群记录记录，
            //异步通知双方，
            //更新消息状态
        //若不同意，在线则发消息通知
        if($check)
        {
            MsgBoxServer::updateStatus($content,$user['user']['id']);
            GroupMember::newGroupMember(['gnumber' => $gnumber,'user_number' => $fromUser['number'],'status' => 1]);
        }else
        {
            //更新为拒绝
            MsgBox::updateById($content['msg_id'] , ['type' => $content['msg_type'] ,'status' => $content['status'] ,'read_time' => time()]);
        }
        // 异步通知双方
        $data  = [
            'id'            => $gid,
            'avatar'         => $groupInfo['avatar'],
            'groupname'     => $groupInfo['groupname'],
            'type'          => 'group'

        ];
        GroupService::doReq($fromUser['number'],$check,$data);
        $server = ServerManager::getInstance()->getServer();
        $server->push(UserCacheService::getFdByNum($fromUser['number']) , json_encode(['type'=>'ws','method'=> 'nok','data'=> '加入群-'.$groupInfo['groupname'].'-成功!']));
        // 创建缓存
        UserCacheService::setGroupFds($gnumber, $user['fd']);

    }
    /*
     * 群组列表
     */
    public function getGroups(){
        $user = $this->getUserInfo();
        $groups = GroupMemberModel::getGroups(['user_number'=>$user['user']['number']]);
        $this->sendMsg(['method'=>'groupList','data'=>$groups]);
    }
}