<?php
/**
 * Created by PhpStorm.
 * User: yuzhang
 * Date: 2018/4/16
 * Time: 下午8:21
 */

namespace App\Task;


class TaskHelper
{
    private $method;
    private $fd;
    private $web_method;
    private $data;

    public function __construct($method, $fd, $web_method, $data)
    {
        $this->method = $method;
        $this->fd = $fd;
        $this->web_method = $web_method;
        $this->data = $data;
    }

    public function getTaskData(){
        $taskData = [
            'method' => $this->method,
            'data'  => [
                'fd'    => $this->fd,
                'data'  => [
                    'type'      => 'ws',
                    'method'    => $this->web_method,
                    'data'      => $this->data
                ]
            ]
        ];
        return $taskData;
    }
}