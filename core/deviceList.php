<?php require_once("config.php"); //session ?>
<!DOCTYPE html>
<html lang="cn">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Loading Bootstrap -->
    <link href="../css/bootstrap.css" rel="stylesheet">

    <!-- Loading Flat UI -->
    <!--<link href="../css/flat-ui.css" rel="stylesheet">-->
    <!--<link href="../css/flat-ui-demo.css" rel="stylesheet">-->
    <title>设备列表</title>
  </head>
  <body>
  	<?php 
  		echo $_SESSION['user'];
  		include "_db.php";
  		global $connection;
  		$query = "SELECT * FROM wUsers WHERE `wuName`= '".$_SESSION['user']."'";
  		$result = mysqli_query($connection, $query);
  		//var_dump($result);
  		if(!$result){
  		 echo '[we have a problem]: '.mysqli_error($connection);
  		}
  		while( $row = mysqli_fetch_array($result) ){
  			$userPermit = array('admin'=>$row['admin'],'manager'=>$row['manager'],'operator'=>$row['operator']); 
  		}
  		var_dump($userPermit);
  		
  		
  		
  	?>
  	<div class="alert alert-success">
  		<?php if($userPermit['manager']){
  			addNewBind("h02","田琳");}
  			else {
  				echo "此用户没有权限";
  			}
  			
  		?> 
  	</div>
    <?php 
    	function output_deviceList() {
    		$file = "../deviceBind.json";
    		$json  = json_decode(file_get_contents($file),true);
    		$output ="";
    		for( $i = 0; $i<count($json); $i++){
    			$output .= "<li>设备编号".$json[$i]["deviceID"]."-".$json[$i]["description"].",使用人".$json[$i]["operator"]."</li>";
    		}
    		echo $output;
    	}
    	
    	//releaseBind();
    	output_deviceList();
    	
    	function releaseBind($deviceID = "h01") {
    		$file = "../deviceBind.json";
    		$json  = json_decode(file_get_contents($file),true);
    		$found = false;
    		for( $i = 0; $i<count($json); $i++){
    			if( $json[$i]["deviceID"] == $deviceID){
    				if( $json[$i]["operator"] != ""){
	    				$json[$i]["operator"] = "";
	    				$fh = fopen($file, 'w');
	    				fwrite($fh, json_encode($json));
	    				fclose($fh);
	    				//var_dump($json[$i]);
	    				$found = true;
	    				break;
	    			}
	    			else {
	    				echo $deviceID."处于无人使用状态";
	    				break;
	    			}
    			}
    			else {
    				$found = false;
    			}
    		}
    		if($found) echo "已将".$deviceID."解除联系";
    	}
    	function addNewBind($deviceID = "h01",$operator = "李雪") {
    		$file = "../deviceBind.json";
    		$json  = json_decode(file_get_contents($file),true);
    		$newArray = array("operator"=>$operator,"deviceID"=>$deviceID);
    		//var_dump(count($json));
    		$foundDev = false;
    		//轮询json数据，检查是否有deviceID = $newArray[deviceID]对应的Operator
    		for( $i = 0; $i<count($json); $i++){
    			$device = $json[$i]['deviceID'];
    			//如果查询设备找到对应数组
    			if( $device == $newArray['deviceID'] ){
    				$foundDev = true;
    				
    				if( $json[$i]['operator'] == ""){
    					//如果此设备无主，可以使用
    					//更改json数据，再次存储
    					$json[$i]["operator"] = $newArray['operator'];
    					//array_push($json, $newArray);
    					$fh = fopen($file, 'w');
    					fwrite($fh, json_encode($json));
    					fclose($fh);
    					//var_dump($json[$i]);
    					break;
    				}
    				else {
    					//找到的操作员和存储时相同
    					if($newArray['operator'] == $json[$i]['operator']){
    						 echo "已经绑定成功，不要重复绑定";
    						 break;
    						 //do nothing and return;
    					}
    					else {
    						echo "该设备已经有人使用，状态为不可用";
    						break;
    					}
    					
    				}
    			}
    			else {
    				$foundDev = false;
    			}
    		
    		}
    		if(!$foundDev){
    			echo "没有找到对应设备";
    		}
    	}
		
		
		
		//写入设备列表文件
		//初始化时使用
//		
//		$rfidReaderList = array(
//			array("deviceID" => 'h00', "operator"=>"", "description"=>"手持0"),
//			array("deviceID" => 'h01', "operator"=>"", "description"=>"手持1"),
//			array("deviceID" => 'h02', "operator"=>"", "description"=>"手持2"),
//			array("deviceID" => 'h03', "operator"=>"", "description"=>"手持3"),
//			array("deviceID" => 'h04', "operator"=>"", "description"=>"手持4"),
//			array("deviceID" => 'h05', "operator"=>"", "description"=>"手持5"),
//			array("deviceID" => 'h06', "operator"=>"", "description"=>"手持6"),
//			array("deviceID" => 'h07', "operator"=>"", "description"=>"手持7"),
//			
//			array("deviceID" => 'd00', "operator"=>"door00", "description"=>"门禁0"),
//			array("deviceID" => 'd01', "operator"=>"door01", "description"=>"门禁1"),
//			array("deviceID" => 'd02', "operator"=>"door02", "description"=>"门禁2"),
//			array("deviceID" => 'd03', "operator"=>"door03", "description"=>"门禁3"),
//			array("deviceID" => 'd04', "operator"=>"door04", "description"=>"门禁4"),
//			array("deviceID" => 'd05', "operator"=>"door05", "description"=>"门禁5")
//		);
//		$fh = fopen($file, 'w');
//		fwrite($fh, json_encode($rfidReaderList));

		
	
	
	 ?>
	 
  </body>
</html>	 