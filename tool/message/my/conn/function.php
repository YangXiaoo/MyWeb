<?php
function unhtml($content){
    $content = htmlspecialchars($content);       //把预定义的字符转换为HTML实体
	$content = str_replace("@","",$content);     //str_replace(find,replace,string,count)
	return trim($content);                       //删除文本中的空格
}
                                                 //定义一个用于截取一段字符串的函数msubstr()
                                                 //$str指字符串，$start指的字符串起始位置，$len指的是长度
function msubstr($str,$start,$len){
    $strlen = $start + $len;                     //存储字符串的总长度（从字符串起始位置到字符串的总长度）
	$tepstr = "";
	for($i = 0; $i < $strlen; $i++){
	     if(ord(substr($str, $i, 1)) > 0xa0){
                                                 //ord() 函数返回字符串的首个字符的 ASCII 值。
                                                 //substr(string,start,length),返回函数字符串的一部分。
		                                         //每次取出两个字符串赋给变量$tmpstr，即一个汉字
		 $tmpstr = substr($str, $i, 2);
		 $i++;
		 }else                                   //如果不是汉字，则每次取一位字符串赋予变量$stmpstr
			 $tmpstr = substr($str, $i, 1);                                    
	}
    return $tmpstr;                              //输出字符串     
}
?>