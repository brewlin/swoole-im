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
     * 统计
     */
    public function total()
    {
        $this->rules->add('value','缺少搜索词')->withRule(Validate::REQUIRED);
        $this->rules->add('type','缺少类型')->withRule(Validate::REQUIRED);
    }
    /**
     * 查找
     */
    public function find()
    {
        $this->rules->add('value','缺少搜索词')->withRule(Validate::REQUIRED);
        $this->rules->add('type','缺少类型')->withRule(Validate::REQUIRED);
        $this->rules->add('page','缺少分页参数')->withRule(Validate::REQUIRED);
    }
    /**
     * 签名
     */
    public function sign()
    {
        $this->rules->add('sign','缺少类型')->withRule(Validate::REQUIRED);
    }

}