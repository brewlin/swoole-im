<?php
namespace App\HttpController\Admin;


class Index extends BaseView
{
	public function index()
	{
		$this->fetch("index/index",[
				'title' => 'WEBSOCKET通用管理后台'
			]);
	}
	public function main()
	{
		$this->fetch("index/main");
	}

}