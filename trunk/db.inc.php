<?php
/*
$hostname='localhost';
$db_username='a3722hos_att';
$db_password='attendancedb';
$db_name='a3722hos_att';
*/

$hostname='localhost';
$db_username='root';
$db_password='';
$db_name='att';


$db=mysql_connect($hostname,$db_username,$db_password);
mysql_select_db($db_name,$db);

//////


?>