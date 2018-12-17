<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/14 0014
 * Time: 下午 17:27
 */

namespace App\Sock\Event;


use App\Process\KeepUser;
use App\Utility\RedisPool;
use EasySwoole\Core\Swoole\Coroutine\PoolManager;
use EasySwoole\Core\Swoole\Time\Timer;

class OnWorkStart
{
    public function onWorkerStart(\swoole_server $server ,$workerId)
    {
        if($workerId == 1)
        {
            $keepuser = new KeepUser();
            Timer::loop(10000, function ()use($keepuser) {
                $keepuser->run();
            });

        }else if($workerId >= 2)
        {
            echo "----------------------\n\n\n";
            PoolManager::getInstance()->addPool(RedisPool::class, 3, 20);
        }

    }

}