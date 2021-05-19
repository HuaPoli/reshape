<?php
declare (strict_types = 1);

namespace app\vocpand\controller;
use app\exception\HttpExceptions;
use app\vocpand\model\Category;
use app\vocpand\service\OpusService;
use app\vocpand\validate\OpusValidate;
use think\facade\Config;
use think\facade\Db;
use think\facade\Filesystem;
use think\Request;
use think\exception\ValidateException;
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

    /***
     * 发布作品
     *
     */
    public function publish(Request $request)
    {
        $data = $request->post();
        (new OpusValidate())->checkParam($data);
        $files = $request->file();
        if(!empty($files)) {
            if($data['file_type'] == 'img'){
                $files = $request->file('images');
                try {
                    validate(['image'=>'filesize:20280|fileExt:jpg,png,gif|image:png,gif,jpg'])
                        ->check($files);
                    $img = '';
                    foreach($files as $file) {
                        $savename = Filesystem::disk('public')->putFile( 'opus', $file);
                        $img.= 'storage/'.str_ireplace('\\','/',$savename).'#';
                    }
                    $data['img_view'] = $img;
                } catch (ValidateException $e) {
                    throw new HttpExceptions(400, $e->getMessage(), 199);
                }
            }else {
                $file = $request->file('images');
                $savename = Filesystem::disk('public')->putFile( 'opus', $file);
                $data['img_view'] = 'storage/'.str_ireplace('\\','/',$savename);
            }
        }
        Db::name('opus')->strict(false)->insert($data);
        return $this->resultJson(0, '发布成功');
    }


}
