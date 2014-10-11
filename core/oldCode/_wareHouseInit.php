<!DOCTYPE html>
<html lang="cn">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../assets/ico/favicon.png">

    <title>WareHouse 初始化页面</title>

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
	<?php include("header.php"); ?>
	<?php include("_db.php"); ?>
	<?php include("functions_manage.php"); ?>
    <div class="container" style="margin-top:60px">
    
	<div class="panel panel-default">
	<div class="panel-heading">初始化仓储系统</div>
	<div class="panel-body">
		<?php var_dump($_POST);
			var_dump($_GET);
			
			if(isset($_GET['ClearSlot']) ){
				
				$query ="DELETE FROM  `wslots` WHERE 1";
				if( mysqli_query($connection,$query) )
					echo "清除仓位信息";
				
			}
			//初始化仓位
			if(isset($_POST['Slotconfirm'])){
				$tHouse;
				$tRow;
				$tCol;
				$tFloor;
				
				if( isset($_POST['tsPosRow_1']) ){
					$tHouse = 1;
					$tRow = $_POST['tsPosRow_1'];
					$tCol = $_POST['tsPosCol_1'];
					$tFloor = $_POST['tsPosFloor_1'];
					for($i=1; $i<$tRow+1; $i++){
						for($j=1; $j<$tCol+1; $j++){
							for($k=1; $k<$tFloor+1; $k++){
								$query = "INSERT INTO wSlots ";
								$query .="(tsWareHouse,tsPosRow,tsPosCol,tsPosFloor) ";
								$query .="VALUES('".$tHouse."','".$i."','".$j."','".$k."')";
								
								mysqli_query($connection,$query);
							}
						}
					}
				}
				if( isset($_POST['tsPosRow_2']) ){
					$tHouse = 2;
					$tRow = $_POST['tsPosRow_2'];
					$tCol = $_POST['tsPosCol_2'];
					$tFloor = $_POST['tsPosFloor_2'];
					for($i=1; $i<$tRow+1; $i++){
						for($j=1; $j<$tCol+1; $j++){
							for($k=1; $k<$tFloor+1; $k++){
								$query = "INSERT INTO wSlots ";
								$query .="(tsWareHouse,tsPosRow,tsPosCol,tsPosFloor) ";
								$query .="VALUES('".$tHouse."','".$i."','".$j."','".$k."')";
								
								mysqli_query($connection,$query);
							}
						}
					}
				}
				if( isset($_POST['tsPosRow_3']) ){
					$tHouse = 3;
					$tRow = $_POST['tsPosRow_3'];
					$tCol = $_POST['tsPosCol_3'];
					$tFloor = $_POST['tsPosFloor_3'];
					for($i=1; $i<$tRow+1; $i++){
						for($j=1; $j<$tCol+1; $j++){
							for($k=1; $k<$tFloor+1; $k++){
								$query = "INSERT INTO wSlots ";
								$query .="(tsWareHouse,tsPosRow,tsPosCol,tsPosFloor) ";
								$query .="VALUES('".$tHouse."','".$i."','".$j."','".$k."')";
								
								mysqli_query($connection,$query);
							}
						}
					}
				}
				if( isset($_POST['tsPosRow_4']) ){
					$tHouse = 4;
					$tRow = $_POST['tsPosRow_4'];
					$tCol = $_POST['tsPosCol_4'];
					$tFloor = $_POST['tsPosFloor_4'];
					for($i=1; $i<$tRow+1; $i++){
						for($j=1; $j<$tCol+1; $j++){
							for($k=1; $k<$tFloor+1; $k++){
								$query = "INSERT INTO wSlots ";
								$query .="(tsWareHouse,tsPosRow,tsPosCol,tsPosFloor) ";
								$query .="VALUES('".$tHouse."','".$i."','".$j."','".$k."')";
								
								mysqli_query($connection,$query);
							}
						}
					}
				}
			
			}
			
			//初始化托盘
			if(isset($_POST['TraysInit'])){
				
				$count = $_POST['TraysCount'];
				$query = "INSERT INTO wTrays ";
				$query .="(twStatus) ";
				$query .="VALUES('空闲')";
				for($i = 0; $i<$count; $i++){
					mysqli_query($connection,$query);
				}
			}
			//清除托盘
			if(isset($_POST['TraysClear'])){
				$query ="DELETE FROM  `wTrays`";
				mysqli_query($connection,$query);
			}
		?>
			<div class="panel-group" id="accordion">
			  <div class="panel panel-default">
				<div class="panel-heading">
				  <h4 class="panel-title">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
					  初始化仓储货位 [SlotTable]
					</a>
					<a class="btn btn-xs navbar-right btn-danger" href="wareHouseInit.php?ClearSlot=1" id="clearSlots" >清除货位设定</a>
				  </h4>
				</div>
				<div id="collapseOne" class="panel-collapse collapse">
				  <div class="panel-body">
					<?php 
						$query = "SELECT  `tsWareHouse` FROM  `wslots`";
						$result = mysqli_query($connection,$query);
						$resultArray = array(0,0,0,0,0);
						$isSlotSet = false; //货位是否设定
						while($row = mysqli_fetch_array($result))
						{
						  $resultArray[$row['tsWareHouse']]++;
						  $isSlotSet = true;
						}
						
						$x = 1;
						do
						  {
							echo "<button class=\"btn btn-default btn-lg\">";
							echo $x."号仓库<br>";
							echo "<span class=\"glyphicon glyphicon-home\"></span> <br>";
							echo $resultArray[$x];
							echo "</button>";
							$x++;
						  }
						while ($resultArray[$x] != 0 && $x<5)
						
						
					?>
					<?php 
						if($isSlotSet){
							echo "<div class=\"alert alert-success\">";
							echo "货位已经设定，如需修改请管理员完全清除，并重新链接";
							echo "</div>";
						}
					?>
					
					
					
				  <?php if($isSlotSet) echo "<!--"; //设置Slot的控制 ?>
				  <ul class="nav nav-pills navbar-right">
					  <li><button type="button" id="addWH_btn" class="btn btn-primary btn-xs" href="#" >增加仓库</button></li>
					  <li><button type="button" class="btn btn-xs btn-primary" href="#" >去掉最后一个仓库</button></li>
					</ul>
				  <form class="form-inline" role="form" action="wareHouseInit.php" method="POST">
					<div id="slotsOptions">
						<hr>
						<h4>1号仓库</h4>
						<div class="form-group">
							<label for="tsPosRow" class=" control-label">仓库行数</label>
							<input type="text" class="form-control" placeholder="tsPosRow_1" name="tsPosRow_1">
						</div>
						<div class="form-group">
							<label for="tsPosCol" class=" control-label">仓库列数</label>
							<input type="text" class="form-control" placeholder="tsPosCol_1" name="tsPosCol_1">
						</div>
						<div class="form-group">
							<label for="tsPosFloor" class=" control-label">仓库层数</label>
							<input type="text" class="form-control" placeholder="tsPosFloor_1" name="tsPosFloor_1">
						</div>
					</div>
					<hr>
					<div class="form-group">
						<button type="submit" name="Slotconfirm" class="btn btn-default">确认</button>
					</div>
				  </form>
				  <?php if($isSlotSet) echo "-->"; //设置Slot的控制?>
				  </div><!-- end of panel body -->
				</div>
			  </div>
			  <div class="panel panel-default">
				<div class="panel-heading">
				  <h4 class="panel-title">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
					  初始化托盘库
					</a>
				  </h4>
				</div>
				<div id="collapseTwo" class="panel-collapse collapse">
				  <div class="panel-body">
					<form class="form-inline" role="form" action="wareHouseInit.php" method="POST">
						<div class="form-group">
							<label for="tsPosCol" class=" control-label">托盘个数</label>
							<input type="text" class="form-control" placeholder="托盘数量" name="TraysCount">
							
							<label class=" control-label">托盘状态：空闲</label>
						</div>
						<div class="form-group">
							<button type="submit" name="TraysInit" class="btn btn-default">初始化托盘</button>
							<button type="submit" name="TraysClear" class="btn btn-default">清除托盘数据</button>
						</div>
					</form>
					目前有托盘
					<?php echo getTableLength();?>
					目前有仓位
					<?php echo getTableLength("wSlots");?>
					
				  </div>
				</div>
			  </div>
			  <div class="panel panel-default">
				<div class="panel-heading">
				  <h4 class="panel-title">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
					  Collapsible Group Item #3
					</a>
				  </h4>
				</div>
				<div id="collapseThree" class="panel-collapse collapse">
				  <div class="panel-body">
					Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
				  </div>
				</div>
			  </div>
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
		});
		$('#addWH_btn').click(function(){
			var num = $('#slotsOptions h4').length+1;
			if(num<=4){
				var htmlCode = "<hr>";
				htmlCode += "<h4>"+num+"号仓库</h4>"
				htmlCode += "<div class=\"form-group\">";
				htmlCode += "<label for=\"tsPosRow\" class=\"control-label\">仓库行数</label>";
				htmlCode += "<input type=\"text\" class=\"form-control\" placeholder=\"tsPosRow_"+num+"\" name=\"tsPosRow_"+num+"\">";
				htmlCode += "</div>";
				
				htmlCode += "<div class=\"form-group\">";
				htmlCode += "<label for=\"tsPosCol\" class=\"control-label\">仓库列数</label>";
				htmlCode += "<input type=\"text\" class=\"form-control\" placeholder=\"tsPosCol_"+num+"\" name=\"tsPosCol_"+num+"\">";
				htmlCode += "</div>";
				
				htmlCode += "<div class=\"form-group\">";
				htmlCode += "<label for=\"tsPosFloor\" class=\"control-label\">仓库层数</label>";
				htmlCode += "<input type=\"text\" class=\"form-control\" placeholder=\"tsPosFloor_"+num+"\" name=\"tsPosFloor_"+num+"\">";
				htmlCode += "</div>";
				$('#slotsOptions').append(htmlCode);
			}
		})
		
	</script>
	<?php 
		mysqli_close($connection);
	?>
  </body>
</html>