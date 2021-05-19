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

Route::post('api/token/user', 'Token/getToken');


Route::group('api',function () {
    Route::get('/home/job', 'Home/list');
    Route::get('/category/all', 'Home/categoryAll');
    Route::get('/job/detail/:id', 'Job/detail');
    Route::get('/opus/list', 'Opus/list');
    Route::get('/job/praise/:id/:praise', 'Job/praise');


    Route::post('/comment/job', 'Comment/job');
    Route::post('/comment/opus', 'Comment/opus');
    Route::post('/comment/answer', 'Comment/answer');

    Route::get('/opus/praise/:id/:praise', 'Opus/praise');
    Route::post('/opus/publish', 'Opus/publish');



    Route::get('/user/info', 'User/info');
    Route::post('/user/finish', 'User/finish');



});

Route::post('api/chat/get_head', 'Chat/get_head');
Route::post('api/chat/load', 'Chat/load');
Route::post('api/chat/save_message', 'Chat/save_message');
Route::post('api/chat/changeNoRead', 'Chat/changeNoRead');
Route::post('api/chat/uploadimg', 'Chat/uploadimg');
Route::post('api/chat/get_name', 'Chat/get_name');
Route::post('api/chat/get_list', 'Chat/get_list');
