<?php
if ( ( $_POST['username'] != null ) && ( $_POST['password'] != null ) ) {
   $userName = $_POST['username'];
   $password = $_POST['password'];
session_start();//����
 $dbhost = 'qdm21208779.my3w.com';  // mysql������������ַ
$dbuser = 'qdm21208779';            // mysql�û���
$dbpass = 'Ab127000';          // mysql�û�������
           //���ݿ�����

$conn = mysqli_connect($dbhost, $dbuser, $dbpass);
if(! $conn )
{
  die('���Ӵ���: ' . mysqli_error($conn));
}

    mysqli_select_db($conn,'qdm21208779_db');

    $sql = "select * from yangxiao where `username` = '$userName' ";
    $res = mysqli_query($conn,$sql);
    $row = mysqli_fetch_assoc($res);
	if($row){
if ($row['password'] == $password) {
       //������֤ͨ��������session�����û��������뱣���ڷ����
       $_SESSION['username'] = $username;
       $_SESSION['password'] = $password;
       //�����ת����¼��Ļ�ӭҳ�� //ע�⣺��������û����cookieһ����������ȥ
       header('Location: ../Life/diary/diary.html');
}else{
   echo "<script>alert('Password Erro'); window.location.href='login.html';</script>";
}
	}else{
	echo "<script>alert('Uername Erro');window.location.href='login.html';</script>";
	}
}
?>