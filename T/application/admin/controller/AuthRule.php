<?php
namespace app\admin\controller;

use app\common\controller\SysAction;

class AuthRule extends SysAction
{
    protected $modelClass = '\app\common\model\AuthRule';
    private $module = 'admin';
    protected $beforeActionList = [
        'noAction' => ['only' => 'delete'],
        'cleanCache' => ['only' => 'create,edit,delete'],
    ];
    
    public function initialize()
    {
        parent::initialize();
        $this->assign('module', $this->module);
    }
    
    public function index()
    {
        $dataList = $this->cModel->treeList($this->module);
        $this->assign('dataList', $dataList);
        return $this->fetch();
    }
    
    protected function noAction()
    {
        if (request()->isPost()){
            $id = input('param.id');
            $id_arr = explode(',', $id);
            if(in_array(1, $id_arr)){
                return ajax_return(lang('not_edit'));
                exit();
            }
        }
    }
    
    protected function cleanCache()
    {
        cache('DB_TREE_AUTHRULE__', null);
        cache('DB_TREE_AUTHRULE_admin_', null);
        cache('DB_TREE_AUTHRULE_admin_1', null);
        cache('DB_TREE_AUTHRULE_admin_0', null);
        cache('DB_TREE_AUTHRULE_member_', null);
        cache('DB_TREE_AUTHRULE_member_1', null);
        cache('DB_TREE_AUTHRULE_member_0', null);
        cache('DB_TREE_AUTHRULE__0', null);
        cache('DB_TREE_AUTHRULE__1', null);
    }
}