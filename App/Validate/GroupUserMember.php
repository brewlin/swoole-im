<?php
/**
 * Created by PhpStorm.
 * User: yuzhang
 * Date: 2018/4/13
 * Time: 下午7:36
 */

namespace App\Validate;


use EasySwoole\Core\Utility\Validate\Rules;
use EasySwoole\Core\Utility\Validate\Validate;

class GroupUserMember extends BaseValidate
{
    public $rules;

    public function __construct($scene)
    {
        $rule = new Rules();
        $this->rules = $rule;
        $this->$scene();
    }

    /**
     * 状态更新
     */
    public function remark()
    {
        $this->rules->add('friend_id','缺少id')->withRule(Validate::REQUIRED);
        $this->rules->add('friend_name','缺少名字')->withRule(Validate::REQUIRED);
    }
    /**
     * 移动好友
     */
    public function move()
    {
        $this->rules->add('friend_id','缺少id')->withRule(Validate::REQUIRED);
        $this->rules->add('groupid','缺少分组id')->withRule(Validate::REQUIRED);
    }
    /**
     * 删除好友
     */
    public function remove()
    {
        $this->rules->add('friend_id','缺少id')->withRule(Validate::REQUIRED);
    }

}