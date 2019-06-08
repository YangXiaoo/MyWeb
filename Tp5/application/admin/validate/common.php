<?php

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
 * @Title: time_line
 * @Description: todo(时间轴)
 * @param int $time
 * @return string
 * @author duqiu
 * @date 2016-6-2
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
        $str = $rtime;
    }
    return $str;
}
/**
 * [page_param 分页额外参数]
 * @return [array] [description]
 */
function page_param(){
    $param = request()->param();//返回数据
    if (isset($param['_pjax'])) {
        unset($param['_pjax']);
    }
    $res['query'] = $param;
    return $res;
}
/**
 * @todo 查询
 * @time(2018-2-18)
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
 * @Title: table_sort
 * @Description: todo(列表table排序)
 * @param string $param
 * @return string
 * @author 苏晓信
 * @date 2017年8月21日
 * @throws
 */
function table_sort($param){
    $url_path = request()->url();
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
 * @Title: authcheck
 * @Description: todo(权限节点判断)
 * @param string $rule
 * @param int $uid
 * @param string $relation
 * @param string $t
 * @param string $f
 * @return string
 * @author duqiu
 * @date 2016-5-24
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
 * @Title: authTopBtn
 * @Description: todo(操作按钮权限)
 * @param string $rule
 * @param string $cationType
 * @param string || array $param
 * @return string
 * @author duqiu
 * @date 2016-5-14
 */
function authAction($rule, $cationType='create', $param=''){
    $cationTypes = [
        'create' => "<a href=\"".url($rule, $param)."\" class=\"btn btn-sm btn-primary\"><i class=\"fa fa-save\"></i> ".lang('create')."</a>",
        'create_arc' => "<a class=\"btn btn-primary btn-xs\" href=\"".url($rule, $param)."\"><i class=\"fa fa-edit\"></i> ".lang('create_arc')."</a>",
        'edit' => "<a class=\"btn btn-primary btn-xs\" href=\"".url($rule, $param)."\"><i class=\"fa fa-edit\"></i> ".lang('edit')."</a>",
        'delete' => "<a class=\"btn btn-danger btn-xs delete-one\" href=\"javascript:void(0);\" data-url=\"".url($rule)."\" data-id=\"".$param."\"><i class=\"fa fa-trash\"></i> ".lang('delete')."</a>",
        'delete_all' => "<a class=\"btn btn-sm btn-danger delete-all\" href=\"javascript:void(0);\" data-url=\"".url($rule)."\" ><i class=\"fa fa-trash\"></i> ".lang('delete')."</a>",
        'save' => "<button type=\"submit\" class=\"btn btn-info pull-right submits\" data-loading-text=\"&lt;i class='fa fa-spinner fa-spin '&gt;&lt;/i&gt; ".lang('submit')."\">".lang('submit')."</button>",
        'auth_user' => "<a class=\"btn btn-primary btn-xs\" href=\"".url($rule, $param)."\"><i class=\"fa fa-edit\"></i> ".lang('auth_user')."</a>",
        'auth_group' => "<a class=\"btn btn-primary btn-xs\" href=\"".url($rule, $param)."\"><i class=\"fa fa-edit\"></i> ".lang('auth_group')."</a>",
        'agree' => "<a class=\"btn btn-success btn-xs\" onclick=\"return confirm('是否已确认给用户退完款？');\" href=\"".url($rule, $param)."\"><i class=\"fa fa-repeat\"></i> ".lang('agree')."</a>",
        'disagree' => "<a class=\"btn btn-danger btn-xs\" href=\"".url($rule, $param)."\"><i class=\"fa fa-undo\"></i> ".lang('disagree')."</a>",
        'backup' => "<a class=\"btn btn-primary btn-sm delete-all\" href=\"javascript:void(0);\" data-url=\"".url($rule)."\" data-title=\"".lang('backup')."\"><i class=\"fa fa-save\"></i> ".lang('backup')."</a>",
        'restore' => "<a class=\"btn btn-primary btn-xs delete-one\" href=\"javascript:void(0);\" data-url=\"".url($rule)."\" data-id=\"".$param."\" data-title=\"".lang('restore')."\"><i class=\"fa fa-rotate-left\"></i> ".lang('restore')."</a>",
        'dowonload' => "<a class=\"btn btn-warning btn-xs\" href=\"".url($rule, $param)."\"><i class=\"fa fa-download\"></i> ".lang('dowonload')."</a>",
        'tokenapi' => "<a class=\"btn btn-danger btn-xs delete-one\" href=\"javascript:void(0);\" data-url=\"".url($rule)."\" data-id=\"".$param."\" data-title=\"".lang('tokenapi')."\"><i class=\"fa fa-lock\"></i> ".lang('tokenapi')."</a>",
        'generate_document' => "<a class=\"btn btn-danger btn-xs delete-one\" href=\"javascript:void(0);\" data-url=\"".url($rule)."\" data-id=\"".$param."\" data-title=\"".lang('generate_document')."\"><i class=\"fa fa-book\"></i> ".lang('generate_document')."</a>",
        'view_document' => "<a class=\"btn btn-warning btn-xs\" href=\"".url($rule, $param)."\"><i class=\"fa fa-book\"></i> ".lang('view_document')."</a>",
    ];
    if( authcheck($rule, UID) != 'noauth' ){
        $result = $cationTypes[$cationType];
    }else{
        $result = '';
    }
    return $result;
}