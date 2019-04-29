<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2017/12/30
 * Time: 下午10:59
 */

return [
    'MAIN_SERVER'=>[
        'HOST'=>'0.0.0.0',
        'PORT'=>8080,
        'SERVER_TYPE'=>\EasySwoole\Core\Swoole\ServerManager::TYPE_WEB_SOCKET_SERVER,
        'SOCK_TYPE'=>SWOOLE_TCP,//该配置项当为SERVER_TYPE值为TYPE_SERVER时有效
        'RUN_MODEL'=>SWOOLE_PROCESS,
        'SETTING'=>[
            'task_worker_num' => 2, //异步任务进程
            'task_max_request'=>10,
            'max_request'=>2500,//强烈建议设置此配置项
            'worker_num'=>2,
            'document_root' => '/website/swoole-im/Public',  // 静态资源目录
            'enable_static_handler' => true,
//            'heartbeat_idle_time' => 60,
//            'heartbeat_check_interval' => 20,
        ],
    ],
    'DEBUG'=>true,
    'TEMP_DIR'=>EASYSWOOLE_ROOT.'/Temp',
    'LOG_DIR'=>EASYSWOOLE_ROOT.'/Log',
    'EASY_CACHE'=>[
        'PROCESS_NUM'=>3,//若不希望开启，则设置为0
        'PERSISTENT_TIME'=>5//如果需要定时数据落地，请设置对应的时间周期，单位为秒
    ],
    'CLUSTER'=>[
        'enable'=>false,
        'token'=>null,
        'broadcastAddress'=>['255.255.255.255:9556'],
        'listenAddress'=>'0.0.0.0',
        'listenPort'=>9556,
        'broadcastTTL'=>5,
        'serviceTTL'=>10,
        'serverName'=>'easySwoole',
        'serverId'=>null
    ],
    'MYSQL'=>[
        'HOST'=>'127.0.0.1',
        'USER'=>'',
        'PASSWORD'=>'',
        'DB_NAME'=>'talk'
    ],

    'REDIS' => [
        'host' => '127.0.0.1', // redis主机地址
        'port' => 6379, // 端口
        'serialize' => false, // 是否序列化php变量
        'auth' => null, // 密码
        'pool' => [
            'min' => 5, // 最小连接数
            'max' => 100 // 最大连接数
        ],
        'errorHandler' => function(){
            return null;
        }
    ],

    'database' => [
        // 数据库类型
        'type'            => 'mysql',
        // 服务器地址
        'hostname'        => '127.0.0.1',
        // 数据库名
        'database'        => 'talk',
        // 用户名
        'username'        => 'xiaodo',
        // 密码
        'password'        => 'xiaodo',
        // 端口
        'hostport'        => '3306',
        // 数据库表前缀
        'prefix'          => '',
        // 是否需要断线重连
        'break_reconnect' => true,
    ],

    'setting' => [
        'token_salt' => 'gye76qwei23eq',
        'WebSocketControllerPath' => 'App\WebsocketController\\',
        'cache_name' => [
            'token_user' => 'user:token:%s:data',                           //  token   => user 信息， 用户登录时创建
            'number_userOtherInfo' =>'userOtherInfo:%s:number:data',        //  number  => fd，token 等信息，用户登录时创建
            'fd_token' => 'fd:%s:data',                                     //  fd => token,    用户登录时创建
            'friend_req' => 'friend:fromNumber:%s:data',                    //  from_num => to_num，发送好友请求时创建，对方拒绝或同意时销毁
            'group_number_fd' => 'group:number:%s:data',                    //  gnumber => fds，创建新群组时创建
        ],
    ]
];
