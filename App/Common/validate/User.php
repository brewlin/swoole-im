<?php
namespace app\common\validate;

class User extends BaseValidate
{
	protected $rule=[
		['id','require|isPositiveInteger','参数缺少ids|id参数错误'],
		['name','require|isNotEmpty' ,'缺少标题|标题不能为空'],
		['img','require|isNotEmpty' ,'缺少图片|缺少图片'],
		['type','require' ,'缺少类型|类型错误'],
		['status','require|checkStatus','参数缺少status|状态非法'],
		['username','require','缺少姓名'],
		['nickname','require','缺少昵称'],
		['sex','require','缺少性别'],
		['birthday','require','生日'],
		['education','require','学历'],
		['job','require','缺少职业'],
		['signature','require','缺少签名'],
		['user_expert','require','缺少标签'],

	];

	protected $scene = [
		'status' => ['id','status'],
		'del' => ['id'],
		'add' => ['name','img','type'],
		'edit' => ['id'],
		'doedit' => ['username','nickname','sex','birthday','education','job','signature','user_expert'],
		//小程序端修改个人信息
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