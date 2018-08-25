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

class LoginValidate extends BaseValidate
{
    protected $rules;

    public function __construct()
    {
        $rule = new Rules();
        $rule->add('email','邮箱必填')->withRule(Validate::REQUIRED);
        $rule->add('password','密码必填，长度在6-16字符之间')->withRule(Validate::REQUIRED)
            ->withRule(Validate::MIN_LEN,6)
            ->withRule(Validate::MAX_LEN,16);
        $this->rules = $rule;
    }
}