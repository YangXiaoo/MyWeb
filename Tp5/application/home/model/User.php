<?php
namespace app\home\model;

use think\Model;
/**
 * @todo 获取登录用户的相关信息
 * @time(2018年2月14日)
 * @author <1270009836@qq.com>
 */
class User extends Model
{
	//获取用户头像，生日，邮箱，状态
	public function userInfo(){
		return $this->hasOne('userInfo','uid','id');
	}
	    public function userGroup()
    {
        return $this->hasMany('authGroupAccess', 'uid', 'id');
    }
       protected function setPasswordAttr($value)
    {
        return md5($value);
    }
    protected function setLoginsAttr()
    {
        return '0';
    }
    protected function setRegIpAttr()
    {
        return request()->ip();
    }
    protected function setLastTimeAttr()
    {
        return time();
    }
    protected function setLastIpAttr()
    {
        return request()->ip();
    }
    
    public function getStatusTurnAttr($value, $data)
    {
        $turnArr = [0=>lang('status0'), 1=>lang('status1')];
        return $turnArr[$data['status']];
    }
    public function getLastTimeTurnAttr($value, $data)
    {
        return date('Y-m-d H:i:s', $data['last_time']);
    }
    
}