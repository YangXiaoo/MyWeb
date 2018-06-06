<?php
namespace app\index\controller;
use think\Request;              // 请求
use think\Controller;
use app\common\model\Personal;   // 个人资料模型


class LoginController extends Controller
{
    // 用户登录表单
    public function index()
    {
        // 显示登录表单
        return $this->fetch();
    }

    // 处理用户提交的登录数据
    public function login()
    {
        // 接收post信息
        $postData = Request::instance()->post();

        // 直接调用M层方法，进行登录。
        if (Personal::login($postData['username'], $postData['password'])) {
            return $this->redirect('bill/index');
        } else {
            return $this->error('账号或密码错误，重新登录', url('index'));
        }
    }



    // 注销
    public function logOut()
    {
        if (Personal::logOut()) {
            return $this->success('注销成功', url('Login/index'));
        } else {
            return $this->error('注销失败', url('Login/index'));
        }
    }
}