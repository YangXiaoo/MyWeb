<?php
//==========================mySQL=============================
��ѯ��

select * from user where user_id in (1,2,3); => select * from user where user_id = 1 or user_id = 2 or user_id = 3;
like�ǹ㷺��ģ��ƥ�䣬�ַ�����û�зָ�����find_in_set�Ǿ�ȷƥ�䣬�ֶ�ֵ��Ӣ�ġ�,���ָ�
mysql> SELECT FIND_IN_SET('b','a,b,c,d');

find����һά���飬select���ض�ά����

?>