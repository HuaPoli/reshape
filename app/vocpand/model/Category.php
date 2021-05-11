<?php
declare (strict_types = 1);

namespace app\vocpand\model;

/**
 * @mixin \think\Model
 */
class Category extends BaseModel
{
    public static function listAll()
    {
        return self::select()->toArray();
    }
}
