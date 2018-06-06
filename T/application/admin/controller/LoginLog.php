<?php
namespace app\admin\controller;

use app\common\controller\SysAction;

class LoginLog extends SysAction
{
    protected $modelClass = '\app\common\model\LoginLog';
    
    public function initialize()
    {
        parent::initialize();
    }
    
    public function index()
    {
        $where = [];
        if (input('get.search')){
            $where[] = ['ip', 'like', '%'.input('get.search').'%'];
        }
        if (input('get._sort')){
            $order = explode(',', input('get._sort'));
            $order = $order[0].' '.$order[1];
        }else{
            $order = 'id desc';
        }
        $dataList = $this->cModel->where($where)->order($order)->paginate('', false, page_param());
        $this->assign('dataList', $dataList);
        return $this->fetch();
    }
}