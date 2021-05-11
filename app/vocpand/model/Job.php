<?php
declare (strict_types = 1);

namespace app\vocpand\model;
use think\facade\Db;

/**
 * @mixin \think\Model
 */
class Job extends BaseModel
{
    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
    public function category()
    {
        return $this->hasOne(Category::class,'id','category_id');
    }


    public function jobList($data)
    {
        $selector = Job::with(['user','category'])
            ->where('type',$data['type']);
        if(isset($data['keywords']) && $data['keywords'] != '' )
            $selector->where('title', 'like', $data['keywords']);
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
