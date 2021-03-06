<?php
namespace app\index\controller;

use think\Db;
use think\Controller;
use app\index\model\UserPaper;
use app\index\model\UserArctype;
use app\index\model\UserArchive;

/**
* @todo 文章Paper列表
* @time(2018-3-5)
*/
class Paper extends Common
{
	private $cModel;
	
	public function _initialize()
	{
		parent::_initialize();
		$this->cModel = new UserArchive();
        $uid = session('webuserId');
	}

	public function index(){
		$where = ['uid' => $uid];
		if (input('get.search')) {
			$where['title|keywords|description'] = ['like', '%'.input('get.search').'%'];//模糊查询
		}
		if (input('get._sort')) {
			$order = explode(',', input('get._sort'));
			$order = $order[0].' '.$order[1];
		}else{
			$order = 'id desc';//默认排列
		}
		$dataList = $this->cModel->where($where)->order($order)->paginate('',false, page_param());
		foreach ($dataList as $k => $v) {
			if (!empty($v['flag'])) {
				$dataList[$K]['flag'] = explode(',', $v['flag']);
			}
			$v->Arctype;
			if (in_array('j', $dataList['flag']) && !empty($v['jumplink'])) {
				$dataList[$k]['arcurl'] = $v['jumplink'];
			}else{
				if (isset($v->Arctype->dirs)) {
					$dataList[$k]['arcurl'] = url('detail/'.$v->Arctype->dirs.'/'.$v['id']);
				}else{
					$dataList[$k]['arcurl'] = '';
				}
			}
		}

		$this->assign('dataList', $dataList);
		return $this->fetch();
	}
/**
 * @todo  create
 * @time(2018-2-19)
 */
    public function create($typeid)
    {
        if (request()->isPost()){
          Db::startTrans();
            //事务在并发操作多张表的时候，为保证用户数据的完整性，一般批量操作时,事务有问题，暂时不用
           try{
                $data = input('post.');
                $data['create_time'] = strtotime($data['create_time']);
                if (isset($data['flag']) || isset($data['litpic'])){
                    $data['flag'] = $this->_flag($data['flag'], $data['litpic']);
                }
                $result = $this->cModel->validate(CONTROLLER_NAME.'.add')->allowField(true)->save($data);
                $data['aid'] = $this->cModel->getLastInsID();
                $mod =  "\app\admin\model\\".ucfirst($data['mod']);
                $And = new $mod;
                $addonData = $And->allowField(true)->save($data);   //新增关联表数据,这里有问题
                // 提交事务
              if ($result){
                    Db::commit();
                   return ajaxReturn(lang('action_success'), url('Arctype/index'));
 //                   return redirect('Arctype/index');
              }else{
                  return ajaxReturn($this->cModel->getError());
              }
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                return ajaxReturn($e->getMessage());
            }
            return ajaxReturn(lang('action_success'), url('Arctype/index'));
        }else{
            $atModel = new Arctype();
            $arctypeList = $atModel->treeList();
            $this->assign('arctypeList', $arctypeList);
            
            $arcData = $atModel->where(['id' => $typeid])->find();   //栏目数据
            $atmModel = new ArctypeMod();
            $where = [ 'id' => $arcData['mid'] ];
            $mod = $atmModel->where($where)->field('mod')->find();
            $mod = $mod['mod'];
            $this->assign('mods', $mod);   //文章拓展表模型
            
            $data['typeid'] = $arcData['id'];
            $data['create_time'] = date('Y-m-d H:i:s', time());
            $data['mid'] = $arcData['mid'];
            $this->assign('data', $data);
            
            return $this->fetch('edit');
        }
    }
    public function edit($id)
    {
        if (request()->isPost()){
            $data = input('post.');
            if (isset($data['create_time'])){
                $data['create_time'] = strtotime($data['create_time']);
            }
            if (isset($data['flag']) || isset($data['litpic'])){
                $data['flag'] = $this->_flag($data['flag'], $data['litpic']);
            }
            if (count($data) == 2){
                foreach ($data as $k =>$v){
                    $fv = $k!='id' ? $k : '';
                }
                $result = $this->cModel->validate(CONTROLLER_NAME.'.'.$fv)->allowField(true)->save($data, $data['id']);
            }else{
                $result = $this->cModel->validate(CONTROLLER_NAME.'.edit')->allowField(true)->save($data, $data['id']);
                $mod = $data['mod'];
                $addonData = db($mod)->field('id', true)->strict(false)->where( 'aid='.$data['id'] )->update($data);   //关联表数据
            }
            if ($result){
                return ajaxReturn(lang('action_success'), url('index'));
            }else{
                return ajaxReturn($this->cModel->getError());
            }
        }else{
            $atModel = new Arctype();
            $arctypeList = $atModel->treeList();
            $this->assign('arctypeList', $arctypeList);
            
            $data = $this->cModel->get($id);
            $addonMod = $data['mod'];
            $data['addondata'] = $data->$addonMod;   //拓展表数据
            unset($data[$data['mod']]);
            
            $atmModel = new ArctypeMod();
            $data['mid'] = $atmModel->where(['mod' => $addonMod])->value('id');
            
            $this->assign('mods', $addonMod);
            
            if (!empty($data['flag'])){
                $data['flag'] = explode(',', $data['flag']);
            }
            $this->assign('data', $data);
            return $this->fetch();
        }
    }
/**
 * @todo  删除
 * @time(2018-2-22)
 */
    public function delete(){
        if (request()->isPost()) {
            $id = input('id');
            if (isset($id) && !empty($id)) {
                $id_arr = explode(',', $id);
                if (!empty($id_arr)) {
                    foreach ($id_arr as $val) {
                        $addonMod = $this->cModel->where(['id' => $val])->value('mod');
                        $this->cModel->where('id='.$val)->delete();
                        db($addonMod)->where('aid='.$val)->delete();
                    }
                    return ajaxReturn(lang('action_success'), url('index'));
                }else{
                    return ajaxReturn(lang('action_fail'));
                }
            }
        }
    }
/**
 * [_flag 增加标签]
 * @param  [array] $flag   
 * @param  [type] $litpic 
 * @return [array]         
 */
	private function _flag($flag, $litpic)
    {
        if(empty($flag)){ $flag=array(); }
        if($litpic != ''){
            array_push($flag, "p");//array_push() 函数向第一个参数的数组尾部添加一个或多个元素（入栈），然后返回新数组的长度.如果用 array_push() 来给数组增加一个单元，还不如用 $array[] =，因为这样没有调用函数的额外负担。
        }else{
            $flag = unset_array("p", $flag);
        }
        $flag_arr = array_unique($flag);
        $result = implode(',', $flag_arr );
        return $result;
    }

}