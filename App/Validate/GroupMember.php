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

class GroupMember extends BaseValidate
{
    public $rules;

    public function __construct($scene)
    {
        $rule = new Rules();
        $this->rules = $rule;
        $this->$scene();
    }
    public function leave()
    {
        $this->rules->add('id','缺少群组id')->withRule(Validate::REQUIRED);
    }
    public function create()
    {
        $this->rules->add('groupName','缺少群名称')->withRule(Validate::REQUIRED);
        $this->rules->add('des','缺少群描述')->withRule(Validate::REQUIRED);
        $this->rules->add('number','缺少群规模')->withRule(Validate::REQUIRED);
        $this->rules->add('approval','缺少验证方式')->withRule(Validate::REQUIRED);
    }
}