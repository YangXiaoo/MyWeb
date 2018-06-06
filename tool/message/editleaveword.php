<?php
include_once("conn/conn.php");
echo "<meta http-equiv='Content-Type' content='text/html'; charset='utf-8'>";
if(isset($_POST["submit"])){
  if(mysql_query("update tb_leaveword set title='".$_POST["title"]."',content='".$_POST["content"]."' where id='".$_POST["id"]."'",$conn)){
  $url=urlencode("首页");
	  echo "<script>alert('编辑成功');history.go(-2);</script>";    
	   
  }else{
	  echo "<script>alert('编辑失败');window.location.href=document.referrer;</script>";    
  }
 exit;
}
$id=$_GET["ed"];
$sql=mysql_query("select * from tb_leaveword where id='".$id."'",$conn);
$info=mysql_fetch_array($sql);
?>



<div class="alert alert-success" role="alert">
  <h4>编辑内容，注意主题不要超过100字，内容不要超过500字</h4>
</div>


<div align="center" class="row">
<div class="col-sm-12">
 
 <script language="javascript">
  function chkinput(form){
    if(form.title.value==""){
	
	  alert("留言主题不能为空！");
	  form.title.focus();
	  return(false);
	
	}
	 if(form.title.value.length>100){
	
	  alert("留言主题字数超过100了");
	  form.title.focus();
	  return(false);
	
	}
	
	 if(form.content.value==""){
	  alert("留言内容不能为空！");
	  form.content.focus();
	  return(false);
	}
	if(form.content.value.length>500){
	  alert("留言内容字数超过限制！");
	  form.content.focus();
	  return(false);
	}
   return(true);
  
  }
 
 </script>
 
 <form name="form1" method="post" action="editleaveword.php" onSubmit="return chkinput(this)">
 

    <div align="left"><strong>主题：</strong>&nbsp;</div>
    <input name="title" type="text" class="form-control" value="<?php echo $info['title'];?>">
 
  
	
  
    <div align="left"><strong>内容：</strong></div>
<textarea name="content" rows="12" class="form-control"><?php echo $info['content'];?></textarea><br>
 <div align="right">
 <input type="hidden" name="id" value="<?php echo $_GET['ed'];?>"><input type="submit" value="编辑" class="btn btn-info" name="submit">&nbsp;&nbsp;<input type="reset" value="取消" class="btn btn-info" id="cancel">
  </div>
  </form>
  <script>
$(document).ready(function(){
    $("#cancel").click(function(){
	    history.go(-1);
	});
});

  </script>
</div>

</div>