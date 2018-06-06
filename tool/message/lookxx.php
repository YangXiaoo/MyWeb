<?php 
//判断当前登录用户
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
//判断结束

//判断是否得到page参数
if(!isset($_GET["page"]) || !is_numeric($_GET["page"])){
	$page=1; 
}else{
	$page=intval($_GET["page"]);
}	

?>

<?php 
$sqls=mysql_query("select * from tb_leaveword where id='".$_GET['l_id']."'",$conn); //查询当前留言的信息，l_id为查看留言是返回的参数,留言信息的id
$infoes=mysql_fetch_array($sqls); //$infoes为每条留言的数组
$looktotal=mysql_query("update tb_leaveword set look=look+1 where id='".$_GET['l_id']."'",$conn);
$sql1=mysql_query("select id,usernc,face,ip,email,qq from tb_user where id='".$infoes["userid"]."'",$conn);//查询留言者的个人信息
$sqlqq=mysql_query("select id,usernc,face,ip,email,qq from tb_user where usernc='$user'",$conn);
$my=mysql_fetch_array($sqlqq);
$info1=mysql_fetch_array($sql1); 

//留言总数
$sqlr=mysql_query("select count(*) as total from tb_replyword where leave_id='".$_GET['l_id']."'",$conn);
$retotal=mysql_fetch_array($sqlr);
$reto=$retotal['total'];
mysql_query("update tb_leaveword set re='$reto' where id='".$_GET['l_id']."'",$conn);
//end



//该主题发布者浏览过后回复的标签变为0，即已经查看回复
$ret=0;
if($infoes["userid"]==$my["id"]){
    mysql_query("update tb_replyword set look='$ret' where leave_id='".$infoes["id"]."'",$conn);
}



//浏览历史记录
if($user!=""){
$date=date("Y-m-d H:i:s");
$look=mysql_query("select * from tb_lookhistory where leavewordid='".$_GET['l_id']."' and userid='".$my["id"]."'",$conn);

if(mysql_num_rows($look)==1){
$history=mysql_query("update tb_lookhistory set data='$date' where leavewordid='".$_GET['l_id']."' and userid='".$my["id"]."'",$conn);
}else{
	mysql_query("insert into tb_lookhistory (userid,data,leavewordid) values('".$my["id"]."','$date','".$_GET['l_id']."')",$conn);
//end
}
}?>
<table cellpadding="0px" cellspacing="0px">
    <tr>
        <td>


<!-- 留言头像和名称 -->
<?php 
$sql1=mysql_query("select usernc,face,ip,email,qq,id from tb_user where id='".$infoes["userid"]."'",$conn);
$info1=mysql_fetch_array($sql1); 
if($info1["face"]=="" && $info1["usernc"]==""){
?>


<!-- 管理员的数据库没有usernc和face信息，所以为空 -->
<img src="images/gly.gif" height="40px" />&nbsp;&nbsp;管理员
<?php 
}else{
?>				
        <a href="index.php?id=<?php echo urlencode("个人信息")?>&userid=<?php echo $info1["id"]?>">
		<img src="<?php echo $info1["face"]; ?>" height="40px"/>
		&nbsp;&nbsp;<?php echo $info1["usernc"];?>
		</a>&nbsp;&nbsp;<?php echo "<small> ".str_replace("-","/",substr($infoes['createtime'],2,8))."</small>";?>
<?php 
}
?>


    </td>
    <td align="right">
<!-- 判断当前用户是否可以进行编辑 -->
<?php 
//$adm已经判断是否存在，非空则当前登录用户为管理员
$adms=mysql_query("select id from tb_adm where userword='".$adm."'",$conn);
$re=mysql_fetch_array($adms);//$re为管理员信息
if($re['id']==$infoes['userid'] && $adm!=""){
?>

<!-- 管理员状态，以个人权限进行编辑 -->
    &nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php?ed=<?php echo $infoes['id'];?> & id=<?php echo urlencode("编辑留言");?>"><img src="image/editor.png" height="20px"></a>
<?php
}//为当前信息的发布者
elseif($user== $info1['usernc'] and $user!=""){ 
?>
         &nbsp;&nbsp;&nbsp;&nbsp; <span> <a href="index.php?ed=<?php echo $infoes['id'];?> & id=<?php echo urlencode("编辑留言");?>">
		 <img src="image/editor.png" height="20px"></a></span>
<?php
}
?>

         </td>
    </tr>
</table><br>
<table cellspacing="0px" cellpadding="0px">
    <tr>
          <td>
           <strong>主题：</strong>
		               <?php echo unhtml($infoes["title"]);?></td>

   </tr>
   <tr>
          <td>
              <strong>内容：</strong>
                         <?php echo unhtml($infoes["content"]);?>

          </td>
   </tr>
</table>

<!-- 回复、浏览、查看更多的标签 -->
<div align="right">
<?php
 $sqllook=mysql_query("select * from tb_leaveword where id='".$_GET['l_id']."'",$conn);
			   $looks=mysql_fetch_array($sqllook);
			   $lookt=$looks["look"];

?>
			   <img src="image/visible.png" height="20px"><small><?php echo $lookt?></small>&nbsp;&nbsp; 


<!-- 对当前用户的权限进行判断 -->
<?php
if($adm!=""){ 
?>
	<button id="replya" type="button"  class="btn btn-primary btn-xs"  >回复</button>
<?php
}else if($user!= $info1['usernc'] and $user!=""){ 
?>
	<button id="replya" type="button"  class="btn btn-primary btn-xs"  >回复</button>
<?php 
}
?>	 	 	
</div>


		   <script >
		     function chkinputa(form){
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
$(document).ready(function(){
   $("#replyashow").hide();
  $("#replya").click(function(){
    $("#replyashow").show();
    $("#replya").hide();
  });
  $("#replyacan").click(function(){
    $("#replyashow").hide();
	$("#replya").show();
  });
});

		   
		   </script>
	<div align="center" id="replyashow">	  
            
             <form name="form1" method="post" action="savereply.php" onSubmit="return chkinputa(this)">
			 
             
                <input type="text" name="title"  class="form-control" placeholder="标题">
                  <input type="hidden" name="t_id" value="<?php echo $infoes["id"];?>" /><br>
                <textarea name="content" rows="10" class="form-control">回复:</textarea><br>
			<div align="right"><input type="submit"  name="submit" value="回复" class="btn btn-info"onclick="return chkinput(form1);">
                &nbsp;<input type="reset" name="reset" value="取消" id="replyacan" class="btn btn-info"></div>
            
			  </form>

</div>



			
			
<!-- <div class="modal fade" id="reply" tabindex="-1" role="dialog" aria-labelledby="login">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <div align="center"><h4 class="modal-title" >回复</h4></div>
      </div>


<div class="modal-body">
  <script language="javascript">
		     function chkinput(form1){
			  if(form1.title.value==""){
			    alert("请输入回复主题！");
			    form1.title.focus();
				return false;
			  }
			  
			  if(form1.content.value==""){
			    alert("请输入回复内容！");
				form1.content.focus();
				return false;
			  }
			  return true;
			 }
		   
		   </script>

<div class="row">
<div class="col-sm-12">
             <form name="form1" method="post" action="savereply.php" onSubmit="return chkinput(this)"> 
                  <input type="text" name="title"  class="form-control" placeholder="主题">
                  <input type="hidden" name="t_id" value="<?php echo $infoes['id'];?>" /><br>
                <textarea name="content" rows="15" class="form-control">回复<?php 
				if(isset($_GET['loor'])){
					$loor=$_GET['loor'];
					echo $loor."楼：";
				}else{	
					$loor="留言：";
					echo $loor;
				}
				?></textarea><br>
				
			<div align="right">

               <input type="submit"  name="submit" value="回复" class="btn btn-info"onclick="return chkinput(form1);">
                &nbsp;<input type="reset" name="reset" value="重写" class="btn btn-info">
              </div>
			
			  </form>
            </div>
			</div>


      </div>
    </div>
  </div>
  </div> -->






<hr>
 <!-- 结束主消息 -->





<?php
$sql=mysql_query("select count(*) as total from tb_replyword where leave_id='".$_GET['l_id']."'",$conn);
$infos=mysql_fetch_array($sql);
$total=$infos['total'];
if($total==0){
	echo "<div align=center>对不起，暂无回复！</div>";
    }else{
	$pagesize=5;
	if($total%$pagesize==0){
		$pagecount=intval($total/$pagesize);
	}else{
		$pagecount=ceil($total/$pagesize);
	}		   
	$i= $total-($page-1)*$pagesize+1;  //$i表示一共有多少条消息
	//查询回复的内容 leave_id为信息的留言标签
	$sql=mysql_query("select * from tb_replyword where leave_id='".$_GET['l_id']."' order by id desc limit ".($page-1)*$pagesize.",$pagesize  ",$conn);
	while($info=mysql_fetch_array($sql)){
		--$i;
		$sqlreply=mysql_query("select usernc,face,ip,email,qq from tb_user where id='".$info["userid"]."'",$conn);
		$inforeply=mysql_fetch_array($sqlreply); 
?>

<!-- 回&nbsp;&nbsp;复：<?php echo unhtml($info["titles"]);?>    -->           <!-- //回复的标题 -->
            
<!--判断当前用户是否可以进行编辑-->
<?php
$admes=mysql_query("select id from tb_adm where userword='".$adm."'",$conn);
$res=mysql_fetch_array($admes);
?>


<div id="rep-<?php echo $i?>">
<table cellspacing="0px" cellpadding="0px" class="responsive">
     <tr>
<?php 
$sqlr=mysql_query("select usernc,face,ip,email,qq,id from tb_user where id='".$info["userid"]."'",$conn);
$infor=mysql_fetch_array($sqlr); 
if($infor["face"]=="" && $infor["usernc"]==""){
?>

         <td align="right">
             <img src="images/gly.gif" height="40px" />
         </td>
         <td>&nbsp;&nbsp;
              <strong>管理员</strong>（<?php echo $i;?>楼）
         </td>

<?php 
}else{
?>	
         <td align="right">			
             <img src="<?php echo $infor["face"]; ?>" height="40px"/></td><td>&nbsp;&nbsp;<a href="index.php?id=<?php echo urlencode("个人信息")?>&userid=<?php echo $infor["id"]?>"> <strong><?php echo $infor["usernc"];?></strong></a>（<?php echo $i;?>楼）<br>
             <small>&nbsp;&nbsp; <?php echo "".str_replace("-","/",substr($info['createtimes'],2,8)).""?></small>
         </td>
<?php 
}
?>
    <tr>
         <td>
         </td>
         <td>
	         <?php echo unhtml($info["contents"]);?>
         </td>
    </tr>
</table>
<div align="right">
<?php
if($adm!=""){ 
?>
&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php?t_id=<?php echo $_GET['l_id']; ?>&loor=<?php echo $i;?>&id=<?php echo urlencode("回复留言")?>"><img src="image/repl.png" height="20px"></a>


<?php
}else if($user!= $inforeply['usernc'] and $user!=""){ 
?>
&nbsp;&nbsp;&nbsp;&nbsp;	<button  id="reply-<?php echo $i?>" type="button"  class="btn btn-primary btn-xs" >回复</button>


		   <script >
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
$(document).ready(function(){
   $("#reshow-<?php echo $i?>").hide();
  $("#reply-<?php echo $i?>").click(function(){
    $("#reshow-<?php echo $i?>").show();
    $("#reply-<?php echo $i?>").hide();
  });
  $("#recan-<?php echo $i?>").click(function(){
    $("#reshow-<?php echo $i?>").hide();
	$("#reply-<?php echo $i?>").show();
  });
});

		   
		   </script>
	<div align="center" id="reshow-<?php echo $i?>">	  
            
             <form name="form1" method="post" action="savereply.php" onSubmit="return chkinput(this)">
			 
             
                <input type="text" name="title"  class="form-control" placeholder="标题">
                  <input type="hidden" name="t_id" value="<?php echo $infoes["id"];?>" /><br>
             
				
                <textarea name="content" rows="10" class="form-control">回复<?php 
				echo $i
				?>楼：</textarea><br>
			<div align="right"><input type="submit"  name="submit" value="回复" class="btn btn-info"onclick="return chkinput(form1);">
                &nbsp;<input type="reset" name="reset" value="取消" id="recan-<?php echo $i?>" class="btn btn-info"></div>
            
			  </form>

</div>

















<?php 
}
?>



<!-- 留下言论的用户和管理员可以删除回复 -->

<?php
if($adm!=""){ 
?>
&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:deleteReply(<?php echo $info['id']; ?>)"><img src="image/dele.png" height="20px"></a>
<?php 
}elseif($user==$inforeply['usernc'] and $user!="" ){

?>	  
&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:deleteReply(<?php echo $info['id']; ?>)"><img src="image/dele.png" height="20px"></a>
<?php 
}
?>
<script type="text/javascript">
    function deleteReply(id){
	   if(window.confirm('确认删除该留言信息？')==true){
         var url="deletereplyword.php";
		 var data={"l_id":id};
		 var success = function(response){
		     if(response.errno==0){
				  alert('o(╥﹏╥)o，删除失败');
			 }else {
				 window.history.go(0);
				   alert('w(ﾟДﾟ)w，已经删除');
			 }
		 }
		 $.post(url, data, success, "json");
}
}
</script>




</div>
</div>
<hr>



<?php 
}
?>






<div class="row" id="page">
 <div class="col-sm-7">
        <div>共有留言&nbsp;<?php echo $total;?>&nbsp;条&nbsp;每页显示&nbsp;<?php echo $pagesize;?>&nbsp;条&nbsp;第&nbsp;<?php echo $page;?>&nbsp;页/共&nbsp;<?php echo $pagecount;?>&nbsp;页</div>
		</div>

		<div class="col-sm-4 col-sm-offset-1">
       
		   <a href="index.php?page=1&l_id=<?php echo $_GET['l_id'];?> & id=<?php echo urlencode($_GET['id']); ?>" class="a1">首页</a>&nbsp;
		   <a href="index.php?page=<?php 
		 		if($page>1) 
		   			echo $page-1;
		  		else
		   			echo 1;  
		   ?>&l_id=<?php echo $_GET['l_id'];?> & id=<?php echo urlencode($_GET['id']); ?>" class="a1">上一页</a>&nbsp;
		  <a href="index.php?page=<?php 
		 		if($page<$pagecount) 
				   echo $page+1;
		  		else
		   			echo $pagecount;  
		   ?>&l_id=<?php echo $_GET['l_id'];?> & id=<?php echo urlencode($_GET['id']); ?>" class="a1">下一页</a>&nbsp;
		   <a href="index.php?page=<?php echo $pagecount;?>&l_id=<?php echo $_GET['l_id'];?> & id=<?php echo urlencode($_GET['id']); ?>" class="a1">尾页</a>
		   </div>
         </div>
<?php 
 }
?>