<?php
/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 2021/5/10
 * Time: 9:47
 */

namespace app\vocpand\service;

use app\vocpand\model\Job;

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
}