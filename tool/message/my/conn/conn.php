
<?php
$conn=mysql_connect("qdm21208779.my3w.com","qdm21208779","Ab127000");			//连接数据库服务器
mysql_select_db("qdm21208779_db",$conn);					//连接指定的数据库
mysql_query("set names utf8");						//对数据库中编码格式进行转换，避免出现中文乱码的问题
?>