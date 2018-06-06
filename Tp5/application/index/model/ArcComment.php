<?php
namespace app\index\model;

use think\Model;

class ArcComment extends Model
{
    public function replay()
    {
        return $this->hasMany('ArcComment', 'cid')->where('status', 1);
    }
    
     public function user()
     {
         return $this->hasOne('User', 'id', 'uid')->field('username');
     }
     public function userAvatar()
     {
         return $this->hasOne('UserInfo', 'uid', 'uid')->field('avatar');
     }
    
     public function ruser()
     {
         return $this->hasOne('User', 'id', 'uid')->field('username');
     }
     public function ruserAvatar()
     {
         return $this->hasOne('UserInfo', 'uid', 'uid')->field('avatar');
     }
    
    public function newComment($limit = '3')
    {
        $where[] = ['status', '=', 1];
        $result = $this->where($where)->order('id DESC')->limit($limit)->select();
        return $result;
    }
}