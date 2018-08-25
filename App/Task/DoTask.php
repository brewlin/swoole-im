<?php
/**
 * Created by PhpStorm.
 * User: yuzhang
 * Date: 2018/4/14
 * Time: 上午10:03
 */

namespace App\Task;

use App\Service\UserCacheService;
use EasySwoole\Core\Swoole\ServerManager;

class DoTask
{
    public static function sendMsg($data){
        $fd = $data['fd'];
        $res = $data['data'];
        $server = ServerManager::getInstance()->getServer();
        return $server->push($fd,json_encode($res));
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