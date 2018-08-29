<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2018/7/28
 * Time: 23:21
 */

namespace App\HttpController\Api;
use App\Model\Group;
use App\Service\UserCacheService;
use App\Validate\User as UserValidate;
use App\Model\User as UserModel;
use App\Model\Friend as FriendModel;
use App\Model\GroupMember;
use App\Model\GroupUser;
use App\Service\GroupUserMemberService;
use App\Service\FriendService;
use EasySwoole\Core\Swoole\ServerManager;
class User extends Base
{
    /**
     * 获取群信息 或者获取好友信息
     */
    public function getInformation()
    {
        //type friend 就获取好友信息 type为group则获取群信息
        (new UserValidate('info'))->goCheck($this->request());
        $data = $this->request()->getRequestParam();
        if($data['type'] == 'friend')
        {
            $info = UserModel::getUser(['id' => $data['id']]);
            $info['type'] = 'friend';
        }else if($data['type'] == 'group')
        {
            $info = Group::getGroup(['id' => $data['id']] , true);
            $info['type'] = 'group';
        }else
        {
            return $this->error('类型错误');
        }
        return $this->success($info);

    }
    /**
     * 用户退出删除用户相关资源
     */
    public function userQuit()
    {
        $token = $this->request()->getRequestParam("token");
        $user   = UserCacheService::getUserByToken($token);
        $info = [
            'user' => $user,
            'token' => $token,
        ];
        if($info)
        {
                // 销毁相关缓存
            $this->delCache($info);

                // 给好友发送离线提醒
            $this->offLine($info);
        }
    }
    /*
    * 销毁个人/群组缓存
    */
    private function delCache($info){
        $fd = UserCacheService::getFdByNum($info['user']['number']);
        UserCacheService::delTokenUser($info['token']);
        UserCacheService::delNumberUserOtherInfo($info['user']['number']);
        UserCacheService::delFdToken($fd);
        UserCacheService::delFds($fd);
        $groups = GroupMember::getGroups(['user_number'=>$info['user']['number']]);
        if(!$groups->isEmpty()){
            foreach ($groups as $val){
                UserCacheService::delGroupFd($val->gnumber, $fd);
            }
        }
    }

    /*
     * 给在线好友发送离线提醒
     */
    private function offLine($user){
        // 获取分组好友
        $groups = GroupUser::getAllFriends($user['user']['id']);
        $friends = GroupUserMemberService::getFriends($groups);
        $server = ServerManager::getInstance()->getServer();
        $data = [
            'type'      => 'ws',
            'method'    => 'friendOffLine',
            'data'      => [
                'number'    => $user['user']['number'],
                'nickname'  => $user['user']['nickname'],
            ]
        ];
        foreach ($friends as $val) {
            foreach ($val['list'] as $v){
                if ($v['online']) {
                    $fd = UserCacheService::getFdByNum($v['number']);
                    $server->push($fd, json_encode($data));
                }
            }
        }
    }
}