<!DOCTYPE html>
<html lang="cn">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>WareHouse 货物包</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="../css/bootstrap-select.css">
	<link rel="stylesheet" type="text/css" href="../css/jPaginateStyle.css">
	<style>
		
		.preview span{
			height: 20px;
			display: block;
			width:22px;
			border: 1px solid #99CC99;
			border-radius: 1px;

		}
		.housepreview{
			list-style: none;
		}
		.housepreview li{
			float: left;
			height: 100%;
			text-align: center;
			border: 1px solid #ddd;
			border-radius: 4px;
			padding:3px;
			margin:2px -1px 2px -1px;
			overflow:hidden;
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
		//如果是插入，使用_POST作为
		if(isset($_POST['PackageSubmit']) )
			insertWarePackages($_POST);
		if(isset($_POST['PackageUpdate']) )
			updateWarePackages($_POST);
		if(isset($_POST['PackageDelete']))
			deleteWarePackages($_POST);
		$pagesize = 10;
		$recordsCount = getTableLength('warePackages');
		$tatalPage = ceil($recordsCount/$pagesize);
	?>
	<div class="container" style="margin-top:60px">
		<?php var_dump($_POST);
			var_dump($_GET);
		?>
		<div class="row">
			<div class="col-lg-8">
				<div id="appListBox">
				</div>
				
				<div class="panel panel-default">
					<div class="panel-heading">货物包列表
						<span class="badge pull-right">总数:<?php echo getTableLength('warePackages');?></span>
						<button id="panelToggle" class="btn btn-info pull-right btn-xs">显示/隐藏</button>
						<button id="newPackage" class="btn btn-info  btn-xs">新建货物包</button>
						
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
			
				<p>
				  <?php
					  if(isset( $_GET['id'] ) ){
						$pData = getWarePackagebyPage($_GET['id']);
						//var_dump($pData);
					  }
				  ?>
				</p>
			</div>
			<div class="col-lg-4">
				<div class="panel panel-default">
					<div class="panel-heading">托盘列表<span class="badge pull-right">总数:<?php echo getTableLength('wTrays');?></span><button id="traysToggle" class="btn btn-info pull-right btn-xs">显示/隐藏</button></div>
					<div class="panel-body" id="traysToggleBody">
						
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">仓库列表<span class="badge pull-right">总数:<?php echo getTableLength('wTrays');?></span><button id="slotsToggle" class="btn btn-info pull-right btn-xs">显示/隐藏</button></div>
					<div class="panel-body" id="slotPreview">
					
					</div>
			</div>
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
			<form class="form-horizontal" role="form" method="POST" action="wPackage.php">
			  <div class="form-group">
				<label id="wpID" for="wpAgentID" class="col-lg-2 control-label">代理商(ID)</label>
				<div class="col-lg-10">
					<?php 
						if(isset ($_GET['id']))
							echo "<input type=\"hidden\" name=\"wpID\" value=\"".$_GET['id']."\" />"
					
					?>
				    <select id="wpAgentID" class="selectpicker show-tick show-menu-arrow" name="wpAgentID"  data-size="5" title="选择一个货代" data-width="100%">
						<?php $agents =  getTableIDNameArray();
							for($i = 0; $i<count($agents); $i++){
								if(isset( $_GET['id'] ) ){
									if($pData['wpAgentID'] == $i+1)
								echo "<option selected value ='".(int)$agents[$i]."'>".$agents[$i]."</option><br>";
								}
								else
								echo "<option value ='".(int)$agents[$i]."'>".$agents[$i]."</option><br>";
							}
						?>
					</select>
				</div>
			  </div>
			  <div class="form-group">
				<label for="inputEmail1" class="col-lg-2 control-label">入、移、出库单据</label>
				<div class="col-lg-10">
				  <input id="wpAppID" type="text" class="form-control" disabled placeholder="系统自动生成" value="
						<?php if(isset( $_GET['id'] ) ) {
							echo "In:".$pData['wpInAppID']."||";
							echo "Mv:".$pData['wpMoveAppID']."||";
							echo "Out:".$pData['wpOutAppID'];
							}
						?>
				  ">
				</div>
			  </div>
			  <div class="form-group">
				<label for="inputEmail1" class="col-lg-2 control-label">当前托盘</label>
				<div class="col-lg-10">
				  <input id="wpCurrTrayID" type="text" class="form-control" disabled placeholder="系统自动生成" value="
						<?php if(isset( $_GET['id'] ) ) {
							echo "目前托盘:".$pData['wpCurrTrayID'];
							}
						?>
				  ">
				</div>
			  </div>
			  <div class = "form-group">
				<div class="col-lg-offset-2 col-lg-10">
				  <div class="checkbox">
					<label>
					  <input id="wpLocked" type="checkbox" name="wpLocked" checked="checked"> 是否锁定
					</label>
					<label style="margin-left:20px">
					  预存天数<input id="wpPlanDays" type="text" name="wpPlanDays" value="<?php if(isset( $_GET['id'] ) ) echo $pData['wpPlanDays']."天" ?>"> 
					</label>
					  已存天数<input id='' type="text" name="wpInStockDays" value="<?php if(isset( $_GET['id'] ) ) echo $pData['wpInStockDays']."天" ?>"> 
					<label style="margin-left:20px">
					</label>
				  </div>
				</div>
			  </div>
			<div class="panel panel-default">
			  <div class="panel-body">
				<?php
					//print form group 
					$items = array("货物包名称"=>"wpFullName","货损状态"=>"wpLossStatus","货物件数"=>"wpCount",
								   "包装规格"=>"wpPackageStander","报关状态"=>"wpCustomerStatus","唛头"=>"wpMAITOU",
								   "毛重"=>"wpTotalWeight","净重"=>"wpWeight","体积"=>"wpVolume");
					foreach($items as $x=>$val)
					{
						echo "<div class=\"form-group\">";
						echo "<label for=\"" . $x . "\" class=\"col-lg-2 control-label\">" . $x . "</label>";
						echo "<div class=\"col-lg-10\">";
						echo "<input type=\"text\" class=\"form-control\" id=\"" . $x . "\" name=\"" . $val . "\" placeholder=\"" . $x . "\"";
						if(isset( $_GET['id'] ))
							echo "value=\"".$pData[$val]."\"";
						echo ">";
						echo "</div></div>";
					}
				?>
			  </div>
			</div>
			  
			  <div class="form-group">
				<label for="wNotePrivate" class="col-lg-2 control-label">备注</label>
				<div class="col-lg-10">
				  <textarea type="text" rows="3" class="form-control" id="wNotePrivate" name="wNotePrivate" placeholder="内部备注"><?php  if(isset( $_GET['id'] )) echo $pData["wNotePrivate"]; ?> </textarea>
				</div>
			  </div>
			  <div class="form-group">
				<label for="wNotePublic" class="col-lg-2 control-label">备注</label>
				<div class="col-lg-10">
				  <textarea type="text" rows="3" class="form-control" id="wNotePublic" name="wNotePublic" placeholder="公开备注"><?php  if(isset( $_GET['id'] )) echo $pData["wNotePublic"]; ?> </textarea>
				</div>
			  </div>
			  <div class="form-group">
				<label for="wCloseInfo" class="col-lg-2 control-label">结算信息</label>
				<div class="col-lg-10">
				  <input type="text" class="form-control" id="wCloseInfo" name="wCloseInfo" placeholder="结算信息" <?php  if(isset( $_GET['id'] )) echo "value=\"".$pData["wCloseInfo"]."\"" ?>>
				</div>
			  </div>
			  
			  <div class="form-group">
				<div class="col-lg-offset-2 col-lg-10">
				  <button type="submit" name="PackageSubmit" id="PackageSubmit" class="btn btn-default">新增</button>
				  <?php if(!isset( $_GET['id'] )) echo "<!--"; ?>
				  <button type="submit" name="PackageUpdate" id="PackageUpdate" class="btn btn-default">保存</button>
				  <button type="submit" name="PackageDelete" id="PackageDelete" class="btn btn-default">删除</button>
				  <?php if(!isset( $_GET['id'] )) echo "-->"; ?>
				</div>
			  </div>
			</form> <!-- Form结束 -->
		  </div>
		  
		</div>

		
	</div> <!-- /container -->
	<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../js/jquery.js"></script>
    <script src="../js/bootstrap.min.js"></script>
	<script type="text/javascript" src="../js/bootstrap-select.js"></script>
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
			//显示仓库
			$("#slotPreview").load('setHouse.php .preview');
			//显示货单
			function afterGeTNewAppList(){
				//加载需要从getPage内响应的元素
				$('.btn-success').click(function(){
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
			$('#appListBox').load('appList.php #appListInPanel',function(){
				$('#Apptxt').load("getPage.php?page=1&&db=wApplications",function(){
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
												$('.btn-success').click(function(){
													$('#modalSign').load("component.php?appID="+$(this).attr('id'));
												});
												//签发按钮
												$('#signedBtn').click(function(){
													var hint = "<div class=\"alert alert-success\" id=\"hintAlert\"><a href=\"#\" class=\"alert-link\">成功签发</a></div>";
													$('#appListHeader').append(hint);
													$('#hintAlert').load("component.php?sign="+$('#appKey').attr('key'));
													$('#appSignedModal').modal('toggle');
												});
											
											});
										}
				});
			});
			
			
			//console.log($('#Apptxt').html());
			//货物包的页码
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
												$('.packEditBtn').click(function(){
													window.location.href = "wPackage.php?id="+$(this).attr('id') ;
												});
											});
										}
			});
			
			$("#traysToggleBody").load("trayList.php");
			//货代选择
			$('.selectpicker').selectpicker();
			//缩略比较长的td
			shortTD();
			//响应修改货物包按键
			$('.packEditBtn').click(function(){
				window.location.href = "wPackage.php?id="+$(this).attr('id') ;
			});
			
		 });//end of complete html
			
	        
			$('#panelToggle').click(function(){
				$('#pagetxt').toggle('slow', function() {
					// Animation complete.
				  });
			});
			$('#traysToggle').click(function(){
				$('#traysToggleBody').toggle('slow', function() {
					// Animation complete.
				  });
			});
			$('#slotsToggle').click(function(){
				$('#slotPreview').toggle('slow', function() {
					// Animation complete.
				  });
			});
			
			function editPackage(id){
				window.location.href = "wPackage.php?id="+id ;
				console.log(id);
			}
			
			$('.bs-glyphicons li').click(function () {
				console.log("click");
			});

			$('#newPackage').click(function(){
				$('#packageEdit').show();
				$('#PackageSubmit').show();
				$('#PackageUpdate').hide();
				$('#PackageDelete').hide();
				$('html,body').animate({
				scrollTop: $("#packageEdit").offset().top},
				'slow');
				//$(document).scrollTo('#packageEdit');
			});
			if(<?php if(isset( $_GET['id']) ) echo "true"; else echo "false";?>){
				$('html,body').animate({
				scrollTop: $("#packageEdit").offset().top},'slow');
				$('#PackageSubmit').hide();
				$('#PackageUpdate').show();
				$('#PackageDelete').show();
			}
			
			
    </script>
    
	<?php 
		mysqli_close($connection);
	?>
  </body>
</html>