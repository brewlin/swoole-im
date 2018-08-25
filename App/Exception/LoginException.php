<?php
/**
 * Created by PhpStorm.
 * User: yuzhang
 * Date: 2018/4/13
 * Time: 下午10:13
 */

namespace App\Exception;


class LoginException extends BaseException
{
    public $code = 400;

    public $msg = '登录失败';

    public $errorCode = 30000;
}