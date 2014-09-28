<?php
	//PHP search Function Union 
	//contorl by GET key 
	//like phpSearch.php?rfid=str
	
	include_once("_db.php");
	//search RFID
	if(isset($_GET['rfid'])){
		//echo "[inPHP function] phpSearch.php?rfid=".$_GET['rfid'];
		$query ="SELECT * FROM `wTrays` WHERE `rfid`=".$_GET['rfid'];
		
		if ($result = mysqli_query($connection, $query)) {
			
			while($row = mysqli_fetch_array($result)){
				echo ($row['twStatus']);	
				//array_push($appList,$row);
				//array_push($stack,$row[$IDname]." ".$row[$Name]);
			}
		}
		else{
			 echo '[we have a problem]: '.mysqli_error($connection);
		}
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

?>