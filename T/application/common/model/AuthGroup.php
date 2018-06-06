<?php
namespace app\common\model;

use think\Model;

class AuthGroup extends Model
{
    public function getModuleTurnAttr($value, $data)
    {
        $list = config('selectlist.auth_group');
        $turnArr = $list['data'];
        return $turnArr[$data['module']];
    }
    
    public function setRulesAttr($value)
    {
        if (!empty($value)){
            return $value = implode(',', array_filter($value));
        }else{
            return $value = '';
        }
    }
}