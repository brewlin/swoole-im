<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2018/8/18
 * Time: 11:27
 */

namespace App\Model;
use think\Model;

class MsgBox extends Model
{
    public function to()
    {
        return $this->belongsTo("User","to");
    }
    public function from()
    {
        return $this->belongsTo("User","from");
    }
    /**
     * 根据用户id获取消息
     */
    public static function getDataByUserId($userId)
    {
        $model = new self();
        return $model->whereOr('from',$userId)
                    ->whereOr('to',$userId)
                    ->with("to")
                    ->order('send_time','desc')
                    ->with("from")
                    ->select();
    }
    /**
     * 添加信息
     */
    public static function addMsgBox($data)
    {
        $msg = new self();
        $msg->save($data);
        return $msg->id;
    }
    public static function getDataById($id)
    {
        return self::find($id);
    }
    public static function updateById($id , $where)
    {
        return self::where('id' , $id)->update($where);
    }
    public static function updateByWhere($where ,$update)
    {
        return self::where($where)->update($update);
    }
    public static function getOneByWhere($where)
    {
        return self::where($where)->find();
    }
}