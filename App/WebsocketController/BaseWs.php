<?php
/**
 * Created by PhpStorm.
 * User: yuzhang
 * Date: 2018/4/14
 * Time: 下午3:41
 */

namespace App\WebsocketController;


use App\Exception\Websocket\FriendException;
use App\Exception\Websocket\TokenException;
use App\Exception\Websocket\WsException;
use App\Model\User as UserModel;
use App\Service\UserCacheService;
use EasySwoole\Core\Socket\AbstractInterface\WebSocketController;
use EasySwoole\Core\Swoole\ServerManager;

class BaseWs extends WebSocketController
{
    function actionNotFound(?string $actionName)
    {
        $data = (new WsException([
            'msg' => '请求方法不存在',
            'errorCode' => 60001
        ]))->getMsg();
        $this->response()->write(json_encode($data));
    }

    // 验证 token
    protected function onRequest(?string $actionName): bool
    {
        $content = $this->request()->getArg('content');
        if(!isset($content['token']) || !UserCacheService::getNumByToken($content['token'])){
            $err = (new TokenException())->getMsg();
            $this->response()->write(json_encode($err));
            return false;
        }
        return true;
    }

    /*
     * 获取当前用户的信息
     */
    protected function getUserInfo(){
        $content = $this->request()->getArg('content');
        $token = $content['token'];
        $fd = $this->client()->getFd();
        $user = UserCacheService::getUserByToken($token);
        if(empty($user)){
            return false;
        }
        $data = [
            'token' => $content['token'],
            'fd'    => $fd,
            'user'  => $user
        ];

        return $data;
    }

    /*
     * 向用户发送格式化后的消息
     */
    protected function sendMsg($params =[]){
        $data = [
            'type'      => 'ws',
            'method'    => 'ok',
            'data'      => 'ok'
        ];
        if(array_key_exists('type',$params)){
            $data['type'] = $params['type'];
        }
        if(array_key_exists('method',$params)){
            $data['method'] = $params['method'];
        }
        if(array_key_exists('data',$params)){
            $data['data'] = $params['data'];
        }
        $this->response()->write(json_encode($data));
    }

    /*
     * 通过 id 验证用户是否在线，以及是否存在
     */
    protected function onlineValidate($toId){
        $ishas = UserModel::getUser(['id' => $toId]);
        if(!$ishas){
            $data = (new FriendException([
                'msg' => '用户不存在',
                'errorCode' => 40002
            ]))->getMsg();
            return $data;
        }
        $fd = UserCacheService::getFdByNum($ishas['number']);
        if(!$fd){
            $data = (new FriendException([
                'msg' => '用户暂时不在线',
                'errorCode' => 40001
            ]))->getMsg();
            return $data;
        }
        $user = [
            'fd'    => $fd,
            'user'  => $ishas
        ];
        return $user;
    }
}