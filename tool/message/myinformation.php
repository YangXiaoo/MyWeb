<?php 
    $usernc=$_SESSION['unc'];
    $sql=mysql_query("select * from tb_user where usernc='$usernc'",$conn);
	$info = mysql_fetch_array($sql);
?>
<div align="center">
    <img src="<?php echo $info['face']?>"  width="60px">
</div><br>

<div>
    <table class="table table-hover " cellpadding="0px" cellspacing="0px" >
	<tr>
	    <td>姓名</td>
		<td align="center"><span id="na"><?php echo $info['usernc']?></span></td>
		<td align="right"><button type="button" class="btn btn-primary btn-xs" id="name1">编辑</button></td>
		</tr>
	

		
		
	<tr id="nameshow">
		<td></td>
		<td>
		<form action="infoedit.php" method="POST" name="form1" id="namef" onSubmit="return checkinput1(this)"><span id="nameinfo"></span>
		<input type="text" class="form-control" name="name" placeholder="<?php echo $info['usernc']?>"><br>
		<div align="right">
		<button name="submit" type="submit" class="btn btn-info" value="提交">提交</button>&nbsp;&nbsp;
		<input type="button" class="btn btn-info" id="namecan" value="取消">
		</div>
		</form>
			<script>
  $(document).ready(function(){
   $("#nameshow").hide();
  $("#name1").click(function(){
    $("#nameshow").show();
    $("#na").hide();
	$("#name").hide();
  });
  $("#namecan").click(function(){
    $("#nameshow").hide();
	$("#name").show();
	$("#na").show();
  });
});

function checkinput1(form1){
    if(form1.name.value==""){
	    document.getElementById("nameinfo").innerHTML="请输入";
		form1.name.focus();
			return false;
	}else{
	 return true;
	}
}
</script>	
		</td>
		<td></td>
	</tr>
	



	<tr>
	    <td>邮箱</td>
		<td align="center"><span id="emaila"><?php echo $info['email']?></span></td>
		<td align="right"><button type="button" class="btn btn-primary btn-xs" id="email" >编辑</button></td>
		</tr>
	<tr>



	<tr id="emailshow">
		<td></td>
		<td>
		<form action="infoedit.php" method="POST" name="form2" id="emailf" onSubmit="return checkinput2(this)"><span id="emailinfo"></span>
		<input type="text" class="form-control" name="email" placeholder="<?php echo $info['email']?>"><br>
		<div align="right">
		<button name="submit" type="submit" class="btn btn-info" value="提交">提交</button>&nbsp;&nbsp;
		
		<input  class="btn btn-info" id="emailcan" type="button" value="取消">
		</div>
		</form>
		</td>
	<script>
  $(document).ready(function(){
	  $("#emailshow").hide();
  $("#email").click(function(){
    $("#emailshow").show();
    $("#emaila").hide();
	$("#email").hide();
  });
  $("#emailcan").click(function(){
    $("#emailshow").hide();
	$("#email").show();
	$("#emaila").show();
  });
}); 
function checkinput2(form2){
    if(form2.email.value==""){
	    document.getElementById("emailinfo").innerHTML="请输入";
		form2.email.focus();
			return false;
	}else{
	 return true;
	}
}
</script>
<td></td>
	</tr>


	<tr>
	    <td>qq号</td>
		<td align="center"><span id="qqa"><?php echo $info['qq']?></span></td>
		<td align="right"><button type="button" class="btn btn-primary btn-xs" id="qq">编辑</button></td>
		</tr>
		
	<tr id="qqshow">
		<td></td>
		<td>
		<form action="infoedit.php" method="POST" id="qqf" name="form3" onSubmit="return checkinput3(this)"><span id="qqinfo"></span>
		<input type="text" class="form-control" name="qq" placeholder="<?php echo $info['qq']?>"><br>
		<div align="right">
		<button name="submit" type="submit" class="btn btn-info" >提交</button>&nbsp;&nbsp;
		<input type="button" class="btn btn-info" id="qqcan" value="取消">
		</div>
		</form>
		</td>
 	<script>
  $(document).ready(function(){
	  $("#qqshow").hide();
  $("#qq").click(function(){
    $("#qqshow").show();
    $("#qqa").hide();
	$("#qq").hide();
  });
  $("#qqcan").click(function(){
    $("#qqshow").hide();
	$("#qq").show();
	$("#qqa").show();
  });
});   

function checkinput3(form3){
    if(form3.qq.value==""){
	    document.getElementById("qqinfo").innerHTML="请输入";
		form3.qq.focus();
			return false;
	}else{
	 return true;
	}
}
</script>
<td></td>
	</tr>
	


	<tr>
	    <td>密码</td>
		<td align="center"><span class="pwda">(⊙o⊙)…更改密码</span></td>
        <td align="right"><button type="button" class="btn btn-primary btn-xs" id="pwd">编辑</button></td>
		</tr>
		
	<tr id="pwdshow">
		<td></td>
		<td>
		<form action="infoedit.php" method="POST" name="form4"  id="pwdf" onSubmit="return checkinput4(this)">
         原密码：&nbsp;&nbsp;<input type="password" class="form-control" name="userpwd1" placeholder="密码"><br>
		 <div ><div align="center"><span class="label label-success">请记住你输入的密码！</span></div>
		密码：&nbsp;&nbsp;&nbsp;<input type="password" class="form-control" name="userpwd2" placeholder="密码"><br>
		重复密码：&nbsp;<input type="password" class="form-control" name="userpwd3" placeholder="重复密码"><br>
		<div align="right">
		<button name="submit" type="submit" class="btn btn-info" >提交</button>&nbsp;&nbsp;
		<input type="button" class="btn btn-info" id="pwdcan" value="取消">
		</div>
		</form>
		</td>
 	<script>
  $(document).ready(function(){
	  $("#pwdshow").hide();
  $("#pwd").click(function(){
    $("#pwdshow").show();
    $("#pwda").hide();
	$("#pwd").hide();
  });
  $("#pwdcan").click(function(){
    $("#pwdshow").hide();
	$("#pwd").show();
	$("#pwda").show();
  });
});    

function checkinput4(form4){
    if(form4.userpwd1.value==""){
	    alert("请填入原密码");
		form4.userpwd1.focus();
			return false;
	}
	if(form4.userpwd2.value==""){
	     alert("请填入新密码");
		 form4.userpwd2.focus();
			return false;
	}
	if(form4.userpwd2.value.length<6){
				 
				   alert("密码长度应大于6位！");   
				   form4.userpwd2.focus();
				   return false; 
	}
	if(form4.userpwd3.value==""){
	     alert("请重复新密码");
		 form4.userpwd3.focus();
			return false;
	}
	if(form4.userpwd3.value!=form4.userpwd2.value){
	     alert("两次密码不一样");
		 form4.userpwd2.focus();
			return false;
	}
		
	
	 return true;
	}
</script>
    <td></td>
	</tr>
	</table>
    </div>

        
    