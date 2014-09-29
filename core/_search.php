<?php
	//2014.09.28
	//PHP search Function Union 
	//contorl by GET key 
	//like phpSearch.php?rfid=str
	
	include_once("_db.php");
	
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
			 echo "query -".$query."-<br>";
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
			echo "query -".$query."-<br>";
			 echo '[we have a problem]: '.mysqli_error($connection);
		}
		echo json_encode($List);
	}

	//跨table查询
	
	if(isset($_GET['tableA']) ){
		$List = array();
		$query =  "SELECT wAgents.waName, warePackages.wpID, wAgents.waType ";
		$query .= "FROM wAgents ";
		$query .= "INNER JOIN warePackages " ;
		$query .= "ON wAgents.waID=warePackages.wpAgentID ";

		if ($result = mysqli_query($connection, $query)) {
			while($row = mysqli_fetch_array($result)){
				array_push($List,$row);
			}
		}
		else{
			echo "query -".$query."-<br>";
			 echo '[we have a problem]: '.mysqli_error($connection);
		}
		echo json_encode($List);
	}

	//跨table查询
	
	if(isset($_GET['tableB']) ){
		$List = array();
		$query =  "SELECT * ";
		$query .= "FROM wAgents a, warePackages p, wApplications app ";
		$query .= "WHERE a.waID=p.wpAgentID AND p.wpID=app.wpID";

		if ($result = mysqli_query($connection, $query)) {
			while($row = mysqli_fetch_array($result)){
				array_push($List,$row);
			}
		}
		else{
			echo "query -".$query."-<br>";
			 echo '[we have a problem]: '.mysqli_error($connection);
		}
		echo json_encode($List);
	}

	//跨table FROM  WHERE全查询
	if(isset($_GET['from']) &&isset($_GET['where']) &&!isset($_GET['select'])){
		$List = array();
		$query =  "SELECT * ";
		$query .= "FROM ".$_GET['from'];
		$query .= " WHERE ". $_GET['where'];
		
		if ($result = mysqli_query($connection, $query)) {
			while($row = mysqli_fetch_array($result)){
				array_push($List,$row);
			}
		}
		else{
			echo "query -".$query."-<br>";
			 echo '[we have a problem]: '.mysqli_error($connection);
		}
		echo json_encode($List);
	}

	//纯query
	if(isset($_GET['query'])){
		$List = array();
		$query =  $_GET['query'];
		
		if ($result = mysqli_query($connection, $query)) {
			while($row = mysqli_fetch_array($result)){
				array_push($List,$row);
			}
		}
		else{
			echo "query -".$query."-<br>";
			 echo '[we have a problem]: '.mysqli_error($connection);
		}
		echo json_encode($List);
	}
	
	
?>