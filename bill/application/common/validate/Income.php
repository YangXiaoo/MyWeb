<?php
namespace app\common\validate;
use think\Validate;     // 内置验证类

class Income extends Validate
{
    protected $rule = [
        'income'=> 'require|length:1,25',
    ];
}