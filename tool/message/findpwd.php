<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="liuyanban">
    <meta name="author" content="yangxiao">

    <title>找回密码</title>

    <!-- Custom styles for this template -->
    <link href="css/message.css" rel="stylesheet">
    <!-- 本地版本 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="../../bootstrap-3.3.7/css/bootstrap.min.css" >
    <!-- jquery 核心 JavaScript 文件 -->
     <script src="../../bootstrap-3.3.7/js/jquery-3.2.1.min.js" ></script>
    <!--  Bootstrap 核心 JavaScript 文件 -->
    <script src="../../bootstrap-3.3.7/js/bootstrap.min.js" "></script>
    </head>
    <body>
<div class="row">
<div class="col-sm-4 col-sm-offset-4" align="center">
<div class="alert alert-success" role="alert">
  <h3>请牢记你的密码</h3>
</div>
<form method="post"  name="form"   action="checkfind.php" onSubmit="return check(this)">
<input type="text" name="usernc" placeholder="名称" class="form-control"><br>
    <input type="password" name="pwd" placeholder="密码"  class="form-control"><br>
	<input type="password" name="pwd1" placeholder="重复密码"  class="form-control"><br>
	<div align="center">
	<input type="submit" value="提交" class="btn btn-info">&nbsp;&nbsp;<input type="reset" value="重写" class="btn btn-info">
	</div>
</form>
<script>
    function check(form){
       
		 if(form.usernc.value==""){
		    alert ("请输入名称");
			form.usernc.focus();
			return false;
		}
	    if(form.pwd.value==""||form.pwd1.value==""){
		    alert ("输入密码");
			form.pwd.focus();
			return false;
		}
		

		if(form.pwd.value.length<6){
		    alert("密码长度小于6");
			form.pwd.focus();
			return false;
		}
		if(form.pwd.value!=form.pwd1.value){
		    alert ("前后密码不相同");
			form.pwd1.focus();
			return false;
		}
		 return true;

	}
</script>
</div>
</div>
<!-- 结束内容 -->
<?php 
include_once("bottom.php");
?>
</body>
</html>