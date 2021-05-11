<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;
Route::miss(function () {
   return json(['code'=>0, 'msg'=> '未找到请求的url地址，请检查请求路径是否正确！','data'=> []],404);
});

Route::get('api/home','Home/index');
Route::get('api/more/page/:page/count/:count','Home/more');
Route::get('api/category/all', 'Category/getAll');
Route::get('api/product_by_category/id/page/count/:id/[:page]/[:count]', 'Product/getListByCategoryId');
Route::get('api/product/detail/:id', 'Product/detail');
Route::get('api/product/praise/:id/:praise', 'Product/praise');
Route::get('api/product/search/:keyword', 'Product/search');
Route::get('api/hot/search/[:count]', 'Search/list');

Route::post('api/product/publish', 'Product/publish');
Route::post('api/product/step', 'Product/step');

Route::get('api/team/detail', 'Team/detail');

Route::post('api/recruitment/zx', 'Recruitment/zx');



