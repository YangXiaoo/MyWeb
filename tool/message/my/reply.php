
<?php
include_once("function.php");		//包含系统功能文件
?>



<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="liuyanban">
    <meta name="author" content="yangxiao">

    <title>留言板</title>

    <!-- Custom styles for this template -->
    <link href="css/message.css" rel="stylesheet">
<link href="patch.css" rel="stylesheet">
<!-- 本地版本 Bootstrap 核心 CSS 文件 -->
<link rel="stylesheet" href="../../bootstrap-3.3.7/css/bootstrap.min.css" >
<!-- jquery 核心 JavaScript 文件 -->
<script src="../../bootstrap-3.3.7/js/jquery-3.2.1.min.js" ></script>
<!--  Bootstrap 核心 JavaScript 文件 -->
<script src="../../bootstrap-3.3.7/js/bootstrap.min.js" "></script>
</head>
<body>

<div class="container-fluid">


<ul class="nav nav-pills">
  <li role="presentation" ><a href="#">Home</a></li>
  <li role="presentation"><a href="index.php?id=<?php echo urlencode("首页");?>">首页</a></li>
  <li role="presentation"><a href="index.php?id=<?php echo urlencode("发表留言");?>">发表留言</a></li>
   <li role="presentation"><a href="index.php?id=<?php echo urlencode("查看留言");?>">查看留言</a></li>
    <li role="presentation"><a href="index.php?id=<?php echo urlencode("查询留言");?>">查询留言</a></li>
	 <li role="presentation"><a href="index.php?id=<?php echo urlencode("用户注册");?>">用户注册</a></li>
      <li role="presentation"><a href="index.php?id=<?php echo urlencode("用户登录");?>">用户登录</a></li>
  <li role="presentation" class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
     欢迎&nbsp <?php echo $_SESSION['$username']?><span class="caret"></span>
    </a>
    <ul class="dropdown-menu">
      <li><a href="index.php?id=<?php echo urlencode("注销登录");?>">注销</a></li>
    </ul>
  </li>
</ul>

<div class="row">
<div class="col-sm-8 main">
 

 <table width="550" height="400" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FCD424">
        <tr>
          <td bgcolor="#FFFFFF" valign="top"><table width="550" height="24" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td background="images/dh_back_1.gif">&nbsp;&nbsp;<img src="images/biao.gif" />&nbsp;回复留言</td>
            </tr>
          </table>
		   <script language="javascript">
		     function chkinput(form){
			  if(form.title.value==""){
			    alert("请输入回复主题！");
			    form.title.focus();
				return(false);
			  }
			  
			  if(form.content.value==""){
			    alert("请输入回复内容！");
				form.content.focus();
				return(false);
			  }
			 
			  return(true);
			 }
		   
		   </script>
		  
            <table width="500" height="400" border="0" align="center" cellpadding="0" cellspacing="0">
             <form name="form1" method="post" action="savereply.php">
			  <tr>
                <td height="30" colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td width="120" height="40"><div align="center">回复主题：</div></td>
                <td width="380">&nbsp;<input type="text" name="title" size="40" class="inputcss">
                  <input type="hidden" name="t_id" value="<?php echo $_GET['t_id'];?>" /></td>
              </tr>
              <tr>
                <td height="250"><div align="center">回复内容：</div></td>
				
                <td height="250">&nbsp;<textarea name="content" rows="15" cols="55" class="inputcss">回复<?php 
				if(isset($_GET['loor'])){
					$loor=$_GET['loor'];
					echo $loor."楼：";
				}else{	
					$loor="留言：";
					echo $loor;
				
				}
				?></textarea></td>
			
              </tr>
              <tr>
                <td colspan="2"><div align="center"><input type="submit"  name="submit" value="回复" class="buttoncss"onclick="return chkinput(form1);">
                &nbsp;&nbsp;&nbsp;<input type="reset" name="reset" value="重写" class="buttoncss"></div></td>
              </tr>
			  </form>
            </table></td>
        </tr>
      </table>
	  </td>
    <td width="5" bgcolor="#FAF3CE"></td>
  </tr>
</table>

</div><!--main结束-->

<div class="sidebar-module">
<div class="col-sm-3 col-sm-offset-1 blog-sidebar">
<span class="label label-info">最新消息</span>
<ul>
             <?php
			   $sql=mysql_query("select title ,id ,createtime from tb_leaveword where id order by id desc limit 0,9",$conn);
			   $info=mysql_fetch_array($sql); 
			   if($info==false){
			 ?>
			  <li>
                对不起，暂无留言！
              </li>
			  <?php
			  }else{
			   do{
			  ?>
             <li><a href="index.php?l_id=<?php echo $info["id"];?> & id=<?php echo urlencode('详细信息'); ?>" class="a1">
				<?php 
				 echo unhtml(msubstr($info["title"],0,14));
				 if(strlen(unhtml($info["title"]))>14){
			          echo ".";
			       }
				  echo "<font color=blue>[".str_replace("-","/",substr($info['createtime'],2,8))."]</font>";
				
				?></a></li>
			  <?php
			   }while($info=mysql_fetch_array($sql));
			  }
			  ?>
            </ul>

</div>
</div>




</div>
</body>
</html>