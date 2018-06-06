<?php
namespace app\home\controller;

use think\Controller;
use app\home\model\Arctype;
use app\home\model\Archive;
use app\home\model\Config;
use app\home\model\Flink;
use app\home\model\Banner;
use app\home\model\ArcComment;
use app\home\model\ArcThumbsup;
use app\home\model\User;

class Category extends Common
{
    public function _initialize(){
        parent::_initialize();
    }
    
public function index($dirs)
    {
        //检测静态页面
        //return        
        $arctypeModel = new Arctype();
        $arctype = $arctypeModel->field(true)->where(['dirs'=>$dirs, 'status'=>1])->order('id DESC')->find();
        if (!$arctype){
            //跳转404
            exit("404");
        }
        $arctype->arctypeMod;
        if ($arctype->arctypeMod->mod == 'addonpage'){
            return $this->tpl_page($arctype);
        }else if($arctype->arctypeMod->mod == 'addonjump'){
            return $this->redirect('http://www.lxa.kim/check/login.html');
        }else{
            return $this->tpl_list($arctype);
        }
    }
    
    private function tpl_list($arctype)
    {
        $typeid_arr = cache('ARCTYPE_ARR_'.$arctype['id']);
        if(!$typeid_arr){
            $arctypeModel = new Arctype();
            $arctypeModel::$allChild = [];   //初始化无限子分类数组
            $typeid_arr = $arctypeModel->allChildArctype($arctype['id']);
            cache('ARCTYPE_ARR_'.$arctype['id'], $typeid_arr);
        }
        if (isAdmin()) {
            $where = [
             'typeid'=> ['in', $typeid_arr],
             'status'=> 1,
            ];
        }else{
            $typeid_arr = unset_array("15", $typeid_arr);//日记不展示。unset_array('15', $typeid_arr)不可以
            $where = [
                'typeid'=> ['in', $typeid_arr],
                'status'=> 1,
            ];
        }
        if (input('get.search')){
            $where['title'] = ['like', '%'.input('get.search').'%'];
        }
        $archiveModel = new Archive();
        $acModel = new ArcComment();
        $atModel = new ArcThumbsup();
        if ($arctype['dirs'] == 'diary') {
            $order = 'create_time ASC';
        }else{
            $order = 'id DESC';
        }
        $act = $arctype['typename'];
        $dataList = $archiveModel->where($where)->order($order)->paginate($arctype['pagesize'], false, page_param());
        foreach ($dataList as $k => $v){
            $v->arctype;
            $dataList[$k]['typename'] = $v->arctype->typename;
            $dataList[$k]['arctypeurl'] = url('@cate/'.$v->arctype->dirs);
            $flag_arr = explode(',', $v['flag']);
            if(in_array('j',$flag_arr) && !empty($v['jumplink'])){
                $dataList[$k]['arcurl'] = $v['jumplink'];
                $dataList[$k]['target'] = 'target="_blank"';
            }else{
                $dataList[$k]['arcurl'] = url('deta/'.$v->arctype->dirs.'/'.$v['id']);   //文章链接  
                $dataList[$k]['target'] = 'target="_self"';
            }
            if (!empty($v['litpic']) && file_exists(WEB_PATH.$v['litpic'])){
                $img_info = getimagesize( WEB_PATH.$v['litpic'] );
                if ($img_info[0] == $img_info[1]){
                    $dataList[$k]['listStyle'] = 'left';
                }else{
                    $dataList[$k]['listStyle'] = 'top';
                }
            }else{
                $dataList[$k]['listStyle'] = 'top';
            }
            $dataList[$k]['comment_total'] = $acModel->where(['aid' => $v['id'], 'status' => 1])->count();  //文章评论总数
            $dataList[$k]['thumbs_total'] = $atModel->where('aid', $v['id'])->count();                      //文章点赞总数
        }
        
        if($arctype['pid'] == '0'){
            $parent = $arctype;
        }else{
            $arctypeModel = new Arctype();
            $parent = $arctypeModel->topArctypeData($arctype['pid']);
        }
        $this->assign('act', $act);
        $this->assign('parent', $parent);               //当前栏目顶级栏目信息
        $this->assign('arctype', $arctype);             //当前栏目信息
        $this->assign('dataList', $dataList);           //列表数据【包含无限子类数据】
        return $this->fetch($arctype['templist']);      //栏目模板
    }
    
    private function tpl_page($arctype)
    {
        if($arctype['pid'] == '0'){
            $parent = $arctype;
        }else{
            $arctypeModel = new Arctype();
            $parent = $arctypeModel->topArctypeData($arctype['pid']);
        }
        
      if ($arctype['templist'] == 'page-list'){
            $this->categorylist();
        }
        $arctype['content'] = htmlspecialchars_decode($arctype['content']);
        $this->assign('act', $arctype['typename']);  
        $this->assign('parent', $parent);               //当前栏目顶级栏目信息
        $this->assign('arctype', $arctype);             //当前栏目信息
        return $this->fetch($arctype['templist']);      //栏目模板
    }
    
  private function categorylist()
    {
        $acModel = new Archive();
        if (isAdmin()) {
            $where = [
             'status'=> 1
            ];
        }else{
            $where = [
                'typeid'=> ['neq', 15],
                'status'=> 1,
            ];
        }
        $pageCount = $acModel->where($where)->count();
/*         $acList = $acModel->where($where)->order('id DESC')->select();
        foreach ($acList as $k => $v){
            $arctype = $v->arctype;
            if (empty($v['jumplink'])) {
                $acList[$k]['url'] = url('deta/'.$arctype['dirs'].$v['id']);
                $acList[$k]['target'] = 'target="_self';
            }else{
                $acList[$k]['url'] = $v['jumplink'];
                $acList[$k]['target'] = 'target="_blank';
            }
        }  
        $this->assign('acList', $acList); */
        $this->assign('pageCount', $pageCount);         
    }

}