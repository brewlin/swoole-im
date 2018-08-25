<?php

namespace App\HttpController\Admin;

use App\Utility\Validate;
use EasySwoole\Config;
use EasySwoole\Core\Http\AbstractInterface\Controller;
use EasySwoole\Core\Http\Request;
use EasySwoole\Core\Http\Response;
use think\Template;
use App\Utility\Model;
use EasySwoole\Core\Component\Cache\Cache;
/**
 * 视图控制器
 * Class ViewController
 * @author  : evalor <master@evalor.cn>
 * @package App\HttpController\Admin
 */
abstract class BaseView extends Controller
{
    protected $view;
    protected $menu = null;//目录菜单
    public $account;
    public $admin;
    public $path;

    /**
     * 初始化模板引擎
     * ViewController constructor.
     * @param string   $actionName
     * @param Request  $request
     * @param Response $response
     */
    function __construct(string $actionName, Request $request, Response $response)
    {
        // $this->menu = config('menu.menu');
        // $this->admin = model('Admin');
        //      $instance = Config::getInstance();
        // var_dump($instance->getConf('menu.'));

        // $this->isLogin();
        // $this->checkRule();
        $this->view = new Template();
        $tempPath   = Config::getInstance()->getConf('TEMP_DIR');     # 临时文件目录
        $this->view->config([
            'view_path'  => EASYSWOOLE_ROOT . '/App/HttpController/Views/Admin/',              # 模板文件目录
            'cache_path' => "{$tempPath}/templates_c/",     # 模板编译目录
            'view_replace_str'       => [
                '__STATIC__' => '/admin',
            ],            // 视图输出字符串内容替换            

        ]);

        parent::__construct($actionName, $request, $response);         
    }

    /**
     * 输出模板到页面
     * @param  string|null $template 模板文件
     * @param array        $vars     模板变量值
     * @param array        $config   额外的渲染配置
     * @author : evalor <master@evalor.cn>
     */
    function fetch($template = '', $vars = [], $config = [])
    {
        $template = ($template == ''?explode('/',$this->path)[2].'/'.explode('/',$this->path)[3]:$template);
        ob_start();
        $vars['menu'] = Config::getInstance()->getConf('menu.menu.');
        $this->view->fetch($template, $vars, $config);
        $content = ob_get_clean();
        $this->response()->write($content);
    }
    /**
     *权限检查
     */
    public function checkRule()
    {
        $adminAcount = $this->getLoginUser();
        $this->assign('adminId',$adminAcount->id);
        if (request()->controller() == 'Admin' && request()->action() == 'logout')
        {
            return true;
        }
        if (session("rules")!='*' && !in_array(request()->controller().'/'.request()->action(),session('rules'))) 
        {
             if (request()->isAjax()) {
                 return error('没有权限访问该功能',config('json.commonError'),10000);
             }else {
                 $this->error('没有权限访问该功能！');
             }

        }        
    }
    /**
     * 判断是否登录
     */
    public function isLogin()
    {
        $user = $this->getLoginUser();
        if(empty($user))
        {
            return $this->redirect(url('Login/index'));
        }
        if(!$user && !$user->id)
        {
            return $this->redirect(url('Login/index'));
        }
    }
    /**
     * 获取当前登录用户
     */
    public function getLoginUser()
    {
        if(!$this->account)
        {
            $this->account =  Cache::getInstance()->get('adminAccount');
        }
        return $this->account;

    }
    /**
     * 更新状态
     */
    public function status()
    {
        (Validate::create($this->request(),'status')->goCheck($this->request()));
         $res = (Model::create($this->request())->updateStatus($this->request()));
        if(!$res)
        {
            return $this->writeJson(400, false,"更新失败");
        }
        return $this->writeJson(200, true,"更新成功");
    }
    /**
     * 编辑
     */
    public function edit()
    {
        //post 逻辑
        if($this->isPost())
        {
            (Validate::create($this->request(),'edit')->goCheck($this->request()));
            $res = (Model::create($this->request()))->doEdit($this->request());
            if($res)
            {
                return $this->writeJson(200,true,'编辑成功');
            }
            return $this->writeJson(400,false,'编辑失败');
         }
        $id = $this->request()->getQueryParam('id');
        if(empty($id))
        {
            return $this->writeJson(400, false,"缺少id");
        }
        $list = (Model::create($this->request()))->getListById($id);
        return $this->fetch('',[
            'title' => '编辑',
            'list' => $list
        ]);
    }
    /**
     * 添加
     */
    public function add()
    {
        //post 逻辑
        if($this->isPost())
        {
            (Validate::create($this->request(),'add')->goCheck($this->request()));
            $res = Model::create($this->request())->doAdd($this->request());
            if($res)
            {
                return $this->writeJson(200, true,"添加成功");
            }
            return $this->writeJson(400, true,"添加失败");
        }
        $list = Model::create($this->request())->getAddData();
        return $this->fetch('',[
            'title' => '添加',
            'list' => $list
        ]);
    }
    /**
     * 更新状态
     */
    public function del()
    {
        (Validate::create($this->request(),'del')->goCheck($this->request()));
        $res = Model::create($this->request())->doDel($this->request());
        if(!$res)
        {
            return $this->writeJson(400, true,"删除失败");
        }
        return $this->writeJson(200, true,"删除成功");
    }
    /**
     * 审核申请
     */
    public function apply()
    {
        $list = (Model::create($this->request()))->getAllList(0);
        return $this->fetch('',[
            'title' => '审核申请',
            'list' => $list
        ]);
    }

    /**
     * 当前请求是否为get方式
     * @return bool
     */
    public function isGet()
    {
        return $this->request()->getMethod() == 'GET';
    }
    /**
     * 当前请求是否为post方式
     * @return bool
     */
    public function isPost()
    {
        return $this->request()->getMethod() == 'POST';
    }
}
