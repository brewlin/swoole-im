<?php
/**
 * Created by PhpStorm.
 * User: yuzhang
 * Date: 2018/4/16
 * Time: 下午9:15
 */

namespace App\Model;


class GroupChatRecord extends BaseModel
{

    public static function newRecord($data)
    {
        $model = new self();
        foreach ($data as $key=>$value){
            $model->$key = $value;
        }
        $model->save();
    }
    /**
     * @param $current 当前用户的id
     * @param $toId    群对象的id
     * @return array
     */
    public static function getAllChatRecordById($uid , $id)
    {
        $model = new self();
        return $model->where('uid' , $uid)->where('gnumber' ,$id)
            ->with('username')
            ->with('avatar')
            ->select(function($query){
                $query->field(['uid' => 'id','created_time'=>'timestamp','data'=>'content']);
            });
    }
}