<!DOCTYPE html>
<html lang="cn">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../assets/ico/favicon.png">

    <title>WareHouse 托盘地图</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <!--<link href="navbar.css" rel="stylesheet"> -->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../../assets/js/html5shiv.js"></script>
      <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">
	
	<?php
		$host = "localhost";
		$databaseName = "stockDB";
		$user = "xiare794";
		$pass = "123";
		$connection=mysqli_connect($host,$user,$pass,$databaseName);
		if(mysqli_set_charset($connection,'utf8'))
			echo "设置uft成功<br>";
		//查询条件
		$query = "SELECT * FROM wTrays ";
		$query .= "WHERE twPosFloor=1";
		$result = mysqli_query($connection,$query);
		if (mysqli_query($connection,$query))
		  {
		  echo "连接数据库成功";
		  }
		else
		  {
		  echo "Error 连接数据库 托盘数据表: " . mysqli_error($connection);
		  }
		//var_dump( $result);
		$cells = array();
		$jsonData = array();
		$maxRow =1;
		$maxCol =1;
		while($row = mysqli_fetch_array($result))
		{
			
			
		  //echo $row['twPosRow'] . " " . $row['twPosCol'] . " " .$row['twStatus'];
		  //echo "<br>";
		  $maxRow = $row['twPosRow']>$maxRow?$row['twPosRow']:$maxRow;
		  $maxCol = $row['twPosCol']>$maxCol?$row['twPosCol']:$maxCol;
		  $cell[($row['twPosRow'])*4 + $row['twPosCol']]  = $row['twStatus'];
		  $output[]=$row;
		  array_push($jsonData, $row);
		}
		
		 $fh = fopen("trayMap.json", 'w');
          //or die("Error opening output file");
		fwrite($fh, json_encode($jsonData));
		fclose($fh);
		
		//var_dump($cell);
		echo "<table class=\"table table-bordered\"><tbody>";
		for($i=1; $i<$maxRow+1; $i++)
		{
			echo "<tr>";
			for($j=1; $j<$maxCol+1; $j++)
			{
				if($cell[($i)*4 + $j] == '忙碌')
				{
					echo "<td class=\"danger\">".$cell[($i)*4 + $j]."</td>";
				}
				if($cell[($i)*4 + $j] == '空闲')
				{
					echo "<td class=\"success\">".$cell[($i)*4 + $j]."</td>";
				}
			}
			echo "</tr>";
		}
		echo "</tbody></table >";
		//批量生成测试托盘
		/*for($i=1; $i<$tRow; $i++){
			for($j=1; $j<$tCol; $j++){
				for($k=1; $k<$tFloor; $k++){
					"SELECT * FROM Persons"
					$query = "INSERT INTO wTrays ";
					$query .="(twStatus,twPosRow,twPosCol,twPosFloor) ";
					if( ($i+$j+$k)%5 == 0)
						$query .="VALUES('忙碌','".$i."','".$j."','".$k."')";
					else
						$query .="VALUES('空闲','".$i."','".$j."','".$k."')";
						
					mysqli_query($connection,$query);
				}
			}
		}
*/
		/*$query = "INSERT INTO wTrays ";
		$query .="(twStatus,twPosRow,twPosCol,twPosFloor) ";
		$query .="VALUES('忙碌','1','1','1')";
		//echo $query;
		
		mysqli_query($connection,$query);
		*/
		
	?>
	<table class="table table-bordered">
        <!--<thead>
          <tr>
            <th>#</th>
            <th>Column heading</th>
            <th>Column heading</th>
            <th>Column heading</th>
          </tr>
        </thead>-->
        <tbody id ="cells">
		  
        </tbody>
      </table>
	  <div id="drawBox">
	  <h1>一号仓库</h1>
		<div class="progress">
			
			<div class="progress-bar progress-bar-danger">
			    <span class="sr-only">20% Complete (warning)</span>
			</div>
			<div class="progress-bar progress-bar-success" >
				<span class="sr-only">35% Complete (success)</span>
		    </div>
		    
		</div>
	  </div>
	</div> <!-- /container -->
	<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
	<script>
		$(function(){
			$('#testForm').collapse({
			  toggle: true
			})
			$(".input-group-addon").css('width',"90px");
			
			var rows = 9;
			var cols = 4;
			var c = "";
			for( var i =0; i<rows; i++){
				c +="<tr>";
				for(var j=0; j<cols; j++){
					c+="<td class=\"warning\">"+(i*cols+j)+"</td>";
				}
				c += "</tr>";
			}
			//$('#cells').html(c);
			$.getJSON('trayMap.json', drawWHOverview);
		});
		
		function loadJson(data){
			var items = [];
			var row;
			$.each(data, function(key, val) {
				console.log(key+val['twStatus']);
			});
		}
		
		function drawWHOverview(data){
			var ov = [0,0,0,0];
			var ovOpcupied = [0,0,0,0];
			$.each(data, function(key, val) {
				if(val['twWareHouse'] == 1){
					ov[1] ++;
					if(val['twStatus'] == '忙碌')
					ovOpcupied[1] ++;
				}
				//console.log(key+val['twStatus']);
			});
			var op = Math.floor(100*ovOpcupied[1]/ov[1]);
			var opStr = (op).toString() + "%";
			var availible = (99-op)+"%";
			console.log(ovOpcupied);
			$('.progress-bar-success').css('width',availible);
			$('.progress-bar-danger').css('width',opStr);
			 
			
		}
	</script>
	
  </body>
</html>