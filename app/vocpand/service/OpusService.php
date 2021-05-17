<?php
/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 2021/5/10
 * Time: 9:47
 */

namespace app\vocpand\service;

use app\vocpand\model\Job;

use app\vocpand\model\Opus;
use think\facade\Db;
use think\facade\Request;
class OpusService
{


    /*
     * 点赞
     * */
    public static function praise($id, $praise)
    {
        $job = Db::name('opus')->find($id);
        $count = $job['praise'];
        if($praise == 'true'){
            $count += 1;
            Db::name('opus')->where('id',$id)->update(['praise'=>$count]);
            return true;
        }
        $count = $count - 1;
        $count < 0 ? 0 : $count;
        Db::name('opus')->where('id',$id)->update(['praise'=>$count]);
        return false;
    }

    public static function opusList($data)
    {


        $list =  (new Opus())->opusList($data);
        $url = 'https://'.Request::host().'/';
        for ($i = 0; $i < count($list); $i++) {
            if($list[$i]['file_type'] == 'img'){
                $imgs = explode('#',$list[$i]['img_view']);
                for ($index = 0; $index < count($imgs); $index++) {
                    $imgs[$index] = $url.$imgs[$index];
                }
                $list[$i]['img_view'] = $imgs;
            }else{
                $list[$i]['img_view'] = $url.$list[$i]['img_view'];
            }
        }
        return $list;


    }
}