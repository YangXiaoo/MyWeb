<?php
namespace app\common\validate;

use think\Validate;

class Archive extends Validate
{
    protected $rule = [
        'typeid' => 'require|integer',
        'title' => 'require',
        'click' => 'require|integer|>=:0',
        'status' => 'require|in:0,1',
    ];

    protected $message = [
        'typeid' => '{%typeid_val}',
        'title' => '{%title_val}',
        'click' => '{%click_val}',
        'status' => '{%status_val}',
    ];

    protected $scene = [
        'add'   => ['typeid', 'title', 'click', 'status'],
        'edit'  => ['typeid', 'title', 'click', 'status'],
        'status' => ['status'],
        'title' => ['title'],
    ];
}