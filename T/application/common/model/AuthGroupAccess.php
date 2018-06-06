<?php
namespace app\common\model;

use think\Model;

class AuthGroupAccess extends Model
{
    public function authGroup()
    {
        return $this->hasOne('AuthGroup', 'id', 'group_id');
    }
    
    public function user()
    {
        return $this->hasOne('User', 'id', 'uid');
    }
    
    public function userInfo()
    {
        return $this->hasOne('UserInfo', 'uid', 'uid');
    }
}