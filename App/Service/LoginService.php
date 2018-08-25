<?php
/**
 * Created by PhpStorm.
 * User: yuzhang
 * Date: 2018/4/14
 * Time: 下午1:18
 */

namespace App\Service;


class LoginService
{
    private $token;
    private $user;

    public function __construct($token , $user)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /*
     * 保存登陆后的初始信息
     * 存两个关联关系键值对
     * 1. token => userInfo
     * 2. uid => token
     */
    public function saveCache(){
        UserCacheService::saveNumToToken($this->user['number'], $this->token);
        UserCacheService::saveTokenToUser($this->token, $this->user);
    }

}