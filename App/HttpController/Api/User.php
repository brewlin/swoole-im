<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2018/7/28
 * Time: 23:21
 */

namespace App\HttpController\Api;
use App\Model\Group;
use App\Service\UserCacheService;
use App\Validate\User as UserValidate;
use App\Model\User as UserModel;
class User extends Base
{
    /**
     * 获取群信息 或者获取好友信息
     */
    public function getInformation()
    {
        //type friend 就获取好友信息 type为group则获取群信息
        (new UserValidate('info'))->goCheck($this->request());
        $data = $this->request()->getRequestParam();
        if($data['type'] == 'friend')
        {
            $info = UserModel::getUser(['id' => $data['id']]);
            $info['type'] = 'friend';
        }else if($data['type'] == 'group')
        {
            $info = Group::getGroup(['id' => $data['id']] , true);
            $info['type'] = 'group';
        }else
        {
            return $this->error('类型错误');
        }
        return $this->success($info);

    }
}