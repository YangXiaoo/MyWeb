<?php
session_start();		//启用session支持
include("conn/conn.php");		//包含数据库文件
include_once("function.php");		//包含系统功能文件
if(isset($_GET['id'])){			//判断获取超链接传递的值
			$id=$_GET['id'];	
		}else{
			$id="我的资料";
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

    <title>个人中心</title>

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
     <li role="presentation"><a href="index.php?id=<?php echo urlencode("用户登录");?>">登录</a></li>
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
	  <li><a href="myInfo.php">个人中心</a></li>
	  <li><a href="index.php?id=<?php echo urlencode("注销登录");?>">注销</a></li>
    </ul>
   </li>
  <?php
  }?>
   </ul>
</div>
</nav>

<!-- //未阅读留言数 -->
<?php
$sql1=mysql_query("select usernc,face,ip,email,qq,id from tb_user where usernc='".$_SESSION['unc']."'",$conn);
$info1=mysql_fetch_array($sql1); 
$sql=mysql_query("select * from tb_leaveword where userid='".$info1["id"]."'",$conn);
$to=0;
while($le=mysql_fetch_array($sql)){
	$sqlr=mysql_query("select sum(look) as total from tb_replyword where leave_id='".$le["id"]."'",$conn);
    $array=mysql_fetch_array($sqlr);
	$total=$array['total'];
	$to=$to+$total;
}
?>

<!-- 导航条结束 -->

<!-- 左侧导航条 -->
<div class="row">
        <div class="col-sm-2  sidebar">
          <ul class="nav nav-sidebar">
            <li class="personal"><a href="#">个人中心<span class="sr-only">(current)</span></a></li>
            <li <?php if($id=="我的资料"){echo "class='active'";}?>><a href="myInfo.php">我的资料</a></li>
            <li <?php if($id=="我的留言"){echo "class='active'";}?>><a href="myInfo.php?id=<?php echo urlencode("我的留言")?>" >我的留言</a></li>
            <li <?php if($id=="消息"){echo "class='active'";}?>><a href="myInfo.php?id=<?php echo urlencode("消息")?>" >消息&nbsp;&nbsp;<span class="badge"><?php echo $to?></span></a></li>
			<li <?php if($id=="浏览记录"){echo "class='active'";}?>><a href="myInfo.php?id=<?php echo urlencode("浏览记录")?>" >浏览记录</a></li>
			<li <?php if($id=="好友"){echo "class='active'";}?>><a href="myInfo.php?id=<?php echo urlencode("好友")?>" >我的好友</a></li>
          </ul>
        </div>
<!--左侧导航条结束-->
<!-- 内容 -->
<div class="col-sm-6 col-sm-offset-1"  id="main">
<?php 
switch($id){
	case "我的留言":
		include "myleave.php";
	break;
	case "消息":
		include "message.php";
	break;
	case "浏览记录":
		include "lookhistory.php";
	break;
	case "好友":
		include "friend.php";
	break;
    default:
		include "myinformation.php";
	break;

}
?>
</div>
<!-- 主内容结束 -->


<!-- 右侧内容 -->
<div class="col-sm-2  blog-sidebar">
<div class="sidebar-module">
</div>
</div>
<!-- 右侧内容结束 -->

</div>
<!-- 结束row -->
</div>
<!-- 结束内容 -->
<?php 
include_once("bottom.php");
?>
</body>
</html>