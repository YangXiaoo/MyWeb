<?php
if(trim($_POST['password']) != trim($_POST['repassword'])){
    exit('两次密码不一致');
}
$username = trim($_POST['username']);

$password = trim($_POST['password']);

$time = time();

$ip = $_SERVER['REMOTE_ADDR'];

$conn = mysqli_connect('qdm21208779.my3w.com', 'qdm21208779', 'Ab127000' );

//如果有错误，存在错误号
if (mysqli_errno($conn)) {

   echo mysqli_error($conn);

   exit;
}

mysqli_select_db($conn, 'qdm21208779_db');

mysqli_set_charset($conn, 'utf8');

$sql = "insert into user(username,password,createtime,createip) values('" . $username . "','" . $password . "','" . $time . "','" . $ip . "')";

$result = mysqli_query($conn, $sql);

if ($result) {
   header('Location:login/login1.html');
} else {
   echo '创建账号失败';
}
mysqli_close($conn);

?>