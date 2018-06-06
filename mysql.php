<?php
//==========================mySQL=============================
查询：

select * from user where user_id in (1,2,3); => select * from user where user_id = 1 or user_id = 2 or user_id = 3;
like是广泛的模糊匹配，字符串中没有分隔符，find_in_set是精确匹配，字段值以英文”,”分隔
mysql> SELECT FIND_IN_SET('b','a,b,c,d');

find返回一维数组，select返回二维数组

?>