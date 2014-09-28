
<?php
	include_once("_db.php");
	
	$query ="SELECT * FROM `wApplications` WHERE `appSignned`=1";
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
	//$appList['succuss']=1;
	
	//var_dump($appList);
	//按格式输出
	
	echo json_encode($appList);
?>
