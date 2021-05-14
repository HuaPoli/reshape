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
}