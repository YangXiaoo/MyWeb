<?php
namespace app\common\controller;

use think\Controller;
use think\facade\Lang;
use think\facade\Env;
use expand\Auth;
use app\common\model\AuthRule;

class Syscommon extends Controller
{
    public function initialize()
    {
        $userId = session('userId');
        define('UID', $userId);   //设置登陆用户ID常量
        
        define('ISPJAX', request()->isPjax());
        define('MODULE_NAME', request()->module());   //全小写
        define('CONTROLLER_NAME', request()->controller());   //首字母大写
        define('ACTION_NAME', request()->action());   //全小写
        
        $treeMenu = $this->treeMenu();
        $this->assign('treeMenu', $treeMenu);
        
        //加载多语言相应控制器对应字段
        if($_COOKIE['think_var']){
            $langField = $_COOKIE['think_var'];
        }else{
            $langField = config('default_lang');
        }
        Lang::load( Env::get('app_path').MODULE_NAME.'/lang/'.$langField.'/'.CONTROLLER_NAME.'.php' );
        
        $auth = new Auth();
        if (!$auth->check(CONTROLLER_NAME.'/'.ACTION_NAME, UID)){
            $this->error(lang('auth_no_exist'), url('Login/index'));
        }
    }
    
    private function treeMenu()
    {
        $auth_admin = config('app.AUTH_CONFIG.AUTH_ADMIN');
        $treeMenu = cache('DB_TREE_MENU_'.UID);
        if(!$treeMenu){
            $where = ['ismenu' => 1, 'module' => 'admin'];
            if (!in_array(UID, $auth_admin)){
                $where['status'] = 1;
            }
            $arModel = new AuthRule();
            $lists =  $arModel->where($where)->order('sorts ASC,id ASC')->select();
            $treeClass = new \expand\Tree();
            $treeMenu = $treeClass->create($lists);
            //判断导航tree用户使用权限
            foreach($treeMenu as $k=>$val){
                if( authcheck($val['name'], UID) == 'noauth' ){
                    unset($treeMenu[$k]);
                }
            }
            cache('DB_TREE_MENU_'.UID, $treeMenu);
        }
        return $treeMenu;
    }
}