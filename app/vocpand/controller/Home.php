<?php
declare (strict_types = 1);

namespace app\vocpand\controller;
use app\vocpand\model\Category;
use app\vocpand\service\JobService;
use think\facade\Config;
use think\Request;

class Home extends CommonController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function list(Request $request)
    {
        $types = ['A','B','C'];
        $data = $request->get();
        if(!isset($data['page']) || !is_numeric($data['page']) || $data['page'] < 1 )
            return $this->resultJson(2, 'page参数不合法');
        if(!isset($data['type']) || !in_array($data['type'],$types))
            return $this->resultJson(2, '未传入正确的工作类型');
        $data['page'] = intval($data['page']);
        $data['pageCount']  = Config::get('setting.page_count');
        $list = JobService::jobList($data);
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
}
