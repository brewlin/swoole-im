<?php
/**
 * Created by PhpStorm.
 * User: yuzhang
 * Date: 2018/4/14
 * Time: 下午4:07
 */

namespace App\Exception\Websocket;


class GroupException extends BaseException
{
    protected $code = 400;
    protected $msg = '群组操作失败';
    protected $errorCode = 40000;
}