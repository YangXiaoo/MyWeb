$(function(){
    $.pjax.defaults.timeout = 5000;
    $.pjax.defaults.maxCacheLength = 0;
    
    toastr.options = {  
        closeButton: true,                  //是否显示关闭按钮
        debug: false,                       //是否使用debug模式
        progressBar: true,                  //是否显示进度条
        positionClass: "toast-top-right",   //弹出窗的位置
        showDuration: "300",                //显示动作时间
        preventDuplicates: true,            //提示框只出现一次
        hideDuration: "300",                //隐藏动作时间
        timeOut: "3000",                    //自动关闭超时时间
        extendedTimeOut: "1000",            ////加长展示时间
        showEasing: "swing",                //显示时的动画缓冲方式
        hideEasing: "linear",               //消失时的动画缓冲方式
        showMethod: "fadeIn",               //显示时的动画方式
        hideMethod: "fadeOut"               //消失时的动画方式
    };
    
    $(document).pjax('a:not(a[target="_blank"])', {container:'#pjax-container', fragment:'#pjax-container'});
    
    $(document).on('submit', 'form[pjax-search]', function(event) {
        $.pjax.submit(event, {container:'#pjax-container', fragment:'#pjax-container'})
    })
    
    $(document).on('pjax:send', function() { NProgress.start(); });
    $(document).on('pjax:complete', function() { NProgress.done(); });
    
    //提交
    $('body').off('click', '.submits');
    $('body').on("click", '.submits', function(event){
        var _this = $(this);
        _this.button('loading');
        var form = _this.closest('form');
        if(form.length){
            var ajax_option={
                dataType:'json',
                success:function(data){
                    if(data.status == '1'){
                        toastr.success(data.info);
                        var url = data.url;
                        $.pjax({url: url, container: '#pjax-container', fragment:'#pjax-container'})
                    }else if(data.status == '2'){
                        restlogin(data.info);
                    }else{
                        _this.button('reset');
                        toastr.warning(data.info);
                    }
                }
            }
            form.ajaxSubmit(ajax_option);
        }
    });
    
    //状态status列表修改（只能进行0和1值的切换）
    $('body').off('click', 'td a.editimg');
    $('body').on('click', 'td a.editimg', function(event){
        var addclass;
        var removeclass;
        var pvalue;   //提交值
        var _this = $(this);
        var field = _this.data('field');
        var id = _this.data('id');
        var value = _this.data('value');
        var url = _this.data('url');
        if ( value == 1){
            pvalue = 0;
            addclass = "fa-check-circle text-green";
            removeclass = "fa-times-circle text-red";
        }else{
            pvalue = 1;
            addclass = "fa-times-circle text-red";
            removeclass = "fa-check-circle text-green";
        }
        var dataStr = jQuery.parseJSON( '{"id":"'+id+'","'+field+'":"'+pvalue+'"}' );   //字符串转json
        $.ajax({
            type : "post",
            url : url,
            dataType : 'json',
            data : dataStr,
            success : function(data) {
                if(data.status == '1'){
                    _this.data('value', pvalue);
                    _this.removeClass(addclass);
                    _this.addClass(removeclass);
                    toastr.success(data.info);
                }else if(data.status == '2'){
                    restlogin(data.info);
                }else{
                    toastr.warning(data.info);
                }
            }
        });
    });
    
    //单条删除-批量删除
    $('body').off('click', '.delete');
    $('body').on("click", '.delete', function(event){
        event.preventDefault();
        var _this = $(this);
        var title = _this.data('title')?_this.data('title'):'删除';
        var url_del = _this.data('url')||'';
        var message = _this.data('message')?_this.data('message'):'确认操作？';
        var id = _this.data('id')||'';
        if(id == ''){       //批量删除
            var str = '';
            var table_box = _this.closest('.box-header').next('.box-body').find(".table tr td input[name='id[]']");
            $(table_box).each(function(){
                if(true == $(this).is(':checked')){
                    str += $(this).val() + ",";
                }
            });
            if(str.substr(str.length-1)== ','){
                id = str.substr(0, str.length-1);
            }
        }
        if(id && url_del){
            $.confirm({
                title: title,
                type: 'red',
                backgroundDismiss: true,
                content: message,
                buttons: {
                    ok: {
                        text: '确定',
                        btnClass: 'btn-danger',
                        action: function () {
                            $.ajax({
                                type : "post",
                                url : url_del,
                                dataType : 'json',
                                data : { id:id, },
                                success : function(data) {
                                    if(data.status == '1'){
                                        toastr.success(data.info);
                                        if(data.url != ''){
                                            var url = data.url;
                                            $.pjax({url: url, container: '#pjax-container', fragment:'#pjax-container'})
                                        }
                                    }else if(data.status == '2'){
                                        restlogin(data.info);
                                    }else{
                                        toastr.warning(data.info);
                                    }
                                }
                            });
                        }
                    },
                    cancel: {
                        text: '取消',
                    }
                }
            });
        }
    });
    
    //上传
    $('body').off('click', '.upload-btn');
    $('body').on("click", '.upload-btn', function(event){
        var _this_upload_btn = $(this);   //当前上传按钮
        var up_url = _this_upload_btn.data('url');   //上传地址
        
        $.confirm({
            title: '上传 - fileupload',
            type: 'red',
            backgroundDismiss: true,
            content: '<form action="'+up_url+'" method="POST" enctype="multipart/form-data" ><input type="file" name="imgFile" /></form>',
            buttons: {
                formSubmit: {
                    text: '确定',
                    btnClass: 'btn-danger',
                    action: function () {
                        var form = this.$content.find('form');
                        var ajax_option={
                            dataType:'json',
                            success:function(data){
                                if(data.error == '0'){
                                    _this_upload_btn.prev().attr("href", data.url);
                                    _this_upload_btn.prev().find('img').attr("src", data.url);
                                    _this_upload_btn.closest('.input-group').find('input').val(data.url);
                                    toastr.success(data.info);
                                }else{
                                    toastr.warning(data.info);
                                }
                            }
                        }
                        form.ajaxSubmit(ajax_option);
                    }
                },
                cancel: {
                    text: '取消',
                }
            }
        });
    });
})