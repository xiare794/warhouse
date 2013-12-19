<!DOCTYPE html>
<html lang="cn">
  <head>
    <meta charset="utf-8">
	<link href="../css/bootstrap.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="../css/bootstrap-select.css">
	<link rel="stylesheet" type="text/css" href="../css/jPaginateStyle.css">
	<style>
			
	</style>
  </head>
<body>
	
	
	<p>appID.wpID.appName.appCount.appIncludeItems.appType.appBookingDate.appData.appOperater.appFromTrayID.appToTrayID.appDeviceID.appCameraFile.appComplete.appStatus.appSigned</p>
	<?php
		include_once("functions_manage.php");
		include_once("_db.php");
		include_once("generateData.php");
		global $connection;
		
		if(isset($_GET['new'])){
			$theName = $thepack['wpMAITOU'].$theType;
			$query = "INSERT INTO `wApplications` VALUES(null,\"".$thepack['wpID']."\",\"".$theName."\",1,null,\"".$theType."\",null,null,\"".$theOp."\",\"".$thepack['wpCurrTrayID']."\",null,null,null,0,0,0)";
			$result = mysqli_query($connection, $query);
			if(!$result){
			 echo '[we have a problem]: '.mysqli_error($connection);
			}
			$newAppId = mysqli_insert_id($connection);
			echo $newAppId;
			
			//更新package内的in app id
			$query = "UPDATE `warePackages` SET wpInAppID=\"".$newAppId."\" WHERE wpID=\"".$thepack['wpID']."\"";
			echo $query;
			$result = mysqli_query($connection, $query);
			if(!$result){
			 echo '[we have a problem]: '.mysqli_error($connection);
			}
		}
		
		// $query = "SELECT * FROM `wApplications` WHERE 1 ";
		// $appPool = array();
		// $result = mysqli_query($connection, $query);
		// if(!$result){
		 // echo '[we have a problem]: '.mysqli_error($connection);
		// }
		// while($row = mysqli_fetch_array($result)){
			// var_dump($row);
			// array_push ($appPool,$row);
		// }
		// var_dump($appPool);
		
	?>
	<div class="panel panel-default" id="appListInPanel">
		<div class="modal fade" id="appSignedModal">
		  <div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">出入库单签署页</h4>
			  </div>
			  <div class="modal-body" id="modalSign">
				<ul class="list-group" id="appSignedUl">
					<li class="list-group-item">Cras justo odio</li>
					<li class="list-group-item">Dapibus ac facilisis in</li>
					<li class="list-group-item">Morbi leo risus</li>
					<li class="list-group-item">Porta ac consectetur ac</li>
					<li class="list-group-item">Vestibulum at eros</li>
				</ul>
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				<button type="button" class="btn btn-primary" id="signedBtn">签署</button>
			  </div>
			</div><!-- /.modal-content -->
		  </div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
		<div class="panel-heading" id="appListHeader">库单列表
			<span class="badge pull-right">总数:<?php echo getTableLength('wApplications'); $tatalPage = ceil(getTableLength('wApplications')/10);?></span>
			<button id="AppListToggle" class="btn btn-info pull-right btn-xs">显示/隐藏</button>
		</div>
		<div class="panel-body">
			<div class="btn-group" id="filter" style="padding: 5px;">
				<button id="allAppsbtn" class="btn btn-default">全部</button>
				<button id="AppUnComp" class="btn btn-default active">未完成</button>
				<button id="AppUnSign" class="btn btn-default">等签字</button>
				<button id="AppIn" class="btn btn-default">入库单</button>
				<button id="AppOut" class="btn btn-default">出库单</button>
				<button id="AppMove" class="btn btn-default">移库单</button>
				<button id="CreateRandomApp" class="btn btn-default">随机生成</button>
			</div>
			<div id="Apptxt">
			</div> 
			<div id="appPagi"></div> 	
		</div>
	</div>
	
	<script src="../js/jquery.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery.alert.js"></script>
	<script type="text/javascript" src="../js/jquery.paginate.js"></script> 
	<script>
		$('#CreateRandomApp').click(function(){
			window.location.href = "appList.php?new=in" ;
		});
		
		function afterGeTNewAppList(){
			$('.btn-success').click(function(){
				//加载需要从getPage内响应的元素
				$('#modalSign').load("component.php?appID="+$(this).attr('id'));
				//签发按钮
				$('#signedBtn').click(function(){
					var hint = "<div class=\"alert alert-success\" id=\"hintAlert\"><a href=\"#\" class=\"alert-link\">成功签发</a></div>";
					$('#appListHeader').append(hint);
					$('#hintAlert').load("component.php?sign="+$('#appKey').attr('key'));
					$('#appSignedModal').modal('toggle');
				});
			});
		}
		$("#appPagi").paginate({ 
				count         : <?php echo ceil(getTableLength('wApplications')/10);?>, 
				start         : 1, 
				display     : 10, 
				border					: false,
				text_color  			: '#888',
				background_color    	: '#EEE',	
				text_hover_color  		: 'black',
				background_hover_color	: '#CFCFCF',
				mouse                   : 'press', 
				onChange                : function(page){
											var para = $('#appListInPanel .active').attr('id');
											$("#Apptxt").load("getPage.php?page="+page+"&&db=wApplications&&para="+para,function () {
												//加载需要从getPage内响应的元素
												afterGeTNewAppList();
												
											});
											
										}
			});
		$( document ).ready( function(){
			$('#Apptxt').load("getPage.php?page=1&&db=wApplications&&para=0",function(){
				afterGeTNewAppList();
				
			});
			
			$('#AppUnSign').click(function(){
				$('.active').removeClass('active');
				$('#AppUnSign').addClass('active');
				$('#Apptxt').load("getPage.php?page=1&&db=wApplications&&para=AppUnSign",function(){afterGeTNewAppList();});
			});
			$('#allAppsbtn').click(function(){
				$('.active').removeClass('active');
				$('#allAppsbtn').addClass('active');
				$('#Apptxt').load("getPage.php?page=1&&db=wApplications&&para=",function(){afterGeTNewAppList();});
			});
			$('#AppUnComp').click(function(){
				$('.active').removeClass('active');
				$('#AppUnComp').addClass('active');
				$('#Apptxt').load("getPage.php?page=1&&db=wApplications&&para=AppUnComp",function(){afterGeTNewAppList();});
			});
			$('#AppIn').click(function(){
				$('.active').removeClass('active');
				$('#AppIn').addClass('active');
				$('#Apptxt').load("getPage.php?page=1&&db=wApplications&&para=AppIn",function(){afterGeTNewAppList();});
			});
			$('#AppOut').click(function(){
				$('.active').removeClass('active');
				$('#AppOut').addClass('active');
				$('#Apptxt').load("getPage.php?page=1&&db=wApplications&&para=AppOut",function(){afterGeTNewAppList();});
			});
			$('#AppMove').click(function(){
				$('.active').removeClass('active');
				$('#AppMove').addClass('active');
				$('#Apptxt').load("getPage.php?page=1&&db=wApplications&&para=AppMove",function(){afterGeTNewAppList();});
			});
			
			$('#AppListToggle').click(function(){
				$('#Apptxt').toggle();
			});
			
			
		});
		
	</script>
	


	
	</body>
</html>