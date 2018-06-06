<?php
use app\index\model\Arctype;
use app\index\model\Archive;
use app\index\model\Config;
use app\index\model\Flink;
use app\index\model\Banner;
use app\index\model\ArcComment;
use app\index\model\ArcThumbsup;
use app\index\model\User;
use app\index\model\UserArchive;
use app\index\model\UserArctype;

/**
 * @Title: ajaxReturn
 * @Description: todo(ajax提交返回状态信息)
 * @param string $info
 * @param url $url
 * @param string $status
 * @author duqiu
 * @date 2016-5-12
 */
function ajaxReturn($info='', $url='', $status='', $data = ''){
    if(!empty($url)){   //操作成功
        $result = array( 'info' => '操作成功', 'status' => 1, 'url' => $url, );
    }else{   //操作失败
        $result = array( 'info' => '操作失败', 'status' => 0, 'url' => '', );
    }
    if(!empty($info)){$result['info'] = $info;}
    if(!empty($status)){$result['status'] = $status;}
    if(!empty($data)){$result['data'] = $data;}
    echo json_encode($result);
    exit();
}


/**
 * @Title: channeldata
 * @Description: todo(当前ID的平级栏目)
 * @param int $pid 上级栏目ID
 * @return array
 * @author 苏晓信
 * @date 2017年7月2日
 * @throws
 */
function channeldata($pid){
    $arctype = new Arctype();
    return $arctype->channeldata($pid);
}

/**
 * @Title: channel
 * @Description: todo(直接输出导航链接)
 * @param int $pid 上级栏目ID
 * @param int $nowid 当前显示ID
 * @param string $ishome 是否显示首页
 * @param string $leftlabel 左标签
 * @param string $rightlabel 右标签
 * @param string $class 类名
 * @return string
 * @author 苏晓信
 * @date 2017年7月2日
 * @throws
 */
function channel($pid, $nowid='', $ishome='', $leftlabel="", $rightlabel="", $class=""){
    $arctype = new Arctype();
    return $arctype->channel($pid, $nowid, $ishome, $leftlabel, $rightlabel, $class);
}

/**
 * @Title: arctypefield
 * @Description: todo(指定查询栏目键值)
 * @param int $id 栏目ID
 * @param string $key 查询键值
 * @author 苏晓信
 * @date 2017年7月2日
 * @throws
 */
function arctypefield($id, $key){
    $arctype = new Arctype();
    return $arctype->arctypefield($id, $key);
}

/**
 * @Title: typename
 * @Description: todo(栏目名称)
 * @param int $id 栏目ID
 * @author 苏晓信
 * @date 2017年7月2日
 * @throws
 */
function typename($id){
    $arctype = new Arctype();
    return $arctype->typename($id);
}

/**
 * @Title: typelink
 * @Description: todo(栏目完整链接)
 * @param int $id 栏目ID
 * @author 苏晓信
 * @date 2017年7月2日
 * @throws
 */
function typelink($id){
    $arctype = new Arctype();
    return $arctype->typelink($id);
}

/**
 * @Title: toparctypedata
 * @Description: todo(返回当前栏目的顶级栏目数据)
 * @param int $id
 * @author 苏晓信
 * @date 2017年7月2日
 * @throws
 */
function toparctypedata($id){
    $arctype = new Arctype();
    return $arctype->topArctypeData($id);
}
function arctCount($num){
    $arctypes = new Arctype();
    return $arctypes->arctCount($num);
}

/**
 * @Title: position
 * @Description: todo(当前位置)
 * @param int $id
 * @param string $home
 * @param string $line
 * @return string
 * @author 苏晓信
 * @date 2017年7月2日
 * @throws
 */
function position($id, $home="首页", $line=">"){
    $arctype = new Arctype();
    return $arctype->position($id, $home, $line);
}

/**
 * @Title: arclist
 * @Description: todo(查询栏目下的文章)
 * @param int $typeid 栏目ID（当前栏目下的所有[无限级]栏目ID）
 * @param int $limit 查询数量
 * @param string $flag 推荐[c] 特荐[a] 头条[h] 滚动[s] 图片[p] 跳转[j]
 * @param string $order 排序
 * @return array
 * @author 苏晓信
 * @date 2017年7月5日
 * @throws
 */
function arclist($typeid='1', $limit='', $flag='', $order='id DESC'){
    $archive = new Archive();
    return $archive->arclist($typeid, $limit, $flag, $order);
}
/**
 * @Title: atc
 * @Description: tf_archive中查询最新的
 * @param $limit 显示数据量
 * @param string $order 查询方法
 * @return array
 * @author 杨潇
 * @date 2018年2月11日
 * @throws
 */
 function atc($limit,$order){
    $archive = new Archive();
    return $archive->atc($limit,$order);
}
/**
 * @Title: prenext
 * @Description: todo(上一篇、下一篇)
 * @param array $archiveArr 当前文档数组
 * @return string
 * @author 苏晓信
 * @date 2017年7月5日
 * @throws
 */
function prenext($archiveArr){
    $archive = new Archive();
    return $archive->prenext($archiveArr);
}

/**
 * @Title: click
 * @Description: todo(文档点击数+1)
 * @param array $archiveArr 当前文档数组
 * @author 苏晓信
 * @date 2017年7月6日
 * @throws
 */
function click($archiveArr){
    $archive = new Archive();
    $archive->click($archiveArr);
}

/**
 * @Title: confv
 * @Description: todo(获取配置值)
 * @param string $k
 * @param string $type
 * @return string
 * @author 苏晓信
 * @date 2017年8月26日
 * @throws
 */
function confv($k, $type = 'web'){
    $config = new Config();
    return $config->confv($k, $type);
}
/**
 * @Title: flinks
 * @Description: todo(友情链接)
 * @return string
 * @author 杨潇
 * @date 2018年2月11日
 * @throws
 */
function flinks($mid, $limit=''){
    $flink = new Flink();
    return $flink->flinks($mid,$limit='');
}

/**
 * @Title: banners
 * @Description: todo(banner模块数据)
 * @param int $mid
 * @param string $limit
 * @author 苏晓信
 * @date 2017年8月26日
 * @throws
 */
function banners($mid, $limit=''){
    $banner = new Banner();
    return $banner->banners($mid, $limit);
}

function tag(){
    
}
/**
 * @Title: search_url
 * @Description: todo(搜索的地址)
 * @param string $delparam
 * @return string
 * @author 苏晓信
 * @date 2018年1月11日
 * @throws
 */
function search_url($delparam){
    $url_path = '';
    $get = input('get.');
    if( isset($get[$delparam]) ){ unset($get[$delparam]); }
    if( isset($get['_pjax'])   ){ unset($get['_pjax']);   }
    if(!empty($get)){
        $paramStr = [];
        foreach ($get as $k=>$v){
            $paramStr[] = $k.'='.$v;
        }
        $paramStrs = implode('&', $paramStr);
        $url_path = $url_path.'?'.$paramStrs;
    }
    return $url_path;
}

/**
 * @Title: page_param
 * @Description: todo(分页额外参数)
 * @return array
 * @author 苏晓信
 * @date 2018年1月13日
 * @throws
 */
function page_param(){
    $param = request()->param();
    if (isset($param['_pjax'])){
        unset($param['_pjax']);
    }
    $res['query'] = $param;
    return $res;
}

/**
 * @Title: table_sort
 * @Description: todo(列表table排序)
 * @param string $param
 * @return string
 * @author 苏晓信
 * @date 2018年1月13日
 * @throws
 */
function table_sort($param){
    $url_path = '';
    $faStr = 'fa-sort';
    $get = input('get.');
    if( isset($get['_pjax']) ){ unset($get['_pjax']); }

    if( isset($get['_sort']) ){   //判断是否存在排序字段
        $sortArr = explode(',', $get['_sort']);
        if ( $sortArr[0] == $param ){   //当前排序
            if ($sortArr[1] == 'asc'){
                $faStr = 'fa-sort-asc';
                $sort = 'desc';
            }elseif ($sortArr[1] == 'desc'){
                $faStr = 'fa-sort-desc';
                $sort = 'asc';
            }
            $get['_sort'] = $param.','.$sort;
        }else{   //非当前排序
            $get['_sort'] = $param.',asc';
        }
    }else{
        $get['_sort'] = $param.',asc';
    }
    $paramStr = [];
    foreach ($get as $k=>$v){
        $paramStr[] = $k.'='.$v;
    }
    $paramStrs = implode('&', $paramStr);
    $url_path = $url_path.'?'.$paramStrs;
    return "<a class=\"fa ".$faStr."\" href=\"".$url_path."\"></a>";
}

/**
 * @Title: del_arr_empty
 * @Description: todo(删除二维数组中的空值)
 * @param array $arr
 * @return array
 * @author 苏晓信
 * @date 2018年1月23日
 * @throws
 */
function del_arr_empty($arr){
    foreach ($arr as $key => $value){
        if ($value == ''){
            unset($arr[$key]);
        }
    }
    return $arr;
}

/**
 * @Title: deldir
 * @Description: todo(删除文件和文件夹)
 * @param string $dir
 * @param string $folder【y:同时删除文件夹,n:只删除文件】
 * @return boolean
 * @author 苏晓信
 * @date 2018年1月26日
 * @throws
 */
function deldir($dir, $folder='n'){
    //删除当前文件夹下得文件（并不删除文件夹）：
    $dh=opendir($dir);
    while ($file=readdir($dh)) {
        if($file!="." && $file!="..") {
            $fullpath=$dir."/".$file;
            if(!is_dir($fullpath)) {
                unlink($fullpath);
            } else {
                deldir($fullpath,$folder);
            }
        }
    }
    closedir($dh);
    //删除当前文件夹
    if($folder=='y'){
        if(rmdir($dir)){
            return true;
        } else {
            return false;
        }
    }
}

/**
 * @Title: trimall
 * @Description: todo(清除字符串中的空格和换行)
 * @param string $str
 * @return string
 * @author 苏晓信
 * @date 2018年2月2日
 * @throws
 */
function trimall($str){
    $qian=array(" ","　","\t","\n","\r");
    $hou=array("","","","","");
    return str_replace($qian, $hou, $str);
}

/**
 * @Title: csubstr
 * @Description: todo(中文字符串截取长度)
 * @param string $str
 * @param int $length
 * @param string $charset
 * @param int $start
 * @param boolean $suffix
 * @return string
 * @author 苏晓信
 * @date 2018年2月2日
 * @throws
 */
function csubstr($str, $length, $charset="", $start=0, $suffix=true) {
    if (empty($charset))
        $charset = "utf-8";
        if (function_exists("mb_substr")) {
            if (mb_strlen($str, $charset) <= $length)
                return $str;
                $slice = mb_substr($str, $start, $length, $charset);
        }else {
            $re['utf-8'] = "/[\x01-\x7f]¦[\xc2-\xdf][\x80-\xbf]¦[\xe0-\xef][\x80-\xbf]{2}¦[\xf0-\xff][\x80-\xbf]{3}/";
            $re['gb2312'] = "/[\x01-\x7f]¦[\xb0-\xf7][\xa0-\xfe]/";
            $re['gbk'] = "/[\x01-\x7f]¦[\x81-\xfe][\x40-\xfe]/";
            $re['big5'] = "/[\x01-\x7f]¦[\x81-\xfe]([\x40-\x7e]¦\xa1-\xfe])/";
            preg_match_all($re[$charset], $str, $match);
            if (count($match[0]) <= $length)
                return $str;
                $slice = join("", array_slice($match[0], $start, $length));
        }
        if ($suffix)
            return $slice . "...";
        return $slice;
}

/**
 * @Title: unset_array
 * @Description: todo(删除二维数组中的值)
 * @param string $str
 * @param array $arr
 * @return Array
 * @author 苏晓信
 * @date 2018年2月4日
 * @throws
 */
function unset_array($str,$arr){
    foreach ($arr as $key => $value){
        if ($value === $str){
            unset($arr[$key]);
        }
    }
    return $arr;
}

/**
 * @Title: auto_description
 * @Description: todo(自动获取内容的内容简介)
 * @param string $d 默认简介【为空则自动获取】
 * @param string $c 内容值
 * @return string
 * @author 苏晓信
 * @date 2018年2月2日
 * @throws
 */
function auto_description($d, $c){
    if( empty($d) ){
        if( !empty($c) ){
            $c = trimall(strip_tags(htmlspecialchars_decode($c)));   //转换标签-去掉HTML标签
            $c = csubstr($c, 250, '', 0, false);
            $result = $c;
        }else{
            $result = '';
        }
    }else{
        $result = $d;
    }
    return $result;
}


/**
 * @Title: time_line
 * @Description: todo(时间轴)
 * @param int $time
 * @return string
 * @author 苏晓信
 * @date 2018年2月6日
 * @throws
 */
function time_line($time){
    $rtime = $time;
    $time = time () - strtotime($time);
    if($time < 60){
        $str = $time.'秒之前';
    }elseif($time < 60*60){
        $min = floor( $time/60 );
        $str = $min.'分钟前';
    }elseif($time < 60*60*24){
        $h = floor($time/(60*60));
        $str = $h.'小时前 ';
    }elseif($time < 60*60*24*3) {
        $d = floor($time/(60*60*24));
        if ($d == 1)
            $str = $d.'天以前';
            else
                $str = $d.'天以前';
    }else{
        $str = date('Y-m-d', strtotime($rtime));
    }
    return $str;
}
function time_only($time){
    return $str = date('Y-m-d', strtotime($time));
}
/**
 * @Title: flagPos
 * @Description: todo(文档属性标签)
 * @param string $flag
 * @return string
 * @author 苏晓信
 * @date 2018年2月8日
 * @throws
 */
function flagPos($flag){
    $flag_arr = explode(',', $flag);
    if (in_array('h', $flag_arr)){
        return '<div class="flag bg-red">头条</div>';
    }elseif (in_array('a', $flag_arr)){
        return '<div class="flag bg-orange">特荐</div>';
    }elseif (in_array('c', $flag_arr)){
        return '<div class="flag bg-green">推荐</div>';
    }
}

/**
 * @Title: authcheck
 * @Description: todo(权限节点判断)
 * @param string $rule
 * @param int $uid
 * @param string $relation
 * @param string $t
 * @param string $f
 * @return string
 * @author 苏晓信
 * @date 2018年1月11日
 * @throws
 */
function authcheck($rule, $uid, $relation='or', $t='', $f='noauth'){
    $auth = new \expand\Auth();
    if( $auth->check($rule, $uid, $type=1, $mode='url',$relation) ){
        $result = $t;
    }else{
        $result = $f;
    }
    return $result;
}

/**
 * @Title: auth_action
 * @Description: todo(操作按钮权限)
 * @param string $rule 权限节点
 * @param string $cationType 按钮样式
 * @param string $info 按钮文字
 * @param string || array  $param 参数
 * @param string $color 颜色
 * @param string $size 大小
 * @param string $icon 图标
 * @return string
 * @author 苏晓信
 * @date 2018年1月13日
 * @throws
 */
function auth_action($rule, $cationType='a', $info='infos', $param='', $color='primary', $size='xs', $icon='edit'){
    $cationTypes = [
        'a' => "<a class=\"btn btn-".$color." btn-".$size."\" href=\"".url($rule, $param)."\"><i class=\"fa fa-".$icon."\"></i> ".$info."</a>",
        'box' => "<a class=\"btn btn-".$color." btn-".$size." delete\" href=\"javascript:void(0);\" data-url=\"".url($rule)."\" data-id=\"".$param."\" data-title=\"".$info."\" ><i class=\"fa fa-".$icon."\"></i> ".$info."</a>",
        'btn' => "<button type=\"submit\" class=\"btn btn-".$color." btn-".$size." pull-right submits\" data-loading-text=\"&lt;i class='fa fa-spinner fa-spin '&gt;&lt;/i&gt; ".$info."\">".$info."</button>",
    ];
    if( authcheck($rule, UID) != 'noauth' ){
        $result = $cationTypes[$cationType];
    }else{
        $result = '';
    }
    return $result;
}

function isLogin(){
    $userid = session('webuserId');
    if (isset($userid)) {
        return true;
    }else{
        return false;
    }
}

function isAdmin(){
        $id = session('webuserId');
        $userModel = new User();
        $user = $userModel->where(['id' => $id])->find();
        if (!empty($user)) {
        $auth = $user->userGroup; 
        foreach ($auth as $k => $v) {
            if ($v['group_id'] == 2) {
                unset($auth[$k]);
            }
        }
        foreach ($auth as $k => $v) {
            if ($v['group_id'] == 1) {
                return true;
            }else{
                return false;
            }
        }
    }else{
        return false;
    }
}
/**
 * @todo 其它用户最火热文章
 * @time(2018-3-5)
 */
function hot_article(){
    $acModel = new UserArchive();
    return $acModel->hot_article();
}
/**
 * @todo  其它用户文章列表
 * @time(2018-3-5)
 */
function usermenu(){
    $atModel = new UserArctype();
    return $atModel->usermenu();
}
/**
 * @todo  用户上下页
 * @time(2018-3-7)
 */
function preview($archive){
    $acModel = new UserArchive();
    return $acModel->preview($archive);
}
function prview($archive){
    $acModel = new Archive();
    return $acModel->prview($archive);
}