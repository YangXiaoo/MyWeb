<?php
namespace app\home\model;

use think\Model;

class Timeline extends Model{
	public function tlusername(){
		return $this->hasOne('User', 'id', 'uid')->field('username');
	}
	public function tlothersname(){
		return $this->hasOne('User', 'id', 'ruid')->field('username');
	}
	public function userachive(){
		return $this->hasOne('UserArchive','id', 'aid');
	}
	public function archive(){
		return $this->hasOne('Archive', 'id', 'aid')->field('title', 'typeid', 'flag', 'jumplink','litpic');
	}
}