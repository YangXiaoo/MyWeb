<?php
if(isset($_SESSION['unc'])){
	$user=$_SESSION['unc'];
}else{
	$user="";
}
$sqlq=mysql_query("select * from tb_user where usernc='$user'",$conn);
$myinfo = mysql_fetch_array($sqlq);
//查询获得浏览量
$sql=mysql_query("select count(*) as total from tb_lookhistory where userid='".$myinfo["id"]."'",$conn);
$history=mysql_fetch_array($sql);
$total=$history['total'];
if($total==0){
echo "<div align=center>╭(╯^╰)╮，你居然都不去看看别人说了什么，快去快去！</div>";
}else
	{
               if(!isset($_GET["page"]) || !is_numeric($_GET["page"])){
			      $page=1; 
			   }else{
			      $page=intval($_GET["page"]);
			   }
			   $pagesize=5;
			   if($total%$pagesize==0){
			      $pagecount=intval($total/$pagesize);
			   }else{
			      $pagecount=ceil($total/$pagesize);
			   }
			   $sql=mysql_query("select * from tb_lookhistory where userid='".$myinfo["id"]."' order by data desc limit ".($page-1)*$pagesize.",$pagesize",$conn);
			   while($info=mysql_fetch_array($sql)){ 
?>
	 <table  cellpadding="0" cellspacing="0">
	 <tr>
	 <td >
		 <?php 
		        $sqlw=mysql_query("select * from tb_leaveword where id='".$info["leavewordid"]."'",$conn);
				$infow=mysql_fetch_array($sqlw); 
				$sql1=mysql_query("select usernc,face,ip,email,qq,id from tb_user where id='".$infow["userid"]."'",$conn);
				$info1=mysql_fetch_array($sql1); 
				if($info1["face"]=="" && $info1["usernc"]==""){
				?>
				<img src="images/gly.gif" height="40px" />&nbsp;&nbsp;<strong>管理员</strong>&nbsp;&nbsp;<small>浏览时间：<?php echo $info["data"]?></small>
				<?php 
				}else{
				?>
			<a href="index.php?id=<?php echo urlencode("个人信息")?>&userid=<?php echo $info1["id"]?>">	
			<img src="<?php echo $info1["face"]; ?>" height="40px"/>&nbsp;&nbsp;
			<strong> <?php echo $info1["usernc"];?></strong></a>&nbsp;&nbsp;
			<small>浏览时间：<?php echo $info["data"]?></small>
				<?php 
				}
				?>
    </td>
              <td align="right">
<!-- 消息编辑 -->               
				<?php
				$adms = mysql_query("select id from tb_adm where userword='".$adm."'",$conn);
				$re = mysql_fetch_array($adms);
				if($re['id'] == $info['userid'] && $adm!="")
				   {
					?>
				   &nbsp;&nbsp;&nbsp;&nbsp; <a href="index.php?ed=<?php echo $info['id'];?> & id=<?php echo urlencode("编辑留言");?>"> <img src="image/editor.png" height="20px"></a>
				<?php
				}elseif($user== $info1['usernc'] and $user!=""){       //如果当前用户是该留言的发表者，则显示编辑按钮
?>
 &nbsp;&nbsp;&nbsp;&nbsp; <a href="index.php?ed=<?php echo $infow['id'];?> & id=<?php echo urlencode("编辑留言");?>"><img src="image/editor.png" height="20px"></a>
<?php
	}else{
?>
   <!-- 编辑 -->   
<?php
}
?>
		</td>
		</tr>
		</table>
<!-- 消息编辑 结束 -->





<table cellpadding="0px" cellspacing="0px">
<!-- <tr><td>&nbsp;</td></tr> -->

<tr>
<td>
<!-- 留言内容 -->		
          <strong>主题：</strong><?php echo unhtml($infow["title"])?><!--message content -->
		 </td>
		 </tr>
		 <tr>
		 <td>
         <strong>内容：</strong><?php echo unhtml($infow["content"])?>
        </td>
		</tr>

</table>
<div align="right">

<!-- 留言内容结束 -->
<!-- 对留言进行编辑-->
			   &nbsp;&nbsp; <a href="index.php?l_id=<?php echo $infow['id']; ?>&id=<?php echo urlencode('详细信息'); ?>"><img src="image/more.png" height="20px"/> </a>
</div>
<hr>
	   <?php

		  }
		?>				  
	   <div class="row" id="page">
           <div class="col-sm-7"><div>共&nbsp;<?php echo $total;?>&nbsp;条&nbsp;每页&nbsp;<?php echo $pagesize;?>&nbsp;条&nbsp;第&nbsp;<?php echo $page;?>&nbsp;页/共&nbsp;<?php echo $pagecount;?>&nbsp;页</div></div>       
           <div class="col-sm-4 col-sm-offset-1"><div><a href="<?php echo $_SERVER[’REQUEST_URI’]?>?page=1&id=<?php  echo urlencode($_GET['id'])?>" class="a1">首页</a>&nbsp;<a href="<?php echo $_SERVER[’REQUEST_URI’]?>?page=<?php 
		 if($page>1){   
		   echo $page-1;
		  }else{
		   echo 1; 
		   } 
		   ?>&id=<?php echo urlencode($_GET['id'])?> " class="a1">上一页</a>&nbsp;<a href="<?php echo $_SERVER[’REQUEST_URI’]?>?page=<?php 
		 if($page<$pagecount){
		  
		   echo $page+1;
		  }else{
		   echo $pagecount;
		   }  
		   ?>&id=<?php echo urlencode($_GET['id'])?>" class="a1">下一页</a> &nbsp;<a href="<?php echo $_SERVER[’REQUEST_URI’]?>?page=<?php echo $pagecount;?>&id=<?php echo urlencode($_GET['id'])?>" class="a1">尾页</a></div></td>
         </div>
       </div>
	<?php
	  }
	?>   







