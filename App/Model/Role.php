<?php

namespace App\Model;


use App\Utility\Model;
use EasySwoole\Core\Http\Request;

class Role extends BaseModel
{
    public function getRoleById($id, $where = [])
    {
        $where['status'] = 1;
        return $this->get($id);
    }
    /**
     * 获取权限规则
     */
    public function getAddData()
    {
        $rule = new Rule();
        return $rule->getAllRule();
    }
    /**
     * 添加
     */
    public function doAdd(Request $request)
    {
        $data = input('post.');
        $data['status'] = 0;
        $data['rules'] = implode(',', $data['rules']);
        return $this->allowField(true)->save($data);
    }

    /**
     * 获取更新的条件
     */
    public function getListByIdByAdmin($id = 0)
    {
        $where['status'] = 1;
        $roles =  $this->where($where)->find($id);
        if(!$roles){
            throw new NotExistException();
        }
        $rule = explode(',',$roles['rules']);
        $rules = $this->getAddData();
        foreach ($rules as $k => &$v)
        {
            if(in_array($v['id'],$rule))
            {
                $v['type'] = 1;
            }else{
                $v['type'] = 0;
            }
        }
        return ['rule' => $rules,'role' => $roles];
    }
    /**
     * 更新
     */
    public function doEdit(Request $request)
    {
        $data = input('post.');
        $id = $data['id'];
        $data['rules'] = implode(',',$data['rules']);
        unset($data['id']);
        return $this->allowField(true)->save($data,['id' => $id]);
    }
}
