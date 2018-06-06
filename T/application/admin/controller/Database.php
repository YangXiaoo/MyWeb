<?php
namespace app\admin\controller;

use app\common\controller\SysAction;
use think\facade\Config;
use think\db\Query;

class Database extends SysAction
{
    public function initialize()
    {
        parent::initialize();
    }
    
    /**
     * @Title: index
     * @Description: todo(数据库列表)
     * @author 苏晓信
     * @date 2018年2月7日
     * @throws
     */
    public function index()
    {
        $dataList = db()->query("SHOW TABLE STATUS");
        $this->assign('dataList', $dataList);
        return $this->fetch();
    }
    
    /**
     * @Title: backup
     * @Description: todo(备份数据库)
     * @author 苏晓信
     * @date 2018年2月7日
     * @throws
     */
    public function backup()
    {
        if (request()->isPost()){
            $id = input('id');
            if (isset($id) && !empty($id)){
                $table_arr = explode(',', $id);   //备份数据表
                $config = Config::get();
                $sql = new \expand\Baksql($config['database']);
                $res = $sql->backup($table_arr);
                return ajax_return($res, url('index'));
            }
        }
    }
    
    /**
     * @Title: reduction
     * @Description: todo(备份列表)
     * @author 苏晓信
     * @date 2018年2月7日
     * @throws
     */
    public function reduction()
    {
        $config = Config::get();
        $sql = new \expand\Baksql($config['database']);
        $dataList = $sql->get_filelist();
        $this->assign('dataList', $dataList);
        return $this->fetch();
    }
    
    /**
     * @Title: restore
     * @Description: todo(还原数据库)
     * @author 苏晓信
     * @date 2018年2月7日
     * @throws
     */
    public function restore()
    {
        if (request()->isPost()){
            $name = input('id');
            $config = Config::get();
            $sql = new \expand\Baksql($config['database']);
            $res = $sql->restore($name);
            return ajax_return($res, url('reduction'));
        }
    }
    
    /**
     * @Title: dowonload
     * @Description: todo(下载备份)
     * @author 苏晓信
     * @date 2018年2月7日
     * @throws
     */
    public function dowonload()
    {
        $table = input('table');
        $config = Config::get();
        $sql = new \expand\Baksql($config['database']);
        $sql->downloadFile($table);
    }
    
    /**
     * @Title: delete
     * @Description: todo(删除备份)
     * @author 苏晓信
     * @date 2018年2月7日
     * @throws
     */
    public function delete()
    {
        if (request()->isPost()){
            $name = input('id');
            $config = Config::get();
            $sql = new \expand\Baksql($config['database']);
            $res = $sql->delfilename($name);
            return ajax_return($res, url('reduction'));
        }
    }
}