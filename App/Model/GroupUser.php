<?php
/**
 * Created by PhpStorm.
 * User: yuzhang
 * Date: 2018/4/15
 * Time: 下午7:19
 */

namespace App\Model;


use think\Model;

class GroupUser extends Model
{
    public function list(){
        return $this->hasMany('GroupUserMember','groupid','id');
    }
    public static function getAllFriends($id)
    {
        $res = self::where('user_id',$id)
                    ->with('list')
                    ->select()->toArray();
        return $res;
    }

    /**
     * 添加分组
     * @param $userId 用户id
     * @param $groupname 分组名
     */
    public static function addGroup($userId , $groupname)
    {
        $data['user_id'] = $userId;
        $data['groupname'] = $groupname;
        $data['status'] = 1;
        return self::create($data);
    }

    /**
     * 修改分组名
     * @param $userId
     * @param $groupname
     */
    public static function editGroup($id , $groupname)
    {
        return self::update(['groupname' => $groupname],['id' => $id]);
    }
    /**
     * 删除分组名
     * 检查下面是否有好友
     * 将好友转移到默认分组去
     */
    public static function delGroup($id ,  $user)
    {
        $group = self::get($id);
        if($group['user_id'] != $user['id'])
        {
            return false;
        }
        $default = self::getDefaultGroupUser($user['id']);
        (new GroupUserMember())->where('user_id',$user['id'])
                               ->where('groupid' , $id)
                               ->update(['groupid' => $default['id']]);
        return self::destroy($id);
    }
    /**
     * 获取用户第一个分组信息
     */
    public static function getDefaultGroupUser($userId)
    {
        return self::where('user_id' , $userId)->order('id','asc')->find();
    }
}