<?php
namespace app\common\validate;

class Rule extends BaseValidate
{
	protected $rule=[
		['id','require|isPositiveInteger','参数缺少|id参数错误'],
		['status','require|checkStatus','参数缺少|状态非法'],
		['pid' ,'require','请选择父级'],
		['title','require','缺少权限名称'],
		['name','require','缺少权限规则'],
	];

	protected $scene = [
		'status' => ['id','status'],
		'add' => ['pid','title','name'],
        'edit' => ['id','name','title'],
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
    /**
     * 添加规则
     */
    public function add($data,$status = 0)
    {
    	$data['status'] = $status;
    	return $this->allowField(true)
    				->save($data);
    }	
}