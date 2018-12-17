<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/3/6
 * Time: 下午12:14
 */

namespace App\Process;


use App\Model\User;
use App\Service\UserCacheService;
use App\Service\UserService;
use App\Task\Task;
use App\Utility\RedisPool;
use EasySwoole\Core\Swoole\Coroutine\PoolManager;
use EasySwoole\Core\Swoole\Process\AbstractProcess;
use EasySwoole\Core\Swoole\ServerManager;
use EasySwoole\Core\Swoole\Task\TaskManager;
use Swoole\Process;
use EasySwoole\Core\Swoole\Time\Timer;

/**
 * Class KeepUser
 * @package App\Process
 * 定时任务，统计哪些不在线的用户删除其缓存
 */
class KeepUser
{

    public function run()
    {
            $serv = ServerManager::getInstance()->getServer();
            $allUser = User::getAllUser(['status' => 1]);
            $userService = new UserService();
            foreach ($allUser as $k => $v)
            {
               $fd = UserCacheService::getFdByNum($v['number']);
               if(!$serv->getClientInfo($fd))
               {
                   $token  = UserCacheService::getTokenByNum($v['number']);
                   $user   = UserCacheService::getUserByToken($token);
                   $taskData = [
                       'method' => 'doJob',
                       'data'  => [
                           'class'    => 'App\Service\UserService',
                           'method'   => 'delUserToken',
                           'data'     => [
                               'user' => $user,
                               'token' => $token,
                           ]
                       ]
                   ];
                   $userService->delUserToken(['user' => $user,'token' => $token]);
                   /**
                   $taskClass = new Task($taskData);
                   TaskManager::async($taskClass);
                    * */
               }
            }
    }

}