<?php
namespace app\common\controller;

use app\common\controller\Syscommon;

class SysAction extends Syscommon
{
    protected $cModel;                  //当前控制器关联模型
    protected $modelClass = false;      //数据模型命名空间
    
    public function initialize()
    {
        parent::initialize();
        if (class_exists($this->modelClass)){
            $this->cModel = new $this->modelClass;
        }
    }
    
    /**
     * @Title: index
     * @Description: todo(基础单表列表显示)
     * @return mixed
     * @author 苏晓信
     * @date 2018年1月15日
     * @throws
     */
    public function index()
    {
        $dataList = $this->cModel->select();
        $this->assign('dataList', $dataList);
        return $this->fetch();
    }
    
    /**
     * @Title: create
     * @Description: todo(基础单表新增)
     * @return mixed
     * @author 苏晓信
     * @date 2018年1月15日
     * @throws
     */
    public function create()
    {
        if (request()->isPost()){
            $data = input('post.');
            $result = $this->validate($data, 'app\common\validate\\'.CONTROLLER_NAME.'.add');
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
            return $this->fetch('edit');
        }
    }
    
    /**
     * @Title: edit
     * @Description: todo(基础单表编辑)
     * @param int $id
     * @return mixed
     * @author 苏晓信
     * @date 2018年1月15日
     * @throws
     */
    public function edit($id)
    {
        if (request()->isPost()){
            $data = input('post.');
            if (count($data) == 2){
                foreach ($data as $k =>$v){
                    $fv = $k!='id' ? $k : '';
                }
                $result = $this->validate($data, 'app\common\validate\\'.CONTROLLER_NAME.'.'.$fv);
                if(true !== $result){
                    return ajax_return($result);
                }
                $result = $this->cModel->allowField(true)->save($data, $data['id']);
            }else{
                $result = $this->validate($data, 'app\common\validate\\'.CONTROLLER_NAME.'.edit');
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
            return $this->fetch();
        }
    }
    
    /**
     * @Title: delete
     * @Description: todo(基础单表删除)
     * @author 苏晓信
     * @date 2018年1月15日
     * @throws
     */
    public function delete()
    {
        if (request()->isPost()){
            $id = input('id');
            if (isset($id) && !empty($id)){
                $id_arr = explode(',', $id);
                $where[] = ['id', 'in', $id_arr];
                $result = $this->cModel->where($where)->delete();
                if ($result){
                    return ajax_return(lang('action_success'), url('index'));
                }else{
                    return ajax_return($this->cModel->getError());
                }
            }
        }
    }
}