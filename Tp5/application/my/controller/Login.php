<?php

namespace app\admin\controller;

use think\Controller;
use app\admin\model\User as Users;
use app\admin\model\TokenUser;
use app\admin\model\LoginLog;
/**
 * @todo 登录
 * @date( 2018年2月14日)
 * @author yangxiao <1270009836@qq.com>
 */
class Login extends Controller
{
	//关联模型并初始化_initialize
	private $userModel;
	public function _initialize(){
		parent::_initialize();//父的方法，构建为_construct()
		$this->userModel = new Users();//实例化模型
	}

	//1.登录页面
	public function index()
	{
		$userId = session('userId');
		if (!empty($userId)) {
			$this->redirect(url('Index/index'));
		}else{
			return $this->fetch();
		}
	}

	//2.登录
	public function checkLogin()
	{
		//判断提交数据
		if (request()->isPost()) {	
			$tkModel = new TokenUser();
			$data = input('post.');//不用助手函数则为$data = Request::instance()->post();
			//验证码
			if (!captcha_check($data['code'])) {
			 	return ajaxReturn(lang('code_error'));
			 }
			 //查询
			 $where = ['username' => $data['username']];
			 $user = $this->userModel->where($where)->find();//一维数组select()二维
			 $auth = $user->userGroup;
			 //判断是否为管理员
			 foreach ($auth as $k => $v) {
			 	if ($v['group_id'] == 1) {
			 		$isAuth = 1;
			 	}else{
			 		$isAuth = 0;
			 	}
			 }
			 if ($isAuth == 0) {
			 	return ajaxReturn(lang('not_admin'));
			 }
			 //验证用户登录名和密码
			 if (!empty($user)) {
			 	if ($user['status'] != '1') {
			 		return ajaxReturn(lang('user_stop'));
			 	}elseif($user['password'] != $data['password']){
			 		return ajaxReturn(lang('password_error'));
			 	}else{
			 		$this->updateLogin($user);
			 		$this->setSession($user);
			 		$this->logLog($user);
			 		return ajaxReturn(lang('login_success'),url('Index/index'));
			 	}
			 }else{
			 	return ajaxReturn(lang('user_no_exist'));
			 }
		}
	}
	//3.注销
	//4.重新登录
/**
 * [updateInfo 更新登录信息] 
 * @todo  更新登录信息
 * @time(2018-2-14)
 */
	private function updateLogin($user){
		$updata = [
			 		'logins'    => $user['logins']+1,
			 		'last_time' => time(),
			 		'last_ip'   => request()->ip(),
			 		];
			 		$where = ['id' => $user['id']];
			 		$this->userModel->where($where)->update($updata);
	}
/**
 * @todo 设置session，cookie
 * @time(2018-2-14)
 */
	private function setSession($user){
		session('userId' , $user['id']);
			//若用户没有设置姓名，则使用登录名
		if (!empty($user['name'])) {
			cookie('name' , $user['name']);
		}else{
			cookie('name', $user['username']);
		}
		cookie('uname' , $user['username']);
		cookie('avatar' , $user->userInfo->avatar);//关联模型获得头像
	}
/**
 * @todo  登录日志
 * @time(2018-2-14)
 */
	private function logLog($user){
		$ip = request()->ip();
		$ipStr = @file_get_contents("http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=".$ip);
		if ($ipStr != '-2') {
			$lgModel = new LoginLog();
			$s = mb_strpos($ipStr, '{');
            $e = mb_strpos($ipStr, '}');
            $ipJsonStr = mb_substr($ipStr, $s, $e-$s+1);
            $ipArr = json_decode($ipJsonStr, true);
            $logData = [
                    'uid'      => $user['id'],
                    'ip'       => $ip,
                    'country'  => $ipArr['country'],
                    'province' => $ipArr['province'],
                    'city'     => $ipArr['city'],
                    'district' => $ipArr['district']
            ];
            $lgModel->save($logData);
        }
	}
	public function loginOut($params=''){
		session('userId', null);
		session('user_token', null);
		session('name', null);
		session('uid', null);
		session('avatar', null);
		$this->redirect('Login/index', $params);
	}
}
// 8b1556c9f46a8aeb44634641feab6f17