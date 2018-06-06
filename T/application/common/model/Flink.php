<?php
namespace app\common\model;

use think\Model;

class Flink extends Model
{
    public function moduleClass()
    {
        return $this->hasOne('ModuleClass', 'id', 'mid')->field('id, title');
    }
    
    /**
     * @Title: flinks
     * @Description: todo(友情链接)
     * @param int $mid 分类ID
     * @param int $limit 查询条数
     * @return array
     * @author 苏晓信
     * @date 2017年11月10日
     * @throws
     */
    public function flinks($mid, $limit='')
    {
        $where[] = [
            ['mid', '=', $mid],
            ['status', '=', 1],
        ];
        $result = $this->where($where)->order('sorts ASC,id ASC')->limit($limit)->select();
        return $result;
    }
}