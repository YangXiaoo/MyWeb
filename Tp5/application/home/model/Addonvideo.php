<?php
namespace app\home\model;

use think\Model;

class Addonvideo extends Model
{
    public function getContentAttr($value, $data)
    {
        return htmlspecialchars_decode($data['content']);
    }
}