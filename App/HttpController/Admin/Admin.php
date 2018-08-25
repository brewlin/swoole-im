<?php
namespace App\HttpController\Admin;
use EasySwoole\Core\Http\Request;
use EasySwoole\Core\Http\Response;
use App\Model\Admin  as AdminModel;
use App\Utility\Validate;
class Admin extends BaseView
{
	private $obj;
	function __construct(string $actionName, Request $request, Response $response)
	{
	    $this->path = $request->getUri()->getPath();
		$this->obj = new AdminModel();	
        parent::__construct($actionName, $request, $response);	
	}
	public function index()
	{
		$data = $this->request()->getRequestParam();
		$keywords = $data['keywords'];
		$list = $this->obj->getAllAdmin($keywords);
		return $this->fetch('',[
			'title' => '管理员列表',
			'list' => $list,
			'key' => $keywords == '' ?'请输入关键词':$keywords,
		]);
	}
	/**
	 * 添加管理员
	 */
	public function add()
	{
		if($this->isPost())
		{
		    Validate::create($this->request(),'add')->goCheck($this->request());
			$data = $this->request()->getParsedBody();
			if($data['password'] != $data['repassword'])
			{
			    return $this->writeJson(400,false,'两次密码不一样请检查');
			}
			$data['salt'] = mt_rand(100,10000);
			$data['password'] = MD5(MD5($data['password'].'XIDO').$data['salt']);
			$res = $this->obj->add($data,1);
			if($res)
			{
			    return $this->writeJson(200,true,'添加成功');
			}
            return $this->writeJson(400,true,'添加失败');
		}
		return $this->fetch('',[
			'title' => '添加管理员'
		]);

	}
	/**
	 *退出登录
	 * */
	public function logOut()
	{
		session('adminAccount', null);
		return $this->redirect(url('Login/index'));
	}

}