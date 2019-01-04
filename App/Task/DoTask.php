<?php
/**
 * Created by PhpStorm.
 * User: yuzhang
 * Date: 2018/4/14
 * Time: 上午10:03
 */

namespace App\Task;

use App\Service\RedisPoolService;
use App\Service\UserCacheService;
use App\Service\UserService;
use App\Utility\Redis;
use App\Utility\RedisPool;
use EasySwoole\Core\Swoole\Coroutine\PoolManager;
use EasySwoole\Core\Swoole\ServerManager;
use EasySwoole\Core\Swoole\Time\Timer;

class DoTask
{
    public static function sendMsg($data){
        $fd = $data['fd'];
        $res = $data['data'];
        $server = ServerManager::getInstance()->getServer();
        return $server->push($fd,json_encode($res));
    }
    public static function sendOfflineMsg($data)
    {
        $server = ServerManager::getInstance()->getServer();
        $fd = $data['fd'];
        $sendData = $data['data'];
        $server->after(3000, function () use ($server,$fd,$sendData) {
            foreach ($sendData['data'] as $res)
            {
                $tmp = [];
                $tmp['type'] = $sendData['type'];
                $tmp['method'] = $sendData['method'];
                $tmp['data'] = $res;
                $server->push($fd,json_encode($tmp));
            }
        });
        return true;

    }

    /**
     * @param $data
     * 公用执行方法
     */
    public static function doJob($data)
    {
        $model = new $data['class'];
        $method = $data['method'];
        $model->$method($data['data']);

    }
    public static function saveMysql($data){
        $model = new $data['class'];
        $method = $data['method'];
        $model::$method($data['data']);
    }

    public static function sendToALl($data){
        $serv = ServerManager::getInstance()->getServer();
        $start_fd = 0;
        $myfd = $data['fd'];
        unset($data['fd']);
        while(true)
        {
            $conn_list = $serv->connection_list($start_fd, 10);
            if($conn_list===false or count($conn_list) === 0)
            {
                break;
            }
            $start_fd = end($conn_list);
            foreach($conn_list as $fd)
            {
                if($myfd!=$fd){
                    $status = $serv->connection_info($fd);
                    if($status['websocket_status']==3){
                        $serv->push($fd, json_encode($data));
                    }
                }

            }
        }
    }


}