<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2018/7/24 0024
 * Time: 20:41
 */

namespace App\HttpController\Api;
use App\Exception\Websocket\WsException;
use App\Model\Group;
use App\Model\GroupMember as GroupMemberModel;
use App\Model\User;
use App\Service\Common;
use App\Service\GroupService;
use App\Service\UserCacheService;
use App\Validate\GroupMember as GroupMemberValidate;
use EasySwoole\Core\Component\Logger;
use EasySwoole\Core\Swoole\ServerManager;

class GroupMember extends Base
{
    public function getMembers()
    {
        //获取群信息
        $id = $this->request()->getRequestParam('id');
        $owner = Group::getGroupOwner($id);
        //获取群成员
        $list = GroupMemberModel::getGroupMembers($owner['gnumber']);
        $data = User::getUserByNumbers($list);
        $this->success(['owner' => $owner ,'list' => $data]);
    }
    /**
     * 离开群组
     */
    public function leaveGroup()
    {
        (new GroupMemberValidate('leave'))->goCheck($this->request());
        $groupNumber = GroupMemberModel::getNumberById($this->request()->getRequestParam('id'));
        $res = GroupMemberModel::delMemberById($this->user['number'] , $groupNumber);
        if(!$res)
        {
            return $this->error('','退出失败');
        }
        return $this->success('','退出成功');
    }
    /**
     * 检查用户是否可以继续创建群
     */
    public function checkUserCreateGroup()
    {
        $list = Group::getGroup(['user_number' => $this->user['number']]);
        if(count($list) > 50)
        {
            return $this->error('','超过最大建群数');
        }
        return $this->success();
    }
    /**
     * 创建群
     */
    public function createGroup()
    {
        (new GroupMemberValidate('create'))->goCheck($this->request());
        $data = $this->request()->getParsedBody();
        // 生成唯一群号
        $number = Common::generate_code(8);
        // 保存群信息，并加入群
        $group_data = [
            'gnumber'       => $number,
            'user_number'   => $this->user['number'],
            'ginfo'         => $data['des'],
            'gname'         => $data['des'],
            'groupname' => $data['groupName'],//群名称
            'approval' => $data['approval'],//验证方式 需要验证 不需要验证
            'number' => $data['number'],//群上限人数
        ];
        $member_data = [
            'gnumber'       => $number,
            'user_number'   => $this->user['number'],
        ];
        try{
           $id =  Group::newGroup($group_data);
            GroupMemberModel::newGroupMember($member_data);
        }catch (\Exception $e){
            Logger::getInstance()->log($e->getMessage(),'LTalk_debug');
            $msg = (new WsException())->getMsg();
            return $this->error(null,$msg);
        }
        $sendData  = [
            'id'            => $id,
            'avatar'         => '/timg.jpg',
            'groupname'     => $data['groupName'],
            'type'          => 'group',
            'gnumber'       => $number

        ];
        // 创建缓存
        UserCacheService::setGroupFds($number, $this->user['fd']);
        $server = ServerManager::getInstance()->getServer();
        $server->push($this->user['fd'] , json_encode(['type'=>'ws','method'=> 'newGroup','data'=> $sendData]));
        $server->push($this->user['fd'] , json_encode(['type'=>'ws','method'=> 'ok','data'=> '创建成功']));
        return $this->success(['groupid' => $number,'groupName' => $data['groupName']],'');

    }
}
