<?php
declare (strict_types = 1);

namespace app\vocpand\model;

use think\facade\Db;

/**
 * @mixin \think\Model
 */
class Comment extends BaseModel
{
    public function answers()
    {
        return $this->hasMany(Answer::class,'comment_id','id');
    }

    public static function saveComment($data)
    {
        $data['uname']  = Db::name('user')->where('id',$data['user_id'])->value('uname');
        self::create($data);
    }


    public static function saveAnswer($data)
    {
        $data['uname']  = Db::name('user')->where('id',$data['user_id'])->value('uname');
        self::create($data);

    }
}
