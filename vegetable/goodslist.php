<?php

session_start();
?>
 <!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>货物列表-GoodsList</title>
  <link rel="stylesheet" href="goodsList.css" >
<!-- 最新版本的 Bootstrap 核心 CSS 文件 -->
<link rel="stylesheet" href="../bootstrap-3.3.7/css/bootstrap.min.css" >
<!-- jquery 核心 JavaScript 文件 -->
<script src="../bootstrap-3.3.7/js/jquery-3.2.1.min.js" ></script>
<!--  Bootstrap 核心 JavaScript 文件 -->
<script src="../bootstrap-3.3.7/js/bootstrap.min.js"></script>
</head>


<body>
 <div class="container-fluid">
 <div class="goodslist">
<h1>hello!<span><?php 
echo $_SESSION['username']
?></span></h1><hr>
<a href="goodscart.php">查看我的购物车</a>

<div class="table-responsive">
    <table class="table table-bordered   table-condensed">
    	<tr class="active">
    		<td>商品图片</td>
    		<td>商品信息</td>
    		<td>商品原价</td>
			<td>优惠价</td>
    		<td>库存</td>
			<td>购买数量</td>
    		<td>选购</td>
    	</tr>
		<?php
//启
 $dbhost = 'qdm21208779.my3w.com';  // mysql服务器主机地址
$dbuser = 'qdm21208779';            // mysql用户名
$dbpass = 'Ab127000';          // mysql用户名密码
 $dbname='qdm21208779_db';          //数据库名称

$conn = mysqli_connect($dbhost, $dbuser, $dbpass,$dbname);
if(! $conn )
{
  die('连接错误: ' . mysqli_error($conn));
}
   $res = mysqli_query($conn,'select * from shop_product');
   $data = mysqli_fetch_array($res, MYSQLI_ASSOC);

 ?>
<?php
    foreach($data as $product):
?>
    	<tr class="active">
    		<td><img src="<?php echo $product['img']?>" width="30px"></td>
    		<td><?php echo $product['title'] ?></td>
    		<td>￥<?php echo $product['originalprice'] ?>元</td>
			<td>￥<?php echo $product['price'] ?>元</td>
    		<td><?php echo $product['inventory']?></td>
			<td><input type="text" id="number" value="1"></td>
    		<td><a href="javascript:addCart(<?php echo $product['id'] ?>)" class="btn btn-primary btn-lg active btn-sm" role="button">购买</a></td>
    	
		      <script type="text/javascript">
            function addCart(productid){
                //ajax请求php脚本完成数据的添加 shop_cart
                var url = "addCart.php";
                var data = {"productid":productid, "num":parseInt($("#number").val())};
                var success= function(response){
                    if(response.errno == 0){
                        alert('加入购物车成功');
                    }else{
                        alert('加入购物车失败');
                    }
                }
                $.post(url, data, success, "json");
            }
      </script>

	  </tr>
<?php
    endforeach;
?> 

    </table>
    </div>
</div>
</div>
</div>
</body>
</html>