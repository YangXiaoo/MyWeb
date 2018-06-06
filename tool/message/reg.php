<?php echo "<meta http-equiv='Content-Type' content='text/html'; charset='utf-8'>";
?>
            <div class="row" align="center">
			<div class="col-sm-12">
              
			
			 <script language="javascript">
			 
			   function chkinput(form){				//定义一个函数
			    
				 if(form.usernc.value==""){				//判断usernc文本框中的值是否为空
				   alert("请输入用户昵称！");   		//如果为空则输出“请输入用户昵称”
				   form.usernc.focus();					//返回到tel文本框
				   return(false);
				 }
				 
				 if(form.userpwd.value==""){
				 
				   alert("请输入注册密码！");   
				   form.userpwd.focus();
				   return(false);
				 
				 }
				  if(form.userpwd.value.length<6){
				 
				   alert("密码长度应大于6位！");   
				   form.userpwd.focus();
				   return(false); 
				 
				 }
				 
				  if(form.userpwd1.value==""){
				 
				   alert("请输入重复密码！");   
				   form.userpwd1.focus();
				   return(false);
				 
				 }
				 if(form.userpwd.value!=form.userpwd1.value){
				 
				   alert("密码与确认密码不同！");   
				   form.userpwd.focus();
				   return(false); 
				 
				 }
				 
				
	/*			 if(form.truename.value==""){
				   alert("请输入真实姓名！");
				   form.truename.focus();
				   return(false);
				 }
	*/
				 if(form.sex.value==""){
				   alert("请选择性别！");
				   form.sex.focus();
				   return(false);
				 }
				 
				 if(form.email.value==""){
	               alert("请输入E-mail地址!");
	               form.email.focus();
	               return(false);
	             }
				
	             var i=form.email.value.indexOf("@");
	             var j=form.email.value.indexOf(".");
	             if((i<0)||(i-j>0)||(j<0)){
                   alert("请输入正确的E-mail地址!");
	               form.email.select();
	               return(false);
	             }
				 
	/*			 if(form.tel.value==""){
				   alert("请输入联系电话！");
				   form.tel.focus();
				   return(false);
				 } 
				 
				 if(isNaN(form.tel.value)){
				   alert("联系电话只能为数字！");
				   form.tel.focus();
				   return(false);
				 }
*/				 
				 if(form.qq.value==""){
				   alert("请输入联系QQ！");
				   form.qq.focus();
				   return(false);
				 } 
				 
				 if(isNaN(form.qq.value)){
				   alert("QQ只能为数字！");
				   form.qq.focus();
				   return(false);
				 }
				 
/*
			     if(form.mibao.value==""){
				   alert("请输入密保问题！");
				   form.mibao.focus();
				   return(false);
				 } 
				 if(form.mibao.value.length > 100){
				  alter("请检查密保问题长度！");
					form.mibao.focus();
				   return(false);
				}
                if(form.answer.value==""){
				alter("请输入密保答案！");
					form.answer.focus();
				   return(false);
				}
			  if(form.answer.value.length > 100){
				  alter("请检查密保答案长度！");
					form.answer.focus();
				   return(false);
				}
*/
			
			    return(true);							//提交表单
			     
			   }

			   $(document).ready(function(){
			       $("#more").hide();
				   $("#link").click(function(){
				       $("#more").show();
					   $("#down").hide();
					   $("#upp").show();
				   });

				   $("#up").click(function(){
				       $("#upp").hide();
					    $("#more").hide();
						$("#down").show();
				   });
			   });
			  
			  </script>
<form name="form1" method="post" action="savereg.php" onSubmit="return chkinput(this)">
    <input type="text" name="usernc"  class="form-control" placeholder="用户名称/必填"><br>
    <input type="password" name="userpwd"  class="form-control" placeholder="密码"><br>
    <input type="password" name="userpwd1"  class="form-control" placeholder="重复密码"><br>
     <input type="text" name="email"  class="form-control" placeholder="邮箱/必填" ><br>
     <input type="text" name="qq"  class="form-control" placeholder="QQ号/必填"><br>
	 <div align="left"><input type="radio" name="sex"  value="男" checked>男&nbsp; <input type="radio" name="sex"  value="女" >女 <br><br>
	
	<strong>头像选择：</strong>
	<select name="face" onchange="form1.user_face.src=this.value">
<?php
	for($i=0;$i<=11;$i++){		//循环输出复选框的头像
?>
	<option value="<?php echo "images/face/".$i.".gif"?>"><?php echo $i.".gif"?></option>
<?php
}
?>
    </select><br><img id=user_face src="images/face/0.gif" width="60" height="60">
	</div>
	<div align="center" id="down"><a href="#" id="link">更多</a></div>
	<div id="more">          
         <input type="text" name="truename" class="form-control" placeholder="真实姓名" ><br>
          <input type="text" name="tel" class="form-control"  placeholder="联系电话"><br>
	    <div align="center" id="upp">
			<a href="#" id="up">收起</a>
		</div>
		</div><hr>
        <div align="right">
           <input name="submit" type="submit" class="btn btn-info " value="注册">
            &nbsp;&nbsp;
           <input name="reset" type="reset" class="btn btn-info" value="重写">
        </div>
</form>
 </div>
</div> 