<?php
namespace app\admin\controller;

use think\Controller;
use think\Lang;
use app\admin\model\AuthRule;
use expand\Auth;
use app\admin\model\Config;
use app\admin\Controller\Login;
use app\admin\model\TokenUser;
use app\admin\model\User;
/**
 * @todo 控制器
 * @time(2018-2-16)
 */
class Common extends Controller{
	public function _initialize(){
		$userId = session('userId');
		define('UID',$userId);
		define('MODULE_NAME',request()->module());
		define('CONTROLLER_NAME',request()->controller());
		define('ACTION_NAME',request()->action());
		$box_is_pjax = $this->request->isPjax();//????????????????request()->isPjax()
		$this->assign('box_is_pjax',$box_is_pjax);
		$treeMenu = $this->treeMenu();
		$this->assign('treeMenu',$treeMenu);

        //加载多语言相应控制器对应字段
        if($_COOKIE['think_var']){
            $langField = $_COOKIE['think_var'];
        }else{
            $langField = config('default_lang');
        }
        Lang::load(APP_PATH . 'admin/lang/'.$langField.'/'.CONTROLLER_NAME.'.php');
	}

/**
 * [treeMenu 分栏]
 * @return [array] none
 * @another method :直接用数据库查询所有数据即可
 */
	public function treeMenu(){
		
		if (!$treeMenu) {
			$where = [
				'ismenu'  => 1,
				'module'  => 'admin',
			];
			if (UID != '-1') {
				$where['status'] = 1;
			}
			$armodel = new AuthRule();
			$lists = $armodel->where($where)->order('sorts ASC,id ASC')->select();
			$treeClass = new \expand\Tree();
			$treeMenu = $treeClass->create($lists);
			cache('DB_TREE_MENU_'.UID, $treeMenu);
		}
		return $treeMenu;
	}

	public function isLog(){
		$user = session('userId');
		if (empty($user)) {
			return false;
		}else{
			$users = new User;
			$dataList = $users->where(['id' =>$user])->find();
			if ($dataList) {
				return true;
			}else{
				return false;
			}
		}

	}
	

}