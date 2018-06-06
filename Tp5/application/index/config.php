<?php
/*
 * 后台模块配置文件
 */
return [
    // 默认AJAX 数据返回格式,可选json xml ...
    'default_ajax_return'    => 'html',
    'lang_switch_on'		=>true,
    'default_lang'			=>'zh-cn',
    'url_html_suffix'		=>'',
        'qqconnect' => [
        'appid' => '101459417',
        'appkey' => '3aea4daf0e09239d012be570990baf8d',
        'callback' => 'http://www.lxxx.site/Tp5/public/index/login/qqconnectBack',
        'scope' => 'get_user_info,add_share,list_album,add_album,upload_pic,add_topic,add_one_blog,add_weibo,check_page_fans,add_t,add_pic_t,del_t,get_repost_list,get_info,get_other_info,get_fanslist,get_idolist,add_idol,del_idol,get_tenpay_addr',
        'errorReport' => true
    ],
];