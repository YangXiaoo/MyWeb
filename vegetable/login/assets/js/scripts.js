
jQuery(document).ready(function() {

    $('.page-container form').submit(function(){
        var username = $(this).find('.username').val();
        var password = $(this).find('.password').val();
		var repassword = $(this).find('.repassword').val();
        if(username == '') {
            $(this).find('.error').fadeOut('fast', function(){
                $(this).css('top', '27px');
            });
            $(this).find('.error').fadeIn('fast', function(){
                $(this).parent().find('.username').focus();
            });
            return false;
        }
        if(password == '') {
            $(this).find('.error').fadeOut('fast', function(){
                $(this).css('top', '96px');
            });
            $(this).find('.error').fadeIn('fast', function(){
                $(this).parent().find('.password').focus();
            });
            return false;
        }
		if(password !== repassword) {
            $(this).find('.error').fadeOut('fast', function(){
                $(this).css('top','96px');
            });
            $(this).find('.error').fadeIn('fast', function(){
                $(this).parent().find('.repassword').focus();
            });
            return false;
        }
    });

    $('.page-container form .username, .page-container form .password,.page-container form .repassword').keyup(function(){
        $(this).parent().find('.error').fadeOut('fast');
    });

});
