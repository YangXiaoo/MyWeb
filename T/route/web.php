<?php

Route::rule('category/:dirs', 'index/Category/index', 'GET');   //栏目
Route::rule('detail/:dirs/:id', 'index/Detail/index', 'GET');   //文章

