<?php
namespace app\common\validate;

class UserPrediction extends BaseValidate
{
	protected $rule=[
		['id','require|isPositiveInteger','参数缺少id|id参数错误'],
		['prediction_id','require|isPositiveInteger','参数缺少预测话题id|id参数类型错误'],
		['user_id','require','缺少用户id'],
		['key1','require','缺少答案1'],
		['key2','require','缺少答案2'],
		['key3','require','缺少答案3'],
		['status','require|checkStatus','参数缺少|状态非法'],
		['name','require','缺少标签']
	];

	protected $scene = [
		'status' => ['id','status'],
        'edit' => ['id','name'],
        'del' => ['id'],
        'answer' => ['prediction_id','key1','key2','key3'],
        'query' => ['prediction_id','user_id'] 
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