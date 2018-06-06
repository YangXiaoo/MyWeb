<?php
namespace app\index\controller;

use app\common\controller\Home;
use app\common\model\Arctype;
use app\common\model\Archive;
use app\common\model\ArcComment;
use app\common\model\ArcThumbsup;

class Detail extends Home
{
    public function initialize()
    {
        parent::initialize();
    }
    
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
        $acList = $acModel->where($where)->order('id DESC')->paginate(10, false, page_param());
        foreach ($acList as $k => $v){
            $v->replay;
        }
        
        click($archive);   //文档增加点击数
        $this->assign('parent', $parent);   //当前文章栏目顶级栏目信息
        $this->assign('arctype', $arctype);   //当前文章栏目信息
        $this->assign('archive', $archive);   //当前文章信息
        $this->assign('arccommentTotal', $arccommentTotal);   //文档评论总数
        $this->assign('acList', $acList);   //文档评论
        return $this->fetch($arctype['temparticle']);   //栏目模板
    }
}
