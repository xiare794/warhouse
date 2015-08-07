<?php 
	//填充在每次使用DB之前
	$host = "localhost";
	$databaseName = "stockDB";
	$user = "xiare794";
	$pass = "123";

	date_default_timezone_set('Asia/Hong_Kong');//其中Asia/Chongqing'为“亚洲/重庆”
	//$t=time();
	//$appCreateTime = date("Y-m-j G:i:s",$t);
	
	$connection=mysqli_connect($host,$user,$pass,$databaseName);
	if(!mysqli_set_charset($connection,'utf8'))
			echo "设置uft失败";
?>