<?php
	//PHP search Function Union
	//用户添加费用接口
	
	include_once("_db.php");
	header("Content-Type: text/html;charset=utf-8");
	
	//如果是添加费用
	if(isset($_GET["insertFee"])){
		//分拆多项fee
		$hint = "";
		$prehandle = 0; // 0初始，1成功，-1失败
		if(isset($_GET["FeeStr"])){
			//FeeStr多条间以|分割，内部以_分割
			$mStr = explode("|", $_GET["FeeStr"]);
			$str = explode("_", $mStr[0]); //分拆数据
			//0wfAppID;1wfAppType;2wfValue;3wfName费用名称
			$query = "SELECT * FROM `wFee` WHERE `wfAppID` = \"".$str[0]."\"";
			if($result = mysqli_query($connection, $query)){
				$rowcount=mysqli_num_rows($result);
				if($rowcount>0){
					//已存在数据 ，删除
					$query  = "DELETE FROM `wFee` WHERE `wfAppID` = \"".$str[0]."\"";
					if($result = mysqli_query($connection, $query)){
						//删除成功,继续添加
						$prehandle = 1;
					}
					else{
						$hint = "已存在，删除失败";
						echo "0".$hint;
						$prehandle = -1;
					}
				}
				else{
					$hint = "不存在可以添加";
					$prehandle = 1;
				}

				
				if($prehandle === 1){
					$query = "";
					//初始query，进循环
					//echo "\nmStr";
					//var_dump($mStr);
					$query .= "INSERT INTO `wFee` (`wfAppID`,`wfAppType`,`wfValue`,`wfName`,`wfCreateTime`) VALUES ";
					foreach ($mStr as $val=>$arr)
					{
						$arr = $arr;
						//echo $arr."||";
						//echo $val.".....";
						$str = explode("_", $arr);
						$query.="( \"".$str[0]."\", \"".$str[1]."\", \"".$str[2]."\", \"".$str[3]."\", \"".date('Y-m-d H:i:s')."\")";

						if($val<count($mStr)-1){
							$query .=",";
						}


					}
					//echo "startQuery:".$query;
					//echo "</br>结果"."\n";

					
					if ($result = mysqli_query($connection, $query))
				  {
				  	echo $result;
				  	//echo "\n 1 record added";
				  }
				  else{
				  	echo $result;
				  	die('Error: ' . mysqli_error($connection));
				  }

				}
			}

			
		}
	}
	
	


?>