﻿<!DOCTYPE html><html lang="cn">  <head>    <meta charset="utf-8">	<meta http-equiv="Content-Type" content="text/html"; charset="utf-8">	<meta name="viewport" content="width=device-width, initial-scale=1.0">	<title>填写进仓通知</title>  </head>	<body>		<?php 		//php part		var_dump($_GET);		var_dump($_POST);				//取出可用wpAgentID		include "_db.php";		global $connection;		$agentsPool= array();		$query = "SELECT * FROM `wAgents`";		$result = mysqli_query($connection, $query);		if(!$result){		 echo '[we have a problem]: '.mysqli_error($connection);		}		while($row = mysqli_fetch_array($result)){			array_push ($agentsPool,$row);		}					include "_db.php";		global $connection;				$wpID = 0;		if(isset($_POST['InStockID'])){			//查找这个进仓编号是否出现过，出现过就只增加进仓单，否则同时需要增加货物包(进仓编号)			$query = "SELECT * FROM `wApplications` WHERE `InStockID`= \"".$_POST['InStockID']."\" AND `appType`= \"in\" ";			$result = mysqli_query($connection, $query);			if(!$result){			 echo '[we have a problem]: '.mysqli_error($connection);			}			$sameIDApp = array();			$wpID = 0;			while($row = mysqli_fetch_array($result)){				array_push($sameIDApp,$row['InStockID']);				$wpID = $row['wpID'];				//array_push ($agentsPool,$row);				//$wpID = $row['wpID'];			}			if(count($sameIDApp)>0){				echo "找到进仓编号为".$_POST['InStockID']."的单据";				var_dump($sameIDApp);			}			//如果未找到对应货物包，先增加货物包			else{				echo "未找到进仓编号为".$_POST['InStockID']."的进仓单，创建新货物包";				$query = "INSERT INTO warePackages ( wpAgentID ) VALUES( ";				$query .= "\" ".$_POST['agent']."\" )";				$result = mysqli_query($connection, $query);				if(!$result){				 echo '[we have a problem]: '.mysqli_error($connection);				}				$wpID = mysqli_insert_id($connection);				echo "wpID = ".$wpID."<br>";			}						//增加进仓单			if(isset($_POST)){				$query = "INSERT INTO wApplications (";				$query .= "wpID, appName, appCount, InStockID, appBookingDate,  appMaitou, appType) VALUES (";				$query .= "\"".$wpID."\","; //wpID				$query .= "\"".$_POST['name']."\","; //appName				$query .= "\"".$_POST['packageNumer']."\","; //appCount				$query .= "\"".$_POST['InStockID']."\","; //InStockID				$query .= "\"".$_POST['time']."\","; //appBookingDate				$query .= "\"".$_POST['Maito']."\","; //appMaitou				$query .= "\"in\""; //类型 入库				$query .= " )";								$result = mysqli_query($connection, $query);				if(!$result){				 echo '[we have a problem]: '.mysqli_error($connection);				}				else{					echo "成功添加入库单".mysqli_insert_id($connection)."---入库";				}							}					}			?>			<h3>填写新增的进仓通知</h3>	<form action="wPackageInsert.php" method="post">	 选择货代: <select name="agent"  >		<?php			for($i=0; $i<count($agentsPool); $i++){				echo "<option value=\"";				echo $agentsPool[$i]['waID']."\">";				echo $agentsPool[$i]['waName']."-".$agentsPool[$i]['waContact'];				echo "</option>";			}		?>	 </select><br>	 进仓时间<input type="date" name="time"><br>	 箱数<input type="number" name="packageNumer"><br>	 品名<input type="text" name="name"><br>	 合同编号<input type="text" name="ContractID"><br>	 进仓编号<input type="text" name="InStockID"><br>	 唛头<input type="text" name="Maito"><br>	<input type="submit">	</form>		</body></html>