<?php
/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 2021/5/10
 * Time: 9:47
 */

namespace app\vocpand\service;

use app\vocpand\model\Job;

use think\facade\Request;
class JobService
{
    public static function jobList($data)
    {
        $list = (new Job())->jobList($data);
        if($list == null)
            return $list;
        $url = 'https://'.Request::host().'/';
        $jobs = [];
        foreach ($list as $value) {
            $user = $value['user'];
            $user['uimg'] = $url . $user['uimg'];
            $value['user'] = $user;
            array_push($jobs, $value);

        }
        return $jobs;

    }
}