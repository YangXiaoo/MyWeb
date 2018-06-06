<?php
namespace app\common\model;
use think\Model;    // 使用前进行声明
use app\common\model\Bill;
use app\common\model\Income;
/**
 * 账单表
 */
class Total extends Model
{
	public function Income(){

		return $this->belongsTo('Income');
	}
}