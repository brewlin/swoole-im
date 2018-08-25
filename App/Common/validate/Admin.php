<?php
namespace app\common\validate;

class Admin extends BaseValidate
{
	protected $rule=[
		['id','require|isPositiveInteger','参数缺少id|id参数错误'],
		['status','require|checkStatus','参数缺少|状态非法'],
		['username' ,'require|max:25|isExist','请填写用户名|用户名太长|用户名已存在'],
		['password' ,'require|max:25','请填写密码'],
		['repassword','require','请确认密码'],
		['salt','require','缺少加密盐值'],
        ['role_id','require','缺少角色id']
	];

	protected $scene = [
		'status' => ['id','status'],
		'add' => ['username','password','repassword'],
        'edit' => ['id','role_id'],
        'del' => ['id']
	];
	
	/**
	 * [checkIDs id1,id2,id3.....]
	 * @param  [type] $valude [description]
	 * @return [type]         [false,true]
	 * @author liangguangchuan 2017-12-23
	 */
	protected function checkStatus($value)
	{
		if($value == "0" || $value == 1 || $value == -1)
		{
			return true;
		}
		return false;
	}
}