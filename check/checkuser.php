<?php
if ( ( $_POST['username'] != null ) && ( $_POST['password'] != null ) ) {
   $userName = $_POST['username'];
   $password = $_POST['password'];
session_start();//启用
 $dbhost = 'qdm21208779.my3w.com';  // mysql服务器主机地址
$dbuser = 'qdm21208779';            // mysql用户名
$dbpass = 'Ab127000';          // mysql用户名密码
           //数据库名称

$conn = mysqli_connect($dbhost, $dbuser, $dbpass);
if(! $conn )
{
  die('连接错误: ' . mysqli_error($conn));
}
    mysqli_select_db($conn,'qdm21208779_db');
    $sql = "select * from yangxiao where `username` = '$userName' ";
    $res = mysqli_query($conn,$sql);


	if (!$res) {
 printf("Error: %s\n", mysqli_error($conn));
 exit();
}
    $row = mysqli_fetch_array($res);
	if($row){
       if ($row['password'] == $password) {
                                             //密码验证通过，设置session，把用户名和密码保存在服务端
       $_SESSION['username'] = $username;
       $_SESSION['password'] = $password;
                                             //最后跳转到登录后的欢迎页面 //注意：这里没有像cookie一样带参数过去
       header('Location: ../Life/diary/diary.html');
        }else{
          echo "<script>alert('Password Erro'); window.location.href='login.html';</script>";
          }
	      }else{
	         echo "<script>alert('Uername Erro');window.location.href='login.html';</script>";
	     }
         }
         ?>