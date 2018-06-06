
<?php
if(isset($_GET['page'])){
		$page=$_GET['page'];
	}else{
	  	$page=1;
	}
	  $page_count=6;
	  $select=mysql_query("select * from tb_category where authorization='1'",$conn);
	  $row=mysql_num_rows($select);
	  $page_page=ceil($row/$page_count);
	  $offect=($page-1)*$page_count;   //获取上一页的最后一条记录，从而计算下一页的起始记录
	  $selects=mysql_query("select * from tb_category where authorization='1'  order by id desc limit $offect,$page_count",$conn);
	  while($array=mysql_fetch_array($selects)){
	  $icon=substr($array['icon'],3,30);
?>

<table cellpadding="0px" cellspacing="0px">
  <tr>
    <td ><a href="index.php?category=<?php echo $array['category'];?>&id=<?php echo urlencode("板块内容");?>"><span class="label label-success"><strong>板块类别</strong>[<?php echo $array['category'];?>]</span></a>&nbsp;&nbsp;&nbsp;<br><br>
	版主：<?php echo $array['noderator']?><br></td>
    <td >创建日期：<?php echo $array['create_date'];?><br>
    主题总数：<?php $selectes=mysql_query("select * from tb_leaveword where category='".$array['category']."'",$conn);
		  $count=mysql_num_rows($selectes);
		  echo $count;
		  ?><br>
    今日主题数：<?php $dates=date("Y-m-d");
		  $rows=mysql_query("select * from tb_leaveword where date='$dates' and category='".$array['category']."'",$conn);
		  $counts=mysql_num_rows($rows);
		  echo $counts;?></td>
  </tr>
  </table><hr>
  <?php 
}	  
?>

<div align="center">
<table cellpadding="0px" cellspacing="0px">
  <tr>
    <td >
      共<?php echo $page_page;?>页 每页<?php echo $page_count;?>条 当前第<?php  echo $page; ?>页  &nbsp;&nbsp;&nbsp;&nbsp;
	  </td>
	  <td>
	  <a href="index.php?page=1">首页</a> 
	  <a href="index.php?page=<?php if($page==1){echo $page=1; }else{ echo $page-1; }?>">上一页</a> 
	  <a href="index.php?page=<?php if($page<$page_page){echo $page+1;}else{ echo $page_page;}?>">下一页</a> 
    <a href="index.php?page=<?php echo $page_page; ?>">尾页</a></td>
  </tr>
</table>
</div>
