<?php
use think\facade\Request;

/**
 * @Title: ajax_return
 * @Description: todo(ajax提交返回状态信息)
 * @param string $info
 * @param string $url
 * @param int $status
 * @param string $data
 * @author 苏晓信
 * @date 2018年1月13日
 * @throws
 */
function ajax_return($info='', $url='', $status='', $data = ''){
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
 * @Title: search_url
 * @Description: todo(搜索的地址)
 * @param string $delparam
 * @return string
 * @author 苏晓信
 * @date 2018年1月11日
 * @throws
 */
function search_url($delparam){
    $url_path = '/'.request()->path();
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
    $url_path = '/'.request()->path();
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
 * @Title: input
 * @Description: todo(重写input方法)
 * @param string $key
 * @param string $default
 * @param string $filter
 * @return mixed
 * @author 苏晓信
 * @date 2018年2月4日
 * @throws
 */
function input($key = '', $default = null, $filter = null){
    if (0 === strpos($key, '?')) {
        $key = substr($key, 1);
        $has = true;
    }
    if ($pos = strpos($key, '.')) {
        // 指定参数来源
        $method = substr($key, 0, $pos);
        if (in_array($method, ['get', 'post', 'put', 'patch', 'delete', 'route', 'param', 'request', 'session', 'cookie', 'server', 'env', 'path', 'file'])) {
            $key = substr($key, $pos + 1);
        } else {
            $method = 'param';
        }
    } else {
        // 默认为自动判断
        $method = 'param';
    }
    if (isset($has)) {
        return Request::has($key, $method, $default);
    } else {
        if (!empty($filter)){
            return Request::$method($key, $default, $filter);
        }else{
            return Request::$method($key, $default);
        }
    }
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