<?php
namespace app\common\exception;
class TokenException extends BaseException{
	public $code=401;
	public $msg='Token 已过期，或者无效token';
	public $errorCode=10001;
}