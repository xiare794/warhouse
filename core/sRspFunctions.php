<?php
	//过程动作表
	$actList = array("入库"=>array( "开始入库"=>"loadIn",
									"写标签"=>"tagged",
									"叉车运输"=>"forkLoad",
									"过门"=>"passDoor",
									"入货位"=>"insertSlot"),
					 "盘货"=>array( "盘货"=>"check"),
					 "移库"=>array( "开始移库"=>"moveStart",
									"叉车运输"=>"forkLoad",
									"入货位"=>"insertSlot"),
					 "出库"=>array( "取货出库"=>"ejectSlot",
									"过门"=>"passDoor",
									"装车"=>"truckLoad",
									"出库完成"=>"fin")
					);
	//var_dump( $actList["入库"]);				 
	include_once("_db.php");
	include_once("functions_manage.php");
	include_once("sRspFunctions.php");
	$rsErrorMsg = "";
	$app = array();
	$actList = array("In"=>array( "loadIn"=>"开始入库",
								"tagged"=>"写标签",
								"forkLoad"=>"叉车运输",
								"passDoor"=>"过门",
								"insertSlot"=>"入货位"),
				 "Check"=>array( "check"=>"盘货"),
				 "Move"=>array( "moveStart"=>"开始移库",
								"forkLoad"=>"叉车运输",
								"insertSlot"=>"入货位"),
				 "Out"=>array( "ejectSlot"=>"取货出库",
								"passDoor"=>"过门",
								"truckLoad"=>"装车",
								"fin"=>"出库完成")
				);
					
	
		
	function endKey($array){
		 end($array);
		 return key($array);
	}
	function getOpenAppByID($pid){			
		//找到wapplications里 wpID = pid; appStatus=开启的应用单，理论上只有一个，有多的报错
		global $connection;
		global $rsErrorMsg;
		global $app;
		$query = "SELECT * FROM `wapplications`";
		$query .= " WHERE `wpID`= $pid AND `appStatus`= \"开启\"";
		$app['appSignned'] = false;
		$stack = array();
		if ($result = mysqli_query($connection, $query)) {			
			while($row = mysqli_fetch_array($result)){
				array_push($stack,$row);
				$app['appType']  = $row['appType'];
				$app['appID']  = $row['appID'];
				$app['appSignned']  = $row['appSignned'];
			}
			var_dump( $stack);
			var_dump($app);
		}
		echo count($stack);
		if(count($stack) == 0)
			$rsErrorMsg .= "-getOpenAppByID-Can't find open app for pack[$pid]";
		if(count($stack) > 1)
			$rsErrorMsg .= "-getOpenAppByID-More than one open app for pack[$pid]";
		return count($stack);
	}
	function ifActProper($act,$pid,$appID){
		//echo "Act Proper";
		global $connection;
		global $rsErrorMsg;
		global $app;
		global $actList;
		$app['finished'] = false;
		//先获取同货物包，下已完成的动作列表
		$query = "SELECT `actType` FROM `wactions`";
		$query .= " WHERE `wpID`= $pid AND `actComplete`= 1 AND `appID`= $appID ";
		echo "$query";
		$actCs = array();
		if ($result = mysqli_query($connection, $query)) {			
			while($row = mysqli_fetch_array($result)){
				array_push($actCs,$row['actType']);
			}
			var_dump( $actCs);
		}
		
		//检验出入库类型
		if($app['appType'] == '出库'){
			$next;
			foreach($actList['Out'] as $key=>$value)
			{
				if(!in_array($key,$actCs)){
					$next = $key;
					break;
				}
			}
			//$rest = array_diff($actList['Out'],$actCs ) ;
			//var_dump($next);
			if(endKey($actList['Out']) == $next){
				$app['finished'] = true;
			}
			if($next == $act)
				return true;
			else
				$rsErrorMsg .= "-ifActProper-App[".$appID."] expect Action[".$next."]";
		}
		//echo "Act Proper END";
	}
	function ifTrayProper($tray,$pid){
		//检验托盘是否可用
		global $connection;
		global $rsErrorMsg;
		global $app;
		global $actList;
		//先获取同货物包，下已完成的动作列表
		$query = "SELECT * FROM `wtrays`";
		$query .= " WHERE `wtID`= ".$tray;
		//echo "$query";
		if ($result = mysqli_query($connection, $query)) {			
			while($row = mysqli_fetch_array($result)){
				//var_dump($row);
				if($row['twStatus'] != "空闲" || $row['wpID'] == $pid ){
					$rsErrorMsg .= "-ifTrayProper-Tray [".$tray."] busy!!";
					return false;
				}
				else{
					echo "got tray";
					return true;
				}
			}
		}
		else{
		 echo '[we have a problem]: '.mysqli_error($connection);
		}
	}

	//数据库中插入一个 wactions
	function AddAct($wpID,$actType){
		global $connection;
		global $rsErrorMsg;
		global $app;
		
		$query = "INSERT INTO `wactions` (`wpID`, `actType`,`actDate`,`appID`,`actComplete`) ";
		$query .= "VALUES ( ".$wpID.", '".$actType."', ";
		$query .= "\"".date("Y-m-d")."\", ";
		$query .= $app['appID'].", ";
		$query .= " 1)";
		
		$result = mysqli_query($connection,$query);
		if(!$result){
		 $rsErrorMsg .= '-AddAct-[we have a problem]: '.mysqli_error($connection);
		 return "mysql error";
		}
	}

	//数据库更新App
	function updateApp($appToTrayID){
		global $connection;
		global $rsErrorMsg;
		global $app;
		
		$query = "UPDATE `wapplications` SET `appToTrayID`= ".$appToTrayID;
		$query .= ", `appDate`= \"".date("Y-m-d")."\"";
		if($app['finished']){
		$query .= "`appComplete`= 1";
		$query .= ", `appStatus`= \"关闭\"";
		}
		$query .=" WHERE `appID` = ".$app['appID'];
		echo $query;
		$result = mysqli_query($connection,$query);
		if(!$result){
		 $rsErrorMsg .= '-updateApp-[we have a problem]: '.mysqli_error($connection);
		 return "mysql error";
		}
	}
	//更新wtrays{wpID,actID,twStatus} //actID暂时不更新
	function updateTrays($wpID,$tray){
		global $connection;

		
		$query = "UPDATE `wtrays` SET `twStatus`= \"忙碌\"";
		$query .= ", `wpID` = ".$wpID;
		$query .= " WHERE `wtID`= ".$tray;
		echo $query;
		$result = mysqli_query($connection,$query);
		if(!$result){
		 $rsErrorMsg .= '-updateTrays-[we have a problem]: '.mysqli_error($connection);
		 return "mysql error";
		}
		

	}
	function ProcessCommand($input)
	{
		global $rsErrorMsg;
		global $app;
		$command = explode(":",$input);	
		list($pid,$act,$tray) = $command;
		
		
		echo $pid."---".$act."---".$tray;
		//查验PID、act、tray是否合法
		//$validate = true;
		//找到wapplications里 wpID = pid; appStatus=开启的应用单，理论上只有一个，有多的报错
		if( getOpenAppByID($pid) != 1){
			var_dump($rsErrorMsg);
			return ;
		}
		//检查应用单是否被签名
		echo "[[[app]]]";
		var_dump($app);
		if( isset($app['appSignned']) ){
			if($app['appSignned'] != 1){
				$rsErrorMsg .= "app[".$app['appID']."] have to get permit before act";
				var_dump($rsErrorMsg);
				return;
			}
		}
		//查验act是否合法
		if( ifActProper($act,$pid,$app['appID']) != 1){
		var_dump($rsErrorMsg);
			return;
		}
		
		//查验TRAY是否可用 $tray
		if ( ifTrayProper($tray,$pid) != 1 ){
			var_dump($rsErrorMsg);
			return;
		}
		
		//插入act、更新app、tray、package
		//需要信息 act.{wpID,actType,actOperator,appID,actDate,actComplete,actDevID}
		AddAct($pid,$act);
		
		//更新app {appToTrayID,appDeviceID,appComplete,appStatus)
		updateApp($tray);
		//更新wtrays{wpID,actID,twStatus} //actID暂时不更新
		updateTrays($pid,$tray);
		
		
		//var_dump($app);
		if($rsErrorMs == "")
			$rsErrorMs ="success";
		return($rsErrorMsg);
	}

?>