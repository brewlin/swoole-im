<?php
/**
 * Created by PhpStorm.
 * User: Yu
 * Date: 2018/4/13
 * Time: 16:53
 */

namespace App\HttpController\Index;
use EasySwoole\Core\Http\AbstractInterface\Controller;
use EasySwoole\Config;


class Index extends Controller
{
    public function index()
    {
        var_dump("sdfs");
    	$instance = Config::getInstance();
//    	var_dump($instance->getConf('menu.'));
        // $this->response()->write('Hello easySwoole!');
    }
}



;