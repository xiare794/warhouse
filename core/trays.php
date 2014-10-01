<!DOCTYPE html>
<html lang="zh-cn">
	<!--2014.09.24
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="WSLayout">
		<meta name="author" content="Ref">


		<title>托盘</title>
		
	</head>
	<body >-->
		<!-- agent container -->
			<div id="traysBody" class="row" key="<?php if( isset($_GET['trayKey'])) echo $_GET['trayKey'];?>">
				<div class="col-lg-6 col-md-6 col-sm-6">
					<div class="panel panel-default scrolls-both" id="traysContainer">
						
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6">
					<div class="panel panel-default scrolls-both" id="item_A_acts">
						货品和动作记录
						
					</div>
				</div>
			</div>

			
	<!--	
	</body>
	-->
	<!-- Bootstrap core js 
	<script src="dist/js/jquery-1.11.1.js"></script>
  <script src="dist/js/bootstrap.min.js"></script>-->
	
  <script>
	  //托盘有什么需求呢
	  //这里需要显示托盘的整体情况，具体托盘
		
		//显示列表
		var query  = "(SELECT * ";
		query 		+= "FROM wTrays t ";
		query 		+= "WHERE t.twStatus<>\"空闲\")";
		query 		+= " ORDER by t.UpdateTime;"	
		$.ajax({ 
	    type : "post", 
	    url : "_search.php?query="+query,
	    async : false, 
	    success : function(data){
	    	//console.log(data);
				var head = new Array("编码","表单","仓库位置","数量","规格","操作时间");
				var attr = new Array("wtID","wtAppID","wSlotID","twWareCount","twWareItemID","UpdateTime");
				var obj = jQuery.parseJSON(data);
				var link = "wSlotID";
				console.log(obj);
				$('#traysContainer').html( FormPanelTable(obj,attr,head,link,"托盘列表"));
				
				//预支筛选
				var str = $('#traySearchInput').val();
				$(".trayRow").each(function(){
					$(this).hide();
					var found = false;
					if($(this).html().match(str)){
						$(this).show();
						found = true;
					}
					if(!found) //如果没有匹配托盘，有可能此记录已出库,记录侧改为查找所有表单对应的入库编码
					{
						var query  = "SELECT * ";
						query 		+= "FROM wActions act, wApplications app ";
						query 		+= "WHERE ( act.InStockID= app.InstockID AND app.appID = "+str +")";
						$.post("_search.php?query="+query,function(data){
								var obj = jQuery.parseJSON(data);
								//console.log(obj);
								var text = "<table class=\"table table-condensed table-hover\" style=\"font-size:10px;\"　>";
								text 	+= "<thead>"+"<th>入库编码</th>"+"<th>类型</th>"+"<th>时间</th>"+"<th>内容</th>"+"</thead>";
								text 	+= "<tbody>";
								
								for(var i = 0; i<obj.length; i++){
									text += "<tr><td>"+obj[i].InStockID+"</td><td>"+obj[i].actType+"</td><td>"+obj[i].actTime+"</td><td>"+obj[i].actContent+"</td></tr>";
								}
								text 	+= "</tbody>";
							  $('#item_A_acts').html(text);
						});
					}
				});
				
				//点击托盘，查询历史托盘动作项
				$('#traysContainer tr').on("click",function () {
					var trayID =  $(this).find("td")[0].innerText;
					var appIDIn = $(this).find("td")[1].innerText.split("/")[0];
					var appIDOut = $(this).find("td")[1].innerText.split("/")[1];
					
					if(appIDOut.length == 0) appIDOut = null;
					//包含的可能性
					var query  = "SELECT * ";
					query 		+= "FROM wActions act ";
					query 		+= "WHERE ( act.trayID="+trayID+" OR act.trayID=0 OR act.trayID=null) AND (act.appID="+appIDIn+" OR "+ "act.appID="+appIDOut+" ) ";
					
					console.log(query);
					$.post("_search.php?query="+query,function(data){
							console.log(data);
					
							var obj = jQuery.parseJSON(data);
							console.log(obj);
							var text = "<table class=\"table table-condensed table-hover\" style=\"font-size:10px;\"　>";
							text 	+= "<thead>"+"<th>入库编码</th>"+"<th>类型</th>"+"<th>时间</th>"+"<th>内容</th>"+"</thead>";
							text 	+= "<tbody>";
							
							for(var i = 0; i<obj.length; i++){
								text += "<tr><td>"+obj[i].InStockID+"</td><td>"+obj[i].actType+"</td><td>"+obj[i].actTime+"</td><td>"+obj[i].actContent+"</td></tr>";
							}
							text 	+= "</tbody>";
						  $('#item_A_acts').html(text);
					});
					
					//$('#item_A_acts').html()
				});
	    }
	  });
	  
	  
	  	//增加过滤
		$('#traySearchInput').on("keyup",function(event){
			if(event.which == 13){
				var str = $('#traySearchInput').val();

				$(".trayRow").each(function(){
					$(this).hide();
					if($(this).html().match(str)){
						$(this).show();
					}
				});

			}
		});
	  
		
		//生成表格形式 返回字符串
		function FormPanelTable(obj,attr,head,link,title){
			//console.log(obj);
			var output = "<div class=\"navbar navbar-default\" role=\"navigation\">";
			output  += 		"<div class=\"container-fluid\">";
			output  += 			"<div class=\"navbar-header\">";
			output  +=				"<a class=\"navbar-brand\" href=\"#\">"+title+"</a>";
			output  +=			"</div>";
			output  +=			"<div class=\"navbar-form navbar-left\" role=\"search\">";
			output  +=				"<div class=\"form-group\">";
			output  +=					"<input type=\"search\" id=\"traySearchInput\" class=\"form-control\" placeholder=\"托盘关键字\" value=\""+$('#traysBody').attr("key")+"\">";
			output  +=				"</div>";
			output  +=			"</div>";
			output  +=		"</div>";
			output  +=	 "</div>";
			/*
			var output = "<div class=\"panel-heading navbar-header\" \>"+title;
			output  += "<div class=\"form-group\"><input type=\"search\" id=\"traysSearchInput\" class=\"form-control\" placeholder=\"筛选托盘\"> </div>"; 
			output  += "</div>";
			*/
			output 	+= "<div class=\"panel-body\">"; 
			output 	+= "<table class=\"table table-condensed table-hover\" style=\"font-size:10px;\"　>";
			output 	+= "<thead>";
			for(var i in head){
				output += "<th>"+head[i]+"</th>";
			}
			output 	+= "</thead>";
			output 	+= "<tbody>";

			var previousAg = "";
			for(var i in obj){
				output += "<tr class=\"trayRow\" >";
				for(var j in attr){
					//加跳转
					if(link == attr[j]){
						//console.log("相同");
						output += "<td><a  class=\"agentLoad btn btn-default btn-xs\" data=\""+obj[i][attr[j]]+"\">"+obj[i][attr[j]]+"</a></td>";
					}
					else if( "wtAppID" == attr[j]){
						output += "<td>"+obj[i][attr[j]]+"/"+obj[i]['wtAppOutID']+"</td>"
					
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

		function outputHint(title,content){
			var output = "<h4>"+title+"</h4>";
			output += "<p>"+content+"</p>";

			$('#agentOperateInfo').html(output).show();
			setTimeout("$('#agentOperateInfo').hide()",2000);
		}
		
		function chooseOnTray(target) {
			console.log(target);
		}


		
	</script>
</html>