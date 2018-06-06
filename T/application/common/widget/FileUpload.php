<?php
namespace app\common\widget;

use think\Controller;

class FileUpload extends Controller
{
    /**
     * @Title: index
     * @Description: todo(普通FileUpload挂件)
     * @param array $data       【编辑操作时旧数据集合】
     * @param array $wconfig    【配置项】
     * <pre>
     *      name                组件标签name属性值，对应数据库字段【必须】
     *      title               组件标题【必须】
     *      placeholder         提示内容【非必须】
     *      type                上传类型【非必须，默认值：image。可选：image,flash,media,file】
     *      title_col           标题占比（默认2）【非必须】
     *      content_col         内容占比（默认7）【非必须】
     *      readonly            只读【非必须】
     *      disabled            禁用【非必须】
     * </pre>
     * @return string
     * @author 苏晓信
     * @date 2018年1月17日
     * @throws
     */
    public function index($data, $wconfig)
    {
        $wconfig['widget_val'] = $wconfig['widget_val_img'] = $data[$wconfig['name']];
        
        /* 图片默认值 */
        if (empty($wconfig['widget_val_img'])){
            $wconfig['widget_val_img'] = '/static/global/image/no-image.png';
        }
        
        /* 是否只读 */
        if (isset($wconfig['readonly']) && $wconfig['readonly'] == 'readonly'){
            $wconfig['readonly'] = 'readonly="readonly"';
            $wconfig['btn_disabled'] = 'disabled="disabled"';
        }else{
            $wconfig['readonly'] = '';
        }
        
        /* 是否禁用 */
        if (isset($wconfig['disabled']) && $wconfig['disabled'] == 'disabled'){
            $wconfig['disabled'] = $wconfig['btn_disabled'] = 'disabled="disabled"';
        }else{
            $wconfig['disabled'] = '';
        }
        
        $wconfig['title_col'] = $wconfig['title_col'] ? $wconfig ['title_col'] : '2';
        $wconfig['content_col'] = $wconfig['content_col'] ? $wconfig ['content_col'] : '7';
        $wconfig['type'] = $wconfig['type'] ? $wconfig ['type'] : 'image';
        
        $this->assign('wconfig', $wconfig);
        return $this->fetch('common@widget/fileupload');
    }
}