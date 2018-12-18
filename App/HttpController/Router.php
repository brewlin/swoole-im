<?php

namespace App\HttpController;


use FastRoute\Route;
use FastRoute\RouteCollector;

class Router extends \EasySwoole\Core\Http\AbstractInterface\Router
{
    function register(RouteCollector $routeCollector)
    {
        $routeCollector->post('/register', '/Api/Login/register');

        $routeCollector->post('/login', '/Api/Login/login');
        $routeCollector->get('/api/im/init', '/Api/Init/init');
        $routeCollector->get('/api/im/members','/Api/GroupMember/getMembers');
        $routeCollector->post('/api/im/image','/Api/Tool/uploadImage');
        $routeCollector->get('/api/im/record','/Api/ChatRecord/getChatRecordByToken');

        /**
         * 聊天路由
         */
        $routeCollector->addGroup('/api/im/chat',function(RouteCollector$r){

            $r->addRoute('POST','/record/read','/Api/ChatRecord/updateIsReadChatRecord');
        });
        /**
         * 分组路由
         */
        $routeCollector->addGroup("/api/im/group",function(RouteCollector $r){
            $r->addRoute('GET','/user/add','/Api/GroupUser/addMyGroup');//添加我的分组
            $r->addRoute('GET','/user/edit','/Api/GroupUser/editMyGroup');//修改我的分组名
            $r->addRoute('GET','/user/del','/Api/GroupUser/delMyGroup');//删除我的分组名
        });
        /**
         * 用户路由
         */
        $routeCollector->addGroup('/api/im/user',function(RouteCollector $r){
           $r->addRoute('GET','/friend/info','/Api/User/GetInformation');
           $r->addRoute('POST','/friend/remark','/Api/GroupUserMember/editFriendRemarkName');
           $r->addRoute('POST','/friend/move','/Api/GroupUserMember/moveFriendToGroup');
           $r->addRoute('POST','/friend/remove','/Api/GroupUserMember/removeFriend');//删除好友
            //获取推荐好友
            $r->addRoute('GET','/friend/recommend','/Api/GroupUserMember/getRecommendFriend');
            //退出
            $r->addRoute('GET','/user/quit','/Api/User/userQuit');
            //用户修改签名
            $r->addRoute('GET','/user/sign','/Api/User/editSignature');
            //查找总数
            $r->addRoute('GET','/find/total','/Api/User/findFriendTotal');
            //查找好友或者群
            $r->addRoute('GET','/find/friend','/Api/User/findFriend');
        });
        /**
         * 群组路由
         */
        $routeCollector->addGroup('/api/im/team',function(RouteCollector $r){
            //退出群组
            $r->addRoute('GET' , '/group/leave','/Api/GroupMember/leaveGroup');
            //检查用户是否可以创建群
            $r->addRoute('GET' , '/group/check','/Api/GroupMember/checkUserCreateGroup');
            //创建群
            $r->addRoute('POST' , '/group/create','/Api/GroupMember/createGroup');
        });
        /**
         * 消息路由
         */
        $routeCollector->addGroup('/api/im/msg',function(RouteCollector $r){
           //获取自己的消息中心
            $r->addRoute('GET','/box/info','/Api/MsgBox/getPersonalMsgBox');
        });
    }
}
