<?php
namespace app\common\model;

use think\Model;

class AuthRule extends Model
{
    public function getLevelTurnAttr($value, $data)
    {
        $list = config('selectlist.auth_level');
        $turnArr = $list['data'];
        return $turnArr[$data['level']];
    }
    
    public function treeList($module = '', $status = '')
    {
        $list = cache('DB_TREE_AUTHRULE_'.$module.'_'.$status);
        if(!$list){
            $where = [];
            if ($module != ''){
                $where[] = ['module', 'eq', $module];
            }
            if ($status != ''){
                $where[] = ['status', 'eq', $status];
            }
            $list = $this->where($where)->order('sorts ASC,id ASC')->select();
            $treeClass = new \expand\Tree();
            $list = $treeClass->create($list);
            cache('DB_TREE_AUTHRULE_'.$module.'_'.$status, $list);
        }
        return $list;
    }
}