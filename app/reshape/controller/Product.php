<?php
declare (strict_types = 1);

namespace app\reshape\controller;

use think\facade\Db;
use think\Request;
use app\reshape\validate\ProductValidate;
use app\reshape\validate\StepValidate;
use think\exception\ValidateException;
use think\facade\Filesystem;
class Product extends BaseContorller
{
//    添加步骤
    public function step(Request $request)
    {
        $data = $request->post(['step','step_desc','product_id']);
        try {
            $result = validate(StepValidate::class)->batch(true)->check($data);

            if (true !== $result) {
                // 验证失败 输出错误信息
                return $this->resultJson(2, 'ok', $result);
            }
            $files = $request->file();
            if(!empty($files)) {
                $file = $request->file('step_img');
                $savename = Filesystem::disk('public')->putFile( 'steps', $file);
                $data['step_img'] = 'storage/'.str_ireplace('\\','/',$savename);
                if($request->post('finished') == 'true')
                    Db::name('product')->where('id',$data['product_id'])
                        ->update(['small_img'=>$data['step_img'],'big_img'=>$data['step_img']]);
            }
            $steps = Db::name('steps')->where('product_id',$data['product_id'])
                ->where('step',$data['step'])->find();
            if(!empty($steps)){
                Db::name('steps')->where('id', $steps['id'])->update($data);
            }else{
                Db::name('steps')->strict(false)->save($data);
            }
            return $this->resultJson(0, 'ok');
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            return $this->resultJson(2, $e->getMessage());
        }

    }



//    发布作品
    public function publish(Request $request)
    {
        $data = $request->post();
        try {
            $result = validate(ProductValidate::class)->check($data);
            if(!$result)
                return $this->resultJson(2, '未传入规定的参数');
            $data['category_id'] = intval($data['category_id']);
            $id = Db::name('product')->strict(false)->insertGetId($data);
            return $this->resultJson(0,'ok',['product_id'=>$id]);


        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            return $this->resultJson(2, $e->getMessage());
        }
    }




//    根据分类id查询
    public function getListByCategoryId($id, $page = 1, $count = 8)
    {
        $id = intval($id);
        $page = intval($page);
        $count = intval($count);
       if(is_int($id) && is_int($page) && is_int($count)) {
           $category = Db::name('category')->find($id);
           $ids = [$id];
           if($category['parent_id'] == 0 )
               $ids = Db::name('category')->where('parent_id',$id)->column('id');
           $selector = Db::name('product')->whereIn('category_id',$ids);
           $total = $selector->count();
           $totalPage = intval(ceil($total / $count));
           if($page > $totalPage)
               return json(['code' => 0, 'msg' => '已是最后一页', 'data' => ['product'=>[],'end'=>true]], 200);
           $start = ($page - 1) * $count;
           $list = $selector->limit($start, $count)->select()->toArray();
           $product = $this->tranformImg($list,['small_img','big_img']);
           $data = ['end' => false,'product' => $product];
           return $this->resultJson(0, 'ok', $data);
       }
        return $this->resultJson(2, '请求参数不合法');
    }


//    根据id获取详情
    public function detail($id)
    {
        $id = intval($id);
        if(!is_int($id))
          return  $this->resultJson(2,'请求参数不合法');
        $product = Db::name('product')->find($id);
        if($product == null)
           return $this->resultJson(2,'未找到相关作品');

        $list = Db::name('steps')->where('product_id',$id)->select()->toArray();
        $steps = $this->tranformImg($list,['step_img']);

        $product = $this->tranformImg([$product],['small_img','big_img']);
        $product[0]['steps'] = $steps;
        $data = $product[0];
         return $this->resultJson(0, 'ok', $data);

    }

//    点赞
    public function praise($id,$praise)
    {
        $id = intval($id);
        if(!is_int($id))
            return  $this->resultJson(2,'请求参数不合法');
        $product = Db::name('product')->find($id);
        $count = $product['praise'];
       if($praise == 'true'){
           $count += 1;
           Db::name('product')->where('id',$id)->update(['praise'=>$count]);
           $sum = Db::name('team')->where('id', 1)->value('sum_praise') + 1;
           Db::name('team') ->where('id' , 1)->update(['sum_praise'=> $sum]);
           return $this->resultJson(0, '点赞成功');
       }
       $count = $count - 1;
       $count < 0 ? 0 : $count;
        Db::name('product')->where('id',$id)->update(['praise'=>$count]);
        return $this->resultJson(0, '已取消点赞');
    }

    //搜索
    public function search($keyword)
    {
        $search = Db::name('search')->where('keyword', $keyword)->find();
        if(empty($search)){
            Db::name('search')->save(['keyword'=>$keyword,'count'=>1]);
        } else {
            $search['count'] += 1;
            Db::name('search')->save($search);
        }
        $keyword.= '%';
        $list = Db::name('product')->where('title', 'like', $keyword)
            ->whereOr('subtitle', 'like', $keyword)
            ->whereOr('tags','like',$keyword)
            ->whereOr('author','like',$keyword)
            ->select()->toArray();
        $product = $this->tranformImg($list,['small_img','big_img']);
        return $this->resultJson(0, 'ok', $product);
    }


}

