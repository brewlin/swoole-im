<?php

namespace app\common\model;

use app\api\service\Token;
class Suggestion extends BaseModel
{
	/**
	 * 意见反馈
	 */
	public function add()
	{
        $data = input('post.');
        $data['user_id'] = Token::getCurrentUid();
        return $this->allowField(true)->save($data);		
	}
}
