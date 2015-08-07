<!DOCTYPE html>
<html lang="zh-cn">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="WSLayout">
		<meta name="author" content="Ref">


		<title>托盘</title>
		<!-- <link href="dist/css/bootstrap.css" rel="stylesheet"> -->
	</head>
	<body >
		<!-- agent container -->
		
		<div class="container">
			<div id="appDetailBody" class="row" >
				<div class="panel panel-default " id="appDetailContainer">
				</div>
			</div>

			<div>
		
	</body>
	
	<!-- Bootstrap core js 
	<script src="dist/js/jquery-1.11.1.js"></script>
  <script src="dist/js/bootstrap.min.js"></script>-->
	
  <script>
	  //货单需求较多
	  //代理商
	  //同一进仓编号下的其他库单
	  //库单具体情况
	  //
	  /* 分离url */

	  var aQuery = window.location.href.split("?");
	  var aGET = new Array();
    if(aQuery.length > 1)
    {
        var aBuf = aQuery[1].split("&");
        for(var i=0, iLoop = aBuf.length; i<iLoop; i++)
        {
            var aTmp = aBuf[i].split("=");  //分离key与Value
            aGET[aTmp[0]] = aTmp[1];
        }
     }
		//return aGET;
	  //console.log(aGET);
	  if(!aGET['appID']){
	  	console.log("未传入合适带有appID的地址");
	  	console.log(window.location.href);
	  }
	  //alert(window.location.href);

	  var appID = aGET['appID'];
	  appID = <?php if(isset($_GET['appID'])) echo $_GET['appID']; else echo 0; ?>;
	  console.log("打印出来appID="+appID);

	  //查询对应货代和app
	  var query = "SELECT a.waName, a.waType, a.waTel, a.waAddr, a.waContact, a.waNote, ";
	  query 	 += "app.appName,app.InStockID, app.appID, app.appMaitou, app.appCount, app.appBookingDate, app.appStatus "
	  query 	 += "FROM wAgents a, wAppIn app ";
	  query 	 += "WHERE  a.waID=app.agentID AND app.appID="+appID;
	  $.ajax({ 
	    type : "post", 
	    url : "_search.php?query="+query,
	    async : false, 
	    success : function(data){
	    	//console.log(data);	
	    	var head = new Array("代理商名","代理类型","联系人","电话","地址","备注");
				var attr = new Array("waName","waType","waContact","waTel","waAddr");
	    	var obj = jQuery.parseJSON(data);
	    	$('#appDetailContainer').html( FormPanelTable(obj,attr,head,"","代理商"));
	    	console.log(obj);
	    	var head = new Array("代理商名","代理类型","电话","地址","联系人","备注","货单名称","进仓编号","出入库","货单ID","唛头","数量","时间","完成");
	    	$('#appDetailContainer').append(FormTableWithSingleObj(obj,head,"","本库单"));
	    }
	  });

	  //查询同一进仓编号下的其他表单
	  var query = "SELECT app1.* ";
	  query 	 += "FROM wAppIn app1, wAppIn app2 ";
	  query 	 += "WHERE app1.InStockID = app2.InStockID AND app1.appID<>"+appID+"  AND app2.appID="+appID ;
	  $.ajax({ 
	    type : "post", 
	    url : "_search.php?query="+query,
	    async : false, 
	    success : function(data){
	    	console.log(data);	
	    	var head = new Array("货单名称","进仓编号","出入库","货单ID","唛头","数量","时间","完成");
				var attr = new Array("appName","InStockID","appType","appID","appMaitou","appCount","appBookingDate","appComplete");
	    	var obj = jQuery.parseJSON(data);
	    	$('#appDetailContainer').append( FormPanelTable(obj,attr,head,"appID","其他相关库单"));
	    	
	    }
	  });
	  $('.appIDBtn').on("click",function(data){
	  	console.log("click"+$(this).attr("data"));
	  	$('#appDetailBox').load("appDetail.php?appID="+$(this).attr("data"));
	  	//$('#appDetailBox').load("appDetail.php?appID="+$(this).attr('id'));
			jumpPage("#appDetailBox","#navViewAppBtn");
	  	//window.location.href = "appDetail.php?appID="+$(this).attr("data");
	  });

	  //查询此货单下的托盘
	  var query = "SELECT t.*, u.*";
	  query 	 += "FROM wTrays t, wUnit u ";
	  query 	 += "WHERE (t.wtAppID="+appID+" OR t.wtAppOutID="+appID+") AND u.trayID=t.wtID ";
	  query 	 += "ORDER by t.wtID";
	  $.ajax({ 
	    type : "post", 
	    url : "_search.php?query="+query,
	    async : false, 
	    success : function(data){
	    	console.log(data);	
	    	var head = new Array("托盘号","托盘数字编码","托盘状态","托盘入库单","托盘出库单","托盘仓库","托盘货物数量","托盘更新时间","货物ID","货物名称","宽(cm)","高(cm)","长度(cm)","重量(kg)","箱数量");
				var attr = new Array("wtID","rfid","twStatus","wtAppID","wtAppOutID","wSlotID","twWareCount","UpdateTime","wiID","wiName","width","height","length","weight","count");
	    	var obj = jQuery.parseJSON(data);
	    	$('#appDetailContainer').append( FormPanelTable(obj,attr,head,"wtID","相关托盘"));
	    	
	    }
	  });
		
		//显示列表
		/*
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
	  });*/
	  
	  //生成单一物体的表格所有属性
	  function FormTableWithSingleObj(obj,head,link,title){
	  	var output ="<div class=\"panel-heading\" \>"+title+"</div>";
	  	output 	+= "<div class=\"panel-body\">";
	  	output 	+= "<table class=\"table table-condensed table-hover table-bordered scrolls-horizontal\" style=\"font-size:85%;\"";
	  	var dataRow = "";
	  	var headerRow ="";
	  	var col=0;
	  	console.log("生成单个物体");
	  	for(var i in obj[0]){
	  		//选取全非数字
	  		var colNum=4;
	  		//行清零
	  		
	  		//条件=如果非数字
	  		if(/^\d+$/.test(i)){
	  			//输入col头
	  			if(col==0){
	  				dataRow   += "<tr>";
	  				headerRow	+= "<tr>";
	  			}
	  			headerRow += "<th>"+head[i]+"</th>";
	  			dataRow += "<td>"+obj[0][i]+"</td>";
	  			
	  			if(col==colNum){
	  				dataRow   += "</tr>";
	  				headerRow	+= "</tr>";

	  				output 	+= headerRow;
	  				output += dataRow;
	  				
	  				dataRow ="";
	  				headerRow ="";
	  				console.log("行结束");
	  			}
	  			//console.log(i);
	  			col++;
	  			
		  		console.log("累加"+col);
		  		if(col>colNum){
		  			col=0;
		  		}
	  		}
	  	}
	  	if(col != 0){
	  			output += headerRow+"</tr>"+dataRow+"</tr>";
	  		}

	  	output 	+="</table></div>";
	  	console.log(output);
	  	return output;
	  }
		
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

			var previousItem;
			for(var i in obj){
				output += "<tr class=\"agentRow\">";
				//正常按序显示
				if(previousItem==null || previousItem['wtID'] != obj[i]['wtID']){
					for(var j in attr){
						//加跳转
						if(link == attr[j]){
							//console.log("相同");
							output += "<td><a  class=\"appIDBtn btn btn-default btn-xs\" data=\""+obj[i][attr[j]]+"\">"+obj[i][attr[j]]+"</a></td>";
						}
						else{
							output += "<td>"+obj[i][attr[j]]+"</td>";
						}
					}
				}
				else{
					var skip = true;
					for(var j in attr){
						if(attr[j] == "wiID"){
							skip = false;
						}
						if(skip)
							output += "<td></td>";
						else
							output += "<td>"+obj[i][attr[j]]+"</td>";

					}
				}
				output += "</tr>";
				previousItem = obj[i];
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