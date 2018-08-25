<?php
/**
 * Created by PhpStorm.
 * User: yuzhang
 * Date: 2018/4/18
 * Time: 下午7:23
 */

namespace App\WebsocketController;

use App\Exception\Websocket\GroupException;
use App\Exception\Websocket\WsException;
use App\Service\Common;
use App\Model\Group as GroupModel;
use App\Model\GroupMember as GroupMemberModel;
use App\Service\GroupService;
use App\Service\UserCacheService;
use EasySwoole\Core\Component\Logger;

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
            GroupModel::newGroup($group_data);
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
            'gnumber'=> $number
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
    public function joinGroup(){
        $content = $this->request()->getArg('content');
        $user = $this->getUserInfo();
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

        // 写入数据库
        $member_data = [
            'gnumber'       => $gnumber,
            'user_number'   => $user['user']['number'],
        ];
        try{
            GroupMemberModel::newGroupMember($member_data);
        }catch (\Exception $e){
            Logger::getInstance()->log($e->getMessage(),'LTalk_debug');
            $msg = (new WsException())->getMsg();
            $this->response()->write(json_encode($msg));
            return;
        }

        // 创建缓存
        UserCacheService::setGroupFds($gnumber, $user['fd']);

        // 异步通知
        $g_info = [
            'gname'  => $res['gname'],
            'ginfo'  => $res['ginfo'],
            'gnumber'=> $res['gnumber'],
        ];
        GroupService::sendNewGroupInfo($g_info, $user);
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