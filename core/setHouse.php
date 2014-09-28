<!DOCTYPE html>
<html lang="cn">
  <head>
  	
  	<link href="../css/bootstrap.css" rel="stylesheet">
    <meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html"; charset="utf-8">
	<style>
		td{
			font-size: 7px;
		}
		.preview span{
			height: 20px;
			display: block;
			width:100%;
			border: 1px solid #EEE;
			margin: -1px 0px -1px 0px;
		}
		.housepreview{
			list-style: none;
		}
		.housepreview li{
			float: left;
			width: 50px;
			height: 100%;
			text-align: center;
			border: 1px solid #ddd;
			border-radius: 4px;
			padding:5px;
			margin:5px -1px 5px -1px;
		}
		.housepreview span.free:hover {
			background-color: rgba(86,61,124,.1);
		}
		.housepreview span.free{
			background-color:rgba(0,255,153,.5);
		}
		.housepreview span.fill:hover {
			background-color: rgba(86,61,124,.1);
		}
		.housepreview span.fill{
			background-color:rgba(153,51,51,.5);
		}
		
	</style>
  </head>
  	<?php
  		include("_db.php");
  		if(isset($_POST)){
  			//var_dump($_POST);
  			if(isset($_POST["setpara"])){
  				//echo "<br>come here<br>";
  				$paras = $_POST['setpara'];
  				$paraArr = explode(";", $paras);
  				unset($paraArr[0]);
  				//var_dump($paraArr);
  				//清理之前的所有Slot
  				$query = "DELETE FROM `wSlots` WHERE 1";
  				$result = mysqli_query($connection, $query);
  				if(!$result){
  				 echo '[we have a problem]: '.mysqli_error($connection);
  				}
  				//建设新Slot
  				for($i =1; $i<count($paraArr)+1; $i++){
  				 	$oneRowArr = explode("-", $paraArr[$i]);
  					//var_dump($oneRowArr);
  					$row = $oneRowArr[0];
  					$floor = $oneRowArr[1];
  					$col = $oneRowArr[2];
  					//为每一列col，每一层floor循环
  					for($c =1; $c<$col+1; $c++){
	  					for( $f= 1 ; $f<$floor+1; $f++){
		  					$query = "INSERT INTO wSlots VALUES (null, 0, ".$row.", ".$f.",".$c.",null)";
		  					$result = mysqli_query($connection, $query);
							if(!$result){
							 echo '[we have a problem]: '.mysqli_error($connection);
							}
						}
					}
  				}
  			}
  			
  		}
		$hideOpt=0;
		if(isset($_GET)){
			if(isset($_GET['preview'])){
				$hideOpt=1;
			}
		}
  	?>
	<body>
	<div class="container">
		<div class="row">
			<?php 
				if($hideOpt==1)
				echo "<!--";
			?>
			<div class="col-lg-5" style="border-right: 1px solid #333;">
				<h1>仓库设置界面</h1>
				<div class="input-group">
				  <span class="input-group-addon">货架列数</span>
				  <input type="text" class="form-control" placeholder="列数" value="5" id="colNum">
				</div>
				<div class="input-group">
				  <span class="input-group-addon">货架层数</span>
				  <input type="text" class="form-control" placeholder="列数" value="2" id="floorNum">
				</div>
				<span class="input-group-addon btn btn-default" id="addRow">点击增加一行货架</span>
				
				<form method="post" action="setHouse.php">
					<div class="input-group">
						<input type="text" class="form-control" name="setpara" value="" id="setpara"/>
						<input type="submit" name="" class="btn btn-success" value="保存仓库设置" />
					</div>
				</form>
			</div>
			<?php 
				if($hideOpt==1)
				echo "-->";
			?>
			<div class="col-lg-7">
				<h4>仓库缩略图</h4>
				
				<table class="house-preview table table-bordered"></table>
				<div class="preview">
					<?php
						include("_db.php");
						$query = "SELECT max(tsPosRow), max(tsPosCol), max(tsPosFloor) FROM `wSlots` WHERE 1 ";
						$result = mysqli_query($connection, $query);
						//var_dump($result);
						if(!$result){
						 echo '[we have a problem]: '.mysqli_error($connection);
						}
						$row = mysqli_fetch_array($result);
						$rn = $row['max(tsPosRow)'];
						$cn = $row['max(tsPosCol)'];
						$fn = $row['max(tsPosFloor)'];
						//echo $rn.$cn.$fn;
						
						
						$slots = array(); 
						for($r=1; $r<$rn+1; $r++){ 
							$query = "SELECT tsPosCol, tsPosFloor, wtID FROM `wSlots` WHERE tsPosRow = \"".$r."\"";
							//echo $query;
							$result = mysqli_query($connection, $query);
							if(!$result){
							 echo '[we have a problem]: '.mysqli_error($connection);
							}
							while($row = mysqli_fetch_array($result)){
								$slot = array(
									"r" => $r,
									"c" => (int)$row['tsPosCol'],
									"f" => (int)$row['tsPosFloor'],
									"wt"=> $row['wtID']
								);
								array_push($slots,$slot);
							}
						}
						// for($temp=0; $temp<count($slots); $temp++){
							// $strid = $slots[$temp]["r"].$slots[$temp]["c"].$slots[$temp]["f"];
						// }
						function searchPos($arr,$r,$c,$f,&$fill=null){
							if (is_array($arr))
							{
								for($i=0; $i<sizeof($arr); $i++)
								{
									$row = $arr[$i];
									if($row["r"] == $r && $row["c"] == $c && $row["f"] == $f ){
										if(!is_null($row['wt'])){
											$fill = $row['wt'];
										}
										return true;
									
									}     
								}
							}
							return false;
						}
						
						
						for($r=1; $r<$rn+1; $r++){
							echo "<div class=\"row\">";//<div class=\"col-xs-1\">";
							echo "<p style=\"padding:0px; margin:0px; border-bottom: 1px solid #ddd;\">第".$r."行</p>";
							echo "<ul class=\"housepreview\">";
							for($c=1; $c<$cn+1; $c++){
								if(searchPos($slots,$r,$c,1)){
									echo "<li>";
									for($f=1; $f<$fn+1; $f++){
										$fill=null;
										if( searchPos($slots,$r,$c,$f,$fill)){
											if($fill)
												echo "<span class=\"fill\" id=\"".($r).($c).($f)."\" >".$fill."</span>";
											else
												echo "<span class=\"free\" id=\"".($r).($c).($f)."\" >"."</span>";
										}
									}
									echo "</li>";
								}
							}
							echo "</ul>";
							echo "</div>";
							//echo "</div></div >";
						}
						
						// echo "<p>".$rn.$cn.$fn."</p>";
					?>
					<!--<span class=""></span>
					<span class=""></span>
					<span class=""></span>-->
				</div>
			</div>
		</div>
	</div>
	<?php
		
	?>
	
	
	
	<script src="../js/jquery.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script type="text/javascript">	
		var clickNum = 0;
		$('#addRow').click(function () {
			clickNum ++;
			var colNum = $('#colNum').val();
			var floorNum = $('#floorNum').val();
			
			var out = "";
			console.log(colNum+floorNum);
			for( var j=1; j<parseInt(floorNum)+1; j++)
			{
				out += "<tr>";
				if(j == 1){
					out += "<td rowspan=\""+floorNum+"\">第"+clickNum+"行</td>";
				}
				for( var i=1; i<parseInt(colNum)+1; i++){
					out += "<td><a href=\"#\">"+clickNum+"-"+j+"-"+i+"</a></td>";
				}
				out +="</tr>";
			}
			
			$('#setpara').val($('#setpara').val()+";"+clickNum+"-"+colNum+"-"+floorNum);
			//out+= "</tr>";
			$('.house-preview').append(out);
			console.log($('.house-preview'));
		})
	
	</script>
	</body>
</html>
	