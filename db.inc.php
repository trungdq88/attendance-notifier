<?php
/*
$hostname='localhost';
*/

$hostname='localhost';
$db_username='root';
$db_password='';
$db_name='att';


$db=mysql_connect($hostname,$db_username,$db_password);
mysql_select_db($db_name,$db);

//////


?>