<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2018/7/24 0024
 * Time: 20:41
 */

namespace App\HttpController\Api;
use App\Model\Group;
use App\Model\GroupMember as GroupMemberModel;
use App\Model\User;
use App\Service\GroupUserMemberService;
use App\Service\UserCacheService;
use EasySwoole\Config;
use EasySwoole\EasySwooleEvent;

class Tool extends Base
{
    public function index()
    {
    }
    //生成保存路径
    public function getFullPath($obj , $type , $clientName)
    {
        $no = self::makeSerialNo();
        $dir = Config::getInstance()->getConf('tool.upload_path')?'/upload':Config::getInstance()->getConf('tool.upload_path');
        $path = $dir."/".$type."/".date("Ymd");

        if (!file_exists(Config::getInstance()->getConf('tool.root_dir').$path)) {
            mkdir(Config::getInstance()->getConf('tool.root_dir').$path, 0777, true);
        }
        if(in_array($type , ['jpeg','jpg','png','avi','mp3']))
        {
            $str = $path."/".$no.".".$type;
        }else
        {
            $str = $path."/".$clientName;
        }
        return $str;
    }
    //生成随机串号
    public static function makeSerialNo(){
        $code = ['A','B','C','D','E','F','G','H','I','J'];
        $serialNo = $code[intval(date('Y'))-2017].strtoupper(dechex(date('m'))).date('d').substr(time(), -5).substr(microtime(),2,5).sprintf('%02d',rand(0,99));
        return $serialNo;
    }
    public function uploadImage()
    {

        //获取群信息
        $file = $this->request()->getUploadedFile('file');
        if(empty($file))
        {
            $this->error([],'缺少文件');
        }
        $type = explode('.',$file->getClientFilename());
        $type = array_pop($type);
        $clientName = $file->getClientFilename();
        $pathName = $this->getFullPath($file,$type , $clientName);
        $file->moveTo(Config::getInstance()->getConf('tool.root_dir').$pathName);
        $this->success(['src' => $pathName]);
    }
}