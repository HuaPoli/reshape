<?php
declare (strict_types = 1);

namespace app\vocpand\model;

use think\facade\Db;

/**
 * @mixin \think\Model
 */
class Answer extends BaseModel
{


    public static function saveAnswer($data)
    {
        $data['uname']  = Db::name('user')->where('id',$data['user_id'])->value('uname');
        self::create($data);
    }
}
