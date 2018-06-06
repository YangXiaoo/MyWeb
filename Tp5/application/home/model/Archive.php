<?php
namespace app\home\model;

use think\Model;
use app\home\model\Arctype;

class Archive extends Model
{
    public function arctype()
    {
        //archive中的typeid与arctype的id相关联，关联的范围为arctype的typename,mid,dirs
        //mid在arctype_mod中对应的id表示单页，模型。。。
        return $this->hasOne('Arctype', 'id', 'typeid')->field('typename, mid, dirs');
    }
    
    public function arctypeMod()
    {
        //mod模型操作
        //好像有错，这里应是arctype与arctypeMod关联
        return $this->hasOne('ArctypeMod', 'id', 'mid')->field('mod');
    }
    
    /**
     * 文章模型关联表
     */
    public function addonarticle()
    {
        //可以获取文章内容addonarticl中的ad对应arctype中的id
        return $this->hasOne('addonarticle', 'aid', 'id');
    }
        public function addonjump()
    {
        //可以获取文章内容addonarticl中的ad对应arctype中的id
        return $this->hasOne('addonarticle', 'aid', 'id');
    }
    
    /**
     * 视频模型关联表
     */
    public function addonvideo()
    {
        return $this->hasOne('addonvideo', 'aid', 'id');
    }
    
    /**
     * 相册模型关联表
     */
    public function addonalbum()
    {
        return $this->hasOne('addonalbum', 'aid', 'id');
    }
    
//2018.2.11 arclist无效 <已查找> 无效原因：  common.php 未写该方法   
/**
 * @Title: atc
 * @Description: tf_archive中查询最新的
 * @param $limit 显示数据量
 * @param string $order 查询方法
 * @return array
 * @author 杨潇
 * @date 2018年2月11日
 * @throws
 */
    public function atc($limit,$order)
    {
        $where['status'] = 1;
        $list = $this->where($where)->limit($limit)->order($order)->select();
        foreach ($list as $k => $val) {
             $list[$k]['arctypeurl'] = url('cate/'.$val->arctype->dirs);
            $flag_arr = explode(',', $val['flag']);
            if(in_array('j',$flag_arr) && !empty($val['jumplink'])){
                $list[$k]['arcurl'] = $val['jumplink'];
            }else{
                $list[$k]['arcurl'] = url('deta/'.$val->arctype->dirs.'/'.$val['id']);
            }
        }
        return $list;
    }
    /**
     * @Title: arclist
     * @Description: todo(查询栏目下的文章)
     * @param int $typeid 栏目ID（当前栏目下的所有[无限级]栏目ID）
     * @param int $limit 查询数量
     * @param string $flag 推荐[c] 特荐[a] 头条[h] 滚动[s] 图片[p] 跳转[j]
     * @param string $order 排序
     * @return array
     * @author y
     * @date 2018年2月7日
     * @throws
     */
    public function arclist($typeid, $limit, $flag, $order)
    {
        if ($typeid == '0'){
            return;
        }
        $typeidStr = cache('ARCTYPE_ARR_'.$typeid);
        if (!$typeidStr){
            $arctype = new Arctype();
            $arctype::$allChild = array();   //初始化无限子分类数组
            $typeidArr = $arctype->allChildArctype($typeid);
            $typeidStr = implode(',', $typeidArr);
            cache('ARCTYPE_ARR_'.$typeid, $typeidStr);
        }
        $where['status'] = 1;
        $where['typeid'] = ['in', $typeidStr];
        if (!empty($flag)){
            $where[] = ['exp', "FIND_IN_SET('".$flag."', flag)"];
        }
        $list = $this->where($where)->limit($limit)->order($order)->select();
        foreach ($list as $k=>$val){
            $mod = $val['mod'];
            $list[$k]['addondata'] = $val->$mod;
            unset($list[$k][$mod]);
            $val->arctype;
            $list[$k]['arctypeurl'] = url('@category/'.$val->arctype->dirs);
            $flag_arr = explode(',', $val['flag']);
            if(in_array('j',$flag_arr) && !empty($val['jumplink'])){
                $list[$k]['arcurl'] = $val['jumplink'];
            }else{
                $list[$k]['arcurl'] = url('detail/'.$val->arctype->dirs.'/'.$val['id']);
            }
        }
        return $list;
    }

    
    /**
     * @Title: click
     * @Description: todo(文档点击数+1)
     * @param array $archiveArr 当前文档数组
     * @author y
     * @date 2018年2月7日
     * @throws
     */
    public function click($archiveArr)
    {
        //setDec为减，setInc为加，配合where一起使用
        return $this->where('id', $archiveArr->id)->setInc('click');
    }
/**
 * @todo  上下页按钮
 * @time(2018-3-7)
 * @author yx
 */
    public function preview($archive){

        $upLabel = "<div class=\"row\">";
        $endLabel = "</div>";
        $leftLabel = "<div class=\"col-sm-6\">";
        $preLabel = $leftLabel."<span>上一篇:</span>";
        $nxtLabel = $leftLabel."<span>下一篇:</span>";
        $precd = [
            'typeid'=>  $archive['typeid'],
            'id'    =>  ['gt', $archive['id']]
        ];
        $nxtcd = [
            'typeid'=>  $archive['typeid'],
            'id'    =>  ['lt', $archive['id']]
        ];
        $pre = $this->where($precd)->order('id DESC')->find();
        if (!empty($pre)) {
            $flag_arr = explode($pre['flag']);
            if (in_array('j', $falg_arr) && !empty($pre['jumplink'])) {
                $pre['arcurl'] = $pre['jumplink'];
                $prelink = $leftLabel.$preLabel."<a href=\"".$pre['arcurl']."\" target=\"_blank\">".$pre['arcurl']."</a>".$endLabel;
            }else{
                $pre['arcurl'] = url('deta/'.$pre->Arctype->dirs.'/'.$pre['id']);
                $preLink = $preLabel."<a href=\"".$pre['arcurl']."\" target=\"_self\">".$pre['title']."</a>".$endLabel;
            }
        }else{
            $preLink = $preLabel."没有了哦".$endLabel;
        }
        $nxt = $this->where($nxtcd)->order('id DESC')->find();
        if (!empty($nxt)) {
            $flag_arr = explode(',', $nxt['flag']);
            if (in_array('j', $falg_arr) && !empty($nxt['jumplink'])) {
                $nxt['arcurl'] = $pre['jumplink'];
                $nxtlink = $nxtLabel."<a href=\"".$nxt['arcurl']."\" target=\"_blank\">".$nxt['title']."</a>".$endLabel;
            }else{
                $nxt['arcurl'] = url('deta/'.$nxt->Arctype->dirs.'/'.$nxt['id']);
                $nxtLink = $nxtLabel."<a href=\"".$nxt['arcurl']."\" target=\"_self\">".$nxt['title']."</a>".$endLabel;
            }
        }else{
                $nxtLink = $nxtLabel."没有了哦".$endLabel;
            }           
        return $upLabel.$preLink.$nxtLink.$endLabel;
    }
/**
 * @todo  上下页按钮
 * @time(2018-3-7)
 * @author yx
 */
    public function prview($archive){

        $upLabel = "<div class=\"row\">";
        $endLabel = "</div>";
        $leftLabel = "<div class=\"col-sm-6\">";
        $preLabel = $leftLabel."<span>上一篇:</span>";
        $nxtLabel = $leftLabel."<span>下一篇:</span>";
        $precd = [
            'typeid'=>  $archive['typeid'],
            'id'    =>  ['lt', $archive['id']]
        ];
        $nxtcd = [
            'typeid'=>  $archive['typeid'],
            'id'    =>  ['gt', $archive['id']]
        ];
        $pre = $this->where($precd)->order('id DESC')->find();
        if (!empty($pre)) {
            $flag_arr = explode($pre['flag']);
            if (in_array('j', $falg_arr) && !empty($pre['jumplink'])) {
                $pre['arcurl'] = $pre['jumplink'];
                $prelink = $leftLabel.$preLabel."<a href=\"".$pre['arcurl']."\" target=\"_blank\">".$pre['arcurl']."</a>".$endLabel;
            }else{
                $pre['arcurl'] = url('@deta/'.$pre->Arctype->dirs.'/'.$pre['id']);
                $preLink = $preLabel."<a href=\"".$pre['arcurl']."\" target=\"_self\">".$pre['title']."</a>".$endLabel;
            }
        }else{
            $preLink = $preLabel."没有了哦".$endLabel;
        }
        $nxt = $this->where($nxtcd)->order('id ASC')->find();
        if (!empty($nxt)) {
            $flag_arr = explode(',', $nxt['flag']);
            if (in_array('j', $falg_arr) && !empty($nxt['jumplink'])) {
                $nxt['arcurl'] = $pre['jumplink'];
                $nxtlink = $nxtLabel."<a href=\"".$nxt['arcurl']."\" target=\"_blank\">".$nxt['title']."</a>".$endLabel;
            }else{
                $nxt['arcurl'] = url('@deta/'.$nxt->Arctype->dirs.'/'.$nxt['id']);
                $nxtLink = $nxtLabel."<a href=\"".$nxt['arcurl']."\" target=\"_self\">".$nxt['title']."</a>".$endLabel;
            }
        }else{
                $nxtLink = $nxtLabel."没有了哦".$endLabel;
            }           
        return $upLabel.$preLink.$nxtLink.$endLabel;
    }

        public function page_category($arctype){
        $arList = $this->where(['typeid' => $arctype['id']])->order('id ASC')->select();
        foreach ($arList as $k=>$v){
            if (empty($v['jumplink'])) {
                $arList[$k]['url'] = url('@deta/'.$arctype['dirs'].'/'.$v['id']);
                $arList[$k]['target'] = 'target="_self"';
            }else{
                $arList[$k]['url'] = $v['jumplink'];
                $arList[$k]['target'] = 'target="_blank"';
            }
        }
        return $arList;
    }
}