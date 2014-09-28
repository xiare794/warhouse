<!DOCTYPE html>
<html lang="zh-cn">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="WSLayout">
		<meta name="author" content="Ref">


		<title>代理商</title>
		<!-- Bootstrap core CSS -->
		<link href="dist/css/bootstrap.css" rel="stylesheet">
	</head>
	<body id="agentBody">
		<!-- agent container -->
		<div class="container">
			<div class="col-lg-9">
				<div class="panel panel-default" id="agentBox">
				</div>
				
				<div class="panel panel-default" id="tt" >
				</div>
			</div>	
		</div>
	</body>
	
	<!-- Bootstrap core js -->
	<script src="dist/js/jquery-1.11.1.js"></script>
  <script src="dist/js/bootstrap.min.js"></script>

  <script>
  	//agent的三个需求
  	//显示列表 为初始化状态
		//通过点击列表，显示详细信息：包括货物包基本情况，支持跳转到货物包
		//操作 新建修改删除

		//代理商目录需要看到的是每个代理商 一共有哪些货物在仓库或者在运
		//agent.id = package.agentid
		//package.appid = p
		
		//显示列表
		var from = "wAgents a, warePackages p, wApplications app";
		var where = "a.waID=p.wpAgentID AND p.wpID=app.wpID GROUP by app.InstockID ORDER by p.wpAgentID";
		var string = "from="+from+"&where="+where;		
		$.post("../_search.php?"+string,function(data){
			//console.log(data);
			var head = new Array("货代名称","货单内容","联系方式/人电话地址","代码");
			var attr = new Array("waName","appName","waContact","InStockID");
			var obj = jQuery.parseJSON(data);
			var link = "InStockID";
			console.log(obj);
			$('#agentBox').html( FormPanelTable(obj,attr,head,link,"代理商列表"));

			//这里需要根据编码返回1，所有编码下的入库出库匹配的货物名称，数量。
			//2，未完成的出库的详细连tray的信息
			$('#agentBox .agentLoad').on('click',function(data){
				console.log($(this).attr('data'));
				
				//这里需要的是所有已完成出入库的为一组
				var query = "SELECT app1.InStockID, app1.appName, app1.appID appIn, app2.appID appOut, app1.appCount,app1.appCount inCount, app1.appBookingDate inTime, app2.appBookingDate outTime, app2.appCount outCount ";
				query 		+="FROM wApplications app1, wApplications app2 ";
				query 		+="WHERE app1.appType=\"in\" AND app2.appType=\"out\" AND app1.appComplete=1 AND app2.appComplete=1 AND app1.InstockID=app2.InstockID AND app1.InstockID=\""+$(this).attr('data')+"\"";
				
				$.post("../_search.php?query="+query,function(data){
					//console.log(data);
					var obj = jQuery.parseJSON(data);
					console.log(obj);
					var head = new Array("货物代码","名称","入库单","入库数量","出库单","出库数量","出库时间");
					var attr = new Array("InStockID","appName","appIn","inCount","appOut","outCount","outTime");
					$('#tt').html(FormPanelTable(obj,attr,head,null,"已完成出库货物列表"));
				});
				//未完成出入库的为一组
				var query = "SELECT *";
				query 		+="FROM wApplications app, wTrays t ";
				query 		+="WHERE (t.wtAppID=app.appID OR t.wtAppOutID=app.appID) AND app.InstockID=\""+$(this).attr('data')+"\" "; 
				console.log(query);
				$.post("_search.php?query="+query,function(data){
					console.log(data);
					var obj = jQuery.parseJSON(data);
					console.log(obj);


					var link = "appID";
					var head = new Array("appID","是否完成","货物代码","名称","托盘","app出入","托盘状态","托盘内货物","更新时间");
					var attr = new Array("appID","appComplete","InStockID","appName","wtID","appType","twStatus","twWareCount","UpdateTime");
					$('#tt').append(FormPanelTable(obj,attr,head,link,"库内货物"));
				});
			});

		});
		
		//生成表格形式 返回字符串
		function FormPanelTable(obj,attr,head,link,title){
			//console.log(obj);
			var output = "<div class=\"panel-heading\" \>"+title+"</div>";
			output 	+= "<div class=\"panel-body\">";

			output 	+= "<table class=\"table table-condensed table-hover\" style=\"font-size:10px;\"";
			output 	+= "<thead>";
			for(var i in head){
				output += "<th>"+head[i]+"</th>";
			}
			output 	+= "</thead>";
			output 	+= "<tbody>";

			var previousAg = "";
			for(var i in obj){
				output += "<tr>";
				for(var j in attr){
					//加跳转
					if(link == attr[j]){
						//console.log("相同");
						output += "<td><a target=\"#tt\" class=\"agentLoad btn btn-default btn-xs\" data=\""+obj[i][attr[j]]+"\">"+obj[i][attr[j]]+"</a></td>";
					}
					//省略货代名称和联系
					else if("waName" == attr[j] && obj[i][attr[j]]==previousAg){
						//console.log("get hree 达到");
						output += "<td></td>";
					}
					else{
						output += "<td>"+obj[i][attr[j]]+"</td>";
					}

				}
				output += "</tr>";
				previousAg = obj[i]['waName'];
				console.log(previousAg);
			}
			output 	 += "</tbody>";
			output 	 += "</table>";
			output 	 += "</div>";
			return output;
		}


		
	</script>
</html>