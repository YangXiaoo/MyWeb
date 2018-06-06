<?php
namespace app\admin\controller;

use app\common\controller\SysAction;
use app\common\model\AuthGroupAccess;
use app\common\model\User;

class AuthGroup extends SysAction
{
    protected $modelClass = '\app\common\model\AuthGroup';
    protected $beforeActionList = [
        'noAction'  =>  ['only' => 'delete'],
    ];
    
    public function initialize()
    {
        parent::initialize();
    }
    
    public function index()
    {
        $where = [];
        if (input('get.search')){
            $where[] = ['title|notation', 'like', '%'.input('get.search').'%'];
        }
        if (input('get._sort')){
            $order = explode(',', input('get._sort'));
            $order = $order[0].' '.$order[1];
        }else{
            $order = 'module asc,level desc,id asc';
        }
        $dataList = $this->cModel->where($where)->order($order)->paginate('', false, page_param());
        $this->assign('dataList', $dataList);
        return $this->fetch();
    }
    
    public function delete()
    {
        if (request()->isPost()){
            $id = input('id');
            if (isset($id) && !empty($id)){
                $id_arr = explode(',', $id);
                $where[] = ['id', 'in', $id_arr];
                $result = $this->cModel->where($where)->delete();
                
                $wherea[] = ['group_id', 'in', $id_arr];
                $agaModel = new AuthGroupAccess();
                $agaModel->where($wherea)->delete();
                if ($result){
                    return ajax_return(lang('action_success'), url('index'));
                }else{
                    return ajax_return($this->cModel->getError());
                }
            }
        }
    }
    
    public function authUser($id)
    {
        $agaModel = new AuthGroupAccess();
        if (request()->isPost()){
            $actions = input('post.actions');
            $uid = input('post.uid');
            $group_id = input('post.id');
            if ($actions == 'add'){
                $data = $agaModel->where(['uid' => $uid, 'group_id' => $group_id])->find();
                if (!empty($data)){
                    $result = 1;
                }else{
                    $result = $agaModel->save(['uid' => $uid, 'group_id' => $group_id]);
                }
            }elseif ($actions == 'del'){
                $data = $agaModel->where(['uid' => $uid, 'group_id' => $group_id])->find();
                if (!empty($data)){
                    $result = $agaModel->where(['uid' => $uid, 'group_id' => $group_id])->delete();
                }else{
                    $result = 1;
                }
            }
            if ($result){
                return ajax_return(lang('action_success'), url('index'));
            }else{
                return ajax_return($this->cModel->getError());
            }
        }else{
            $data = $this->cModel->get($id);
            $userList = $agaModel->where('group_id', $id)->paginate('100', false, page_param());
            
            $this->assign('data', $data);
            $this->assign('userList', $userList);
            return $this->fetch();
        }
    }
    
    public function checkUser()
    {
        $group_id = input('post.group_id');
        $id = input('post.id');
        $userModel = new User();
        $where[] = ['username', 'in', $id];
        $whereor[] = ['id', 'in', $id];
        $user = $userModel->where($where)->whereOr($whereor)->select();      //查询匹配用户
        $authGroupAccessModel = new AuthGroupAccess();
        foreach ($user as $k => $val){
            $group = $authGroupAccessModel->where([
                ['group_id', '=', $group_id],
                ['uid', '=', $val->id]
            ])->find();
            if (!empty($group)){
                unset($user[$k]);
            }else{
                $val->userInfo;
            }
        }
        return $user;
    }
    
    protected function noAction()
    {
        if (request()->isPost()){
            $user_group = config('app.AUTH_CONFIG.AUTH_USER_GROUP');
            $id = input('param.id');
            $id_arr = explode(',', $id);
            foreach ($user_group as $k => $v){
                if(in_array($v, $id_arr)){
                    return ajax_return(lang('not_edit'));
                    exit();
                }
            }
        }
    }
}