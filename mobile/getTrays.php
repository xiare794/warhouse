
<?php
	include_once("_db.php");
	
	if(isset($_GET['idx'])){
		
		$query ="SELECT * FROM `wTrays` WHERE `wpID`=".$_GET['idx'];
	}
	else
	$query ="SELECT * FROM `wTrays` WHERE 1";
	
	$appList = array();
	if ($result = mysqli_query($connection, $query)) {
			
			while($row = mysqli_fetch_array($result)){
				//echo $row['appOperator'];
				array_push($appList,$row);
				//array_push($stack,$row[$IDname]." ".$row[$Name]);
			}
	}
	else{
		 echo '[we have a problem]: '.mysqli_error($connection);
		}
	
	echo json_encode($appList);
?>