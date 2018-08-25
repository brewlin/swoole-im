<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2018/7/24 0024
 * Time: 20:41
 */

namespace App\HttpController\Api;
use App\Model\GroupUser as GroupUserModel;
use App\Service\UserCacheService;
use App\Validate\GroupUser as GroupUserValidate;

class GroupUser extends Base
{
    /**
     * 分组名添加
     */
    public function addMyGroup()
    {
        (new GroupUserValidate('add'))->goCheck($this->request());
        $token = $this->request()->getRequestParam('token');
        $groupname = $this->request()->getRequestParam('groupname');
        $id = UserCacheService::getIdByToken($token);
        $group = GroupUserModel::addGroup($id , $groupname);
        $this->success(['id' => $group->id , 'groupname' => $groupname]);
    }
    /**
     * 分组名修改
     */
    public function editMyGroup()
    {
        (new GroupUserValidate('edit'))->goCheck($this->request());
        $data = $this->request()->getRequestParam();
        $res = GroupUserModel::editGroup($data['id'] , $data['groupname']);
        if(!$res)
        {
            return $this->error([],'修改失败');
        }
        return $this->success([],'修改成功');
    }
    /**
     * 删除分组名
     */
    public function delMyGroup()
    {
        (new GroupUserValidate('del'))->goCheck($this->request());
        $res = GroupUserModel::delGroup($this->request()->getRequestParam('id') ,$this->user);
        if($res)
        {
            return $this->success([],'删除成功');
        }
        return $this->error([],'删除失败');
    }
}