<?php
declare (strict_types = 1);

namespace app\vocpand\controller;

use app\vocpand\service\UserToken;
use app\vocpand\validate\TokenValidate;
use think\Request;

class Token
{


    public function getToken(Request $request)
    {
        $data = $request->post();
        (new TokenValidate())->checkParam($data);
        $token = (new UserToken($data['code'],$data['role']))->get();
        return $this->resultJson(0,'msg', ['token'=>$token]);
    }
    protected function resultJson($code, $msg, $data=[], $httpCode=200)
    {
        return json(['code' => $code, 'msg' => $msg, 'data' => $data], 200);
    }
}
