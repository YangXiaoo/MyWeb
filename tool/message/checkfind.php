<?php
include("conn/conn.php");
echo "<meta http-equiv='Content-type' content='text/html'; charset='utf-8' >";
$pwd=$_POST["pwd"];
$usernc=$_POST["usernc"];
$sql=mysql_query("select * from tb_user where usernc='$usernc'",$conn);
$info=mysql_fetch_array($sql);
if(mysql_num_rows($sql)==1){
    mysql_query("update tb_user set userpwd='".md5($pwd)."' where usernc='$usernc'",$conn);
	echo "<script>alert('修改成功,返回主页登录');window.location.href='index.php';</script>";
}else{
    echo "<script>alert('修改失败');history.back()</script>";
}
?>