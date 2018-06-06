<?php
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
$sql=mysql_query("select * from tb_user where usernc='$user'",$conn);
$myinfo = mysql_fetch_array($sql);
$sqlq=mysql_query("select * from tb_addfriend where myid='".$myinfo["id"]."'",$conn);
while($friend=mysql_fetch_array($sqlq)){
$sqla=mysql_query("select * from tb_user where id='".$friend["friendid"]."'",$conn);
$ff = mysql_fetch_array($sqla);
?>
    <table cellpadding="0px" cellspacing="0px" class="table table-hover">
	<tr>
	    <td>
		
		<img src="<?php echo $ff["face"]; ?>" height="40px"/>&nbsp;&nbsp;
		
		</td>
		<td >
		<a href="index.php?id=<?php echo urlencode("个人信息")?>&userid=<?php echo $ff["id"]?>"><?php echo $ff["usernc"];?></a><br>
		关注时间：<?php echo $friend["createtime"]?>
		</td>
	</tr>
	</table>
<?php
}
?>

