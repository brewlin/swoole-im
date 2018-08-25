<?php

namespace app\common\model;


class Admin extends BaseModel
{
	/**
	 * 通过名字获取管理员
	 */
    public function getAdminByName($where = [])
    {
    	$where['status'] = ['neq',0];
    	return $this->where($where)
    				->find();
    }
    /**
     * 添加管理员
     */
    public function add($data,$status = 0)
    {
    	$data['status'] = $status;
    	return $this->allowField(true)
    				->save($data);
    }
    /**
     * 获取所有的管理员
     */
    public function getAllAdmin($key)
    {
        if($key)
        {
            //复合查询
            $con['id'] = ['like',"%{$key}%"];
            $con['username'] = ['like',"%{$key}%"];
            $con['nickname'] = ['like',"%{$key}%"];
            $con['email'] = ['like',"%{$key}%"];
            $con['_logic'] = 'OR';
            $where['_complex'] = $con;
            return $this->whereOr('id','like',"%{$key}%")
                        ->whereOr('username','like',"%{$key}%")
                        ->whereOr('email','like',"%{$key}%")
                        ->where('status','neq',0)
                        ->paginate();
        }
        return $this->where('status','neq',0)
                    ->paginate();
    }
    /**
     * role外键
     */
    public function role()
    {
        return $this->belongsTo('Role','role_id','id');
    }
    /**
     * 获取更新的条件
     */
    public function getListById($id = 0)
    {
        $where['status'] = 1;
        $role = model('role')->getAllList();
        $admin =  $this->where($where)->find($id);
        $admin['role'] = $role;
        return $admin;
    }
}
