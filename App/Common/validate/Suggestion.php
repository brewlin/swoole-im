<?php
namespace app\common\validate;

class Suggestion extends BaseValidate
{
	protected $rule=[
		['id','require|isPositiveInteger','参数缺少id|id参数错误'],
		['status','require|checkStatus','参数缺少status|状态非法'],
		['content','require','缺少意见内容'],
        ['name','require','缺少角色名称'],
        ['rules','require','缺少规则'],
	];

	protected $scene = [
		'status' => ['id','status'],
		'add' => ['name','rules'],
        'edit' => ['name','rules','id'],
        'del' => ['id'],
        'doadd' => ['content'],
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