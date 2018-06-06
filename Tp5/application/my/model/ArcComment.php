<?php
namespace app\admin\model;

use think\Model;

  class ArcComment extends Model
{
    public function replay()
    {
        return $this->hasMany('ArcComment', 'cid');
    }
    
    public function user()
    {
        return $this->hasOne('User', 'id', 'uid')->field('username, name');
    }
    public function userAvatar()
    {
        return $this->hasOne('UserInfo', 'uid', 'uid')->field('avatar');
    }
    
    public function ruser()
    {
        return $this->hasOne('User', 'id', 'ruid')->field('username, name');
    }
    public function ruserAvatar()
    {
        return $this->hasOne('UserInfo', 'uid', 'ruid')->field('avatar');
    }
    
    public function archive()
    {
        return $this->hasOne('Archive', 'id', 'aid')->field('title');
    }
    
    public function newComment($limit = '3')
    {
        $where[] = ['status', '=', 1];
        $result = $this->where($where)->order('id DESC')->limit($limit)->select();
        return $result;
    }
}
