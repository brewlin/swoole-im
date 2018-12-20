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

    /**
     * 更新已读消息
     */
    public function updateIsReadChatRecord()
    {
        (new \App\Validate\ChatRecord('read'))->goCheck($this->request());
        $where = ['to_id' => $this->user['id'],'uid' => $this->request()->getParsedBody('uid'),'is_read' => 0];
        $data = ['is_read' => 1];
        $type = $this->request()->getParsedBody('type');
        RecordServer::updateChatRecordIsRead($where,$data,$type);
        return $this->success([],'收取消息成功');
    }
}