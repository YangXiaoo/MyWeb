<?php
if(trim($_POST['password']) != trim($_POST['repassword'])){
    exit('�������벻һ��');
}
$username = trim($_POST['id']);

$password = md5(trim($_POST['password']));

$time = time();

$ip = $_SERVER['REMOTE_ADDR'];

$conn = mysqli_connect('qdm21208779.my3w.com', 'qdm21208779', 'Ab127000', );

//����д��󣬴��ڴ����
if (mysqli_errno($conn)) {

   echo mysqli_error($conn);

   exit;
}

mysqli_select_db($conn, 'qdm21208779_db');

mysqli_set_charset($conn, 'utf8');

$sql = "insert into user(username,password,createtime,createip) values('" . $username . "','" . $password . "','" . $time . "','" . $ip . "')";

$result = mysqli_query($conn, $sql);

if ($result) {
   header['location login.php'];
} else {
   echo '�����˺�ʧ��';.

}

mysqli_close($conn);

?>