<?php
namespace app\common\validate;
class Register extends BaseValidate
{
	protected $rule=[
		['username' ,'require|max:25|isExist','请填写用户名|用户名太长|用户名已存在'],
		['password' ,'require|max:25','请填写密码'],
		['repassword' ,'require|max:25','请填写确认密码']
	];
}