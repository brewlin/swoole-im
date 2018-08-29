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

class User extends BaseValidate
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
    public function info()
    {
        $this->rules->add('id','缺少id')->withRule(Validate::REQUIRED);
        $this->rules->add('type','缺少类型')->withRule(Validate::REQUIRED);
    }
    /**
     * 签名
     */
    public function sign()
    {
        $this->rules->add('sign','缺少类型')->withRule(Validate::REQUIRED);
    }

}