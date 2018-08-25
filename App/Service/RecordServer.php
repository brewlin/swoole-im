<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2018/8/5 0005
 * Time: 12:06
 */

namespace App\Service;
use App\Model\ChatRecord;
use App\Model\Group;
use App\Model\GroupChatRecord;
class RecordServer
{
    /**
     * 获取好友 或者群聊天记录
     * @param $data type id $uid
     */
    public static function getAllChatRecordById($uid , $data)
    {
        if($data['type'] == 'friend')
        {
            return self::getFriendRecordById($uid , $data);
        }else if($data['type'] == 'group')
        {
            return self::getGroupRecordById($uid , $data);
        }else
        {
            return self::getFriendRecordById($uid , $data);
        }
    }

    /**
     * 获取好友的聊天记录
     * @param $data
     */
    public static function getFriendRecordById($uid , $data)
    {
        $list = ChatRecord::getAllChatRecordById($uid , $data['id']);
        return $list;
    }
    /**
     * 获取群的聊天记录
     */
    public static function getGroupRecordById($uid , $data)
    {
        $groupNumber = Group::getNumberById($data['id']);
        $list = GroupChatRecord::getAllChatRecordById($uid , $groupNumber);
        return $list;
    }

}