<?php 
#настройки

define ('DB_HOST','10.32.0.244');
define ('DB_LOGIN','admin');
define ('DB_PASSWORD','gfhjkm2@');
define ('DB_NAME','udir_test');

$link = mysqli_connect(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_NAME) or die ("MySQL Error ".mysql_error());
mysqli_query ($link, "set names utf8") or die ("<br>Invalid query ".mysqli_error($link));			
?>