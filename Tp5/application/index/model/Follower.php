<?php
namespace app\index\model;

use think\Model;

class Follower extends Model{
	public function myinfo(){
		return $this->hasOne('User', 'id', 'uid')->field('username');
	}
	public function followinfo(){
		return $this->hasOne('User', 'id', 'fid')->field('username');
	}
}