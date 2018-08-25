<?php
/**
 * Created by PhpStorm.
 * User: yuzhang
 * Date: 2018/4/13
 * Time: 下午7:45
 */

namespace App\Exception;


use EasySwoole\Config;
use EasySwoole\Core\Component\Logger;
use EasySwoole\Core\Http\AbstractInterface\ExceptionHandlerInterface;
use EasySwoole\Core\Http\Request;
use EasySwoole\Core\Http\Response;

class ExceptionHandel implements ExceptionHandlerInterface
{
    private $code;
    private $msg;
    private $errorCode;

    public function handle( \Throwable $exception, Request $request, Response $response )
    {
        if( $exception instanceof BaseException ){
            // 自定义异常，返回给客户具体信息
            $this->code = $exception->code;
            $this->msg  = $exception->msg;
            $this->errorCode = $exception->errorCode;
        }else{
            $debug = Config::getInstance()->getConf('DEBUG');
            $this->code =500;
            $this->errorCode = 999;
            $this->recordErrorLog($exception);
            if($debug){
                $this->msg = $exception->getMessage();
            }else{
                $this->msg = '服务器错误';
                $this->recordErrorLog($exception);
            }
        }
        $this->returnJson($response);
    }

    private function recordErrorLog(\Throwable $exception){
        $msg = json_encode($exception->getMessage());
        Logger::getInstance()->log($msg,'LTalk_debug');
    }

    private function returnJson($response){
        $data = Array(
            "msg" => $this->msg,
            "errorCode" => $this->errorCode,
            'code' => 1
        );
        $response->write(json_encode($data,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
        $response->withHeader('Content-type','application/json;charset=utf-8');
        $response->withStatus($this->code);
    }
}