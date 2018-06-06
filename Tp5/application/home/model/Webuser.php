<?php
namespace app\home\model;

use think\Model;

class Webuser extends Model{
	public function userInfo(){
		return $this->hasOne('WebuserInfo', 'uid', 'id');
	}
	
}