<?php
namespace app\common\exception;

/**
*参数异常类 
*/
class ParameterException extends BaseException
{
	public $code=400;
	public $msg='param error';	
	public $errorCode=10006;	
}



