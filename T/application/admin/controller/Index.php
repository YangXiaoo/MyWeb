<?php
namespace app\admin\controller;

use app\common\controller\SysAction;
use think\helper\Time;
use think\facade\Env;

class Index extends SysAction
{
    public function initialize()
    {
        parent::initialize();
    }
    
    public function index()
    {
        $data = [];
        
        $data['user_total'] = $this->userTotal();               //用户总数
        $data['archive_total'] = $this->archiveTotal();         //文章总数
        $data['user_near'] = $this->userNear();                 //最近7天注册用户数
        $data['guestbook_total'] = $this->guestbookTotal();     //最近7天评论数
        $data['login_line_json'] = $this->loginLogLineJson();   //最近30天登录统计json
        $data['login_list'] = $this->loginLogList();            //最新登录8条信息
        $data['group_pie_json'] = $this->groupPieJson();        //用户组人数统计
        $data['system_config'] = $this->systemConfig();         //用户组人数统计
        
        $this->assign('data', $data);
        return $this->fetch();
    }
    
    /**
     * @Title: userTotal
     * @Description: todo(用户总数)
     * @author 苏晓信
     * @date 2018年2月6日
     * @throws
     */
    private function userTotal()
    {
        $userModel = new \app\common\model\User;
        return $userTotal = $userModel->count();
    }
    
    /**
     * @Title: archiveTotal
     * @Description: todo(文章总数)
     * @author 苏晓信
     * @date 2018年2月6日
     * @throws
     */
    private function archiveTotal()
    {
        $archiveModel = new \app\common\model\Archive;
        return $archiveTotal = $archiveModel->count();
    }
    
    /**
     * @Title: userNear
     * @Description: todo(最近7天注册用户)
     * @author 苏晓信
     * @date 2018年2月6日
     * @throws
     */
    private function userNear()
    {
        $userModel = new \app\common\model\User;
        $userTime =  Time::dayToNow(7, true);   //7天前零点到昨日结束的时间戳
        $userTime = $userTime[0];
        return $userNear = $userModel->where('create_time', 'egt', $userTime)->count();
    }
    
    /**
     * @Title: guestbookTotal
     * @Description: todo(最近7天评论数)
     * @author 苏晓信
     * @date 2018年2月6日
     * @throws
     */
    private function guestbookTotal()
    {
        return 'XXX';
//         $guestbookModel = new \app\admin\model\Guestbook;
//         $nowTime = strtotime(date('Ymd', time())) + 86400;
//         $guestbookTime = $nowTime - 604800;
//         return $guestbookTotal = $guestbookModel->where('create_time', 'egt', $guestbookTime)->count();
    }
    
    /**
     * @Title: loginLogLineJson
     * @Description: todo(最近30天登录统计json)
     * @author 苏晓信
     * @date 2018年2月6日
     * @throws
     */
    private function loginLogLineJson()
    {
        $loginLogModel = new \app\common\model\LoginLog;
        $time = Time::dayToNow(30);
        $nowTime = $time[1];
        $loginLogLineTime = $time[0];
        $loginLogLine = $loginLogModel->where('create_time', 'between', [$loginLogLineTime, $nowTime])->select();
        $loginLogLineArr = [];
        $loginLogLineArr['datasets'][0]['label'] = lang('login_count');
        $loginLogLineArr['datasets'][0]['fill'] = false;
        $loginLogLineArr['datasets'][0]['borderColor'] = '#4bc0c0';
        foreach ($loginLogLine as $k=>$v){
            $labels = date('Y-m-d', strtotime($v['create_time']));
            if (!in_array($labels, $loginLogLineArr['labels'])){
                $loginLogLineArr['labels'][] = date('Y-m-d', strtotime($v['create_time']));
            }
            $loginLogLineArr['datasets']['data'][$labels] += 1;
        }
        $old = $loginLogLineArr['datasets']['data'];
        unset($loginLogLineArr['datasets']['data']);
        foreach ($old as $v){
            $loginLogLineArr['datasets'][0]['data'][] = $v;
        }
        return $loginLogLineJson = json_encode($loginLogLineArr);
    }
    
    /**
     * @Title: loginLogList
     * @Description: todo(最新登录8条信息)
     * @author 苏晓信
     * @date 2018年2月6日
     * @throws
     */
    private function loginLogList()
    {
        $loginLogModel = new \app\common\model\LoginLog;
        return $loginLogList = $loginLogModel->limit(8)->order('id DESC')->select();
    }
    
    /**
     * @Title: groupPieJson
     * @Description: todo(用户组人数统计)
     * @return string
     * @author 苏晓信
     * @date 2018年2月6日
     * @throws
     */
    private function groupPieJson()
    {
        $agModel = new \app\common\model\AuthGroup;
        $userModel = new \app\common\model\User;
    
        $groupPieArr = [];
    
        $agData = $agModel->where('status', 1)->select();
        $noGroup = ['id' => '0', 'title' => lang('user_no_group'), 'pic' => '#666'];
        $agData[count($array)-1] = $noGroup;
        foreach($agData as $k => $v){
            $agData[$k]['count'] = 0;
            $groupPieArr['labels'][] = $v['title'];
        }
        $userData = $userModel->select();
        foreach ($userData as $k=>$v){
            $userGroup = $v->userGroup;
            if(!empty($userGroup)){
                foreach ($userGroup as $k2 => $v2){
                    foreach ($agData as $k3 =>$v3){
                        if ($v3['id'] == $v2['group_id']){
                            $agData[$k3]['count'] += 1;
                            break;
                        }
                    }
                }
            }else{
                $agData[count($array)-1]['count'] += 1;
            }
        }
        foreach($agData as $k=>$v){
            $groupPieArr['datasets'][0]['data'][] = $v['count'];
            $groupPieArr['datasets'][0]['backgroundColor'][] = $v['pic'];
        }
        return $groupPieJson = json_encode($groupPieArr);
    }
    
    /**
     * @Title: systemConfig
     * @Description: todo(服务器信息配置)
     * @author 苏晓信
     * @date 2018年2月6日
     * @throws
     */
    private function systemConfig()
    {
        return $config = [
            '操作系统' => PHP_OS,
            '服务器时间' => date("Y-n-j H:i:s"),
            'PHP版本号' => PHP_VERSION,
            '运行环境' => $_SERVER["SERVER_SOFTWARE"],
            'PHP运行方式' => php_sapi_name(),
            '上传附件限制' => ini_get('upload_max_filesize'),
            '执行时间限制' => ini_get('max_execution_time').'秒',
        ];
    }
    
    
    
    /**
     * @Title: cleanCache
     * @Description: todo(清除缓存)
     * @return mixed
     * @author 苏晓信
     * @date 2018年1月29日
     * @throws
     */
    public function cleanCache()
    {
        deldir(Env::get('runtime_path'), 'y');
        if (request()->isPost()){
            return ajax_return(lang('action_success'), '', 1);
        }else{
            return $this->fetch();
        }
    }
}
