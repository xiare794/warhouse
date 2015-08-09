<?php
	//PHP search Function Union 
	//contorl by GET key 
	//like phpSearch.php?rfid=str
	
	include_once("_db.php");
	//更新数据表符合id的某一项数据
	//table数据表
	//tKey 目标数据名
	//tVal 目标数据值
	//idKey id数据名
	//idVal id值
	//UPDATE `wAppIn` SET `appMaitou`="AMFJJDFS" WHERE `appID`=147
	//http://localhost/ws/warhouse/WebServer/phpUpdate.php?table=wAppIn&&idKey=appID&&idVal=147&&tKey=appMaitou&&tVal=FDSJJFDS
	if(isset($_GET["table"]) && isset($_GET["idKey"]) && isset($_GET["idVal"]) && isset($_GET["tKey"]) && isset($_GET["tVal"]) )
	{
		$query = "UPDATE `".$_GET['table']."` SET `".$_GET['tKey']."`=\"".$_GET['tVal']."\" WHERE `".$_GET['idKey']."`= ".$_GET['idVal'];
		if ($result = mysqli_query($connection, $query)) {
			
			echo $result;
		}
		else{
			 echo '[we have a problem]: '.mysqli_error($connection);
		}
	}

	if (isset($_GET["updateQuery"])) {
		$query = $_GET["updateQuery"];
		if ($result = mysqli_query($connection, $query)) {
			
			echo $result;
		}
		else{
			 echo '[we have a problem]: '.mysqli_error($connection);
		}
	}



?>