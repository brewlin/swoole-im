<?php
/**
 * Created by PhpStorm.
 * User: yuzhang
 * Date: 2018/4/14
 * Time: 上午9:26
 */

namespace App\Service;


use App\Service\Common;
use EasySwoole\Config;

class Token
{
    /*
     * 生成token
     * 用三组字符串组合加密，生成唯一token
     * 1. 32个字符组成的随机字符串
     * 2. 时间戳
     * 3. 配置 salt 盐
     */
    protected static function generateToken(){
        $chars = Common::getRandChar(32);
        $timestamp = $_SERVER['REQUEST_TIME_FLOAT'];
        $token_salt = Config::getInstance()->getConf('setting.token_salt');
        return md5($chars.$timestamp.$token_salt);
    }
}