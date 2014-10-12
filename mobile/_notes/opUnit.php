<?php
	include_once("_db.php");
	global $connection;
	//如果针对某货单操作

	//var_dump($_POST);
	//var_dump($_GET);
	echo "----".$_POST['count']."------".$_POST['updateTime'];
	$nArray = array_merge_recursive($_POST,array("trayID"=>$_GET['trayID']));
	if($_GET['act'] == "add"){
		//增加新存储单位
		unset($nArray['trayCaseSize']);
		unset($nArray['SlotPosition']);
		unset($nArray['InStockID']);
		unset($nArray['wpID']);
		unset($nArray['DialogRfid']);

		$query ="INSERT INTO `wareUnit` (";
		foreach($nArray as $key=>$value){
			$query .= "`".$key."` ,";
		}
		$query = trim($query, ",");
		$query .= ") VALUES (";
		foreach($nArray as $key=>$value){
			$query .= "'".$value."',";
		}
		$query = trim($query, ",");
		$query .= ")";
		echo $query;
		/*
		if ($result = mysqli_query($connection, $query)) {
			echo $result;
		}
		else{
		 echo '[we have a problem]: '.mysqli_error($connection);
		}
		*/
	}


?>