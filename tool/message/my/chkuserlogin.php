<?php
session_start();
if(isset($_SESSION['userword'])){
	echo "<script>alert('在同一台机器上，不允许同时使用用户名和管理员进行登录！'); window.location.href='index.php';</script>";		
}else{
include_once("conn/conn.php");
if(isset($_POST["usernc"]) && isset($_POST["userpwd"])){
$sql=mysql_query("select usertype from tb_user where usernc='".$_POST["usernc"]."' and userpwd='".md5(trim($_POST["userpwd"]))."'",$conn);
$info=mysql_num_rows($sql);
if($info==1){
    $_SESSION['unc']=$_POST["usernc"];
	echo "<script>alert('successful！');window.location.href='index.php';</script>"; 
}else{
  echo "<script>alert('fail！');history.back();</script>";
}
}
}
?>