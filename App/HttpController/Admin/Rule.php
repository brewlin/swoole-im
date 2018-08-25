<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2018/6/27 0027
 * Time: 10:12
 */

namespace App\HttpController\Admin;


class Rule extends BaseView
{
    public $obj;
    public function _initialize()
    {
        parent::_initialize();
        $this->obj = model('Rule');
    }
    /**
     * 规则列表
     */
    public function index()
    {
        $co = input('get.status',1);
        if($co)
        {
            $list = $this->obj->getAllRule($co);
        }else{
            $list = $this->obj->getAlllist(['status' => 0]);
        }
        $this->assign([
            'title' => '权限列表',
            'list' => $list,
            'status' => config('status.status'),
            'co' => $co
        ]);
        return $this->fetch();
    }

}