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
		//错误信息
		$errorMsg = "";
		//如果已知货物包ID
		if(isset($_GET['pid']) && isset($_POST['createApp']) ){
			insertApplication($_POST);//新建一个单据
		}
		if(isset($_POST['appAction'])){
			operatorApp($_POST['appAction'],$_POST['appIDtoApply']);
		}
			
	?>
	
	
					
	<div class="container" style="margin-top:60px">
		<?php var_dump($_POST);
			var_dump($_GET);
			
			$currPID = 0;
			if(isset($_GET['pid']))
				$currPID = $_GET['pid'];
		?>
		<?php if($errorMsg != null) echo "<pre>".$errorMsg."</pre>"; ?>
		<div class="panel panel-success">
			<div class="panel-heading"><h2>选择货物包</h2>
				
			</div>
			<div class="panel-body">
				<h4>1.选择货物包
				<select id="wPackagesSelect"  class="selectpicker show-tick show-menu-arrow">
				<?php $items =  getTableIDNameArray("warePackages","wpID","wpFullName");
					for($i = 0; $i<count($items); $i++){
						echo "<option";
						if((int)$items[$i] == $currPID)
							echo " selected ";
						echo " value ='".(int)$items[$i]."'>".$items[$i]."</option>";
					}
				?>
				
				</select></h4>
				<div id='table'>
					<?php
						if(isset($_GET['pid'])) 
							printAppByPID($_GET['pid']);
					?>
				</div>
				<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
					  <div class="modal-content">
						<div class="modal-header">
						  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						  <h4 class="modal-title">创建一个新表单</h4>
						</div>
						<form role="form" method="POST">
						<div class="modal-body">
							
							  <div class="form-group">
								<label >货物包</label>
								<input name="wpID" class="form-control" value="<?php echo $_GET['pid']; ?>" readonly >
							  </div>
							  <div class="form-group">
								<label for="exampleInputEmail1">选择表单类型</label>
								<select name="appType" class="form-control" id="appTypeOptionControl">
									<option>入库</option>
									<option>移库</option>
									<option>出库</option>
								</select>
							  </div>
							  <div class="form-group">
								<label >货物名称</label>
								<input class="form-control" type="text" id="appName" name="appName" placeholder="货物名称">
							  </div>
							  <div class="form-group">
								<label >货物数量(单位：箱)</label>
								<input class="form-control" type="text" id="appCount" name="appCount" placeholder="输入阿拉伯数字">
							  </div>
							  <div class="form-group">
								<label >预计时间(xxxx-xx-xx)</label>
								<input class="form-control" type="text" id="appBookingDate" name="appBookingDate" placeholder="预计入库时间">
							  </div>
							  <div class="form-group">
								<label >负责人</label>
								<select name="appOperator" class="form-control">
									<option>user</option>
									<option>关羽</option>
									<option>刘备</option>
									<option>曹孟德</option>
								</select>
							  </div>
							  <div class="form-group">
								<input class="form-control" type="text" style="display:none" name="appComplete" value="0" >
								<input class="form-control" type="text" style="display:none" name="appStatus" value="开启" >
								<input class="form-control" type="text" style="display:none" name="appSignned" value="0">
								<input class="form-control" type="date" style="display:none" name="appDate">
							  </div>
												
						</div>
						<div class="modal-footer">
						  <button type="submit" name="createApp" class="btn btn-default">确认</button>
						  <button type="submit" class="btn btn-default" data-dismiss="modal">取消</button>
						  <button type="submit" class="btn btn-primary">Save changes</button>
						</div>
						</form>	
					  </div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div><!-- /.modal -->
			</div>
		</div>
		<div class="panel panel-success">
			<div class="panel-heading"><h3>所有开启的表单</h3>
			</div>
			 <div class="panel-body">
				<p>所有开启状态的表单</p>
				<?php printAllAppOpen(); ?>
			 </div>
		</div>
		<div class="panel panel-success">
			<div class="panel-heading"><h3>关闭或删除表单</h3>
			</div>
			 <div class="panel-body">
				<p><code>关闭表单</code>将表单状态设置到关闭，即不可用表单，将来也不再执行，关闭状态的表单还将继续留存在数据库中</p>
				<p><code>删除表单</code>彻底删除表单，不可恢复或查看</p>
				<form method="POST" role="form" action="appAdd.php">
					<div class="form-group">
						<label class="col-lg-2 control-label">输入表单号</label>
						<div class="col-lg-10">
							<input name="appIDtoApply" class="form-control" placeholder="输入表单号" >
						 	<div class="radio">
							  <label>
								<input type="radio" name="appAction" id="optionsRadios1" value="delete" checked>
								删除表单
							  </label>
							</div>
						 	<div class="radio">
							  <label>
								<input type="radio" name="appAction" id="optionsRadios1" value="close" >
								关闭表单
							  </label>
							</div>
							
						</div>
						
					</div>
					<button class="btn btn-primary">执行</button>
				</form>
			 </div>
		</div>
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
					?>
					
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
	<script type="text/javascript" src="../js/bootstrap-select.js"></script>
	<script type="text/javascript" src="../js/jquery.paginate.js"></script> 
	<script type="text/javascript" >
		$('#wPackagesSelect').change(function(){
			
			console.log( $('#wPackagesSelect').val());
			window.location.href = "appAdd.php?pid="+$('#wPackagesSelect').val();
		});
		 $(window).on('load', function () {
			//初始化选择器
            $('.selectpicker').selectpicker();
			//如果地址含有pid,pid已经选择
			if( GetQueryString('pid')){
				//console display
			}
			CreateAppRule();
			//console.log($('#table tr').length);
			//$('#appTypeOptionControl').html()
        });
		//$('#createAppModal').modal('toggle');
		//js获取当前地址的$_GET项
		//成功返回数值或字符，失败返回Null
		function GetQueryString(name) 
		{ 
			var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)"); 
			var r = window.location.search.substr(1).match(reg); 
			if (r!=null) return unescape(r[2]); 
				return null; 
		}
		function CreateAppRule(){
			var rows = $('#table tr').length;
			var inOk = moveOk = outOk = true;
			var importComplete = false;
			console.log( rows);
			if(rows != 0){
				for(var i=1; i<rows; i++){
					var type = $('#table tr:eq('+i+')>th:eq(3)').html();
					var complete = $('#table tr:eq('+i+')>th:eq(8)').html();
					var availible = $('#table tr:eq('+i+')>th:eq(9)').html();
					
					//console.log($('#table tr:eq('+i+')>th:eq(8)').html());
					if(type =="入库" && complete == 1)
						importComplete = true;
					if(type == '入库' && availible == "开启")
						inOk = false;
					if( availible == "开启" ) 
						moveOk = false;
				}
				console.log(inOk,moveOk);
				$str = "";
				if(inOk)
				$str += "<option>入库</option>";
				if(importComplete&&moveOk){
				$str += "<option>移库</option>";
				$str += "<option>出库</option>";
				}
				console.log($('#appTypeOptionControl'));
				$('#appTypeOptionControl').html($str);
			}
			else
			{
				$str = "<option>入库</option>";
				$('#appTypeOptionControl').html($str);
			}
			
		}
		
	</script> 
	
	<?php 
		mysqli_close($connection);
	?>
  </body>
</html>