<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2018/7/24 0024
 * Time: 20:41
 */

namespace App\HttpController\Api;
use App\Model\Group;
use App\Model\GroupMember as GroupMemberModel;
use App\Model\User;
use App\Validate\GroupMember as GroupMemberValidate;
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
        var_dump($this->user);
    }
}
