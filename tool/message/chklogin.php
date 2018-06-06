<?php
session_start();
include("conn/conn.php");
echo "<meta http-equiv='Content-Type' content='text/html'; charset='utf-8'>";
	if(isset($_POST['Submit']) && $_POST['Submit']=="登录")	{
		if($_POST['username']!="" && $_POST['password']!=""){					
			$check="select userword from tb_adm where userword='".$_POST['username']."'and password='".$_POST['password']."'";		
			$result=mysql_query($check,$conn);						
			$info=mysql_num_rows($result);								
   	 		if($info==1){
		 		$_SESSION["userword"]=$_POST['username'];  
      			echo "<script>alert('登录成功'); window.location.href='admin_browse.php';</script>";
			}else{
				echo "<script>alert('登录失败，你不是站长'); window.location.href='index.php';</script>";		
			}
		} 
	}else{
		echo "<script>alert('null'); window.location.href='index.php';</script>";		
	}
?>
 