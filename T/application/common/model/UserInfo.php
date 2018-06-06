<?php
namespace app\common\model;

use think\Model;

class UserInfo extends Model
{
    protected $insert  = ['avatar'];
    
    protected function setAvatarAttr($value)
    {
        if ($value){
            return $value;
        }else{
            return config('default_avatar');
        }
    }
}