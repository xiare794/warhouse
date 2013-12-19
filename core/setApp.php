<!DOCTYPE html>
<html lang="cn">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>WareHouse 货物包</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/bootstrap-select.css">
	<link rel="stylesheet" type="text/css" href="css/jPaginateStyle.css">
	
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
	<?php 
		//如果已知货物包ID
		if(isset($_GET['pID']) )
		//	insertWarePackages($_POST);
		;
		
		$status = array("packageIdentity"=>"false",
						"trays"=>"false",
						"moving"=>"false",
						"complete"=>"false");
	?>
	<div class="container" style="margin-top:60px">
		<?php var_dump($_POST);
			var_dump($_GET);
			
			$currPID = 0;
			if(isset($_GET['pid']))
				$currPID = $_GET['pid'];
		?>
		
		<div class="panel <?php if(isset($_GET['pid'])) echo "panel-success"; else echo "panel-warning";?>">
		  <div class="panel-heading"><h2>分配托盘</h2>
		  </div>
		  <div class="panel-body">
			<div class="form-group">
				<label id="wpID" for="wPackages" class="col-lg-2 control-label">1.选择货物包</label>
				<div class="col-lg-10">
				 <select id="wPackagesSelect" class="selectpicker show-tick show-menu-arrow" name="wPackages"  data-size="5" title="选择一个货代" data-width="100%">
					<?php $items =  getTableIDNameArray("warePackages","wpID","wpFullName");
						for($i = 0; $i<count($items); $i++){
							echo "<option";
							if((int)$items[$i] == $currPID)
								echo " selected ";
							echo " value ='".(int)$items[$i]."'>".$items[$i]."</option>";
						}
					?>
					
				</select>
				<?php var_dump( getCurrTraysByPID($currPID) );?>
			    </div>
			</div>
			
			<div class="form-group">
				<label id="wpAppID" class="col-lg-2 control-label">2.表单</label>
				<div class="col-lg-10">				
				 	<?php 
						if(isset($_GET['pid'])){
				
							$items =  getTableByA_equel_B("wactions","wpID",$currPID);
							for($i = 0 ; $i<count($items); $i++){
								echo "<p>".$items[$i]['actType'].":  ".$items[$i]['actDate'].":  ".$items[$i]['actComplete'];
							}
						}
						//var_dump($items);
						/*
						for($i = 0; $i<count($items); $i++){
							echo "<option";
							if((int)$items[$i] == $currPID)
								echo " selected ";
							echo " value ='".(int)$items[$i]."'>".$items[$i]."</option>";
						}*/
					?>
					
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
	<script type="text/javascript" src="js/bootstrap-select.js"></script>
	<script type="text/javascript" src="js/jquery.paginate.js"></script> 
	<script type="text/javascript" >
		$('#wPackagesSelect').change(function(){
			
			console.log( $('#wPackagesSelect').val());
			window.location.href = "setApp.php?pid="+$('#wPackagesSelect').val();
		});
	
	</script> 
	
	<?php 
		mysqli_close($connection);
	?>
  </body>
</html>