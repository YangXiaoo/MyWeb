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
<h1>hello!<?php 
$username = $_SESSION['username'];
echo "$username";
?></h1><hr>
<a href="goodscart.php">查看我的购物车</a>


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
   $data = mysqli_fetch_assoc($res);









$count_sql = 'select count(id) as c from shop_product';

$result = mysqli_query($conn, $count_sql);

$data = mysqli_fetch_assoc($result);

//得到总的用户数
$count = $data['c'];

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

/*
if (isset($_GET['page'])) {
    $page = (int) $_GET['page'];
} else {
    $page = 1;
}
 */

//每页显示数

$num = 5;

//得到总页数
$total = ceil($count / $num);

if ($page <= 1) {
    $page = 1;
}

if ($page >= $total) {
    $page = $total;
}


$offset = ($page - 1) * $num;

$sql = "select * from shop_product order by id desc limit $offset , $num";

$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result)) {

    //存在数据则循环将数据显示出来

echo '<div class="table-responsive">';
   echo '<table class="table table-bordered   table-condensed">';
    echo	'<tr class="active">';
    	echo	'<td>商品图片</td>';
    	echo	'<td>商品信息</td>';
    	echo	'<td>商品原价</td>';
		echo	'<td>优惠价</td>';
    	echo	'<td>库存</td>';
		echo	'<td>购买数量</td>';
    	echo	'<td>选购</td>';
    	echo '</tr>';

    while ($row = mysqli_fetch_assoc($result)) {

        echo '<tr>';

        echo '<td>' . $row['img'] . '</td>';
        echo '<td>' . date('Y-m-d H:i:s', $row['createtime']) . '</td>';
        echo '<td>' . long2ip($row['createip']) . '</td>';
        echo '<td><a href="edit.php?id=' . $row['id'] . '">edit</a></td>';
        echo '<td><a href="delete.php?id=' . $row['id'] . '">delect</a></td>';

        echo '</tr>';
    }

    echo '<tr><td colspan="5"><a href="page.php?page=1">First page</a> | <a href="page.php?page=' . ($page - 1) . '">up</a>  | <a href="page.php?page=' . ($page + 1) . '">down</a>|  <a href="page.php?page=' . $total . '">last</a> | page: ' . $page . '  |total' . $total . ' </td>
	</tr>';

    echo '</table>';

} else {
    echo '没有数据';
}

mysqli_close($conn);
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
