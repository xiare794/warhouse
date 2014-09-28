<?php
	include_once("_db.php");
	//如果针对某货单操作
	if(isset($_GET['addNew'])){	
		//检查是否存在一样的，
		$find = 0;
		$count = 0;
		$query = "SELECT * FROM `wareItems` WHERE `length`=\"".$_POST['length']."\" AND `width`=\"".$_POST['width']."\" AND `height`=\"".$_POST['height']."\" AND  `weight`=\"".$_POST['weight']."\" ";
		if ($result = mysqli_query($connection, $query)) {
			echo $query;
			while($row = mysqli_fetch_array($result)){
				echo "找到相同的";
				$find = $row['wiID'];
				$count = $row['count']+1;
				
			}
		}
		else{
		 echo '[we have a problem]: '.mysqli_error($connection);
		}
		
		echo "find".$find;
		if($find){
			
			//存在就更新货品尺寸的count
			$query = "UPDATE `wareItems` SET `count` = \"".$count."\" WHERE `wiID`=\"".$find."\" ";
			if ($result = mysqli_query($connection, $query)) {
				echo "update".$find;
			}
			else{
			 echo '[we have a problem]: '.mysqli_error($connection);
			}
		}
		else{
			//存在就更新货品尺寸的count
			$query = "INSERT INTO `wareItems` (`length`,`width`,`height`,`weight`) VALUES( \"".$_POST['length']."\", \"".$_POST['width']."\", \"".$_POST['height']."\", \"".$_POST['weight']."\") ";
			if ($result = mysqli_query($connection, $query)) {
				echo "update".$find;
			}
			else{
			 echo '[we have a problem]: '.mysqli_error($connection);
			}
			
			//不存在就新建一个
		}
		
		
		//$query ="SELECT * `rfid`=\"".$_POST['DialogRfid']."\"";
		/*
		$query ="UPDATE `wTrays` SET `twStatus`=\"使用中\",`wtAppID`=\"".$_POST['appID']."\" ,`UpdateTime`=\"".$_POST['updateTime']."\" ,`wpID`=\"".$_POST['wpID']."\" ,`twWareCount`=\"".$_POST['trayCaseCount']."\"  WHERE `rfid`=\"".$_POST['DialogRfid']."\"";
		if ($result = mysqli_query($connection, $query)) {
			echo $result;
		}
		else{
		 echo '[we have a problem]: '.mysqli_error($connection);
		}*/
	}
	//如果是绑定操作
	if(isset($_GET['getLi'])){
		$query ="SELECT * FROM `wareItems` WHERE 1 ORDER BY `updateTime` DESC";
		$SizeList = array();
		if ($result = mysqli_query($connection, $query)) {
				
				while($row = mysqli_fetch_array($result)){
					array_push($SizeList,$row);
				}
		}
		else{
		 echo '[we have a problem]: '.mysqli_error($connection);
		}
		
		echo json_encode($SizeList);
	
	}
	//printf ("Updated records: %d\n", mysql_affected_rows());
	
?>
