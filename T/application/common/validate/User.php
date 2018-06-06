<?php
namespace app\common\validate;

use think\Validate;

class User extends Validate
{
    protected $rule = [
        'username' => 'require|min:1|unique:user',
        'password' => 'require|min:6',
        'repassword' => 'require|confirm:password',
        'email' => 'email|unique:user',
        'mobile' => '/^1[34578]\d{9}$/|unique:user',
        'status' => 'require|in:0,1',
    ];

    protected $message = [
        'username.require' => '{%username_require}',
        'username.min' => '{%username_min}',
        'username.unique' => '{%username_unique}',
        'password' => '{%password_val}',
        'password.min' => '{%password_min}',
        'repassword' => '{%repassword_val}',
        'email' => '{%email_val}',
        'email.unique' => '{%email_unique}',
        'mobile' => '{%mobile_val}',
        'mobile.unique' => '{%mobile_unique}',
        'status' => '{%status_val}',
    ];

    protected $scene = [
        'add'   => ['username', 'password', 'repassword', 'email', 'mobile', 'status'],
        'edit'  => ['email', 'mobile', 'sex', 'status'],
        'password' => ['password', 'repassword'],
        'status' => ['status'],
        'name' => ['name'],
    ];
}