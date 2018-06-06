$(document).ready(function(){
    $("").click(function(){
    	
    });
});
$(function () {
  $('[data-toggle="popover"]').popover()
});/*提示框*/
 function $(id) {  
       return document.getElementById(id);  
   }  

          function check(form) {

          if($("#name").value=='') {
                alert("请输入用户帐号!");
                form.name.focus();
                return false;
           }
       if($("#password").value==''){
                alert("请输入登录密码!");
                form.password.focus();
                return false;
         }
         return true;
         }