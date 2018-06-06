 <?php
 ?>
 <!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>购物车-Goodscart</title>
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
<h1>hello!<?php echo $_SESSION['username']?></h1><hr>
<a href="goodslist.php">返回货物列表</a>

<div class="table-responsive">
    <table class="table table-bordered   table-condensed">
    	<tr class="active">
    		<td>商品名称</td>
			<td>商品原价</td>
			<td>优惠价（本店价）</td>
			<td>购买数量</td>
    		<td>小计</td>
			<td>操作</td>
    	</tr>
		<?php
    try{
        $pdo = new PDO("mysql:host=qdm21208779.my3w.com;dbname=qdm21208779_db","qdm21208779","Ab127000",array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
        $pdo->query("set names utf8");
        $sql = "select p.id,  p.title, p.price, p.originalprice, c.num from shop_product p right join shop_cart c on p.id=c.productid where c.userid=?";
        $stmt = $pdo->prepare($sql);
        $userid = $_SESSION['id'];
        $stmt->execute(array($userid));
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }catch(PDOException $e){
        echo $e->getMessage();
    }
?>

<?php
    $total = 0;
    foreach($data as $product):
?>
       <tr id="tr-<?php echo $product['id'] ?>" class="products">
              <td bgcolor="#ffffff" align="center" style="width:300px;">
				<?php echo $product["title"]?>
              </td>
              <td align="center" bgcolor="#ffffff">￥<?php echo $product["originalprice"] ?>元</td>
              <td align="center" bgcolor="#ffffff">￥<?php echo $product["price"] ?>元</td>
              <td align="center" bgcolor="#ffffff">


                <input type="text" name="goods_number" value="<?php echo $product["num"] ?>" size="4" class="inputBg" style="text-align:center " onblur="changeNum(<?php echo $product["id"] ?>, this.value)" id="product-<?php echo $product["id"] ?>" >//修改商品数量操作并返回数据
               </td>



              <td align="center" bgcolor="#ffffff">￥<span id="total-<?php echo $product["id"] ?>"><?php echo $product["num"]*$product["price"] ?></span>元</td>
              <td align="center" bgcolor="#ffffff">
                <a href="javascript:delPro(<?php echo $product["id"] ?>);" class="f6">删除</a>
              </td>
            </tr>
<?php
    $total += $product["price"]*$product["num"];
    endforeach;
?> 
            <script type="text/javascript">
                function changeNum(productid, num){
                    //通过ajax将对应商品的数量进行修改操作
                    var url = "changeNum.php";
                    var data = {"productid":productid, "num":num};
                    var success = function(response){
                        if(response.errno == 0){
                            var price = ($("#product-"+productid).val())*($("#p-"+productid).html());//根据数据库改变商品价格
                            $("#total-"+productid).html(price);
                        }
                    }
                    $.post(url, data, success, "json");
                }
                
                function delPro(productid){
                    //通过ajax将商品的id传递给PHP脚本进行数据表的删除
                    var url = "deleteProduct.php";
                    var data = {"productid":productid};
                    var success = function(response){
                        if(response.errno == 0){
                            $("#tr-"+productid).remove();
                        }
                    }
                    $.get(url, data, success, "json");
                }
            </script>
 
                      </tbody></table>
          <table width="99%" align="center" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
            <tbody><tr>
              <td bgcolor="#ffffff">
                            购物金额小计 ￥<?php echo $total ?>元             
              </td>
              <td align="right" bgcolor="#ffffff">
                <input type="button" value="清空购物车" class="bnt_blue_1" onclick="clearCart()">
                <script type="text/javascript">
                    function clearCart(){
                        var url = "clear.php";
                        var success = function(response){
                            if(response.errno == 0){
                                $(".products").remove();
                            }
                        }
                        $.get(url, success, 'json');
                    }
                </script>
              </td>
            </tr>
          </tbody></table>


          <input type="hidden" name="step" value="update_cart">
        </form>
        <table width="99%" align="center" border="0" cellpadding="5" cellspacing="0" bgcolor="#dddddd">
          <tbody><tr>
            <td bgcolor="#ffffff"><a href="goodslist.php" alt="continue">继续购物</a></td>
            <td bgcolor="#ffffff" align="right"><a href="update.php" alt="checkout">结算</a></td>
          </tr>
        </tbody></table>
       </div>
    
  

    </table>
    </div>
</div>
</div>
</div>
</body>
</html>
