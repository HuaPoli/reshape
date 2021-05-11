<?php
declare (strict_types = 1);

namespace app\vocpand\controller;

use app\BaseController;
use app\exception\HttpExceptions;
use think\facade\Cache;
use think\facade\Config;
use think\facade\Request;

class CommonController extends BaseController
{
//    protected $user;
    protected function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        $token = Request::header('token');
        if($token == null)
            throw  new HttpExceptions(400, '禁止未携带token访问', 1000);
        $value = Cache::get($token);
        if($value == null)
            throw  new HttpExceptions(400, 'token已失效', 1000);
        Cache::set($token, $value, Config::get('setting.token_expire_in'));
//        $this->user = json_decode($value);


    }


    protected function resultJson($code, $msg, $data=[], $httpCode=200)
    {
        return json(['code' => $code, 'msg' => $msg, 'data' => $data], 200);
    }
}
