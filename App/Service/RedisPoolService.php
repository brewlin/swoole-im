<?php
/**
 * Created by PhpStorm.
 * User: yuzhang
 * Date: 2018/4/14
 * Time: 下午12:45
 */

namespace App\Service;


use App\Utility\RedisPool;
use EasySwoole\Core\Swoole\Coroutine\PoolManager;

class RedisPoolService
{
    private $pool;
    private $pool_obj;

    public function __construct()
    {
        $this->pool =  PoolManager::getInstance()->getPool(RedisPool::class);
        $this->pool_obj = $this->pool->getObj();
    }


    public function set($key, $value){
        if(is_array($value)){
            $value = json_encode($value);
        }
        return $this->pool_obj->exec('set', $key, $value);
    }

    public function get($key){
        $res = $this->pool_obj->exec('get', $key);
        return $res;
    }

    public function keys($pattern){
        $res = $this->pool_obj->exec('keys', $pattern);
        return $res;
    }

    /*
     * 添加一个集合成员
     */
    public function sAdd($key, $value = []){
        $res = $this->pool_obj->exec('sAdd', $key, $value);
        return $res;
    }

    /*
     * 随机获取一个集合成员
     */
    public function sRandMember($key, $count = null){
        $res = $this->pool_obj->exec('sRandMember', $key, $count);
        return $res;
    }

    /*
     * 获取集合的成员数
     */
    public function sCard($key){
        $res = $this->pool_obj->exec('sCard', $key);
        return $res;
    }

    /*
     * 删除一个集合成员
     */
    public function sRem($key, $mem){
        $res = $this->pool_obj->exec('sRem', $key, $mem);
        return $res;
    }

    /*
     * 获取集合的所有成员
     */
    public function sMembers($key){
        $res = $this->pool_obj->exec('sMembers', $key);
        return $res;
    }

    /*
     * hash
     */
    public function hMset($key, $hashArr){
        $res = $this->pool_obj->exec('hMset', $key, $hashArr);
        return $res;
    }

    public function hSet($key, $hashKey, $value){
        $res = $this->pool_obj->exec('hSet', $key, $hashKey, $value);
        return $res;
    }

    public function hGet($key, $hashKey){
        $res = $this->pool_obj->exec('hGet', $key, $hashKey);
        return $res;
    }

    public function hGetAll($key){
        $res = $this->pool_obj->exec('hGetAll', $key);
        $flag = 1;
        $data = [];
        $tmp = [];
        if(empty($res)){
           return [];
        }
        foreach ($res as $key => $val){
            if($flag%2==0){
                $tmp_key = array_pop($tmp);
                $data[$tmp_key] = $val;
            }else{
                array_push($tmp,$val);
            }
            $flag+=1;
        }
        return $data;
    }

    public function hKeys($key){
        $res = $this->pool_obj->exec('hKeys', $key);
        return $res;
    }

    public function hDel($key, $hashKey){
        $res = $this->pool_obj->exec('hDel', $key,$hashKey);
        return $res;
    }

    public function del($key){
        $res = $this->pool_obj->exec('del', $key);
        return $res;
    }

    public function lPush($key, $mem){
        $res = $this->pool_obj->exec('lPush', $key, $mem);
        return $res;
    }

    public function lIndex($key, $index){
        $res = $this->pool_obj->exec('lIndex', $key, $index);
        return $res;
    }

    public function lLen($key){
        $res = $this->pool_obj->exec('lLen', $key);
        return $res;
    }

    public function lRem($key, $val, $count=0){
        $res = $this->pool_obj->exec('lRem', $key, $val, $count);
        return $res;
    }

    public function __destruct()
    {
        $this->pool->freeObj($this->pool_obj);
    }

}
