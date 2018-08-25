<?php
namespace App\HttpController\Admin;

class Register extends BaseView
{
	private $admin;
	public function _initialize()
	{
		$this->admin = model('admin');
	}
	/**
	 * [index 添加管理员]
	 * @return [type] [json]
	 */
	public function index()
	{
		if(request()->isPost())
		{
			validate('Register')->doCheck();
			$data = input('post.');
			if($data['password'] != $data['repassword'])
			{
				return error('两次填写的密码不一致',confg('json.commonError'),10061);
			}
			$data['salt'] = code();
			$data['password'] = MD5(MD5($data['password'].'QianWen').$data['salt']);
			$res = $this->admin->add($data);
			if($res)
			{
				return success('申请成功，请等待审核');
			}
			return error('申请失败，请重新申请',500,10062);

		}
		return $this->fetch('',[
			'title' => '预言家-注册'
		]);
	}
}