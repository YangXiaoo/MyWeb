<?php
namespace app\index\controller;

use think\Controller;
use app\index\model\UserArctype;

class Arctype extends Common{

	private $cModel;
	public function _initialize(){
		parent::_initialize();
		$this->cModel = new UserArctype();
	}

	public function index(){
		$userid = session('webuserId');
		$dataList = $this->cModel->where(['uid' => $userid])->select();
		foreach ($dataList as $k => $v) {
			if (!empty($v['jumplink'])) {
				$dataList[$k]['typelink'] = $v['jumplink'];
			}else{
				$dataList[$k]['typelink'] = url('UserCategory/'.$v['dirs'].'/'.$v['id']);
			}
		}
		$this->assign('dataList', $dataList);
		return $this->fetch();
	}
/**
 * @todo 编辑
 * @param   $id 传入文章类型id
 * @time(2018-2-17)
 */
	public function edit($id){
		//1.index页面编辑
		$userid = session('webuserId');
		if (request()->isPost()) {
			$data = input('post.');
			if (count($data) == 2) {
				$result = $this->cModel->allowField(true)->save($data,$data['id']);
			}else{
				$userid = session('webuserId');
				$data['uid'] = $userid;
				$result = $this->cModel->allowField(true)->save($data,$data['id']);
			}
			
			if ($result) {
				return ajaxReturn(lang('action_success'), url('index'));
			}else{
				return ajaxReturn($this->cModel_>getError());
			}
		}else{
			$data = $this->cModel->get($id);
			$this->assign('data', $data);
			return $this->fetch();
		}
	}
/**
 * @todo  创建新的文章类型
 * @time(2018-2-18)
 */
	public function create(){
		if (request()->isPost()) {
			$data = input('post.');
			$result = $this->cModel->save($data);
			if ($result) {
				return ajaxReturn(lang('action_success'),url('index'));
			}else{
				return ajaxReturn($this->cModel->getError());
			}
		}else{	
			$uid = session('webuserId');
			$this->assign('uid', $uid);
			return $this->fetch();			
		}
	}
/**
 * @todo  删除文档
 * @time(20118-2-18)
 */
	public function delete(){
		if (request()->isPost()) {
			$id = input('id');
			if (!empty($id)) {
				$id_arr = explode(',', $id);//多选情况
				$where = ['id' => ['in', $id_arr]];
				$result = $this->cModel->where($where)->delete();
				cache('DB_TREE_ARETYPE', null);
				if ($result) {
					return ajaxReturn(lang('action_success'), url('index'));
				}else{
					return ajaxReturn($this->cModel->getError());
				}
			}
		}
	}
}