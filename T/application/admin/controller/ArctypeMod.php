<?php
namespace app\admin\controller;

use app\common\controller\SysAction;

class ArctypeMod extends SysAction
{
    protected $modelClass = '\app\common\model\ArctypeMod';
    protected $beforeActionList = [
        'noAction'  =>  ['only' => 'edit,delete'],
    ];
    
    public function initialize()
    {
        parent::initialize();
    }
    
    public function index()
    {
        $where = [];
        if (input('get.search')){
            $where[] = ['name|mod', 'like', '%'.input('get.search').'%'];
        }
        if (input('get._sort')){
            $order = explode(',', input('get._sort'));
            $order = $order[0].' '.$order[1];
        }else{
            $order = 'sorts asc,id asc';
        }
        $dataList = $this->cModel->where($where)->order($order)->paginate('', false, page_param());
        $this->assign('dataList', $dataList);
        return $this->fetch();
    }
    
    protected function noAction()
    {
        if (request()->isPost()){
            $id = input('param.id');
            $id_arr = explode(',', $id);
            if(in_array(1, $id_arr) || in_array(2, $id_arr) || in_array(3, $id_arr)){
                return ajax_return(lang('not_edit'));
                exit();
            }
        }
    }
}