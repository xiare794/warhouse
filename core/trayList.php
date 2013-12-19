<!DOCTYPE html>
<html lang="cn">
  <head>
    <meta charset="utf-8">
	<style>
			bs-glyphicons {
			  padding: 0px;
			  list-style: none;
			  overflow: hidden;
			  margin: 0px;
			}
			.bs-glyphicons li {
			  float: left;
			  width: 50px;
			  height: 30px;
			  padding: 0px;
			  margin: 2px;
			  font-size: 10px;
			  text-align: center;
			  border: 1px solid #ddd;
			  list-style: none;
			  
			}
			.bs-glyphicons .glyphicon {
			  margin-top: 0px;
			  margin-bottom: 0px;
			  font-size: 20px;
			}
			.bs-glyphicons .glyphicon-class {
			  display: block;
			  text-align: center;
			}
			.bs-glyphicons li:hover {
			  background-color: rgba(86,61,124,.1);
			}
	</style>
  </head>
<body>
	<div class="btn-group" style="padding: 5px;">
	  <button id="allTraysbtn" class="btn btn-default">全部</button>
	  <button id="idleTraysbtn" class="btn btn-default">空闲</button>
	  <button id="shelfTraysbtn" class="btn btn-default">在货架</button>
	  <button id="inUseTraysbtn" class="btn btn-default">使用中</button>
	</div>
	<?php
		include_once("_db.php");
		global $connection;
		$query = "SELECT * FROM `wTrays` WHERE 1 ";
		$trayPool = array();
		$result = mysqli_query($connection, $query);
		if(!$result){
		 echo '[we have a problem]: '.mysqli_error($connection);
		}
		while($row = mysqli_fetch_array($result)){
			//var_dump($row);
			array_push ($trayPool,$row);
		}
		
	?>
	<ul class="bs-glyphicons" style="padding: 0px; margin: 0px;">
		<?php
			for($i= 0; $i< count($trayPool) ; $i++){
				if( $trayPool[$i]['twStatus'] == "空闲"){
					echo "<li class=\"idle\"><span class=\"glyphicon glyphicon-inbox \" style=\"color: #336633;\">".$trayPool[$i]['wtID']."</span></li>";
				}
				else if ($trayPool[$i]['twStatus'] == "使用中") {
					echo "<li class=\"inUse\"><span class=\"glyphicon glyphicon-inbox \" style=\"color: #FF3333;\">".$trayPool[$i]['wtID']."</span></li>";
				}
				else {
					echo "<li class=\"onShelf\" ><span class=\"glyphicon glyphicon-inbox \" style=\"color: #666699;\">".$trayPool[$i]['wtID']."</span></li>";
				}
			}
		?>
	</ul>


	<script>
		$('#allTraysbtn').click(function () {
			$('.idle').show();
			$('.inUse').show();
			$('.onShelf').show();
		})
		$('#idleTraysbtn').click(function () {
			$('.idle').show();
			$('.inUse').hide();
			$('.onShelf').hide();
		})
		$('#shelfTraysbtn').click(function () {
			$('.idle').hide();
			$('.inUse').hide();
			$('.onShelf').show();
		})
		$('#inUseTraysbtn').click(function () {
			$('.idle').hide();
			$('.inUse').show();
			$('.onShelf').hide();
		})
	</script>
	</body>
</html>