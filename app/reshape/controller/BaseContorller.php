<?php
declare (strict_types = 1);

namespace app\reshape\controller;

use think\facade\Request;
class BaseContorller
{
    //    转换图片地址
    protected function  tranformImg($list,$fields) {
        $url = Request::host().'/';
        $url = 'https://'.$url;
        foreach ($fields as $field) {
            $arr = [];
            foreach ($list as $value) {
                $value[$field] = $url . $value[$field];
                array_push($arr, $value);
            }
            $list = $arr;
        }
        return $list;
    }

    protected function resultJson($code, $msg, $data=[], $httpCode=200)
    {
        return json(['code' => $code, 'msg' => $msg, 'data' => $data], 200);
    }
}
