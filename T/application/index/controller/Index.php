<?php
namespace app\index\controller;

use app\common\controller\Home;
use app\common\model\Archive;
use app\common\model\ArcComment;
use app\common\model\ArcThumbsup;
use app\common\model\Arctype;

class Index extends Home
{
    public function initialize()
    {
        parent::initialize();
    }
    
    public function index()
    {
        $archiveModel = new Archive();
        $arcCommentModel = new ArcComment();
        $arcThumbsupModel = new ArcThumbsup();
        $typeid_arr = cache('ARCTYPE_ARR_1');
        if(!$typeid_arr){
            //缓存不存在时，设置缓存
            $arctypeModel = new Arctype();
            $arctypeModel::$allChild = [];   //初始化无限子分类数组
            $typeid_arr = $arctypeModel->allChildArctype(1);//得到当前所有pid=1下的文章
            cache('ARCTYPE_ARR_1', $typeid_arr);
        }
        $where[] = [
                ['typeid', 'in', $typeid_arr],
                ['status', '=', '1'],
        ];
        $dataList = $archiveModel->where($where)->order('flag DESC,id DESC')->paginate(10);
        if( $dataList){
        foreach ($dataList as $k => $v){
            $v->arctype;
            $dataList[$k]['arctypeurl'] = url('@category/'.$v->arctype->dirs);
            $flag_arr = explode(',', $v['flag']);
            //判断j属性是否在数组中
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
            $dataList[$k]['comment_total'] = $arcCommentModel->where(['aid' => $v['id'], 'status' => 1])->count();   //文章评论总数
            $dataList[$k]['thumbs_total'] = $arcThumbsupModel->where('aid', $v['id'])->count();   //文章点赞总数
        }
        $parent = ['id' => '0'];
        $this->assign('parent', $parent);
        $this->assign('dataList', $dataList);
    }
        return $this->fetch();
    }
}
