<div class="alert alert-info" role="alert" align="center">
  <h3>在这里你可以搜索留言的主题，内容，以及留言者的姓名。</h3>
</div>
<div align="center" >
<table >
       <form  class="form-inline" name="form1" method="post" action="index.php?id=<?php echo urlencode("查询留言");?> " onsubmit="return chkinput_search(this)">
                 
				 <tr>
				 <td>
                  <select name="type"   class="form-control">
				    <option value="">请选择</option>
                    <option value="1">主题</option>
                    <option value="2">内容</option>
                    <option value="3">留言者</option>
                  </select>
				  </td><td>&nbsp;</td>
				 <td><input type="text" name="keyword"  class="form-control"></td>
				  <td><button class="btn " type="submit" value="查询" class="buttoncss" name="submit"><img src="image/search.png" height="20px"></button></td>
				  </tr>
				  
		</form>
		</table>
			 <script language="javascript">
	   function chkinput_search(form){
	     if(form.type.value==""){
		   alert('请选择查询条件！');
		   form.type.focus();
		   return(false);
		 }
		  if(form.keyword.value==""){
		   alert('请输入查询关键字！');
		   form.keyword.focus();
		   return(false);
		 }
		 return(true);
	   
	   } 
	 </script>

	  <?php
	    
	   if(isset($_POST["submit"])){
	     $type=$_POST["type"];
	     $keyword=$_POST["keyword"];
		 if($type==1){
		  $sql=mysql_query("select * from tb_leaveword where title like '%".$keyword."%'",$conn);
		 }elseif($type==2){
		  $sql=mysql_query("select * from tb_leaveword where content like '%".$keyword."%'",$conn);
		 }elseif($type==3){
		  $sql0=mysql_query("select id from tb_user where usernc='".$keyword."'",$conn);
		  $info0=mysql_fetch_array($sql0);
		  $sql=mysql_query("select * from tb_leaveword where userid='".$info0["id"]."'",$conn);
		 }
		 
		 $info=mysql_fetch_array($sql);
		 if($info==false){
		  echo "<br><br><div align=center>对不起，没有查找到您要查找的内容！</div>";
		 }else{
		   do{
	  ?>
	  
	 
              
<table>
<tr>
<td>
<?php 
$sql1=mysql_query("select usernc,face,ip,email,qq from tb_user where id='".$info["userid"]."'",$conn);
$info1=mysql_fetch_array($sql1); 
if($info1["face"]=="" && $info1["usernc"]==""){
?>
<img src="images/gly.gif" height="40px"  />&nbsp;&nbsp;&nbsp;&nbsp;管理员
<?php 
}else{
?>				
<img src="<?php echo $info1["face"]; ?>" height="40px"  />&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $info1["usernc"];?>
<?php 
}
?>	</td>
</tr><tr><td>
        <span><strong>主题：</strong><?php echo unhtml($info["title"]);?> </td></tr>
		<tr>
		<td>
          <strong>内容：</strong><?php echo unhtml($info["content"]);?></span></td></tr>    <hr>
	  <?php
	     }while($info=mysql_fetch_array($sql));
		 } 
	   }
	  
	  ?>
</table>	  
	</div>  
 <hr>