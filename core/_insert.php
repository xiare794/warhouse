<?php
	include_once("_db.php");

	$table = "wApplications";
	if(isset($_GET['table']))
	{
		$table = $_GET['table'];
	}

	unset($_POST['waID']);
	unset($_POST['new']);

	insertNewTableItem($table,$_POST);

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
		  echo $query;
		}
		else{
		 echo '[we have a problem]: '.mysqli_error($connection);
		 echo $query;
		}
	}
?>