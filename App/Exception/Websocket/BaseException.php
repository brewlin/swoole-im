<?php
/**
 * Created by PhpStorm.
 * User: yuzhang
 * Date: 2018/4/14
 * Time: 下午4:06
 */

namespace App\Exception\Websocket;


class BaseException
{
    protected $code;
    protected $msg;
    protected $errorCode;

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

    public function getMsg(){
        return [
            'code' => $this->code,
            'msg' => $this->msg,
            'errorCode' => $this->errorCode,
        ];
    }
}