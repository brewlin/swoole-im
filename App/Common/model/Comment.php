<?php

namespace app\common\model;

use app\api\service\Token;
class Comment extends BaseModel
{
    /**
     * 精选评论
     */
    public function getTopCommentByForeignId($id , $where = [] , $type = 0 ,$limit = 0)
    {
        return $this->where('status' ,1)
                    ->where('type',$type)
                    ->where($where)
                    ->limit($limit)
                    ->order('love','desc')
                    ->where('foreign_id' , $id)
                    ->select();
    }
    /**
     * 添加评论
     */
    public  function add($type = 0)
    {
        $data['foreign_id'] = input('post.id');
        $data['content'] = input('post.content');
        $data['user_id'] = Token::getCurrentUid();
        $data['type'] = $type;
        return $this->save($data);
    }
}
