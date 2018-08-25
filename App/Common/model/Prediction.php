<?php
/**
 * @author xiaodong
 * @date(2018.6.29 10:02)
 */
namespace app\common\model;
use app\common\enum\ScopeEnum;
use app\api\service\Token;
/**
 * 预测话题
 */
class Prediction extends BaseModel
{
	protected $hidden = ['create_time','update_time','status'];
	public function getSimpleList($num)
	{
		return $this->withCount('userprediction')
					->where(self::$normal)->limit($num)->select();
	}
	public function getAllList($where = ['status' => 1],$order = ['id' => 'asc'])
	{
		return $this->withCount('comment')
					->where($where)
					->paginate();
	}
	/**
	 * [预测话题详情页接口]
	 * @param  [type]  $id         [预测话题id]
	 * @param  integer $userNum    [用户预测显示条数]
	 * @param  integer $historyNum [历史预测显示条数]
	 * @return [type]              [array]
	 */
	public function getListById($id ,$userNum = 2 , $historyNum = 2 , $commentNum = 2)
	{
		$res = parent::getListById($id);
		$res['userprediction'] = model('UserPrediction')->getTopUserPredictionByPredictionId($id,[],$userNum);
		$res['history'] = parent::getListByUserId($res['user_id'], [], $historyNum);
		$res['comment'] = model('Comment')->getTopCommentByForeignId($id,[],ScopeEnum::PredictionComment , $commentNum);
		return $res;
	}
	/**
	 * 获取历史值
	 */
	public function getHistoryPredictionById($id , $where = [])
	{
		return $this->with(['historyprediction' => function($query){$query->where('status',1);}])
					->where('status', 1)
					->where($where)
					->find($id);

	}
	/**
	 * 添加预测话题
	 */
	public function add()
	{
        $data = input('post.');
        $data['user_id'] = Token::getCurrentUid();
        return $this->allowField(true)->save($data);		
	}
	/**
	 * 查询布莱尔得分的相关数据
	 */
	public function getBlair()
	{
		return $this->with(['userprediction' => function($query)
						{
							$query->where('score',-1);
						}])
					->where(['main_key' => ['neq','']])
					->select();

	}
    /**
     * 统计综合得分
     */
    public function getTotal()
    {
        return $this->with(['userprediction' => function($query)
        {
            $query->where(['score' => ['neq',-1]]);
        }])
            ->where(['main_key' => ['neq',''],'main_score' => -1])
            ->select();

    }
}
