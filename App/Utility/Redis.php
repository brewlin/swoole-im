<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/20 0020
 * Time: 上午 11:27
 */

namespace App\Utility;


use EasySwoole\Config;

class Redis
{
    public static $_instance = null;
    public static function getInstance()
    {
        if(empty(self::$_instance))
            self::$_instance = self::createObject();
        return self::$_instance;
    }
    public static function createObject()
    {
        $conf = Config::getInstance()->getConf('REDIS');
        $redis = new \EasySwoole\Core\Swoole\Coroutine\Client\Redis($conf['host'], $conf['port'], $conf['serialize'], $conf['auth']);
        if (is_callable($conf['errorHandler'])) {
            $redis->setErrorHandler($conf['errorHandler']);
        }
        return $redis;
    }

}