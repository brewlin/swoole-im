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

class GroupUser extends BaseValidate
{
    public $rules;

    public function __construct($scene)
    {
        $rule = new Rules();
        $this->rules = $rule;
        $this->$scene();
    }
    public function add()
    {
        $this->rules->add('token','缺少token')->withRule(Validate::REQUIRED);
        $this->rules->add('groupname','缺少分组名')->withRule(Validate::REQUIRED);
    }
    public function edit()
    {
        $this->rules->add('id','缺少id')->withRule(Validate::REQUIRED);
        $this->rules->add('groupname','缺少分组名')->withRule(Validate::REQUIRED);
    }
    public function del()
    {
        $this->rules->add('id','缺少id')->withRule(Validate::REQUIRED);
    }

}