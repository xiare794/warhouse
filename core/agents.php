
<?php require_once("config.php"); //session
?>
<!DOCTYPE html>
<html lang="zh-cn"><!-- 2014.09.28 -->
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
		<style >
		.form-control{
			padding: 0px 10px;
		}		
		</style>
		
		<!-- <link href="dist/css/bootstrap.css" rel="stylesheet"> -->
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
					    <label for="exampleInputEmail1">货代地址</label>
					    <input type="text" class="form-control" id="waAddr" name="waAddr" placeholder="货代地址">
					  </div>
					  <div class="form-group col-lg-3 col-md-3" class="input-xs">
					    <label for="exampleInputEmail1">联系人</label>
					    <input type="text" class="form-control" id="waContact" name="waContact" placeholder="联系人">
					  </div>
					  <div class="form-group col-lg-9 col-md-9" class="input-xs">
					    <label for="exampleInputEmail1">备注</label>
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
								<a class="navbar-brand" href="#">代理商操作</a>
							</div>
							<div class="navbar-form navbar-left" role="search">
						        <div class="form-group">
						          <input type="search" id="agentSearchInput" class="form-control" placeholder="筛选代理商">
						        </div>
						        
						     </div>
						     <div class="navbar-form navbar-right">
						      	<button id="agentOpHint" class="btn btn-default btn-sm form-control">操作提示</button>
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
			</div>

			<div 
		
	</body>
	
	<!-- Bootstrap core js -->
	<!--<script src="dist/js/jquery-1.11.1.js"></script>
  <script src="dist/js/bootstrap.min.js"></script> -->

  <script>
  	//agent的三个需求
  	//显示列表 为初始化状态
		//通过点击列表，显示详细信息：包括货物包基本情况，支持跳转到货物包
		//操作 新建修改删除

		//代理商目录需要看到的是每个代理商 一共有哪些货物在仓库或者在运

		var userName = "<?php echo $_SESSION['user'];?>";
		var userID = "<?php echo $_SESSION['userID'];?>";
		

		console.log(userName);
		//addRemind("测试","这是一个输出和定时器<br>用户"+userName+"  代码"+userID,3000);
		//显示列表
		var query  = "(SELECT * ";
		query 		+= "FROM wAgents a, warePackages p, wApplications app ";
		query 		+= "WHERE a.waID=p.wpAgentID AND p.wpID=app.wpID GROUP by app.InstockID )";
		query 		+= " ORDER by p.wpAgentID;"	
		$.ajax({ 
	    type : "post", 
	    url : "_search.php?query="+query,
	    async : false, 
	    success : function(data){
	    	//console.log(data);
				var head = new Array("货代名称","货单内容","联系方式/人电话地址","进仓编号");
				var attr = new Array("waName","appName","waContact","InStockID");
				var obj = jQuery.parseJSON(data);
				var link = "InStockID";
				//console.log(obj);
				$('#agentBoxContainerRelated').html( FormPanelTable(obj,attr,head,link,"代理商列表"));
	    }
	  });
	  //显示无app或wp绑定列表
	  var query  = "SELECT * FROM wAgents a ";
		query 		+= "WHERE NOT EXISTS ( SELECT wpAgentID FROM warePackages WHERE wpAgentID=a.waID) ";
		//query 		+= "ORDER by a.waID;"	
		$.ajax({ 
			type : "post", 
			url : "_search.php?query="+query,
			async : false, 
			success : function(data){
				//console.log(data);
				var head = new Array("货代名称","联系方式/人电话地址");
				var attr = new Array("waName","waContact");
				var obj = jQuery.parseJSON(data);
				var link = "InStockID";
				//console.log(obj);
				$('#agentBoxContainerUnRelated').html( FormPanelTable(obj,attr,head,link,"未启用代理商"));
			}
		});
					//$.post("_search.php?query="+query,function(data){
			
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
				$('#editAgent').show();
				$('#agentInStock').hide();
				console.log($(this).attr('data'));
				$.post("_search.php?table=wAgents&attr=waID&val="+$(this).attr('data'),function(data){
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
				});
			});
			//新增货代按钮
			$('#createAgentBtn').on("click",function(){
				$('#editAgent').show();
				$('#agentInStock').hide();
				$("#updateAgentBtn").hide();
				$("#deleteAgentBtn").hide();
				$("#newAgentBtn").show();
			});
			//取消操作按钮，
			$('#cancelBtn').on('click',function(){
				$('#editAgent').hide();
			});
			//操作提示按钮
			$('#agentOpHint').on("click",function(){
				var title="代理商操作提示";
				var hint="<ol>";
				hint += "<li>点击新增货代，输入货代信息，点击新增增加一个货物代理商人</li>";
				hint += "<li>点击某个货代名称，修改信息再点击更新保存修改的信息</li>";
				hint += "<li>点击某个货代名称，进行删除操作</li>";
				hint += "<li>点击某个进仓编号，查看进仓编号下的货物情况</li>";
				addRemind(title,hint,5000);
			});
			//新建按钮
			$('#newAgentBtn').on("click",function(data){
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
			});
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
			$("#updateAgentBtn").on("click",function(data){
					console.log("更新"+$("#waID").val()+"货代");
					$.get("phpUpdate.php?table=wAgents&&idAttr=waID&&idValue="+$("#waID").val()+"&&tAttr=waName&&tValue="+$("#waName").val());
					$.get("phpUpdate.php?table=wAgents&&idAttr=waID&&idValue="+$("#waID").val()+"&&tAttr=waType&&tValue="+$("#waType").val());
					$.get("phpUpdate.php?table=wAgents&&idAttr=waID&&idValue="+$("#waID").val()+"&&tAttr=waAddr&&tValue="+$("#waAddr").val());
					$.get("phpUpdate.php?table=wAgents&&idAttr=waID&&idValue="+$("#waID").val()+"&&tAttr=waContact&&tValue="+$("#waContact").val());
					$.get("phpUpdate.php?table=wAgents&&idAttr=waID&&idValue="+$("#waID").val()+"&&tAttr=waTel&&tValue="+$("#waTel").val());
					$.get("phpUpdate.php?table=wAgents&&idAttr=waID&&idValue="+$("#waID").val()+"&&tAttr=waNote&&tValue="+$("#waNote").val());
					addAgentMemo("edit");
					//需要userID，actType actTime,#inStockID,#trayID,#trayID,#slotID,actContent
					//有userID actType="更新货代",actTime = date('Y-m-d H:i:s')
					/*
					var output = "userID"+userID+"<br>";
					output += "actTime使用php时间"+"<br>";
					output += "actType更新货代<br>";
					output += $("#waID").val()+$("#waName").val()+ $("#waType").val()+$("#waAddr").val()+$("#waContact").val()+$("#waTel").val()+$("#waNote").val();
					outputHint("测试备注，之后删除",output,3000);
					*/
					

					
			});
			$('input').on("blur",function(){
					//console.log("从input退出");
					$(this).parent().addClass("has-warning");
			});
			$('#deleteAgentBtn').on("click",function(data){
				console.log("暂时不提供此功能");
				addRemind("删除货代","暂时不提供删除功能",3000,"bs-callout-danger");
			});
			//这里需要根据编码返回1，所有编码下的入库出库匹配的货物名称，数量。
			//2，未完成的出库的详细连tray的信息
			$('.agentLoad').on('click',function(data){
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
					console.log(obj);
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
					console.log(obj);


					var link = "appID";
					var head = new Array("appID","是否完成","进仓编号","名称","托盘","app出入","托盘状态","托盘内货物","更新时间");
					var attr = new Array("appID","appComplete","InStockID","appName","wtID","appType","twStatus","twWareCount","UpdateTime");
					$('#agentInStock').append(FormPanelTable(obj,attr,head,link,"库内货物"));

					$('.appOpPanelDetail').on("click",function(){
						console.log("查找货单"+$(this).attr('id'));
						//库单id= $(this).attr('id')
						//显示详细情况div = #appDetailBox
						//console.log( "appDetail.php?appID="+ $(this).attr('id') );
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
			for(var i in obj){
				output += "<tr class=\"agentRow\">";
				for(var j in attr){
					//如果打印appID的链接
					if(attr[j] == "appID"){
						output += "<td><a  class=\"appOpPanelDetail btn btn-default btn-xs\" data=\""+obj[i][attr[j]]+"\">"+obj[i][attr[j]]+"</a></td>";
					}
					//加跳转
					else if(link == attr[j]){
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
						output += "<td><a class=\"agentIDLoad btn btn-default btn-xs\" data=\""+obj[i]['waID']+"\">"+obj[i]["waName"]+"</a></td>";
					}
					else{
						output += "<td>"+obj[i][attr[j]]+"</td>";
					}

				}
				output += "</tr>";
				previousAg = obj[i]['waName'];
				//console.log(previousAg);
			}
			output 	 += "</tbody>";
			output 	 += "</table>";
			output 	 += "</div>";
			return output;
		}

		


		
	</script>
</html>