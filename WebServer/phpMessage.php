<?php
	//PHP search Function Union
	//用户添加费用接口
	
	include_once("_db.php");
	header("Content-Type: text/html;charset=utf-8");
	//echo "123";
	//如果是添加费用
	if(isset($_GET["MessageType"])){
		//分拆多项fee
		$hint = "";
		$prehandle = 0; // 0初始，1成功，-1失败
		if(isset($_GET["mPara"]) ){
			//mPara多条间以|分割
			//UserID|InstockID|trayID|slotID|appID|actContent
			$mPara = explode("|", $_GET["mPara"]);
			$query = "INSERT INTO `wActions` (`actUserID`,`actType`,`actTime`,`InStockID`,`trayID`,`slotID`,`appID`,`actContent`) VALUES ";
			$query.="(";
			$query.="\"".$mPara[0]."\", ";
			$query.="\"".$_GET["MessageType"]."\", ";
			$query.="\"".date('Y-m-d H:i:s')."\", ";
			$query.="\"".$mPara[1]."\", ";
			$query.="\"".$mPara[2]."\", ";
			$query.="\"".$mPara[3]."\", ";
			$query.="\"".$mPara[4]."\", ";
			$query.="\"".$mPara[5]."\" ";
			$query.=")";
			
			//echo $query;
			if ($result = mysqli_query($connection, $query))
		  {
		  	echo $result;
		  }
		  else{
		  	echo $result;
		  	die('Error: ' . mysqli_error($connection));
		  }
		}
	}

	if(isset($_GET["GetMessage"])){
		$count = 10;
		$interval = "MONTH"; //默认为月
		if(isset($_GET['msgCount'])){
			$count = $_GET['msgCount'];
		}
		if(isset($_GET['interval'])){
			$interval = $_GET['interval'];
		}
		$query = "SELECT * FROM `wactions` WHERE ".$interval."(NOW())=".$interval."(`actTime`) AND `waRep`=1 ORDER BY `actTime` DESC LIMIT 0,".$count;
		//SELECT * FROM `wactions` WHERE MONTH(NOW())=MONTH(`actTime`) ORDER BY `actTime` DESC LIMIT 0,10
		$List = array();
		if ($result = mysqli_query($connection, $query)) {
			while($row = mysqli_fetch_array($result)){
				array_push($List,$row);
			}
		}
		else{
			 echo '[we have a problem]: '.mysqli_error($connection);
		}
		echo json_encode($List);
	}	

	if(isset($_GET["Read"])){
		$query = "UPDATE `wactions` SET `waRep`=0 WHERE `actID`=\"".$_GET["Read"]."\"";
		if ($result = mysqli_query($connection, $query)) {
			echo $result;
		}
		else{
			 echo '[we have a problem]: '.mysqli_error($connection);
		}

	}

	if(isset($_GET["truckID"])){
		
		//$query = "SELECT * FROM `wactions` WHERE `actType`=\"指定司机\" AND `waRep`=1 ORDER BY `actTime` DESC ";
		//SELECT * FROM `wactions` WHERE MONTH(NOW())=MONTH(`actTime`) ORDER BY `actTime` DESC LIMIT 0,10
		//SELECT a.actID, a.actContent,i.appName, i.appMaitou, i.OpInput, i.OpCounter, i.OpFork, i.InStockID, i.appPreCount FROM wActions a, wAppIn i WHERE a.actType="指定司机" AND a.appID=i.appID ORDER BY  a.actTime DESC 
		$query = "SELECT a.actID, a.actContent,i.appName, i.appMaitou, i.OpInput, i.OpCounter, i.OpFork, i.InStockID, i.appPreCount, i.appID FROM wActions a, wAppIn i WHERE i.OpFork=".$_GET['truckID']." AND a.actType=\"指定司机\" AND a.appID=i.appID ORDER BY  a.actTime DESC ";

		$List = array();
		if ($result = mysqli_query($connection, $query)) {
			while($row = mysqli_fetch_array($result)){
				array_push($List,$row);
			}
		}
		else{
			 echo '[we have a problem]: '.mysqli_error($connection);
		}
		echo json_encode($List);
	}

?>