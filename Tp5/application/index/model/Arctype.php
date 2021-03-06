<?php
namespace app\index\model;

use think\Model;

class Arctype extends Model
{
    static public $allChild=array();   //无限子分类所有ID数组
    protected $insert  = ['description'];
    protected $update = [];
    
    public function arctypeMod()
    {
        return $this->hasOne('ArctypeMod', 'id', 'mid');
    }
    
    public function arcCount()
    {
        return $this->hasMany('Archive', 'typeid');
    }
    
    protected function setDescriptionAttr($value)
    {
        return auto_description($value, input('param.content'));
    }
    
    public function treeList()
    {
        $list = cache('DB_TREE_ARETYPE');
        if(!$list){
            $list = $this->order('sorts ASC,id ASC')->select();
            foreach ($list as $k => $v){
                if ($v->arctypeMod->mod == 'addonjump' && !empty($v['jumplink'])){
                    $v['typelink'] = $v['jumplink'];
                }else{
                    $v['typelink'] = url('@category/'.$v['dirs']);
                }
            }
            $treeClass = new \expand\Tree();
            $list = $treeClass->create($list);
            cache('DB_TREE_ARETYPE', $list);
        }
        return $list;
    }
    
    
    

    /**
     * @Title: channeldata
     * @Description: todo(当前ID的平级栏目)
     * @param int $pid 上级栏目ID
     * @return array
     * @author 苏晓信
     * @date 2017年7月2日
     * @throws
     */
    public function channeldata($pid){
        
        if(!$result){
            $where = [
                    'pid' => $pid,
                    'status' => 1,
            ];
            $result = $this->where($where)->order('sorts ASC,id ASC')->select();
            foreach ($result as $k =>$val){
                $val->arctypeMod;
                if ( $val->arctypeMod->mod == 'addonjump' && !empty($val->jumplink) ){
                    $result[$k]['typelink'] = $val->jumplink;
                    $result[$k]['target'] = " target=\"_blank\"";
                }else{
                    $result[$k]['typelink'] = url('@category/'.$val->dirs);
                    $result[$k]['target'] = " target=\"".$val->target."\"";
                }
            }
            cache('DB_ARCTYPE_PID_'.$pid, $result,3600);
        }
        return $result;
    }
    

    
    /**
     * @Title: position
     * @Description: todo(当前位置)
     * @param int $id
     * @param string $home
     * @param string $line
     * @return string
     * @author 苏晓信
     * @date 2017年7月2日
     * @throws
     */
    public function position($id, $home, $line){
        $interval = "<span>".$line."</span>";
        $positionArr = [];
        $positionArr = $this->positionArctype($id);
        $positionArr = array_reverse($positionArr);
        $result = "<a href=\"".url("/")."\">".$home."</a>".$interval;
        $num = count($positionArr) - 1;
        foreach($positionArr as $k=>$val){
            $result .= '<a href="'.url('@category/'.$val['dirs']).'">'.$val['typename'].'</a>';
            if($k != $num){
                $result .= $interval;
            }
        }
        return $result;
    }
    public function positionArctype($id, $result=[]){
        $data = $this->where('id', $id)->find();
        if(!$data){
            return false;
        }else {
            $data = $data->toArray();
        }
        if($data['pid'] == '0'){
            $result[] = $data;
        }else{
            $result[] = $data;
            return $this->positionArctype($data['pid'], $result);
        }
        return $result;
    }
    
    /**
     * @Title: arctypeCount
     * @Description: todo(栏目文章统计数)
     * @param int $pid 上级栏目ID
     * @author 苏晓信
     * @date 2017年11月11日
     * @throws
     */
    public function arctCount($pid){
        $data = $this->channeldata($pid);
        foreach ($data as $k => $v){
            $v->arcCount;
        }
        return $data;
    }
    
    /********************************************-系统方法-********************************************/
    
    /**
     * 查询当前ID下最顶级ID
     * @param int $pid
     */
    public function topArctypeData($pid){
        $data = $this->where(['id' => $pid])->find();
        if($data['pid'] == '0'){
            $data->arctypeMod;
            $result = $data;
        }else{
            return $this->topArctypeData($data['pid']);
        }
        return $result;
    }
    
    /**
     * 查询当前ID下无限分级栏目的所有ID
     * @param int $id
     */
    public function allChildArctype($id){
        self::$allChild[] = $id;
        $where = array(
                    'pid' => $id,
                    'status' => 1,
            );
        $data = $this->where($where)->order('sorts ASC,id ASC')->select();
        if(!empty($data)){
            foreach($data as $k=>$v){
                $this->allChildArctype($v['id']);
            }
        }
        $result = self::$allChild;
        return $result;
    }
}