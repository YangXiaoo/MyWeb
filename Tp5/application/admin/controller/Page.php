<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\model\Archive;
use app\admin\model\Arctype;

class Page extends Common{
	private $atModel;
	public function _initialize(){
		parent::_initialize();
		$this->atModel = new Arctype();
	}
/**
 * [index 单页编辑主页]
 * @todo  单页编辑
 * @time(2018-2-27)
 */
	public function index(){
		if (input('get.search')) {
			$where['title|keywords|description'] = ['like', '%'.input('get.search').'%'];
		}
		if (input('get._sort')) {
			$order = explode(',', input('get._sort'));
			$order = $order[0].' '.$order[1];
		}else{
			$order = 'id desc';
		}
		$where = [
			'pid' => 2,
			'status' => 1,
			'is_edit' => 0,
		];
		$dataList = $this->atModel->where($where)->order($order)->paginate('', false, page_param());
		foreach ($dataList as $k => $v) {
			$dataList[$k]['arcurl'] = url('@category/'.$v['dirs']);
		}
		$this->assign('dataList', $dataList);
		return $this->fetch();
	}

	public function edit($id){
		if (request()->isPost()) {
			$data = input('post.');
			if (isset($data['create_time'])) {
				$data['create_time'] = strtotime($data['create_time']);//strtotime(time,now) 函数将任何英文文本的日期或时间描述解析为 Unix 时间戳（自 January 1 1970 00:00:00 GMT 起的秒数）
			}
			if (count($data) == 2) {
				$result = $this->atModel->allowField(true)->save($data,$data['id']);
			}else{
				$result = $this->atModel->allowField(true)->save($data, $data['id']);
			}
			if ($result) {
				return ajaxReturn(lang('action_success'), url('index'));
			}else{
				return ajaxReturn($this->atModel->getError());
			}
		}else{
			$dataList = $this->atModel->get($id);
			$this->assign('data', $dataList);
			return $this->fetch();
		}
	}

		public function create(){
		if (request()->isPost()) {
			Db::startTrans();
			try{
				$data = input('post.');
				$data['pid'] = 2;
				$data['mid'] = 1;
				$data['target'] = '_self';
				if (isset($data['create_time'])) {
					$data['create_time'] = strtotime($data['create_time']);//strtotime(time,now) 函数将任何英文文本的日期或时间描述解析为 Unix 时间戳（自 January 1 1970 00:00:00 GMT 起的秒数）
				}
				$result = $this->atModel->allowField(true)->save($data);
				if ($result) {
					Db::commit();
					return ajaxReturn(lang('action_success'), url('index'));
				}else{
					return ajaxReturn($this->atModel->getError());
				}
			}catch(\Exception $e){
				Db::rollback();
				return ajaxReturn($e->getMessage());
			}
		}else{
			$data['create_time'] = date('Y-m-d H:i:s', time());
			$this->assign('data', $dataList);
			return $this->fetch('edit');
		}
	}
}