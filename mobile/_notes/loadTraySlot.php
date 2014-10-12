<?php
	//托盘进货仓或出货仓
	include_once("_db.php");
	$hint = "";
	$continue = true;
	$trayID = null;
	if( isset($_GET['act'])){
		if($_GET['act']=="in"){
			//var_dump($_POST);	
			//上架检查
			//是否有slotID //slotID是否可用
			if(isset($_POST['SlotPosition'])){
				$query ="SELECT * FROM `wSlots` WHERE `wSlotID`=".$_POST['SlotPosition'];
				if ($result = mysqli_query($connection, $query)) {
					//$num_rows = mysqli_num_rows($result);
					//echo "打印result".$num_rows;
					//var_dump($result['num_rows']);
					while($row = mysqli_fetch_array($result)){
						if(!isset($row['wtID'])){
							//var_dump($row);
							if($row['wtID'] != null){
								$hint = $_POST['SlotPosition']."位置已用，请查实";
								$continue = false;
								//echo $hint;	
							}
							else{
								//slotID可用
							}
						}
						else{
							//slotID为空
							$continue = false;
							$hint ="该货位已占用";
						}
					}
				}
				else{
					 echo '[we have a problem]: '.mysqli_error($connection);
					 $hint .= "无效仓位参数";
					 $continue = false;
				}
			}
			
			// 是否有trayID
			if(isset( $_POST['DialogRfid']) && isset($_POST['appID']) ){
				if($continue){
					$query ="SELECT * FROM `wTrays` WHERE `rfid`=\"".$_POST['DialogRfid']."\" AND `twStatus`=\"使用中\" AND `wtAppID` =\"".$_POST['appID']."\"";
					if ($result = mysqli_query($connection, $query)) {
							$num_rows = mysqli_num_rows($result);
							if($num_rows>0){
								//echo "找到".$num_rows."行符合";
								$row = mysqli_fetch_array($result);
								$trayID = $row['wtID'];
							}
							else{
								$hint .= "<br>rfid对应状态和对应表单未找到";
								$continue = false;
							}
					}
					else{
						echo '[we have a problem]: '.mysqli_error($connection);
						$continue = false;
					}
				}
			}
			else{
				$hint .= "<br>缺少RFID码或者APPID";
				$continue = false;
			}
			
			//appID是否是入库，是否开放
			if(isset($_POST['appID'] )){
				if( $continue){
					$query ="SELECT * FROM `wApplications` WHERE `appID`=\"".$_POST['appID']."\" AND `appComplete`=0 ";
					if ($result = mysqli_query($connection, $query)) {
							$num_rows = mysqli_num_rows($result);
							if($num_rows>0){
								//echo "找到".$num_rows."条符合表单";
							}
							else{
								$hint .= "选取表单已完成，无法修改";	
								$continue = false;
							}
					}
					else{
						echo '[we have a problem]: '.mysqli_error($connection);
						$continue = false;
					}
				}
			}
			
			//开始上架，更新Slot,更新Tray,完成
			if($continue && $trayID){
				$query ="UPDATE `wSlots` SET `wtID`=\"".$trayID."\"  WHERE `wSlotID`=\"".$_POST['SlotPosition']."\"";
				if ($result = mysqli_query($connection, $query)){
					$hint .="<br>货位".$_POST['SlotPosition']."更新成功";
				}
				else{
					echo '[we have a problem]: '.mysqli_error($connection);
					$hint .="<br>货位".$_POST['SlotPosition']."更新失败";
					$continue = false;
				}
				echo "trayID=".$trayID;
				$query ="UPDATE `wTrays` SET `twStatus`=\"货架\", `UpdateTime`=\"".$_POST['updateTime']."\"  WHERE `wtID`=".$trayID;
				echo $query;
				if ($result = mysqli_query($connection, $query)){
					$hint .="<br>托盘".$trayID."上架成功";
				}
				else{
					echo '[we have a problem]: '.mysqli_error($connection);
					$hint .="<br>托盘".$trayID."上架失败";
					$continue =  false;
				}
				if($continue == true){
				  $hint .= "<br>更新成功";
				}
			}
		}
		
		
		if($_GET['act']=="out"){
			//var_dump($_POST);	
			$trayID = NULL;
			//下架检查
			//查询托盘信息
			if(isset( $_POST['DialogRfid']) && isset($_POST['appID']) ){
				if($continue){
					$query ="SELECT * FROM `wTrays` WHERE `rfid`=\"".$_POST['DialogRfid']."\" AND `twStatus`=\"货架\" AND `wtAppOutID` =\"".$_POST['appID']."\"";
					if ($result = mysqli_query($connection, $query)) {
							$num_rows = mysqli_num_rows($result);
							if($num_rows>0){
								//echo "找到".$num_rows."行符合";
								$row = mysqli_fetch_array($result);
								$trayID = $row['wtID'];
							}
							else{
								
								$hint .= "<br>rfid对应状态和对应表单未找到";
								$continue = false;
							}
					}
					else{
						echo '[we have a problem]: '.mysqli_error($connection);
						$continue = false;
					}
				}
			}
			else{
				$hint .= "<br>缺少RFID码或者APPID";
				$continue = false;
			}
			
			//检查下架货品
			//检查下架数量
			//更新slot信息
			//更新trayID信息
			$query ="UPDATE `wSlots` SET `wtID`='NULL' WHERE `wtID`=\"".$trayID."\"";
			if ($result = mysqli_query($connection, $query)){
				echo $result;
				$hint .="<br>货位".$_POST['SlotPosition']."更新成功";
			}
			else{
				echo '[we have a problem]: '.mysqli_error($connection);
				$hint .="<br>货位".$_POST['SlotPosition']."更新失败";
				$continue = false;
			}
			
			$query ="UPDATE `wTrays` SET `twStatus`='使用中' WHERE `wtID`=\"".$trayID."\"";
			if ($result = mysqli_query($connection, $query)){
				$hint .="<br>托盘".$trayID."更新成功";
			}
			else{
				echo '[we have a problem]: '.mysqli_error($connection);
				$hint .="<br>托盘".$trayID."更新失败";
				$continue = false;
			}
						
			//开始上架，更新Slot,更新Tray,完成
		}
		//echo $hint;
	}
	else
		echo "something else";
	
	
	
?>