<?php
	include_once("_db.php");
	//var_dump($_POST);
	if(isset($_POST['userName']) && isset($_POST['Password']) ){
		
		$query ="SELECT * FROM `wUsers` WHERE ";
		$query .= " (`wuName`=\"".$_POST['userName']."\" AND `wuPassword`=\"".$_POST['Password']."\")";
		$query .=" OR ";
		$query .= "( `wuNamePY`=\"".$_POST['userName']."\" AND `wuPassword`=\"".$_POST['Password']."\")";
	}
	else
	$query ="SELECT * FROM `wUsers` WHERE 1";
	
	//echo $query;
	$Users = array();
	if ($result = mysqli_query($connection, $query)) {
			
			while($row = mysqli_fetch_array($result)){
				//echo $row['appOperator'];
				array_push($Users,$row);
				//array_push($stack,$row[$IDname]." ".$row[$Name]);
			}
	}
	else{
		 echo '[we have a problem]: '.mysqli_error($connection);
		}
	
	echo json_encode($Users);
?>