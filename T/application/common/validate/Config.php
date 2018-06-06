<?php
namespace app\common\validate;

use think\Validate;

class Config extends Validate
{
    protected $rule = [
        'k' => 'require',
        'v' => 'require',
        'type' => 'require',
        'desc' => 'require',
        'texttype' => 'require',
        'textvalue' => 'require',
        'sorts' => 'require|integer|>=:1',
        'status' => 'require|in:0,1',
    ];

    protected $message = [
        'k' => '{%k_val}',
        'v' => '{%v_val}',
        'type' => '{%type_val}',
        'desc' => '{%desc_val}',
        'texttype' => '{%texttype_val}',
        'textvalue' => '{%textvalue_val}',
        'sorts' => '{%sorts_val}',
        'status' => '{%status_val}',
    ];

    protected $scene = [
        'add'   => ['k', 'v', 'type', 'desc', 'texttype', 'textvalue', 'sorts', 'status'],
        'edit'  => ['k', 'v', 'type', 'desc', 'texttype', 'textvalue', 'sorts', 'status'],
        'status' => ['status'],
        'k' => ['k'],
        'v' => ['v'],
        'desc' => ['desc'],
        'type' => ['type'],
        'sorts' => ['sorts'],
    ];
}