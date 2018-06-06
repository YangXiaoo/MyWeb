<?php
namespace app\admin\controller;

use think\Controller;

class Demo extends Controller
{
    public function initialize()
    {
        $data = [
                'title' => '数据库值title',
                'sex' => '0',
                'flag' => 'j,p,h',
                'content' => '哈哈哈哈哈哈哈',
                'birthday' => '1116723511',
                'birthday2' => '1116723511',
                'birthday3' => '1116723511',
                'birthday4' => '1116723511',
        ];
        $this->assign('data', $data);
    }
    
    public function widgetInput()
    {
        return $this->fetch();
    }
    
    public function widgetSelect()
    {
        return $this->fetch();
    }
    
    public function widgetTextarea()
    {
        return $this->fetch();
    }
    
    public function widgetCheckbox()
    {
        return $this->fetch();
    }
    
    public function widgetRadio()
    {
        return $this->fetch();
    }
    
    public function widgetFileUpload()
    {
        return $this->fetch();
    }
    
    public function widgetDate()
    {
        return $this->fetch();
    }
    
    public function widgetContent()
    {
        return $this->fetch();
    }
}
