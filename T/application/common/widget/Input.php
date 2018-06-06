<?php
namespace app\common\widget;

use think\Controller;

class Input extends Controller
{
    /**
     * @Title: index
     * @Description: todo(普通Input挂件)
     * @param array $data       【编辑操作时旧数据集合】
     * @param array $wconfig    【配置项】
     * <pre>
     *      name                组件标签name属性值，对应数据库字段【必须】
     *      title               组件标题【必须】
     *      default_val         默认值，新增无数据时默认值【非必须】
     *      placeholder         提示内容【非必须】
     *      title_col           标题占比（默认2）【非必须】
     *      content_col         内容占比（默认7）【非必须】
     *      inputtype           默认text【非必须，可选：password】
     *      readonly            只读【非必须】
     *      disabled            禁用【非必须】
     * </pre>
     * @return string
     * @author 苏晓信
     * @date 2018年1月1日
     * @throws
     */
    public function index($data, $wconfig)
    {
        $wconfig['widget_val'] = $data[$wconfig['name']];
        if (isset($wconfig['inputtype']) && $wconfig['inputtype'] == 'password'){
            $wconfig['widget_val'] = '';
        }
        
        /* 默认值 */
        if (empty($wconfig['widget_val']) && isset($wconfig['default_val']) && $wconfig['default_val'] != ''){
            $wconfig['widget_val'] = $wconfig['default_val'];
        }
        
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
        $wconfig['inputtype'] = $wconfig['inputtype'] ? $wconfig ['inputtype'] : 'text';
        
        $this->assign('wconfig', $wconfig);
        return $this->fetch('common@widget/input');
    }
}