<?php
namespace app\common\model;
use think\Model;    // 使用前进行声明
/**
 *@name  bill 模型
 *@author yangxiao
 */
class Personal extends Model
{
/**
 *@function 用户登录
 */   
static public function login($username, $password)
	{   

	    //验证用户存在
        $map = array('username'=>$username);//得到登陆者的姓名
		$Personal = self::get($map);

        //验证密码正确
		if (!is_null($Personal))
		{
		    if ($Personal->checkPassword($password)){
			    //登录,使用session
				session('personalId',$Personal->getData('id'));
				session('persoanlName',$Personal->getData('username'));
				return true;
			}
		}
		return false;
 }
/**
 * @checkPassword() 验证密码正确性
 * @param string $password 密码
 * @return bool
 */
 public function checkPassword($password){
     if ($this->getData('password') == $password){
	     return true;
	 }else{
	     return false;
	 }
 }
/**
 * 注销
 * @return bool
 * @time 2018/2/5
 */
  static public function logOut(){
      session('persoanlId',null);
	  return true;
  }
/**
 * 检查是否登录
 * @return bool 
 * @time 2018/2/5
 */
 static public function isLogin(){
     $personalId = session('persoanlId');

	 if (isset($personalId)){
	     return true;
	 }else{
	     return false;
	 }
  }
}