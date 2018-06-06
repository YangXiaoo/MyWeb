<?php
namespace app\common\model;

use think\Model;

class Banner extends Model
{
    public function moduleClass()
    {
        return $this->hasOne('ModuleClass', 'id', 'mid')->field('id, title');
    }
}