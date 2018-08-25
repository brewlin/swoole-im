<?php

namespace app\common\model;


class Question extends BaseModel
{
    /**
     * 获取规则
     */
    public function getAddData()
    {
    	return config('status.type');
    }
    /**
     * 获取指定条数的列表
     */
    public function getSimpleList($num)
	{
		return $this->where(self::$normal)->limit($num)->select();
	}
}
