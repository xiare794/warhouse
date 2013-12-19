<?php 
	//统一配置数据库
	include_once("../core/_db.php");
	$todoApp = array();

	$query = "SELECT * FROM `wApplications` WHERE appSignned = '1'";
	$result = mysqli_query($connection, $query);
	if(!$result){
	 echo '[we have a problem]: '.mysqli_error($connection);
	}
	while( $row = mysqli_fetch_array($result) ){
		$todoArray = array('appID'=>$row['appID'],
							'wpID'=>$row['wpID'],
							'appName'=>$row['appName'],
							'appCount'=>$row['appCount'],
							'appType'=>$row['appType'],
							'appOperator'=>$row['appOperator'],
							'appFromTrayID'=>$row['appFromTrayID'],
							'appDeviceID'=>$row['appDeviceID'],
							'appComplete'=>$row['appComplete'],
							'appStatus'=>$row['appStatus']
							);
		array_push($todoApp,$todoArray);
	}
	$todoApp["success"] = 1;
	echo(json_encode($todoApp));
	
	
?>
	
