<?php
/**
 * Created by PhpStorm.
 * User: yuzhang
 * Date: 2018/4/13
 * Time: 下午7:45
 */

namespace App\Exception;


class BaseException extends \Exception
{
    // HTTP 状态码
    public $code = 400;

    // 错误信息
    public $msg = '未知错误';

    // 自定义错误码
    public $errorCode = 10000;

    public function __construct($params = [])
    {
        if(!is_array($params)){
            return;
        }
        if(array_key_exists('code', $params)){
            $this->code = $params['code'];
        }
        if(array_key_exists('msg', $params)){
            $this->msg = $params['msg'];
        }
        if(array_key_exists('errorCode', $params)){
            $this->errorCode = $params['errorCode'];
        }
    }
}