<!DOCTYPE html>
<html>
<head>
	<title>test</title>
</head>
<body>
<?php
$conn=mysql_connect("xxx","xxx","xxx");			//连接数据库服务器
mysql_select_db("xxx",$conn);					//连接指定的数据库
mysql_query("set names utf8");						//对数据库中编码格式进行转换，避免出现中文乱码的问题
?>
</body>
</html>
