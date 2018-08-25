<?php
/**
 * @author xiaodong
 */
namespace App\Model;
use think\Model;
use EasySwoole\Core\Http\Request;

class BaseModel extends Model
{
    public static $normal = ['status' => 1];
    public static $pause = ['status' => 0];
    /**
     * 修改状态
     */
    public function updateStatus(Request $request)
    {
        $data  = $request->getQueryParams();
        return $this->save(['status' => $data['status']],['id' => $data['id']]);
    }
    /**
     * 更新
     */
    public function doEdit(Request $request)
    {
        $data = $request->getParsedBody();
        $id = $data['id'];
        unset($data['id']);
        return $this->allowField(true)->save($data,['id' => $id]);
    }
    /**
     * 删除
     */
    public function doDel(Request $request)
    {
        $data  = $request->getQueryParams();
        return $this->destroy($data['id']);
    }
    /**
     * 添加
     */
    public function doAdd(Request $request)
    {
        $data = $request->getParsedBody();
        $data['status'] = 0;
        return $this->allowField(true)->save($data);
    }
    /**
     * 根据id查询
     */
    public function getListById($id)
    {
        $where['status'] = 1;
        return $this->where($where)->find($id);
    }
    /**
     * 获取所有列表
    */
    public function getAllList($status = 1,$where = [],$order = ['id' => 'asc'])
    {
        return $this->where('status',$status)
                    ->where($where)
                    ->order($order)
                    ->select();
    }

    /**
     *通过uid获取list 
     */
    public function getListByUserId($id ,$where = [] , $limit = 0)
    {
        return $this->where('status' , 1)
                    ->where($where)
                    ->where('user_id',$id)
                    ->limit($limit)
                    ->select();
    }
    //关联用户名
    public function username()
    {
        return $this->belongsTo('User','id')->bind('username');
    }
    //关联头像
    public function avatar()
    {
        return $this->belongsTo('User','id')->bind('avatar');
    }
}
