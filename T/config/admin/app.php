<?php
return [
    // URL伪静态后缀
    'url_html_suffix'        => '',
    
    //系统默认头像地址
    'default_avatar' => '/static/global/image/avatar.png',
    
    //AUTH 权限配置
    'AUTH_CONFIG'=>[
        'AUTH_ON'           => true,                //认证开关
        'AUTH_TYPE'         => 1,                   //认证方式，1为实时认证；2为登录认证
        'AUTH_GROUP'        => 'auth_group',        //用户组数据表名
        'AUTH_GROUP_ACCESS' => 'auth_group_access', //用户-用户组关系表
        'AUTH_RULE'         => 'auth_rule',         //权限规则表
        'AUTH_USER'         => 'user',              //用户信息表
        //系统管理员ID【不可被删除】
        'AUTH_ADMIN'        => [42],
        //系统用户组别【不可被删除】
        'AUTH_USER_GROUP'   => [1,2],
    ],
];
