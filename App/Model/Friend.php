<?php
/**
 * Created by PhpStorm.
 * User: yuzhang
 * Date: 2018/4/15
 * Time: 下午7:19
 */

namespace App\Model;


use think\Model;

class Friend extends Model
{
//    public function user(){
//        return $this->belongsTo('User','f_id','id');
//    }

    public static function getAllFriends($id)
    {
        $res1 = self::where('f_id','=',$id)->column('e_id');
        $res2 = self::where('e_id','=',$id)->column('f_id');
        $res = array_merge($res1, $res2);
        return $res;
    }

    public static function newFriend($f_id, $e_id){
        $model = new self();
        $model->f_id = $f_id;
        $model->e_id = $e_id;
        $model->save();
    }
}