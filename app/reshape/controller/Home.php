<?php
declare (strict_types = 1);

namespace app\reshape\controller;
use think\facade\Db;
use think\facade\Request;

class Home
{
    public function index() {
        $slogan = $this->getSlogan();
        $banner = $this->getBannder();
        $newest = $this->getNewest();
        $rank = $this->getRank();
        $recommend = $this->getRecommend();
        $illustration = $this->getIllustration();
        $grid = $this->getGrid();
        $data = ['slogan' =>$slogan,
                'banner'=>$banner,
                'newest'=>$newest,
                'rank'=>$rank,
                'recommend'=>$recommend,
                'illustration'=> $illustration,
                'grid' => $grid
        ];
        $count = Db::name('team')->where('id',1)->value('browse') + 1;
        Db::name('team')->where('id',1)->update(['browse' => $count]);
        return json(['code'=>0, 'msg'=> 'ok','data'=> $data],200);
    }

//    下拉加载更多
    public function more($page,$count)
    {
       if(!is_numeric($page) || !is_numeric($count) || $count < 1 )
           return json(['code'=>2, 'msg'=> '请求参数不合法，确定携带的参数为正整数','data'=> []],200);
        $page < 0 ? 1 : $page;
        $page = intval($page);
        $count = intval($count);
//           当前页 - 1 x 显示条数 + 10
        $total = Db::name('product')->count() - 10 ;
        $totalPage = intval(ceil($total / $count));
        $end = false;
        if($page >= $totalPage ) {
            $page = $totalPage;
            $end = true;
        }
        $start = ($page - 1) * $count + 10;
        $list = Db::name('product')->limit($start,$count)->select()->toArray();
        $list = $this->tranformImg($list,'small_img');
        $recommend = $this->tranformImg($list,'big_img');
        $data = ['end' => $end,'recommend'=> $recommend];
        return json(['code'=>0, 'msg'=> 'ok','data'=> $data],200);
    }


//    获取标语
    private function getSlogan() {
        $list = Db::name('slogan')->select()->toArray();
        $total = count($list);
        $num = intval($total / 10  );
        if($num < 2)
            return $list;
        $random = rand(1,$num);
        $slogan = [];
        for ($i = 0; $i < $total; $i++) {
            if(count($slogan) >= 10)
                break;
            if(($i + 1 ) % $random == 0)
                array_push($slogan,$list[$i]);
        }
        return $slogan;
    }

//    获取轮播图
    private function getBannder()
    {
        $list = Db::name('banner')->select()->toArray();
        return $this->tranformImg($list, 'img');
    }


//    获取每周上新
    private function getNewest()
    {
        $list = Db::name('newest')->limit(8)->select()->toArray();
        return $this->tranformImg($list,'newest_img');
    }
    private function getRank()
    {
        $list = Db::name('rank')->order('num')->select()->toArray();
        return $this->tranformImg($list,'rank_img');
    }
    private function getRecommend()
    {
        $list = Db::name('product')->limit(0,10)->select()->toArray();
        $list = $this->tranformImg($list,'small_img');
        return $this->tranformImg($list,'big_img');
    }
//    获取九宫格分类
    private function getGrid()
    {
        $list = Db::name('category')
            ->where('parent_id' , 0)
            ->select()->toArray();
        $list = $this->tranformImg($list, 'img');
        return $this->tranformImg($list, 'icon');
    }

//    获取 illustration
    private function getIllustration()
    {
        $list = Db::name('illustration')->select()->toArray();
        return $this->tranformImg($list, 'url');
    }
//    转换图片地址
    private function  tranformImg($list,$field) {
        $url = Request::host().'/';
		$url = 'https://'.$url;
        $data = [];
        foreach ($list as $value) {
            $value[$field] = $url . $value[$field];
            array_push($data, $value);
        }
        return $data;
    }

}
