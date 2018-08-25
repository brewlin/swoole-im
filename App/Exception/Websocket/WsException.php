<?php
/**
 * Created by PhpStorm.
 * User: yuzhang
 * Date: 2018/4/14
 * Time: 下午4:17
 */

namespace App\Exception\Websocket;


class WsException extends BaseException
{
    protected $code = 400;
    protected $msg = 'ws 未知错误';
    protected $errorCode = 60000;
}