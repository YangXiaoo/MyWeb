<?php
namespace app\index\controller;

use think\Controller;
use app\index\model\User;
use app\index\model\UserInfo;
use app\index\model\Timeline;
use app\index\model\Follower;
use app\index\model\UserPaper;
use app\index\model\UserArctype;
use app\index\model\UserArchive;
use app\index\model\ArcComment;
use app\index\model\ArcThumbsup;

class Usercategory extends Common
{
/**
 * @todo  category
 * @time(2018-3-6)
 */   
    public function index($dirs, $id)
    {
        //获得当前文档类型信息
        //当前文档链接
        //当前文档内文章的点赞数。。。
        $archiveModel = new UserArchive();
        $archive = $archiveModel->where(['typeid'=> $id])->order('create_time DESC')->paginate(10, false, page_param());;
        if (empty($archive)) {
            return $this->fetch();
        }else{
            $uModer = new User();
            $acModel = new ArcComment();
            $atModel = new ArcThumbsup();
            $where= [
                'aid' => $archive['id'], 
                'status' => 1,
                ];
            foreach ($archive as $k => $v) {
            $archive[$k]['typename'] = $v->Arctype->typename;
            //$ur = $uModer->where(['id' => $v['uid']])->find();
            //$archive[$k]['username'] = $ur['username'];
            $archive[$k]['comment_total'] = $acModel->where($where)->count();
            $archive[$k]['thumbs_total'] = $atModel->where('aid', $v['id'])->count();
            $v->Arctype;
            $flag_arr = explode(',', $v['flag']);
            if (in_array('j', $flag_arr) && !empty($v['jumplink'])) {
                $archive[$k]['arcurl'] = url($v['jumplink']);
                $archive[$k]['target'] = 'target="_blank"';
            }else{
                $archive[$k]['arcurl'] = url('userdetail/'.$v->Arctype->typename.'/'.$v['id']);
                $archive[$k]['target'] = 'target="_self"';
            }
            if (!empty($v['litpic']) && file_exists(WEB_PATH.$v['litpic'])){
                $img_info = getimagesize( WEB_PATH.$v['litpic'] );
                if ($img_info[0] == $img_info[1]){
                    $archive[$k]['listStyle'] = 'left';
                }else{
                    $archive[$k]['listStyle'] = 'top';
                }
            }else{
                $archive[$k]['listStyle'] = 'top';
            }
            }                
            $this->assign('dataList', $archive);   
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
            'description' => '浏览了<strong>'.$archive['title'].'</strong>',
            'icon'  => 'fa fa-eye bg-teal',
            'aid'   =>  $archive['id'],
            'link'  =>  $url_path,
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
}
