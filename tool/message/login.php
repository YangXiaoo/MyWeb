
<script language="javascript">
function checklogin(){
	if(login.username.value!="" && login.password.value!=""){
		return true
	}else{
		alert("用户名或者密码不能为空!")
		return false
	}
}
</script>
               </style>
			   <div align="center"><strong>站长登录</strong></div><br>
			   <table border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FCD424">
	<form action="chklogin.php" method="post" name="login" class="STYLE1" id="login" onsubmit="return checklogin()">
               
        
          <tr>
              
              <td align="right"><strong>账号:</strong>&nbsp;</td>
                 
                 <td> <input name="username" type="text" class="form-control"  id="username" /></td>
               <tr><td>&nbsp;</td></tr>
			   <tr>
              <td align="right"><strong>密码:</strong>&nbsp;</td>
                <td><input name="password" type="password"  class="form-control"  id="password" />
              </td>
               <tr><td>&nbsp;</td></tr>
			   <tr><td></td><td align="right">
                <input type="submit" name="Submit" class="btn btn-info" value="登录" />
&nbsp; 
<input type="reset" name="Submit2" class="btn btn-info" value="重写" />
</td>
             
        </tr>
      
      
 </form>
</table>

			     