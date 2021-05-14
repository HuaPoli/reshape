<?php
declare (strict_types = 1);

namespace app\vocpand\controller;
use app\vocpand\model\Category;
use app\vocpand\service\OpusService;
use think\facade\Config;
use think\Request;
class Opus extends CommonController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function list(Request $request)
    {

        $data = $request->param();
        if(!isset($data['page']) || !is_numeric($data['page']) || $data['page'] < 1 )
            return $this->resultJson(2, 'page参数不合法');
        $data['page'] = intval($data['page']);
        $data['pageCount']  = Config::get('setting.page_count');
        $list = OpusService::opusList($data);
        if($list == null)
            return $this->resultJson(9999, '已经是最后一页',[]);
        return $this->resultJson(0, 'ok',$list);
    }

    /*
     * 获取全部分类
     *
     * */
    public function categoryAll()
    {
       $list = Category::listAll();
        return $this->resultJson(0, 'ok',$list);
    }

    /*
   * 评论点赞
   * */
    public function praise($id,$praise)
    {
        $id = intval($id);
        if(!is_int($id))
            return  $this->resultJson(2,'请求参数不合法');

        $isPraise = OpusService::praise($id, $praise);
        if($isPraise)
            return $this->resultJson(0, '点赞成功');
        return $this->resultJson(0, '已取消点赞');
    }
}
