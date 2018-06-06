<?php
session_start();	//启用session支持
include_once("conn/conn.php");		//包含数据库连接文件
include_once("function.php");	
echo "<meta http-equiv='Content-Type' content='text/html'; charset='utf-8'>";
$name=$_SESSION["unc"];
//包含系统功能文件
if(isset($_SESSION["unc"])){        //对登录用户进行判断
	$sql=mysql_query("select id from tb_user where usernc='".$_SESSION["unc"]."'");   //查询当前用户数据表中的信息
	$info=mysql_fetch_array($sql);   //从结果集中获取信息
	$userid=$info['id'];	//获取用户的id
}
if(isset($_SESSION['userword'])){
	$sql=mysql_query("select id from tb_adm where userword='".$_SESSION["userword"]."'");
	$info=mysql_fetch_array($sql);
	$userid=$info['id'];
}
if(isset($_POST['content'])){
	if (is_file("filterwords.txt")){					//判断给定文件名是否为一个正常的文件
	    $filter_word = file("filterwords.txt");		//把整个文件读入一个数组中
		$content=$_POST['content'];
		$title=$_POST['title'];
		$category=$_POST['category'];
		for($i=0;$i<count($filter_word);$i++){			//应用For循环语句对敏感词进行判断		
		   if(preg_match("/".trim($filter_word[$i])."/i",$content)){		//应用正则表达式，判断传递的留言信息中是否含有敏感词
			  echo "<script >alert('w(ﾟДﾟ)w，留言信息中居然包含敏了感词！');history.go(-2);</script>";
		 	  exit;
		   }
	    }
	}
	$createtime=date("Y-m-d H:i:s");//获取留言时间
	$ldate=date("Y-m-d");
    if($_POST['category1']!=""){
		$authorization=0;
	$date=date("Y-m-d H:i:s");
	$insert=mysql_query("insert into tb_category(authorization,category,noderator,create_date) values('$authorization','".$_POST['category1']."','$name','$date')",$conn);
	if($insert){
		echo "<script>alert('等待管理员审核！');history.go(-2);</script>";
	}else{
		echo "<script>alert('(ಥ﹏ಥ)，发布失败了');history.go(-2);</script>";
	}
	}
	else{

	if(mysql_query("insert into tb_leaveword(userid,createtime,title,content,category,date)values('$userid','$createtime','$title','$content','$category','$ldate')")){ 
       echo "<script>alert('O(∩_∩)O~，发布成功了');history.go(-2);</script>";
    
	   //在页面中提交的留言信息，写入到数据库留言表中，提示留言成功并跳转到查看留言
	}else{
	   echo "<script >alert('o(╥﹏╥)o，失败了');history.go(-2);</script>";
	}
}
}
?>
