<?php
namespace app\common\exception;
use think\Exception;
use think\exception\Handle;
use think\Request;
use think\Log;
class ExceptionHandler extends Handle{
	private $code;
	private $msg;
	private $errorCode;
	//返回客户端当前请求的url路径
	private $url;

	public function render(\Exception $e){
		if($e instanceof BaseException){
			$this->code=$e->code;
			$this->msg=$e->msg;
			$this->errorCode=$e->errorCode;
		}else{
			if(config('api_debug'))
			{
				return parent::render($e);

			}
			$this->code=500;
			$this->msg='server is wrong,not your bussiness';
			$this->errorCode=999;
			$this->recordErrorLog($e);
		}
		$request=Request::instance();
		$result=[
			'msg'=>$this->msg,
			'errorCode'=>$this->errorCode,
			'request_url'=>$request->url()
		];
		return json($result,$this->code);
	}
	/**
	 * [recordErrorLog 用于全局定义记录日志功能]
	 * @param  \Exception $e [get a exception class] 
	 * @return [type]        [description]
	 * @author xiaodo 2017-12-18
	 */
	private function recordErrorLog(\Exception $e){
		Log::init([
			'type'=>'File',
			'path'=>LOG_PATH,
			'level'=>['error']
		]);
		Log::record($e->getMessage(),'error');
	}
}