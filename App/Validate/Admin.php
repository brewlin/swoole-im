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

class Admin extends BaseValidate
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
    public function status()
    {
        $this->rules->add('id','require id')->withRule(Validate::REQUIRED);
        $this->rules->add('status','require')->withRule(Validate::REQUIRED);
    }
    /**
     * 添加数据
     */
    public function add()
    {
        $this->rules->add('username','缺少用户名')->withRule(Validate::REQUIRED);
        $this->rules->add('password','缺少密码')->withRule(Validate::REQUIRED);
        $this->rules->add('repassword','缺少确认密码')->withRule(Validate::REQUIRED);
    }
    /**
     * 编辑数据验证
     */
    public function edit()
    {
            $this->rules->add('id','缺少id')->withRule(Validate::REQUIRED);
            $this->rules->add('role_id','缺少角色id')->withRule(Validate::REQUIRED);
    }
    /**
     * 删除数据
     */
    public function del()
    {
        $this->rules->add('id','require id')->withRule(Validate::REQUIRED);
    }
}