<?php
	//PHP search Function Union 
	//contorl by GET key 
	//like phpSearch.php?rfid=str
	
	include_once("_db.php");
	header("Content-Type: text/html;charset=utf-8");
	//search RFID
	if(isset($_GET['rfid'])){
		//echo "[inPHP function] phpSearch.php?rfid=".$_GET['rfid'];
		$query ="SELECT * FROM `wTrays` WHERE `rfid`=\"".$_GET['rfid']."\"";
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
	//单where查找
	if(isset($_GET['table']) && isset($_GET['attr']) && isset($_GET['val']) ){
		$List = array();
		$query = "SELECT * FROM `".$_GET['table']."` WHERE `".$_GET['attr']."` = \"".$_GET['val']."\"";
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
	
	//无条件查询
	if(isset($_GET['table']) && !isset($_GET['attr'])){
		$List = array();
		$query = "SELECT * FROM `".$_GET['table']."`";
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


	//纯query
	if(isset($_GET['query'])){
		$List = array();
		$query = $_GET['query'];
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

	if(isset($_GET['registerRfid'])){
		$rfids = explode(";", $_GET['registerRfid']);
		//$rfids = split(";", $_GET['registerRfid']);
		$query ="INSERT INTO `wtrays`(`rfid`, `twStatus`,`UpdateTime`) VALUES ( \"".$rfids[0]."\", \"空闲\", \"".date('Y-m-d H:i:s')."\")";
		if ($result = mysqli_query($connection, $query)) {
			if($result=="1"){
				echo "成功添加";
			}
			else{
				echo "添加失败";
			}

		}
		else{
			 echo '[we have a problem]: '.mysqli_error($connection);
		}
	}

	//纯query
	if(isset($_GET['insert'])){
		$query = $_GET['insert'];
		if ($result = mysqli_query($connection, $query)) {
			if($result=="1"){
				echo "成功添加";
			}
			else{
				echo "添加失败";
			}

		}
		else{
			 echo '[we have a problem]: '.mysqli_error($connection);
		}
		
	}

	//http://192.168.199.120/ws/warhouse/WebServer/phpSearch.php?mobile=InsertUnitForTray_addUinttrayID=6&userID=33&height=24&count=30&width=57&itemType=1&length=47&userName=admin&appID=147
	if(isset($_GET['mobile'])){
		//修改托盘
		//将trayid对应的托盘 状态置仓库外，wtAppID置appID，twWareCount置count，Updatetime更新
		$out = array();
		if($_GET['mobile']=="InsertUnitForTray_addUint")
		{
			$actTime = date('Y-m-d H:i:s');



			//1.更新tray
			//UPDATE `wtrays` SET `twStatus`="空闲",`wtAppID`="0",`twWareCount`="0" WHERE `wtID`="6"
			
			$query = "UPDATE `wtrays` SET `twStatus`=\"仓库外\",`wtAppID`=\"".$_GET['appID']."\",`twWareCount`=\"".$_GET['count']."\",`UpdateTime`=\"".$actTime."\" WHERE `wtID`=\"".$_GET['trayID']."\"";
			if ($result = mysqli_query($connection, $query))
			{
				if($result=="1"){
						array_push($out,array("InsertUnitForTray_editTray"=>"true"));
						//echo "{'InsertUnitForTray_addUint':'1'}";
					}
					else{
						array_push($out,array("InsertUnitForTray_editTray"=>"修改托盘失败"));
					}

			}
			else{
					array_push($out,array("InsertUnitForTray_editTray"=>mysqli_error($connection)));
					//echo '[we have a problem]: '.mysqli_error($connection);
			}

			//2.添加unit
			//INSERT INTO `wunit`(`appID`, `trayID`, `wiName`, `itemType`, `width`, `length`, `height`, `count`, `updateTime`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6],[value-7],[value-8],[value-9],[value-10],[value-11],[value-12])
			$iquery = "INSERT INTO `wunit`(`appID`, `trayID`, `wiName`, `itemType`, `width`, `length`, `height`, `count`, `updateTime`) VALUES (";
			$iquery.= "\"".$_GET['appID']."\", ";
			$iquery.= "\"".$_GET['trayID']."\", ";
			$iquery.= "\"".$_GET['name']."\", ";
			$iquery.= "\"".$_GET['itemType']."\", ";
			$iquery.= "\"".$_GET['width']."\", ";
			$iquery.= "\"".$_GET['length']."\", ";
			$iquery.= "\"".$_GET['height']."\", ";
			$iquery.= "\"".$_GET['count']."\", ";
			$iquery.= "\"".$actTime."\" )";
			if ($result = mysqli_query($connection, $iquery))
			{

				if($result=="1")
				{
					array_push($out,array("InsertUnitForTray_addUint"=>"true"));
					//echo "{'InsertUnitForTray_addUint':'1'}";
				}
				else{
					array_push($out,array("InsertUnitForTray_addUint"=>"增加货物失败"));
				}

			}
			else{
					array_push($out,array("InsertUnitForTray_addUint"=>mysqli_error($connection)));
					//echo '[we have a problem]: '.mysqli_error($connection);
			}
			
			//3.添加Log
			//INSERT INTO `wactions`(`actUserID`, `actType`, `actTime`, `InStockID`, `trayID`, `appID`, `actContent`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6],[value-7],[value-8],[value-9])
			$lquery = "INSERT INTO `wactions`(`actUserID`, `actType`, `actTime`, `InStockID`, `trayID`, `appID`, `actContent`) VALUES (";
			$lquery.= "\"".$_GET['userID']."\", ";
			$lquery.= "\"loadStock\", ";
			$lquery.= "\"".$actTime."\", ";
			$lquery.= "\"".$_GET['InStockID']."\", "; 
			$lquery.= "\"".$_GET['trayID']."\", ";
			$lquery.= "\"".$_GET['appID']."\", ";
			$lquery.= "\"".$_GET['userName']."对托盘装载了".$_GET['count']."箱货物，属于货单".$_GET['appID'].",编号".$_GET['InStockID']."\" )";
			//echo $lquery;
			if ($result = mysqli_query($connection, $lquery))
			{
				if($result=="1"){
						array_push($out,array("InsertUnitForTray_AddLog"=>"true"));
						//echo "{'InsertUnitForTray_addUint':'1'}";
					}
					else{
						array_push($out,array("InsertUnitForTray_AddLog"=>"修改记录失败"));
					}

			}
			else{
					array_push($out,array("InsertUnitForTray_AddLog"=>mysqli_error($connection)));
					//echo '[we have a problem]: '.mysqli_error($connection);
			}

			echo json_encode($out);
		}
			
			
		

		
	}

	
	

?>