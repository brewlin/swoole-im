<?php
/**
 * @author xiaodong
 * @date(2018.7.30 10:20)
 */
namespace app\common\model;

/**
 * 排行榜
 */
class Top extends BaseModel
{
	protected $hidden = ['create_time','update_time','status'];
	public function getSimpleList($num = 2)
	{
		return $this->withCount('userprediction', self::$normal)
					->with('userlove',self::$normal)
					->where(self::$normal)
					->order('avg_score','asc')
					->limit($num)
					->select();
	}

}
