<?php
namespace app\common\widget;

use think\Controller;

class Textarea extends Controller
{
    /**
     * @Title: index
     * @Description: todo(普通Textarea挂件)
     * @param array $data       【编辑操作时旧数据集合】
     * @param array $wconfig    【配置项】
     * <pre>
     *      name                组件标签name属性值，对应数据库字段【必须】
     *      title               组件标题【必须】
     *      placeholder         提示内容【非必须】
     *      title_col           标题占比（默认2）【非必须】
     *      content_col         内容占比（默认7）【非必须】
     *      readonly            只读【非必须】
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
        
        /* 是否只读 */
        if (isset($wconfig['readonly']) && $wconfig['readonly'] == 'readonly'){
            $wconfig['readonly'] = 'readonly="readonly"';
        }else{
            $wconfig['readonly'] = '';
        }
        
        /* 是否禁用 */
        if (isset($wconfig['disabled']) && $wconfig['disabled'] == 'disabled'){
            $wconfig['disabled'] = 'disabled="disabled"';
        }else{
            $wconfig['disabled'] = '';
        }
        
        $wconfig['title_col'] = $wconfig['title_col'] ? $wconfig ['title_col'] : '2';
        $wconfig['content_col'] = $wconfig['content_col'] ? $wconfig ['content_col'] : '7';
        
        $this->assign('wconfig', $wconfig);
        return $this->fetch('common@widget/textarea');
    }
}