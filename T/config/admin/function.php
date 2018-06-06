<?php
/**
 * @Title: auth_rule_admin
 * @Description: todo(管理员后台节点)
 * @return array
 * @author 苏晓信
 * @date 2018年1月16日
 * @throws
 */
function auth_rule_admin(){
    $authRuleModel = new \app\common\model\AuthRule();
    $list = $authRuleModel->treeList('admin', 1);
    $option = [ '0' => lang('auth_rule_top')];
    if (!empty($list)){
        foreach ($list as $k => $v){
            if ($v->h_layer < 3){
                if ($v->h_layer > 1){
                    $lv = '';
                    for ($i = 1; $i < $v->h_layer; $i++){
                        $lv .= '　　';
                    }
                    $lv .= '├ ';
                }else{
                    $lv = '';
                }
                $option[$v->id] = $lv.$v->title;
            }
        }
    }
    return $option;
}

/**
 * @Title: auth_rule_member
 * @Description: todo(会员前台节点)
 * @return array
 * @author 苏晓信
 * @date 2018年1月29日
 * @throws
 */
function auth_rule_member(){
    $authRuleModel = new \app\common\model\AuthRule();
    $list = $authRuleModel->treeList('member', 1);
    $option = [ '0' => lang('auth_rule_top')];
    if (!empty($list)){
        foreach ($list as $k => $v){
            if ($v->h_layer < 3){
                if ($v->h_layer > 1){
                    $lv = '';
                    for ($i = 1; $i < $v->h_layer; $i++){
                        $lv .= '　　';
                    }
                    $lv .= '├ ';
                }else{
                    $lv = '';
                }
                $option[$v->id] = $lv.$v->title;
            }
        }
    }
    return $option;
}


/**
 * @Title: auth_rule
 * @Description: todo(权限节点)
 * @return array
 * @author 苏晓信
 * @date 2018年1月19日
 * @throws
 */
function auth_rule(){
    $authRuleModel = new \app\common\model\AuthRule();
    $list = $authRuleModel->treeList('', 1);
    $option = [];
    if (!empty($list)){
        foreach ($list as $k => $v){
            $option[$v->id] = [$v->level, $v->title];
        }
    }
    return $option;
}

/**
 * @Title: arctyp_tree
 * @Description: todo(文章分类)
 * @return array
 * @author 苏晓信
 * @date 2018年2月2日
 * @throws
 */
function arctyp_tree(){
    $arctypeModel = new \app\common\model\Arctype();
    $list = $arctypeModel->treeList();
    $option = [ '0' => lang('top_arctype')];
    if (!empty($list)){
        foreach ($list as $k => $v){
            if ($v->h_layer > 1){
                $lv = '';
                for ($i = 1; $i < $v->h_layer; $i++){
                    $lv .= '　　';
                }
                $lv .= '├ ';
            }else{
                $lv = '';
            }
            $option[$v->id] = $lv.$v->typename;
        }
    }
    return $option;
}

/**
 * @Title: arctyp_trees
 * @Description: todo(文章分类)
 * @return array
 * @author 苏晓信
 * @date 2018年2月2日
 * @throws
 */
function archive_arctyp_tree(){
    $arctypeModel = new \app\common\model\Arctype();
    $list = $arctypeModel->treeList();
    if (!empty($list)){
        foreach ($list as $k => $v){
            if ($v->h_layer > 1){
                $lv = '';
                for ($i = 1; $i < $v->h_layer; $i++){
                    $lv .= '　　';
                }
                $lv .= '├ ';
            }else{
                $lv = '';
            }
            $option[$v->id] = [$v->mid, $lv.$v->typename];
        }
    }
    return $option;
}

/**
 * @Title: arctype_mod
 * @Description: todo(文章模型)
 * @return array
 * @author 苏晓信
 * @date 2018年2月2日
 * @throws
 */
function arctype_mod(){
    $arctypeModModel = new \app\common\model\ArctypeMod();
    $list = $arctypeModModel->field('id,name,mod')->where('status', 1)->order('sorts ASC,id ASC')->select();
    $option = [];
    if (!empty($list)){
        foreach ($list as $k => $v){
            $option[$v->id] = [$v->mod, $v->name];
        }
    }
    return $option;
}

/**
 * @Title: module_flink
 * @Description: todo(友情链接模块)
 * @author 苏晓信
 * @date 2018年2月5日
 * @throws
 */
function module_flink(){
    $moduleClassModel = new \app\common\model\ModuleClass();
    $list = $moduleClassModel->where(['status' => 1, 'action' => 'flink'])->order('sorts ASC,id ASC')->select();
    $option = [];
    if (!empty($list)){
        foreach ($list as $k => $v){
            $option[$v->id] = $v->title;
        }
    }
    return $option;
}

/**
 * @Title: module_banner
 * @Description: todo(BANNER模块)
 * @author 苏晓信
 * @date 2018年2月5日
 * @throws
 */
function module_banner(){
    $moduleClassModel = new \app\common\model\ModuleClass();
    $list = $moduleClassModel->where(['status' => 1, 'action' => 'banner'])->order('sorts ASC,id ASC')->select();
    $option = [];
    if (!empty($list)){
        foreach ($list as $k => $v){
            $option[$v->id] = $v->title;
        }
    }
    return $option;
}