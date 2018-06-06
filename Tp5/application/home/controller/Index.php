<?php
namespace app\home\controller;

use think\Controller;
use app\home\model\Arctype;
use app\home\model\Archive;
use app\home\model\ArcComment;
use app\home\model\ArcThumbsup;
use app\home\model\Flink;
use app\home\model\User;
use app\home\model\Timeline;

class Index extends Common
{
    public function _initialize()
    {
        parent::_initialize();
    }
    
    public function index()
    {
        $arctypeModel = new Arctype();
        $arcCommentModel = new ArcComment();
        $arcThumbsupModel = new ArcThumbsup();
        $dirs = 'category';
        $arctype = $arctypeModel->field(true)->where(['dirs'=>$dirs, 'status'=>1])->order('id DESC')->find();
        if (empty($arctype)){
            //跳转404
            exit("404");
        }
        $typeid_arr = cache('ARCTYPE_ARR_1');
        if(!$typeid_arr){
            $arctypeModel = new Arctype();
            $arctypeModel::$allChild = [];   
            $typeid_arr = $arctypeModel->allChildArctype($arctype['id']);  
            cache('ARCTYPE_ARR_1', $typeid_arr); 
        }  
        if (isAdmin()) {
            $where = [
             'typeid'=> ['in', $typeid_arr],
             'status'=> 1,
            ];
        }else{
            $typeid_arr = unset_array("15", $typeid_arr);
            $where = [
                'typeid'=> ['in', $typeid_arr],
                'status'=> 1,
            ];
        }
        if (input('get.search')){
            $where['title'] = ['like', '%'.input('get.search').'%'];
        }
        $archiveModel = new Archive();
        $dataList = $archiveModel->where($where)->order('id DESC')
        ->page(1,10)->select();
        foreach ($dataList as $k => $v){
            $dataList[$k]['comment_total'] = $arcCommentModel->where(['aid' => $v['id'], 'status' => 1])->count();   //文章评论总数
            $dataList[$k]['thumbs_total'] = $arcThumbsupModel->where('aid', $v['id'])->count();   //文章点赞总数
            $v->arctype;
            $dataList[$k]['arctypeurl'] = url('home/cate/'.$v->arctype->dirs);   //文章栏目链接
            $flag_arr = explode(',', $v['flag']);
            if (in_array('j', $flag_arr) && !empty($v['jumplink'])) {
                $dataList[$k]['arcurl'] = $v['jumplink'];
                $dataList[$k]['target'] = 'target="_blank"';
            }else{
            $dataList[$k]['arcurl'] = url('home/deta/'.$v->arctype->dirs.'/'.$v['id']);   //文章链接  
            $dataList[$k]['target'] = 'target="_self"';
            }
            $dataList[$k]['typename'] = $v->arctype->typename;
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
            
        }
        
        if($arctype['pid'] == '0'){
            $parent = $arctype;
        }else{
            $arctypeModel = new Arctype();
            $parent = $arctypeModel->topArctypeData($arctype['pid']);
        }       
        $this->assign('parent', $parent);   //当前栏目顶级栏目信息
        $this->assign('arctype', $arctype);   //当前栏目信息
        $this->assign('dataList', $dataList);   //列表数据【包含无限子类数据】
        return $this->fetch();   //栏目模板
    }
   public function more($pages)
    {
        $arctypeModel = new Arctype();
        $arcCommentModel = new ArcComment();
        $arcThumbsupModel = new ArcThumbsup();
        $dirs = 'category';
        $arctype = $arctypeModel->field(true)->where(['dirs'=>$dirs, 'status'=>1])->order('id DESC')->find();
        if (empty($arctype)){
            //跳转404
            exit("404");
        }
        $typeid_arr = cache('ARCTYPE_ARR_1');
        if(!$typeid_arr){
            $arctypeModel = new Arctype();
            $arctypeModel::$allChild = [];   
            $typeid_arr = $arctypeModel->allChildArctype($arctype['id']);  
            cache('ARCTYPE_ARR_1', $typeid_arr); 
        }  
        if (isAdmin()) {
            $where = [
             'typeid'=> ['in', $typeid_arr],
             'status'=> 1,
            ];
        }else{
            $typeid_arr = unset_array("15", $typeid_arr);
            $where = [
                'typeid'=> ['in', $typeid_arr],
                'status'=> 1,
            ];
        }
        if (input('get.search')){
            $where['title'] = ['like', '%'.input('get.search').'%'];
        }
        $archiveModel = new Archive();
        $dataList = $archiveModel->where($where)->order('id DESC')
        ->page($pages,10)->select();
        foreach ($dataList as $k => $v){
            $dataList[$k]['comment_total'] = $arcCommentModel->where(['aid' => $v['id'], 'status' => 1])->count();   //文章评论总数
            $dataList[$k]['thumbs_total'] = $arcThumbsupModel->where('aid', $v['id'])->count();   //文章点赞总数
            $v->arctype;
            $dataList[$k]['arctypeurl'] = url('@cate/'.$v->arctype->dirs);   //文章栏目链接
            $flag_arr = explode(',', $v['flag']);
            if (in_array('j', $flag_arr) && !empty($v['jumplink'])) {
                $dataList[$k]['arcurl'] = $v['jumplink'];
                $dataList[$k]['target'] = 'target="_blank"';
            }else{
            $dataList[$k]['arcurl'] = url('deta/'.$v->arctype->dirs.'/'.$v['id']);   //文章链接  
            $dataList[$k]['target'] = 'target="_self"';
            }
            $dataList[$k]['typename'] = $v->arctype->typename;
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
            
        }
        
        $this->assign('arctype', $arctype);   //当前栏目信息
        $this->assign('dataList', $dataList);   //列表数据【包含无限子类数据】
        return $this->fetch('inc/new_arc');   //栏目模板
    }
}
