<?php
namespace app\admin\controller;

use app\common\controller\SysAction;

class Config extends SysAction
{
    protected $modelClass = '\app\common\model\Config';
    
    public function initialize()
    {
        parent::initialize();
    }
    
    public function index()
    {
        $where = [];
        if (input('get.search')){
            $where[] = ['k|v|desc|type|texttype', 'like', '%'.input('get.search').'%'];
        }
        if (input('get._sort')){
            $order = explode(',', input('get._sort'));
            $order = $order[0].' '.$order[1];
        }else{
            $order = 'type asc,status desc,sorts asc,id asc';
        }
        $dataList = $this->cModel->where($where)->order($order)->paginate('', false, page_param());
        $this->assign('dataList', $dataList);
        return $this->fetch();
    }
    
    /**
     * @Title: save
     * @Description: todo(循环保存字段)
     * @author 苏晓信
     * @date 2018年1月22日
     * @throws
     */
    public function save()
    {
        if (request()->isPost()){
            $data = input('post.');
            $type = $data['type'];   //取出类型
            unset($data['type']);
            if ( $type == 'up' ){
                cache('DB_UP_CONFIG', NULL);
            }
            if(!empty($type)){
                if(is_array($data) && !empty($data)){
                    foreach ($data as $k=>$val) {
                        if (is_array($val)){
                            $val = implode(',', del_arr_empty($val));
                        }
                        $where = ['type' => $type, 'k' => $k];
                        $this->cModel->where($where)->update(['v' => $val]);
                    }
                    return ajax_return(lang('action_success'), url('Config/'.$type));
                }else{
                    return ajax_return($this->cModel->getError());
                }
            }else{
                return ajax_return($this->cModel->getError());
            }
        }
    }
    
    /**
     * @Title: web
     * @Description: todo(站点配置)
     * @return mixed
     * @author 苏晓信
     * @date 2018年1月22日
     * @throws
     */
    public function web()
    {
        $type = ACTION_NAME;
        $where = ['type' => $type, 'status'=>1];
        $data = $this->cModel->where($where)->order('sorts ASC,id ASC')->select();
        foreach ($data as $key => $val){
            $val[$val->k] = $val->v;
        }
        $this->assign('data', $data);
        $this->assign('type', $type);
        return $this->fetch('save');
    }
    
    /**
     * @Title: up
     * @Description: todo(上传配置)
     * @return mixed
     * @author 苏晓信
     * @date 2018年1月22日
     * @throws
     */
    public function up()
    {
        $type = ACTION_NAME;
        $where = ['type' => $type, 'status'=>1];
        $data = $this->cModel->where($where)->order('sorts ASC,id ASC')->select();
        foreach ($data as $key => $val){
            $val[$val->k] = $val->v;
        }
        $this->assign('data', $data);
        $this->assign('type', $type);
        return $this->fetch('save');
    }
}