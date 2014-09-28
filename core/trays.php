<!DOCTYPE html>
<html lang="zh-cn">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="WSLayout">
		<meta name="author" content="Ref">


		<title>托盘</title>
		<!--<link href="dist/css/bootstrap.css" rel="stylesheet"> -->
	</head>
	<body >
		<!-- agent container -->
			<div id="traysBody" class="row" >
				<div class="panel panel-default scrolls-both" id="traysContainer">
				</div>
			</div>

			
		
	</body>
	
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
	    }
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
				output += "<tr class=\"agentRow\">";
				for(var j in attr){
					//加跳转
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


		
	</script>
</html>