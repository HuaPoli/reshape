<?php
declare (strict_types = 1);

namespace app\vocpand\model;
/**
 * @mixin \think\Model
 */
class Opus extends BaseModel
{
    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
    public function category()
    {
        return $this->hasOne(Category::class,'id','category_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class,'opus_id','id');
    }

    public function opusList($data)
    {
        $selector = Opus::with(['user', 'category','comments']);
        if(isset($data['city']) && $data['city'] != '' )
            $selector->where('city', $data['city']);
        if(isset($data['country']) && $data['country'] != '' )
            $selector->where('country', $data['country']);
        if(isset($data['category_id']) && is_numeric($data['category_id']))
            $selector->where('category_id', $data['category_id']);
        $total = $selector->count();
        $tpage = ceil(($total / $data['pageCount']));
        if(  $tpage < $data['page'] )
            return null;
        $start = ($data['page'] - 1 ) * $data['pageCount'];
        return $selector->limit($start, $data['pageCount'])->select()->toArray();


    }




}
