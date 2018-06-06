<?php
echo "<meta http-equiv='Content-Type' content='text/html'; charset='utf-8'>";
?>
<div align="center"><h2>回复留言</h2></div><br>
<div align="center">
		   <script language="javascript">
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
		   
		   </script>
		  
            <table  cellpadding="0" cellspacing="0">
             <form name="form1" method="post" action="savereply.php">
			 
              <tr>
                <td align="right"><strong>回复主题：</strong>&nbsp;</td>
                <td><input type="text" name="title"  class="form-control">
                  <input type="hidden" name="t_id" value="<?php echo $_GET['t_id'];?>" /></td>
              </tr>
			  <tr><td>&nbsp;</td></tr>
              <tr>
                <td align="right"><strong>回复内容：</strong>&nbsp;</td>
				
                <td ><textarea name="content" rows="15" class="form-control">回复<?php 
				if(isset($_GET['loor'])){
					$loor=$_GET['loor'];
					echo $loor."楼：";
				}else{	
					$loor="留言：";
					echo $loor;
				
				}
				?></textarea></td>
			
              </tr>
			  <tr><td>&nbsp;</td></tr>
              <tr>
                <td ></td><td align="center"><input type="submit"  name="submit" value="回复" class="btn btn-info"onclick="return chkinput(form1);">
                &nbsp;<input type="reset" name="reset" value="重写" class="btn btn-info"></td>
              </tr>
			  </form>
            </table><hr>

</div>


