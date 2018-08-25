<?php
namespace app\common\exception;

class WeChatException extends BaseException{
	public $code = 400;
	public $msg = '微信服务器接口调用失败';
	public $errorCode = 999;
}