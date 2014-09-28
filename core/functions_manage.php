<?php
	//根据Query 获取数据表长度
	function getTableLengthByQuery($query){
		global $connection;
		if($stmt = mysqli_prepare($connection, $query) ){
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);
			return mysqli_stmt_num_rows($stmt);
		}
		if(!$stmt){
		 echo '[we have a problem]: '.mysqli_error($connection);
		 
		}
		return 0;
	}
	//取得数据表长度
	function getTableLength($tableName="wtrays"){
		global $connection;
		$query = "SELECT * FROM `".$tableName."`";
		if($stmt = mysqli_prepare($connection, $query) ){
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);
			return mysqli_stmt_num_rows($stmt);
		}
		if(!$stmt){
		 echo '[we have a problem]: '.mysqli_error($connection);
		 
		}
		return 0;
	}
	
	//取得某数据表中的ID和名称数组
	function getTableIDNameArray($tableName="wAgents",$IDname="waID",$Name = "waName"){
		global $connection;
		//$query = "SELECT * FROM `".$tableName."`";
		$query = "SELECT  `".$IDname."` ,  `".$Name."` FROM  `".$tableName."` LIMIT 0 , 30";
		$stack = array();
		if ($result = mysqli_query($connection, $query)) {
			
			while($row = mysqli_fetch_array($result)){
				array_push($stack,$row[$IDname]." ".$row[$Name]);
			}
		return $stack;
		}
	}
	
	//取得某数据表中的某一属性值相等的数据
	function getTableByA_equel_B($tableName="wAgents",$Name="waID",$Value = "waName"){
		global $connection;
		//$query = "SELECT * FROM `".$tableName."`";
		//$query ="SHOW COLUMNS FROM ".$tableName;
		//var_dump(mysqli_query($connection, $query));
		$query = "SELECT * FROM  `".$tableName."` WHERE `".$Name."` = ".$Value;
		//echo $query;
		$stack = array();
		if ($result = mysqli_query($connection, $query)) {
			while($row = mysqli_fetch_array($result)){
				array_push($stack,$row);
				//array_push($stack,$row['actType']."-".$row['actDate']);
			}
		return $stack;
		}
	}
	
	//根据货物包ID获取正在使用托盘
	function getCurrTraysByPID($pID){
		global $connection;
		$trays = array();
		$query = "SELECT * FROM `wTrays` ";
		$query .= "WHERE wpID=".$pID;
		$result = mysqli_query($connection, $query);
		if(!$result){
		 echo '[we have a problem]: '.mysqli_error($connection);
		}
		while($row = mysqli_fetch_array($result)){
			//var_dump($row);
			array_push($trays,$row["wtID"]);
		}
		return $trays;
	}
	
	//**************通用插入更新
	//插入一个新的item
	function insert($table, $para,$data){
		global $connection;
		$query = "INSERT INTO ".$table." (";
			
		foreach($para as $attr){
			$query .= $attr;
			if(array_search($attr,$para) != (count($para)-1)) $query .= ", ";
		}
		$query .= ") VALUES (";
		for( $i = 0; $i<count($data); $i++){
			
			$query .= "'".$data[$i]."'";
			if($i < count($data)-1) $query .= ", ";
		}
		$query .= ")";

		$result = mysqli_query($connection,$query);
		if(!$result){
		 echo '[we have a problem]: '.mysqli_error($connection)."<br>query [".$query."]";
		}else{
			echo "成功添加".$data[0].$data[1]."到".$table;
		}
	}
	//更新一个item
	function update($table, $para,$data){
		//更新参数/值中的第一个是查找key
		global $connection;
		$return  = "";
		$query = "UPDATE `".$table."` SET ";
		for($i = 1; $i<count($para); $i++){
			if($i != 1)
				$query .= ", ";
			$query .= "`".$para[$i]."`=\"".$data[$i]."\"";	
		}
		$query .= " WHERE `".$para[0]."` = \"".$data[0]."\"";
		
		$result = mysqli_query($connection,$query);
		if(!$result){
		 echo '[we have a problem]: '.mysqli_error($connection)."<br>query [".$query."]";
		 $return .='[we have a problem]: '.mysqli_error($connection)."<br>query [".$query."]";
		}else{
			echo "成功修改:[";
			$return .= "成功修改:[";
			for($i=0; $i<count($para); $i++){
				echo $para[$i].":".$data[$i]."|";
				$return .= $para[$i].":".$data[$i]."|";
			}
			echo "]";
			$return .="]";
		}
		return $return;
	}
	//删除一个item
	function delete($table, $para,$data){
		global $connection;
		$query = "DELETE FROM `".$table."`";
		$query .= " WHERE `".$para."` = \"".$data."\"";
		
		$result = mysqli_query($connection,$query);
		if(!$result){
		 echo '[we have a problem]: '.mysqli_error($connection)."<br>query [".$query."]";
		}else{
			echo "成功删除".$table."中的元素";
		}
	}
	
	//插入一个新的货物包
	function insertWarePackages($data){
		global $connection;
		$para = array("wpAgentID", "wpLossStatus", "wpLocked", "wpFullName", "wpCount","wpPackageStander",
				  "wpCustomerStatus","wpMAITOU","wpTotalWeight","wpWeight","wpVolume",
				  "wpInStockDays","wpPlanDays","wNotePrivate","wNotePublic","wCloseInfo");
	
		//INSERT INTO table_name (column1, column2, column3,...)
		//VALUES (value1, value2, value3,...)
		$query = "INSERT INTO warePackages (";
		for($i = 0; $i<count($para); $i++){
			$query .= $para[$i];
			if($i<count($para)-1)
				$query .= ", ";
		}
		$query .=") VALUES (";
		$query .= (int)$data["wpAgentID"].", ";
		$query .= "'".$data["wpLossStatus"]."', ";
		$query .= "'".(bool)$data["wpLocked"]."', ";
		$query .= "'".$data["wpFullName"]."', ";
		$query .= (int)$data["wpCount"].", ";
		$query .= "'".$data["wpPackageStander"]."', ";
		$query .= "'".$data["wpCustomerStatus"]."', ";
		$query .= "'".$data["wpMAITOU"]."', ";
		$query .= (int)$data["wpTotalWeight"].", ";
		$query .= (int)$data["wpWeight"].", ";
		$query .= (int)$data["wpVolume"].", ";
		$query .= (int)$data["wpInStockDays"].", ";
		$query .= (int)$data["wpPlanDays"].", ";
		$query .= "'".$data["wNotePrivate"]."', ";
		$query .= "'".$data["wNotePublic"]."', ";
		$query .= "'".$data["wCloseInfo"]."') ";
		
		echo $query;
		$result = mysqli_query($connection,$query);
		if(!$result){
		 echo '[we have a problem]: '.mysqli_error($connection);
		}
	}
	
	//插入一个新的执行任务
	function insertRecords($data){
		global $connection;
		$para = array('wpID', 'actType','actDate');//, "actDate", "appID", "actOperator","actComplete", "actDevID");
		$actions = array("startLoading", "Marked", "Moving", "GetInStock", "inSlot", "Check", "取货出库", "出门", "装车", "出库完成");
		$query = "INSERT INTO `wActions` (`wpID`, `actType`,`actDate`) VALUES (";
		$query .= (int)$data[0].", ";
		$query .= "'".$actions[$data[1]]."', ";
		//$query .= "'".date(DATE_RFC822)."'";
		$query .= "'".date('Y-m-d')."'";
		$query .= ") ";
		//$query .= "'".$data["actDate"]."') ";
		
		echo $query;
		$result = mysqli_query($connection,$query);
		if(!$result){
		 echo '[we have a problem]: '.mysqli_error($connection);
		 return "mysql error";
		}
		else
		 return "succuss";
	}
	//更新的货物包
	function updateWarePackages($data){
		global $connection;
		$para = array("wpAgentID", "wpLossStatus", "wpLocked", "wpFullName", "wpCount","wpPackageStander",
				  "wpCustomerStatus","wpMAITOU","wpTotalWeight","wpWeight","wpVolume",
				  "wpInStockDays","wpPlanDays","wNotePrivate","wNotePublic","wCloseInfo");
	
		$query = "UPDATE `warePackages` SET ";
		
		for($i = 0; $i<count($para); $i++){
			if( isset($data[$para[$i]] ) ){
				$query .= "`".$para[$i]."`=\"".$data[$para[$i]]."\"";
				if($i<count($para)-1)
					$query .= ", ";
			}
		}
		$query .= " WHERE `wpID`=\"".$data["wpID"]."\";";

		echo $query;
		$result = mysqli_query($connection,$query);
		if(!$result){
		 echo "<br>".'[we have a problem]: '.mysqli_error($connection);
		}
	}
	/*删除选定的货物包
	function deleteWarePackages($data){
		echo "cal function deleteWarePackages";
		global $connection;
		$query = "DELETE FROM `warePackages` ";
		$query .= " WHERE `wpID`=\"".$data["wpID"]."\";";
		echo $query;
		$result = mysqli_query($connection,$query);
		if(!$result){
		 echo "<br>".'[we have a problem]: '.mysqli_error($connection);
		}
	}*/	
	//获得通过page货物包
	function getWarePackagebyPage($page = 1){
		global $connection;
		$query = "SELECT * FROM `warePackages` WHERE `wpID` =".$page;
		if ($result = mysqli_query($connection, $query)) {
			
			while($row = mysqli_fetch_array($result)){
				return $row;
			}
		}
	}
	//打印表单 para是mysqli_query返回的结果
	function printAppTable($result){
		$Str = "<table class=\"table table-hover\">";
			$Str .= "<tr>";
			$Str .= "<th>货物包名称</th>";
			$Str .= "<th>表单序号</th>";
			$Str .= "<th>货物包序号</th>";
			$Str .= "<th>类型</th>";
			$Str .= "<th>预订日期</th>";
			$Str .= "<th>表单日期</th>";
			$Str .= "<th>对应托盘</th>";
			$Str .= "<th>许可</th>";
			$Str .= "<th>完成</th>";
			$Str .= "<th>表单状态</th>";
			$Str .= "</tr>";
		while($row = mysqli_fetch_array($result)){
			//var_dump($row);
			$Str .= "<tr>";
			$Str .= "<th>".$row["appName"]."</th>";
			$Str .= "<th>".$row["appID"]."</th>";
			$Str .= "<th>".$row["wpID"]."</th>";
			$Str .= "<th>".$row["appType"]."</th>";
			$Str .= "<th>".$row["appBookingDate"]."</th>";
			$Str .= "<th>".$row["appDate"]."</th>";
			$Str .= "<th>".$row["appToTrayID"]."</th>";
			$Str .= "<th>".$row["appSignned"]."</th>";
			$Str .= "<th>".$row["appComplete"]."</th>";
			$Str .= "<th>".$row["appStatus"]."</th>";
			$Str .= "</tr>";
			//array_push($trays,$row["wtID"]);
		}
		$Str .="</table>";
		//echo $Str;
		return($Str);
	}
	//打印所有开启表单
	function printAllAppOpen(){
		global $connection;
		//获取表单
		$query = "SELECT * FROM `wapplications` ";
		$query .= "WHERE `appStatus`= \"开启\"";
		$result = mysqli_query($connection, $query);
		if(!$result){
		 echo '[we have a problem]: '.mysqli_error($connection);
		 return;
		}
		if(mysqli_data_seek($result,0	)){
			$Str = printAppTable($result);
		}
		else{
			$Str ="<p>表单数据库表单数量为零</p>";
		}
		echo $Str;
	}
	//将指定Pid的单据分入库、移库、出库打印为表格
	function printAppByPID($pid){
		global $connection;
		//获取表单
		$query = "SELECT * FROM `wapplications` ";
		$query .= "WHERE wpID=".$pid;
		$result = mysqli_query($connection, $query);
		if(!$result){
		 echo '[we have a problem]: '.mysqli_error($connection);
		 return;
		}
		if(mysqli_data_seek($result,0	)){
			$Str = printAppTable($result);
		}
		else{
			$Str ="<p>未找到此货物包下的出入库单，请创建新单。</p>";
			
		}
		$Str .="<a  href=\"#myModal\" data-toggle=\"modal\" class=\"btn btn-default\" id=\"createApp\">新建</a>";
		echo $Str;
		//return $trays;
	}
	
	function wpIDHaveCertApp($wpID,$type){
		global $connection;
		$query = "SELECT * FROM `wapplications` ";
		if($type == "入库"){
			//找到开启状态的入库单
			$query .= "WHERE `wpID`=".$wpID." AND `appStatus` = \"开启\" AND `appType` = \"入库\"";
		}
		else if($type == "移库"){
			//找到完成状态的入库单
			$query .= "WHERE `wpID`=".$wpID." AND `appComplete` = '1' AND `appType` = \"入库\"";
		}
		else if($type == "出库"){
			//查找所有入库、移库单未完成
			$query .= "WHERE `wpID`=".$wpID." AND `appStatus` = \"开启\" AND `appType` IN (\"入库\",\"移库\")";
		}
		echo "<br>wpIDHaveCertApp:".$query;
		$result = mysqli_query($connection, $query);
		if(!$result){
		 echo '[we have a problem]: '.mysqli_error($connection);
		}
		$find = mysqli_data_seek ($result,0);
		if($type==="入库"){
			if($find) return false;
			else return true;
		}
		if($type==="移库"){
			if($find) return true;
			else return false;
		}
		if($type==="出库"){
			if($find) return false;
			else return true;
		}
		
		if( !($find && $type==="入库") || ($find && $type=="移库") || !($find && $type=="出库"))
			return true;
		else
			return false;
	}
	//返回一个货物包所有app的表单号
	function getAppByWpID($wpid){
		//返回一数组
		$retArray = array("入库"=>"","移库"=>"","出库"=>"");
		global $connection;
		$query = "SELECT * FROM `wapplications` ";
		$query .= "WHERE `appType` IN (\"入库\",\"移库\",\"出库\") AND `wpID` = ".$wpid;
		$result = mysqli_query($connection, $query);
		if(!$result){
		 echo '[we have a problem]: '.mysqli_error($connection);
		}
		while($row = mysqli_fetch_array($result)){
			if($retArray[$row['appType']] != null)
				$retArray[$row['appType']] .= ",";
			$retArray[$row['appType']] .= $row['appID']; 
		}
		var_dump($retArray);
		return $retArray;
	}
	function insertApplication($data){
		global $connection;
		global $errorMsg;
		$para = array("wpID", "appType", "appName", "appCount", "appBookingDate","appOperator",
				  "appComplete","appStatus","appSignned","appDate");
		//检查wpID下是否有开启状态表单,
		//如果有：结束插入操作，建议修改完成或关闭上一个表单
		if(wpIDHaveCertApp($data['wpID'],$data['appType'])){
			//echo "没找到";
			$query = "INSERT INTO `wapplications` (";
			for($i = 0; $i<count($para); $i++){
				$query .= "`".$para[$i]."`";
				if($i<count($para)-1)
					$query .= ", ";
			}
			$query .=") VALUES (";
			$query .= (int)$data["wpID"].", ";
			$query .= "'".$data["appType"]."', ";
			$query .= "'".$data["appName"]."', ";
			$query .= (int)$data["appCount"].", ";
			$query .= "'".$data["appBookingDate"]."', ";
			$query .= "'".$data["appOperator"]."', ";
			$query .= "'".(bool)$data["appComplete"]."', ";
			$query .= "'".$data["appStatus"]."', ";
			$query .= "'".$data["appSignned"]."', ";
			$query .= "'".$data["appDate"]."') ";
			
			echo $query;
			$result = mysqli_query($connection,$query);
			if(!$result){
			 echo '[we have a problem]: '.mysqli_error($connection);
			}
			//将package内的出入移库单更新
			//获取之前的数据
			$query = "SELECT `wpInAppID`, `wpMoveAppID`, `wpOutAppID` FROM `warepackages` WHERE `wpID` = ".$data["wpID"];
			$result = mysqli_query($connection,$query);
			$oldValue;
			if(!$result){
			 echo '[we have a problem]: '.mysqli_error($connection);
			}
			while($row = mysqli_fetch_array($result)){
				if( $data["appType"] == "入库")
					$oldValue = $row['wpInAppID'];
				if( $data["appType"] == "移库")
					$oldValue = $row['wpMoveAppID'];
				if( $data["appType"] == "出库")
					$oldValue = $row['wpOutAppID'];
			}
			//将之前的单号以"，"和新加入的隔开
			$appIDs = getAppByWpID($data["wpID"]);
			
			$query = "UPDATE `warepackages` "; 
					$query .= "SET `wpInAppID`=\"".$appIDs['入库']."\",";
					$query .= " `wpMoveAppID`=\"".$appIDs['移库']."\",";
					$query .= " `wpOutAppID`=\"".$appIDs['出库']."\"";
			$query .= " WHERE `wpID` = ".$data["wpID"];
			$result = mysqli_query($connection,$query);
			if(!$result){
			 echo '[we have a problem]: '.mysqli_error($connection);
			}
			//$query = "UPDATE `warepackages` SET " 
		}
		else{
			if($data["appType"] == "入库")
				$errorMsg .= "存在使用中的入库单<br>";
			if($data["appType"] == "移库")
				$errorMsg .= "存在未完成的入库单<br>";
			if($data["appType"] == "出库")
				$errorMsg .= "存在未完成的入库单或移库单<br>";
			$errorMsg .= "新建失败";
		}
	}
	
	function operatorApp($action,$id){
		global $connection;
		global $errorMsg;
		echo $action;
		$query ="";
		if($action == "delete"){
			$query = "DELETE FROM `wapplications` WHERE `appID` = ".(int)$id;
		}
		if($action == "close"){
			$query = "UPDATE `wapplications` SET `appStatus` = \"关闭\" WHERE `appID` = ".(int)$id;
		}
		echo $query;
		$result = mysqli_query($connection,$query);
		if(!$result){
		 $errorMsg .= '[we have a problem]: '.mysqli_error($connection);
		}
		/*if(mysqli_data_seek($result,0)){
			echo "执行";
		}
		else
			echo "失败";
			*/
	}
	
	function curPageURL() {
	 $pageURL = 'http';
	 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	 $pageURL .= "://";
	 if ($_SERVER["SERVER_PORT"] != "80") {
	  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	 } else {
	  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	 }
	 return $pageURL;
	}
	

?>







