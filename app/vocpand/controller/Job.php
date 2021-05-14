<?php
declare (strict_types = 1);

namespace app\vocpand\controller;

use app\vocpand\service\JobService;
use think\Request;

class Job extends CommonController
{
    public function detail($id)
    {
        $data = JobService::jobDetail($id);
        return $this->resultJson(0, 'ok',$data);
    }

    /*
     * 工作点赞
     * */

    public function praise($id,$praise)
    {
        $id = intval($id);
        if(!is_int($id))
            return  $this->resultJson(2,'请求参数不合法');

        $isPraise = JobService::praise($id, $praise);
        if($isPraise)
            return $this->resultJson(0, '点赞成功');
        return $this->resultJson(0, '已取消点赞');
    }

}
