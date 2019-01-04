<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2018/7/24 0024
 * Time: 20:41
 */

namespace App\HttpController\Api;
use App\Model\Friend;
use App\Model\GroupMember;
use App\Model\GroupUser;
use App\Service\GroupUserMemberService;
use App\Service\UserCacheService;
class Init extends Base
{
    public function index()
    {
    }
    public function init()
    {
        //获取自己信息
        $token = $this->request()->getRequestParam('token');
        $user = UserCacheService::getUserByToken($token);
        $user['status'] = 'online';
        // 获取分组好友
        $friends = GroupUser::getAllFriends($user['id']);
        $data = GroupUserMemberService::getFriends($friends);
        //获取群组信息
        $groups = GroupMember::getGroupNames(['user_number'=>$user['number'],'status' => 1]);
        $this->success(['mine' => $user ,'friend' => $data, 'group' => $groups]);
    }
}