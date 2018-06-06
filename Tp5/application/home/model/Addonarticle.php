<?php
namespace app\home\model;

use think\Model;

class Addonarticle extends Model
{
    public function getContentAttr($value, $data)
    {
        return htmlspecialchars_decode($data['content']);
    }
}