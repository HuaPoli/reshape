<?php
/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 2021/5/10
 * Time: 9:47
 */

namespace app\vocpand\service;


use app\vocpand\model\Opus;
use think\facade\Request;
class OpusService
{
    public static function opusList($data)
    {
        $list = (new Opus())->opusList($data);
        if($list == null)
            return $list;
        $url = 'https://'.Request::host().'/';
        $opuses = [];
        foreach ($list as $value) {
            $img_views = explode('/#/',$value['img_view']) ;
            $arr = [];
            foreach ($img_views as $img_view) {
                array_push($arr, ($url . $img_view));
            }
            $value['img_view'] = $arr;
            array_push($opuses, $value);

        }
        return $opuses;

    }

}