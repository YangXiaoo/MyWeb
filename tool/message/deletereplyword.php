<?php
include_once("conn/conn.php");
$l_id=$_POST['l_id'];
$result=mysql_query("delete from tb_replyword  where id='$l_id'",$conn);
if(!$result){
     $response=array(
	     'errno' =>-1,
		 'errmsg' =>  'fail',
		 'data' => false,
	 );
}else{
     $response=array(
	    'errno' => 0,
	     'errmsg' => 'success',
		 'data' =>  true,
	 );
}
 echo json_encode("$response");
?>