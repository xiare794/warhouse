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
	
	
	<!--<p>appID.wpID.appName.appCount.appIncludeItems.appType.appBookingDate.appData.appOperater.appFromTrayID.appToTrayID.appDeviceID.appCameraFile.appComplete.appStatus.appSigned</p>-->
	<?php
		include_once("functions_manage.php");
		include_once("_db.php");
		//include_once("generateData.php");
		
		
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
					<li class="list-group-item">废代码</li>
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
			
			<span class="  pull-right" style="font:5px">
              总数:
              <span class="btn-tip"><?php echo getTableLength('wApplications'); $tatalPage = ceil(getTableLength('wApplications')/10);?></span>
            </span>
            
			
            <div class="dropdown pull-right" id="applistFilterDiv">
              <button class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown">筛选<span class="caret"></span></button>
              <span class="dropdown-arrow"></span>&nbsp;
              <ul class="dropdown-menu">
              	
                <li class="selected"><a id="allAppsbtn">全部</a></li>
                <li><a id="AppUnComp">未完成</a></li>
                <li><a id="AppUnSign">等签字</a></li>
                <li><a id="AppIn">入库单</a></li>
                <li><a id="AppOut">出库单</a></li>
                <li><a id="AppMove">移库单</a></li>
                <li><a id="">随机生成</a></li>
              </ul>
            </div>
			
			<div class="pull-right col-sm-5 form-group input-sm">
				<label>时间</label>
				<input id="start-date" type="date"></input>
				<input id="end-date" type="date"></input>
			</div>
			<div class="pull-right col-sm-3 form-group input-sm">
				<label>关键字</label>
				<input id="app-keyword" type="text"></input>
			</div>
            <!--&nbsp;<a class="pull-right"><span class="fui-plus">新增</span></a>&nbsp;-->
            
		</div>
		<div class="panel-body">
        
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
		
		/*
		function afterGeTNewAppList(){
			$('.btn-success').click(function(){
				//加载需要从getPage内响应的元素
				
				$('#modalSign').load("component.php?appID="+$(this).attr('id'));
				
				//签发按钮
				$('#signedBtn').click(function(){
					
					var hint = "<div class=\"alert alert-success\" id=\"hintAlert\"><a href=\"#\" class=\"alert-link\">成功签发</a></div>";
					$('#appListHeader').append(hint);
					$('#hintAlert').load("component.php?sign="+$('#appSignedUl').attr('key'));
					console.log("hintAlert loaded");
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
			var keyFilter = "";
			var dateFilter = array("",""); 
			$('#Apptxt').load("getPage.php?page=1&&db=wApplications&&para=0",function(){
				afterGeTNewAppList();
			});
			console.log("app-keyword");
			&('#app-keyword').onFocus(function(){
				$('#app-keyword').val("fdafdsafds");
				console.log(keyFilter);
			})
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
		*/
		
		
		
	</script>
	


	
	</body>
</html>