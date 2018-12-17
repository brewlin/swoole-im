SWOOLE_IM 基于websocket实时通讯平台
==============
1.概述
--------------
+ 基于swoole底层的easyswoole框架
+ 搭建有httpsever后台admin
+ 独立websocket中心服务处理数据
+ 异步任务机制、异步redis池
+ 本项目前端使用layim 搭建，借鉴了菜单插件contextmenu
+ 请使用swoole扩展2.1.3 以及php 7.1
+ 快速开始
    - composer update
    - 编译swoole的时候需开启异步reids client 
        - ./configure --enable-async-redis
    - 修改 config配置文件 端口等信息
    - php index.php start 开启服务
+ 项目地址 http://im.huido.site 可以注册


2.架构图
--------------
![](http://talk.huido.site/swoole-im.png)

3.后台开发
--------------

    
4.websocket服务器开发
-----------


5.开发进度 && 实现功能
----------
> 好友单聊

> 添加好友

> 发送图片 文件视频等。。并解析

> 群聊

> websocket token 机制

> 分组添加 分组名（修改，删除 移动好友）

> 好友右键菜单操作功能

    - 发送好友信息
    - 查看好友资料
    - 查看好友聊天记录
    - 好友备注功能
    - 移动好友分组
    - 删除好友功能
> 发现中心

    - 搜索好友
    - 推荐好友 添加好友
> 消息中心

    - 好友离线上线通知
    - 系统消息推送
    - 好友添加申请通知 以及交互操作


6.开发模块
----------
> 带有完整的后台管理
>
> 带有完整的中心服服务
>
> 带有独立的api模块

