<?php
namespace app\common\exception;
use think\Exception;
class BaseException extends Exception{
	/**
	 * [$code 状态码 400,200]
	 * @var [type]
	 */
	public $code=400;
	public $msg='args error';
	public $errorCode=10000;

	public function __construct($params=[]){
		if(!is_array($params)){
			// throw new Exception('参数必须是数组');
			return;
		}
		if(array_key_exists('code', $params)){
			$this->code=$params['code'];
		}
		if(array_key_exists('msg', $params)){
			$this->msg=$params['msg'];
		}
		if(array_key_exists('errorCode', $params)){
			$this->errorCode=$params['errorCode'];
		}

	}

}
/**
* 
*/
