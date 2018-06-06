<?php
namespace app\index\controller;
use app\common\model\Personal;  // 模型
use app\common\model\Income;
use think\Request;      // 请求
use think\Controller;
use app\common\model\Total;
/**
 * 账单管理
 *@author yangxiao
 *@time 2018/2/6
 */
class TotalController extends Controller
{
	//主页
     public function index()
    {
        try{
		    $personalId = session('personalId');
			if ($personalId == null){
			    return $this->redirect('login/index');
			}

			//获取查询信息
			$time = Request::instance()->get('time');
			echo $time;

			$pageSize = 10;//show 10 data

			$Total = new Total;           			
			$Totals = $Total->paginate($pageSize,false,[
			    'query'=>[
			           'personal_id' =>$personalId,	
			             ],	
			]);

			//向v层传数据
			$this->assign('totals',$Totals);

			//取回打包后的数据
			$htmls = $this->fetch();
			return $htmls;

		}catch(\think\Exception\HttpResponseException $e){
		    throw $e;
		}catch(\Exception $e){
		    return $e->getMessage();
		}
    }
 /**
 * 插入信息
 *@author yangxiao
 *@time 2/6
 */   
    public function insert(){

    	
    	try{

    		$message = '';

    		//读取数据
    		$postData = Request::instance()->post();

    		//实例化
            $Total = new Total;
    		//为对象赋值
    		$Total->kind = $postData['kind'];
    		$Total->update_time = date("Y-m-d H:i:s");	
    		$Total->function = $postData['function'];
            $Total->personal_id = session('personalId');
             
    		//验证并保存
    		$result = $Total->validate(true)->save($Total->getData());

    		//反馈结果
    		
    			
    		
    	}catch(\Exception $e){

    		return $e->getMessage();
    	}
    	return $this->redirect('index');
    }
 /**
 * 删除
 *@author yangxiao
 *@time 2/6
 */
    public function delete()
    {
        try {
            // 获取get数据
            $Request = Request::instance();
            $id = Request::instance()->param('id/d');
            
            // 判断是否成功接收
            if (is_null($id) || 0 === $id) {
                throw new \Exception('未获取到ID信息', 1);
            }

            // 获取要删除的对象
            $Total = Total::get($id);

            // 要删除的对象存在
            if (is_null($Total)) {
                throw new \Exception('不存在id为' . $id . '的消费，删除失败', 1);
            }

            // 删除对象
            if (!$Total->delete()) {
                return $this->error('删除失败:' . $Total->getError());
            }

        // 获取到ThinkPHP的内置异常时，直接向上抛出，交给ThinkPHP处理
        } catch (\think\Exception\HttpResponseException $e) {
            throw $e;

        // 获取到正常的异常时，输出异常
        } catch (\Exception $e) {
            return $e->getMessage();
        } 

        // 进行跳转
        return $this->redirect('index');
    }
 /**
 * 编辑更新数据
 *@author yangxiao
 *@time 2/6
 */    
    public function update()
    {
       
        try {
            // 接收数据，取要更新的关键字信息
            $id = Request::instance()->post('id/d');

            // 获取当前对象，得到一个对象的数据
            $Total = Total::get($id);

            if (!is_null($Total)) {
                // 写入要更新的数据
                $Total->kind = input('post.kind');
                
                $Total->total = input('post.total');
                

                // 更新
                if (false ===  $Total->validate(true)->save($Total->getData())) {
                    return $this->error('更新失败' . $Total->getError());
                }
            } else {
                throw new \Exception("所更新的记录不存在", 1);   // 调用PHP内置类时，需要在前面加上 \ 
            }

        // 获取到ThinkPHP的内置异常时，直接向上抛出，交给ThinkPHP处理
        } catch (\think\Exception\HttpResponseException $e) {
            throw $e;

        // 获取到正常的异常时，输出异常
        } catch (\Exception $e) {
            return $e->getMessage();
        } 
        
        // 成功跳转至index触发器
        return $this->redirect('index');
    }
}
