
 <table width="180" height="80" border="0" align="center" cellpadding="0" cellspacing="0">
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
			 <form name="form_login" method="post" action="chkuserlogin.php" onsubmit="return chkinputlogin(this)">
			  <tr>
                <td width="50" height="30"><div align="center">用户名：</div></td>
                <td width="130">&nbsp;<input type="text" size="16" class="inputcss" name="usernc"></td>
              </tr>
              <tr>
                <td height="30"><div align="center">密&nbsp;&nbsp;码：</div></td>
                <td height="30">&nbsp;<input type="password" size="16" class="inputcss" name="userpwd"></td>
              </tr>
              <tr>
                <td height="30" colspan="2"><div align="center"><input type="submit" value="登录" class="buttoncss">&nbsp;&nbsp;<input type="reset" value="重写" class="buttoncss" ></div></td>
              </tr>
			  
			  </form>
            </table>


