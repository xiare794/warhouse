
<?php
	
	include "_db.php";
	
	function refreshTaskJson() {
		global $connection;
		$todoApp = array();
		$query = "SELECT * FROM `wApplications` WHERE appSignned = '1'";
		$result = mysqli_query($connection, $query);
		//var_dump($result);
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
		$fh = fopen("../todo.json", 'w');
		fwrite($fh, json_encode($todoApp));
		fclose($fh);
		//return $taskPool;
	}
	function getSignedApp($type="all"){
		global $connection;
		$taskPool = array();
		if($type == "in" || $type == "move" || $type == "out" )
			$Qpara = "WHERE appType = '".$type."' AND appSignned = '1'";
		else 
			$Qpara = "WHERE appSignned = '1'";
		$query = "SELECT * FROM `wApplications` ".$Qpara;
		$result = mysqli_query($connection, $query);
		//var_dump($result);
		if(!$result){
		 echo '[we have a problem]: '.mysqli_error($connection);
		}
		while( $row = mysqli_fetch_array($result) ){
			array_push($taskPool,$row);
		}
		return $taskPool;
	}
	
	
	
	function getAPPbyID($id){
		global $connection;
		$query = "SELECT * FROM `wApplications` WHERE appID = '".$id."' ";
		$result = mysqli_query($connection, $query);
		//var_dump($result);
		if(!$result){
		 echo '[we have a problem]: '.mysqli_error($connection);
		}
		$row = mysqli_fetch_array($result);
		return $row;
	}
	function passApp($id){
		global $connection;
		$query = "UPDATE `wApplications` SET `appSignned`=1 WHERE appID = '".$id."' ";
		$result = mysqli_query($connection, $query);
		
		if(!$result){
		 echo '[we have a problem]: '.mysqli_error($connection);
		}
		else{
			echo $id."号库单已签署,刷新查看";
		}
	}
	
	$statusTextArray = array(
		0	=>"待入库",
		1	=>"写标签",
		2	=>"叉车取货",
		3	=>"进门",
		4	=>"入货位",
		5	=>"盘货记录",
		6	=>"取货出库",
		7	=>"出门",
		8	=>"装车",
		9	=>"出库完成"
	);
	
	$appTypeTextArray =  array(
		"in" => "入库单",
		"move" => "移库单",
		"out" => "出库单"
	);
	
	$signTextArray = array(
		0	=> "已签署",
		1   => "未签署"
	);
	
?>