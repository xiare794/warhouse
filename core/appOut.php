
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


		<title>出库单</title>

		
		<link href="../css/docs.min.css" rel="stylesheet">
		<style>
			.appOutInit{
				background-color: rgba(0,0,0,0.1);
			}
			.appOutProcessing{
				background-color: rgba(0,50,30,0.2);
			}
			.appOutCentain{
				background-color: rgba(0,0,30,0.2);
			}
			.appOutDone{
				background-color: rgba(0,125,30,0.2);
			}
		</style>
	</head>
	<body >
		<!-- agent container -->
			<div id="appOutBody"  >

					<div class="navbar navbar-default" role="navigation">
						<div class="container-fluid">
							<div class="navbar-header">
								<a class="navbar-brand" href="#">出库单</a>
							</div>
							<div class="navbar-form navbar-left" role="search">
				        <div class="form-group">
				          <input type="search" id="agentSearchInput" class="form-control" placeholder="请输入箱号">
				        </div>
				        
						  </div>
						  <label class="radio-inline">
							  <input type="radio" name="compRadio" id="outputComp" value="COMPLETE"> 已完成
							</label>
					    <label class="radio-inline">
							  <input type="radio" name="compRadio" id="outputComp" value="UNCOMPLETE"> 未完成
							</label>
				     <ul class="nav nav-pills navbar-right" role="tablist">
				      	<li><a class="navLink" id="refreshList" href="#" onclick="refreshListOutApp()" ><span class="glyphicon glyphicon-list"></span></a></li>
								<li><a class="navLink" id="plusContainer" href="#" ><span class="glyphicon glyphicon-plus"></span></a></li>
				     </ul>
							
						</div>
					</div>

				
				<div class="panel panel-default scrolls-both" id="appOutDiv">
					
					<div class="row">
						<div id="mainAppOut" class= "col-lg-4 col-md-4 col-sm-4" style="font-size:50%" >
							筛选操作 或添加
						</div>
						<div id="viceAppOut" class= "col-lg-8 col-md-8 col-sm-8">
						</div>
					</div>
					
				</div>

			</div>

		
	</body>
	<script type="text/javascript">
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
		//新出库单生成
		function newOut_onsubmit(){
			var viceHint = "生成";
			viceHint +="表单提供Type,Series,AgentID,ContainerID,OpInput/n";
			viceHint +="补充createTime,/n";
			viceHint += "count,volume每次添加货品后更新";
			

			//时间
			var time = new Date().Format("yyyy-MM-dd hh:mm:ss"); 
			viceHint += time;
			$("#viceAppOut").html(viceHint);

			var postStr = "";
			$("#appOutDiv select").each(function(){
				postStr   += $(this).attr("name")+"="+$(this).val()+"&";
			});
			$("#appOutDiv input").each(function(){
				postStr   += $(this).attr("name")+"="+$(this).val()+"&";
			});
			postStr   += "createTime="+time+"&";

			var valid = true;
			if(valid){
				//增加集装箱
				$.ajax({
				    type : "POST", 
				    url : "_insert.php?table=wAppOut",
				    data: postStr,
				    async : false, 
				    success : function(data){
				    	//console.log(data);
				    	if(data[0]="1"){
				    		addRemind("成功添加一集装箱",postStr,5000,"bs-callout-info");
				    		$("#mainContainer").html("成功添加");
				    	}
				    	
			    	}
			  });

			}
		}
		
		//刷新出库表
		function refreshListOutApp(){
			var htmlStr = "<div class=\"list-group\" font-size=\"14p\">";
			//1获取参数
			//目前无参数
			console.log($("#agentSearchInput").val());
			var sStr = $("#agentSearchInput").val();



			//2查询数据库
			var query = "query=SELECT * FROM `wAppOut` app, `wContainers` con WHERE app.containerID = con.wCID AND `wCStatus`<4";

			if(sStr == ""){
				;
			}
			else{
				query += " AND `wCSeries` like \"%"+sStr+"%\"";
				//`appBookingDate`like \"%"+datestr+"%\""
			}

			$.ajax({
			    type : "GET", 
			    url : "_search.php?"+query,
			    async : false, 
			    success : function(data){
			    	//console.log(data);
			    	var obj = jQuery.parseJSON(data);
			    	console.log(obj)
			    	var extra = "";
			    	for(i in obj ){
			    		extra = "";
			    		if(obj[i]["appStatus"] == "0")
			    			extra = "appOutInit";
			    		else if(obj[i]["appStatus"] == "1")
			    			extra = "appOutCentain";
			    		else if(obj[i]["appStatus"] == "2" )
			    			eatra = "appOutDone"

			    		//htmlStr += obj[i]["wCID"] + " | "+obj[i]["wCSeries"] + " | "+obj[i]["wCTiDan"] + " | "+obj[i]["wCTypeID"] + " | "+obj[i]["wCSeal"]+ "<br>";
			    		htmlStr += "<a href=\"#\" class=\"list-group-item "+extra+"\" outAppID=\""+obj[i]["wAppID"]+"\"  containerID=\""+obj[i]["containerID"]+"\" appStatus=\""+obj[i]["appStatus"]+"\">";
			    		htmlStr += "<p class=\"list-group-item-heading\" style=\"font-size:12px\">出库流水["+obj[i]["series"]+"]";
			    		

			    		htmlStr += "箱号:["+obj[i]["wCSeries"]+"]</p>";
			    		htmlStr += "<p class=\"list-group-item-text\" style=\"font-size:10px\" >";
			    		if(obj[i]["Type"] == 1)
			    			htmlStr += "装箱出库<br>";
			    		htmlStr += "状态:"+obj[i]["wCStatus"]+ "<br>";
			    		htmlStr += "箱号:"+obj[i]["wCSeries"] + "<br>";
			    		htmlStr += "提单:"+obj[i]["wCTiDan"] +"<br>";
			    		htmlStr += "箱型:" +obj[i]["wCType"] + "<br>";
			    		htmlStr += "封号:"+obj[i]["wCSeal"]+"</p></a>";

			    	}
			    	//3添加进目前页面
			    	htmlStr += "</div>";
			    	$("#mainAppOut").html(htmlStr);
			    	
		    	}
		  });
			
			//mainAppOut
			//4增加响应
			$("#mainAppOut .list-group-item").on("click",function(){
				//console.log("原来的属性"+$(this).hasClass());

				$("#mainAppOut .list-group-item").removeClass("active");
				$(this).addClass("active");

				//查找属于箱子containerID的
				//组成列表，填补
				var div = "viceAppOut";
				var ViceInner = "<div id=\"vList\" class=\"bs-example\"></div><div id=\"vOperate\" class=\"highlight\"></div>"
				$("#"+div).html(ViceInner); //清空右侧表
				listWare();
				console.log($(this));

				//根据目前出库单状态刷新
				var outAppStatus = $("#mainAppOut .list-group-item.active").attr("appStatus");
				if(outAppStatus == "0")
					addOpForAppOut();
				if(outAppStatus == "1"){
					OpingAppOut();
				}
			});
		}

		//确定位置方法扩展
		Array.prototype.indexOf = function(e){
		  for(var i=0,j; j=this[i]; i++){
		    if(j==e){return i;}
		  }
		  return -1;
		}

		function listWare(){
			console.log("listWare function ");
			var outAppID = $("#mainAppOut .list-group-item.active").attr("outAppID");
			var query = "query=SELECT u.*, t.rfid, t.wtID FROM `wTrays` t, `wUnit` u   WHERE t.wtAppOutID ="+outAppID+" AND t.twWareCount!=0 AND t.wtID = u.trayID";
			var div = "vList";
			$.ajax({
			    type : "GET", 
			    url : "_search.php?"+query,
			    async : false, 
			    success : function(data){
			    	//console.log(data);
			    	var obj = jQuery.parseJSON(data);
			    	if(obj.length == 0){
			    		console.log("为空");
			    		$("#"+div).append("<p>请添加装箱货物</p>");
			    	}
			    	else{
			    		//如果不空，要添加货物列表，以什么形式呢
			    		$("#"+div).html("");

			    		var li ="<table class=\"table\" style=\"font-size:50%\">";
			    		var total = {
			    			'volume':0,
			    			'traysCount':0,
			    			'count':0
			    		};
			    		var appIDList = new Array();

			    		li += "<tr><th>送货公司</th><th>名称</th><th>体积(m3)</th><th>托数</th><th>箱数</th><th>操作</th></tr>";
			    		$("#"+div).html(li);
			    		li = "";
			    		for( var i in obj){
			    			var vol = ( (obj[i]['width']*obj[i]['length']*obj[i]["height"])/1000000 ).toFixed(2);
			    			//console.log(vol);
			    			//vol = vol.toFixed(2);
			    			var maitou ="未备注";
			    			var deliverComp = "未填写";
			    			var appSeries = "未备注";

			    			if(appIDList.indexOf(obj[i]["appID"])==-1){
			    				appIDList.push(obj[i]["appID"]);
			    				
			    				$.ajax({
			    					type : "GET", 
								    url : "_search.php?query=SELECT `appSeries`, `appMaitou`, `deliverComp` FROM wAppIn WHERE `appID`="+obj[i]["appID"],
								    async : false,
								    success : function(data){
								    	var obj = jQuery.parseJSON(data);
								    	deliverComp = obj[0]["deliverComp"];
									  	console.log(obj);
								    }
			    				});
			    				
			    				//未找到，新建一行
			    				li+= "<tr data=\""+obj[i]["appID"]+"\"><td>"+deliverComp+"</td><td>"+obj[i]["wiName"]+"</td><td>"+vol+"</td><td>1</td><td>"+obj[i]["count"]+"</td>";
			    				li+= "<td><button type=\"button\" class=\"btn btn-default btn-xs\" val=\""+obj[i]["appID"]+"\">去掉</button></td></tr>"
			    				$("#"+div+" table tbody").append(li);
			    				li ="";
			    			}
			    			else{
			    				//找到，修改对应项
			    				//console.log($("#"+div+" tr"));
			    				var tr = $("#"+div+" tr[data|="+obj[i]["appID"]+"]" );
			    				//增加箱数
			    				tr.find("td").eq(4).append("/"+obj[i]["count"]);
			    				//增加托数
			    				var number = tr.find("td").eq(3).html();
			    				number++;
			    				tr.find("td").eq(3).html(number);
					    		//增加体积
					    		var addVol = Number(tr.find("td").eq(2).html());
					    		//console.log()
					    		//console.log("新增体积为"+vol);
					    		//console.log("原本体积"+tr.find("td").eq(2).html());
					    		//console.log(obj[i]);
					    		tr.find("td").eq(2).html( (Number(addVol)+Number(vol)).toFixed(2));


			    			}
			    			total.volume += Number(vol);
			    			total.traysCount++;
			    			//total.traysCount += Number(obj[i]["weight"]);
			    			total.count += Number(obj[i]["count"]);
			    		}
			    		//加合计这行
			    		li+= "<tr><th>合计</th><td></td><td>"+total.volume.toFixed(2)+"</td><td>"+total.traysCount+"</td><td>"+total.count+"</td></tr>";
			    			
			    		//li += "</table>";
			    		$("#"+div+" table").append(li);

			    		$("#"+div+" button").on("click",function(){
			    			var appID = $(this).attr("val");
			    			//console.log("wtID:"+wtID);
			    			var query = "table=wTrays&&idAttr=wtAppID&&idValue="+appID+"&&tAttr=wtAppOutID&&tValue=";
						  	//更新托盘出库单号 ajax实时更改托盘对应的出库
						  	$.ajax({
							    type : "GET", 
							    url : "phpUpdate.php?"+query,
							    async : false, 
							    success : function(data){
							    	console.log("删除"+data);
							    }
							});
					  		reloadAvailableWare();
					  		listWare();
			    		});

			    	}
			    	
		    	}
		  });


		}
		function addOpForAppOut(){
			
			

			//列表下面是添加表单，根据搜索
			var filterQuery ="";
			var viceHtml = "";
			var div = "vOperate";
			//增加添加表格
			//viceHtml += "<table id=\"outTable\" class=\"table\"> </table>";
			viceHtml += "<span>选择出库货物,全部选择完成后，点选出库</span>"
			viceHtml += "<div class=\"form-inline\">"
				viceHtml += "<div class=\"input-group\" style=\"margin-bottom: 10px\">";
		  		viceHtml += "<div class=\"input-group-addon\">进仓编号(送货公司)</div>";
		  		viceHtml += "<input id=\"outputPickFilter\" class=\"form-control\" placeholder=\"进仓编号或者送货公司\" />";
		  	viceHtml += "</div>";
	  		viceHtml += "<div class=\"input-group\" style=\"margin-bottom: 10px\">";
		  		viceHtml += "<div class=\"input-group-addon\">货物</div>";
		  		viceHtml += "<select id=\"outputPickUint\" class=\"form-control\" />";
		  		viceHtml += "<div class=\"input-group-addon\" style=\"padding:2px 10px\"><button type=\"button\" class=\"btn btn-success btn-xs\" onclick=\"addInOutList()\">添加选中</button></div>";
	  		viceHtml += "</div>";
	  		viceHtml += "<div class=\"input-group\" style=\"margin-bottom: 10px\">";
	  			viceHtml += "<label for=\"comment\" class=\"col-sm-2 control-label\">备注</label>";
	  			viceHtml += "<textarea id=\"comment\" class=\"form-control\" rows=\"3\" style=\"width:400px\"></textarea>";
	  		viceHtml += "</div>";

	  		viceHtml += "<div class=\"input-group\" style=\"margin-bottom: 10px\">";
	  			viceHtml += "<button type=\"button\" class=\"btn btn-success\" onclick=\"ConfirmAppOut()\">确定出库</button>";
	  		viceHtml += "</div>";
  			
	  		viceHtml += "</div>";

	  		$("#"+div).html(viceHtml);
	  		reloadAvailableWare();
			
			$("#outputPickFilter").on("blur",function(){
				reloadAvailableWare();
			});

		}

		function OpingAppOut(){
			//出库单执行中
			var outAppID = $("#mainAppOut .list-group-item.active").attr("outAppID");
			var query = "query=SELECT t.*,app.*,appIn.appMaitou FROM `wTrays` t , `wAppOut` app, `wAppIn` appIn  WHERE ( app.wAppID=\""+outAppID+"\" AND app.wAppID=t.wtAppOutID  AND t.wtAppID=appIn.appID)";			//ajax重新加载可用货物
			$.ajax({
			    type : "GET", 
			    url : "_search.php?"+query,
			    async : false, 
			    success : function(data){
			    	//console.log(data);
			    	var obj = jQuery.parseJSON(data);
			    	console.log(obj);
			    	var viceHtml = "<p>等待进箱托盘</p>";
			    	viceHtml += "<table class=\"table\" style=\"font-size:50%\">";
			    	viceHtml += "<tr><th>箱编号</th><th>托盘号</th><th>托盘位置</th><th>唛头</th><th>托盘仓位</th><th>托盘内箱数</th><th>操作</th></tr>";
					var div = "vOperate";
					var total = {
		    			'volume':0,
		    			'traysCount':0,
		    			'count':0
		    		};
		    		var tr = "";
			    	for(i in obj ){
			    		tr += "<tr>";
			    		tr += "<td>"+obj[i]["containerID"]+"</td><td>"+obj[i]["wtID"]+"</td><td>"+obj[i]["twStatus"]+"</td><td>"+obj[i]["appMaitou"]+"</td><td>"+obj[i]["wSlotID"]+"</td><td>"+obj[i]["twWareCount"]+"</td><td></td></tr>";
			    	//	ops += "<option appID=\""+obj[i]["appID"]+"\" >"+obj[i]["appName"]+"|"+obj[i]["appPreCount"]+"箱|"+obj[i]["InStockID"]+" | "+obj[i]["deliverComp"]+"</option>";
			    	}
			    	viceHtml += tr;
			    	viceHtml += "</table>";
			    	$("#"+div).html(viceHtml);


			    	var query = "query=SELECT u.*,appIn.* FROM `wUnit` u , `wAppIn` appIn WHERE ( u.appOutID=\""+outAppID+"\" AND u.appID=appIn.appID )";			//ajax重新加载可用货物
					$.ajax({
					    type : "GET", 
					    url : "_search.php?"+query,
					    async : false, 
					    success : function(data){
					    	//console.log(data);
					    	var obj = jQuery.parseJSON(data);
					    	console.log(obj);
					    	var viceHtml = "<p>已进箱货物</p>";
					    	viceHtml += "<table class=\"table\" style=\"font-size:50%\">";
					    	viceHtml += "<tr><th>货代号</th><th>进仓编号</th><th>进仓作业号</th><th>送货公司</th><th>唛头</th><th>货型</th><th>件数</th><th>操作</th></tr>";
							var div = "vOperate";
							var tr = "";
							for(i in obj ){
					    		tr += "<tr>";
					    		tr += "<td>"+obj[i]["agentID"]+"</td><td>"+obj[i]["InStockID"]+"</td><td>"+obj[i]["appSeries"]+"</td><td>"+obj[i]["deliverComp"]+"</td><td>"+obj[i]["appMaitou"]+"</td><td>"+obj[i]["itemType"]+"</td><td>"+obj[i]["count"]+"/"+obj[i]["appCount"]+"</td><td></td></tr>";
					    	}
					    	viceHtml += tr+"</table>";
					    	$("#"+div).append(viceHtml);
					    }
					});
			    	
			    }
			});
				var div = "vOperate";
			    viceHtml = "<div><a id=\"completeAppOutBtn\" onclick=\"completeAppOutBtnOnClick()\"  class=\"btn-embossed btn btn-primary form-control\" style=\"width:40%\" disabled>出箱完成</a></div>";
			    $("#"+div).append(viceHtml);

			    //viceHtml += "<div><B</div>";
			    
			//});
			

			//解除绑定，给unit添加appOutID项，
			

			    	//3添加进目前页面
			    	
			    	//$("#viceAppOut #outputPickUint").append(ops);
			    	/*
			    	var li ="<table class=\"table\" style=\"font-size:50%\">";
		    		var total = {
		    			'volume':0,
		    			'traysCount':0,
		    			'count':0
		    		};
		    		var appIDList = new Array();

			    	li += "<tr><th>箱编号</th><th>托盘号</th><th>托盘位置</th><th>托盘仓位</th><th>托盘内箱数</th><th>操作</th></tr>";
		    		$("#"+div).html(li);
		    		li = "";
		    		for( var i in obj){
		    			var vol = ( (obj[i]['width']*obj[i]['length']*obj[i]["height"])/1000000 ).toFixed(2);
		    			//console.log(vol);
		    			//vol = vol.toFixed(2);
		    			var maitou ="未备注";
		    			var deliverComp = "未填写";
		    			var appSeries = "未备注";

		    			if(appIDList.indexOf(obj[i]["appID"])==-1){
		    				appIDList.push(obj[i]["appID"]);
		    				
		    				$.ajax({
		    					type : "GET", 
							    url : "_search.php?query=SELECT `appSeries`, `appMaitou`, `deliverComp` FROM wAppIn WHERE `appID`="+obj[i]["appID"],
							    async : false,
							    success : function(data){
							    	var obj = jQuery.parseJSON(data);
							    	deliverComp = obj[0]["deliverComp"];
								  	console.log(obj);
							    }
		    				});
		    				
		    				//未找到，新建一行
		    				li+= "<tr data=\""+obj[i]["appID"]+"\"><td>"+deliverComp+"</td><td>"+obj[i]["wiName"]+"</td><td>"+vol+"</td><td>1</td><td>"+obj[i]["count"]+"</td>";
		    				li+= "<td><button type=\"button\" class=\"btn btn-default btn-xs\" val=\""+obj[i]["appID"]+"\">去掉</button></td></tr>"
		    				$("#"+div+" table tbody").append(li);
		    				li ="";
		    			}
		    			else{
		    				//找到，修改对应项
		    				//console.log($("#"+div+" tr"));
		    				var tr = $("#"+div+" tr[data|="+obj[i]["appID"]+"]" );
		    				//增加箱数
		    				tr.find("td").eq(4).append("/"+obj[i]["count"]);
		    				//增加托数
		    				var number = tr.find("td").eq(3).html();
		    				number++;
		    				tr.find("td").eq(3).html(number);
				    		//增加体积
				    		var addVol = Number(tr.find("td").eq(2).html());
				    		//console.log()
				    		//console.log("新增体积为"+vol);
				    		//console.log("原本体积"+tr.find("td").eq(2).html());
				    		//console.log(obj[i]);
				    		tr.find("td").eq(2).html( (Number(addVol)+Number(vol)).toFixed(2));


		    			}
		    			total.volume += Number(vol);
		    			total.traysCount++;
		    			//total.traysCount += Number(obj[i]["weight"]);
		    			total.count += Number(obj[i]["count"]);
		    		}
		    		//加合计这行
		    		li+= "<tr><th>合计</th><td></td><td>"+total.volume.toFixed(2)+"</td><td>"+total.traysCount+"</td><td>"+total.count+"</td></tr>";
		    			
		    		//li += "</table>";
		    		$("#"+div+" table").append(li);*/
			    	
		    //	}
		  //});
		}


		function reloadAvailableWare(){
			console.log("重新加载可选货物");
			$("#viceAppOut #outputPickUint").html("");
			//var unitQuery = "query=SELECT * FROM `wTrays` t , `wAppIn` app  WHERE ( app.appID=t.wtAppID AND t.twWareCount!=0 AND t.wtAppOutID ='' )";
			var unitQuery = "query=SELECT * FROM `wAppIn` app  WHERE ( app.appStatus = 3 )";
			
			//添加筛选项
			var key = $("#outputPickFilter").val();
			var addtional = "";
			if(key!=""){
				addtional = " AND (app.InStockID LIKE '%"+key+"%' OR app.deliverComp LIKE '%"+key+"%')";
			}
			console.log(unitQuery+addtional);

			//ajax重新加载可用货物
			$.ajax({
			    type : "GET", 
			    url : "_search.php?"+unitQuery+addtional+" LIMIT 0 , 30",
			    async : false, 
			    success : function(data){
			    	//console.log(data);
			    	var ops = "";
			    	var obj = jQuery.parseJSON(data);
			    	console.log(obj);
			    	
			    	for(i in obj ){
			    		ops += "<option appID=\""+obj[i]["appID"]+"\" >"+obj[i]["appName"]+"|"+obj[i]["appPreCount"]+"箱|"+obj[i]["InStockID"]+" | "+obj[i]["deliverComp"]+"</option>";
			    	}
			    	//3添加进目前页面
			    	
			    	$("#viceAppOut #outputPickUint").append(ops);
			    	
		    	}
		  });
		}

		function addInOutList(){

	  	//托盘ID
	  	var appID = $("#outputPickUint option:selected").attr("appID");
	  	var outAppID = $("#mainAppOut .list-group-item.active").attr("outAppID");
	  	//给托盘添加outAPPID;
	  	var query = "table=wTrays&&idAttr=wtAppID&&idValue="+appID+"&&tAttr=wtAppOutID&&tValue="+outAppID;
	  	console.log(query);
	  	//更新托盘出库单号 ajax实时更改托盘对应的出库
	  	$.ajax({
		    type : "GET", 
		    url : "phpUpdate.php?"+query,
		    async : false, 
		    success : function(data){
		    	console.log("添加结果"+data);
		    }
		  });
	  	//给入库单进行预约出库
	  	var query = "table=wAppIn&&idAttr=appID&&idValue="+appID+"&&tAttr=appStatus&&tValue=4";
	  	console.log(query);
	  	//更新托盘出库单号 ajax实时更改托盘对应的出库
	  	$.ajax({
		    type : "GET", 
		    url : "phpUpdate.php?"+query,
		    async : false, 
		    success : function(data){
		    	console.log("添加结果"+data);
		    }
		  });

	  	//查找所有托盘，看其appID，如果
	  	//ref

	  	var wtAppID_query = "SELECT `wtAppID` FROM `wTrays` WHERE `wtAppOutID` = \""+outAppID+"\"";
	  	var ap_Str = "";
			$.ajax({
			    url : "_search.php?query="+wtAppID_query,
			    type: "get",
			    async : false, 
			    success : function(data){
			    	//console.log(data);
			    	var obj = jQuery.parseJSON(data);
			    	for( var i=0; i<obj.length; i++){
			    		//console.log(obj[i]['waName']);
			    		ap_Str += obj[i]["wtAppID"];//修改
			    		ap_Str += i<(obj.length-1)?":":"";
			    		//agents[i] = {id:obj[i]["waID"],name:obj[i]["waName"],CODE:obj[i]["CODE"]};
			    	}
						console.log("一共牵连"+ap_Str);
		    	}
		  });

		  if(ap_Str.length>0){
				//更新appOut的appIn列表
		  	var query = "table=wAppOut&&idAttr=wAppID&&idValue="+outAppID+"&&tAttr=appIns&&tValue="+ap_Str;
		  	console.log(query);
		  	//更新托盘出库单号 ajax实时更改托盘对应的出库
		  	$.ajax({
			    type : "GET", 
			    url : "phpUpdate.php?"+query,
			    async : false, 
			    success : function(data){

			    	console.log("添加出库单的入库"+data);
			    }
			  });
		  }


  		reloadAvailableWare();
  		listWare();
	  }

	  function ConfirmAppOut(){
	  	//托盘ID
	  	//var trayID = $("#outputPickUint option:selected").attr("val");
	  	var outAppID = $("#mainAppOut .list-group-item.active").attr("outAppID");
	  	var query = "table=wAppOut&&idAttr=wAppID&&idValue="+outAppID+"&&tAttr=appStatus&&tValue=1";
	  	//更新托盘出库单号 ajax实时更改托盘对应的出库
	  	$.ajax({
			    type : "GET", 
			    url : "phpUpdate.php?"+query,
			    async : false, 
			    success : function(data){
			    	console.log("添加结果"+data);
			    	addRemind("出库已确认","出库已确认，等待理货员整理出库",5000,"bs-callout-info");
			    }
			});



  		reloadAvailableWare();
  		listWare();

	  }


		$("#plusContainer").on("click",function(){
			console.log("开始plusContainer");
			//显示新建集装箱
			var attrs = new Array();
			attrs[0] = {id:"Type",type:"select",label:"出库类型",placeholder:"1",inputStyle:""};
			attrs[1] = {id:"Series",type:"text",label:"水单号",placeholder:"2014111001",inputStyle:"readonly"};
			attrs[2] = {id:"AgentID",type:"select",label:"货代",placeholder:"Container SEAL Number",inputStyle:""};
			attrs[3] = {id:"ContainerID",type:"select",label:"集装箱号",placeholder:"先创建集装箱",inputStyle:""};
			attrs[4] = {id:"OpInput",type:"text",label:"输单",placeholder:"",inputStyle:"readonly"};
			
			var str = "<form class=\"form\" role=\"form\" id=\"CForm\">";
			str += 		"<div class=\"row\">";
			str += 		"	<div id=\"mainAppOut\" class= \"col-lg-6 col-md-6 col-sm-6\" >";
			for(var i in attrs){
				str += addInput(attrs[i]);
			}

			str += "<a id=\"newOutputBtn\" onclick=\"newOut_onsubmit()\"  class=\"btn-embossed btn btn-primary form-control\" style=\"width:40%\">新建</a>";

			str += "</div>";
			str += "<div id=\"viceAppOut\" class= \"col-lg-6 col-md-6 col-sm-6\"></div></div></form>";
			//console.log(str);
			$("#appOutDiv").html(str);

			//给出库类型增加选项
			$("#Type").append("<option value=\"1\">装箱出库</option>");
			

			//给集装箱增加选项
			$("#ContainerID").append("<option value=\"0\">未选中</option>");
			for(var i in av_con){
				$("#ContainerID").append("<option value=\""+av_con[i].id+"\">箱号("+av_con[i].series+"),提单号("+av_con[i].tiDan+")</option>");
			}

			//给货代增加选项
			$("#AgentID").append("<option value=\"0\">未选中</option>");
			for(var i in agents){
				$("#AgentID").append("<option value=\""+agents[i].id+"\">公司名("+agents[i].name+"),CODE("+agents[i].CODE+")</option>");
			}
			//暂时固定出库类型
			$("#appOutDiv input#Type").val("装箱出库");

			//暂时固定出库水单号
			//获取今日已有出库+1得新出库单水单号
			$("#appOutDiv input#Series").val("20141116001");

			//固定输单人员
			$("#appOutDiv input#OpInput").val(userID);



		});
		
		function addInput(object){
			//console.log("根据js提供的Obj生成表单html");
			if(object.type == "select"){
				var html = "<label  class=\"control-label\" for=\""+object.id+"\">"+object.label+"</label>";
				html += "<select name=\""+object.id+"\" id=\""+object.id+"\" class=\"form-control\" ></select>";
			}
			else{
				var html = "<label  class=\"control-label\" for=\""+object.id+"\">"+object.label+"</label>";
				html +=  "<input  name=\""+object.id+"\" id=\""+object.id+"\" type=\""+object.type+"\" class=\"form-control\" placeholder=\""+object.placeholder+"\" "+object.inputStyle+" >";
			}
			return html;
		}


		//准备货单选择
		var agents = new Array(); 
		var agents_query = "SELECT * FROM wAgents WHERE 1";
		$.ajax({
		    url : "_search.php?query="+agents_query,
		    type: "get",
		    async : false, 
		    success : function(data){
		    	//console.log(data);
		    	var obj = jQuery.parseJSON(data);
		    	for( i in obj){
		    		//console.log(obj[i]['waName']);
		    		agents[i] = {id:obj[i]["waID"],name:obj[i]["waName"],CODE:obj[i]["CODE"]};
		    	}
					//console.log(agents);
	    	}
	  });

		//可用集装箱
		var av_con = new Array(); 
		var av_con_query = "SELECT * FROM wContainers WHERE `wCStatus` = 0";
		$.ajax({
		    url : "_search.php?query="+av_con_query,
		    type: "get",
		    async : false, 
		    success : function(data){
		    	//console.log(data);
		    	var obj = jQuery.parseJSON(data);
		    	for( i in obj){
		    		//console.log(obj[i]);
		    		av_con[i] = {id:obj[i]["wCID"],series:obj[i]["wCSeries"],tiDan:obj[i]["wCTiDan"]};
		    	}
					//console.log(av_con);
	    	}
	  });

	</script>
	
	<!-- Bootstrap core js -->
	<!--<script src="dist/js/jquery-1.11.1.js"></script>
  <script src="dist/js/bootstrap.min.js"></script> -->

</html>