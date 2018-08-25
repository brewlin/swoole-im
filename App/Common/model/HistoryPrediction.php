<?php
/**
 * @author xiaodong
 * @date(2018.6.29 10:02)
 */
namespace app\common\model;
use app\common\enum\ScopeEnum;
/**
 * 预测话题
 */
class HistoryPrediction extends BaseModel
{
	protected $hidden = ['create_time','update_time','status'];
}
