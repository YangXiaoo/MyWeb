<?php
namespace app\common\validate;
use think\Validate;     // 内置验证类

class Total extends Validate
{
    protected $rule = [
        'kind'=> 'require|length:1,25',
    ];
}