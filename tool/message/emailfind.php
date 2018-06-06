<?php
//该文件名为 emailfind.php
/* use PHPMailerPHPMailerPHPMailer;
use PHPMailerPHPMailerException;

require 'php/Exception.php';
require 'php/PHPMailer.php';
require 'php/SMTP.php'; */
include("conn/conn.php");
include_once "class.phpmailer.php"; 
include_once "class.smtp.php";	
//include_once "Exception.php";
//获取一个外部文件的内容 
$mail=new PHPMailer(); 
///
$send_email=$_POST['email'];
$sql=mysql_query("select * from tb_user where email='$send_email'",$conn);
$info = mysql_fetch_array($sql);
if (mysql_num_rows($sql)==1){
$pwd=md5($info["userpwd"]);

//$send_phone=$_POST['phone'];
$send_theme="找回密码";
//$send_kind=$_POST['kind'];
$mailcontent = "<div align=center><strong>姓名昵称: </strong>".$info["usernc"]." <br><strong>用途: </strong>".$send_theme.' <br><a href="http://www.lxxx.site/tool/message/findpwd.php">点击进去修改</a></div>';//邮件内容
///
//设置smtp参数 
$mail->IsSMTP(); 
$mail->SMTPAuth=true; 
$mail->SMTPKeepAlive=true; 
$mail->Host="ssl://smtp.qq.com"; 
$mail->Port=465; 
//填写你的email账号和密码 
$mail->Username="1593606228@qq.com"; 
$mail->Password="yrvnpxwjbmyiiged";#注意这里要填写授权码就是我在上面网易邮箱开启SMTP中提到的，不能填邮箱登录的密码哦。 
//设置发送方，最好不要伪造地址 
$mail->From="popmarshmallow@foxmail.com"; 
$mail->FromName="marshroom";//发送者用户名 
$mail->Subject="找回密码";//邮件标题 
$mail->AltBody=$mailcontent; //邮件内容
$mail->WordWrap=50; // set word wrap 
$mail->MsgHTML($mailcontent); 

//设置回复地址 
$mail->AddReplyTo("popmarshmallow@foxmail.com","lala"); 
//设置邮件接收方的邮箱和姓名 
$mail->AddAddress($send_email,$info["usernc"]);//接收者邮箱和用户名 
//使用HTML格式发送邮件 
$mail->IsHTML(true); 
//通过Send方法发送邮件 
//根据发送结果做相应处理 
if(!$mail->Send()){ 
    //echo "Mailer Error:".$mail->ErrorInfo;
    echo "<meta charset=\"UTF-8\">";
    echo "<script language=\"JavaScript\">
";
    echo " alert(\"对不起，邮件发送失败！！联系管理员\");
";
    echo " history.back();
";
    echo "</script>";
    exit;
    exit(); 
    }else{ 
        echo "<meta charset=\"UTF-8\">";
        echo "<script language=\"JavaScript\">
";
        echo " alert(\"发送成功！查看邮箱找回密码！\");
";
       echo " history.back();
	   ";
        echo "</script>";
        exit; 
}
}else{
echo "<meta charset=\"UTF-8\">";
        echo "<script language=\"JavaScript\">
";
        echo " alert(\"邮箱不存在！\");
";
       echo " history.back();
	   ";
        echo "</script>";
}
?>