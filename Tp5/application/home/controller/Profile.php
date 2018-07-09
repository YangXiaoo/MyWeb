<?php
namespace app\index\controller;

use think\Db;
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
use app\index\model\Arctype;
use app\index\model\Archive;

class Profile extends Common{
	private $cModel;
	public function _initialize(){
		parent::_initialize();
		$this->cModel = new UserInfo();
	}
/**
 * @todo 个人信息
 * @time(2018-3-2)
 */
	public function index($uid){
		if (!empty($uid)) {
			$userid = $uid;
		}else{
			$userid = session('webuserId');
		}
		$where = [
			'uid' => $userid,
			'only_view' => 0,
		];
		$result = $this->cModel->where($where)->find();
		if (empty($result)) {
			return $this->fetch('404');
		}else{
			$uModel = new User();
			$user = $uModel->where(['id' => $userid])->find();
			//个人信息	
			session('oId',$userid);
			cookie('oavatar', $result['avatar']);
			cookie('ousername', $user['username']);
			$acModel = new UserArchive();
			$dataList = $acModel->where(['uid' => $userid, 'status'=>1])->order('create_time DESC')->paginate(10, false, page_param());
			if (!empty($dataList)) {
				$cmModel = new ArcComment();
				$tmModel = new ArcThumbsup();
				foreach ($dataList as $k => $v){
            		$dataList[$k]['comment_total'] = $cmModel->where(['aid' => $v['id'], 'status' => 1])->count();
            		$dataList[$k]['thumbs_total'] = $tmModel->where('aid', $v['id'])->count();
            		$dataList[$k]['typename'] =  $v->Arctype->typename;
            		$v->Arctype;
            		$flag_arr = explode(',', $v['flag']);
            		if (in_array('j', $flag_arr) && !empty($v['jumplink'])) {
                		$dataList[$k]['arcurl'] = $v['jumplink'];
                		$dataList[$k]['target'] = 'target="_blank';
            		}else{
            			$dataList[$k]['arcurl'] = url('userdetail/'.$v->Arctype->dirs.'/'.$v['id']);   
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
				}
			}
		if ($uid !== session('webuserId') && !empty($ruid= session('webuserId'))){
			$url_path = URL_PATHS.'/profile/index/'.$uid;
			$uModel = new User();
			$finfo = $uModel->where(['id' => $uid])->find();
			$udescription = '浏览了<a href="'.$url_path.'" target="_blank">'.$finfo['username'].'</a>的主页';
			$fdescription = '浏览了你的主页';
			$icon = 'fa fa-tint bg-orange';
			$uaction = 13;//查看他人主页
			$faction = 14;//主页被人查看
			$this->saveTimeline($ruid, $uid, $udescription, $icon, $uaction, 0);
			$this->saveTimeline($uid, $ruid, $fdescription, $icon, $faction, 1);
		}
		$this->assign('dataList', $dataList);
		return $this->fetch();
		}
	}
/**
 * @todo  关注好友
 * @time(2018-3-4)
 */
	public function follow($id){
		$userid = session('webuserId');
		$data = [
			'uid'	=>	$userid,
			'fid'	=>	$id,
		];
		$fModel = new Follower();
		$fresult = $fModel->where($data)->find();
		if (empty($fresult)) {
			$fModel->save($data);
			//$insertid = $fModel->getLastInsID();
			//$result = $fModel->where(['id' => $insertid])->find();
			$uModel = new User();
			$finfo = $uModel->where(['id' => $id])->find();
			$url_path = URL_PATHS.'/profile/index/'.$id;
			$udescription = '关注<a href="'.$url_path.'" target="_blank><strong>'.$finfo['username'].'</strong></a>成功';
			$uaction = 5;//关注别人
			$faction = 6;//被人关注
			$fdescription = '关注了你';
			$icon = 'fa fa-heart-o bg-red';
			$this->saveTimeline($userid, $id, $udescription, $icon, $uaction, 0);
			$result = $this->saveTimeline($id, $userid, $fdescription, $icon, $faction, 1);
			if ($result){
				return ajaxReturn('关注成功','', 1);
			}else{
				return ajaxReturn($uModel->getError());
			}
		}else{
			return ajaxReturn('你已经关注过了', '', 0);
		}
	}
/**
 * @todo  个人资料
 * @time(2018-3-4)
 */
	public function info(){
		$uid = session('oId');
		$fModel = new Follower();
		$following = $fModel->where(['uid' => $uid])->select();
		$follow['fgnum'] = count($following);
		$follower = $fModel->where(['fid' => $uid])->select();
		$follow['frnum'] = count($follower);
		$result = $this->cModel->where(['uid' => $uid])->find();
		$cid = $this->setUserId();
		$oid = session('oId');
		$this->assign('follow', $follow);
		$this->assign('data',$result);
		$this->assign('cid', $cid);
		$this->assign('oid', $oid);
		return $this->fetch();		
	}
/**
 * @todo 时间轴
 * @time(2018-3-4)
 */
	public function timeline(){
		$userid = session('webuserId');
		$tlModel = new Timeline();
		$tlresult = $tlModel->where(['uid' => $userid])->order('create_time DESC')->paginate(50, false, page_param());//tlresult - timeline result
		foreach ($tlresult as $k => $v) {
			$tlresult[$k]['username'] = $v->tlusername->username;
			$tlresult[$k]['date'] = date('Y-m-d', strtotime($v['create_time']));
			$tlresult[$k]['oname'] = $v->tlothersname->username;//tlothersname-timeline others name
		}
		$cid = $this->setUserId();
		$this->assign('dataList', $tlresult);
		$this->assign('cid', $cid);
		$this->assign('uid', $userid);
		return $this->fetch();
	}
/**
 * @todo  浏览记录
 * @time(2018-3-8)
 */
	public function history(){
		$uid = session('webuserId');
		$acModel = new ArcComment();
		$actModel = new ArcThumbsup();
		$acvModel = new Archive();
		$atModel = new Arctype();
		$tmModel = new Timeline();
		$uacvModel = new UserArchive();
		$uatModel = new UserArctype();
		$where = [
			'uid' => $uid,
			'action' => 1,
		];
		$dataList = $tmModel->where($where)->order('create_time DESC')->paginate(10, false, page_param());
			foreach ($dataList as $k => $v){
				if ($v['aid'] < 1000) {
				$archive = $acvModel->where(['id' => $v['aid']])->find();
				$dataList[$k]['litpic'] = $archive['litpic'];
				$dataList[$k]['title'] = $archive['title'];
				$rat = $atModel->where(['id' => $archive['typeid']])->find();
				$dataList[$k]['typename'] = $rat['typename'];
				$flag_arr = explode(',', $archive['flag']);
				if (in_array('j', $flag_arr) && !empty($archive['jumplink'])) {
					$dataList[$k]['arcurl'] = $v['jumplink'];
                	$dataList[$k]['target'] = 'target="_blank"';
            	}else{
                	$dataList[$k]['arcurl'] = url('detail/'.$rat['dirs'].'/'.$v['aid']); 
                	$dataList[$k]['target'] = 'target="_self"';
            	}
            	if (!empty($archive['litpic']) && file_exists(WEB_PATH.$archive['litpic'])){
                $img_info = getimagesize( WEB_PATH.$archive['litpic'] );
                if ($img_info[0] == $img_info[1]){
                    $dataList[$k]['listStyle'] = 'left';
                }else{
                    $dataList[$k]['listStyle'] = 'top';
                }
            }else{
                $dataList[$k]['listStyle'] = 'top';
            }
            }else{
				$archive = $uacvModel->where(['id' => $v['aid']])->find();
				$dataList[$k]['litpic'] = $archive['litpic'];
				$dataList[$k]['title'] = $archive['title'];
				$rat = $uatModel->where(['id' => $archive['typeid']])->find();
				$dataList[$k]['typename'] = $rat['typename'];
				$flag_arr = explode(',', $archive['flag']);
				if (in_array('j', $flag_arr) && !empty($archive['jumplink'])) {
					$dataList[$k]['arcurl'] = $v['jumplink'];
                	$dataList[$k]['target'] = 'target="_blank"';
            	}else{
                	$dataList[$k]['arcurl'] = url('userdetail/'.$rat['dirs'].'/'.$v['aid']); 
                	$dataList[$k]['target'] = 'target="_self"';
            	}
            	if (!empty($archive['litpic']) && file_exists(WEB_PATH.$archive['litpic'])){
                $img_info = getimagesize( WEB_PATH.$archive['litpic'] );
                if ($img_info[0] == $img_info[1]){
                    $dataList[$k]['listStyle'] = 'left';
                }else{
                    $dataList[$k]['listStyle'] = 'top';
                }
            }else{
                $dataList[$k]['listStyle'] = 'top';
            }
            } 
            	$dataList[$k]['comment_total'] = $acModel->where(['aid' => $v['aid'], 'status' => 1])->count();
            	$dataList[$k]['thumbs_total'] = $actModel->where('aid', $v['aid'])->count();
            }
 		            $data = '浏览历史';
            $this->assign('data', $data);
		$this->assign('dataList', $dataList);
		return $this->fetch();
	}
/**
 * @todo  文章收藏
 * @time(2018-3-8)
 * @redo(2018-3-11)
 */
	public function collect(){
		$uid = session('webuserId');
		$acModel = new ArcComment();
		$actModel = new ArcThumbsup();
		$acvModel =new Archive();
		$atModel = new Arctype();
		$tmModel = new Timeline();
		$uacvModel = new UserArchive();
		$uatModel = new UserArctype();
		$where = [
			'uid' => $uid,
			'action' => 16,
		];
		$dataList = $tmModel->where($where)->order('create_time DESC')->paginate(10, false, page_param());
			foreach ($dataList as $k => $v){
				if ($v['aid'] < 1000) {
				$archive = $acvModel->where(['id' => $v['aid']])->find();
				$dataList[$k]['litpic'] = $archive['litpic'];
				$dataList[$k]['title'] = $archive['title'];
				$rat = $atModel->where(['id' => $archive['typeid']])->find();
				$dataList[$k]['typename'] = $rat['typename'];
				$flag_arr = explode(',', $archive['flag']);
				if (in_array('j', $flag_arr) && !empty($archive['jumplink'])) {
					$dataList[$k]['arcurl'] = $v['jumplink'];
                	$dataList[$k]['target'] = 'target="_blank"';
            	}else{
                	$dataList[$k]['arcurl'] = url('detail/'.$rat['dirs'].'/'.$v['aid']); 
                	$dataList[$k]['target'] = 'target="_self"';
            	}
            	if (!empty($archive['litpic']) && file_exists(WEB_PATH.$archive['litpic'])){
                $img_info = getimagesize( WEB_PATH.$archive['litpic'] );
                if ($img_info[0] == $img_info[1]){
                    $dataList[$k]['listStyle'] = 'left';
                }else{
                    $dataList[$k]['listStyle'] = 'top';
                }
            }else{
                $dataList[$k]['listStyle'] = 'top';
            }
            }else{
				$archive = $uacvModel->where(['id' => $v['aid']])->find();
				$dataList[$k]['litpic'] = $archive['litpic'];
				$dataList[$k]['title'] = $archive['title'];
				$rat = $uatModel->where(['id' => $archive['typeid']])->find();
				$dataList[$k]['typename'] = $rat['typename'];
				$flag_arr = explode(',', $archive['flag']);
				if (in_array('j', $flag_arr) && !empty($archive['jumplink'])) {
					$dataList[$k]['arcurl'] = $v['jumplink'];
                	$dataList[$k]['target'] = 'target="_blank"';
            	}else{
                	$dataList[$k]['arcurl'] = url('userdetail/'.$rat['dirs'].'/'.$v['aid']); 
                	$dataList[$k]['target'] = 'target="_self"';
            	}
            	if (!empty($archive['litpic']) && file_exists(WEB_PATH.$archive['litpic'])){
                $img_info = getimagesize( WEB_PATH.$archive['litpic'] );
                if ($img_info[0] == $img_info[1]){
                    $dataList[$k]['listStyle'] = 'left';
                }else{
                    $dataList[$k]['listStyle'] = 'top';
                }
            }else{
                $dataList[$k]['listStyle'] = 'top';
            }
            } 
            	$dataList[$k]['comment_total'] = $acModel->where(['aid' => $v['aid'], 'status' => 1])->count();
            	$dataList[$k]['thumbs_total'] = $actModel->where('aid', $v['aid'])->count();

            }
            $data = '文章收藏';
            $this->assign('data', $data);
		$this->assign('dataList', $dataList);
		return $this->fetch('history');
	}
/**
 * @todo  好友列表
 * @time(2018-3-10)
 */
	public function friend(){
		$uid = session('webuserId');
		if (empty($uid)) {
			return $this->fetch('login/login');
		}else{
			$frModel = new Follower();
			$where = [
				'uid' => session('webuserId'),
			];
			$dataList = $frModel->where($where)->order('create_time DESC')->paginate(10 ,false, page_param());
				foreach ($dataList as $k => $v) {
				$dataList[$k]['username'] = $v->followinfo->username;
				$dataList[$k]['link'] = url('Profile/index/'.$v['fid']);
			}
			$this->assign('dataList', $dataList);
			return $this->fetch();
			}
	}
/**
 * @todo  个人编辑设置
 * @time(2018-3-4)
 */
	public function edit(){
		$uid = $this->setUserId();
		$data = $this->cModel->where(['uid' => $uid])->find();//$this->cModel->get($uid);
		$this->assign('data', $data);
		return $this->fetch();
	}

/**
 * @todo  保存个人信息
 * @time(2018-3-3)
 */
	public function saveInfo(){
		if (request()->isPost()) {
			$data = input('post.');
			$infoModel = new UserInfo();
			$result = $infoModel->where(['uid' => $data['uid']])->update($data);
			if ($result) {
				return ajaxReturn(lang('editinfo_success'), url('Profile/edit'));
			}else{
				return ajaxReturn($infoModel->getError());
			}
		}
	}
/**
 * @todo  时间轴
 * @time(2018-3-4)
*/
	private function saveTimeline($uid, $ruid, $description, $icon, $action, $active){
		$data = [
			'uid' => $uid,
			'ruid' => $ruid,
			'description'	=> $description,
			'icon'	=>	$icon,
			'action' => $action,
			'active' => $active
		];
		$tlModel = new Timeline();
		$result = $tlModel->save($data);
		if ($result !== false) {
			return true;
		}else{
			return false;
		}
	}
/**
 * @todo  设置当前用户Id
 * @time(2018-3-4)
 */
	private function setUserId(){
		$cid = session('webuserId');
		if (empty($cid)) {
			$cid = 0;
		}
		return $cid;		
	}
}