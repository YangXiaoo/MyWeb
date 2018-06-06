<?php
return [
    //操作标题
    'c_title'           => '角色管理',
    
    //数据字段
    'id'                => 'ID',
    'module'            => '所属模块',
    'level'             => '角色等级',
    'title'             => '角色名称',
    'status'            => '状态',
    'rules'             => '角色授权',
    'notation'          => '角色描述',
    'pic'               => '角色图标',
    'recom'             => '推荐显示',
    
    //数据验证提示
    'level_val'             => '角色等级必须为数字整数',
    'title_val'             => '角色名称不能为空',
    'status_val'            => '状态必须为数字整数（0,1）',
    
    //其他
    'auth_user'         => '授权用户',
    'username'          => '用户名',
    'auth_user_info'    => '请在下面输入框中输入 <span class="badge bg-red">用户ID</span> 或 <span class="badge bg-red">用户名</span>，来检测用户是否已拥有该角色，并给用户授权角色，多个用户以 <span class="label label-danger">,</span> 分割',
    'auth_user_input'   => 'ID或用户名',
    'auth_user_btn'     => '检测',
    'auth_user_empty'   => '未检测到添加用户',
    
    //其他
    'not_edit'              => '系统默认角色不可操作',
];