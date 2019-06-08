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
//阿里云OSS配置
    'aliyun_oss' => [
        'KeyId'      => 'LTAIzJXcO2XYyUgw',  //您的Access Key ID
        'KeySecret'  => 'toNewbPtdqw16XCO7CeaL4HEMXbpK9',  //您的Access Key Secret
        'Endpoint'   => 'http://oss-cn-hangzhou.aliyuncs.com',  //阿里云oss 外网地址endpoint
        'Bucket'     => 'yangxiao',  //Bucket名称
    ],
       'ACCESSKEY' => 'M23soq7m3vhpTZfVh8MGpUGdh6vslluDhM_B7ChF',//你的accessKey
    'SECRETKEY' => '5WbY7ZEmS08XD94chkY_-PkarPijxqLzaGPDSGBJ',//你的secretKey
    'BUCKET' => 'yangxiao',//上传的空间
    'DOMAIN'=>'
p5o8ifx24.bkt.clouddn.com',//空间绑定的域名

];