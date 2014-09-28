<?php require_once("config.php"); //session
?>
<!DOCTYPE html>
<html lang="cn">
  <head>
  	<?php
  		include("_db.php"); 
  	 	include("functions_manage.php");
  	 	//如果没有用户信息，就跳转回登陆页面
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
    
    <title>WareHouse 表单</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.css" rel="stylesheet">
    <link href="../css/prettify.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="../css/bootstrap-select.min.css">
		<link rel="stylesheet" type="text/css" href="../css/jPaginateStyle.css">
    
        <!-- Loading Flat UI -->
    <link href="../css/flat-ui.css" rel="stylesheet">

    <style >
    .scrolls-horizontal{
			overflow-x: scroll;
	    overflow-y: hidden;
	    white-space:nowrap;
	    width: 100%;
		}
		.scrolls-verital{
			overflow-y: scroll;
	    overflow-x: hidden;
	    white-space:nowrap;
	    height:400px;
		}
		.scrolls-both{
			overflow-y: scroll;
	    overflow-x: scroll;
	    white-space:nowrap;
	    height:400px;
	    width: 100%;
		}
		#sideNav{
			font-size: 15px;
			line-height: 1;
			background: #F7F5FA;
		}
		#sideNav>li>ul{
			font-size: 9px;
			line-height: 0.5;
			margin: 2px;
			margin-left: 20px;
			padding: 2px;
		}
		#sideNav>li>ul>li>a{
			padding: 8px;
		}
		.rounded {
		  border-radius: 4px;
		}

		#sideOption{
			background: #F7F5FA;
		}



    </style>
  </head>

  <body>
  	
	<?php include("header.php"); ?>

	<!-- 开始库单列表 -->
	
	<div class="container-fluid" style="margin-top:60px">
		<?php 
			//var_dump($_POST);
			//var_dump($_GET);
			//var_dump($_SESSION);
		?>
		<div class="row-fluid">
			<div class="col-lg-3">

				<ul class="nav nav-pills nav-stacked rounded"   id="sideNav">
				  <li><a class="navLink" href="#agentBox">代理商</a></li>
				  <!-- <li><a class="navLink" href="#packageBox">货物包</a></li> -->
				  <li>
				  	<a data-toggle="collapse" data-target="#appsNav" class="navLink allApp" href="#appListBox">货单</a>
				  	<ul class="nav collapse nav-pills nav-stacked rounded" id="appsNav">
				  		<li><a class="navLink inApp" href="#appListBox">入库单</a></li>
				  		<li><a class="navLink outApp" href="#appListBox">出库单</a></li>
				  		<li><a class="navLink " href="#appListBox">发库单</a></li>
				  		<li><a class="navLink " href="#appOpPanel" id="navEditAppBtn">新建/修改库单</a></li>
				  		<li><a class="navLink " href="#appDetailBox" id="navViewAppBtn">查看库单详情</a></li>
				  	</ul>
				  </li>
				  <li><a class="navLink" href="#trayBox">托盘</a></li>
				  <li><a class="navLink" href="#actionBox">记录</a></li>
				  <li><a class="navLink" href="#staticsBox">统计</a></li>
				  <li>
				  	<a data-toggle="collapse" data-target="#wareNav"  class="navLink" href="#wareHouseBox">仓库</a>
				  	<!--<ul class="nav collapse nav-pills nav-stacked rounded" id="wareNav">
				  		<li><a  class="navLink">cangku1</a></li>
				  		<li><a  class="navLink">cangku2</a></li>
				  		<li><a  class="navLink">cangku3</a></li>
				  	</ul>-->
				  </li>
				  <li><a  class="navLink">配置</a></li>
				</ul>
				<br>

				<div id="sideOption" class="nav-stacked rounded">
					
					<p>筛选库单列表<br></p>

					<!--
					<div class="dropdown input-sm form-group" id="applistFilterDiv" >
					  <label>类型</label>
					  <button class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown">筛选<span class="caret"></span></button>
					  <span class="dropdown-arrow"></span>
					  <ul class="dropdown-menu">
							<li class="selected"><a id="allAppsbtn">全部</a></li>
							<li><a id="AppUnComp">未完成</a></li>
							<li><a id="AppUnSign">等签字</a></li>
							<li><a id="AppIn">入库单</a></li>
							<li><a id="AppOut">出库单</a></li>
					  </ul>
					</div>
					-->
					<div class="form-group input-sm" style="height:70px">
						<label>时间</label><br>
						<input id="start-date" type="date" style="width:150px"></input><br>
						<input id="end-date" type="date" style="width:150px"></input>
						<a class="btn  btn-primary btn-xs filterUpdate" style="padding:6px 6px"> <span class="glyphicon glyphicon-search" ></span></a>
					</div>

					<div class="form-group input-sm" style="height:120px" ><br>
						<label>关键字</label>
						<input id="app-keyword" type="search" style="width:100px"></input>
						<a class="btn  btn-primary btn-xs filterUpdate" style="padding:6px 6px"> <span class="glyphicon glyphicon-search"></span></a>
					</div>
				</div>
			</div>


			<div class="col-lg-9">
				<!--代理商-->
				<div id="agentBox" class="navBlock">
					代理商录入中
				</div>
				<!-- 托盘包 -->
				<div id="trayBox" class="navBlock">
					托盘包录入中
				</div>
				<!-- 仓库 -->
				<div id="wareHouseBox" class="navBlock">
					仓库包录入中
				</div>
				<!-- 动作记录 -->
				<div id="actionBox" class="navBlock">
					记录录入中
				</div>
				<!-- 统计数据 -->
				<div id="staticsBox" class="navBlock">
					统计初始化
				</div>
				<div id="appDetailBox" class="navBlock">
						库单详情
						<h1>详情</h1>
				</div>
				<div id="appListBox" class="panel panel-default navBlock">
					<!-- 库单签名modal页 -->
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
					<!-- 库单签名modal页结束 -->


					<div class="panel-heading " id="appListHeader">库单列表
						<span class="  pull-right" style="font:5px" id="phpAppCount" num="<?php echo getTableLength('wApplications'); ?>">
						  总数:
						  <span class="btn-tip"><?php echo getTableLength('wApplications'); $tatalPage = ceil(getTableLength('wApplications')/10);?></span>
						</span>
						
						
						
						<!--&nbsp;<a class="pull-right"><span class="fui-plus">新增</span></a>&nbsp;-->
						
					</div>
					<div class="panel-body">
					
						<div id="Apptxt" class="scrolls-horizontal">
						</div> 
						<div id="appPagi"></div> 	
					</div>

					
				</div>
        <div id="_applicationDebug">
        	<p class="text-info">
        		<?php include_once("_applicationPhp.php"); ?>
          </p>
        </div>
        <?php
					//var_dump($_POST);
				?>
        <div data-role="page" class="panel panel-default navBlock" id="appOpPanel">
					<div class="panel-heading">
						接收新货单
                        <span class="pull-right"><a id="generateOutAppBtn" class="btn btn-default" disabled onClick="newOutAppShow()">生成出库单</a><span>  </span><a class="btn btn-primary" onClick="newAppShow()">新建货单</a></span>
					</div>
                    <div class="panel-body">
               
						<form class="form-horizontal" role="form" method="POST" >
              <p>
	              <div class="form-group input">
	                <label for="selectpicker" class="control-label col-sm-2">选择货代</label>
									<div class="col-sm-8">
										<select class="selectpicker" id="agentSelect" name="agentSelect">
										  <option value="0">货代数据导入失败</option>
										</select>
										<a href="_agents.php" ><span class="fui-user">编辑货代  </span></a>
									</div>
							  </div>
              </p>
              <div class="form-group input">
								<label for="InStockID" class="col-sm-2 control-label">货品名称*</label>
								<div class="col-sm-10"><input name="appName" id="appName" type="text" class="form-control flat" placeholder="货品名称"></div>
						  </div>

						  <div class="form-group input">
								<label for="InStockID" class="col-sm-2 control-label">进仓编号*</label>
								<div class="col-sm-4"><input name="InStockID" id="InStockID" type="text" class="form-control flat" placeholder="请输入进仓编号"></div>
								<label for="appMaitou" class="col-sm-2 control-label">麦头*</label>
								<div class="col-sm-4"><input name="appMaitou" id="appMaitou" type="text" class="form-control flat" placeholder="货品麦头"></div>
						  </div>
						  
						  <div class="form-group input">
								<label for="stockid" class="col-sm-2 control-label">货号</label>
								<div class="col-sm-4"><input name="stockid" id="stockid" type="text" class="form-control flat" placeholder="例如:D9493C - 暂时无用"></div>
								<label for="appCount" class="col-sm-2 control-label">箱数*</label>
								<div class="col-sm-4"><input name="appCount" id="appCount" type="number" class="form-control flat" placeholder="箱数量"></div>
						  </div>
						  
						  <div class="form-group input">
								<label for="deliverComp" class="col-sm-2 control-label">送货单位或地点</label>
								<div class="col-sm-4"><input name="deliverComp" id="deliverComp" type="text" class="form-control flat" placeholder="送货公司/地点来源"></div>
								<label for="deliverTruckID" class="col-sm-2 control-label">送货车号</label>
								<div class="col-sm-4"><input name="deliverTruckID" id="deliverTruckID" type="text" class="form-control flat" placeholder="货车牌号"></div>
						  </div>
						  
						  <div class="form-group input">
								<label for="deliverDriver" class="col-sm-2 control-label">送货司机</label>
								<div class="col-sm-4"><input name="deliverDriver" id="deliverDriver" type="text" class="form-control flat" placeholder="送货人姓名"></div>
								<label for="deliverMobile" class="col-sm-2 control-label">司机联系方式*</label>
								<div class="col-sm-4"><input name="deliverMobile" id="deliverMobile" type="text" class="form-control flat" placeholder="手机或座机"></div>
						  </div>
						  <div class="form-group input">
								<label for="extraInfo" class="col-sm-2 control-label">备注</label>
								<div class="col-sm-10"><input name="extraInfo" id="extraInfo" type="text" class="form-control flat" placeholder="备注"></div>
						  </div>
						  <div class="form-group">
							<label for="selectpicker" class="control-label col-sm-2">选择货物尺寸</label>
							<div class="col-sm-10">
								<select class="selectpicker" id="itemSizeSelect" name="itemSizeSelect">
								  <option value="0">货代数据导入失败</option>
								</select>
							</div>
						  </div>
							<div class="col-sm-4"></div>
							<div class="col-sm-2"><button id="appOpPanelNew" type="submit" name="new"  class="btn-embossed btn btn-primary form-control">新建</button></div>
						  	<div class="col-sm-2"><button id="appOpPanelEdit" style="display:none" type="submit" name="edit" class="btn-embossed btn btn-primary form-control">更新</button></div>
						  	<div class="col-sm-2"><button id="appOpPanelDelete" style="display:none" type="submit" name="delete" class="btn-embossed btn btn-danger form-control">删除</button></div>
						  	<div class="col-sm-2"><button id="appOpPanelGenerateOutApp" style="display:none" type="submit" name="newOut" class="btn-embossed btn btn-primary form-control">新建出库</button></div>
						  	
							
						      <div >
								<input  id="formappID" name="appID"  style="display:none" >
								<input  id="formappType" name="appType" value="in"  style="display:none">&nbsp;
							  </div>
							  
							 <div class="col-sm-12">
								<p id="debug" class="form-group col-sm-6">debug info:</p>
							 </div>
							
						
                        </form>
                        	
                    </div>
                       
                	
                 </div>
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
		//user的信息;
		var userName = "<?php echo $_SESSION['user'];?>";
		var userID = "<?php echo $_SESSION['userID'];?>";
		//过滤基值
		var appFilter = {page:1,db:"wApplications",printInfo:getFilter,para:null,key:null,count:0};
		//无过滤输入
		ReloadAppTxt();

		//页面跳转
		/* 页面响应 */
		$(".navBlock").hide();
		function jumpPage(pTarget,linkName){
			$(".navBlock").hide();
			$($(pTarget)).show();
			$('.navLink').parent().removeClass("active");
			$(linkName).parent().addClass('active');
		}
		$(".navLink").on("click",function(event){
			$('#hintAlert').html("");
			$(".navBlock").hide();
			$($(this).attr('href')).show();
			$('.navLink').parent().removeClass("active");
			$(this).parent().addClass('active');

			if($(this).hasClass('inApp')){
				appFilter.para = "AppIn";
				appFilter.page = 1;
				ReloadAppTxt();
			}
			if($(this).hasClass('outApp')){
				appFilter.para = "AppOut";
				appFilter.page = 1;
				ReloadAppTxt();
			}
			if($(this).hasClass('allApp')){
				//console.log($(this).hasClass('allApp'));
				appFilter.para = "AppAll";
				appFilter.page = 1;
				ReloadAppTxt();
			}
			//库单详情
			if($(this).attr('href') == "#appDetailBox"){
				console.log("查看库单详情123");
				$('#appDetailBox').html("<h5>未选定库单<br><small>从入库单或出库单中选择</small></h5>");
			}
			//货代
			if($(this).attr('href') == "#agentBox"){
				console.log("货代包");
				$('#agentBox').load("agents.php?appID=64");
			}
			//仓库盒子
			if($(this).attr('href')=="#wareHouseBox"){
				console.log("查找托盘");
				$("#wareHouseBox").load("wHouse.php#houseBody");
			}
			//托盘盒子
			if($(this).attr('href') == "#trayBox"){
				console.log("查找托盘");
				$("#trayBox").load("trays.php#traysBody");
			}

			//动作
			if($(this).attr('href') == "#actionBox"){
				console.log("动作集合");
				$('#actionBox').load("action.php");
			}
			//统计
			if($(this).attr('href') == "#staticsBox"){
				console.log("统计");
				$('#staticsBox').load("statics.php");
			}
		});
		//新建货单UI
	  function newAppShow(){
   		$('#appOpPanelNew').show();
			$('#appOpPanelEdit').hide();
			$('#appOpPanelDelete').hide();
			$('#appOpPanelGenerateOutApp').hide();
			$('#createNewApp').html("新建货单");
			$('#appName').val("");
			$('#InStockID').val("");
			$('#appMaitou').val("");
			//$('#stockid').val(obj[0].stockid);
			$('#appCount').val(0);
			$('#InStockID').removeAttr('readonly');
			//生成出库单按钮响应
			$('#generateOutAppBtn').attr("disabled","disabled");
			$('#generateOutAppBtn').addClass("btn-default");
			$('#generateOutAppBtn').removeClass("btn-primary");
	  }
		//新建出库单UI
		function newOutAppShow(){
			$('#appOpPanelNew').hide();
			$('#appOpPanelEdit').hide();
			$('#appOpPanelDelete').hide();
			$('#appOpPanelGenerateOutApp').show();
			$('#InStockID').attr('readonly','readonly');

			$('#createNewApp').html("生成出库单");
			$('#formappType').val('out');
		}

		//修改库单UI
		function editAppShow(){
			console.log("修改模式");
			$('#appOpPanelNew').hide();
			$('#appOpPanelEdit').show();
			$('#appOpPanelDelete').show();
			$('#appOpPanelGenerateOutApp').hide();
			$('#InStockID').attr('readonly','readonly');	
		}
		/* 页面响应结束*/
		


		
		
		//过滤响应
		function getFilter(){
			var str = "getPage.php?";
			str += "page="+this.page;
			str += "&&"+"db="+this.db;
			
			if(this.para) str += "&&"+"para="+this.para;
			
			this.key = $('#app-keyword').val();
			if(this.key) str += "&&"+"key="+this.key;
			
			this.startDate = $('#start-date').val();
			this.endDate = $('#end-date').val();
			if( (this.startDate) && (this.endDate) )
			str += "&&"+"from="+this.startDate+"&&to="+this.endDate;
			return str;
		}
		$('#app-keyword').on("keyup",function(event){
			if(event.which == 13){
				ReloadAppTxt();
				console.log($('#start-date').val());
			}
		});
		$('.filterUpdate').click(function(){
			ReloadAppTxt();
		});
		
		
		
		//重新加载库单列表
		function ReloadAppTxt(){
			console.log("页单筛选:"+appFilter.printInfo());

			$.ajax({
		    type : "get", 
		    url : appFilter.printInfo(),
		    async : false, 
		    success : function(data){
		    	$('#Apptxt').html(data);
					updatePaginate();
	    	}
	    });
	    updatePaginate();
	    /*
			$('#Apptxt').load(appFilter.printInfo(),function(){

				//afterGeTNewAppList();
				addEditBtnEvent();
				
				updatePaginate();
			});
			*/
		}
		//筛选里的类型下拉单响应
		$('#applistFilterDiv a').click(function(data){
			$('#applistFilterDiv button').html($(this).text()+"<span class=\"caret\"></span>");
			$('#appListBox .selected').removeClass("selected");
			$(this).parent().addClass("selected");
			appFilter.para = $(this).attr('id');
			//console.log(appFilter.printInfo());
			ReloadAppTxt();
		});
		
		//页面事件
		function updatePaginate(){

			appFilter.itemLength = $("#phpAppCount").attr("num");//$('table').attr("count");
			console.log("页面数量"+appFilter.itemLength);
			var pageCount =1;
			if(appFilter.itemLength>0)
				pageCount = Math.ceil(appFilter.itemLength/10);
			$("#appPagi").paginate({ 
			count         : pageCount, 
			start         : 1, 
			display     : 10, 
			border					: false,
			text_color  			: '#888',
			background_color    	: '#EEE',	
			text_hover_color  		: 'black',
			background_hover_color	: '#CFCFCF',
			mouse                   : 'press', 
			onChange                : function(page){
										console.log("改变页数"+page+"/"+pageCount+";总数:"+appFilter.itemLength);
										appFilter.page = page;
										$.ajax({
									    type : "get", 
									    url : appFilter.printInfo(),
									    async : false, 
									    success : function(data){
									    	$('#Apptxt').html(data);
									    	//addEditBtnEvent();
												updatePaginate();
								    	}
								    });
								    /*
										$("#Apptxt").load(appFilter.printInfo(),function () { 
											//加载需要从getPage内响应的元素
											$('.btn-succss').click(function(){
												$('#modalSign').load("component.php?appID="+$(this).attr('id'));
											});
											//签发按钮
											$('#signedBtn').click(function(){
												var hint = "<div class=\"alert alert-success\" id=\"hintAlert\"><a href=\"#\" class=\"alert-link\">成功签发</a></div>";
												$('#appListHeader').append(hint);
												$('#hintAlert').load("component.php?sign="+$('#appKey').attr('key'));
												addAgentMemo("signed");
												$('#appSignedModal').modal('toggle');
											});
											//addEditBtnEvent();
																					
										});*/
									}
			});
		}
			//});\
		

			
		//准备货代数据 
		$.post("_search.php?table=wAgents",function(data){
			var obj = jQuery.parseJSON(data);
			var ops = "";
			for(var i = 0; i<obj.length; i++){
				ops += "<option value=\""+obj[i].waID+"\" >"+obj[i].waName+"</option>";
			}
		  $('#agentSelect').html(ops);
	   });
		   
	   //准备具体货物数据 是否需要，留用
	   $.post("_search.php?table=wareItems",function(data){
			//console.log(data);
		   var obj = jQuery.parseJSON(data);
		   var ops = "<option value=\"0\" >未确定尺寸</option>";
		   for(var i = 0; i<obj.length; i++){
				//console.log(obj[i]);
		   		ops += "<option value=\""+obj[i].wiID+"\" >"+obj[i].width+"cm*"+obj[i].length+"cm*"+obj[i].height+"cm,"+obj[i].weight+"kg"+"</option>";
		   }
		   $('#itemSizeSelect').html(ops);
		   //$('#itemSizeSelect').selectpicker();
	   });
		   
	  
		//签发按钮的响应
		//这个响应是针对签发按钮的，需要修改
		/*
		function afterGeTNewAppList(){
			//加载需要从getPage内响应的元素
			$('.btn-signedApp').click(function(){
				$('#modalSign').load("component.php?appID="+$(this).attr('id'));
				//签发按钮
				$('#signedBtn').click(function(){
					var hint = "<div class=\"alert alert-success\" id=\"hintAlert\"><a href=\"#\" class=\"alert-link\">成功签发</a></div>";
					$('#appListHeader').append(hint);
					$('#hintAlert').load("component.php?sign="+$('#appSignedUl').attr('key'));
					$('#appSignedModal').modal('toggle');
				});
			});
		}*/
    </script>
    
	<?php 
		mysqli_close($connection);
	?>
  </body>
</html>