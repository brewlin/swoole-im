<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2018/7/28
 * Time: 23:21
 */

namespace App\HttpController\Api;
use App\Service\RecordServer;
class ChatRecord extends Base
{
    public function getChatRecordByToken()
    {
        (new \App\Validate\ChatRecord('record'))->goCheck($this->request());
        $res = RecordServer::getAllChatRecordById($this->user['id'] , $this->request()->getRequestParam());
        return $this->success($res);

    }
}