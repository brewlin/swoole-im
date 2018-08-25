<?php
namespace app\common\validate;

class Experience extends BaseValidate
{
	protected $rule=[
		['id','require|isPositiveInteger','参数缺少id|id参数错误'],
		['status','require|checkStatus','参数缺少|状态非法'],
		['title' ,'require','缺少标题'],
		['content','require','缺少主要内容'],
		['key1','require','缺少答案1'],
		['key2','require','缺少答案2'],
		['key3','require','缺少答案3'],
	];

	protected $scene = [
		'status' => ['id','status'],
        'edit' => ['id','title','content','watch'],
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