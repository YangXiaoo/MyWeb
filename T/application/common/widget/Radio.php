<?php
namespace app\common\widget;

use think\Controller;

class Radio extends Controller
{
    /**
     * @Title: index
     * @Description: todo(普通Radio挂件)
     * @param array $data       【编辑操作时旧数据集合】
     * @param array $wconfig    【配置项】
     * <pre>
     *      name                组件标签name属性值，对应数据库字段【必须】
     *      title               组件标题【必须】
     *      from                select选项来源【必须其中一项：selectlist,function】
     *      fromcfg             数据来源配置【必填，配合from使用】
     *          selectlist      来自selectlist配置【如sex,status】
     *          function_name   来自方法数据【一个方法名称不加括号】
     *      title_col           标题占比（默认2）【非必须】
     *      content_col         内容占比（默认7）【非必须】
     *      disabled            禁用【非必须】
     * </pre>
     * @return string
     * @author 苏晓信
     * @date 2018年1月6日
     * @throws
     */
    public function index($data, $wconfig)
    {
        $wconfig['widget_val'] = $data[$wconfig['name']];
        
        if ( $wconfig['from'] != 'selectlist' && $wconfig['from'] != 'function' ){
            return 'select from error';
        }
        if ( !isset($wconfig['fromcfg']) && empty($wconfig['fromcfg'])){
            return 'select fromcfg error';
        }
        
        $optionListData = [];
        if ( $wconfig['from'] == 'selectlist' ){
            if ( config('selectlist.'.$wconfig['fromcfg']) ){
                $selectlist = config('selectlist.'.$wconfig['fromcfg']);
                $optionListData = $selectlist['data'];
            }else{
                return 'selectlist option error';
            }
        }elseif( $wconfig['from'] == 'function' ){
            if ( function_exists($wconfig['fromcfg']) ){
                $functions = "return ".$wconfig['fromcfg']."();";
                $optionListData = eval("return ".$wconfig['fromcfg']."();");
            }else{
                return 'fromcfg function error';
            }
        }
        $optionList = [];
        foreach ($optionListData as $k => $v){
            $optionList[$k]['value'] = $k;
            $optionList[$k]['html'] = $v;
            if ( $wconfig['widget_val'] != '' && $k == $wconfig['widget_val'] ){
                $optionList[$k]['checked'] = 'checked="checked"';
            }else{
                $optionList[$k]['checked'] = '';
            }
        }
        
        /* 是否禁用 */
        if (isset($wconfig['disabled']) && $wconfig['disabled'] == 'disabled'){
            $wconfig['disabled'] = 'disabled="disabled"';
        }else{
            $wconfig['disabled'] = '';
        }
        
        $wconfig['title_col'] = $wconfig['title_col'] ? $wconfig ['title_col'] : '2';
        $wconfig['content_col'] = $wconfig['content_col'] ? $wconfig ['content_col'] : '7';
        
        $this->assign('optionList', $optionList);
        $this->assign('wconfig', $wconfig);
        return $this->fetch('common@widget/radio');
    }
}