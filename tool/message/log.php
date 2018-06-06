
<div align="center"><strong>用户登录</strong></div><br><div align="center">
 <table  border="0" cellpadding="0" cellspacing="0">
 <form name="form_login" method="post"  action="chkuserlogin.php" onsubmit="return chkinputlogin(this)">

             <script language="javascript">
			   function chkinputlogin(form){
			     if(form.usernc.value==""){
				   alert("请输入用户昵称！");
				   form.usernc.focus();
				   return(false);
				 }
				 
				  if(form.userpwd.value==""){
				   alert("请输入登录密码！");
				   form.userpwd.focus();
				   return(false);
				 }
				 return(true);
			   }
			 </script>
			 
			
			  <tr >
                <td align="right" ><strong>账号：&nbsp;</strong></td>
                <td ><input type="text" class="form-control"  name="usernc"></td>
              </tr>

			  <tr><td>&nbsp;</td></tr>

              <tr >
                <td ralign="right"><strong>密码：</strong>&nbsp;</td>
                <td ><input type="password"  class="form-control"  name="userpwd"> </td>
              </tr>
			  <tr><td>&nbsp;</td></tr>

              <tr >
                <td></td><td align="right"><input type="submit" value="登录" class="btn btn-info">&nbsp;&nbsp;<input type="reset" value="重写" class="btn btn-info"></td>
              </tr>
               </form>
			  </table></div>
			  <hr>