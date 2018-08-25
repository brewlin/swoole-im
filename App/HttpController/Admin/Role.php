<?php
/**
 * @author xiaodong
 */
namespace App\HttpController\Admin;
use App\Utility\Model;
use EasySwoole\Core\Http\Request;
use EasySwoole\Core\Http\Response;
use App\Utility\Validate;
use EasySwoole\Config;
use App\Model\Role as RoleModel;

class Role extends BaseView
{
    private $obj;
    function __construct(string $actionName, Request $request, Response $response)
    {
        $this->path = $request->getUri()->getPath();
        $this->obj = new RoleModel();
        parent::__construct($actionName, $request, $response);
    }
    /**
     * 角色列表
     */
    public function index()
    {
        $co = $this->request()->getQueryParam('status');
        $list = $this->obj->getAllList($co == null?1:$co);
        return $this->fetch('',[
            'title' => '角色列表',
            'list' => $list,
            'status' => Config::getInstance()->getConf('status.status'),
            'co' => $co
        ]);
    }

}
