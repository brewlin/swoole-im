<?php
/**
 * Created by PhpStorm.
 * User: yuzhang
 * Date: 2018/4/16
 * Time: 下午9:15
 */

namespace App\Model;


use think\Model;

class ChatRecord extends Model
{
    public static $_instance = null;
    public static function getInstance()
    {
        if(empty(self::$_instance))
            self::$_instance = new self();
        return self::$_instance;
    }
    public function user(){
        return $this->belongsTo('User','uid','id');
    }

    public function touser(){
        return $this->belongsTo('User','to_id','id');
    }
    public static function updateByWhere($where ,$data)
    {
        return self::getInstance()->where($where)->update($data);
    }

    public static function newRecord($data)
    {
        $model = new self();
        foreach ($data as $key=>$value){
            $model->$key = $value;
        }
        $model->save();
    }
    public function username()
    {
        return $this->belongsTo('User','id')->bind('username');
    }
    public function avatar()
    {
        return $this->belongsTo('User','id')->bind('avatar');
    }
    public function getTimeStampAttr($value)
    {
        return strtotime($value)*1000;
    }
    /**
     * @param $current 当前用户的id
     * @param $toId    聊天对象的id
     * @return array
     */
    public static function getAllChatRecordById($current , $toId)
    {
        $model = new self();
        return $model->where(function($query)use($current,$toId){
                            $query->where('uid',$current)->where('to_id',$toId);
                        })
                      ->whereOr(function($query)use($current , $toId){
                            $query->where('uid',$toId)->where('to_id',$current);
                      })
                      ->with('username')
                      ->with('avatar')
                      ->select(function($query){
                         $query->field(['uid' => 'id','created_time'=>'timestamp','data'=>'content']);
                      });
    }
    /**
     * 查看未读聊天记录
     */
    public static function  getAllNoReadRecord($uid)
    {
        $model = new self();
        return $model->where(['to_id' => $uid,'is_read' => 0])
                      ->with('user')
                      ->with('touser')
                      ->select();
    }
}