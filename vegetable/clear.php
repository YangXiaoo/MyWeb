<?php
    //将该用户下的所有购物车数据删除
    session_start();
    $userid = $_SESSION['id'];
    try{
        $pdo = new PDO("mysql:host=qdm21208779.my3w.com;dbname=qdm21208779_db","qdm21208779","Ab127000",array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
        $pdo->query("set names utf8");
        $sql = "delete from shop_cart where userid=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($userid));
        $rows = $stmt->rowCount();
    }catch(PDOException $e){
        echo $e->getMessage();
    }
    
    if($rows){
        $response = array(
            'errno'  => 0,
            'errmsg' => 'success',
            'data'   => true,
        );
    }else{
        $response = array(
            'errno'  => -1,
            'errmsg' => 'fail',
            'data'   => false,
        );
    }
    
    echo json_encode($response);
    
    
    
    