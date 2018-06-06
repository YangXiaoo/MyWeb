<?php
namespace app\index\controller;

use think\Controller;
use app\index\model\UserArctype;
use app\index\model\UserArchive;
use app\index\model\ArcComment;
use app\index\model\ArcThumbsup;
use app\index\model\Timeline;
use app\index\model\User;

class Userdetail extends Common
{
/**
 * @todo  个人文章
 * @time(2018-3-5)
 */   
    public function index($dirs, $id)
    {
        //1.获得当前文章的信息
        //2.获得当前文章的内容
        //3.获得当前文章的类型
        //4.获得当前文章评论数
        //5.获得当前文章点赞数
        //6.获得当前文章的评论
        $arctypeModel = new UserArchive();
        $archive = $arctypeModel->where(['id'=>$id])->find();
        if (empty($archive)) {
            return $this->redirect('404');
        }else{
            $where= [
                'aid' => $archive['id'], 
                'status' => 1,
                ];
            $archive['paper'] = $archive->Paperinfo;
            $archive['paper']['content'] = htmlspecialchars_decode($archive['paper']['content']);
            $archive['typename'] = $archive->Arctype->typename;
            $uModer = new User();
            $ur = $uModer->where(['id' => $archive['uid']])->find();
            $archive['username'] = $ur['username'];
            $acModel = new ArcComment();
            $archive['comment_total'] = $acModel->where($where)->count();
            $atModel = new ArcThumbsup();
            $archive['thumbs_total'] = $atModel->where('aid', $archive['id'])->count();
            $where = [
                'aid' => $archive['id'], 
                'status' => 1,
                'cid' => 0,
            ];
            $acList = $acModel->where($where)->order('id DESC')->paginate(10, false, page_param());
            foreach ($acList as $k => $v){
                $v->replay;
                $v->userAvatar;
                $v->user;
            }
            $userid = session('webuserId');
            $this->view($archive);
            $arctypeModel->where(['id'=>$archive['id']])->setInc('click');   
            $this->assign('archive', $archive);   
            $this->assign('acList', $acList);   
            $this->assign('userId', $userid);
            return $this->fetch();   
    }
    } 
/**
 * @todo  阅读浏览记录
 * @time(2018-3-2)
 * @redo
 */
    public function view($archive){
        $uid = session('webuserId');
        if (!empty($uid)) {
        $url_path = request()->url();
        $tlModel = new Timeline();
        $data = [
            'uid' => $uid,
            'description' => '浏览了文章<strong><a href="'.$url_path.'" target="_blank">'.$archive['title'].'</a></strong>',
            'icon'  => 'fa fa-eye bg-teal',
            'aid'   =>  $archive['id'],
            'create_time'   =>  time(),
            'action'    => 1,//阅读
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
    $acModel = new UserArchive();
    $dataList = $acModel->where(['id' => $id])->find();
        $result = $arcthumb->allowField(true)->save($data);
        $link = $_SERVER['HTTP_REFERER'];
        //写入记录
        if ($uid != 0) {
            $tlModel = new Timeline();
            //$url_path = $request->controller().'/'.$$dataList['aid'];
            $saveDate = [
                'uid'   =>  $uid,
                'aid'   =>  $id,
                'description'   =>  '赞了文章<strong><a href="'.$link.'" target="_blank">'.$dataList['title'].'</a></strong>',
                'icon'  => 'fa fa-thumbs-o-up bg-red',
                'action'    => 2,//点赞
                'active'    =>0
                ];
            $vieresult = $tlModel->save($saveDate);
            $savetumb = [
                'uid'   =>  $uid,
                'aid'   =>  $id,
                'description'   =>  '赞了你的文章<strong><a href="'.$link.'" target="_blank">'.$dataList['title'].'</a></strong>',
                'icon'  => 'fa fa-thumbs-o-up bg-red',
                'action'    => 15,//点赞
                'active'    =>1
                ];
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
            $acModel = new UserArchive();
            $archive = $acModel->where(['id' => $aid])->find();
            $acModel->where(['id' => $aid])->setInc('collect');
            $dirs = $archive->Arctype->dirs;
            $data = [
                'uid'   =>  $uid,
                'ruid'  =>  session('oId'),
                'aid'   =>  $aid,
                'action'=>  16,
                'description'   =>'收藏了文章&nbsp;<strong><a href="'.URL_PATHS.'/userdetail/'.$dirs.'/'.$aid.'" target="_blank">'.$archive['title'].'</a>&nbsp;</strong>',
                'icon'  =>  'fa fa-star-o bg-red',
                'active'    => 0
            ];
            $tlModel = new Timeline();
            $tlModel->create($data);
            $udata = [
                'ruid'   =>  $uid,
                'uid'  =>  session('oId'),
                'aid'   =>  $aid,
                'action'=>  17,
                'description'   =>'收藏了你的文章&nbsp;<strong><a href="'.URL_PATHS.'/userdetail/'.$dirs.'/'.$aid.'" target="_blank">'.$archive['title'].'</a>&nbsp;</strong>',
                'icon'  =>  'fa fa-star-o bg-red',
                'active'    => 1
            ];
            $tlModel->save($udata);
            return ajaxReturn('收藏文章成功', '', 1);//此处应该有判断未知故障
        }

    }
}
