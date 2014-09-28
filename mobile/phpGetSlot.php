<?php
	include_once("_db.php");
	//使用group by 和where复合获得目前空闲的选项
	//如果没有指定 tswareHouse
	//	-group by tsWareHouse
	$query = "";
	if(!isset($_GET['tsWareHouse'])){
		$query ="SELECT * FROM `wSlots` GROUP BY (`tsWareHouse`)";
	}
	else if(isset($_GET['tsWareHouse'])&&!isset($_GET['tsPosRow'])){
		$query ="SELECT * FROM `wSlots` WHERE `tsWareHouse` = \"".$_GET['tsWareHouse']."\" GROUP BY(`tsPosRow`) DESC";
	}
	else if(isset($_GET['tsWareHouse'])&&isset($_GET['tsPosRow']) &&!isset($_GET['tsPosCol']) ){
		$query ="SELECT * FROM `wSlots` WHERE `tsWareHouse` = \"".$_GET['tsWareHouse']."\" AND `tsPosRow` = \"".$_GET['tsPosRow']."\" GROUP BY(`tsPosCol`) DESC";
		//echo $query;
	}
	else if(isset($_GET['tsWareHouse'])&&isset($_GET['tsPosRow']) &&isset($_GET['tsPosCol']) &&!isset($_GET['tsPos']) ){
		$query ="SELECT * FROM `wSlots` WHERE `tsWareHouse` = \"".$_GET['tsWareHouse']."\" AND `tsPosRow` = \"".$_GET['tsPosRow']."\" AND `tsPosCol` = \"".$_GET['tsPosCol']."\" GROUP BY(`tsPosFloor`) DESC";
	}
	

	$sLots = array();
	if ($result = mysqli_query($connection, $query)) {
			
			while($row = mysqli_fetch_array($result)){
				//echo $row['appOperator'];
				array_push($sLots,$row);
				//array_push($stack,$row[$IDname]." ".$row[$Name]);
			}
	}
	else{
		 echo '[we have a problem]: '.mysqli_error($connection);
		}
	
	echo json_encode($sLots);
?>