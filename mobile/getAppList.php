
<?php
	include_once("_db.php");
	$start = 0;
	$step = 5;
	$assigned = true;
	$descend = true;
	$type = "in";
	$onlyUncomplete = false;
	
	//赋值外部信息
	if(isset($_GET['step'])  && isset($_GET['start'])&& isset($_GET['Assigned'])  && isset($_GET['timeDescend'])){
		$start = $_GET['start'];
		$step = $_GET['step'];
		$assigned = $_GET['Assigned'];
		$descend = $_GET['timeDescend'];
		$type = $_GET['appType'];
		$onlyUncomplete = $_GET['onlyUnCom'];
	}
	
	$query = "SELECT * FROM `wApplications` ";
	//如果取单独信息
	if(isset($_GET['idx'])){
		$query .="WHERE `appID`=".$_GET['idx'];
	}
	elseif(isset($_GET['all'])){
		$query .="";	
	}
	elseif(isset($_GET['type']) && isset($_GET['unfinished']) ){
		$query .="WHERE `appType`=\"".$_GET['type']."\" AND `appComplete` = 0";
	}
	else{
		//筛选签字
		//$query ="SELECT * FROM `wApplications` ";
		$query .= " WHERE `appType`=\"".$type."\" ";
		if($assigned == "true"){
			$query .= "AND `appSignned`=1 ";
		}
		if($onlyUncomplete == "true"){
			$query .= "AND `appComplete`= \"0\" ";
			//echo "uncomp = ".$onlyUncomplete."<br>";
		}
		if($descend == true){
			$query .= "ORDER BY appID DESC ";	
		}
		else{
			$query .= "ORDER BY appID ";
		}
			
		//筛选起始终止
		$query .= "LIMIT ".$start.",".$step;
	}
	
	//echo $query;
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
