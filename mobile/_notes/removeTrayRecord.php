<?php
	include_once("_db.php");
	//如果针对某货单操作
	//var_dump($_POST);
	if(isset($_POST['InStockID']) && isset($_POST['trayCaseCount'])){	
		//检索是否是全部出库
		//echo "come here?";
		//var_dump($_POST);
		$outType = "total";
		$outCount = $_POST['trayCaseCount'];
		$remainCount = 0;
		$query ="SELECT * FROM `wTrays` WHERE `rfid`=\"".$_POST['DialogRfid']."\"";
		if ($result = mysqli_query($connection, $query)) {
			$row = mysqli_fetch_array($result);
			if( $row['twWareCount'] >$outCount){
				$outType = "part";	
				$remainCount = $row['twWareCount'] -$outCount;
			}			
		}
		
		$query ="UPDATE `wTrays` SET";
		if( $outType != "total"){
			$query .= " `twStatus`=\"使用中\", `twWareCount`=\"".$remainCount."\", `wtAppID`=\"".$_POST['appID']."\" , ";
		}
		else{
			$query .= " `twStatus`=\"空闲\", `wtAppID`=\"\", ";
			$query .= "`wtAppOutID`=\"\", `twWareCount`=\"\", ";
			$query .= "`twWareItemID`=\"\", `wpID`=\"\",";
		}
		
		$query .= " `UpdateTime`=\"".$_POST['updateTime']."\" WHERE `rfid`=\"".$_POST['DialogRfid']."\"";
		//echo $query;
		if ($result = mysqli_query($connection, $query)) {
			echo $result;
		}
		else{
		 echo '[we have a problem]: '.mysqli_error($connection);
		}
	}
	//如果是绑定操作
	if(isset($_POST['DialogTrayID']) && isset($_POST['DialogRfid'])){
		$query ="UPDATE `wTrays` SET `rfid`=\"".$_POST['DialogRfid']."\"  WHERE `wtID`=\"".$_POST['DialogTrayID']."\"";
		if ($result = mysqli_query($connection, $query)) {
			echo $result;
		}
		else{
		 echo '[we have a problem]: '.mysqli_error($connection);
		}
	
	}
	//printf ("Updated records: %d\n", mysql_affected_rows());
	
?>
