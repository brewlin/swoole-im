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
use App\Model\GroupMember;
use App\Model\GroupUser;
use App\Service\GroupUserMemberService;
use EasySwoole\Core\Swoole\ServerManager;
class User extends Base
{
    const Friend = 'friend';
    const Group = 'group';

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
                'number'    => $user['user']['id'],
                'nickname'  => $user['user']['nickname'],
            ]
        ];
        foreach ($friends as $val) {
            foreach ($val['list'] as $v){
                if ($v['status']) {
                    $fd = UserCacheService::getFdByNum($v['number']);
                    $server->push($fd, json_encode($data));
                }
            }
        }
    }
    /**
     * 修改用户签名
     */
    public function editSignature()
    {
        (new UserValidate('sign'))->goCheck($this->request());
        $sign = $this->request()->getQueryParam('sign');
        UserModel::updateUser($this->user['id'] ,['sign' => $sign]);
        $user = UserModel::find($this->user['id']);
        UserCacheService::saveTokenToUser($this->request()->getQueryParam('token') , $user);
        return $this->success([],'成功');
    }
    /**
     * 查找好友 群
     */
    public function findFriendTotal()
    {
        (new UserValidate('total'))->goCheck($this->request());
        $type = $this->request()->getQueryParam('type');
        $value = $this->request()->getQueryParam('value');

        if($type == self::Friend)
        {
            //搜索用户
            $res = UserModel::searchUser($value);
            return $this->success(['count' => count($res) ,'limit' => 16]);
        }else
        {
            //搜索群组
            $res = UserModel::searchUser($value);
            return $this->success(['count' => count($res) ,'limit' => 16]);
        }
    }
    /**
     * 查找好友 群 统计数量
     */
    public function findFriend()
    {
        (new UserValidate('find'))->goCheck($this->request());
        $type = $this->request()->getQueryParam('type');
        $page = $this->request()->getQueryParam('page');
        $value = $this->request()->getQueryParam('value');
        if($type == self::Friend)
        {
            //搜索用户
            $res = UserModel::searchUser($value , $page);
            return $this->success($res);
        }else
        {
            //搜索群组
            $res = UserModel::searchUser($value , $page);
            return $this->success($res);
        }

    }

}
