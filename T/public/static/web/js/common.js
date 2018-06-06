$(function(){
    $.pjax.defaults.timeout = 5000;
    $.pjax.defaults.maxCacheLength = 0;
    $(document).pjax('a:not(a[target="_blank"])', {container:'#pjax-container', fragment:'#pjax-container'});
    
    $(document).on('submit', 'form[pjax-search]', function(event) {
        var _this = $(this);
        $.pjax.submit(event, {container:'#pjax-container', fragment:'#pjax-container'});
        _this.find('input[name="k"]').val('');
    })
    
    $(document).on('pjax:send', function() { NProgress.start(); });
    $(document).on('pjax:complete', function() { NProgress.done(); });
})

/*返回顶部*/
$(window).scroll(function(){
    var sc=$(window).scrollTop();
    var rwidth=$(window).width()+$(document).scrollLeft();
    var rheight=$(window).height()+$(document).scrollTop();
    if(sc>0){
        $("#goTop").css("display","block");
    }else{
        $("#goTop").css("display","none");
    }
});
$("#goTop").click(function(){
    $('body,html').animate({scrollTop:0},300);
});
/*返回顶部*/

/*手机固定*/
$(window).resize(function(){
    if($(window).width() < 500){
        $('body').removeClass("layout-boxed").addClass("fixed");
    }
});
if($(window).width() < 500){
    $('body').removeClass("layout-boxed").addClass("fixed");
}
/*手机固定*/