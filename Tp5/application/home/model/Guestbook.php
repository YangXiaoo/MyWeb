<?php
namespace app\index\model;

use think\Model;

class Guestbook extends Model
{
    public function replay()
    {
        return $this->hasMany('Guestbook', 'gid')->where('status', 1);
    }
}