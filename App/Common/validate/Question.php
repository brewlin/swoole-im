<?php
namespace app\common\validate;

class Question extends BaseValidate
{
	protected $rule=[
		['id','require|isPositiveInteger','参数缺少id|id参数错误'],
        ['title','require','缺少标题名称'],
        ['content','require','缺少内容'],
		['status','require|checkStatus','参数缺少status|状态非法'],
        ['name','require','缺少角色名称'],
	];

	protected $scene = [
		'status' => ['id','status'],
		'add' => ['title','content'],
        'edit' => ['title','content','id'],
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