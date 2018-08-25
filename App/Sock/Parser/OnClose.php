<?php
/**
 * Created by PhpStorm.
 * User: yuzhang
 * Date: 2018/4/15
 * Time: 下午5:18
 */

namespace App\Sock\Parser;


use App\Model\GroupMember;
use App\Service\FriendService;
use App\Service\UserCacheService;
use App\Model\Friend as FriendModel;
use EasySwoole\Core\Swoole\ServerManager;

class OnClose
{
    private $fd;

    public function __construct($fd)
    {
        $this->fd = $fd;
    }

    public function close(){
        $info = $this->getInfoByFd();
        if($info){
            // 销毁相关缓存
//            $this->delCache($info);

            // 给好友发送离线提醒
            $this->offLine($info);
        }
    }

    /*
     * 销毁个人/群组缓存
     */
    private function delCache($info){
        UserCacheService::delTokenUser($info['token']);
        UserCacheService::delNumberUserOtherInfo($info['user']['number']);
        UserCacheService::delFdToken($this->fd);
        UserCacheService::delFds($this->fd);
        $groups = GroupMember::getGroups(['user_number'=>$info['user']['number']]);
        if(!$groups->isEmpty()){
            foreach ($groups as $val){
                UserCacheService::delGroupFd($val->gnumber, $this->fd);
            }
        }
    }

    /*
     * 给在线好友发送离线提醒
     */
    private function offLine($user){
        $friends = FriendModel::getAllFriends($user['user']['id']);
        $friends = FriendService::getFriends($friends);
        $server = ServerManager::getInstance()->getServer();

        $data = [
            'type'      => 'ws',
            'method'    => 'friendOffLine',
            'data'      => [
                'number'    => $user['user']['number'],
                'nickname'  => $user['user']['nickname'],
            ]
        ];
        foreach ($friends as $val){
            if($val['online']){
                $fd = UserCacheService::getFdByNum($val['number']);
                $server->push($fd,json_encode($data));
            }
        }
    }


    private function getInfoByFd(){
        $token  = UserCacheService::getTokenByFd($this->fd);
        if(!$token){
            return [];
        }
        $user   = UserCacheService::getUserByToken($token);
        return [
            'token' => $token,
            'user'  => $user
        ];
    }
}