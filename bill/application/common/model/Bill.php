<?php
namespace app\common\model;
use think\Model;    // 使用前进行声明
use app\common\model\Total;
/**
 * 账单表
 */
class Bill extends Model
{
	public function Total(){

		return $this->belongsTo('Total');
	}
}