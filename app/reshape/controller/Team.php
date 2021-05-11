<?php
declare (strict_types = 1);

namespace app\reshape\controller;

use think\facade\Db;
use think\Request;

class Team extends BaseContorller
{
    public function detail()
    {
        $list = Db::name('team')->find(1);
        $data = $this->tranformImg([$list],['about_img']);
        return $this->resultJson(0,'ok',$data);
    }

}
