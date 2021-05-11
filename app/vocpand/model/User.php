<?php
declare (strict_types = 1);

namespace app\vocpand\model;

/**
 * @mixin \think\Model
 */
class User extends BaseModel
{

    public static function getByOpenID($openid)
    {
        return self::where('openid', $openid)->find();
    }
}
