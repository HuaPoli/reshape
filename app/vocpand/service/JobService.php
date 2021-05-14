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
class JobService
{
    public static function jobList($data)
    {
        $list = (new Job())->jobList($data);

        return $list;

    }
    /*
     * 获取工作详情
     * */
    public static function jobDetail($id)
    {
        $job = Job::detail($id);
        $guestbooks = Db::name('guestbook')->where('user_id',$job['user_id'])
            ->select()->toArray();

        $data = ['job'=>$job,'guestbooks' => $guestbooks];
        return $data;
    }

    /*
     * 点赞
     * */
    public static function praise($id, $praise)
    {
        $job = Db::name('job')->find($id);
        $count = $job['praise'];
        if($praise == 'true'){
            $count += 1;
            Db::name('job')->where('id',$id)->update(['praise'=>$count]);
            return true;
        }
        $count = $count - 1;
        $count < 0 ? 0 : $count;
        Db::name('job')->where('id',$id)->update(['praise'=>$count]);
        return false;
    }
}