
<?php require_once("config.php"); //session
?>
<DOCTYPE html>
<html lang="zh-cn">
	<head>
		<?php
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
		<meta name="description" content="WSLayout">
		<meta name="author" content="Ref">


		<title>记录</title>

		
		
	</head>
	<body >
		<!-- agent container -->
			<div id="agentBody"  >
				
				
				<blockquote  id="editAgent" style="display:none; border:1px #F1F1F1 solid; margin-top:0px margin-bottom:0px; background-color:#F2FFFF">
					<form role="form" class="row" id="editAgentForm">
						<div class="col-lg-12"> <h4>货代表格 <a class="btn btn-warning btn-sm pull-right" id="cancelBtn" style="margin-right:15px" >放弃操作</a><hr></h4> </div>
						<input type="text" class="form-control" id="waID" name="waID" placeholder="waID" style="display:none">
					  <div class="form-group col-lg-3 col-md-3 input-xs">
					    <label for="exampleInputEmail1">货代名称</label>
					    <input type="text" class="form-control input-xs" id="waName" name="waName" placeholder="货代名称">
					  </div>

					  <div class="form-group col-lg-3 col-md-3" class="input-xs">
					    <label for="exampleInputEmail1">货代类型</label>
					    <input type="text" class="form-control" id="waType" name="waType" placeholder="货代类型">
					  </div>

					  <div class="form-group col-lg-3 col-md-3" class="input-xs">
					    <label for="exampleInputEmail1">货代联系方式</label>
					    <input type="text" class="form-control" id="waTel" name="waTel" placeholder="货代联系方式">
					  </div>

					  <div class="form-group col-lg-3 col-md-3" class="input-xs">
					    <label for="waAddr">货代地址</label>
					    <input type="text" class="form-control" id="waAddr" name="waAddr" placeholder="货代地址">
					  </div>
					  <div class="form-group col-lg-3 col-md-3" class="input-xs">
					    <label for="waContact">联系人</label>
					    <input type="text" class="form-control" id="waContact" name="waContact" placeholder="联系人">
					  </div>
					  <div class="form-group col-lg-3 col-md-3" class="input-xs">
					    <label for="CODE">代码</label>
					    <input type="text" class="form-control" id="CODE" name="CODE" placeholder="代码">
					  </div>
					  <div class="form-group col-lg-6 col-md-6" class="input-xs">
					    <label for="waNote">备注</label>
					    <input type="text" class="form-control" id="waNote" name="waNote" placeholder="备注">
					  </div>
					  <div class="col-lg-3 col-lg-offset-9 col-md-3 col-md-offset-9 ">
						  <a class="btn btn-warning btn-sm" id="cancelBtn" >放弃操作</a>
						  <a class="btn btn-primary btn-sm" id="newAgentBtn" style="display:none">保存新建</a>
						  <a class="btn btn-success btn-sm" id="updateAgentBtn" style="display:none">更新</a>
						  <a class="btn btn-danger btn-sm" id="deleteAgentBtn" style="display:none">删除</a>
						</div>
					</form>
				</blockquote>
				

					<div class="navbar navbar-default" role="navigation">
						<div class="container-fluid">
							<div class="navbar-header">
								<a class="navbar-brand" href="#">代理商</a>
							</div>
							<div class="navbar-form navbar-left" role="search">
						        <div class="form-group">
						          <input type="search" id="agentSearchInput" class="form-control" placeholder="筛选代理商">
						        </div>
						        
						     </div>
						     <div class="navbar-form navbar-right">
						      	<button  class="btn btn-default btn-sm form-control" id="createAgentBtn">新增货代</button>
						     </div>
						</div>
					</div>

				
				<div class="panel panel-default scrolls-both" id="agentBoxContainer">
					<div id="agentBoxContainerRelated"  ></div>
					<div id="agentBoxContainerUnRelated"  ></div>
				</div>
				
				<div class="panel panel-default" id="agentInStock" >
				</div>


				<div class="modal fade" id="newAgentModal" tabindex="-1" role="dialog" >
			    <div class="modal-dialog ">
			        <div class="modal-content">
			            <div class="modal-header">
				            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				            <h4 class="modal-title" id="myModalLabel">新增货代表格</h4>
			            </div>
			            <form id="newAgentModalForm" role="form" method="POST">
				            <div class="modal-body">
				            	
				                <!--<h3>Modal Body</h3>-->

						          <div class="form-group">
						          	<label for="waName">名称</label>
			    							<input type="text" class="form-control input-xs" id="waName" name="waName" placeholder="货代名称">
						          </div>
										  <div class="form-group" class="input-xs">
										    <label for="waTel">联系方式</label>
										    <input type="text" class="form-control" id="waTel" name="waTel" placeholder="货代联系方式">
										  </div>
										  <div class="form-group">
										    <label for="CODE">代码</label>
										    <input type="text" class="form-control" id="CODE" name="CODE" placeholder="代码">
										  </div>
										  <div class="form-group">
										    <label for="exampleInputEmail1">备注</label>
										    <input type="text" class="form-control" id="waNote" name="waNote" placeholder="备注">
										  </div>
								      
				            </div>
				            <div class="modal-footer">
				                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				                <button type="button" class="btn btn-primary" id="submitNewAgent">保存新建货代</button>
					        	</div>
					        </form>
				    </div>
				  </div>
				</div>

				<div class="modal fade" id="editAgentModal" tabindex="-1" role="dialog" >
			    <div class="modal-dialog">
			        <div class="modal-content">
			            <div class="modal-header">
				            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				            <h4 class="modal-title" id="myModalLabel">货代信息</h4>
			            </div>
			            <form id="editAgentModalForm" role="form" method="POST">
				            <div class="modal-body">
				            	<div id="editAgentModalInfo">
				            	</div>
				                <!--<h3>Modal Body</h3>-->

						          <div class="form-group">
						          	<label for="waName">名称</label>
			    							<input type="text" class="form-control input-xs" id="waName" name="waName" placeholder="货代名称">
						          </div>
										  <div class="form-group" class="input-xs">
										    <label for="waTel">联系方式</label>
										    <input type="text" class="form-control" id="waTel" name="waTel" placeholder="货代联系方式">
										  </div>
										  <div class="form-group">
										    <label for="CODE">代码</label>
										    <input type="text" class="form-control" id="CODE" name="CODE" placeholder="代码">
										  </div>
										  <div class="form-group">
										    <label for="exampleInputEmail1">备注</label>
										    <input type="text" class="form-control" id="waNote" name="waNote" placeholder="备注">
										  </div>
								      
				            </div>
				            <div class="modal-footer">
				                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				                <button type="button" class="btn btn-warning" id="openEditAgentForm" >修改货代</button>
				                <button type="button" class="btn btn-primary" id="submitEditAgentForm" style="display:none">保存修改</button>
				                <button type="button" class="btn btn-danger" id="deleteEditAgentForm">删除本货代</button>
					        	</div>
					        </form>
				    </div>
				  </div>
				</div>

			</div>



			
		
	</body>
	

  <script>
  	//agent的三个需求
  	//显示列表 为初始化状态
		//通过点击列表，显示详细信息：包括货物包基本情况，支持跳转到货物包
		//操作 新建修改删除

		//代理商目录需要看到的是每个代理商 一共有哪些货物在仓库或者在运

		var userName = "<?php echo $_SESSION['user'];?>";
		var userID = "<?php echo $_SESSION['userID'];?>";
		

		console.log(userName);
		
		var query  = "(SELECT * ";
		query 		+= "FROM wAgents a, wAppIn app ";
		query 		+= "WHERE a.waID=app.agentID  GROUP by app.InstockID )";
		query 		+= " ORDER by app.agentID;"	
		$.ajax({ 
	    type : "post", 
	    url : "_search.php?query="+query,
	    async : false, 
	    success : function(data){
	    	//console.log(data);
				var head = new Array("货代名称","代码","货单内容","进仓编号","数量","入库时间");
				var attr = new Array("waName","CODE", "appName","InStockID","appPreCount","appBookingDate");
				var obj = jQuery.parseJSON(data);
				var link = null;
				//console.log(obj);
				$('#agentBoxContainerRelated').html( FormPanelTable(obj,attr,head,link,"代理商列表"));
	    }
	  });

		query  = "SELECT * ";
		query 		+= "FROM `wAgents` ";
		query 		+= "WHERE wAgents.waID not in (SELECT `agentID` FROM `wAppin`)";
	  $.ajax({ 
	    type : "post", 
	    url : "_search.php?query="+query,
	    async : false, 
	    success : function(data){
	    	//console.log(data);
				var head = new Array("货代名称","代码","联系方式");
				var attr = new Array("waName","CODE","waTel");
				var obj = jQuery.parseJSON(data);
				var link = null;
				//console.log(obj);
				$('#agentBoxContainerUnRelated').html( FormPanelTable(obj,attr,head,link,"未入仓代理商"));
	    }
	  });

			
			//增加过滤
			$('#agentSearchInput').on("keyup",function(event){
				if(event.which == 13){
					var str = $('#agentSearchInput').val();

					$(".agentRow").each(function(){
						$(this).hide();
						if($(this).html().match(str)){
							$(this).show();
						}
					});

				}
			});

			//对货代编辑添加事件
			$('.agentIDLoad').on("click",function(){
				var waID = $(this).attr('data');
				//$('#editAgent').show();
				//$('#agentInStock').hide();

			
				var modalhtml = "";
				//获取对应货代
				$("#openEditAgentForm").show();
				$("#submitEditAgentForm").hide();
				$("#deleteEditAgentForm").hide();

				if( $(this).attr("deleteOk")=="1"){
					console.log("货代可以被删除");
					$("#deleteEditAgentForm").show();

					$("#deleteEditAgentForm").unbind("click").on("click",function(){
						var deleteQuery = "DELETE FROM `wAgents` WHERE `waID`="+waID;
						console.log(deleteQuery);
						$.post("_search.php?delQuery="+deleteQuery,function(data){
							if(data[0]=="1"){
								addRemind("操作成功","删除货代"+$("#editAgentModal #waName").val(),3000);
								addAgentMemo("delete");
							}
							else{
								addRemind("操作失败","删除货代遇到错误"+data,3000);
							}
						});
						$('#editAgentModal').modal('hide');
					});

				}

				$.ajax({ 
			    type : "post", 
			    url : "_search.php?table=wAgents&attr=waID&val="+$(this).attr('data'),
			    async : false, 
			    success : function(data){
			    	var obj = jQuery.parseJSON(data);
			    	modalhtml += "<h4>"+obj[0]['waName']+"</h4>";
						modalhtml += "<p>ID:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+obj[0]['waID']+"<br>";
						modalhtml += "代码:&nbsp;&nbsp;&nbsp;&nbsp;"+obj[0]['CODE']+"<br>";
						modalhtml += "联系:&nbsp;&nbsp;&nbsp;&nbsp;"+obj[0]['waTel']+"<br>";
						modalhtml += "备注:&nbsp;&nbsp;&nbsp;&nbsp;"+obj[0]['waNote']+"</p>";

						$("#editAgentModal #waName").val(obj[0]['waName']);
						$("#editAgentModal #waTel").val(obj[0]['waTel']);
						$("#editAgentModal #CODE").val(obj[0]['CODE']);
						$("#editAgentModal #waNote").val(obj[0]['waNote']);

						$("#editAgentModalForm").attr("waID",obj[0]['waID']);
			    }
			  });
			  /*
				$.post("_search.php?table=wAgents&attr=waID&val="+$(this).attr('data'),function(data){
					console.log(data);
					var obj = jQuery.parseJSON(data);
					//console.log(obj);
					$("#waID").val(obj[0]['waID']);
					$("#waName").val(obj[0]['waName']);
					$("#waType").val(obj[0]['waType']);
					$("#waAddr").val(obj[0]['waAddr']);
					$("#waContact").val(obj[0]['waContact']);
					$("#waTel").val(obj[0]['waTel']);
					$("#waNote").val(obj[0]['waNote']);

					$("#updateAgentBtn").show();
					$("#deleteAgentBtn").show();
					$("#newAgentBtn").hide();

					modalhtml += "<h4>"+obj[0]['waName']+"</h4>";
					modalhtml += "<p>ID&nbsp;&nbsp;&nbsp;&nbsp;:"+obj[0]['waID']+"<br>";
					modalhtml += "<p>地址:"+obj[0]['waAddr']+"<br>";
					modalhtml += "<p>联系方式:"+obj[0]['waContact']+"<br>";
					modalhtml += "<p>电话:"+obj[0]['waTel']+"<br>";
					modalhtml += "<p>备注:"+obj[0]['waNote']+"</p>";

				});*/

				
				//使用新建modal
				var options = {
					"backdrop":true,
					"keyboard":true,
					"show":true
				}
				$("#editAgentModal").modal(options);
				$("#editAgentModal .form-group").hide();
				
				

				$('#editAgentModal').unbind("hidden.bs.modal").on('hidden.bs.modal', function (e) {
				  // do something...
				  //console.log("modal 重新增加关闭事件");
				});
				$("#editAgentModal #editAgentModalInfo").html(modalhtml);
				$("#openEditAgentForm").unbind("click").on("click",function(){ //修改货代开启
					$("#editAgentModal #editAgentModalInfo").html(""); //不显示html
					$("#editAgentModal .form-group").show();
					$("#editAgentModal #submitEditAgentForm").show();
					$("#openEditAgentForm").hide();
					
					
				});
				$("#submitEditAgentForm").unbind("click").on("click",function(){
					var waID = $("#editAgentModalForm").attr("waID");
					console.log("更新"+waID+"货代");
					console.log($("#editAgentModal #waName"));
					$.get("phpUpdate.php?table=wAgents&&idAttr=waID&&idValue="+waID+"&&tAttr=waName&&tValue="+$("#editAgentModal #waName").val());
					$.get("phpUpdate.php?table=wAgents&&idAttr=waID&&idValue="+waID+"&&tAttr=waTel&&tValue="+$("#editAgentModal #waTel").val());
					$.get("phpUpdate.php?table=wAgents&&idAttr=waID&&idValue="+waID+"&&tAttr=CODE&&tValue="+$("#editAgentModal #CODE").val());
					$.get("phpUpdate.php?table=wAgents&&idAttr=waID&&idValue="+waID+"&&tAttr=waNote&&tValue="+$("#editAgentModal #waNote").val());
					addAgentMemo("edit");
					$('#editAgentModal').modal('hide');
				});

			});
			//新增货代按钮
			$('#createAgentBtn').on("click",function(){
				//$('#editAgent').show();
				//$('#agentInStock').hide();
				//$("#updateAgentBtn").hide();
				//$("#deleteAgentBtn").hide();
				//$("#newAgentBtn").show();
				//使用新建modal
				var options = {
					"backdrop":true,
					"keyboard":true,
					"show":true
				}
				$("#newAgentModal").modal(options);

				$('#newAgentModal').unbind("hidden.bs.modal").on('hidden.bs.modal', function (e) {
				  // do something...
				  console.log("modal 重新增加关闭事件");
				});

				//$("#newAgentModal .modal-body").html("<p>动态增加modal</p>");

				$("#newAgentModal #submitNewAgent").unbind("click").on("click", function(event){
					console.log("modal 提交新货代，并且关闭modal");
					$.post( "_insert.php?table=wAgents", $( "#newAgentModalForm" ).serialize())
						.done(function(data){
							console.log(data);
							if(data[0]=="1"){
								$('#DialogRfidHint').html("绑定成功");
								addRemind("新增货代成功","新增货代"+$('#waName')+"成功",3000);
								addAgentMemo("new");
							}
							else{
								$('#DialogRfidHint').html(data);
								addRemind("新增货代失败","新增货代"+$('#waName')+"失败",3000,"bs-callout-danger");
							}
							$('#newAgentModal').modal('hide');
					});
				});


			});
			//取消操作按钮，
			//$('#cancelBtn').on('click',function(){
			//	$('#editAgent').hide();
			//});
			
			//新建按钮
			/*$('#newAgentBtn').on("click",function(data){
				$.post( "_insert.php?table=wAgents", $( "#editAgentForm" ).serialize())
						.done(function(data){
							console.log(data);
							if(data[0]=="1"){
								$('#DialogRfidHint').html("绑定成功");
								addRemind("新增货代成功","新增货代"+$('#waName')+"成功",3000);
								addAgentMemo("new");
							}
							else{
								$('#DialogRfidHint').html(data);
								addRemind("新增货代失败","新增货代"+$('#waName')+"失败",3000,"bs-callout-danger");
							}
					});
			});*/
			//增加备忘
			function addAgentMemo(type){
				var memo = "";
				var typeHint = "";
				if(type == "new"){
					typeHint = "新增货代";
					memo ="新增货代:";
				}
				else if(type == "edit"){
					typeHint = "修改货代";
					memo ="修改货代:";
				}
				else if(type == "delete"){
					typeHint = "删除货代";
					memo ="删除货代:"
				}
				
				//var memo = "修改货代：";
				$(".has-warning").each(function(data){
					var input = $(this).find("input");
					memo += input.attr("placeholder")+"为"+input.val()+";";
				});
				console.log(memo);
				//需要userID，actType actTime,#inStockID,#trayID,#trayID,#slotID,actContent
				var query = "?actUserID="+userID+"&&actType="+type+"Agent"+"&&actTime=--&&actContent="+memo;
				//console.log("_insertAct.php"+query);
				
				$.post( "_insertAct.php"+query,function(data){
					//console.log(data);
					if(data == 1){
						addRemind(typeHint+"成功",memo,4000,"bs-callout-info");
						$('#editAgent').hide();
					}
					else {
						addRemind(typeHint+"失败",data,4000,"bs-callout-danger");
					}
				});
			}
			//修改信息
			/*
			$("#updateAgentBtn").on("click",function(data){
					console.log("更新"+$("#waID").val()+"货代");
					$.get("phpUpdate.php?table=wAgents&&idAttr=waID&&idValue="+$("#waID").val()+"&&tAttr=waName&&tValue="+$("#waName").val());
					$.get("phpUpdate.php?table=wAgents&&idAttr=waID&&idValue="+$("#waID").val()+"&&tAttr=waType&&tValue="+$("#waType").val());
					$.get("phpUpdate.php?table=wAgents&&idAttr=waID&&idValue="+$("#waID").val()+"&&tAttr=waAddr&&tValue="+$("#waAddr").val());
					$.get("phpUpdate.php?table=wAgents&&idAttr=waID&&idValue="+$("#waID").val()+"&&tAttr=waContact&&tValue="+$("#waContact").val());
					$.get("phpUpdate.php?table=wAgents&&idAttr=waID&&idValue="+$("#waID").val()+"&&tAttr=waTel&&tValue="+$("#waTel").val());
					$.get("phpUpdate.php?table=wAgents&&idAttr=waID&&idValue="+$("#waID").val()+"&&tAttr=waNote&&tValue="+$("#waNote").val());
					addAgentMemo("edit");
					
			});*/
			$('input').on("blur",function(){
					//console.log("从input退出");
					$(this).parent().addClass("has-warning");
			});
			/*
			$('#deleteAgentBtn').on("click",function(data){
				console.log("暂时不提供此功能");
				addRemind("删除货代","暂时不提供删除功能",3000,"bs-callout-danger");
			});*/
			//这里需要根据编码返回1，所有编码下的入库出库匹配的货物名称，数量。
			//2，未完成的出库的详细连tray的信息
			$('.agentLoad').on('click',function(data){
				console.log("告警 ---具体货物进仓编号");
				//console.log($(this).attr('data'));
				$('#agentInStock').show();
				$('#editAgent').hide();
				//这里需要的是所有已完成出入库的为一组
				var query = "SELECT app1.InStockID, app1.appName, app1.appID appIn, app2.appID appOut, app1.appCount,app1.appCount inCount, app1.appBookingDate inTime, app2.appBookingDate outTime, app2.appCount outCount ";
				query 		+="FROM wApplications app1, wApplications app2 ";
				query 		+="WHERE app1.appType=\"in\" AND app2.appType=\"out\" AND app1.appComplete=1 AND app2.appComplete=1 AND app1.InstockID=app2.InstockID AND app1.InstockID=\""+$(this).attr('data')+"\"";			
				$.post("_search.php?query="+query,function(data){
					//console.log(data);
					var obj = jQuery.parseJSON(data);
					//console.log(obj);
					var head = new Array("货物代码","名称","入库单","入库数量","入库时间","出库单","出库数量","出库时间");
					var attr = new Array("InStockID","appName","appIn","inCount","inTime","appOut","outCount","outTime");
					$('#agentInStock').html(FormPanelTable(obj,attr,head,null,"已完成出库货物列表"));
				});
				//未完成出入库的为一组
				var query = "SELECT *";
				query 		+="FROM wApplications app, wTrays t ";
				query 		+="WHERE (t.wtAppID=app.appID OR t.wtAppOutID=app.appID) AND app.InstockID=\""+$(this).attr('data')+"\" ";
				query 		+="ORDER by t.UpdateTime";
				console.log(query);
				$.post("_search.php?query="+query,function(data){
					//console.log(data);
					var obj = jQuery.parseJSON(data);
					//console.log(obj);


					var link = "appID";
					var head = new Array("appID","是否完成","进仓编号","名称","托盘","app出入","托盘状态","托盘内货物","更新时间");
					var attr = new Array("appID","appComplete","InStockID","appName","wtID","appType","twStatus","twWareCount","UpdateTime");
					$('#agentInStock').append(FormPanelTable(obj,attr,head,link,"库内货物"));

					$('.appOpPanelDetail').on("click",function(){
						console.log("查找货单"+$(this).attr('id'));
						//库单id= $(this).attr('id')
						//显示详细情况div = #appDetailBox
						//console.log( "appDetail.php?appID="+ $(this).attr('id') );
						console.log($(this).attr('data'));
						$('#appDetailBox').load("appDetail.php?appID="+$(this).attr('data'));

						jumpPage("#appDetailBox","#navViewAppBtn");
					});

				});
			});

			

		//});
		
		//生成表格形式 返回字符串
		function FormPanelTable(obj,attr,head,link,title){
			//console.log(obj);
			var output = "<div class=\"panel-heading\" \>"+title+"</div>";
			output 	+= "<div class=\"panel-body\">";

			output 	+= "<table class=\"table table-condensed table-hover\" style=\"font-size:85%;\"";
			output 	+= "<thead>";
			for(var i in head){
				output += "<th>"+head[i]+"</th>";
			}
			output 	+= "</thead>";
			output 	+= "<tbody>";

			var previousAg = "";
			var extra = "";
			if(title == "未入仓代理商")
				extra += " deleteOk=\"1\" ";
			if(title == "代理商列表")
				extra += " deleteOk=\"0\" ";


			for(var i in obj){
				output += "<tr class=\"agentRow\">";
				for(var j in attr){
					if(link == attr[j]){
						//console.log("相同");
						output += "<td><a  class=\"agentLoad btn btn-default btn-xs\" data=\""+obj[i][attr[j]]+"\">"+obj[i][attr[j]]+"</a></td>";
					}
					//省略货代名称和联系
					else if("waName" == attr[j] && obj[i][attr[j]]==previousAg){
						//console.log("get hree 达到");
						output += "<td style=\"visibility:hidden\">"+obj[i][attr[j]]+"</td>";
					}
					//对货代名称增加link
					else if ("waName" == attr[j]){
						output += "<td><a class=\"agentIDLoad btn btn-default btn-xs\" data=\""+obj[i]['waID']+"\""+extra+">"+obj[i]["waName"]+"</a></td>";
					}

					else{
						output += "<td>"+obj[i][attr[j]]+"</td>";
					}

				}
				output += "</tr>";
				previousAg = obj[i]['waName'];
				
			}
			output 	 += "</tbody>";
			output 	 += "</table>";
			output 	 += "</div>";
			return output;
		}

		


		
	</script>
</html>