<?php
namespace app\home\controller;

use think\Controller;
use app\home\model\ArcComment as ArcComments;
use app\home\model\Archive;
use app\home\model\Arctype;
use app\home\model\Timeline;
use app\home\model\UserArchive;
use app\home\model\UserArctype;

class ArcComment extends Common{

	private $cModel;
	public function _initialize(){
		parent::_initialize();
		$this->cModel = new ArcComments();
	}
	public function archiveSave(){
		if (request()->isPost()) {
			$data = input('post.');
			$data['status'] = 1;
			$qian=array(" ","　","\t","\n","\r");
            $hou=array("&nbsp;","&nbsp;&nbsp;","&nbsp;","<br>","<br>");
			$data['content'] = str_replace($qian, $hou, $data['content']);
			//$data['content'] = nl2br($data['content']);
			$result = $this->cModel->allowField(true)->save($data);
			$archiveModel = new Archive();
            $typeid = $archiveModel->where('id', $data['aid'])->value('typeid');
            $arctypeModel = new Arctype();
            $dirs = $arctypeModel->where('id', $typeid)->value('dirs');
            $url_path = URL_PATHS.'/detail/'.$dirs.'/'.$data['aid'];//URL_PATHS全局变量，在入口文件public->index.php中设置
            $tlModel = new Timeline();			
			if ($result) {
				if ($data['cid'] == 0) {
					$acModel = new Archive();
					$acresult = $acModel->where(['id' => $data['aid']])->find();
					$description = '对文章<strong>'.$acresult['title'].'</strong>留言:';
					$content = $data['content'];
					$action = 3;//文章留言
					$active = 0;
					$ruid = 0;
 				}else{
 					if ($data['tid'] == 0) {//第二层回复
 						$rleave = $this->cModel->where(['id' => $data['cid']])->find();
 						$tdata = [
 							'uid' => $rleave['uid'],
 							'ruid'	=>	$data['uid'],
 							'link'	=>	$url_path,
 							'description'	=>	'对你的评论<strong>'.$rleave['content'].'</strong>回复:',
 							'content'	=>	$data['content'],
 							'icon'	=>	'fa fa-reply bg-yellow',
 							'action'	=>	10,//文章一级回复消息,别人对自己的回复
 							'active'	=>	1
 						];
 						$ttlModel = new Timeline();
 						$ttlModel->save($tdata);
 						//$data['ruid'] = $rleave['uid'];
						$description = '对评论<strong>'.$rleave['content'].'</strong>回复:';
						$content = $data['content'];
						$action = 4;//文章一级回复消息
						$ruid = session('oId');
					}else{
						$third = $this->cModel->where(['id' => $data['tid']])->find();
						$thirddata = [
							'uid' => $third['uid'],
 							'ruid'	=>	$data['uid'],
 							'link'	=>	$url_path,
 							'description'	=>	'对你的评论<strong>'.$third['content'].'</strong>回复:',
 							'content'	=>	$data['content'],
 							'icon'	=>	'fa fa-reply bg-yellow',
 							'action'	=>	10,//二级别人对自己的回复,记录到时间轴
 							'active'	=>	1,
						];
						$tModel = new Timeline();
						$tModel->save($thirddata);
						//$data['ruid'] = $third['uid'];
						$description = '对评论<strong>'.$third['content'].'</strong>回复:';
						$content = $data['content'];
						$action = 9;//对文章二级留言
						$active = 0;
					}	
				}
                $tldata = [
                	'uid'	=>	$data['uid'],
                	'ruid'	=>	$ruid,
                	'aid'	=>	$data['aid'],
                	'link'	=>	$url_path,
                	'description'	=>	$description,
                	'icon'	=>	'fa fa-comments bg-green',
                	'action' => $action,
                	'content' => $content,
                	'active'	=>	$active
                ];
                $tlModel->save($tldata);
                $url_path = $_SERVER['HTTP_REFERER'];
                return ajaxReturn('评论成功！', url('@deta/'.$dirs.'/'.$data['aid']));
			}else{
				return ajaxReturn($this->cModel->getError());
			}
		}
	}
}