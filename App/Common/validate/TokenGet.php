<?php
namespace app\common\validate;
/**
 * Token 获取验证器
 */
class TokenGet extends BaseValidate
{
	protected $rule=[
		['code','require|isNotEmpty','CODE参数必须传|参数不能为空'],
	];
	
}