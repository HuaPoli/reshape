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
}
