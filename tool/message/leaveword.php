<?php
	if(isset($_SESSION['unc']) or isset($_SESSION['userword'])){			//用户或管理员登录后才可以发表留言
		$select=mysql_query("select * from tb_category where authorization='1'",$conn);
		$name=$_SESSION['unc'];
?>
<div align="center">
         <div class="alert alert-warning" role="alert">
  <h4>发表内容请注意言辞，你可以选择板块或者创建一个板块。创建的板块需要等待管理员审核。</h4>
</div>
</div><br>
        
<div class="row">  
<div class="col-sm-12">
		   <script language="javascript">
		     function chkinput(form){
			  if(form.title.value==""){
			    alert("请输入留言主题！");
			    form.title.focus();
				return false;
			  }
			  if(form.title.value.length>100){
			    alert("主题数超过100！");
			    form.title.focus();
				return false;
			  }
			  
			  if(form.content.value==""){
			    alert("请输入留言内容！");
				form.content.focus();
				return false;
			  }
              if(form.content.value.length>500){
			    alert("字数超过500");
				form.content.focus();
				return false;
			  }

			  if(form.category.value=="" &&form.category1.value==""){
			      alert("请选择板块或创建板块！");
				  form.category.focus();
				  return false;
			  }
			  if(form.category.value!="" && form.category1.value!="" ){
			      alert("只能选择一个板块或创建一个板块！");
				  form.category1.focus();
				  return false;
			  }
			 
			  return true;
			 }
		   
		    </script>


		  <form name="form" method="post" action="saveleaveword.php" onSubmit="return chkinput(this)">
           
            <strong>选择板块：</strong>
			 <select name="category"class="form-control">
			 <option value="">选择</option>
			 <?php
			while($array=mysql_fetch_array($select)) {
			?>
			<option value="<?php echo $array['category']?>"><?php echo $array['category']?></option>
			<?php  }
			?>
			</select><br>
			
            <strong>或创建板块：</strong>
             <input type="text" name="category1" class="form-control"><br>
                <input type="text" name="title"  class="form-control" placeholder="主题">
                <input type="hidden" name="userid"/><br>

                <textarea name="content" rows="12" class="form-control" placeholder="内容"></textarea><br>
				<div align="right">
                <input type="submit"  name="submit" value="发表" class="btn btn-info" onclick="return chkinput(form1);">&nbsp;&nbsp;&nbsp;<input type="reset" name="reset" value="重写" class="btn btn-info">
                </div>
			  </form>
			  </div>
			  </div>
          			
<hr>
<?php
}else{
	echo "<script>alert('登录后发表！');history.back();</script>";
}
?>