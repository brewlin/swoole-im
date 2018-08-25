<?php
/**
 * @author xiaodong
 * @date(2018.6.25)
 */
namespace App\HttpController\Admin;

class Login extends BaseView
{
	private $admin;

	public function _initialize()
	{
		$this->admin = model('Admin');
		$account = session('adminAccount');
		if($account)
		{
			return $this->redirect(url('Index/index'));
		}
	}
	public function index()
	{
		/**
		 * 用户登录逻辑
		 */
		if(request()->isPost())
		{
			(validate('Login')->doCheck());
	        $username = input('post.username');
	        $password = input('post.password');
	        $account = $this->admin->getAdminByName(['username' => $username]);
	        if(empty($account))
	        {
	        	return error('账户不存在,或已经被停用',config('json.commonError'),10007);
	        }
	        if($account['password'] == MD5(MD5($password.'QianWen').$account['salt']))
	        {
	        	//写入缓存里
	        	session('adminAccount', $account);
	        	/**
	        	 * 写入日志
	        	 */
	        	$this->getPri($account->role_id);
	        	return success('登录成功');
	        }
	        return error('密码错误，请重新输入密码',config('json.commonError'),10071);
		}		
		return $this->fetch('',[
			'title' => '预言家-登陆'
		]);

	}
	/**
	 *缓存用户权限
	 */
    public function getPri($roleId)
    {
	    $roleres = model('Role')->getRoleById($roleId);
	    if ($roleres['rules'] =='*')
	    {
	      session('rules','*');
	    }else if(empty($roleres))
        {
            $this->error("账号未激活");
        }
	    else
	    {
	        $rules = model('Rule')->getRuleByIds($roleres['rules']);
	        $_pris = [];
	        foreach ($rules as $k => $v) {
	          $_pris[] = $v;
	        }
	        session('rules',$_pris);
	      }
    }	
}