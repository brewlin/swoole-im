<?php
/**
 * Created by PhpStorm.
 * User: yuzhang
 * Date: 2018/4/14
 * Time: 下午5:18
 */

namespace App\Service;


use EasySwoole\Config;

class UserCacheService
{
    /*
     * 保存 token => userInfo
     */
    public static function saveTokenToUser($token, $user){
        if(!is_array($user)){
            $user = json_decode( json_encode( $user),true);
        }
        $user = [
            'id'        =>$user['id'],
            'number'    =>$user['number'],
            'nickname'  =>$user['nickname'],
            'username'  =>$user['username'],
            'sex'       =>$user['sex'],
            'avatar'    => $user['avatar'],
            'sign'      => $user['sign'],
            'last_login'=>$user['last_login'],
        ];
        $key = Config::getInstance()->getConf('setting.cache_name.token_user');
        $key = sprintf($key,$token);
        $redis_pool = new RedisPoolService();
        return $redis_pool->hMset($key,$user);
    }

    /*
     * 保存 number => token
     */
    public static  function saveNumToToken($number, $token){
        $key = Config::getInstance()->getConf('setting.cache_name.number_userOtherInfo');
        $key = sprintf($key,$number);
        $redis_pool = new RedisPoolService();
        return $redis_pool->hSet($key, 'token',$token);
    }

    /*
     * 根据number获取token
     */
    public static function getTokenByNum($number){
        $key = Config::getInstance()->getConf('setting.cache_name.number_userOtherInfo');
        $key = sprintf($key,$number);
        $redis_pool = new RedisPoolService();
        return $redis_pool->hGet($key,'token');
    }

    /*
     * 根据 token 获得 number 信息
     */
    public static function getNumByToken($token){
        $key = Config::getInstance()->getConf('setting.cache_name.token_user');
        $key = sprintf($key,$token);
        $redis_pool = new RedisPoolService();
        return $redis_pool->hGet($key,'number');
    }
    /**
     * 根据token获取id信息
     */
    public static function getIdByToken($token)
    {
        $res = self::getUserByToken($token);
        return $res['id'];
    }
    /*
     * 保存 number => fd
     */
    public static function saveNumToFd($number, $fd){
        $key = Config::getInstance()->getConf('setting.cache_name.number_userOtherInfo');
        $key = sprintf($key,$number);
        $redis_pool = new RedisPoolService();
        return $redis_pool->hSet($key, 'fd',$fd);
    }

    /*
     * 根据 number 获取 fd
     */
    public static function getFdByNum($number){
        $key = Config::getInstance()->getConf('setting.cache_name.number_userOtherInfo');
        $key = sprintf($key,$number);
        $redis_pool = new RedisPoolService();
        return $redis_pool->hGet($key, 'fd');
    }

    /*
     * 根据 token 获取所有 user 信息
     */
    public static function getUserByToken($token){
        $key = Config::getInstance()->getConf('setting.cache_name.token_user');
        $key = sprintf($key,$token);
        $redis_pool = new RedisPoolService();
        return $redis_pool->hGetAll($key);
    }

    /*
     * 保存好友请求的双方验证信息
     */
    public static function saveFriendReq($from_num, $to_num){
        $key = Config::getInstance()->getConf('setting.cache_name.friend_req');
        $key = sprintf($key,$from_num);
        $redis_pool = new RedisPoolService();
        return $redis_pool->set($key, $to_num);
    }

    /*
     * 获取好友验证
     */
    public static function getFriendReq($from_num){
        $key = Config::getInstance()->getConf('setting.cache_name.friend_req');
        $key = sprintf($key,$from_num);
        $redis_pool = new RedisPoolService();
        return $redis_pool->get($key);
    }

    /*
     * fd => token
     */
    public static function saveTokenByFd($fd, $token){
        $key = Config::getInstance()->getConf('setting.cache_name.fd_token');
        $key = sprintf($key,$fd);
        $redis_pool = new RedisPoolService();
        return $redis_pool->set($key, $token);
    }

    /*
     * 获取fd => token
     */
    public static function getTokenByFd($fd){
        $key = Config::getInstance()->getConf('setting.cache_name.fd_token');
        $key = sprintf($key,$fd);
        $redis_pool = new RedisPoolService();
        return $redis_pool->get($key);
    }

    public static function saveFds($fd){
        $key = Config::getInstance()->getConf('setting.cache_name.all_fd');
        $redis_pool = new RedisPoolService();
        return $redis_pool->sAdd($key, $fd);
    }

    public static function getFdFromSet(){
        $key = Config::getInstance()->getConf('setting.cache_name.all_fd');
        $redis_pool = new RedisPoolService();
        return $redis_pool->sRandMember($key);
    }

    public static function setGroupFds($gnumber,$fd){
        $key = Config::getInstance()->getConf('setting.cache_name.group_number_fd');
        $key = sprintf($key,$gnumber);
        $redis_pool = new RedisPoolService();
        return $redis_pool->lPush($key, $fd);
    }

    public static function getGroupFdsLen($gnumber){
        $key = Config::getInstance()->getConf('setting.cache_name.group_number_fd');
        $key = sprintf($key,$gnumber);
        $redis_pool = new RedisPoolService();
        return $redis_pool->lLen($key);
    }

    public static function getGroupFd($gnumber, $index){
        $key = Config::getInstance()->getConf('setting.cache_name.group_number_fd');
        $key = sprintf($key,$gnumber);
        $redis_pool = new RedisPoolService();
        return $redis_pool->lIndex($key, $index);
    }

    public static function delGroupFd($gnumber, $fd){
        $key = Config::getInstance()->getConf('setting.cache_name.group_number_fd');
        $key = sprintf($key,$gnumber);
        $redis_pool = new RedisPoolService();
        return $redis_pool->lRem($key, $fd);
    }
    /*
     * 销毁
     */
    public static function delTokenUser($token){
        $key = Config::getInstance()->getConf('setting.cache_name.token_user');
        $key = sprintf($key,$token);
        self::delHashKey($key);
    }

    public static function delNumberUserOtherInfo($number){
        $key = Config::getInstance()->getConf('setting.cache_name.number_userOtherInfo');
        $key = sprintf($key,$number);
        self::delHashKey($key);
    }

    public static function delFdToken($fd){
        $key = Config::getInstance()->getConf('setting.cache_name.fd_token');
        $key = sprintf($key,$fd);
        $redis_pool = new RedisPoolService();
        return $redis_pool->del($key);
    }

    public static function delFriendReq($from_num){
        $key = Config::getInstance()->getConf('setting.cache_name.friend_req');
        $key = sprintf($key,$from_num);
        $redis_pool = new RedisPoolService();
        return $redis_pool->del($key);
    }

    public static function delFds($fd){
        $key = Config::getInstance()->getConf('setting.cache_name.all_fd');
        $redis_pool = new RedisPoolService();
        return $redis_pool->sRem($key, $fd);
    }

    /*
     * 删除 hash 键下的所有值
     */
    private static function delHashKey($key){
        $redis_pool = new RedisPoolService();
        $res = $redis_pool->hKeys($key);
        if($res){
            foreach ($res as $val){
                $redis_pool->hDel($key, $val);
            }
        }

    }
}