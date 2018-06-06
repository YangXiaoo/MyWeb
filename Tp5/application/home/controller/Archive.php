<?php
namespace app\index\controller;

use think\Db;
use think\Controller;
use app\index\model\UserPaper;
use app\index\model\UserArctype;
use app\index\model\UserArchive;
use app\index\model\Addonarticle;

/**
* @todo 文章Paper列表
* @time(2018-3-5)
*/
class Archive extends Common
{
	private $cModel;
	
	public function _initialize()
	{
		parent::_initialize();
		$this->cModel = new UserArchive();
        
	}

	public function index(){
        $uid = session('webuserId');
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
		$dataList = $this->cModel->where($where)->order($order)->paginate(10, false, page_param());
		foreach ($dataList as $k => $v) {
			if (!empty($v['flag'])) {
				$dataList[$K]['flag'] = explode(',', $v['flag']);
			}
			$v->Arctype;
			if (in_array('j', $dataList['flag']) && !empty($v['jumplink'])) {
				$dataList[$k]['arcurl'] = $v['jumplink'];
			}else{
				if (isset($v->Arctype->dirs)) {
					$dataList[$k]['arcurl'] = url('userdetail/'.$v->Arctype->dirs.'/'.$v['id']);
				}else{
					$dataList[$k]['arcurl'] = '';
				}
			}
                if (isset($v->Arctype->typename)) {
                    $dataList[$k]['typename'] = $typename;
                }else{
            
                $dataList[$k]['typename'] = '';
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
        $uid = session('webuserId');
        if (request()->isPost()){
          Db::startTrans();
            //事务在并发操作多张表的时候，为保证用户数据的完整性，一般批量操作时,事务有问题，暂时不用
           try{
                $data = input('post.');
                $data['uid'] = session('webuserId');
                $data['create_time'] = strtotime($data['create_time']);
                if (isset($data['flag']) || isset($data['litpic'])){
                    $data['flag'] = $this->_flag($data['flag'], $data['litpic']);
                }
                $result = $this->cModel->allowField(true)->save($data);
                $data['aid'] = $this->cModel->getLastInsID();
                unset($data['id']);
                $paper = new UserPaper();
                $paper->allowField(true)->save($data);

                // 提交事务
              if ($result){
                    Db::commit();
                   return ajaxReturn(lang('action_success'), url('Arctype/index'));
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
            $atModel = new UserArctype();
            $arctypeList = $atModel->where(['uid' => $uid])->select();
            $this->assign('arctypeList', $arctypeList);
            $arcData = $atModel->where(['id' => $typeid])->find();   //栏目数据 
            $data['typeid'] = $arcData['id'];
            $data['create_time'] = date('Y-m-d H:i:s', time());
            $data['action'] = 'create';
            $this->assign('data', $data);          
            return $this->fetch();
        }
    }
    public function edit($id)
    {
        $uid = session('webuserId');
        if (request()->isPost()){
            $data = input('post.');
            if (isset($data['create_time'])){
                $data['create_time'] = strtotime($data['create_time']);
            }
            if (isset($data['flag']) || isset($data['litpic'])){
                $data['flag'] = $this->_flag($data['flag'], $data['litpic']);
            }
            if (count($data) == 2){
                $userid = session('webuserId');
                $data['uid'] = $userid;
                $result = $this->cModel->allowField(true)->save($data, $data['id']);
            }else{
                $userid = session('webuserId');
                $data['uid'] = $userid;
                $da = $data;
                $result = $this->cModel->allowField(true)->save($data, $data['id']);
                $paper = new UserPaper();
                $dataList = $paper->where(['aid' => $da['id']])->find();
                $id = $dataList['id'];
                unset($da['id']);
                $paper->allowField(true)->save($da,['id' => $id]);
                
            }
            if ($result){
                return ajaxReturn(lang('action_success'), url('index'));
            }else{
                return ajaxReturn($this->cModel->getError());
            }
        }else{
            $atModel = new UserArctype();
            $arctypeList = $atModel->where(['uid' => $uid])->select();
            $this->assign('arctypeList', $arctypeList);
            
            $data = $this->cModel->get($id);
            $data['paper'] = $data->Paperinfo;
      
            
            if (!empty($data['flag'])){
                $data['flag'] = explode(',', $data['flag']);
            }
            $data['action'] = 'edit';
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