<?php
	include_once("_db.php");

	$table = "wActions";
	//时间使用php现在时间
	if(isset($_GET['actTime']))
		$_GET['actTime']= date('Y-m-d H:i:s');

	insertNewTableItem($table,$_GET);
	//var_dump($_GET);
	function insertNewTableItem($tableName, $array){
		global $connection;
		
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
		  //echo $query;
		}
		else{
		 echo '[we have a problem]: '.mysqli_error($connection);
		 //echo $query;
		}
	}
?>