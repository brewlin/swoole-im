<?php

namespace EasySwoole;

use App\Process\SendStatistics;
use App\Sock\Parser\OnClose;
use App\Utility\RedisPool;
use \EasySwoole\Core\AbstractInterface\EventInterface;
use EasySwoole\Core\Component\Di;
use EasySwoole\Core\Component\SysConst;
use \EasySwoole\Core\Swoole\EventHelper;
use EasySwoole\Core\Swoole\Coroutine\PoolManager;
use EasySwoole\Core\Swoole\Process\ProcessManager;
use \EasySwoole\Core\Swoole\ServerManager;
use \EasySwoole\Core\Swoole\EventRegister;
use \EasySwoole\Core\Http\Request;
use \EasySwoole\Core\Http\Response;
use think\Db;
use \EasySwoole\Core\Utility\File;

/**
 * 全局事件定义文件
 * Class EasySwooleEvent
 * @package EasySwoole
 */
Class EasySwooleEvent implements EventInterface
{

    /**
     * 框架初始化事件
     * 在Swoole没有启动之前 会先执行这里的代码
     */
    static public function frameInitialize(): void
    {
        // 设置全局异常处理类
        Di::getInstance()->set( SysConst::HTTP_EXCEPTION_HANDLER, \App\Exception\ExceptionHandel::class );
        // 获得数据库配置
        $dbConf = Config::getInstance()->getConf('database');
        Db::setConfig($dbConf);
        self::loadConf(EASYSWOOLE_ROOT.'/Conf');

        date_default_timezone_set('Asia/Shanghai');
    }
    /**
     * load the conf
     */
    static public function loadConf($confPath)
    {
        $Conf  = Config::getInstance();
        $files = File::scanDir($confPath);
        foreach ($files as $file) {
            $data = require_once $file;
            $Conf->setConf(strtolower(basename($file, '.php')), (array)$data);
        }
    }

    /**
     * 创建主服务
     * 除了主服务之外还可以在这里创建额外的端口监听
     * @param ServerManager $server
     * @param EventRegister $register
     */
    static public function mainServerCreate(ServerManager $server, EventRegister $register): void
    {
        if (version_compare(phpversion('swoole'), '2.1.0', '>=')) {
            PoolManager::getInstance()->addPool(RedisPool::class, 3, 10);
        }

        // 添加 onMessage 的处理方式
        EventHelper::registerDefaultOnMessage($register, "App\Sock\Parser\WebSock");

        // 监听 onclose 事件
        $register->add($register::onClose, function (\swoole_server $server, $fd, $reactorId ) {
            (new OnClose($fd))->close();
        });

        ProcessManager::getInstance()->addProcess('SendStatistics', SendStatistics::class);
    }

    static public function onRequest(Request $request, Response $response): void
    {
        $response->withHeader('Access-Control-Allow-Origin', '*');
        $response->withHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
        $response->withHeader('Access-Control-Allow-Credentials', 'true');
        $response->withHeader('Access-Control-Allow-Headers', 'token');
        if ($request->getMethod() === 'OPTIONS') {
            $response->withStatus(202);
            $response->end();
        }
        // 每个请求进来都先执行这个方法 可以作为权限验证 前置请求记录等
//        $request->withAttribute('requestTime', microtime(true));
    }

    static public function afterAction(Request $request, Response $response): void
    {
        // 每个请求结束后都执行这个方法 可以作为后置日志等
//        $start = $request->getAttribute('requestTime');
//        $spend = round(microtime(true) - $start, 3);
//        Logger::getInstance()->console("request :{$request->getUri()->getPath()} take {$spend}");
    }
}
