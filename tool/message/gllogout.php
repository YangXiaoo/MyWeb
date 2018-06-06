<?php
session_start();
if(isset($_SESSION['userword'])){
	unset($_SESSION['userword']);
	echo "<script>alert('success!'); window.location.href='index.php';</script>";	
}else{
	echo "<script>alert('fail!'); window.location.href='index.php';</script>";	
}

?>