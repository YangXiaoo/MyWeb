<?php

if(isset($_SESSION['unc'])){
	$user=$_SESSION['unc'];
}else{
	$user="";
}
if(isset($_SESSION['userword'])){
	$adm=$_SESSION['userword'];
}else{
	$adm="";
}
             $category=$_GET["category"];
			 $sql=mysql_query("select count(*) as total from tb_leaveword where category='".$_GET["category"]."'",$conn);
			 $info=mysql_fetch_array($sql);
			 $total=$info['total'];
			 if($total==0){
			  echo "<div align=center>对不起，暂无留言！</div>";
			 }else{
			   if(!isset($_GET["page"]) || !is_numeric($_GET["page"])){
			      $page=1; 
			   }else{
			      $page=intval($_GET["page"]);
			   }
			   $pagesize=6;
			   if($total%$pagesize==0){
			      $pagecount=intval($total/$pagesize);
			   }else{
			      $pagecount=ceil($total/$pagesize);
			   }
			   $sql=mysql_query("select * from tb_leaveword where category='".$_GET["category"]."' order by createtime desc limit ".($page-1)*$pagesize.",$pagesize  ",$conn);
			   while($info=mysql_fetch_array($sql)){ 
	     ?>

	 <table  cellpadding="0" cellspacing="0">
	 <tr>
	 <td >
		 <?php 
				$sql1=mysql_query("select usernc,face,ip,email,qq,id from tb_user where id='".$info["userid"]."'",$conn);
				$info1=mysql_fetch_array($sql1); 
				if($info1["face"]=="" && $info1["usernc"]==""){
				?>
				<img src="images/gly.gif" height="40px" />&nbsp;&nbsp;<strong>管理员</strong>
				<?php 
				}else{
				?>
			<a href="index.php?id=<?php echo urlencode("个人信息")?>&userid=<?php echo $info1["id"]?>">	<img src="<?php echo $info1["face"]; ?>" height="40px"/>&nbsp;&nbsp;<strong> <?php echo $info1["usernc"];?></strong></a>&nbsp;&nbsp;<?php echo "<small> ".str_replace("-","/",substr($info['createtime'],2,8))."</small>";?>
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
 &nbsp;&nbsp;&nbsp;&nbsp; <a href="index.php?ed=<?php echo $info['id'];?> & id=<?php echo urlencode("编辑留言");?>"><img src="image/editor.png" height="20px"></a>
<?php
	}else{
?>
   <!-- 编辑 -->   
<?php
}
?>

 <!--按钮出现菜单 <a  data-toggle="collapse" href="#collapseExample2" aria-expanded="false" aria-controls="collapseExample">
  &circ;
</a>

<span class="collapse" id="collapseExample2">
  
<a href="index.php?ed=<?php echo $info['id'];?> & id=<?php echo urlencode("编辑留言");?>">&nbsp;&nbsp;<small>编辑</small></a>
  
</span> -->



				</td>
				</tr></table><br>
<!-- 消息编辑 结束 -->
<table cellpadding="0px" cellspacing="0px">
<!-- <tr><td>&nbsp;</td></tr> -->

<tr>
<td>

			
          <strong>主题：</strong><?php echo unhtml($info["title"])?><!--message content -->
		 </td>
		 </tr>

		 <tr>
		 <td>
<strong>内容：</strong><?php echo unhtml($info["content"])?>
        </td>
		</tr>

</table>
<div align="right">

	
		


<?php 
$sqlu=mysql_query("select usernc from tb_user where id='".$info['userid']."'",$conn);
$infou=mysql_fetch_array($sqlu);
//浏览记录
$lookt=$info["look"];
?>
			   <img src="image/visible.png" height="20px"><small><?php echo $lookt?></small>


			   &nbsp;&nbsp; <a href="index.php?l_id=<?php echo $info['id']; ?>&id=<?php echo urlencode('详细信息'); ?>"><img src="image/more.png" height="20px"/> </a>

</div>
<hr>			 


	   <?php

		  }
		?>
			
			
	  
	   <div class="row" id="page">

           <div class="col-sm-7"><div>共&nbsp;<?php echo $total;?>&nbsp;条&nbsp;每页&nbsp;<?php echo $pagesize;?>&nbsp;条&nbsp;第&nbsp;<?php echo $page;?>&nbsp;页/共&nbsp;<?php echo $pagecount;?>&nbsp;页</div></div>
           
           <div class="col-sm-4 col-sm-offset-1"><div><a href="<?php echo $_SERVER[’REQUEST_URI’]?>?page=1&category=<?php echo $_GET['category']?>&id=<?php  echo urlencode($_GET['id'])?>" class="a1">首页</a>&nbsp;<a href="<?php echo $_SERVER[’REQUEST_URI’]?>?page=<?php 
		 if($page>1){ 
		  
		   echo $page-1;
		  }else{
		   echo 1; 
		   } 
		   ?>&category=<?php echo $_GET['category']?>&id=<?php echo urlencode($_GET['id'])?> " class="a1">上一页</a>&nbsp;<a href="<?php echo $_SERVER[’REQUEST_URI’]?>?page=<?php 
		 if($page<$pagecount){
		  
		   echo $page+1;
		  }else{
		   echo $pagecount;
		   }  
		   ?>&category=<?php echo $_GET['category']?>&id=<?php echo urlencode($_GET['id'])?>" class="a1">下一页</a> &nbsp;<a href="<?php echo $_SERVER[’REQUEST_URI’]?>?page=<?php echo $pagecount;?>&category=<?php echo $_GET['category']?>&id=<?php echo urlencode($_GET['id'])?>" class="a1">尾页</a></div></td>
         </div>
       </div>
	<?php
	  }
	?>   
	   