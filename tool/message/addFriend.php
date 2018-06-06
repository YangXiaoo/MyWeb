<?php
$myid = $_POST['myid'];
$friendid = $_POST['friendid'];
include("conn/conn.php");
$createtime=date("Y-m-d H:i:s");
$sql=mysql_query("select * from tb_addfriend where myid='$myid' and friendid='$friendid'");
$info = mysql_fetch_array($sql);
if($info){

	$response = array(
            'errno'     => -2,
            'errmsg'   => 'fail',
            'data'       => false,
        );

}else {
   $sqla = mysql_query("insert into tb_addfriend(myid,friendid,createtime) values('$myid','$friendid','$createtime')");
    if($sqla){
        $response = array(
            'errno'     => 0,
            'errmsg'  => 'success',
            'data'      => true,
        );
    }else{
        $response = array(
            'errno'     => -1,
            'errmsg'   => 'fail',
            'data'       => false,
        );
    }
}
    echo json_encode($response);
  
    ?>
