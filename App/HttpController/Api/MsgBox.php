<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2018/8/18
 * Time: 14:17
 */

namespace App\HttpController\Api;
use App\Model\MsgBox as MsgBoxModel;
class MsgBox extends Base
{
    /**
     * 获取用户的消息中心
     */
    public function getPersonalMsgBox()
    {
        //返回form和to都为自己的信息
        $res = MsgBoxModel::getDataByUserId($this->user['id']);
        return $this->success($res,$this->user);
    }
}