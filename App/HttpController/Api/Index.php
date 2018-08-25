<?php
/**
 * Created by PhpStorm.
 * User: Yu
 * Date: 2018/4/13
 * Time: 16:53
 */

namespace App\HttpController\Api;

class Index extends Base
{
    public function index()
    {
        $this->response()->write('Hello easySwoole!');
    }

}