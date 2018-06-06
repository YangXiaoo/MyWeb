<?php
session_start();
$usernc=$_SESSION['unc'];
include("conn/conn.php");
echo "<meta http-equiv='Content-Type' content='text/html'; charset='utf-8'>";
if(isset($_POST["name"])){
    if(mysql_query("update  tb_user set usernc='".$_POST["name"]."' where usernc='$usernc'",$conn)){
	    echo "<script>alert('^_^，名称修改成功，请返回主页重新登录！');window.location.href='logout.php';</script>"; 
      }else{
         echo "<script>alert('o(╥﹏╥)o，没有修改成功');history.back();</script>";
      }
	}

if(isset($_POST["qq"])){
    if(mysql_query("update  tb_user set qq='".$_POST["qq"]."' where usernc='$usernc'",$conn)){
	    echo "<script>alert('^_^，qq号码修改成功！');history.go(-1);</script>"; 
      }else{
         echo "<script>alert('o(╥﹏╥)o，没有修改成功');history.back();</script>";
      }
	}

if(isset($_POST["email"])){
    if(mysql_query("update  tb_user set email='".$_POST["email"]."' where usernc='$usernc'",$conn)){
	    echo "<script>alert('^_^，邮箱修改成功！');history.go(-1);</script>"; 
      }else{
         echo "<script>alert('o(╥﹏╥)o，没有修改成功');history.back();</script>";
      }
	}
if(isset($_POST["userpwd1"])&&isset($_POST["userpwd2"])){
	$sql = mysql_query("select * from tb_user  where usernc='$usernc' and userpwd='".md5(trim($_POST["userpwd1"]))."'",$conn);
	$info=mysql_num_rows($sql);

    if($info==1){
	    mysql_query("update  tb_user set userpwd='".md5(trim($_POST["userpwd2"]))."' where usernc='$usernc'",$conn);
		echo "<script>alert('^_^，密码修改成功，请返回主页重新登录！');window.location.href='logout.php';</script>"; 
      }else{
         echo "<script>alert('o(╥﹏╥)o，没有修改成功');history.back();</script>";
      }
	}
?>