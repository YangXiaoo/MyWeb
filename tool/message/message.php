<?php
$sqlm=mysql_query("select usernc,face,ip,email,qq,id from tb_user where usernc='".$_SESSION['unc']."'",$conn);
$infom=mysql_fetch_array($sqlm); 
$sqll=mysql_query("select * from tb_leaveword where userid='".$infom["id"]."'",$conn);
while($infol=mysql_fetch_array($sqll)){
$sqlr=mysql_query("select * from tb_replyword where look=1 and leave_id='".$infol["id"]."'",$conn);
while($rep=mysql_fetch_array($sqlr)){
    $sqllr=mysql_query("select * from tb_leaveword where id='".$rep["leave_id"]."'",$conn);
	$infolr=mysql_fetch_array($sqllr);
?>
<table cellpadding="0px" cellspacing="0px" class="table table-hover">
<tr>
    <td>
     <strong>回复的主题：</strong>
	 &nbsp;&nbsp;&nbsp;<a href="index.php?l_id=<?php echo $infolr['id']; ?>&id=<?php echo urlencode('详细信息'); ?>">
	 <?php echo $infolr["title"]?></a>
	</td>
</tr>
<tr>
	<td>
	<strong>回复的内容：</strong>
	<?php echo $rep["contents"]?>&nbsp;&nbsp;<small><?php echo $rep["createtimes"]?></small>
    </td>
</tr>
</table>
<?php
}
}
//回复数为0时
if($to==0){
    echo "<div align=center>w(ﾟДﾟ)w，暂时没有消息</div>";
}
?>