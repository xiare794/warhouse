<!DOCTYPE html>
<html lang="zh-cn">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="WSLayout">
		<meta name="author" content="Ref">


		<title>仓库</title>
		<!-- <link href="dist/css/bootstrap.css" rel="stylesheet">-->
	</head>
	<body class="container">
		<!-- agent container -->
			<div id="houseBody" class="row" >
				<div class="panel panel-default col-lg-12 " id="houseContainer">仓库
				</div>
			</div>

			
		
	</body>
	
	<!-- Bootstrap core js
	<script src="dist/js/jquery-1.11.1.js"></script>
  <script src="dist/js/bootstrap.min.js"></script> -->
	
  <script>
  	//s计算天数
  	function calculateDays(date){
  		var diff = (new Date()).getTime() - (new Date(date)).getTime();
  		var diffDays = Math.floor(diff/1000/60/60/24);

  		//console.log(diffDays+"天");
  		return(diffDays);
  	}
	  //托盘有什么需求呢
	  //这里需要显示托盘的整体情况，具体托盘
	  var query =" SELECT * FROM wSlots ";
		$.ajax({ 
	    type : "post", 
	    url : "_search.php?query="+query,
	    async : false, 
	    success : function(data){
	    	//console.log(data);
				//var head = new Array("编码","表单","仓库位置","数量","规格","操作时间");
				var attr = new Array("wSlotID","tsWareHouse","tsPosRow","tsPosCol","tsPosFloor","wtID");
				var obj = jQuery.parseJSON(data);
				//var link = "wSlotID";
				console.log(obj);
				$('#houseContainer').html( FormPanelTable(obj,attr,"","","仓库列表"));
	    }
	  });

		//查找所有仓位和托盘对的上好的位置
	  var query  ="SELECT * FROM wSlots s, wTrays t, wareUnit u ";
	  query 		+="WHERE s.wtID = t.wtID AND u.trayID=t.wtID ";
	  $.ajax({ 
	    type : "post", 
	    url : "_search.php?query="+query,
	    async : false, 
	    success : function(data){
	    	//console.log(data);
				var obj = jQuery.parseJSON(data);
				console.log(obj);
				for(var i in obj){
					var item = $("#houseContainer td[row="+obj[i]["tsPosRow"]+"][floor="+obj[i]["tsPosFloor"]+"][col="+obj[i]["tsPosCol"]+"][house="+obj[i]["tsWareHouse"]+"]");
					var string = "仓库位置"+ obj[i]["wSlotID"]+"<br/>"; 
					string += obj[i]["wiName"]+obj[i]["count"]+"箱 <br/>";
					string += "更新时间"+obj[i]["updateTime"];
					string += "已存放"+calculateDays(obj[i]["updateTime"])+"天";
					calculateDays(obj[i]["updateTime"]);
					item.append("<span class=\"glyphicon glyphicon-list\" style=\"color:DarkSeaGreen\" data-toggle=\"tooltip\"  data-original-title=\""+string+"\" ></span> ");
					//<button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="" data-original-title="Tooltip on top">上方Tooltip</button>
				}
	    }
	  });
	  var options={
			animation:true,
			trigger:'hover',
			html:true //触发tooltip的事件
		}
	  $('#houseContainer span').tooltip(options);
	  
		
		//生成表格形式 返回字符串
		function FormPanelTable(obj,attr,head,link,title){
			//console.log(obj);
			var output = "<div class=\"panel-heading\" \>"+title+"</div>";
			output 	+= "<div class=\"panel-body\">";

			output 	+= "<table class=\"table table-bordered \" style=\"font-size:10px;\"";

			output 	+= "<tbody>";
				output += "<tr>";
				output += "<td>货仓0号</td>";
				for(var c=0; c<8; c++){
					output += "<td>"+c+"列</td>";
				}
				output += "</tr>";
				for(var r=0; r<5; r++){
					output += "<tr class=\"houseRow\">";
						output += "<td rowspan=\"2\">"+r+"行</td>";
						for(var c=0; c<8; c++){
							output += "<td floor=\"2\" house=\"0\" row=\""+r+"\" col=\""+c+"\"><span class=\"glyphicon glyphicon-unchecked\" style=\"color:DimGray \" ></span></td>";
						}
						output += "</tr >";

						output += "<tr class=\"houseRow\">";
						for(var c=0; c<8; c++){
							output += "<td floor=\"1\" house=\"0\" row=\""+r+"\" col=\""+c+"\"><span class=\"glyphicon glyphicon-unchecked\" style=\"color:DimGray \"> </span></td>";
						}
					output += "</tr >";
				}
			output  += "</tbody>";
			output 	 += "</table>";
			output 	 += "</div>";
			return output;
		}

			
			//for(var i in obj){
				
				/*output += "<tr class=\"agentRow\">";
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
				*/


		function outputHint(title,content){
			var output = "<h4>"+title+"</h4>";
			output += "<p>"+content+"</p>";

			$('#agentOperateInfo').html(output).show();
			setTimeout("$('#agentOperateInfo').hide()",2000);
		}


		
	</script>
</html>