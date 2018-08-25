<?php
/**
 * Created by PhpStorm.
 * User: yuzhang
 * Date: 2018/4/14
 * Time: 下午4:14
 */

namespace App\Exception\Websocket;


class TokenException extends BaseException
{
    protected $code = 400;
    protected $msg = 'token 异常';
    protected $errorCode = 50000;
}