<?php
/**
 * Created by PhpStorm.
 * User: Yu
 * Date: 2018/4/13
 * Time: 16:53
 */

namespace App\HttpController\Api;
use App\Service\UserCacheService;
use EasySwoole\Core\Http\AbstractInterface\Controller;

class Base extends Controller
{
    public $user = null;
    public function index()
    {
        $this->response()->write('Hello easySwoole!');
    }

    /**
     * 进行token权限校验
     */
    public function onRequest($action):bool
    {
        $headerToken = null;
        if($this->request()->getHeader('Token'))
        {
            $headerToken = $this->request()->getHeader('token')[0];
        }
        $requestToken = $this->request()->getRequestParam('token');
        if($headerToken || $requestToken)
        {
            $token = $headerToken?$headerToken:$requestToken;
            $user = UserCacheService::getUserByToken($token);
            if($user)
            {
                $this->user = $user;
                return true;
            };
            $this->error([],'token非法');
            return false;
        }
        $this->error([],'缺少token');
        return false;
    }
    //公用返回方法
    public function success($data = null,$msg = null ,$statusCode = 0){
        if(!$this->response()->isEndResponse()){
            $data = Array(
                "code"=>$statusCode,
                "data"=>$data,
                "msg"=>$msg
            );
            $this->response()->write(json_encode($data,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
            $this->response()->withStatus(200);
            return true;
        }else{
            trigger_error("response has end");
            return false;
        }
    }
    //公用返回方法
    public function error($data = null,$msg = null ,$statusCode = 4){
        if(!$this->response()->isEndResponse()){
            $data = Array(
                "code"=>$statusCode,
                "data"=>$data,
                "msg"=>$msg
            );
            $this->response()->write(json_encode($data,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
            $this->response()->withStatus(400);
            return true;
        }else{
            trigger_error("response has end");
            return false;
        }
    }

}