<?php
/**
 * Created by PhpStorm.
 * User: yuzhang
 * Date: 2018/4/13
 * Time: 下午10:00
 */

namespace App\Model;


use think\Model;

class User extends Model
{
    protected $hidden = ['created_time'];

    public function getLastLoginAttr($value, $data){
        if(empty($value)){
            return '-';
        }
        return date('Y-m-d H:i:s',$value);
    }

    public static function getUser($where){
        return self::where($where)->find();
    }

    public static function newUser($data){
        $user = new self();
        foreach ($data as $key => $val){
            $user->$key = $val;
        }
        $user->save();
        return $user->id;
    }

    public static function updateUser($id,$data){
        return self::update($data, ['id' => $id]);
    }
    public static function getUserByNumbers($numbers)
    {
        $data = [];
        foreach ($numbers as $k => $v)
        {
            $data[] = self::where('number',$v)->find();
        }
        return $data;
    }
    public static function getNumberById($id)
    {
        $user = self::find($id);
        return $user['number'];
    }
    public static function getUserById($id)
    {
        return self::find($id);
    }
    public static function getAllUser()
    {
        return self::select();
    }
    public static function searchUser($value , $page = null)
    {
        if($page == null)
        {
            if(!$value)
                return self::select();
            return self::whereOr('number','like','%'.$value.'%')
                        ->whereOr('nickname','like','%'.$value.'%')
                        ->whereOr('phone','like','%'.$value.'%')
                        ->whereOr('email','like','%'.$value.'%')
                        ->select();
        }
        if(!$value)
            return self::limit(16)->page($page)->select();
        return self::whereOr('number','like','%'.$value.'%')
                    ->whereOr('nickname','like','%'.$value.'%')
                    ->whereOr('phone','like','%'.$value.'%')
                    ->whereOr('email','like','%'.$value.'%')
                    ->limit(16)->page($page)->select();
    }
}