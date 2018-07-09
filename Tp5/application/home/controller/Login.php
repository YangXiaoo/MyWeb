<?php
namespace app\home\controller;

use think\Controller;
use qqconnect\QC;
use think\Db;
use app\home\model\User;
use app\home\model\LoginLog;
use app\home\model\UserInfo;
use app\home\model\Webuser;
use app\home\model\WebuserInfo;
use app\home\model\AuthGroupAccess;
use app\home\model\Timeline;
use email\STMP;
use email\PHPMailer;


class Login extends Common{
	//1.跳转至登录页面index；2.右上角登录check；3.注销；
	//关联模型并初始化_initialize
	//初始化中连续写多个$this->xxModel = new xx();只有一个]有效
	private $userModel;
	public function _initialize(){
		parent::_initialize();//父的方法，构建为_construct()
		$this->userModel = new User();//实例化模型
	}

	//1.登录页面
	public function index()
	{
		$userId = session('webuserId');
		if (!empty($userId)) {
			return $this->fetch('index/index');
		}else{
			return $this->fetch();
		}
	}

	public function login()
	{
		$userId = session('webuserId');
		if (!empty($userId)) {
			return $this->fetch('index/index');
		}else{
			return $this->fetch();
		}
	}
	public function reg(){
		if (request()->isPost()) {
			$user = input('post.');
			if (!captcha_check($user['code'])) {
			 	return ajaxReturn(lang('code_error'));
			 }
			$where = ['username' => $user['username']];
			$data = $this->userModel->where($where)->find();
			if (!empty($data)) {
				return ajaxReturn(lang('user_exist'));
			}else{
				if ($user['password'] != $user['repassword']) {
					return ajaxReturn(lang(password_not_equal));
				}else{
					$user['password'] = $user['password'];
					# $user['status'] = 1;
					$this->userModel->allowField(true)->save($user);
					$u = $this->userModel->where($where)->find();
					$this->updateLogin($u);
					$this->saveAuthGroupAccess($u);
					$result = $this->saveUserInfo($u);
			 		$this->setSession($u);
			 		$this->saveTimeline($u, 1);
			 		# $is_log = $this->logLog($u);
					if ($result) {
						return ajaxReturn(lang('reg_success'), url('Index/index'));
					}else{
						return ajaxReturn(lang('reg_error'));		
					}
				}
			}
	
		}
	}
	
	public function active($id){
		$this->userModel->where(['id' => $id])->updata(['status' => 1]);
		return $this->fetch('Login/index');
	}  

    public function sendEmail($user){
    	$mail=new PHPMailer(); 
       	$send_email=$user['email'];
		$send_theme="sign";
		//$send_kind=$_POST['kind'];
		//邮件内容
		$mailcontent = "<div align=center><strong>user : </strong>";
		//设置smtp参数 
		$mail->IsSMTP(); 
		$mail->SMTPAuth=true; 
		$mail->SMTPKeepAlive=true; 
		$mail->Host="ssl://smtp.qq.com"; 
		$mail->Port=465; 
		//填写你的email账号和密码 
		$mail->Username="1593606228@qq.com"; 
		$mail->Password="xxx";
		//注意这里要填写授权码就是我在上面网易邮箱开启SMTP中提到的，不能填邮箱登录的密码哦。 
		//设置发送方，最好不要伪造地址 
		$mail->From="popmarshmallow@foxmail.com"; 
		$mail->FromName="marshroom";//发送者用户名 
		$mail->Subject="sign";//邮件标题 
		$mail->AltBody=$mailcontent; //邮件内容
		$mail->WordWrap=50; // set word wrap 
		$mail->MsgHTML($mailcontent); 

		//设置回复地址 
		$mail->AddReplyTo("popmarshmallow@foxmail.com","lala"); 
		//设置邮件接收方的邮箱和姓名 
		$mail->AddAddress($send_email,$user['username']);//接收者邮箱和用户名 
		//使用HTML格式发送邮件 
		$mail->IsHTML(true); 
		//通过Send方法发送邮件 
		//根据发送结果做相应处理 
		if(!$mail->Send()){ 
		    return ajaxReturn('发送失败,请联系站长。');
		    }
		else{ 
			return ajaxReturn('发送成功请查看邮箱',url('Index\index'));
		}
    }

	public function saveReg($user){
		$this->userModel->create($user);
	}
    /**
     * @Title: qqconnect
     * @Description: todo(QQ授权登录)
     * @return \think\response\Redirect
     * @author 苏晓信
     * @date 2018年2月12日
     * @throws
     */
    public function qqconnect()
    {
        $qc = new QC();
        return redirect($qc->qq_login());
    }
    
    /**
     * @Title: qqconnectBack
     * @Description: todo(QQ授权登录回调)
     * @author yangxiao
     * @time (2018-3-1)
     */
    public function qqconnectBack()
    {
        $qc = new QC();
        $access_token = $qc->qq_callback();     //access_token
        $openid = $qc->get_openid();            //openid
        $qc = new QC($access_token, $openid);
        $user_info = $qc->get_user_info();//获取授权登录用户信息http://wiki.connect.qq.com/get_user_info
        if ($user_info['gender'] == '男') {
        	$user_info['sex'] = 1;
        }elseif ($user_info['gender'] == '女') {
        	$user_info['sex'] = 0;
        }else{
        	$user_info['sex'] = 2;
        }
        $data = [
            'authorize_type' => 2,                      //2 QQ授权登录
            'uid' => $openid,                        	//openid
            'username' => $user_info['nickname'],       //昵称
            'reg_ip'  => request()->ip(),
        ];
        $where = [
            'authorize_type' => 2,
            'username' => $user_info['nickname'],
        ];											//查询条件
        $webuserData = $this->userModel->where($where)->find();
        if(empty($webuserData)){
//        	Db::startTrans();
//        try{                  //第一次授权登录
            $this->userModel->allowField(true)->save($data);
            $uid = $this->userModel->getLastInsID();
            $reg = $this->userModel->where(['id' => $uid])->find();
            //保存个人信息
            $infodata = [
                'uid' => $reg['id'],
                'avatar' => $user_info['figureurl_qq_2'],
                'sex' => $user_info['sex'],
            ];
            $infoModel = new UserInfo();
            $result = $infoModel->save($infodata);
            $data = [
            	'uid' => $reg['id'],
            	'group_id' => '2', //普通会员
        	];
        	$authModel = new AuthGroupAccess();
        	$authModel->create($data);
            //cookie
            session('webuserId', $reg['id']);
            cookie('webname', $user_info['nickname']);
            cookie('webavatar', $user_info['figureurl_qq_2']);
            //更新登录次数及最后登录ip
            $updata = [
                    'logins'    => $reg['logins']+1,
                    'last_time' => time(),
                    'last_ip'   => request()->ip()
                    ];
        	$where = ['id' => $reg['id']];
        	$this->userModel->where($where)->update($updata);
        	$this->saveTimeline($reg, 1);
        	//登录记录
        	$results = $this->logLog($reg);
        	if ($results) {//此处应进行多个判断
//        		Db::commit();
        		$this->redirect('Index/index');
        	}else{
        		$this->redirect('Login/index');
        	}
/*        }catch(\Exception $e){
        	Db::rollback();
        	return ajaxReturn($e->getMessage());
        	}
*/        	
        }else{
        	//设置cookie
        	session('webuserId', $webuserData['id']);
            cookie('webname', $user_info['nickname']);
            cookie('webavatar', $user_info['figureurl_qq_2']);
            //更新登录次数
            $this->saveTimeline($webuserData, 0);
            $this->updateLogin($webuserData);
            $this->logLog($webuserData);
        	$this->redirect('Index/index');
        }
    }

	private function saveUserInfo($user){
		$data = [
			'uid' => $user['id'],
			'avatar' => '/PIC/avatar.png',//默认头像
			'sex' => '1',//默认为男
		];
		$infoModel = new UserInfo();
		$result = $infoModel->save($data);
		if ($result) {
			return true;
		}else{
			return false;
		}
	}
	private function saveAuthGroupAccess($user){
		$data = [
			'uid' => $user['id'],
			'group_id' => '2',//普通会员
		];
		$authModel = new AuthGroupAccess();
		$authModel->create($data);
	}

	//2.登录
	public function check()
	{
		//判断提交数据
		if (request()->isPost()) {	
			$data = input('post.');//不用助手函数则为$data = Request::instance()->post();
			//验证码
			if (!captcha_check($data['code'])) {
			 	return ajaxReturn(lang('code_error'));
			 }
			 //查询
			 $where = ['username' => $data['username']];
			 $user = $this->userModel->where($where)->find();//一维数组select()二维
			 //验证用户登录名和密码
			 if (!empty($user)) {
			 	if ($user['status'] != '1') {
			 		return ajaxReturn(lang('user_stop'));
			 	}elseif($user['password'] != md5($data['password'])){
			 		return ajaxReturn(lang('password_error'));
			 	}else{
			 		$this->updateLogin($user);
			 		$this->setSession($user);
			 		$this->saveTimeline($user, 0);
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
 * @todo  保存到时间轴
 * @time(2018-3-2)
 * @author yangxiao 
 */
	private function saveTimeline($user, $isreg){
		if ($isreg == 1) {
			$description = '注册成功！';
		}else{
			$description = '登录成功！';
		}
		$timeLineData = [
			'uid' => $user['id'],
			'icon' => 'fa fa-user bg-aqua',
			'description' => $description,  
		];
		$tlModel = new Timeline();
		$tlModel->save($timeLineData);
	}
/**
 * [updateInfo 更新登录信息] 
 * @todo  更新登录信息
 * @time(2018-2-14)
 */
	private function updateLogin($user){
		$updata = [
			 		'logins'    => $user['logins']+1,
			 		'last_time' => time(),
			 		'last_ip'   => request()->ip()
			 		];
			 		$where = ['id' => $user['id']];
			 		$this->userModel->where($where)->update($updata);
	}
/**
 * @todo 设置session，cookie
 * @time(2018-2-14)
 */
	private function setSession($user){
		session('webuserId', $user['id']);
			//若用户没有设置姓名，则使用登录名
		if (!empty($user['name'])) {
			cookie('webname' , $user['name']);
		}else{
			cookie('webname', $user['username']);
		}
		cookie('webuname' , $user['username']);
		cookie('webavatar' , $user->userInfo->avatar);//关联模型获得头像
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
            ];
            $isSave = $lgModel->allowField(true)->save($logData);
            if ($isSave) {
            	return true;
            }else{
            	return false;
            }
        }
	}
/**
 *@todo  保存时间轴 并退出
 *@time(2018-2-14)
 *@redo(2018-3-7)
*/
	public function loginOut(){
				session('webuserId', null);
				cookie('webname', null);
				cookie('webavatar', null);
				$this->redirect('http://www.lxxx.site');
		}
	
}