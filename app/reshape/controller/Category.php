<?php
declare (strict_types = 1);

namespace app\reshape\controller;

use think\facade\Db;
use think\Request;

class Category extends BaseContorller
{
//    获取所有分类
    public function getAll()
    {
        $list = Db::name('category')->select()->toArray();
        $category = $this->tranformImg($list,['img','icon']);
        return $this->resultJson(0, 'ok', $category);
    }

}
