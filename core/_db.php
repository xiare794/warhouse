<?php 
	//填充在每次使用DB之前
	$host = "localhost";
	$databaseName = "stockDB";
	$user = "xiare794";
	$pass = "123";
	$connection=mysqli_connect($host,$user,$pass,$databaseName);
	if(!mysqli_set_charset($connection,'utf8'))
			echo "设置uft失败";
?>