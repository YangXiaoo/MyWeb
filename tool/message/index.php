<?php
session_start();		//启用session支持
include("conn/conn.php");		//包含数据库文件
include_once("function.php");		//包含系统功能文件
if(isset($_GET['id'])){			//判断获取超链接传递的值
			$id=$_GET['id'];	
		}else{
			$id="首页";
		}

if(isset($_SESSION['unc'])){
	$user=$_SESSION['unc'];
}else{
	$user="";
}
if(isset($_SESSION['userword'])){
	$adm=$_SESSION['userword'];
}else{
	$adm="";
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="liuyanban">
    <meta name="author" content="yangxiao">

    <title>留言板</title>

    <!-- Custom styles for this template -->
    <link href="css/message.css" rel="stylesheet">
<!-- 本地版本 Bootstrap 核心 CSS 文件 -->
<link rel="stylesheet" href="../../bootstrap-3.3.7/css/bootstrap.min.css" >
<!-- jquery 核心 JavaScript 文件 -->
<script src="../../bootstrap-3.3.7/js/jquery-3.2.1.min.js" ></script>
<!--  Bootstrap 核心 JavaScript 文件 -->
<script src="../../bootstrap-3.3.7/js/bootstrap.min.js" "></script>
<link rel="stylesheet" href="css/dialog.css">
<script src="dist/dialog.js"></script>
</head>
<body>

<div class="container">

<nav class="navbar navbar-default">


<div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>

      <a class="navbar-brand" href="../../index.html"><img src="image/home.png" height="30px"></a>

    </div>

<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
<ul class="nav navbar-nav ">
  <li role="presentation"><a href="index.php?id=<?php echo urlencode("首页");?>">首页</a></li>
  <li role="presentation"><a href="index.php?id=<?php echo urlencode("发表留言");?>">发表</a></li>
</ul>
<ul class="nav navbar-nav navbar-right">
<li  role="presentation"><a href="index.php?id=<?php echo urlencode("查询留言");?>"> <img src="image/search.png" height="20px"></a> </li>

<?php 
if(!isset($_SESSION['unc'])){
	?>
     <li role="presentation"><a href="#"   data-toggle="modal" data-target="#login" data-whatever="@mdo">登录</a></li>
	 <li role="presentation"><a href="index.php?id=<?php echo urlencode("用户注册");?>">注册</a></li>
     
<?php
}else{
	?>

  <li role="presentation" class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
     欢迎&nbsp
	<?php
    if(isset($_SESSION['unc'])){
	     echo $_SESSION['unc'];
	}
	else{
		echo $_SESSION['userword'];
	}
	?>

	<span class="caret"></span>
    </a>
    <ul class="dropdown-menu">
	  <li><a href="myInfo.php?">个人中心</a></li>
	  <li><a href="index.php?id=<?php echo urlencode("注销登录");?>">注销</a></li>
    </ul>
  </li>
  <?php
  }?>
</ul>


</div>
</nav>


<div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="login">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <div align="center"><h4 class="modal-title" >用户登录</h4></div>
      </div>
<div class="modal-body">
<?php
?>
  <script language="javascript">
			   function chkinputlogin(form){
			     if(form.usernc.value==""){
				   alert("请输入用户昵称！");
				   form.usernc.focus();
				   return(false);
				 }
				 
				  if(form.userpwd.value==""){
				   alert("请输入登录密码！");
				   form.userpwd.focus();
				   return(false);
				 }
				 if(form.numb.value!=form.checknum.value){
				     alert("验证码不正确");
					 form.numb.focus();
					 return false;
				 }
				 return(true);
			   }
function chkinputlogin1(form){
			     if(form.email.value==""){
				   alert("请输入邮箱！");
				   form.email.focus();
				   return(false);
				 }
           if(form.numb.value!=form.checknum.value){
				     alert("验证码不正确");
					 form.numb.focus();
					 return false;
				 }
				 return true;
}
}

   $(document).ready(function(){
       $("#findf").hide();
	   $("#find").click(function(){
	       $("#log").hide();
		   $("#findf").show();
	   });
		   $("#back").click(function(){
			   $("#findf").hide();
		       $("#log").show();
		   });

   });












			 </script>
<?php
$num="";
	for($i=0;$i<4;$i++){
		$num .= dechex(rand(0,15));
	}//产生验证码num
?>
<div class="row">
<div class="col-sm-12">
             <form name="form_login" method="post"  action="chkuserlogin.php" onsubmit="return chkinputlogin(this)" id="log">
               <strong>账号：&nbsp;</strong>
               <input type="text" class="form-control"  name="usernc">

                <strong>密码：</strong>&nbsp;
                <input type="password"  class="form-control"  name="userpwd"><br>
				<div class="row">
			  <div class="col-sm-6" >
				<strong>验证码</strong><input type="text" name="numb" class="form-control">
				</div>
				<div class="col-sm-6" >
				<br><input id="checknum" name="checknum" type="hidden" value="<?php echo $num; ?>">
				<img src="inc/valcode.php?num=<?php echo $num; ?>" width="60" height="30" />
				
				</div>
				</div>
				<div align="right">
                <input type="button" value="忘记" class="btn btn-info" id="find">&nbsp;&nbsp; <input type="submit" value="登录" class="btn btn-info">&nbsp;&nbsp;<input type="reset" value="重写" class="btn btn-info">
             </div>
               </form>



             <form name="form_find" method="post"  action="emailfind.php" onsubmit="return chkinputlogin1(this)" id="findf">
               <strong>注册邮箱&nbsp;</strong>
               <input type="eamil" class="form-control"  name="email">

              <br>
			  <div class="row">
			  <div class="col-sm-6" >
				<strong>验证码</strong><input type="text" name="numb" class="form-control">
				</div>
				<div class="col-sm-6" >
				<br><input id="checknum" name="checknum" type="hidden" value="<?php echo $num; ?>">
				<img src="inc/valcode.php?num=<?php echo $num; ?>" width="60" height="30" />
				
				</div>
				</div>
				<div align="right">
                <input type="button" value="返回" class="btn btn-info" id="back">&nbsp;&nbsp; <input type="submit" value="找回" class="btn btn-info">
             </div>
               </form>
            </div>
			</div>
      </div>
    </div>
  </div>
</div>






 <div class="container bs-docs-container container-fluid" id="main">
<div class="row">
<div class="col-sm-7 blog-main">
  <?php 
		
		switch($id){	//获取超级链接传递的变量		
			case "首页":		//判断如果变量的值等于“首页”		
        		include "shouye.php";			//则执行该语句
    		break;			//否则跳出循环	
			case "板块内容":
				include "main.php";
			break;
			case "用户注册":
        		include "reg.php";
    		break;
			case "发表留言":
        		include "leaveword.php";
    		break;
			case "查看留言":
        		include "lookleaveword.php";
    		break;
			case "查询留言":
        		include "searchword.php";
    		break;
			case "版主管理":
        		include "login.php";
    		break;
			case "注销登录":
        		include "logout.php";
    		break;
			case "编辑留言":
        		include "editleaveword.php";
    		break;
			case "回复留言":
				include "reply.php";
			break;
			case "回复编辑留言":
        		include "edlitreplyword.php";
    		break;
			case "详细信息":
        		include "lookxx.php";
    		break;
			case "个人信息":
				include "personal.php";
			break;
			default:		//判断当该值等于空时，执行下面的语句					
        		include "main.php";
    		break;
		}
	?>


</div><!--main结束-->
<?php
$id=$_GET['id'];
if ($id=="首页"||$id=="板块内容"||$id=="查看留言"||$id=="详细信息"||$id=="查询留言")
{
	?>
<div class="col-sm-3 col-sm-offset-1 blog-sidebar">
<div class="sidebar-module">

<span class="label label-info"><strong>最新消息</strong></span><br><br>

	<?php
			   $sql=mysql_query("select title ,id ,createtime from tb_leaveword where id order by id desc limit 0,6",$conn);
			   $info=mysql_fetch_array($sql); 
			   if($info==false){
			 ?>
			  
              <p>对不起，暂无留言!</p>
              
			  <?php
			  }else{
			   do{
			  ?>
             <span>
			 <a href="index.php?l_id=<?php echo $info["id"];?> & id=<?php echo urlencode('详细信息'); ?>">
				<?php 
				 echo unhtml(msubstr($info["title"],0,50));
				 if(strlen(unhtml($info["title"]))>50){ 
			          echo "&hellip;";
			       }
				  echo "<font color=blue>[".str_replace("-","/",substr($info['createtime'],2,8))."]</font>";
				?></a><br></span>
			  <?php
			   }while($info=mysql_fetch_array($sql));
			  }
			  ?>

</div>
</div>
<?php
}
?>
</div>
</div>
</div>
<?php 
include_once("bottom.php");
?>
</body>
</html>                                                  