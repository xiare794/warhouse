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
    <link href="../css/flat-ui-demo.css" rel="stylesheet">
  </head>

  <body>
  	
	<?php include("header.php"); ?>
	
	<div class="container" style="margin-top:100px">
		<?php 
			//插入一个新的item
			/*
			function insert($table, $para,$data){
				global $connection;
				$query = "INSERT INTO ".$table." (";
					
				foreach($para as $attr){
					$query .= $attr;
					if(array_search($attr,$para) != (count($para)-1)) $query .= ", ";
				}
				$query .= ") VALUES (";
				for( $i = 0; $i<count($data); $i++){
					
					$query .= "'".$data[$i]."'";
					if($i < count($data)-1) $query .= ", ";
				}
				$query .= ")";

				$result = mysqli_query($connection,$query);
				if(!$result){
				 echo '[we have a problem]: '.mysqli_error($connection)."<br>query [".$query."]";
				}else{
					echo "成功添加".$data[0].$data[1]."到".$table;
				}
			}
			//更新一个item
			function update($table, $para,$data){
				global $connection;
				$query = "UPDATE `".$table."` SET ";
				for($i = 1; $i<count($para); $i++){
					if($i != 1)
						$query .= ", ";
					$query .= "`".$para[$i]."`=\"".$data[$i]."\"";	
				}
				$query .= " WHERE `".$para[0]."` = \"".$data[0]."\"";
				
				$result = mysqli_query($connection,$query);
				if(!$result){
				 echo '[we have a problem]: '.mysqli_error($connection)."<br>query [".$query."]";
				}else{
					echo "成功修改".$table."中的元素";
				}
			}
				
			//删除一个item
			function delete($table, $para,$data){
				global $connection;
				$query = "DELETE FROM `".$table."`";
				$query .= " WHERE `".$para."` = \"".$data."\"";
				
				$result = mysqli_query($connection,$query);
				if(!$result){
				 echo '[we have a problem]: '.mysqli_error($connection)."<br>query [".$query."]";
				}else{
					echo "成功删除".$table."中的元素";
				}
			}
			*/
			//var_dump($_POST);
			echo "<code>";
			
			if(isset($_POST['new'])){
				$attrs=array("waName","waType","waAddr","waTel","waContact");
				$vals = array($_POST['waName'],$_POST['waType'],$_POST['waAddr'],$_POST['waTel'],$_POST['waContact']);
				insert('wAgents',$attrs, $vals);
			}
			if(isset($_POST['save'])){
				$attrs=array("waID","waName","waType","waAddr","waTel","waContact");
				$vals = array($_POST['waID'],$_POST['waName'],$_POST['waType'],$_POST['waAddr'],$_POST['waTel'],$_POST['waContact']);
				update('wAgents',$attrs, $vals);
			}
			if(isset($_POST['delete'])){
				$attrs="waID";
				$vals =$_POST['waID'];
				delete('wAgents',$attrs, $vals);
			}
			echo "</code>";
			//var_dump($_GET);
			//var_dump($_SESSION);
			
		?>
		<div id="hintZone"> </div>
		<div class="row">
			<div class="col-lg-12">
				
				<div class="panel panel-default" id="agentBox">
					<div class="panel-heading">
						货代列表
						<p class="pull-right"><a onclick="CreateAgent()"><span class="fui-plus">新增</span></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;总数:<?php echo getTableLength('wagents');?></p>
					</div>
					<div class="panel-body" id="agentBox">
					</div>
					<div class="panel-body" id="extraDiv">
					</div>
					<div class="panel-body" id="agentFormDiv" style="display:none">
						<div class="panel panel-default">
							<div class="panel-heading">
								货代信息
							</div>
							<form method="POST" role="form"  id="agentForm" class="form-horizontal" class="panel-body">
								<div class="form-group input-sm">
									<label for="waID" class="col-sm-2 control-label">货代编号</label>
									<div class="col-sm-3">
										<input id="waID" name="waID" type="text" class="form-control flat" readonly placeholder="auto" >
									</div>
									<label for="waName" class="col-sm-2 control-label">货代名称</label>
									<div class="col-sm-5">
										<input id="waName" name="waName" type="text" class="form-control flat" placeholder="货代全名" >
									</div>
								</div>
								<div class="form-group input-sm">
									<label for="waType" class="col-sm-2 control-label">货物类型</label>
									<div class="col-sm-3">
										<input  id="waType" name="waType" type="text" class="form-control flat" placeholder="类型" >
									</div>
									<label for="waAddr" class="col-sm-2 control-label">联系地址</label>
									<div class="col-sm-5">
										<input id="waAddr" name="waAddr" type="text" class="form-control flat" placeholder="地址" >
									</div>
								</div>
								<div class="form-group input-sm">
									<label for="waTel" class="col-sm-2 control-label">联系电话</label>
									<div class="col-sm-3">
										<input  id="waTel" name="waTel" type="text" class="form-control flat" placeholder="手机或固定电话" >
									</div>
									<label for="waContact" class="col-sm-2 control-label">联系人</label>
									<div class="col-sm-5">
										<input id="waContact" name="waContact" type="text" class="form-control flat" placeholder="联系人姓名" >
									</div>
								</div>
								<div class="form-group">
									<span class="col-sm-6"></span>
									<div class="col-sm-2"><button type="submit" name="new" id="agent_new_btn" class="btn-embossed btn btn-primary form-control">新建</button></div>
									<div class="col-sm-2"><button type="submit" name="save" id="agent_save_btn" class="btn-embossed btn btn-primary form-control">保存</button></div>
									<div class="col-sm-2"><button type="submit" name="delete" id="agent_delete_btn" class="btn-embossed btn btn-danger form-control">删除</button></div>
								</div>
								<code id="codeHint"></code>
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
	<script type="text/javascript" src="../js/bootstrap-select.min.js"></script>
	<script type="text/javascript" src="../js/jquery.paginate.js"></script> 
	<script type="text/javascript"> 
		
		$(function(){ 
			//agent box
			var packages;
			var apps;
			$.post("_search.php?table=warePackages",function(data){
				packages = jQuery.parseJSON(data);
				$.post("_search.php?table=wApplications",function(data){
					apps = jQuery.parseJSON(data);
					$.post("_search.php?table=wagents",function(data){
						var obj = jQuery.parseJSON(data);
						var agentStr = "<table class=\"table table-condensed table-hover\" style=\"font-size:10px\">";
						agentStr += "<thead><th>ID/货代名称</th><th>分类/库单</th><th>电话</th><th>地址</th><th>查看</th></thead><tbody>";
						obj.reverse();
						for( var i in obj){
							agentStr += "<tr><td>"+obj[i]['waID']+"/"+obj[i]['waName']+"</td>";
							agentStr += "<td>"+obj[i]['waType']+"</td>";
							agentStr += "<td>"+obj[i]['waContact']+"/"+obj[i]['waTel']+"</td><td>"+obj[i]['waAddr']+"</td>";
							agentStr += "<td><a title=\""+obj[i]['waID']+"/"+obj[i]['waName']+"\" val=\""+obj[i]['waID']+"\" class=\"packInclude\">查看详细</a>&nbsp;&nbsp;<a val=\""+obj[i]['waID']+"\" class=\"packEdit\">修改</a></td></tr>";
						}
						agentStr += "</tbody></table>";
						
						$('#agentBox').html(agentStr);
						//显示点击
						$('.packInclude').click(function(){
							var output = "<p>"+$(this).attr('title')+"</p>";
							output += "<table class=\"table table-condensed table-hover\" style=\"font-size:10px\">";
							output += "<thead><th></th><th>ID/入库编码</th><th>名称/出入</th><th>数量</th><th>时间</th></thead><tbody>";
							for( var i in packages){
								if(packages[i]['wpAgentID'] == $(this).attr('val')){
									console.log(packages[i]);
									var tWpID = packages[i]['wpID'];
									
									for(var j in apps){
										if(apps[j]['wpID'] == tWpID){
											console.log(apps[j]);
											output += "<tr><td></td><td>"+apps[j]['appID']+"/"+apps[j]['InStockID']+"</td>";
											output += "<td>"+apps[j]['appName']+"/"+apps[j]['appType']+"</td><td>"+apps[j].appCount+"箱</td><td>"+apps[j].appBookingDate+"</td>";
										}
									}
								}
							}
							$('#extraDiv').html(output);
							//隐藏列表
							$('#extraDiv').show();
							//显示表单
							$('#agentFormDiv').hide();
						});
						
						$('.packEdit').click(function(){
							//隐藏列表
							$('#extraDiv').hide();
							//显示表单
							$('#agentFormDiv').show();
							//按钮配置
							$('#agent_new_btn').attr('disabled',"disabled");
							$('#agent_save_btn').removeAttr('disabled',"disabled");
							$('#agent_delete_btn').removeAttr('disabled',"disabled");
							
							
							//表单赋值
							var tr = $(this).parent().parent().find('td');
							console.log(tr.eq(0).text());
							$('#waID').val(tr.eq(0).html().split("/")[0]);
							$('#waName').val(tr.eq(0).html().split("/")[1]);
							$('#waType').val(tr.eq(1).html());
							$('#waTel').val(tr.eq(2).html().split("/")[1]);
							$('#waContact').val(tr.eq(2).html().split("/")[0]);
							$('#waAddr').val(tr.eq(3).html());
							
							//试探货代下面是否有货物包，如果有，禁止删除
							$('#codeHint').html("");
							for( var i in packages){
								if(packages[i]['wpAgentID'] == tr.eq(0).html().split("/")[0]){
									//存在货物包，禁止删除
									$('#agent_delete_btn').attr('disabled',"disabled");
									$('#codeHint').html("该货代下存在货物包，无法删除")
								}
							}

							
						
						});
					});
				});
			});
		 });//end of complete html
		
		function CreateAgent(){
			console.log('click');
			//隐藏列表
			$('#extraDiv').hide();
			//显示表单
			$('#agentFormDiv').show();
			//表单清空
			$('#agentFormDiv :input').val("");
			//按钮配置
			$('#agent_new_btn').removeAttr('disabled',"disabled");
			$('#agent_save_btn').attr('disabled',"disabled");
			$('#agent_delete_btn').attr('disabled',"disabled");
		}
		
	</script>
	<?php 
		mysqli_close($connection);
	?>
  </body>
</html>