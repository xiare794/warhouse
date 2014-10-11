<?php require_once("config.php"); //session
?>
<!DOCTYPE html>
<html lang="cn">
  <head>
  	<?php
  		include("_db.php"); 
  	 	include("functions_manage.php"); 	
  		if(!isset ($_SESSION['user']) ){
  			$_SESSION['previewPage'] = curPageURL();
  			//echo curPageURL();
  			echo "<meta HTTP-EQUIV=\"REFRESH\" content=\"0; url=login.php\">";
  		}
  		else {
  			;
  			//nothing
  		}
  	?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>WareHouse 货物包</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.css" rel="stylesheet">
    <link href="../css/prettify.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="../css/bootstrap-select.min.css">
	<link rel="stylesheet" type="text/css" href="../css/jPaginateStyle.css">
    
        <!-- Loading Flat UI -->
    <link href="../css/flat-ui.css" rel="stylesheet">
  </head>

  <body>
  	
	<?php include("header.php"); ?>
	
	<?php 
		//如果是插入，使用_POST作为

		$pagesize = 10;
		$recordsCount = getTableLength('warePackages');
		$tatalPage = ceil($recordsCount/$pagesize);
	?>
	<div class="container" style="margin-top:100px">
		<?php 
			//var_dump($_POST);
			//var_dump($_GET);
			//var_dump($_SESSION);
		?>
		<div class="row">
			<div class="col-lg-12">
				
				<div class="panel panel-default" id="packageBox">
					<div class="panel-heading">货物包列表
					
						<p class="pull-right">总数:<?php echo getTableLength('warePackages');?></p>
					</div>
					<div class="panel-body">
						<div id="pagetxt"> 
							<?php
								include("getPage.php");
							?>
						</div> 
						<div id="demo"></div> 	
					</div>
				</div>
				<?php
                  if(isset( $_GET['id'] ) ){
                    $pData = getWarePackagebyPage($_GET['id']);
					$agent = getTableByA_equel_B("wAgents","waID",$pData['wpAgentID']);
					$Apps = getTableByA_equel_B("wApplications","wpID",$_GET['id']);
					$trays = array();
					foreach($Apps as $app){
						$tray = getTableByA_equel_B("wTrays",'appID',$app['appID']);
						array_push($trays,$tray);
					}
					
				  }
                ?>
			</div> 
		</div>
		<div class="panel panel-default " id="packageEdit" <?php if(!isset( $_GET['id'] ) ) echo "style=\"display:none\""; ?> >
		  <div class="panel-heading">货物包管理<span class="badge pull-right"><?php 
			if(isset( $_GET['id'] ) ){
				echo "正在修改第".$_GET['id'];
			}
			else
				echo "已有货物包".getTableLength('warePackages');
		  
		  ?>包</span></div>
		    
		  <div class="panel-body">
			<div class="row">
				<div class="col-md-4">
					<?php
						echo "<h5><small>货代：</small>".$agent[0]["waName"]."</h5>";
						echo "<p><i>id：</i>".$agent[0]['waID']."<br>";
						echo "<p><i>商品类型：</i>".$agent[0]['waType']."<br>";
						echo "<p><i>地址：</i>".$agent[0]['waAddr']."<br>";
						echo "<p><i>联系人：</i>".$agent[0]['waContact'].$agent[0]['waTel']."<br>";
						echo "</p>";
					?>
				</div>
				<div class="col-md-8">
					<?php
						echo "<h6>库单</h6>";
						foreach ($Apps as $app)
						{
							if($app['appType']=="in"){
								echo "<p><small>".$app["InStockID"]."-".$app['appID']."</small>";
								echo $app['appName']."<code>入库</code>".$app['appCount']."箱";
								echo "<br>".$app['appBookingDate']."</p>";
							}
							if($app['appType']=="out"){
								echo "<small>".$app["InStockID"]."-".$app['appID']."</small>";
								echo $app['appName']."<code>出库</code>".$app['appCount']."箱";
								echo "<br>".$app['appBookingDate']."</p>";
							}
							echo "<hr>";
						}
						
					?>
				</div>
			</div>
			 <?php
				if($pData == null)
					echo "<p class=\"mbl\">选择一个货物包，查看详情</p>";
				else{
					//var_dump($pData);
					//var_dump($agent);
					//var_dump($Apps);
					var_dump($trays);
				}
			?>
			 
		  </div>
		  
		</div>

		
	</div> <!-- /container -->
	<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    
    <script src="../js/jquery.js"></script>
    <script src="../js/bootstrap.min.js"></script>
	<script type="text/javascript" src="../js/bootstrap-select.min.js"></script>
	<script type="text/javascript" src="../js/jquery.paginate.js"></script> 
	<script type="text/javascript"> 
		$(function(){ 
			//缩略td中的过长元素
			function shortTD() {
				var tds = $('td');
				for( var i=0; i<tds.length; i++){
					if(tds[i].innerHTML.length >20 && tds[i].innerHTML[0] != "<"){
						tds[i].innerHTML = tds[i].innerHTML.substring(0, 10)+"..."+tds[i].innerHTML.substring(tds[i].innerHTML.length-5,tds[i].innerHTML.length);
					}
				}
			}
			
			
			
			
			$("#demo").paginate({ 
				count         : <?php echo $tatalPage;?>, 
				start         : 1, 
				display     : 10, 
				border					: false,
				text_color  			: '#888',
				background_color    	: '#EEE',	
				text_hover_color  		: 'black',
				background_hover_color	: '#CFCFCF',
				mouse                   : 'press', 
				onChange                : function(page){ 
											$("#pagetxt").load("getPage.php?page="+page+"&&db=warePackages",function () {
												shortTD(); 
												$('.showPackBtn').click(function(){
													window.location.href = "_package.php?id="+$(this).attr('id') ;
												});
											});
										}
			});
			
			
			$('.selectpicker').selectpicker();
			//缩略比较长的td
			shortTD();
			//响应修改货物包按键
			$('.showPackBtn').click(function(){
				window.location.href = "_package.php?id="+$(this).attr('id') ;
			});
			
		 });//end of complete html
			
	        
			
			
			
			
			
			
    </script>
    
	<?php 
		mysqli_close($connection);
	?>
  </body>
</html>