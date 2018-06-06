<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
session_start();
// [ 应用入口文件 ]

// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');
define('WEB_PATH', __DIR__);
define('DOMAIN', 'http://www.lxxx.site');
define('APP_DEBUG', true);
define('DB_FIELD_CACHE',false);
define('HTML_CACHE_ON',false);
define('URL_PATHS','http://www.lxxx.site/Tp5/public');

// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';
require_once __DIR__ . '/../vendor/php-sdk-7.2.3/autoload.php';
