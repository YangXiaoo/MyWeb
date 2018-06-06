<?php
namespace app\index\controller;

use app\index\controller\Common;
use app\index\model\Archive;
use app\index\model\Arctype;
use app\index\model\ArcComment;
use app\index\model\ArcThumbsup;
use app\index\model\UserArctype;
use app\index\model\UserArchive;

class Search extends Common
{
    public function _initialize()
    {
        parent::_initialize();
    }
    
    public function index()
    {
        $archiveModel = new Archive();
        $acModel = new ArcComment();
        $atModel = new ArcThumbsup();
        $typeid_arr = cache('ARCTYPE_ARR_1');
        if(!$typeid_arr){
            $arctypeModel = new Arctype();
            $arctypeModel::$allChild = [];   //初始化无限子分类数组
            $typeid_arr = $arctypeModel->allChildArctype(1);
            cache('ARCTYPE_ARR_1', $typeid_arr);
        }
        if (isAdmin()) {//日记权限
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
        $search_k = input('get.k');
        if ( isset($search_k) && !empty($search_k)){
            $where['title'] = ['like', '%'.input('get.search').'%'];
            $where['title|description'] = ['like', '%'.$search_k.'%'];
        }
        $dataList = $archiveModel->where($where)->order('id DESC')->paginate(10, false, page_param());
        foreach ($dataList as $k => $v){
            //str_ireplace(find,replace,string,count)
            $dataList[$k]['title'] = str_ireplace($search_k, "<em>".$search_k."</em>", $v['title']);
            $dataList[$k]['description'] = str_ireplace($search_k, "<em>".$search_k."</em>", $v['description']);
            $v->arctype;
            $dataList[$k]['arctypeurl'] = url('@category/'.$v->arctype->dirs);
            $flag_arr = explode(',', $v['flag']);
            if(in_array('j',$flag_arr) && !empty($v['jumplink'])){
                $dataList[$k]['arcurl'] = $v['jumplink'];
                $dataList[$k]['target'] = 'target="_blank"';
            }else{
                $dataList[$k]['arcurl'] = url('detail/'.$v->arctype->dirs.'/'.$v['id']);
                $dataList[$k]['target'] = 'target="_self"';
            }
            //图片位置
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
            $dataList[$k]['thumbs_total'] = $atModel->where('aid', $v['id'])->count();//文章点赞总数
        }
        $parent = ['id' => '0'];
        $this->assign('parent', $parent);
        $this->assign('dataList', $dataList);
        
        return $this->fetch();
    }

/**
 * @todo  其它用户文章搜索
 * @time(2018-3-7)
 */   
    public function other()
    {
        $archiveModel = new UserArchive();
        $aeModel = new UserArctype();
        $acModel = new ArcComment();
        $atModel = new ArcThumbsup();
        $search_k = input('get.k');
        if ( isset($search_k) && !empty($search_k)){
            $where['title'] = ['like', '%'.input('get.search').'%'];
            $where['title|description'] = ['like', '%'.$search_k.'%'];
        }
        $where['uid'] = session('oId');
        $dataList = $archiveModel->where($where)->order('id DESC')->paginate(10, false, page_param());
        foreach ($dataList as $k => $v){
            //str_ireplace(find,replace,string,count)
            $dataList[$k]['title'] = str_ireplace($search_k, "<em>".$search_k."</em>", $v['title']);
            $dataList[$k]['description'] = str_ireplace($search_k, "<em>".$search_k."</em>", $v['description']);
            $v->Arctype;
            $dataList[$k]['arctypeurl'] = url('@usercategory/'.$v->Arctype->dirs,'/'.$v->Arctype->id);
            $flag_arr = explode(',', $v['flag']);
            if(in_array('j',$flag_arr) && !empty($v['jumplink'])){
                $dataList[$k]['arcurl'] = $v['jumplink'];
                $dataList[$k]['target'] = 'target="_blank"';
            }else{
                $dataList[$k]['arcurl'] = url('userdetail/'.$v->Arctype->dirs.'/'.$v['id']);
                $dataList[$k]['target'] = 'target="_self"';
            }
            //图片位置
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
            $dataList[$k]['thumbs_total'] = $atModel->where('aid', $v['id'])->count();//文章点赞总数
        }
        $parent = ['id' => '0'];
        $this->assign('parent', $parent);
        $this->assign('dataList', $dataList);
        
        return $this->fetch('usersearch');
    }
}
