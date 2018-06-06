<?php
namespace app\home\model;

use think\Model;

class UserArctype extends Model{
/**
 * @todo 个人制定菜单
 * @time(2018-3-5)
 */
	public function usermenu(){
		$uid = session('oId');
		$where = [
			'uid' => $uid,
			'status' => 1
		];
		$dataList = $this->where($where)->order('sorts DESC')->select();
		foreach ($dataList as $k => $v) {
			$dataList[$k]['typelink'] = url('@usercategory/'.$v['dirs'].'/'.$v['id']);
		}
		return $dataList;
	}
}