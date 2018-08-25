<?php
namespace app\common\validate;

class Featured extends BaseValidate
{
	protected $rule=[
		['id','require|isPositiveInteger','参数缺少ids|id参数错误'],
		['name','require|isNotEmpty' ,'缺少标题|标题不能为空'],
		['img','require|isNotEmpty' ,'缺少图片|缺少图片'],
		['type','require' ,'缺少类型|类型错误'],
		['status','require|checkStatus','参数缺少status|状态非法'],
	];

	protected $scene = [
		'status' => ['id','status'],
		'del' => ['id'],
		'add' => ['name','img','type'],
		'edit' => ['id']
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