<?php
namespace app\home\model;

use think\Model;
/**
 * @todo 用户个人信息模型
 * @time( 2018-2-14)
 */
class UserInfo extends Model{
	protected $insert  = ['avatar'];
    
    protected function setAvatarAttr($value)
    {
        if ($value){
            return $value;
        }else{
            return '/static/global/face/default.png';
        }
    }
	public function getBirthdayTurnAttr($value, $data)
    {
        return date('Y-m-d', $data['birthday']);
    }

}