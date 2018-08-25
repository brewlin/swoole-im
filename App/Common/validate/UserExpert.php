<?php
namespace app\common\validate;

class UserExpert extends BaseValidate
{
	protected $rule=[
		['id','require|isPositiveInteger','参数缺少id|id参数错误'],
		['status','require|checkStatus','参数缺少|状态非法'],
		['name','require','缺少标签']
	];

	protected $scene = [
		'status' => ['id','status'],
        'edit' => ['id','name'],
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