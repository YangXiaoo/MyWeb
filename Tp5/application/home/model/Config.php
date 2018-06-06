<?php
namespace app\home\model;

use think\Model;

class Config extends Model
{
    /**
     * @Title: confv
     * @Description: todo(获取配置值)
     * @param string $k
     * @param string $type
     * @return string
     * @author 苏晓信
     * @date 2017年8月26日
     * 方法覆盖，同名，同参数
     * @throws
     */
    public function confv($k, $type){
        $where = [
            'k' => $k,
            'type' => $type
        ];
        $result = $this->where($where)->value('v');//得到配置值键名为v的值
        return htmlspecialchars_decode($result);
    }
}