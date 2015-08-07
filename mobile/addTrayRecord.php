<?php
	include_once("_db.php");
	//如果针对某货单操作
	$tableName = "wUnit";
	$array = $_POST;

	function insertNewTableItem($tableName, $array){
		global $connection;
		unset($array['trayCaseSize']);
		unset($array['SlotPosition']);
		unset($array['InStockID']);
		unset($array['wpID']);
		
		//$a=array("appID"=>"23","wiName"=>"随便什么名","width"=>"129");
		$query ="INSERT INTO `".$tableName."` (";
		foreach($array as $key=>$value){
			$query .= "`".$key."` ,";
		}
		$query = trim($query, ",");
		$query .= ") VALUES (";
		foreach($array as $key=>$value){
			$query .= "'".$value."',";
		}
		$query = trim($query, ",");
		$query .= ")";
		
		if ($result = mysqli_query($connection, $query)) {
			echo $result;
			echo "appID=".$array['appID'];
		  echo "trayID=".$array['trayID'];
		  echo "wiName=".$array['wiName'];
		  echo $query;
		}
		else{
		 echo '[we have a problem]: '.mysqli_error($connection);
		 echo $query;
		}
	}
	
	
	if(isset($_POST['weight'])){	
		//var_dump($_POST);
		insertNewTableItem("wUnit",$_POST);
		
	}
	//如果是绑定操作
	if(isset($_POST['DialogTrayID']) && isset($_POST['DialogRfid'])){
		//如果是绑定操作
		
		if($_POST['DialogRfid'] != "unbind"){
			//绑定操作需要先检查
			$query = "SELECT * FROM `wTrays` WHERE `rfid` = \"".$_POST['DialogRfid']."\"";
			if($result = mysqli_query($connection, $query)){
				$field_cnt = mysqli_num_rows($result);
				if(	$field_cnt==0){
					$query ="UPDATE `wTrays` SET `rfid`=\"".$_POST['DialogRfid']."\"  WHERE `wtID`=\"".$_POST['DialogTrayID']."\"";
					if ($result = mysqli_query($connection, $query)) {
						echo $result;
					}
					else{
					 echo '[we have a problem]: '.mysqli_error($connection);
					}
				}
				else{
					$row = mysqli_fetch_array($result);
					echo '该编码已绑定给另一托盘#'.$row['wtID']."------".$field_cnt."---".$query;
				}
			}
			else{
			 echo '[we have a problem]: '.mysqli_error($connection);
			}
		}
		//如果是解除绑定操作
		else{
			
			$query = "SELECT * FROM `wTrays` WHERE `wtID` = \"".$_POST['DialogTrayID']."\"";
			if($result = mysqli_query($connection, $query)){
				$field_cnt = mysqli_num_rows($result);
				$row = mysqli_fetch_array($result);
				if($field_cnt == 1 && $row['twStatus'] == "空闲"){
					//允许解除绑定
					$query ="UPDATE `wTrays` SET `rfid`=\"\"  WHERE `wtID`=\"".$_POST['DialogTrayID']."\"";
					if ($result = mysqli_query($connection, $query)) {
						echo $result;
					}
					else{
					 echo '[we have a problem]: '.mysqli_error($connection);
					}
				}
				else{
					echo "托盘#".$row['wtID']."处在使用阶段，无法解除绑定";	
				}
				
			}
			else{
			 echo '[we have a problem]: '.mysqli_error($connection);
			}
			
		}
	}
	//printf ("Updated records: %d\n", mysql_affected_rows());
	
?>
