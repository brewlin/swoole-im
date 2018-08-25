<?php

namespace app\common\model;

use app\api\service\Token;
class UserPrediction extends BaseModel
{
    protected $hidden = ['create_time','update_time','status'];    
    /**
     * 回答预测话题
     */
    public function add()
    {
        $data = input('post.');
        $data['user_id'] = Token::getCurrentUid();
        $data['status'] = 1;
        return $this->allowField(true)->save($data);
    }    	
    /**
     * 精选用户预测
     */
    public function getTopUserPredictionByPredictionId($id ,$where = [] , $limit = 0)
    {
        return $this->where('status' ,1)
                    ->where($where)
                    ->where('prediction_id',$id)
                    ->limit($limit)
                    ->select();
    }
    /**
     * 通过userid查询预测回答
     */
    public function getPredictionByUserId($userId ,$predictionId)
    {
        return $this->with('prediction',['status' => 1])
                    ->where('status' , 1)
                    ->where('prediction_id',$predictionId)
                    ->where('user_id',$userId)
                    ->find();
    }   
    /**
     * 查询预测数量
     */
    public function getPredictionCountByUserId($userId,$where = [])
    {
        return $this->where('status' ,1)
                    ->where('user_id',$userId)
                    ->where($where)
                    ->count();

    }
    public function getOnePrediction($predictionId)
    {
        return $this->with('prediction')
                    ->where('prediction_id',$predictionId)
                    ->where('user_id',Token::getCurrentUid())
                    ->find();
    }
}
