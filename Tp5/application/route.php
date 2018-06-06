<?php
use think\Route;

Route::rule('category/:dirs', 'index/Category/index', 'GET');   //栏目
Route::rule('detail/:dirs/:id', 'index/Detail/index', 'GET');   //文章
Route::rule('profile/index/:uid', 'index/Profile/index', 'GET');
Route::rule('userdetail/:dirs/:id', 'index/Userdetail/index', 'GET');
Route::rule('usercategory/:dirs/:id', 'index/Usercategory/index', 'GET');
Route::rule('cate/:dirs', 'home/Category/index', 'GET');   //栏目
Route::rule('deta/:dirs/:id', 'home/Detail/index', 'GET');   //文章

//api2.0
return [
        'api/flink/demo'           => 'api/flink/demo',               //测试友情链接自定义接口
        '__rest__'=>[
                'api/archive'       => 'api/archive',
                'api/flink'         => 'api/flink',
        ],
];