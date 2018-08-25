<?php
namespace app\common\validate;
class Login extends BaseValidate
{
	protected $rule=[
		['username' ,'require|max:25','请填写用户名'],
		['password' ,'require|max:25','请填写密码'],
	];
}