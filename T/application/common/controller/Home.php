<?php
namespace app\common\controller;

use think\Controller;

class Home extends Controller
{
    public function initialize()
    {
        define('ISPJAX', request()->isPjax());
        define('MODULE_NAME', request()->module());   //全小写
        define('CONTROLLER_NAME', request()->controller());   //首字母大写
        define('ACTION_NAME', request()->action());   //全小写
    }
}