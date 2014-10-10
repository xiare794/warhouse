<?php
	date_default_timezone_set("Asia/Hong_Kong");
	$t=time();
	$appCreateTime = date("Y-m-j G:i:s",$t);
	$panelHintTitle = "";
	$panelHint = "";
	$panelType = "";
	//echo $appCreateTime;
	//新增app响应 新增在后面
	/*
	if(isset($_POST['new'])){
		$attrs=array("appName","InStockID","appMaitou","appCount","deliverComp","deliverTruckID","deliverDriver","deliverMobile","extraInfo","appType","appBookingDate");
		$vals = array($_POST['appName'],$_POST['InStockID'],$_POST['appMaitou'],$_POST['appCount'],$_POST['deliverComp'],$_POST['deliverTruckID'],$_POST['deliverDriver'],$_POST['deliverMobile'],$_POST['extraInfo'],$_POST['appType'],date('Y-m-d H:i:s'));
		insert('wApplications',$attrs, $vals);
	}*/
	//修改app响应
	if(isset($_POST['edit'])){
	  	$varify = true;
	  	//确认签署、可以被修改
	  	$query = "SELECT `appComplete`,`appName` FROM `wApplications` WHERE `appID` = ".$_POST['appID'];
	  	$result = mysqli_query($connection, $query);
	  	while($row = mysqli_fetch_array($result)){
	  		if( $row['appComplete'] == "1"){
	  			$panelHint = "[".$row['appName']."]货单已完成,无法修改".$appCreateTime;
	  			$panelHintTitle = "修改失败";
	  			$panelType = "bs-callout-danger";
	  			$varify = false;
	  		}
	  	}
	  	
	  	if($varify){
		  $attrs=array("appID","appName","InStockID","appMaitou","appCount","deliverComp","deliverTruckID","deliverDriver","deliverMobile","extraInfo","appType","appBookingDate");
			$vals = array($_POST['appID'],$_POST['appName'],$_POST['InStockID'],$_POST['appMaitou'],$_POST['appCount'],$_POST['deliverComp'],$_POST['deliverTruckID'],$_POST['deliverDriver'],$_POST['deliverMobile'],$_POST['extraInfo'],$_POST['appType'],date('Y-m-d H:i:s'));
			$updateResult = update('wApplications',$attrs, $vals);
			if($updateResult == 1){
				$panelHintTitle = "库单修改";
				$panelHint = $_POST['appID'].$_POST['appName'].date('Y-m-d H:i:s')."成功修改";
				$panelType = "bs-callout-warning";
				//添加备忘
				//actUserID,actType,actTime,InStockID,actContent
				$actUserID = $_SESSION['userID'];
				$actType = "editApp";
				$actTime = date('Y-m-d H:i:s');
				$InStockID = $_POST['InStockID'];
				$actContent = $_SESSION['user']."对".$InStockID."的.".$_POST['appType']."库单进行修改操作;";
				$para = array("actUserID"=>$actUserID,"actType"=>$actType,"actTime"=>$actTime,"InStockID"=>$InStockID,"actContent"=>$actContent);
				//echo "<br>";
				addMemo($para);
			}
			else{
				$panelHintTitle = "库单修改失败";
				$panelHint = $updateResult;
				$panelType = "bs-callout-danger";
			}
		}
		
	}
	//删除app响应
	if(isset($_POST['delete'])){
		$attrs="appID";
		$vals =$_POST['appID'];
		$deleteResult = delete('wapplications',$attrs, $vals);
		if($deleteResult == 1){
			//考虑删除货物包,后期加入
			$panelHintTitle = "库单修改成功";
			$panelHint = "货物包".$_POST['appID'].$_POST['appName']."已成功删除";
			$panelType = "bs-callout-warning";
			//添加备忘
			//actUserID,actType,actTime,InStockID,actContent
			$actUserID = $_SESSION['userID'];
			$actType = "deleteApp";
			$actTime = date('Y-m-d H:i:s');
			$InStockID = $_POST['InStockID'];
			$actContent = $_SESSION['user']."对".$InStockID."的.".$_POST['appType']."库单进行删除操作";
			$para = array("actUserID"=>$actUserID,"actType"=>$actType,"actTime"=>$actTime,"InStockID"=>$InStockID,"actContent"=>$actContent);
			//echo "<br>";
			addMemo($para);
		}
		else{
			$panelHintTitle = "库单删除失败";
			$panelHint = $deleteResult;
			$panelType = "bs-callout-danger";
		}
	}
	
	//生成出库响应
	if(isset($_POST['newOut'])){
		$hint = "";
		//对应入库单号
		$preAppInID = $_POST['appID'];
		$app_wpID = null;
		$pQuery = "SELECT `wpID` FROM `wApplications` WHERE `appID` = \"".$preAppInID."\"";
		$pQuery .= " AND `appComplete` = 1";
		if ($result = mysqli_query($connection, $pQuery)) {
			if($row = mysqli_fetch_array($result))
				$app_wpID = $row['wpID'];
		}
		else
			$hint .= '[we have a problem]: '.mysqli_error($connection);
			
		$hint .= "入库单号:$preAppInID;包号:$app_wpID";
		
		$query = "INSERT INTO `wApplications` ";
		$query .= "(`appName`, `InStockID`, `appMaitou`, ";
		$query .= "`appCount`, `appType`, `appBookingDate`,`wpID`)";
		$query .= "VALUE( \"".$_POST['appName']."\", \"".$_POST['InStockID']."\", \"".$_POST['appMaitou']."\", ";
		$query .= "\"".$_POST['appCount']."\", \"".$_POST['appType']."\", '".$appCreateTime."', '".$app_wpID."')";
		//$hint .= $query;
		if ($result = mysqli_query($connection, $query)) {
			if($result){
				$rcappid = mysqli_insert_id($connection);
				$hint .= "<br>编号".$rcappid. "的库单(" .$_POST['appName']. ")已添加";
				
				//当result正常时，更新所有wTrays的wtAppOutID
				$query = "UPDATE `wTrays` SET `wtAppOutID` = \"".$rcappid."\" WHERE `wtAppID` = ".$preAppInID." ";
				//$hint .= "<br>".$query;
				if ($result = mysqli_query($connection, $query)) {
					$hint .= "<br>属于".$preAppInID."货物包出库单号为:".$rcappid;
				}
				else
					$hint .= '[we have a problem]: '.mysqli_error($connection);	
			}
			else
			$hint .= '[we have a problem]: '.mysqli_error($connection);
		}
		$panelHintTitle = "出库单生成";
		$panelHint = $hint;
		$panelType = "bs-callout-warning";
		//echo "<pre >$hint ";
		//echo "<br>";
		//添加备忘
		//actUserID,actType,actTime,InStockID,actContent
		$actUserID = $_SESSION['userID'];
		$actType = "newOutApp";
		$actTime = date('Y-m-d H:i:s');
		$InStockID = $_POST['InStockID'];
		$actContent = $_SESSION['user']."对".$InStockID."的.".$_POST['appType']."库单进行新建操作";
		$para = array("actUserID"=>$actUserID,"actType"=>$actType,"actTime"=>$actTime,"InStockID"=>$InStockID,"actContent"=>$actContent);
		addMemo($para);

		//echo "</pre>";
	}
	
	//新增app响应，包含了检查货物包
	if(isset($_POST['new'])){
		//先检索是否已存在库单
		$app_wpID = null;
		$pQuery = "SELECT `wpID` FROM `wApplications` WHERE `InStockID` = \"".$_POST['InStockID']."\"";
		if ($result = mysqli_query($connection, $pQuery)) {
			if($row = mysqli_fetch_array($result))
				$app_wpID = $row['wpID'];
		}
		else
			echo '[we have a problem]: '.mysqli_error($connection);
			
		
		$query = "INSERT INTO `wApplications` ";
		$query .= "(`appName`, `InStockID`, `appMaitou`, `appCount`, ";
		$query .= "`appType`, `appBookingDate`, `deliverComp`, `deliverTruckID`, `deliverDriver`, `deliverMobile`, `extraInfo`) ";
		$query .= "VALUE( \"".$_POST['appName']."\", \"".$_POST['InStockID']."\", \"".$_POST['appMaitou']."\", ";
		$query .= "\"".$_POST['appCount']."\", \"".$_POST['appType']."\", '".$appCreateTime."', ";
		$query .= "\"".$_POST['deliverComp']."\", \"".$_POST['deliverTruckID']."\", \"".$_POST['deliverDriver']."\", \"".$_POST['deliverMobile']."\", \"".$_POST['extraInfo']."\" )";
		//echo $query;
		$hint = "";
		if ($result = mysqli_query($connection, $query)) {
			if($result){
				$rcappid = mysqli_insert_id($connection);
				$hint .= "编号".$rcappid. "的库单(" .$_POST['appName']. ")已添加";
				
				//创建好库单后更新或者创建新货物包
				if($app_wpID){
					$query = "UPDATE `wApplications` SET `wpID` = \"".$app_wpID."\" WHERE `appID` = ".$rcappid." ";
					if ($result = mysqli_query($connection, $query)) {
						//echo $query;
						$hint .= "<br>属于".$app_wpID."货物包";
					}
					else
						$hint .= '[we have a problem]: '.mysqli_error($connection);	
				}
				else{
					$query = "INSERT INTO `warePackages` (`wpAgentID`) VALUES  (".$_POST['agentSelect'].")";
					//echo "<br>".$query;
					if ($result = mysqli_query($connection, $query)) {
						$rc = mysqli_insert_id($connection);
						$hint .= "<br>属于".$rc."货物包";
						$query = "UPDATE `wApplications` SET `wpID` = ".$rc." WHERE `appID` = \"".$rcappid."\" ";
					 	if(!$result = mysqli_query($connection, $query))
						$hint .= '<br>[we have a problem]: '.mysqli_error($connection);	
					}
					else
						$hint .= '[we have a problem]: '.mysqli_error($connection);		
				}
				$panelHintTitle = "入库单生成";
				$panelHint = $hint;
				$panelType = "bs-callout-warning";
				//添加备忘
				//actUserID,actType,actTime,InStockID,actContent
				$actUserID = $_SESSION['userID'];
				$actType = "newApp";
				$actTime = date('Y-m-d H:i:s');
				$InStockID = $_POST['InStockID'];
				$actContent = $_SESSION['user']."对".$InStockID."的.".$_POST['appType']."库单进行新建操作";
				$para = array("actUserID"=>$actUserID,"actType"=>$actType,"actTime"=>$actTime,"InStockID"=>$InStockID,"actContent"=>$actContent);
				addMemo($para);

				//echo "</pre>";
			}
			else {
				$panelHintTitle = "入库单失败";
				$panelHint = $result;
				$panelType = "bs-callout-danger";
			}
		}
		else{
			echo '[we have a problem]: '.mysqli_error($connection);
		}
	}
	function addMemo($para){
		global $connection;
		
		$query ="INSERT INTO `wActions` (";
		foreach($para as $key=>$value){
			$query .= "`".$key."` ,";
		}
		$query = trim($query, ",");
		$query .= ") VALUES (";
		foreach($para as $key=>$value){
			$query .= "'".$value."',";
		}
		$query = trim($query, ",");
		$query .= ")";
		
		if ($result = mysqli_query($connection, $query)) {
			echo $result;
		  echo $query;
		}
		else{
		 echo '[we have a problem]: '.mysqli_error($connection);
		 echo $query;
		}
	}
?>