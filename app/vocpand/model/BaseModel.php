<?php
declare (strict_types = 1);

namespace app\vocpand\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class BaseModel extends Model
{
    protected function  prefixImgUrl($value, $data){
        $finalUrl = $value;
        return $finalUrl;
    }
}
