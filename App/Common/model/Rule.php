<?php
/**
 * @author xiaodo
 * @date(2018.6.25 12:42)
 */
namespace app\common\model;

class Rule extends BaseModel
{
	public $res = [];
	public function getAllRule($status = 1) 
	{
		$where['status'] = $status;
		$data = $this->where($where)
					 ->select();
		$this->tree($data);
		return $this->res;
	}
	public function tree($data,$pId = 0,$level =  0) 
	{
		foreach ($data as $k => $v) 
		{
			if($v['pid'] == $pId)
			{
				$v['level'] = $level;
				$this->res[] = $v;
				$this->tree($data,$v['id'],$level+1);
			}
		}
	}
	public function getRuleByIds($ids)
	{
		$where['status'] = 1;
		return $this->where($where)
					->where('id','in',$ids)
					->column('name');
	}
	/**
     * 获取更新条件
     */
	public function getAddData()
    {
        $where = [
            'status' => 1,
            'pid' => 0,
        ];
        return $this->where($where)
                     ->select();
    }
}
