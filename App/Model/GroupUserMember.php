<?php
/**
 * Created by PhpStorm.
 * User: yuzhang
 * Date: 2018/4/15
 * Time: 下午7:19
 */

namespace App\Model;


use think\Model;

class GroupUserMember extends Model
{
//    public function user(){
//        return $this->belongsTo('User','f_id','id');
//    }

    public static function getAllFriends($id)
    {
        return self::where('user_id',$id)->column('friend_id');
    }

    public static function newFriend($uId, $friendId , $groupId )
    {
        $model = new self();
        $model->user_id = $uId;
        $model->friend_id = $friendId;
        $model->groupid = $groupId;
        $model->save();
    }
    /**
     * 修改好友备注名
     */
    public static function editFriendRemarkName($uid , $friendId , $remark)
    {
        return self::where('user_id' , $uid)
                        ->where('friend_id' , $friendId)
                        ->update(['remark_name' => $remark]);
    }
    /**
     * 移动联系人
     * @param $uid 自己的id
     * @param $friendId 被移动的好友id
     * @param $groupid 移动的目标分组id
     */
    public static function moveFriend($uid , $friendId , $groupid)
    {
        return self::where('user_id' , $uid)
                    ->where('friend_id' , $friendId)
                    ->update(['groupid' => $groupid]);
    }
    public static  function removeFriend($uid , $fiendId)
    {
        return self::where('user_id' , $uid)
                        ->where('friend_id',$fiendId)
                        ->delete();
    }
}