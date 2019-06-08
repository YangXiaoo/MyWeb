<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\model\Arctype as Arctypes;
use app\admin\model\ArctypeMod;

class Arctype extends Common{

	private $cModel;
	public function _initialize(){
		parent::_initialize();
		$this->cModel = new Arctypes;//Arctypes()也可以
	}

	public function index(){
		$dataList = $this->cModel->treeList();
		foreach ($dataList as $k => $v) {
			if (!empty($v['jumplink'])) {
				$dataList[$k]['typelink'] = $v['jumplink'];
			}else{
				$dataList[$k]['typelink'] = url('@category/'.$v['dirs']);
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
		if (request()->isPost()) {
			$data = input('post.');
			if (count($data) == 2) {
				foreach ($data as $k => $v) {
					$fv = $k!='id' ? $k : '';//循环得到当前编辑项目id
				}
				$result = $this->cModel->save($data,$data['id']);//where($data['id'])->save($data);--validate(CONTROLLER_NAME.'.'.$fv)->allowField(true)
			}else{
		//2. edit页面编辑
				$result = $this->cModel->save($data,$data['id']);//validate(CONTROLLER_NAME.'.'.edit)->allowField(true)->
			}
			cache('DB_TREE_ARETYPE', null);
			if ($result) {
				return ajaxReturn(lang('action_success'), url('index'));
			}else{
				return ajaxReturn($this->cModel_>getError());
			}
		}else{
			$arctypeList = $this->cModel->treeList();
			$this->assign('arctypeList', $arctypeList);//下拉列表

			$amModel = new ArctypeMod();
			$modList = $amModel->where(['status' => 1])->order('sorts ASC,id ASC')->select();
			$this->assign('modList', $modList);

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
			cache(DB_TREE_ARETYPE,null);
			if ($result) {
				return ajaxReturn(lang('action_success'),url('index'));
			}else{
				return ajaxReturn($this->cModel->getError());
			}
		}else{
			$arctypeList = $this->cModel->treeList();
			$this->assign('arctypeList',$arctypeList);
			$amModel = new ArctypeMod();
			$modList = $amModel->where(['status' => 1])->order('sorts ASC,id ASC')->select();
			$this->assign('modList', $modList);
			return $this->fetch('edit');			
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