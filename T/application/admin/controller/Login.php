<?php
namespace app\admin\controller;

use think\Controller;
use app\common\model\User;

class Login extends Controller
{
    private $cModel;
    
    public function initialize()
    {
        parent::initialize();
        $this->cModel = new User;
    }
    
    public function index()
    {
        $userId = session('userId');
        if (!empty($userId)){
            $this->redirect('Index/index');
        }else{
            return $this->fetch();
        }
    }
    
    public function checkLogin()
    {
        if(request()->isPost()){
            $data = input('post.');
            if(!captcha_check($data['code'])){
                return ajax_return(lang('code_error'));
            };
            $where = ['username' => $data['username'] ];
            $user = $this->cModel->where($where)->find();
            if(!empty($user)){
                if($user['status'] != '1'){
                    return ajax_return(lang('user_stop'));
                }elseif($user['password'] != md5($data['password'])){
                    return ajax_return(lang('password_error'));
                }else{
                    $this->updateUser($user);   //更新登陆信息
                    $this->setSession($user);   //设置session,cookie
                    $this->loginLog($user);     //登陆日志
                    return ajax_return(lang('login_success'), url('Index/index'));
                }
            }else{
                return ajax_return(lang('user_empty'));
            }
        }
    }
    
    private function updateUser($user)
    {
        $data = [
            'logins' => $user['logins']+1,
            'last_time' => time(),
            'last_ip' => request()->ip(),
        ];
        $this->cModel->where('id', $user['id'])->update($data);
    }
    
    private function setSession($user)
    {
        session('userId', $user['id']);
        if (!empty($user['name'])){
            cookie('name', $user['name']);
        }else{
            cookie('name', $user['username']);
        }
        cookie('uname', $user['username']);
        cookie('avatar', $user->userInfo->avatar);
    }
    
    private function loginLog($user)
    {
        $ip = request()->ip();
        //$ip = '111.10.243.171';
        $ipStr = @file_get_contents("http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=".$ip);
        if ($ipStr != '-2'){
            $loginLogModel = new \app\common\model\LoginLog();
            $s = mb_strpos($ipStr, '{');
            $e = mb_strpos($ipStr, '}');
            $ipJsonStr = mb_substr($ipStr, $s, $e-$s+1);
            $ipArr = json_decode($ipJsonStr, true);
            $logData = [
                    'uid' => $user['id'],
                    'ip' => $ip,
                    'country' => $ipArr['country'],
                    'province' => $ipArr['province'],
                    'city' => $ipArr['city'],
                    'district' => $ipArr['district']
            ];
            $loginLogModel->save($logData);
        }
    }
    
    public function loginOut($params='')
    {
        session('userId', null);
        cookie('name', null);
        cookie('uname', null);
        cookie('avatar', null);
        $this->redirect('Login/index', $params);
    }
}
