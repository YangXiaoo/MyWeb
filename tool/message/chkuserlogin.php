<?php
session_start();
if(isset($_SESSION['userword'])){
	echo "<script>alert('在同一台机器上，不允许同时使用用户名和管理员进行登录！'); window.location.href='index.php';</script>";		
}else{
include_once("conn/conn.php");
echo "<meta http-equiv='Content-Type' content='text/html'; charset='utf-8'>";
if(isset($_POST["usernc"]) && isset($_POST["userpwd"])){
$sql=mysql_query("select usertype from tb_user where usernc='".$_POST["usernc"]."' and userpwd='".md5(trim($_POST["userpwd"]))."'",$conn);
$info=mysql_num_rows($sql);
if($info==1){
    $_SESSION['unc']=$_POST["usernc"];
	echo "<script>alert('O(∩_∩)O哈哈~，登录成功');window.location.href='index.php';</script>"; 
}else{
  echo "<script>alert('o(╥﹏╥)o，登录失败了，可能是网络故障');history.back();</script>";
}
}
}
?>