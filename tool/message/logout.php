

<?php
session_start();
echo "<meta http-equiv='Content-Type' content='text/html'; charset='utf-8'>";
if(isset($_SESSION['unc'])){
	unset($_SESSION['unc']);
	echo "<script>alert('注销成功！'); window.location.href='index.php';</script>";	
}else{
	echo "<script>alert('您尚未登录！'); window.location.href='index.php';</script>";	
}

?>