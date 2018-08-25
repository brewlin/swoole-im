<?php

namespace app\common\model;

use app\api\service\Token;

class User extends BaseModel
{
    /**
     * 标签自动转换
     */
    public function setUserExpertAttr($value)
    {
        return implode(',',$value);
    }
    public function getUserExpertAttr($value)
    {
        return explode(',',$value);
    }
    /**
     *通过user_id获取list 
     */
    public function getAllListByUserId($id ,$where = [] , $limit = 0)
    {
        return $this->where('status' , 1)
                    ->where($where)
                    ->where('user_id',$id)
                    ->limit($limit)->select();
    }
    /**
     * 精选用户预测
     */
    public function getTopUserPrediction($id ,$where = [] , $limit = 0)
    {
        return  $this->with(['userprediction' => function($query)use ($limit){
                                $query->limit($limit);
                            }])
                    ->where('status' ,1)
                    ->where($where)
                    ->find($id);
    }
    /**
     * 精选评论
     */
    public function getTopComment($id , $where = [] , $type = 0 ,$limit = 0)
    {
        return $this->with(['comment' => function($query)use ($limit){
            $query->order('love' ,'desc');
        }])
                    ->where('status' ,1)
                    ->where('type',$type)
                    ->where($where)
                    ->where('foreign_id' , $id)
                    ->limit($limit)
                    ->select();
    }
    /**
     * 查看他人信息
     */
    public function getListById($id,$predictionNum = 4 , $userpredictionNum = 3)
    {
        $list =  $this->with('userexpert')//关联标签
                      ->with('top')//关联排行榜
                      ->withCount(['userprediction' => function($query){
                            $query->where(['score' => ['eq',0]]);
                      }])//关联正在预测的问题
                      ->withCount(['userprediction' => function($query){
                            $query->where(['score' => ['neq',0]]);
                      }])//关联已经预测的问题
                      ->with(['prediction' => function($query) use($predictionNum){
                            $query->limit($predictionNum);
                      }])//关联预测的话题
                      ->with(['userprediction' => function($query)use($userpredictionNum){
                            $query->limit($userpredictionNum);
                      }])//关联参与预测的问题
                      ->with('experience')//关联经验分享
                      ->where('status',1)->find($id);
        return $list;
    }
    /**
     * 关联预测表数据
     * @return [type] [description]
     */
    public function prediction()
    {
        return $this->belongsTo('Prediction','id','user_id');
    }
    /**
     * 修改个人信息
     */
    public function edit()
    {
        $data = input('post.');
        return $this->allowField(true)->save($data,['id' => Token::getCurrentUid()]); 
    }

}
