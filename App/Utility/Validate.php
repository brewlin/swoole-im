<?php
/**
 * @author  xiaodong
 * @date(2018.7.9 16:18)
 */
namespace App\Utility;
use EasySwoole\Core\Http\Request;
use App\Validate\Admin;
class Validate
{
    public static function create(Request $request,$action = '')
    {
        $server = $request->getServerParams();
        $className = explode('/',$server['path_info'])[1];
        return self::autoCreate($className,$action);
    }
    private static function autoCreate(?string $className,?string $action)
    {
        switch ($className) {
            case 'admin':
                return new Admin($action);
                break;

            default:
                # code...
                break;
        }
        return false;
    }
}