<?php require_once("config.php"); //session
?>
<!DOCTYPE html>
<html lang="cn">
  <head>
  	<?php
  		
  		include("_db.php"); 
  	 	include("functions_manage.php");
  	 	$jumpBool = false;
  	 	//如果没有用户信息，就跳转回登陆页面
  		if(!isset ($_SESSION['user']) ){
  			$_SESSION['previewPage'] = curPageURL();
  			$jumpBool = true;
  			//echo curPageURL();
  			echo "<meta HTTP-EQUIV=\"REFRESH\" content=\"0; url=login.php\">";
  		}
  		else {
  			$jumpBool = false;
  			//通过验证，继续执行
  		}
  		//执行库单操作
  		include("_applicationPhp.php");


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
    <!-- Loading uploadify -->
    <link rel="stylesheet" type="text/css" href="uploadify/uploadify.css"/>

	<!-- Loading remind Css 提示样式 -->
	<link href="../css/remind.custom.css" rel="stylesheet">
	
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
	    height:600px;
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
		/*自定义*/
		.panel-body{
			padding: 3px;
		}

		.form-control{
			height: 34px;
			padding-top: 5px;
		}
		label{
			font-weight: bold;
			line-height: 1.2em;
			font-size: 80%
		}

		.anchor{
			position: relative;
			top: -44px;
		}

		.tabletd{
			text-overflow:ellipsis;
			white-space:nowrap; 
			overflow:hidden; 
		}


    </style>
  </head>

  <body>
  	
	<?php 
		include("header.php");
		//如果没验证成功不显示
		if($jumpBool){
  			echo "<!--";
  	}

	?>

	<!-- 开始库单列表 -->
	
	<?php if($jumpBool){
  			echo "<!--";
  	}?><div class="container-fluid" style="margin-top:60px">
		<?php 
			//var_dump($_POST);
			//var_dump($_GET);
			//var_dump($_SESSION);
		?>
		
		<div class="col-lg-3 col-md-3 col-sm-4 remindBox" id="remindBox">
			
		</div>
		<div class="row">
			<div class="col-lg-2 col-md-2 col-sm-2">

				<ul class="nav nav-pills nav-stacked rounded" id="sideNav" style="margin-left:5px">
				  <li><a class="navLink" href="#agentBox">代理商</a></li>
				  
				  <li>
				  	<a data-toggle="collapse" data-target="#appsInNav" class="navLink" href="#appListBox">入库管理</a>
				  	<ul class="nav collapse nav-pills nav-stacked rounded" id="appsInNav">
				  		<li><a class="navLink allApp " href="#appListBox" >入库单</a></li>
				  		<li><a class="" href="#" id="createAppInNav" >新建/修改库单</a></li>
				  		<li><a class="navLink " href="#appDetailBox" id="navViewAppBtn">查看库单详情</a></li>
				  	</ul>
				  </li>
				  <li>
				  	<a data-toggle="collapse" data-target="#appsOutNav" class="navLink" href="#appOutBox">出库管理</a>
				  	<ul class="nav collapse nav-pills nav-stacked rounded" id="appsOutNav">
				  		<li><a class="navLink " href="#appOutBox">出库单</a></li>
				  		<li><a class="navLink " href="#container">集装箱</a></li>
				  	</ul>
				  </li>
				  <li><a class="navLink" href="#trayBox">托盘</a></li>
				  <li><a class="navLink" href="#actionBox">记录</a></li>
				  <li><a class="navLink" href="#staticsBox">统计</a></li>
				  <li>
				  	<a data-toggle="collapse" data-target="#wareNav"  class="navLink" href="#wareHouseBox">仓库</a>
				  </li>
				  <li><a  class="navLink">配置</a></li>
				</ul>
				

				<div id="sideOption" class="nav rounded" style="padding:5px;margin-top:5px;margin-left:5px">
					<!--
					<div id="appsOption" style="display: none;">
						<p>筛选库单列表<br></p>

						<div class="form-group input-sm" style="height:70px">
							<label>时间</label><br>
							<input id="start-date" type="date" style="width:150px"></input><br>
							<input id="end-date" type="date" style="width:150px"></input>
							<a class="btn  btn-primary btn-xs filterUpdate" style="padding:6px 6px"> <span class="glyphicon glyphicon-search" ></span></a>
						</div>
	
						<div class="form-group input-sm"  ><br>
							<label>关键字</label>
							<input id="app-keyword" type="search" style="width:100px"></input>
							<a class="btn  btn-primary btn-xs filterUpdate" style="padding:6px 6px"> <span class="glyphicon glyphicon-search"></span></a>
						</div>
						<div class="form-group input-sm">
							<input type="checkbox" name="filterComplete" id="appFilterComplete" class="filterUpdate" checked="true"/>仅显示未完成
							<input type="checkbox" name="filterDetail" id="appFilterDetail" class="filterUpdate" checked="true"/>精简显示
						</div>
					</div>
				-->
					
				
					<div id="uploadBox">
							<form>
								<div id="queue"></div>
								<input id="file_upload" name="file_upload" type="file" multiple="true">
							</form>
							
					</div>

					<a class="btn btn-default" style="margin-left:20px" id="clickPrint">click print</a>
					<a class="btn btn-warning" style="margin-left:20px" id="clickPrintPDF">PDF</a>
				</div>
			</div>


			<div class="col-lg-10 col-md-10 col-sm-10" style="padding:0px 20px 0px 2px">
				<div id="hint_TMP_BOX" >
				</div>
				<div id="agentBox" class="navBlock">
					代理商录入中
				</div>
				
				<div id="trayBox" class="navBlock">
					托盘包录入中
				</div>
				
				<div id="wareHouseBox" class="navBlock">
					仓库包录入中
				</div>
				
				<div id="actionBox" class="navBlock">
					记录录入中
				</div>
				
				<div id="staticsBox" class="navBlock">
					统计初始化
				</div>
				<div id="appDetailBox" class="navBlock">
						库单详情
						<h1>详情</h1>
				</div>
				<div id="appOutBox" class="navBlock">
					出库单录入中
				</div>
				<div id="container" class="navBlock">
					集装箱
				</div>
				<div id="appListBox" class="panel panel-default navBlock" >
					
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
							<button type="button" class="btn btn-primary" id="signedBtn" 
								<?php if( !($_SESSION["job"] == 1 || $_SESSION["job"] ==6) ) {
													echo "disabled=\"disabled\"";
													
												}
								 ?>
							>签署</button>
						  </div>
						</div>
					  </div>
					</div>


					<div class="panel-heading " id="appListHeader" >
						入库单列表
						<span class="  pull-right" style="font:5px" id="phpAppCount" num="<?php echo getTableLength('wAppIn'); ?>">
						  总数:
						  <span class="btn-tip"><?php echo getTableLength('wAppIn'); $tatalPage = ceil(getTableLength('wAppIn')/10);?></span>
						</span>
						
						
						
						
						
					</div>
					<div class="panel-body" style="padding:0px">
						<div id="AppFilter" style="background:#DDD;">
							<div id="appsOption" style="display: none; background:#F5F5F5; padding-bottom:5px">
								<form class="form-inline" >
									<div class="form-group" style="padding-left:10px">
										<label>选项:</label>
									</div>

									<div class="form-group input-sm" >
										<label>时间</label>
										<input id="start-date" type="date" style="width:150px"/>
										<input id="end-date" type="date" style="width:150px"/>
										<a class="btn  btn-primary btn-xs filterUpdate" style="padding:6px 6px"> <span class="glyphicon glyphicon-search" ></span></a>
									</div>
				
									<div class="form-group input-sm"  >
										<label>关键字</label>
										<input id="app-keyword" type="text"  />
										<a class="btn  btn-primary btn-xs filterUpdate" style="padding:6px 6px"> <span class="glyphicon glyphicon-search"></span></a>
									</div>
									<div class="form-group input-sm">
										<input type="checkbox" name="filterNeedSign" id="appFilterSign" class="filterUpdate" checked="true"/>待签字
										<input type="checkbox" name="filterComplete" id="appFilterComplete" class="filterUpdate" checked="true"/>未完成
										
									</div>
								</form>
							</div>
							<span class="glyphicon glyphicon-chevron-down" aria-hidden="true" style="text-align:center; display:block;" id="AppInFilterSwitch" ></span>
							
						</div>
						<div id="Apptxt" ></div>  	
					</div>

					
				</div>
        <div id="_applicationDebug" style="display: none;">
        	<p class="text-info">
        		<?php include_once("_applicationPhp.php"); ?>
          </p>
        </div>
        

        <!--
        <div data-role="page" class="panel panel-default navBlock" id="appOpPanel">
						<div class="panel-heading">
							新建入库 <span class="pull-right"><a id="generateOutAppBtn" class="btn btn-default" disabled onClick="newOutAppShow()">生成出库单</a><span>  </span><a class="btn btn-primary" onClick="newAppShow()">新建货单</a></span>
						</div>
            
            <div class="panel-body">


							<form class="form" role="form" method="POST" onsubmit="return form_onsubmit()" id="appInCreateForm" action="_applications.php">
								<div class="row" style="border-bottom:1px solid #330; margin:15px 0px">
									<div class="col-sm-12  col-xs-12">
										<label for="appSeries" class="control-label" id="appSeriesLabel" style="width:100%; text-align:right">表单号:201410290001</label>
									  <input name="appSeries" id="appSeries" type="text" class="form-control" placeholder="201410290001" style="display:none">
									</div>
								</div>
								
								<div class="row">
									<div class="col-sm-4  col-xs-6" >
									  <label for="appName" class="control-label">货品名称*</label>
										<input name="appName" id="appName" type="text" class="form-control" placeholder="必须填写">

								  	

								  	<label for="deliverReceipt" class="control-label">代理商入库凭证*</label>
										<input name="deliverReceipt" id="deliverReceipt" type="text" readonly class="form-control uploadfile" placeholder="上传扫描件">
									  

									</div>

									<div class="col-sm-4  col-xs-6">
										
										<label for="InStockID" class="control-label">进仓编号*</label>
										<input name="InStockID" id="InStockID" type="text" class="form-control" placeholder="必须填写">

										<label for="appPreCount" class="control-label">预入数量*</label>
										<input name="appPreCount" id="appPreCount" type="number" class="form-control" placeholder="必须填写">

										<label for="appBookingDate" class="control-label">预入时间<a id="getNow" >现在</a></label>
										<input class="nowInput" name="appBookingDate" id="appBookingDate" readonly type="text" class="form-control flat" min="2014-10-23" style="width:100%">

									</div>

									<div class="col-sm-4  col-xs-6">
										
										<label for="deliverComp" class="control-label">送货公司</label>
										<input name="deliverComp" id="deliverComp" type="text" class="form-control" placeholder="送货公司">

										<label for="deliverDriver" class="control-label">送货司机</label>
										<input name="deliverDriver" id="deliverDriver" type="text" class="form-control" placeholder="送货人姓名">
										<label for="deliverMobile" class="control-label">司机联系方式*</label>
										<input name="deliverMobile" id="deliverMobile" type="text" class="form-control" placeholder="手机或座机">

										<label for="deliverTruckID" class="control-label">送货车号</label>
										<input name="deliverTruckID" id="deliverTruckID" type="text" class="form-control" placeholder="货车牌号">
										
									</div>
								</div>
								<div class="row" style="border-top:1px solid #330; margin:15px 0px">
									<div class="col-sm-12 col-xs-12" >
										<label for="opInput" class="control-label">输单员:<?php echo $_SESSION['user']; ?></label>
								  	<input type="text" id="opInput" name="opInput" readonly class="form-control" value="<?php echo $_SESSION['user']; ?>" style="display:none"/>
									</div>
								</div>
								<div class="col-sm-2 col-xs-2" style="margin-top:10px" >
									<button id="appOpPanelNew" type="submit" name="new"  class="btn-embossed btn btn-primary form-control" >新建</button>
								</div>
							</form>
                	
            </div>
               
        	
         </div><!-- page end-->


        <div class="modal fade" id="newAppInBlock" tabindex="-1" role="dialog" >
			    <div class="modal-dialog" style="width:1000px;">
			        <div class="modal-content">
			            <div class="modal-header">
				            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				            <h4 class="modal-title" id="myModalLabel">新增货单</h4>
			            </div>
			            <form id="newAppModalForm" role="form" method="POST" onsubmit="return form_onsubmit()" action="_applications.php">
				            <div class="modal-body">
				            	<div class="row">
				            		<!-- 左侧第一列 -->
				            		<div class="col-md-3 col-sm-3">
				            			<div class="form-group">
								          	<label for="appSeries">货单序号</label>
					    							<input type="text" class="form-control input-xs" id="appSeries" name="appSeries" placeholder="<?php echo "加入日期";$query = "SELECT `appSeries` FROM `wAppIn` "; echo getTableLengthByQuery($query); ?>">
								          </div>
								          <div class="form-group">
								          	<label for="agentID_input_value">货代</label>
					    							<input type="text" class="form-control input-xs" id="agentID_input_value"  placeholder="输入货代编号">
								          </div>
								          <div class="form-group">
								          	<label for="InStockID">进仓编号</label>
					    							<input type="text" class="form-control input-xs" id="InStockID" name="InStockID" placeholder="进仓编号">
								          </div>
								          <div class="form-group">
								          	<label for="appName">货名</label>
					    							<input type="text" class="form-control input-xs" id="appName" name="appName" placeholder="货品名 必须填写">
								          </div>
								        </div>

								        <!-- 左侧第2列 -->
				            		<div class="col-md-3 col-sm-3">
								          <div class="form-group">
									          <label for="appPreCount" class="control-label">预入数量*</label>
														<input name="appPreCount" id="appPreCount" type="number" class="form-control" placeholder="必须填写">
													</div>
													<div class="form-group">
														<label for="appBookingDate" class="control-label">预入时间<a id="getNow" >现在</a></label>
														<input class="nowInput" name="appBookingDate" id="appBookingDate" readonly type="text" class="form-control flat" min="2014-10-23" style="width:100%">
													</div>
				            		</div>

				            		<!-- 左侧第3列 -->
				            		<div class="col-md-6 col-sm-6">
				            			<div class="form-group" class="input-xs">
												    <label for="deliverComp" class="control-label">送货公司</label>
														<input name="deliverComp" id="deliverComp" type="text" class="form-control" placeholder="送货公司">
													</div>
													<div class="form-group" class="input-xs">
														<label for="deliverDriver" class="control-label">送货司机</label>
														<input name="deliverDriver" id="deliverDriver" type="text" class="form-control" placeholder="送货人姓名">
													</div>
													<div class="form-group" class="input-xs">
														<label for="deliverMobile" class="control-label">司机联系方式*</label>
														<input name="deliverMobile" id="deliverMobile" type="text" class="form-control" placeholder="手机或座机">
													</div>
													<div class="form-group" class="input-xs">
														<label for="deliverTruckID" class="control-label">送货车号</label>
														<input name="deliverTruckID" id="deliverTruckID" type="text" class="form-control" placeholder="货车牌号">
													</div>
				            		</div>
				            	</div>
										  <div class="form-group">
										    <label for="exampleInputEmail1">备注</label>
										    <input type="text" class="form-control" id="deliverReceipt" name="deliverReceipt" placeholder="备注">
										  </div>
								      
				            </div>
				            <div class="modal-footer">
												<div class="form-inline col-md-6 col-sm-6 pull-left" style="text-align:left" >
													<label for="opInput" class="control-label">输单员:<?php echo $_SESSION['user']; ?></label>
											  	<input type="text" id="opInput" name="opInput" readonly class="form-control" value="<?php echo $_SESSION['userID']; ?>" style="display:none"/>
												</div>
											
				                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				                <button type="submit" class="btn btn-primary" name="new">保存新建货单</button>
					        	</div>
					        </form>
				    </div>
				  </div>
				</div> <!-- modal end-->

				<div class="modal fade" id="AppEditInBlock" tabindex="-1" role="dialog" >
			    <div class="modal-dialog" style="width:1000px;">
			        <div class="modal-content">
			            <div class="modal-header">
				            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				            <h4 class="modal-title" id="myModalLabel">修改货单</h4>
			            </div>
			            <form id="AppEditModalForm" role="form" method="POST" onsubmit="return form_onsubmit()" action="_applications.php">
				            <div class="modal-body">
				            	<div class="row">
				            		<!-- 左侧第一列 -->
				            		<div class="col-md-3 col-sm-3">
				            			<div class="form-group">
								          	<label for="appSeries">货单序号</label>
					    							<input type="text" class="form-control input-xs" id="appSeries" name="appSeries" placeholder="<?php echo "加入日期";$query = "SELECT `appSeries` FROM `wAppIn` "; echo getTableLengthByQuery($query); ?>">
								          </div>
								          <div class="form-group">
								          	<label for="agentID_input_value">货代</label>
					    							<input type="text" class="form-control input-xs" id="agentID_input_value"  placeholder="输入货代编号">
								          </div>
								          <div class="form-group">
								          	<label for="InStockID">进仓编号</label>
					    							<input type="text" class="form-control input-xs" id="InStockID" name="InStockID" placeholder="进仓编号">
								          </div>
								          <div class="form-group">
								          	<label for="appName">货名</label>
					    							<input type="text" class="form-control input-xs" id="appName" name="appName" placeholder="货品名 必须填写">
								          </div>
								        </div>

								        <!-- 左侧第2列 -->
				            		<div class="col-md-3 col-sm-3">
								          <div class="form-group">
									          <label for="appPreCount" class="control-label">预入数量*</label>
														<input name="appPreCount" id="appPreCount" type="number" class="form-control" placeholder="必须填写">
													</div>
													<div class="form-group">
														<label for="appBookingDate" class="control-label">预入时间<a id="getNow" >现在</a></label>
														<input class="nowInput" name="appBookingDate" id="appBookingDate" readonly type="text" class="form-control flat" min="2014-10-23" style="width:100%">
													</div>
				            		</div>

				            		<!-- 左侧第3列 -->
				            		<div class="col-md-6 col-sm-6">
				            			<div class="form-group" class="input-xs">
												    <label for="deliverComp" class="control-label">送货公司</label>
														<input name="deliverComp" id="deliverComp" type="text" class="form-control" placeholder="送货公司">
													</div>
													<div class="form-group" class="input-xs">
														<label for="deliverDriver" class="control-label">送货司机</label>
														<input name="deliverDriver" id="deliverDriver" type="text" class="form-control" placeholder="送货人姓名">
													</div>
													<div class="form-group" class="input-xs">
														<label for="deliverMobile" class="control-label">司机联系方式*</label>
														<input name="deliverMobile" id="deliverMobile" type="text" class="form-control" placeholder="手机或座机">
													</div>
													<div class="form-group" class="input-xs">
														<label for="deliverTruckID" class="control-label">送货车号</label>
														<input name="deliverTruckID" id="deliverTruckID" type="text" class="form-control" placeholder="货车牌号">
													</div>
				            		</div>
				            	</div>
										  <div class="form-group">
										    <label for="exampleInputEmail1">备注</label>
										    <input type="text" class="form-control" id="deliverReceipt" name="deliverReceipt" placeholder="备注">
										  </div>
								      
				            </div>
				            <div class="modal-footer">
												<div class="form-inline col-md-6 col-sm-6 pull-left" style="text-align:left" >
													<label for="opInput" class="control-label">输单员:<?php echo $_SESSION['user']; ?></label>
											  	<input type="text" id="opInput" name="opInput" readonly class="form-control" value="<?php echo $_SESSION['user']; ?>" style="display:none"/>
												</div>
											
				                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				                <button type="submit" class="btn btn-primary" name="new">保存修改</button>
					        	</div>
					        </form>
				    </div>
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

    <script src="_applicationsScript.js"></script>
		<script type="text/javascript" src="../js/bootstrap-select.min.js"></script>
		<!--<script type="text/javascript" src="../js/jquery.paginate.js"></script> -->
		
		<script src="../js/custom_input_thinking.js" charset="utf-8"></script>
		
  	<!-- Loading uploadify -->
    <script type="text/javascript" src="uploadify/jquery.uploadify.js"></script>

		<script type="text/javascript">

		/************   新建appin所需要的动作      ***********/
		
		Date.prototype.Format = function (fmt) { //author: meizz 
	    var o = {
	        "M+": this.getMonth() + 1, //月份 
	        "d+": this.getDate(), //日 
	        "h+": this.getHours(), //小时 
	        "m+": this.getMinutes(), //分 
	        "s+": this.getSeconds(), //秒 
	        "q+": Math.floor((this.getMonth() + 3) / 3), //季度 
	        "S": this.getMilliseconds() //毫秒 
	    };
	    if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
	    for (var k in o)
	    if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
	    return fmt;
	}

		var myDate = new Date();
		var year = myDate.getFullYear(); //获取完整的年份(4位,1970-????) 
		var month = myDate.getMonth()+1; //获取当前月份(0-11,0代表1月) 
		var date = myDate.getDate(); //获取当前日(1-31) 
		var datestr = year+"-"+month+"-"+date;

		$("#getNow").on("click",function(){
			var now = new Date().Format("yyyy-MM-dd hh:mm:ss"); 
			//var time = "";
			$("#appBookingDate").val(now);
			console.log(now);
		});

		//查找代理商序号
		var queryStr= "SELECT COUNT(*) FROM wAppIn WHERE `appBookingDate`like \"%"+datestr+"%\"";
		var appInNumToday = 0;
		$.ajax({
		    type : "get", 
		    url : "_search.php?query="+queryStr,
		    async : false, 
		    success : function(data){
		    	console.log(data);
		    	var obj = jQuery.parseJSON(data);
		    	appInNumToday += obj[0][0];
	    	}
	  });
	  var seriesT  = (appInNumToday +1).toString();
	  for(var i =4 ; i< (appInNumToday +1).toString().len; i--){
	  	seriesT = "0"+seriesT;
	  }

		var datecode = year.toString()+month.toString()+date.toString()+seriesT;
		var tableCount = <?php $query ="SELECT * FROM `wAppIn` WHERE appBookingDate like \"%".date('Y-m-d')."%\" "; echo getTableLengthByQuery($query); ?>;
		//单据号最高为999， 不足补0
		tableCount ++;
		var countStr = "";
		if(tableCount<10){
			countStr = datecode+"00"+tableCount;
		}
		else if(tableCount<100){
			countStr = datecode+"0"+tableCount;
		}
		else{
			countStr = datecode+ tableCount;
		}
		//var countStr = parseInt(tableCount+1)
		//$("#appSeriesLabel").html("作业号："+datecode);
		//console.log("tableCount"+tableCount);
		$("#appSeries").val(countStr);
		
		console.log("时间"+$(".nowInput").val());
		$(".nowInput").val(datestr);

		inputThinking("agentID",1,"wAgents");
		//$("#appOpPanelNew").attr("disabled","disabled");
		function form_onsubmit(){
			//return false;
			console.log(userJob);
			if(userJob != 1 && userJob != 6){
				addRemind("无新建权限","使用输单账户或管理员账户",5000,"bs-callout-danger");
				return false;
			}

			var errorStr= "";
			$("#newAppModalForm input").each(function(index){

				if( $(this).val() == ""){
					errorStr += $(this).attr("name")+$(this).attr("placeholder")+"缺少\n";
					console.log($(this).val());
				}
			});

			console.log(errorStr);
			if(errorStr =="")
				return true;
			else{
				addRemind("新建入库缺少参数",errorStr,5000,"bs-callout-danger");
				return false;
			}
			console.log("参数正常");
			return true;
		}


		//user的信息;
		var userName = "<?php echo $_SESSION['user'];?>";
		var userID = "<?php echo $_SESSION['userID'];?>";
		var userJob = "<?php echo $_SESSION['job'];?>";
		var hint = "<?php echo $panelHint; ?>";
		var hintTitle = "<?php echo $panelHintTitle; ?>";
		var hintType  = "<?php echo $panelType; ?>";
		console.log("告警类型"+hintType);
		console.log("userID"+userID);
		if(hint != "")
			addRemind(hintTitle,hint,5000,hintType);
		//过滤基值
		//db,DataBase;
		//printInfo: getPage?db=wAppIn
		var appFilter = {db:"wAppIn",page:1,printInfo:getFilter,para:null,key:null,count:0,filterComplete:true,filterDetail:true};
		//无过滤输入
		//ReloadAppTxt();

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
			//$('#hintAlert').html("123");
			console.log($(this));
			$(".navBlock").hide();
			$($(this).attr('href')).show();
			$('.navLink').parent().removeClass("active");
			$(this).parent().addClass('active');
	
			//console.log($(this).parent());
			
			if($(this).hasClass('allApp')){
				//$('#appsOption').show();
				appFilter.para = "AppUnComp";
				appFilter.page = 1;
				ReloadAppTxt();
			}
			//出库单列表
			if($(this).attr("href") == "#appOutBox"){
				$("#appOutBox").load("appOut.php")
			}
			//集装箱
			if($(this).attr("href") == "#container"){
				$("#container").load("containers.php")
			}
			//库单详情
			if($(this).attr('href') == "#appDetailBox"){
				console.log("查看库单详情");
				$('#appDetailBox').html("<h5>未选定库单<br><small>从入库单或出库单中选择</small></h5>");
				addRemind("未选择库单提示","从库单列表中选择库单详情",5000,"bs-callout-info");
			}
			//货代
			if($(this).attr('href') == "#agentBox"){
				console.log("货代包");
				$('#agentBox').load("agents.php");
			}
			//仓库盒子
			if($(this).attr('href')=="#wareHouseBox"){
				console.log("查找托盘");
				$("#wareHouseBox").load("wHouse.php#houseBody");
			}
			//托盘盒子
			if($(this).attr('href') == "#trayBox"){
				console.log("查找托盘");
				$("#trayBox").load("trays.php");
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
			$('#appOpPanelEdit').parent().hide();
			$('#appOpPanelDelete').parent().hide();
			$('#appOpPanelGenerateOutApp').parent().hide();
			$('#appOpPanelNew').parent().show();
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

			console.log($("input #appBookingDate"));
	  }
		//新建出库单UI
		function newOutAppShow(){
			$('#appOpPanelNew').parent().hide();
			$('#appOpPanelEdit').parent().hide();
			$('#appOpPanelDelete').parent().hide();
			$('#appOpPanelGenerateOutApp').parent().show();
			$('#InStockID').attr('readonly','readonly');

			$('#createNewApp').html("生成出库单");
			$('#formappType').val('out');
		}

		//修改库单UI
		function editAppShow(){
			console.log("修改模式");
			$('#appOpPanelNew').parent().hide();
			$('#appOpPanelEdit').parent().show();
			$('#appOpPanelDelete').parent().show();
			$('#appOpPanelGenerateOutApp').parent().hide();
			$('#InStockID').attr('readonly','readonly');	
		}
		/* 页面响应结束*/
		


		
		
		//过滤响应
		function getFilter(){
			console.log(this);
			var str = "getPage.php?";
			str += "page="+this.page;
			str += "&&"+"db="+this.db;

			//签字-已完成
			var needSign = $('#appFilterSign').is(":checked");
			if(needSign) 
			{
				this.para = "AppUnSign";
			}
			else{
				//筛选-已完成  //未完成的集合比签字大，可以覆盖 当签字筛选不用时才考虑完成度筛选
				this.filterComplete = $('#appFilterComplete').is(":checked");
				if(this.filterComplete) { 
					this.para = "AppUnComp";
				}
				else{
					this.para = "AllApp";
				}
			}
			
			str += "&&"+"para="+this.para;

			
			
			//筛选-关键字
			this.key = $('#app-keyword').val();
			if(this.key) str += "&&"+"key="+this.key;
			
			
			//筛选-时间间隔
			this.startDate = $('#start-date').val();
			this.endDate = $('#end-date').val();
			if( (this.startDate) && (this.endDate) )
			str += "&&"+"from="+this.startDate+"&&to="+this.endDate;
			//打印-筛选结果
			console.log("筛选"+str);
			return str;
		
		}


		$('#app-keyword').on("keyup",function(event){
			if(event.which == 13){
				appFilter.page = 1;
				ReloadAppTxt();
				//console.log($('#start-date').val());
			}
		});
		$('.filterUpdate').click(function(){
			appFilter.page = 1;
			ReloadAppTxt();
		});
		
		
		
		//重新加载库单列表
		function ReloadAppTxt(){
			console.log("页单筛选,load的url---"+appFilter.printInfo());
			
			$.ajax({
		    type : "get", 
		    url : appFilter.printInfo(),
		    async : false, 
		    success : function(data){
		    	$('#Apptxt').html(data);
	    	}
	    });
	    
		}
		//筛选里的类型下拉单响应
		/*
		$('#applistFilterDiv a').click(function(data){
			$('#applistFilterDiv button').html($(this).text()+"<span class=\"caret\"></span>");
			$('#appListBox .selected').removeClass("selected");
			$(this).parent().addClass("selected");
			appFilter.para = $(this).attr('id');
			//console.log(appFilter.printInfo());
			ReloadAppTxt();
		});
		*/
		//页面事件
		/*
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
								    
									}	
			});
		}
		*/
			
		

			
		//准备货代数据 
		$.post("_search.php?table=wAgents",function(data){
			var obj = jQuery.parseJSON(data);
			var ops = "";
			for(var i = 0; i<obj.length; i++){
				ops += "<option value=\""+obj[i].waID+"\" >"+obj[i].waName+"</option>";
			}
		  $('#agentSelect').html(ops);
	   });
	   
	   
	   function addRemind(title,content,time,type) {
	   		console.log("call remind");
		   	if(title== undefined){
		   		title = "测试";
		   		time = 5000;
		   	}
		   	if( type == undefined || type == ""){
		   		type = "bs-callout-info";
		   	}
		   	//var exsitRemind = $(".remindBox").find(".bs-callout").length;
		   	var newRemind = "<div  class=\"bs-callout "+type+" remind\">";
		   	newRemind += "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>";
		   	newRemind += "<h4>"+title+"</h4>";
		   	newRemind += "<p>"+content+"</p>";
		   	newRemind += "</div>";
		   	$(".remindBox").append(newRemind);
		   	$(".remindBox .remind").last().slideDown(500).delay(4000).slideUp(500,function(date){
		   		//$(".remindBox").html("");
		   		console.log($(this).remove());
		   		console.log("remind 删除");
		   	});
		 }
	   		
	 
		   
	   <?php $timestamp = time();?>
	   $('#file_upload').uploadify({
				'formData'     : {
					'timestamp' : '<?php echo $timestamp;?>',
					'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
				},
				'swf'      : 'uploadify/uploadify.swf',
				'uploader' : 'uploadify/uploadify.php',
				'onUploadComplete' : function(file) {
            alert('文件 ' + file.name + ' 上传成功.');
            $(".uploadfile").val(file.name);
            //console.log(file);
        },
        'onUploadError' : function(file, errorCode, errorMsg, errorString) {
            alert('文件 ' + file.name + ' 上传失败: ' + errorString);
        }
			});

	   $("#clickPrint").on("click",function(){
	   	 console.log("clickPrintExcel");
	   	 htmlobj=$.ajax({url:"exportExcel.php",async:false});
  		 console.log(htmlobj.responseText);
	   });
	   $("#clickPrintPDF").on("click",function(){
	   	 console.log("clickPrintPFD");
	   	 htmlobj=$.ajax({url:"exportPDF.php",async:false});
  		 console.log(htmlobj.responseText);
  		 $("#hint_TMP_BOX").html(htmlobj.responseText);
	   });

	   
    </script>
    
	<?php 
		mysqli_close($connection);
		//如果没验证成功不显示
		
	?>
  </body>
</html>