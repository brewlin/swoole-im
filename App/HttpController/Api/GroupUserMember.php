<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2018/7/28
 * Time: 23:21
 */

namespace App\HttpController\Api;
use App\Validate\GroupUserMember as GroupUserMemberValidate;
use App\Model\GroupUserMember as GroupUserMemberModel;
use App\Model\User;
class GroupUserMember extends Base
{
    /**
     * 编辑好友备注名
     */
    public function editFriendRemarkName()
    {
        (new GroupUserMemberValidate('remark'))->goCheck($this->request());
        $data = $this->request()->getRequestParam();
        $res = GroupUserMemberModel::editFriendRemarkName($this->user['id'] , $data['friend_id'] , $data['friend_name']);
        if($res)
        {
            return $this->success($data['friend_name']);
        }
        return $this->error('','修改失败');
    }
    /**
     * 移动好友分组
     */
    public function moveFriendToGroup()
    {
        (new GroupUserMemberValidate('move'))->goCheck($this->request());
        $data = $this->request()->getRequestParam();
        $res = GroupUserMemberModel::moveFriend($this->user['id'] , $data['friend_id'] , $data['groupid']);
        if($res)
        {
            //返回好友信息
            $user = User::getUser(['id' => $data['friend_id']]);
            return $this->success($user);
        }
        return $this->error('','移动失败');
    }
    /**
     * 删除好友
     */
    public function removeFriend()
    {
        (new GroupUserMemberValidate('remove'))->goCheck($this->request());
        $data = $this->request()->getRequestParam();
        $res = GroupUserMemberModel::removeFriend($this->user['id'] , $data['friend_id']);
        if($res)
        {
            return $this->success('','删除成功');
        }
        return $this->error('','修改成功');
    }
    /**
     * 获取推荐好友
     */
    public function getRecommendFriend()
    {
        //获取所有好友
        $list = User::getAllUser();
        //去除已经是本人的好友关系
        return $this->success($list);

    }
}