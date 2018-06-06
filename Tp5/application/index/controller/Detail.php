<?php
namespace app\index\controller;

use think\Controller;
use app\index\model\Arctype;
use app\index\model\Archive;
use app\index\model\Guestbook;
use app\index\model\Flink;
use app\index\model\ModuleClass;
use app\index\model\ArcComment;
use app\index\model\ArcThumbsup;
use app\index\model\Timeline;

class Detail extends Common
{

    
    public function index($dirs, $id)
    {
        //检测静态页面
        //return
        $acModel = new ArcComment();
        $atModel = new ArcThumbsup();
        $arctypeModel = new Arctype();
        $arctype = $arctypeModel->where(['dirs'=>$dirs])->order('id DESC')->find();
        $arctype->arctypeMod;
        
        $archiveModel = new Archive();
        $archive = $archiveModel->where(['id'=>$id, 'status'=>1])->find();
        $archive['comment_total'] = $acModel->where(['aid' => $archive['id'], 'status' => 1])->count();   //文章评论总数
        $archive['thumbs_total'] = $atModel->where('aid', $archive['id'])->count();   //文章点赞总数
        if (empty($archive)){
            //跳转404
        }
        $archive['addondata'] = $archive->{$arctype->arctypeMod->mod};   //拓展模式表数据
        unset($archive[$arctype->arctypeMod->mod]);
        
        if($arctype['pid'] == '0'){
            $parent = $arctype;
        }else{
            $arctypeModel = new Arctype();
            $parent = $arctypeModel->topArctypeData($arctype['pid']);
        }
        
        $where = [
                'aid' => $archive['id'],
                'status' => 1,
        ];
        $arccommentTotal = $acModel->where($where)->count();
        $where['cid'] = 0;
        $acList = $acModel->where($where)->order('id DESC')->page(1, 5)->select();
        foreach ($acList as $k => $v){
            $v->replay;
            $v->userAvatar;
            $v->user;
        }
        if ($dirs == 'diary' && !isAdmin()) {
            return $this->fetch('diary');
        }
        $userid = session('webuserId');
        $this->view($archive);
        click($archive);   //文档增加阅读数
        $this->assign('parent', $parent);   //当前文章栏目顶级栏目信息
        $this->assign('arctype', $arctype);   //当前文章栏目信息
        $this->assign('archive', $archive);   //当前文章信息
        $this->assign('arccommentTotal', $arccommentTotal);   //文档评论总数
        $this->assign('acList', $acList);   //文档评论
        $this->assign('userId', $userid);
        if ($dirs == 'diary') {
            return $this->fetch('diary');
        }else{
            return $this->fetch($arctype['temparticle']);   //栏目模板
       }
    } 
   public function more($dirs, $id, $page)
    {
        //检测静态页面
        //return
        $acModel = new ArcComment();
        $arctypeModel = new Arctype();
        $arctype = $arctypeModel->where(['dirs'=>$dirs])->order('id DESC')->find();
        $arctype->arctypeMod;
        
        $archiveModel = new Archive();
        $archive = $archiveModel->where(['id'=>$id, 'status'=>1])->find();
        if (empty($archive)){
            //跳转404
        }
        $archive['addondata'] = $archive->{$arctype->arctypeMod->mod};   //拓展模式表数据
        unset($archive[$arctype->arctypeMod->mod]);        
        $where = [
                'aid' => $archive['id'],
                'status' => 1,
        ];
        $where['cid'] = 0;
        $acList = $acModel->where($where)->order('id DESC')->page($page,5)->select();
        foreach ($acList as $k => $v){
            $v->replay;
            $v->userAvatar;
            $v->user;
        }
         if ($dirs == 'diary' && !isAdmin()) {
            return $this->fetch('diary');
        }
        $userid = session('webuserId');
        $this->assign('archive', $archive);   //当前文章信息
        $this->assign('acList', $acList);   //文档评论
        $this->assign('userId', $userid);
        return $this->fetch('inc/new_comen');
    } 
/**
 * @todo  阅读浏览记录
 * @time(2018-3-2)
 */
    public function view($archive){
        $uid = session('webuserId');
        if (!empty($uid)) {
        $url_path = request()->url();
        $tlModel = new Timeline();
        $data = [
            'uid' => $uid,
            'description' => '浏览了<strong><a href="'.$url_path.'" target="_blank">'.$archive['title'].'</a></strong>',
            'icon'  => 'fa fa-eye bg-teal',
            'aid'   =>  $archive['id'],
            'create_time'   =>  time(),
            'action'    => 1,//阅读
            'active'    =>0
        ];
        $where =[
            'uid'   =>  $uid,
            'aid'   =>  $archive['id'],
            'action'    => 1,
        ];
        $result = $tlModel->where($where)->find();
        if (empty($result)) {
            $tlModel->save($data);
        }else{
            $tlModel->where($where)->update($data);
        }
    }
}
/**
 * [thumb 异步加载点赞并写入TIMELINE中]
 * @return [array] 
 * @time(2018-2-24)
 */
 public function thumb($id){  
    $uid = session('webuserId');
    if (empty($uid)) {
        $uid = 0;
    }
    $arcthumb = new ArcThumbsup; 
    if (isset($_COOKIE[$id+10000])) {
            $data['info'] = "你已经赞过了";   
            return ajaxReturn($data['info'], '', 0); 
        }
    $data = [
        'uid' => $uid,
        'aid' => $id,
    ];
    $acModel = new Archive();
    $dataList = $acModel->where(['id' => $id])->find();
        $result = $arcthumb->allowField(true)->save($data);
        //写入记录
        if ($uid != 0) {
            $tlModel = new Timeline();
            //$url_path = $request->controller().'/'.$$dataList['aid'];
            $saveDate = [
                'uid'   =>  $uid,
                'aid'   =>  $id,
                'description'   =>  '赞了<strong>'.$dataList['title'].'</strong>',
                'link'  =>  $_SERVER['HTTP_REFERER'],
                'icon'  => 'fa fa-thumbs-o-up bg-red',
                'action'    => 2,//点赞
                'active'    =>0,
                ];
            $vieresult = $tlModel->save($saveDate);
        }
        if(!isset($_COOKIE[$id+10000]) && $result!==false){  
            $cookiename = $id+10000;  
            setcookie($cookiename,40,time()+120,'/');   
            $data['info'] = "点赞成功";  
            return ajaxReturn($data['info'],'', 1);   
        }else{  
            $data['info'] = "点赞失败";   
            return ajaxReturn($data['info']);      
            }
    }

/**
 * @todo  收藏文章
 * @time(2018-3-8)
 */
    public function like($aid){
        $uid = session('webuserId');
        if (empty($uid)) {
            return ajaxReturn('请先登录','',2);
        }
        $where = [
            'aid' =>  $aid,
            'uid'   =>$uid,
            'action' => 18//收藏站长的文章
        ];
        $tlModel = new Timeline();
        $result = $tlModel->where($where)->find();
        if ($result) {
            return ajaxReturn('你已经收藏了该文章', '', 0);
        }else{
            $acModel = new Archive();
            $archive = $acModel->where(['id' => $aid])->find();
            $acModel->where(['id' => $aid])->setInc('collect');
            $dirs = $archive->arctype->dirs;
            $data = [
                'uid'   =>  $uid,
                'aid'   =>  $aid,
                'action'=>  18,
                'description'   =>'收藏了文章&nbsp;<strong><a href="'.URL_PATHS.'/detail/'.$dirs.'/'.$aid.'" target="_blank">'.$archive['title'].'</a>&nbsp;</strong>',
                'icon'  =>  'fa fa-star-o bg-red',
                'active'    => 0
            ];
            $tlModel->save($data);
            return ajaxReturn('收藏文章成功', '', 1);
        }

    }
}
