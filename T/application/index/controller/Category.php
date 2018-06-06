<?php
namespace app\index\controller;

use app\common\controller\Home;
use app\common\model\Arctype;
use app\common\model\Archive;
use app\common\model\Guestbook;
use app\common\model\ArcComment;
use app\common\model\ArcThumbsup;

class Category extends Home
{
    public function initialize()
    {
        parent::initialize();
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
        
        $where[] = [
             ['typeid', 'in', $typeid_arr],
             ['status', '=', 1],
        ];
        if (input('get.search')){
            $where['title'] = ['like', '%'.input('get.search').'%'];
        }
        $archiveModel = new Archive();
        $acModel = new ArcComment();
        $atModel = new ArcThumbsup();
        $dataList = $archiveModel->where($where)->order('id DESC')->paginate($arctype['pagesize'], false, page_param());
        foreach ($dataList as $k => $v){
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
        
        if ($arctype['templist'] == 'list_guestbook'){
            $this->guestbook($arctype);
        }
        
        $this->assign('parent', $parent);               //当前栏目顶级栏目信息
        $this->assign('arctype', $arctype);             //当前栏目信息
        return $this->fetch($arctype['templist']);      //栏目模板
    }
    
    private function guestbook($arctype)
    {
        $gbModel = new Guestbook();
        $where = [
                'tid' => $arctype['id'],
                'status' => 1,
        ];
        $guestbookTotal = $gbModel->where($where)->count();
        $where['gid'] = 0;
        $gbList = $gbModel->where($where)->order('id DESC')->paginate(10, false, page_param());
        foreach ($gbList as $k => $v){
            $v->replay;
        }
        $this->assign('guestbookTotal', $guestbookTotal);   //总留言数
        $this->assign('gbList', $gbList);                   //留言信息
    }
}
