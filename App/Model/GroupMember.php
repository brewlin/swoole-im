<?php
/**
 * Created by PhpStorm.
 * User: yuzhang
 * Date: 2018/4/18
 * Time: 下午7:24
 */

namespace App\Model;


use think\Model;

class GroupMember extends Model
{
    protected $hidden = ['id','creater_time'];

    public function info(){
        return $this->belongsTo('Group','gnumber','gnumber');
    }

    public static function newGroupMember($data){
        $model = new self();
        foreach ($data as $key => $val){
            $model->$key = $val;
        }
        $model->save();
    }

    public static function getGroups($where){
        return self::where($where)->with('info')->select();
    }
    public static function getGroupNames($where)
    {
        $res = [];
        $list = self::where($where)->with('info')->select();
        foreach ($list as $group)
        {
            $res[] = $group['info'];
        }
        return $res;

    }
    public static function getGroupMembers($gnumber)
    {
        return self::where('gnumber',$gnumber)->column('user_number');
    }

    /**
     * 删除群成员
     * @param $userNumber
     * @param $groupNumber
     */
    public static function delMemberById($userNumber , $groupNumber)
    {
        return self::where('user_number' , $userNumber)
                        ->where('gnumber' , $groupNumber)
                        ->delete();
    }
    public static function getNumberById($id)
    {
        $res = self::get($id);
        return $res['gnumber'];
    }
}