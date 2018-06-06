<?php
namespace app\common\validate;
use think\Validate;     // 内置验证类

class Bill extends Validate
{
    protected $rule = [
        'consume'=> 'require|length:1,25',
    ];
}