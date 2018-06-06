<?php
namespace app\admin\controller;

use app\common\controller\SysAction;

class Arctype extends SysAction
{
    protected $modelClass = '\app\common\model\Arctype';
    protected $beforeActionList = [
        'cleanCache' => ['only' => 'create,edit,delete'],
    ];
    
    public function initialize()
    {
        parent::initialize();
        $data = ['target' => '_self'];
        $this->assign('data', $data);
    }
    
    public function index()
    {
        $dataList = $this->cModel->treeList();
        $this->assign('dataList', $dataList);
        return $this->fetch();
    }
    
    protected function cleanCache()
    {
        cache('DB_TREE_ARETYPE', null);
    }
}