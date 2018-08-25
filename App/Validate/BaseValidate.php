<?php
/**
 * Created by PhpStorm.
 * User: Yu
 * Date: 2018/4/13
 * Time: 17:30
 */

namespace App\Validate;

use App\Exception\ParameterException;
use EasySwoole\Core\Component\Logger;
use EasySwoole\Core\Utility\Validate\Rules;
use EasySwoole\Core\Utility\Validate\Validate;

class BaseValidate
{
    public function goCheck($request){
        // 获取所有参数
        $params = $request->getRequestParam();

        // 新建验证类
        $validate = new Validate();

        $res = $validate->validate($params,$this->rules);
        // 返回验证结果或抛出异常
        if(!$res->hasError()){
            return true;
        }else{
            $error = $res->getErrorList()->all();
            throw new ParameterException([
                'msg' => $error]
            );
        }
    }
}