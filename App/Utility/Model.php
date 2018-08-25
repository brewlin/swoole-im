<?php
/**
 * @author  xiaodong
 * @date(2018.7.9 16:18)
 */
namespace App\Utility;
use EasySwoole\Core\Http\Request;
use App\Model\Admin;
use App\Model\Role;
class Model
{
  
    public static function create($request)
    {
        if(!($request instanceof Request))
        {
            return self::autoCreate($request);
        }
        $server = $request->getServerParams();
        $className = explode('/',$server['path_info'])[2];
         return self::autoCreate($className);
    }
    private static function autoCreate(?string $className)
    {
    	switch ($className) {
    		case 'admin':
                return new Admin();
    			break;
            case 'role':
                return new Role();
                break;
    		default:
    			# code...
    			break;
    	}
    	return false;
    }
}