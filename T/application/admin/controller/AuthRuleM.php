<?php
namespace app\admin\controller;

use app\common\controller\SysAction;

class AuthRuleM extends SysAction
{
    protected $modelClass = '\app\common\model\AuthRule';
    private $module = 'member';
    protected $beforeActionList = [
        'noAction'  =>  ['only' => 'delete'],
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
        return $this->fetch('auth_rule/index');
    }
    
    public function create()
    {
        if (request()->isPost()){
            $data = input('post.');
            $result = $this->validate($data, 'app\common\validate\AuthRule.add');
            if(true !== $result){
                return ajax_return($result);
            }
            $result = $this->cModel->allowField(true)->save($data);
            if ($result){
                return ajax_return(lang('action_success'), url('index'));
            }else{
                return ajax_return($this->cModel->getError());
            }
        }else{
            return $this->fetch('auth_rule/edit');
        }
    }
    
    public function edit($id)
    {
        if (request()->isPost()){
            $data = input('post.');
            if (count($data) == 2){
                foreach ($data as $k =>$v){
                    $fv = $k!='id' ? $k : '';
                }
                $result = $this->validate($data, 'app\common\validate\AuthRule.'.$fv);
                if(true !== $result){
                    return ajax_return($result);
                }
                $result = $this->cModel->allowField(true)->save($data, $data['id']);
            }else{
                $result = $this->validate($data, 'app\common\validate\AuthRule.edit');
                if(true !== $result){
                    return ajax_return($result);
                }
                $result = $this->cModel->allowField(true)->save($data, $data['id']);
            }
            if ($result){
                return ajax_return(lang('action_success'), url('index'));
            }else{
                return ajax_return($this->cModel->getError());
            }
        }else{
            $data = $this->cModel->get($id);
            $this->assign('data', $data);
            return $this->fetch('auth_rule/edit');
        }
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