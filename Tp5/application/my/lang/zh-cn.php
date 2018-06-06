<?php

return [
    //操作成功提示信息
    'action'            => '操作',
    'action_success'    => '操作成功',
    'action_fail'       => '操作失败',
    'not_admin'         => '该账号不是管理员',
    
    //按钮字段
    'list'              => '列表',
    'create'            => '新增',
    'edit'              => '编辑',
    'delete'            => '删除',
    'search'            => '搜索',
    'back'              => '返回',
    'reset'             => '撤销',
    'submit'            => '提交',
    
    //info
    'base_param'        => '基本参数',
    'advanced_param'    => '高级参数',
    'video_param'       => '视频参数',
    'album_param'       => '相册参数',
    'other_param'       => '其他参数',
    'base_password'     => '修改密码',
    'base_avatar'       => '修改头像',
    'user_info'         => '附加信息',
    
    'code_error'        => '验证码错误',
    'password_error'    => '密码错误',
    'login_success'     => '登陆成功',
    'user_no_exist'     => '用户不存在',
    'auth_no_exist'     => '没有权限',
    'user_stop'         => '用户被停用',
    'no-up-file'        => '没有上传文件',
    'isbrowse'          => '当前处于浏览模式，不允许修改任何数据',
    'clean_cache'       => '清除缓存',
    'web_home'          => '网站首页',
    'Upload'            => '上传',
    'login_other'       => '账户已在其他地方登陆，请重新登录',
    'login_timeout'     => '登陆超时',
    
    //Select-Option选项字段
    'please_choose'     => '请选择',
    
    'status0'           => '停用',
    'status1'           => '在用',
    
    'ismenu0'           => '否',
    'ismenu1'           => '是',
    
    'yes_no0'           => '否',
    'yes_no1'           => '是',
    
    'sex0'              => '女',
    'sex1'              => '男',

    'switch0'           => '否',
    'switch1'           => '是',
    
    'target_self'       => '当前页',
    'target_blank'      => '新页面',
    
    'auth_level_1'      => '项目',
    'auth_level_2'      => '模块',
    'auth_level_3'      => '操作',
    //=====================================index
    'user_num'          => '用户数',
    'archive_num'       => '文章数',
    'new_user_num'      => '最近一周注册用户',
    'new_guestbook_num' => '最近一周留言数',
    
    'login_count'       => '登录统计',
    'login_30_count'    => '最近30天用户登录统计',
    'login_new'         => '最新登录',
    'login_l_ip'        => 'IP',
    'login_l_address'   => '位置',
    'login_l_time'      => '登录时间',
    
    'user_l_group'      => '用户组',
    
    'system_config'     => '系统配置',
    
    'feedback'          => '系统反馈',
    
    'more'              => '更多',
    /*===============================Arctype=============================*/
    'id'                => 'ID',
    'pid'               => '上级分类',
    'typename'          => '分类名称',
    'mid'               => '分类模型',
    'target'            => '弹出方式',
    'jumplink'          => '跳转链接',
    'dirs'              => '分类目录',
    'litpic'            => '缩略图',
    'content'           => '内容',
    'sorts'             => '排序',
    'status'            => '状态',
    'keywords'          => '关键字',
    'description'       => '描述',
    'templist'          => '列表页模板',
    'temparticle'       => '内容页模板',
    'pagesize'          => '分页条数',
    'create_time'       => '创建时间',
    'update_time'       => '编辑时间',
    
    //数据验证提示
    'pid_val'               => '上级分类必须为数字整数',
    'typename_val'          => '分类名称不能为空',
    'mid_val'               => '分类模型必须为数字整数',
    'dirs_require'          => '分类目录不能为空',
    'dirs_val'              => '分类目录必须为（数字字母-_）',
    'target_val'            => '弹出方式不能为空',
    'templist_val'          => '列表页模板必须为（数字字母_）',
    'temparticle_val'       => '内容页模板必须为（数字字母_）',
    'pagesize_val'          => '分页条数必须为大于0数字整数',
    
    'sorts_val'             => '排序必须为大于0数字整数',
    'status_val'            => '状态必须为数字整数（0,1）',
    
    //其他
    'top_arctype'           => '顶级分类',
    'preview'               => '预览',
    'not_edit'              => '系统默认文章模型不可编辑',
    'not_delete'            =>'系统默认文章模型不可删除',
    'create_arc'            => '新增文章',
    //=====================================================================
    //<?php


    'id'                => 'ID',
    'typeid'            => '所属分类',
    'title'             => '标题',
    'title_color'       => '标题颜色',
    'title_weight'      => '标题加粗',
    'flag'              => '属性',
    'jumplink'          => '跳转地址',
    'litpic'            => '缩略图',
    'writer'            => '作者',
    'source'            => '来源',
    'keywords'          => '关键字',
    'description'       => '描述',
    'click'             => '点击数',
    'status'            => '状态',
    'create_time'       => '创建时间',
    'update_time'       => '编辑时间',
    
    'content'           => '内容',
    
    'video_url'         => '视频地址',
    
    //数据验证提示
    'typeid_val'            => '所属分类不能为空',
    'title_val'             => '标题不能为空',
    'click_val'             => '点击数必须为大于等于0数字整数',
    'create_time_val'       => '创建时间不能为空',
    'status_val'            => '状态必须为数字整数（0,1）',
    
    //其他
    'preview'               => '预览',
    'no_arctype'            => '[无分类]',
    'not_edit'              => '系统默认文章模型不可编辑',
    'flag_c'                => '推荐',
    'flag_a'                => '特荐',
    'flag_h'                => '头条',
    'flag_s'                => '滚动',
    'flag_p'                => '图片',
    'flag_j'                => '跳转',
    'name'              => '文章模型名称',
    'mod'               => '文章模型操作',

];