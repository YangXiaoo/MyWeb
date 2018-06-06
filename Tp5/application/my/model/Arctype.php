<?php
namespace app\admin\model;

use think\Model;

class Arctype extends Model{

	public function arctypeMod(){
		return $this->hasOne('ArctypeMod', 'id', 'mid');
	}
    public function treeList()
    {
        $list = cache('DB_TREE_ARETYPE');
        if(!$list){
            $list = $this->order('sorts ASC,id ASC')->select();
            foreach ($list as $k => $v){
                $v->arctypeMod;
            }
            $treeClass = new \expand\Tree();
            $list = $treeClass->create($list);
            cache('DB_TREE_ARETYPE', $list,86400);//缓存24小时
        }
        return $list;
    }
}