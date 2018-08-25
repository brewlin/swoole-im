<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2018/6/27 0027
 * Time: 16:40
 */

namespace app\common\exception;


class NotExistException extends BaseException
{
    public $code=400;
    public $msg='data not found';
    public $errorCode=30006;
}