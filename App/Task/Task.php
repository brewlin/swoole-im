<?php
/**
 * Created by PhpStorm.
 * User: yuzhang
 * Date: 2018/4/14
 * Time: 上午9:48
 */

namespace App\Task;


use EasySwoole\Core\Swoole\Task\AbstractAsyncTask;

class Task extends AbstractAsyncTask
{
    /*
     * $taskData = [
     *      'method' => '',
     *      'data'  => ''
     * ];
     */
    function run($taskData, $taskId, $fromWorkerId)
    {
        $method = $taskData['method'];
        return DoTask::$method($taskData['data']);
    }

    function finish($result, $task_id)
    {
        // 任务执行完的处理
    }

}