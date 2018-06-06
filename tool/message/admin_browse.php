<?php
session_start();		//启用session支持
include("conn/conn.php");		//包含数据库文件
include_once("function.php");		//包含系统功能文件
if(isset($_GET['page'])){
	$page=$_GET['page'];
}else{
	$page=1;
}
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


<div class="row">
<div class="col-sm-8 main">
          <div class="alert alert-success" role="alert" align="center">
  <h4>版主浏览</h4>
        </div>

			<form action="" method="post" name="form1" style="font-size: 24px; color: #00CC66">
        <div align="center">

          <table border="1" cellpadding="1" cellspacing="1" bordercolor="#FFFFFF" bgcolor="#FCD424">
            <tr>
              <td bgcolor="#FFFFFF"><div align="center" class="STYLE4" style="color: #00CC66">主题</div></td>
              <td bgcolor="#FFFFFF"><div align="center" class="STYLE4" style="color: #00CC66">用户</div></td>
              <td  bgcolor="#FFFFFF"><div align="center" class="STYLE4" style="color: #00CC66">发帖时间</div></td>
              <td  bgcolor="#FFFFFF"><div align="center" class="STYLE4" style="color: #00CC66">回复次数</div></td>
              <td bgcolor="#FFFFFF"><div align="center" class="STYLE4" style="color: #00CC66">全主题删除</div></td>
            </tr>
<?php
	include("conn/conn.php");

	$pagesize = 5 ;											//每页显示记录数
	$sqlstr = "select * from tb_leaveword";
	$total = mysql_query($sqlstr,$conn);
	$totalNum = mysql_num_rows($total);						//总记录数
	$pagecount = ceil($totalNum/$pagesize);				//总页数
	$offset=($page-1)*$pagesize;
	$sqlpase="select * from tb_leaveword  order by id desc limit $offset,$pagesize";
	$result=mysql_query($sqlpase,$conn);
	while ($rows = mysql_fetch_array($result)){					//循环输出查询结果
?>	
          <tr>
              <td bgcolor="#FFFFFF"><div align="center"><a href="index.php?l_id=<?php echo $rows['id']; ?>&id=<?php echo urlencode('详细信息'); ?>"><?php echo $rows['title'];?></a></div></td>
              <td bgcolor="#FFFFFF">
			  
			    <div align="center">
			      <?php 
			 $sql="select *  from tb_user where id='".$rows['userid']."'";
			 $res=mysql_query($sql,$conn);
			 $rew=mysql_fetch_array($res);
			 echo unhtml($rew['usernc']);
			 ?>			  
			      </div></td>
              <td bgcolor="#FFFFFF"><div align="center"><?php echo $rows['createtime'];?></div></td>

              <td bgcolor="#FFFFFF">
			    <div align="center">
			      <?php 
			  $sqls="select *  from tb_replyword where leave_id='".$rows['id']."'";
			 $rs=mysql_query($sqls,$conn);
			 $rw=mysql_num_rows($rs);
			  echo $rw;
			  ?>			  
			      </div></td>
              <td bgcolor="#FFFFFF"><div align="center"><a href="javascript:if(window.confirm('确定删除该留言信息么？')==true){window.location.href='deleteleaveword.php?del_id=<?php echo $rows['id']; ?>';}">删除</a></div></td>
			
            </tr>
			<?php 
			}                                              
			
			?>




          </table>
          <p align="right" style="font-size: 14px; color: #000000"><a href="gllogout.php">退出</a></p>
        </div>
      </form>
			&nbsp;</p></td>
        </tr>
      </table>
	   </p>
	   <table width="550" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
         <tr>
           <td width="351"><div align="left">共有留言&nbsp;<?php echo $totalNum;?>&nbsp;条&nbsp;每页显示&nbsp;<?php echo $pagesize;?>&nbsp;条&nbsp;第&nbsp;<?php echo $page;?>&nbsp;页/共&nbsp;<?php echo $pagecount;?>&nbsp;页</div></td>
           
           <td width="199"><div align="right"><a href="<?php echo $_SERVER["PHP_SELF"]?>?page=1" class="a1">首页</a>&nbsp;
		   
		   <a href="<?php echo $_SERVER["PHP_SELF"]?>?page=<?php 
		 if($page>1) 
		  
		   echo $page-1;
		  else
		   echo 1;  
		   ?>" class="a1">上一页</a>
		   
		   &nbsp;<a href="<?php echo $_SERVER["PHP_SELF"]?>?page=<?php 
		 if($page<$pagecount) 
		  
		   echo $page+1;
		  else
		   echo $pagecount;  
		   ?>" class="a1">下一页&nbsp;</a>
		   
		   <a href="<?php echo $_SERVER["PHP_SELF"]?>?page=<?php echo $pagecount;?>" class="a1">尾页</a></div></td>
         </tr>
       </table>
    </td>
    <td width="5" bgcolor="#FAF3CE"></td>
  </tr>
</table>
      </td>
<td width="5" bgcolor="#FAF3CE"></td>
  </tr>
</table>

</div><!--main结束-->




</div>
</body>
</html>