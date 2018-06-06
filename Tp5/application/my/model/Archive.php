<?php
namespace app\admin\model;

use thinK\Model;
/**
 *@todo  文章档案
 *@time( 2018-2-16)
 */
class Archive extends Model{
	//文章类型
	public function arctype(){
		return $this->hasOne('Arctype','id','typeid')->field('typename, mid, dirs');
	}
	//文章模型
	public function arctypeMod(){
		return $this->hasOne('ArctypeMod', 'id', 'mid')->field('mod');
	}
	//作者信息
	public function User()
    {
        return $this->hasOne('User', 'id', 'writer')->field('name');
    }
    
    /**
     * 文章模型关联表
     */
    public function addonarticle()
    {
        return $this->hasOne('addonarticle', 'aid', 'id');
    }
    
    /**
     * 视频模型关联表
     */
    public function addonvideo()
    {
        return $this->hasOne('addonvideo', 'aid', 'id');
    }
    
    /**
     * 相册模型关联表
     */
    public function addonalbum()
    {
        return $this->hasOne('addonalbum', 'aid', 'id');
    }


}