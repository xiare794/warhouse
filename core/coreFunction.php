
<?php
	
	include "_db.php";
	$opPool = array("田琳","王永恒","郑欣欣","张晓红","赵黔豫","孙丽华","任海艳","董媛媛","姚雁晓","吴小丽","陈芳","赵浩杰");
	
	$rfidReaderList = array(
		0	=> "手持0", 
		'h00'=>"手持0",
		1	=> "手持1", 
		'h01'=>"手持1",
		2	=> "手持2", 
		'h02'=>"手持2",
		3	=> "手持3", 
		'h03'=>"手持3",
		4	=> "手持4", 
		'h04'=>"手持4",
		5	=> "手持5", 
		'h05'=>"手持5",
		6	=> "手持6", 
		'h06'=>"手持6",
		7	=> "手持7", 
		'h07'=>"手持8",
		
		10	=> "门禁0", 
		'd00'=>"门禁0",
		11	=> "门禁1", 
		'd01'=>"门禁1",
		12	=> "门禁2", 
		'd02'=>"门禁2",
		13	=> "门禁3", 
		'd03'=>"门禁3",
		14	=> "门禁4", 
		'd04'=>"门禁4",
		15	=> "门禁5", 
		'd05'=>"门禁5", 
	);
	
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
		//var_dump($connection);
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

	function  socket_OutputApp() {
		global $connection;
		$output ="";
		
		if($type == "in" || $type == "move" || $type == "out" )
			$Qpara = "WHERE appType = '".$type."' AND appSignned = '1'";
		else 
			$Qpara = "WHERE appSignned = '1'";
		$query = "SELECT * FROM `wApplications` ".$Qpara;
		//var_dump($connection);
		$result = mysqli_query($connection, $query);
		//var_dump($result);
		if(!$result){
		 echo '[we have a problem]: '.mysqli_error($connection);
		}
		while( $row = mysqli_fetch_array($result) ){
			//array_push($taskPool,$row);
			$output .="----waiting tray:".$row['appFromTrayID']."\n";
		}
		$output .= "scan a Tray\n";
		return $output;
	}
	
	function addNewBind($command="H01:23") {
		//拆分命令 格式为RFID读取设备编号:托盘号
		global $rfidReaderList;
		$cmd = explode(":", strtolower($command));
		if(count($cmd) != 2){
			echo "格式错误";
		}
		else{
			//检查读取设备是否合法
			if( array_key_exists($cmd[0], $rfidReaderList)){
				
				//读取已绑定文件
				$file = "../trayBind.json";
				$json  = json_decode(file_get_contents($file),true);
				$newArray = array("deviceID"=>$cmd[0],"trayID"=>$cmd[1]);
				//检查新绑定是否已经存在
				if( in_array($newArray, $json)){
					echo "对应托盘已经有人负责,负责人是".$newArray['deviceID'];
				}
				else{
					//将新绑定写入文件
					array_push($json, $newArray);
					$fh = fopen("../trayBind.json", 'w');
					fwrite($fh, json_encode($json));
					fclose($fh);
					echo "更新文件";
				}
				//检查托盘是否在允许单内，不在
				$taskPool = getSignedApp();
			}
			else {
				//如果输入设备ID不合法
				echo "读取设备不在清单内 - ".$cmd[0];
			}
		}
	}
	
	
	function getAPPbyID($id){ //通过appID获得APP
		global $connection;
		$query = "SELECT * FROM `wAppIn` WHERE appID = '".$id."' ";
		$result = mysqli_query($connection, $query);
		//var_dump($result);
		if(!$result){
		 echo '[we have a problem]: '.mysqli_error($connection);
		}
		$row = mysqli_fetch_array($result);
		return $row;
	}
	function getTraysbyAppID($id){//通过appID获得所有app下托盘
		global $connection;
		$query = "SELECT * FROM `wTrays` WHERE wtAppID = '".$id."' ";
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
		$query = "UPDATE `wAppIn` SET `appStatus`=1 WHERE appID = '".$id."' ";
		$result = mysqli_query($connection, $query);
		
		if(!$result){
		 echo '[we have a problem]: '.mysqli_error($connection);
		}
		else{
			echo $id."号库单已签署";
		}
	}


	function FullCompleteAppIn($id){
		global $connection;
		$query = "UPDATE `wAppIn` SET `appStatus`=3 WHERE appID = '".$id."' ";
		$result = mysqli_query($connection, $query);
		
		if(!$result){
		 echo '[we have a problem]: '.mysqli_error($connection);
		}
		else{
			echo $id."号库单已完成入库";
		}
	}
	if(isset($_GET['FullCompleteAppIn'])){
		FullCompleteAppIn($_GET['FullCompleteAppIn']);
	}
	
	
?>