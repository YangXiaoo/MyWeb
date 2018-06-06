<?php
namespace app\admin\controller;

use app\common\controller\SysAction;
use app\common\model\Arctype;
use think\Db;

class Archive extends SysAction
{
    protected $modelClass = '\app\common\model\Archive';
    
    public function initialize()
    {
        parent::initialize();
    }
    
    public function index()
    {
        $where = [];
        if (input('get.search')){
            $where[] = ['title', 'like', '%'.input('get.search').'%'];
        }
        if (input('get._sort')){
            $order = explode(',', input('get._sort'));
            $order = $order[0].' '.$order[1];
        }else{
            $order = 'id desc';
        }
        $dataList = $this->cModel->where($where)->order($order)->paginate('', false, page_param());
        foreach ($dataList as $k => $v){
            if(!empty($v['flag'])){ $dataList[$k]['flag'] = explode(',', $v['flag']); }
            if(in_array('j', $dataList[$k]['flag']) && !empty($v['jumplink'])){
                $dataList[$k]['arcurl'] = $v['jumplink'];
            }else{
                $v->arctype;
                if(isset($v->arctype->dirs)){
                    $dataList[$k]['arcurl'] = url('detail/'.$v->arctype->dirs.'/'.$v['id']);
                }else{
                    $dataList[$k]['arcurl'] = '';
                }
            }
        }
        $this->assign('dataList', $dataList);
        return $this->fetch();
    }
    
    public function create($typeid)
    {
        if (request()->isPost()){
            Db::startTrans();
            try{
                $data = input('post.');
                $data['flag'] = $this->_flag($data['flag'], $data['litpic']);
                $result = $this->validate($data, 'app\common\validate\\'.CONTROLLER_NAME.'.add');
                if(true !== $result){
                    return ajax_return($result);
                }
                $result = $this->cModel->allowField(true)->save($data);     //主表数据
                $AddonModel = "\app\common\model\\".ucfirst($data['mod']);
                $AddonModel = new $AddonModel;
                $data['aid'] = $this->cModel->getLastInsID();
                $addonData = $AddonModel->allowField(true)->save($data);    //拓展表数据
                // 提交事务
                if ($result && $addonData){
                    Db::commit();
                    return ajax_return(lang('action_success'), url('Arctype/index'));
                }else{
                    return ajax_return($this->cModel->getError());
                }
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                return ajax_return($e->getMessage());
            }
        }else{
            $arctypeModel = new Arctype();
            $arcData = $arctypeModel->where(['id' => $typeid])->find();  //栏目数据
            $data = [
                'typeid' => $arcData['id'],
                'mod' => $arcData->arctypeMod->mod,
            ];
            $this->assign('data', $data);
            return $this->fetch('edit');
        }
    }
    
    public function edit($id)
    {
        if (request()->isPost()){
            $data = input('post.');
            if (isset($data['flag']) || isset($data['litpic'])){
                $data['flag'] = $this->_flag($data['flag'], $data['litpic']);
            }
            if (count($data) == 2){
                foreach ($data as $k =>$v){
                    $fv = $k!='id' ? $k : '';
                }
                $result = $this->validate($data, 'app\common\validate\\'.CONTROLLER_NAME.'.'.$fv);
                if(true !== $result){
                    return ajax_return($result);
                }
                $result = $this->cModel->allowField(true)->save($data, $data['id']);
                $addonData = true;
            }else{
                $result = $this->validate($data, 'app\common\validate\\'.CONTROLLER_NAME.'.edit');
                if(true !== $result){
                    return ajax_return($result);
                }
                $result = $this->cModel->allowField(true)->save($data, $data['id']);        //主表数据
                $AddonModel = "\app\common\model\\".ucfirst($data['mod']);
                $AddonModel = new $AddonModel;
                $aid = $data['id'];
                unset($data['id']);
                $addonData = $AddonModel->allowField(true)->save($data, ['aid' => $aid]);   //拓展表数据
            }
            if ($result && $addonData){
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
    
    public function delete()
    {
        if (request()->isPost()){
            $id = input('id');
            if (isset($id) && !empty($id)){
                $id_arr = explode(',', $id);
                if (!empty($id_arr)){
                    foreach ($id_arr as $val){
                        $addonMod = $this->cModel->where(['id' => $val])->value('mod');
                        $this->cModel->where('id='.$val)->delete();
                        db($addonMod)->where('aid='.$val)->delete();   //关联表数据
                    }
                    return ajax_return(lang('action_success'), url('index'));
                }else{
                    return ajax_return(lang('action_fail'));
                }
            }
        }
    }
    
    private function _flag($flag, $litpic)
    {
        if(empty($flag)){ $flag = []; }
        if($litpic != ''){
            array_push($flag, "p");
        }else{
            $flag = unset_array("p", $flag);
        }
        $flag_arr = array_unique(del_arr_empty($flag));
        $result = implode(',', $flag_arr );
        return $result;
    }
}