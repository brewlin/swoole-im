<?php
/**
 * Created by PhpStorm.
 * User: yuzhang
 * Date: 2018/4/16
 * Time: 下午9:20
 */

namespace App\Service;


use App\Model\ChatRecord;
use App\Task\Task;
use App\Task\TaskHelper;
use EasySwoole\Core\Swoole\ServerManager;
use EasySwoole\Core\Swoole\Task\TaskManager;

class ChatService
{
//    /*
//     *  发送聊天消息
//     *  异步，做标记是自己的还是对方发的
//     */
//    public static function sendPersonalMsg($data){
//        // 给自己发
//        $myData = [
//            'time'  => date("H:i:s", time()),
//            'flag'  => 1,                       // 1自己的消息 ，2对方的消息
//            'data'  => $data['data'],
//            'number'=> $data['to']['user']['number']    // 跟谁聊
//        ];
//        $taskData = (new TaskHelper('sendMsg', $data['from']['fd'], 'chat', $myData))
//            ->getTaskData();
//        $taskClass = new Task($taskData);
//        TaskManager::async($taskClass);
//
//        // 给对方发
//        $toData = [
//            'time'  => date("H:i:s", time()),
//            'flag'  => 2,                       // 1自己的消息 ，2对方的消息
//            'data'  => $data['data'],
//            'number'=> $data['from']['user']['number']  // 哪来的
//        ];
//        $taskData = (new TaskHelper('sendMsg', $data['to']['fd'], 'chat', $toData))
//            ->getTaskData();
//        $taskClass = new Task($taskData);
//        TaskManager::async($taskClass);
//    }
    /*
     *  发送聊天消息
     *  异步，做标记是自己的还是对方发的
     */
    public static function sendPersonalMsg($data){
//        // 给自己发
//        $myData = [
//            'username' => $data['to']['user']['username'],
//            'avatar' => $data['to']['user']['avatar'],
//            'id' => $data['to']['user']['id'],
//            'type' => 'friend',//聊天类型，好友聊天
//            'mine'  => true,                       // true自己的消息 ，false对方的消息
//            'fromid' => $data['to']['user']['id'],
//            'content'  => $data['data'],
//            'timestamp' => time()*1000,
//            'number'=> $data['to']['user']['number']    // 跟谁聊
//        ];
//        $taskData = (new TaskHelper('sendMsg', $data['from']['fd'], 'chat', $myData))
//            ->getTaskData();
//        $taskClass = new Task($taskData);
//        TaskManager::async($taskClass);

        // 给对方发
        $toData = [
            'username' => $data['from']['user']['username'],
            'avatar' => $data['from']['user']['avatar'],
            'id' => $data['from']['user']['id'],
            'type' => 'friend',//聊天类型，好友聊天
            'mine'  => false,                       // true自己的消息 ，false对方的消息
            'fromid' => $data['from']['user']['id'],
            'content'  => $data['data'],
            'timestamp' => time()*1000,
            'number'=> $data['from']['user']['number'],  // 哪来的
        ];
        $taskData = (new TaskHelper('sendMsg', $data['to']['fd'], 'chat', $toData))
            ->getTaskData();
        $taskClass = new Task($taskData);
        TaskManager::async($taskClass);
    }

    /**
     * 发送离线消息
     * @param $data
     */
    public static function sendOfflineMsg($fd ,$sendData)
    {
        $fromData = [];
        foreach ($sendData as $data)
        {
            $toData = [
                'username' => $data['from']['user']['username'],
                'avatar' => $data['from']['user']['avatar'],
                'id' => $data['from']['user']['id'],
                'type' => 'friend',//聊天类型，好友聊天
                'mine'  => false,                       // true自己的消息 ，false对方的消息
                'fromid' => $data['from']['user']['id'],
                'content'  => $data['data'],
                'timestamp' => time()*1000,
                'number'=> $data['from']['user']['number'],  // 哪来的
            ];
            $fromData[] = $toData;

        }
        $taskData = (new TaskHelper('sendOfflineMsg', $fd, 'chat', $fromData))
            ->getTaskData();
        $taskClass = new Task($taskData);
        TaskManager::async($taskClass);


    }
    /*
     * 存储消息记录
     */
    public static function savePersonalMsg($data){
        $taskData = [
            'method' => 'saveMysql',
            'data'  => [
                'class'    => 'App\Model\ChatRecord',
                'method'   => 'newRecord',
                'data'     => [
                    'uid'       => $data['from']['user']['id'],
                    'to_id'     => $data['to']['user']['id'],
                    'data'      => $data['data'],
                    'is_read' => $data['is_read']
                ]
            ]
        ];
        $taskClass = new Task($taskData);
        TaskManager::async($taskClass);
    }

    /*
     * 发送群组聊天记录
     */
    public static function sendGroupMsg($data){
        $res = [
            'method'    => 'groupChat',
            'type'      => 'ws',
            'data'      => [
                'time'  => date("H:i:s", time()),
                'groupNumber'  => $data['gnumber'],
                'msg'   => $data['data'],
                'user'  => $data['user']['user'],
                'flag'  => 2
            ]
        ];
        $myres = [
            'method'    => 'groupChat',
            'type'      => 'ws',
            'data'      => [
                'time'  => date("H:i:s", time()),
                'groupNumber'  => $data['gnumber'],
                'msg'   => $data['data'],
                'user'  => $data['user']['user'],
                'flag'  => 1
            ]
        ];

        $myfd = $data['user']['fd'];
        $len = UserCacheService::getGroupFdsLen($data['gnumber']);
        $serv = ServerManager::getInstance()->getServer();
        for($i=0; $i<$len; $i++){
            $fd = UserCacheService::getGroupFd($data['gnumber'], $i);
            if($fd!=$myfd){
                $serv->push($fd, json_encode($res));
            }else{
                $serv->push($fd, json_encode($myres));
            }
        }
    }

    // 存储群组消息
    public static function saveGroupMsg($data){
        $taskData = [
            'method' => 'saveMysql',
            'data'  => [
                'class'    => 'App\Model\GroupChatRecord',
                'method'   => 'newRecord',
                'data'     => [
                    'uid'       => $data['user']['user']['id'],
                    'gnumber'   => $data['gnumber'],
                    'data'      => $data['data']
                ]
            ]
        ];
        $taskClass = new Task($taskData);
        TaskManager::async($taskClass);
    }

}