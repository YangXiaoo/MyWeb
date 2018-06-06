<?php
    session_start();
    //1.接收参数并且处理参数
    $productid = intval($_GET['productid']);
    $userid = $_SESSION['id'];
    //2.删除数据表
    try{
        $pdo = new PDO("mysql:host=qdm21208779.my3w.com;dbname=qdm21208779_db","qdm21208779","Ab127000",array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
        $pdo->query("set names utf8");
        $sql = "delete from shop_cart where productid=? and userid=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($productid, $userid));
        $rows = $stmt->rowCount();
    }catch(PDOException $e){
        echo $e->getMessage();
    }
    
    //3.返回处理结果
    if($rows){
        $response = array(
            'errno'     =>  0,
            'errmsg'   => 'success',
            'data'       => true
        );
    }else{
        $response = array(
            'errno'     =>  -1,
            'errmsg'   => 'fail',
            'data'       => false
        );
    }
    
    echo json_encode($response);
    
    
    
    