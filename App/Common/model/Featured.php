<?php

namespace app\common\model;


class Featured extends BaseModel
{
    protected $hidden = ['create_time','update_time','delete_time','type','status'];    
    /**
     * 获取添加规则
     */
    public function getAddData()
    {
    	return config('status.type');
    }
    /**
     * 根据id查询
     */
    public function getListById($id = 0)
    {
        $where['status'] = 1;
        $res = $this->where($where)->find($id);
        $res['type'] = config('status.type');
        return $res;
    }    
}
