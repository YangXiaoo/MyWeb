<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\model\ArctypeMod as ArctypeMods;

class ArctypeMod extends Common{
	private $cModel;

	public function _initialize(){
		parent::_initialize();
		$this->cModel = new ArctypeMods;
	}

	public function index(){
		$where = [];
		if (input('get.search')) {
			$where['name|mod'] = ['like', '%'.input('get.search').'%'];
		}
		if (input('get._sort')) {
			$order = explode(',', input('get._sort'));
			$order = $order[0].' '.$order[1];
		}else{
			$order = 'sorts asc,id asc';
		}
		$dataList = $this->cModel->where($where)->order($order)->paginate('', false, page_param());
		$this->assign('dataList', $dataList);
		return $this->fetch();
	}
	public function create(){
		if (request()->isPost()) {
			$data = input('post.');
			$result = $this->cModel->validate(CONTROLLER_NAME.'.add')->allowField(true)->save($data);
			if ($result) {
				return ajaxReturn(lang('action_success'), url('index'));
			}else{
				return ajaxReturn($this->cModel->getError());
			}
		}else{
			return $this->fetch('edit');
		}
	}
	public function edit($id){
		if (request()->isPost()) {
			$data = input('post.');
			if (in_array($data['id'],['1','2', '3'])) {
				return ajaxReturn(lang('not_edit'));
			}
			if (count($data) == 2) {//行内编辑
				foreach ($data as $k => $v) {
					$fv = $k!='id' ? $k : '';
				}
				$result = $this->cModel->validate(CONTROLLER_NAME.'.'.$fv)->allowField(true)->save($data,$data['id']);
			}else{
				$result = $this->cModel->validate(CONTROLLER_NAME.'.edit')->allowField(true)->save($data,$data['id']);
			}
			if ($result) {
				return ajaxReturn(lang('action_success'), url('index'));

			}else{
				return ajaxReturn($this->cModel->getError());
			}
		}else{
			$data = $this->cModel->get($id);
			$this->assign('data', $data);
			return $this->fetch();
		}
	}
	public function delete($id){
		//1.接收传入id参数；2.数组分裂；3.判断权限；4.删除
		if (request()->isPost()) {
			$id = input('id');
			if (isset($id) && !empty($id)) {
				$id_arr = explode(',', $id);
				if (in_array(1, $id_arr) || in_array(2, $id_arr) || in_array(3, $id_arr)) {
					return ajaxReturn(lang('not_delete'));
				}
				$where = ['id' => ['in', $id_arr]];
				$result = $this->cModel->where($where)->delete();
				if ($result) {
					return ajaxReturn(lang('action_success'), url('index'));
				}else{
					return ajaxReturn($this->cModel->getError());
				}
			}
		}
	}
}