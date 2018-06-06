<?php
namespace app\home\controller;

use think\Db;
use think\Controller;
use think\Request;
use app\home\model\User;
use app\home\model\UserInfo;
use app\home\model\LoginLog;
use app\home\model\Timeline;
use app\home\model\AuthGroupAccess;

class Api extends Common
{

    private $cModel;
    public function _initialize(){
        parent::_initialize();
        $this->cModel = new User();
    }

    public function weibo(){
        // 微博登录官方开发步骤
        // http://open.weibo.com/wiki/Connect/login
        // 微博登录官方获取信息接口
        // http://open.weibo.com/wiki/2/users/show
        $code  = input('code');
        $tom = "https://api.weibo.com/oauth2/access_token?client_id=2820019779&client_secret=6f0218592438b5f5eedc83f731a87285&grant_type=authorization_code&redirect_uri=http://www.lxxx.site/Tp5/public/index/api/weibo&code=" .$code ;
        // post获取开始 获取重要的唯一ID钥匙-访问令牌
        $url = $tom;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POST, TRUE); 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERPWD, "username:password");
        $data = curl_exec($curl);
        curl_close($curl);

        $result = json_decode($data, true);
        $access_token = $result['access_token'];
        $uid = $result['uid'];
        // 继续获得用户信息 - 开始 方法一
        $tom = "https://api.weibo.com/2/users/show.json?access_token=". $access_token ."&uid=" . $uid;
        // 使用file方法 方法二
        // $domain = 'Rinuo.com'; 
        // $cha = 'http://panda.www.net.cn/cgi-bin/check.cgi?area_domain='.$domain ; 
        $data = file_get_contents($tom,'rb'); 
        $data = json_decode($data, true);  
        $data['uid'] = $uid;//此处uid为字符串，数据库设置为int类型
        $where = [
            'username' => $data['name'],
        ];
        $suser = $this->cModel->where($where)->find();
        if (empty($suser)) {//对数据连续操作应使用事务，这里修改麻烦没使用
            $userid = $this->saveSina($data);//保存注册信息并返回id
            $limit = [
                'id' => $userid,
            ];
            $user = $this->cModel->where($limit)->find();
            $this->saveUserInfo($user, $data);
            $this->saveTimeline($user, 1);
            $this->updateLogin($user);
            $this->setSession($data, $user);
            $this->saveAuthGroupAccess($user);
            $results = $this->logLog($user);
            if (!$results) {
                // 模板变量赋值
                $this->redirect('Profile/index/'.$user['id']);
            }else{
                $this->redirect('Login/index');
            }
        }else{ 
            $this->updateLogin($suser);
            $this->logLog($suser);
            $this->saveTimeline($suser, 0);
            session('webuserId', $suser['id']);
            cookie('webname' , $suser['username']);
            cookie('webavatar' , $data['avatar_hd']);
            $this->redirect('Profile/index/'.$suser['id']);           
            }     
    }
/**
 * [saveSina save]
 * @param  [array] $user [回调]
 * @time(2018-2-28)
 */
    public function saveSina($data){
        $ip = request()->ip();
        $datas = [
            'username' => $data['name'],
            'uid'  => $data['uid'],
            'reg_ip'   => $ip,
            'authorize_type' => 1,
        ];
        $this->cModel->create($datas);
        $userid = $this->cModel->getLastInsID();
        return $userid;
    }
/**
 * @todo  保存到时间轴
 * @time(2018-3-2)
 * @author yangxiao 
 */
    private function saveTimeline($user, $isreg){
        if ($isreg == 1) {
            $description = '注册成功！';
            $action = 11;
        }else{
            $description = '登录成功！';
            $action = 0;
        }
        $timeLineData = [
            'uid' => $user['id'],
            'icon' => 'fa fa-user bg-aqua',
            'description' => $description, 
            'action'    =>  $action, 
        ];
        $tlModel = new Timeline();
        $tlModel->save($timeLineData);
    }
/**
 * [saveUserInfo description]
 * @param  array $user 查询
 * @param  array $data json_decod之后的回调数据
 * @time(2018-3-1)
 */
    public function saveUserInfo($user,$data){
        if ($data['gender'] == "男") {
            $data['sex'] = 1;
        }elseif ($data['gender'] == "女") {
            $data['sex'] = 0;
        }else{
            $data['sex'] = 2;
        }
            $infodata = [
                'uid' => $user['id'],
                'avatar' => $data['avatar_hd'],
                'sex' => $data['sex'],
            ];
            $infoModel = new UserInfo();
            $infoModel->save($infodata);
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
        $where = ['uid' => $user['uid']];
        $this->cModel->where($where)->update($updata);
    }              
/**
 * @todo 设置session，cookie
 * @time(2018-2-14)
 */
    private function setSession($data, $user){
        session('webuserId', $user['id']);
        //若用户没有设置姓名，则使用登录名
        cookie('webname' , $user['username']);
        cookie('webavatar' , $data['avatar_hd']);//关联模型获得头像
    }
/**
 * @param  [array] $user [个人信息]
 * time(2018-3-1)
 * @todo 设置为普通会员
 */
    public function saveAuthGroupAccess($user){
        $data = [
            'uid' => $user['id'],
            'group_id' => '2',//普通会员
        ];
        $authModel = new AuthGroupAccess();
        $authModel->create($data);
    } 
/**
 * @todo  登录日志
 * @time(2018-2-14)
 */
    private function logLog($user){
        $ip = request()->ip();
        $ipStr = @file_get_contents("http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=".$ip);//Api
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
            $result = $lgModel->save($logData);
            if ($reselt) {
                return true;
            }else{
                return false;
            }
        }
    }

}
 