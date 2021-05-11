<?php
declare (strict_types = 1);

namespace app\reshape\controller;

use think\facade\Db;
use think\Request;

class Search extends BaseContorller
{
    public function list($count = 10)
    {
        $count = intval($count);
        $list = Db::name('search')->order('count','desc')->limit(0,$count)->select()->toArray();
        $total = count($list);
        for ($i = 0; $i < $count; $i++) {
            if($i>=5)
                break;
            $list[$i]['highlight']=1;
        }
        return $this->resultJson(0,'ok',$list);
    }

}
