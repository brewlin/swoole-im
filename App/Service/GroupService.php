<?php
/**
 * Created by PhpStorm.
 * User: yuzhang
 * Date: 2018/4/18
 * Time: 下午8:09
 */

namespace App\Service;


use App\Exception\Websocket\WsException;
use App\Model\Group as GroupModel;
use App\Model\GroupMember as GroupMemberModel;
use App\Task\Task;
use App\Task\TaskHelper;
use EasySwoole\Core\Component\Logger;
use EasySwoole\Core\Swoole\Task\TaskManager;

class GroupService
{
    public static function sendNewGroupInfo($g_info, $user){
        // 异步通知
        $toData = [
            'gname'  => $g_info['gname'],
            'ginfo'  => $g_info['ginfo'],
            'gnumber'=> $g_info['gnumber'],
        ];

        $taskData = (new TaskHelper('sendMsg', $user['fd'], 'newGroup', $toData))
            ->getTaskData();
        $taskClass = new Task($taskData);
        TaskManager::async($taskClass);
    }
}