<?php
/**
 * Created by PhpStorm.
 * User: yuzhang
 * Date: 2018/4/13
 * Time: 下午8:36
 */

namespace App\Exception;


class ParameterException extends BaseException
{
    public $code = 400;

    public $msg = '参数异常';

    public $errorCode = 10001;
}