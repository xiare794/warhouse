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
			<ul class="nav nav-tabs" role="tablist" id="HouseNavTab">
			  <li role="presentation"><a href="#houseContainer">老仓库显示</a></li>
			  <li role="presentation" class="active"><a href="#tongzhou1">仓库显示</a></li>
			</ul>
			<div id="houseBody" >
				<div class=" col-lg-12 col-md-12" id="houseContainer" >旧仓库显示
				</div>
				<div class="panel panel-default col-lg-12 col-md-12 " id="tongzhou1" >
					<!--<div class="panel-headding">
							<h6>一号库情况</h6>
						</div>
						<div class="panel-body">
							<p>testtesttesttesttest</p>
						</div>-->
						<table class="table" id="wareHouseDisplay">
			        <thead>
			          <tr>
			            <th>#</th>
			            <th>First Name</th>
			            <th>Last Name</th>
			            <th>Username</th>
			          </tr>
			        </thead>
			        <tbody>
			          <tr>
			            <td>1</td>
			            <td>Mark</td>
			            <td>Otto</td>
			            <td>@mdo</td>
			          </tr>
			          <tr>
			            <td>2</td>
			            <td>Jacob</td>
			            <td>Thornton</td>
			            <td>@fat</td>
			          </tr>
			          <tr>
			            <td>3</td>
			            <td>Larry</td>
			            <td>the Bird</td>
			            <td>@twitter</td>
			          </tr>
			        </tbody>
			      </table>
							<!--<button id="autoGenerateHouse">产生仓库位置</button>-->
					
				</div>
			</div>

			
		
	</body>
	
	<!-- Bootstrap core js
	<script src="dist/js/jquery-1.11.1.js"></script>
  <script src="dist/js/bootstrap.min.js"></script> -->
	
  <script>
  	//产生tsPos 临时用
  	$("#autoGenerateHouse").on("click",function(){
  		var query ="query=SELECT *,max(wSlotID),min(wSlotID) FROM wSlots";
  		var ss = 0;
  		var se = 0;
  		$.ajax({
		    type : "post", 
		    url : "_search.php?"+query,
		    async : false, 
		    success : function(data){
		    	var obj = jQuery.parseJSON(data);
		    	ss = obj[0]["min(wSlotID)"];
		    	se = obj[0]["max(wSlotID)"];
		    }
	    });

  		var pos = new Array();
  		var ch = new Array("A","B","C");
  		for(var i=0; i<3; i++){
  			for(var j = 1; j<26; j++){
  				pos.push(ch[i]+j);
  			}
  		}
  		console.log(pos.length);

  		console.log(se-ss);
  		var idx = 0;
  		for(var i = ss; i<se; i++){
				$.get("phpUpdate.php?table=wSlots&&idAttr=wSlotID&&idValue="+i+"&&tAttr=tsPos&&tValue="+pos[idx]);
				idx++;	
  		}

  	});


  	$("#HouseNavTab a").on("click",function(){
  		$("#HouseNavTab li").removeClass("active");
  		$(this).parent().addClass("active");
  		
  		var target = $(this).attr("href").substring(1);
  		$("#houseContainer").css("display","none");
  		$("#tongzhou1").css("display","none");
  		$($(this).attr("href")).css("display","");

  		console.log("到这了");
  		console.log($(this).attr("href"));
  		if($(this).attr("href")=="#tongzhou1"){
  			//显示
  			getWareHouseDisplay();
  			

  		}
  	});

  	function getWareHouseDisplay(){
  		var html = "<thead><tr><th colspan=\"2\">A排</td><th colspan=\"2\">B排</td><th colspan=\"2\">C排</td></tr></thead>";
  		for(var i=1; i<26; i++){
  			html+= "<tr ><td id=\"A"+i+"\">A"+i+"</td>";
  			html+= "<td id=\"A"+(i+1)+"\">A"+(i+1)+"</td>";

  			html+= "<td id=\"B"+i+"\">B"+i+"</td>";
  			html+= "<td id=\"B"+(i+1)+"\">B"+(i+1)+"</td>";

  			html+= "<td id=\"C"+i+"\">C"+i+"</td>";
  			html+= "<td id=\"C"+(i+1)+"\">C"+(i+1)+"</td>";
  			html+= "</tr>";
  			i++;
  		}
  		$("#wareHouseDisplay").html(html);

  		var query ="query=SELECT * FROM wSlots";
  		$.ajax({
		    type : "post", 
		    url : "_search.php?"+query,
		    async : false, 
		    success : function(data){
		    	var slots = jQuery.parseJSON(data);

		    	var trays;
	    		var appIns; 
	    		var query = "query=SELECT * FROM wTrays";
	    		$.ajax({ 
				    type : "post", 
				    url : "_search.php?"+query,
				    async : false,
				    dataType:"json",
				    success : function(data){
				    	trays = data;
				    }
				  });
				  query = "query=SELECT * FROM wAppIn";
				  $.ajax({ 
				    type : "post", 
				    url : "_search.php?"+query,
				    async : false,
				    dataType:"json",
				    success : function(data){
				    	appIns = data;
				    }
				  });
	    		
	    		//console.log(trays);
	    		//console.log(appIns);

		    	for(var i in trays){
		    		if(trays[i]["twStatus"]=="货架"){
		    			console.log("herehre");
		    			var slotID = trays[i]["wSlotID"];
		    			//console.log(trays[i]);
		    			for(var j in slots){
		    				if(slots[j]["wSlotID"]== slotID){
		    					//console.log(slots[j]['tsPos']);
		    					//console.log( $("#wareHouseDisplay #"+slots[j]['tsPos'])) ;
		    					var appIn;
		    					var query = "SELECT * FROM wAppIn WHERE appID= "+ trays[i]["wtAppID"];
		    					$.ajax({ 
								    type : "post", 
								    url : "_search.php?query="+query,
								    async : false,
								    dataType:"json",
								    success : function(data){
								    	appIn = data[0];
								    }
								  });
								  console.log(appIn);
								  var tips = "进仓编号:"+appIn["InStockID"]+"<br/>";
					  			tips += "货物:"+appIn["appName"]+"<br/>";
					  			tips += "数量:"+trays[i]["twWareCount"];

		    					var span = "<span class=\"glyphicon glyphicon-list\" style=\"color:DarkSeaGreen\" data-toggle=\"tooltip\"  data-original-title=\""+tips+"\" ></span>";
		    					$("#wareHouseDisplay #"+slots[j]['tsPos']).append(span);

		    					break;
		    				}
		    			}
		    		}
		    	}


	    		var options={
						animation:true,
						trigger:'hover',
						html:true //触发tooltip的事件
					}
				  $('#wareHouseDisplay span').tooltip(options);
				  /*
				  $('#wareHouseDisplay span').on("mouseover",function(){
				  		//console.log( $(this).attr("appID"));
				  		var query = "SELECT * FROM wAppIn WHERE appID= "+ $(this).attr("appID");
				  		//console.log(query);
				  		var tSpan = $(this);
				  		$.getJSON("_search.php?query="+query, function(data){
				  			console.log(data);
				  			console.log("打印自己");
				  			console.log(tSpan);
				  			var tips = "进仓编号:"+data[0]["InStockID"]+"<br/>";
				  			tips += "货物:"+data[0]["appName"]+"<br/>";
				  			tips += "数量:"+$(this).attr("count");
				  			console.log($(this).attr("data-original-title"));
				  			tSpan.attr("data-original-title",tips);
				  			tSpan.unbind();
				  			console.log(data);
				  		});
				  });*/
		    		//console.log(obj[i]['tsPos']);
		    		
		    		/*
		    		var query = "query=SELECT t.wtID, t.twStatus t.wtAppID, t.wSlotID FROM wTrays t, wAppIn in WHERE "
		    		$.getJSON("_search.php?"+query, function(data){

						  console.log(data);

						});*/
		    	
		    }
	    });
	    return html;
  	}

  	//s计算天数
  	function calculateDays(date){
  		var day = date.split(" ")[0].split("-");
  		var time = date.split(" ")[1].split(":");
  		//date = date.replace(/-/g, " ");
  		var dd = new Date(day[0],day[1],day[2],time[0],time[1],time[2]);
  		
  		var diff = new Date().getTime() - dd.getTime();
  		var diffDays = Math.floor(diff/1000/60/60/24);
  		return(diffDays);
  	}
	  //托盘有什么需求呢
	  //这里需要显示托盘的整体情况，具体托盘
	  var mxRow = 0;
	  var mxCol = 0;
	  var mxFloor = 0;
	  var query =" SELECT *,max(tsPosRow),max(tsPosCol),max(tsPosFloor) FROM wSlots ";
		$.ajax({ 
	    type : "post", 
	    url : "_search.php?query="+query,
	    async : false, 
	    success : function(data){
	    	//console.log(data);
				//var head = new Array("编码","表单","仓库位置","数量","规格","操作时间");
				var attr = new Array("wSlotID","tsWareHouse","tsPosRow","tsPosCol","tsPosFloor","wtID");
				var obj = jQuery.parseJSON(data);
				//console.log(obj);
				
				
				//var link = "wSlotID";
				
				mxRow = Number(obj[0]["max(tsPosRow)"]);
				mxCol = Number(obj[0]["max(tsPosCol)"]);
				mxFloor = Number(obj[0]["max(tsPosFloor)"]);
				
				var wareVox = [];
				for(var item in obj){
				//	wareVox [item['tsPosRow']][item['tsPosCol']][item['tsPosFloor']];
				}
				//console.log(wareVox);
				
				$('#houseContainer').html( FormPanelTable(obj,attr,"","","仓库列表"));
				
	    }
	  });
	  //查找所有仓位修改其颜色
	  var query  ="SELECT * FROM wSlots s ";
	  $.ajax({ 
	    type : "post", 
	    url : "_search.php?query="+query,
	    async : false, 
	    success : function(data){
	    	var obj = jQuery.parseJSON(data);
	    	
	    	for(var i in obj){
	    		var item = $("#houseContainer td[row="+obj[i]["tsPosRow"]+"][floor="+obj[i]["tsPosFloor"]+"][col="+obj[i]["tsPosCol"]+"][house="+obj[i]["tsWareHouse"]+"]");
	    		item.css("background-color","#EEE");
	    		item.css("border-left","1px solid #333");
	    		item.css("border-right","1px solid #333");
	    		item.css("border-top","2px solid #333");
	    		item.css("border-bottom","2px solid #333");
	    		item.css("width","50px");
	    		item.css("height","35px");
	    		
	    		
	    		//item.html(obj[i]["tsPosRow"]+"-"+obj[i]["tsPosCol"]+"-"+obj[i]["tsPosFloor"]);
	    		//console.log(item);
	    	}
	    }
	  });
		
	  //查找所有仓位和托盘对的上好的位置
	  var query  ="SELECT * FROM wSlots s, wTrays t, wUnit u ";
	  query 		+="WHERE s.wtID = t.wtID AND u.trayID=t.wtID ";
	  $.ajax({ 
	    type : "post", 
	    url : "_search.php?query="+query,
	    async : false, 
	    success : function(data){
	    	//console.log(data);
				var obj = jQuery.parseJSON(data);
				//console.log(obj);
				for(var i in obj){
					//console.log("计算天"+calculateDays(obj[i]["updateTime"]));
					var item = $("#houseContainer td[row="+obj[i]["tsPosRow"]+"][floor="+obj[i]["tsPosFloor"]+"][col="+obj[i]["tsPosCol"]+"][house="+obj[i]["tsWareHouse"]+"]");
					var string = "仓库位置"+ obj[i]["wSlotID"]+"<br/>"; 
					string += obj[i]["wiName"]+obj[i]["count"]+"箱 <br/>";
					string += "更新时间"+obj[i]["updateTime"];
					string += "已存放"+calculateDays(obj[i]["updateTime"])+"天";
					item.append("<span class=\"glyphicon glyphicon-list\" style=\"color:DarkSeaGreen\" data-toggle=\"tooltip\"  data-original-title=\""+string+"\" ></span> ");
					//<button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="" data-original-title="Tooltip on top">上方Tooltip</button>
					console.log(calculateDays(obj[i]["updateTime"]));
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
			//console.log(obj[0]);
			console.log(mxRow + " " +mxCol + " " +mxFloor );
			var output = "<div class=\"panel-heading\" \>"+title+"</div>";
			output 	+= "<div class=\"panel-body\">";

			output 	+= "<table class=\"table \" style=\"font-size:10px;\"";
			//顺序是
			output 	+= "<tbody>";
				output += "<tr>";
				output += "<td>货仓0号</td>";
				for(var c=1; c<mxCol+1; c++){
					output += "<td>"+c+"列</td>";
					
					console.log(c+"列/"+Number(mxCol+1)+"列");
				}
				output += "</tr>";
				
				
				for(var r=1; r<mxRow+1; r++){
					output += "<tr class=\"houseRow\">";
						output += "<td rowspan=\"3\">"+r+"行</td>";
						for(var f=1; f<mxFloor+1; f++){
							
							for(var c=1; c<mxCol+1; c++){
								output += "<td floor=\""+f+"\" house=\"0\" row=\""+r+"\" col=\""+c+"\"></td>";
							
							}
							output += "</tr><tr>"
						}
						output += "</tr >";
				}
				//<span class=\"glyphicon glyphicon-unchecked\" style=\"color:DimGray \" ></span>
				
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