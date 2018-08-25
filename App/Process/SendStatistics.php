<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/3/6
 * Time: 下午12:14
 */

namespace App\Process;


use EasySwoole\Core\Swoole\Process\AbstractProcess;
use EasySwoole\Core\Swoole\ServerManager;
use Swoole\Process;
use EasySwoole\Core\Swoole\Time\Timer;

class SendStatistics extends AbstractProcess
{

    public function run(Process $process)
    {
        Timer::loop(2000, function () {
            $serv = ServerManager::getInstance()->getServer();
            $count = count($serv->connections);

            $start_fd = 0;
            $serv = ServerManager::getInstance()->getServer();
            $res = [
                'type' => 'ws',
                'method' => 'Statistics',
                'data' => [
                    'count' => $count
                ]
            ];
            while (true) {
                $conn_list = $serv->connection_list($start_fd, 10);
                if ($conn_list === false or count($conn_list) === 0) {
                    break;
                }
                $start_fd = end($conn_list);
                foreach ($conn_list as $fd) {
                    $status = $serv->connection_info($fd);
                    if ($status['websocket_status'] == 3) {
                        $serv->push($fd, json_encode($res));
                    }
                }
            }
        });
    }

    public function onShutDown()
    {

    }

    public function onReceive(string $str, ...$args)
    {

    }
}