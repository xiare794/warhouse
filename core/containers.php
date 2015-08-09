
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

		<link href="../css/docs.min.css" rel="stylesheet">
		<title>出库单</title>

		
		
	</head>
	<body >
		<!-- agent container -->
			<div id="appOutBody"  >
					<div class="navbar navbar-default" role="navigation">
						<div class="container-fluid">
							
							<div class="navbar-form navbar-left" role="search">
				        <div class="form-group">
				        	<label>查询</label>
				          <input type="search" id="containerSearchInput" class="form-control" placeholder="查询集装箱">
				        </div>
				        
						  </div>
				     
				     		<ul class="nav navbar-right" >
				     			<li >
				     				<div class="btn-group ">
					     				<label class="radio-inline" id="dateRangeLabel">日期筛选关闭</label>
					     				<div id="dateRangeDiv" style="display:none">
					     					<input type="date" id="startDate">
					     					<input type="date" id="endDate">
											</div>
											<span class="glyphicon glyphicon-chevron-right" id="expandDateRangeBtn" style="cursor:pointer"></span>
										</div>
									</li>
								  <li>
								  	<div class="btn-group" class="col-xs-2">
									    <label class="radio-inline">
											  <input type="radio" name="boxComp" id="inlineCheckbox1" value="`wCStatus` = 4"> 已完成
											</label>
									    <label class="radio-inline">
											  <input type="radio" name="boxComp" id="inlineCheckbox1" value="`wCStatus` != 4" checked> 未完成
											</label>
										</div>
								  </li>
								  <li>
								  	<div class="btn-group">
									  	<button class="btn btn-default btn-sm" id="refreshListC" href="#" onclick="refreshList()" ><span class="glyphicon glyphicon-list"></span></button>
									  	<button class="btn btn-default btn-sm" id="plusContainer" href="#" onclick="PlusContainerFunc()"><span class="glyphicon glyphicon-plus"></span></button>
									  	<button class="btn btn-default btn-sm" id="refreshBtn" href="#" ><span id="refreshBtn" class="glyphicon glyphicon-refresh"></span></button>
									  </div>

								  </li>
								</ul>
				     
							
						</div>
					</div>

				
				<div class="panel panel-default scrolls-both" id="appOutContainer">
					
					<form class="form" role="form" id="CForm">
						
							<div id="mainContainer" class= "col-lg-6 col-md-6 col-sm-6" >
								筛选操作 或添加
							</div>
							<div id="viceContainer" class= "col-lg-6 col-md-6 col-sm-6">
							</div>
						
					<form>

				</div>
				
			</div>

		
	</body>

  
	<script type="text/javascript">
		//用户数组
		var users = getUsers();
		function getUsers(){
			var uA = new Array();
			var query = "query=SELECT * FROM wUsers"; 
			$.ajax({
		    type : "GET", 
		    url : "_search.php?"+query,
		    async : false, 
		    success : function(data){
		    	var obj = jQuery.parseJSON(data);
		    	console.log(obj);
		    	for(i in obj ){
		    		uA[obj[i]["userID"]] = obj[i]["wuName"];
		    	}
		    }
		  });
		  return uA;
		}

		
		//日期筛选事件
		$("#expandDateRangeBtn").on("click",function(){
			//点击扩展
			if($(this).hasClass("glyphicon-chevron-right")){
				$("#dateRangeDiv").css("display","inline-block");
				$(this).removeClass("glyphicon-chevron-right");
				$(this).addClass("glyphicon-chevron-left");
				$("#dateRangeLabel").html("日期筛选打开");
			}
			else{
				$("#dateRangeDiv").css("display","none");
				$(this).removeClass("glyphicon-chevron-left");
				$(this).addClass("glyphicon-chevron-right");
				$("#dateRangeLabel").html("日期筛选关闭");
			}
		});
		//设置默认时间
		var now = new Date();
		var oneWeekBefore = new Date(now.getTime() - 1000*60*60*24*7 );
		$("#startDate").val(oneWeekBefore.Format("yyyy-MM-dd"));
		$("#endDate").val(now.Format("yyyy-MM-dd"));


		var containerFilter = "";
		console.log($("#plusContainer"));
		function refreshList(){
			//筛选时keyword项、时间段项、完成度项
			console.log("keyword："+$("#containerSearchInput").val());
			console.log("时间段筛选器"+$("#expandDateRangeBtn").hasClass("glyphicon-chevron-right"));
			console.log("时间段:"+$("#startDate").val()+"-"+$("#endDate").val());
			console.log("完成:"+$("input[name='boxComp']:checked").val());

			//获取已有的集装箱列表
			console.log("执行refreshListC");
			var query = "query=SELECT * FROM wContainers ";
			query += "WHERE "+$("input[name='boxComp']:checked").val();
			if($("#expandDateRangeBtn").hasClass("glyphicon-chevron-left")){
				query += " AND unix_timestamp( `wCDeadline` ) between unix_timestamp( '"+$("#startDate").val()+" 00:00:00' ) and unix_timestamp( '"+$("#endDate").val()+" 00:00:00' )";
			}
			console.log(query);
			var htmlStr = "<div class=\"list-group\">";
			
			$.ajax({
				    type : "GET", 
				    url : "_search.php?"+query,
				    async : false, 
				    success : function(data){
				    	console.log(data);
				    	var obj = jQuery.parseJSON(data);
				    	for(i in obj ){
				    		//htmlStr += obj[i]["wCID"] + " | "+obj[i]["wCSeries"] + " | "+obj[i]["wCTiDan"] + " | "+obj[i]["wCTypeID"] + " | "+obj[i]["wCSeal"]+ "<br>";
				    		htmlStr += "<a href=\"#\" class=\"list-group-item\" data=\""+obj[i]["wCID"]+"\">";
				    		htmlStr += "<h5 class=\"list-group-item-heading\">"+obj[i]["wCType"]+"型箱 "+obj[i]["wCSeries"]+"</h5>";
				    		htmlStr += "<p class=\"list-group-item-text\">"+obj[i]["wCID"] + " | "+obj[i]["wCSeries"] + " | "+obj[i]["wCTiDan"] + " | "+obj[i]["wCType"] + " | "+obj[i]["wCSeal"]+"</p></a>";

				    	}
				    	//htmlStr += "</div>";
				    	$("#mainContainer").html(htmlStr);
		    			//console.log(obj[0]);

		    			//增加点击某集装箱响应
		    			$("#mainContainer a").on("click",function(){
		    				console.log("点击"+$(this).attr("data")+"号集装箱");
		    				console.log("要求在viceContainer DIV显示详细并允许实时操作");
		    				refreshVice($(this).attr("data"));
		    			});
				    	
			    	}
			  });
		}
		//刷新副显示屏
		function refreshVice(wCID){
			//清空右侧
			$("#viceContainer").html("");
			if(wCID == 0)
				return;
			var query = "query=SELECT * FROM wContainers WHERE wCID = "+wCID;
			var html = "<div id=\"cList\" class=\"panel panel-default\">";
			
			
		  
			$.ajax({
				    type : "GET", 
				    url : "_search.php?"+query,
				    async : false, 
				    success : function(data){
				    	var obj = jQuery.parseJSON(data);
				    	html+= "<div class=\"panel-heading\">箱号-"+obj[0]["wCSeries"]+"集装箱</div>";
				    	html+="<table class=\"table\">";
				    		html+="<tr><td>箱型</td><td>"+obj[0]["wCType"]+"</td>";
				    			html+="<td>箱封号</td><td>"+obj[0]["wCSeal"]+"</td></tr>";
				    		html+="<tr><td>箱提单号</td><td>"+obj[0]["wCTiDan"]+"</td>";
				    			html+="<td>船名</td><td>"+obj[0]["wCShipID"]+"</td></tr>";
				    		html+="<tr><td>司机名</td><td>"+obj[0]["wCDeliver"]+"</td>";
				    			html+="<td>始发港</td><td>"+obj[0]["wCPortFrom"]+"</td></tr>";
				    		html+="<tr><td>目的港</td><td>"+obj[0]["wCPortTo"]+"</td>";
				    			html+="<td>进港时间</td><td>"+obj[0]["wCDeadline"]+"</td></tr>";
				    	html+= "</table>";

				    	console.log(obj[0]["wCStatus"]);
				    	if(obj[0]["wCStatus"] == "0"){
				    		html += "<div class=\"panel-body\">";
					    		html+= "<p>集装箱初始化成功,等待绑定出库单</p>";
					    		html+= "<div class=\"btn-group\">";
		  							html+= "<button type=\"button\" class=\"btn btn-danger\" onclick=\"deleteContainer("+obj[0]["wCID"]+")\">删除</button>";
		  							html+= "<button type=\"button\" class=\"btn btn-default\">备用</button>";
									html+= "</div>";
								html += "</div>";// end panel-body
				    	}
				    	else if(obj[0]["wCStatus"] == "1"){
				    		html+= "<p>加载集装箱信息</p>";
				    		html+= getAppOutInfo(wCID);
				    	}
				    	else if(obj[0]["wCStatus"] == "2"){
				    		html+= "<p>集装箱装箱中</p>";
				    		html+= getAppOutInfo(wCID);
				    	}
				    	else if(obj[0]["wCStatus"] == "3"){
				    		html+= "<p>集装箱装箱完成</p>";
				    		html+= getAppOutInfo(wCID);
				    	}
				    	else if(obj[0]["wCStatus"] == "4"){
				    		html+= "<p>集装箱已离开仓库</p>";
				    		html+= getAppOutInfo(wCID);
				    	}

				    	html+= "</div>"; //panel结束

				    }
			});
			$("#viceContainer").html(html);
		}
		function getAppOutInfo(wCID){
			var responseText = "";
			var query = "query=SELECT * FROM `wAppOut` aOut , `wTrays` t WHERE aOut.containerID = "+wCID+" AND t.wtAppOutID = aOut.wAppID";
			$.ajax({
		    type : "GET", 
		    url : "_search.php?"+query,
		    async : false, 
		    success : function(data){
		    	console.log(data);
		    	var obj = jQuery.parseJSON(data);
		    	console.log(obj);
		    	if(obj.length>0){
			    	responseText +="<table class=\"table\">";
			    	responseText += "<tr><td>输单"+users[obj[0]["OpInput"]]+"</td>";
			    	responseText += "<td>叉车"+users[obj[0]["OpFork"]]+"</td>";
			    	responseText += "<td>仓管"+users[obj[0]["OpManager"]]+"</td>";
			    	responseText += "<td>理货"+users[obj[0]["OpCounter"]]+"</td>";
			    	responseText += "<td>审核"+users[obj[0]["OpReview"]]+"</td></tr>";
			    	responseText += "</table>";
			    }
		    	else{
		    		responseText += "没有数据";
		    	}
		    }
		  });
		  console.log(responseText);
		  return responseText;
		}

		function deleteContainer(id){
			
			var query = "delQuery= DELETE from wContainers where wCID="+id;
			$.ajax({
		    type : "GET", 
		    url : "_search.php?"+query,
		    async : false, 
		    success : function(data){
		    	console.log("数据是"+data);
		    	if(data[0]== "1"){
		    		addRemind("成功删除","删除ID为"+id+"的集装箱",5000,"bs-callout-danger");
		    		refreshList();
		    		refreshVice(0);
		    	}
		    }
		  });
		}
		
		function newCont_onsubmit(){
			//return false;
			console.log("执行newCont_onsubmit");
			//console.log($("#wCType"));
			//console.log($("#wCType").val());
			var valid = true;
			$("#viceContainer").html("");
			var postStr = "";

			$("#CForm select").each(function(){
				postStr   += $(this).attr("name")+"="+$(this).val()+"&";
			});
			$("#CForm input").each(function(){
				if($(this).val() == ""){
					//为空
					if(valid)
						valid = false;
					$("#viceContainer").html( $("#viceContainer").html()+"<br>"+$(this).attr("name")+"为空,valid"+valid);
					//alert($(this).attr("name")+"为空");
				}
				//需要POST的值，把每个变量都通过&来联接  
				postStr   += $(this).attr("name")+"="+$(this).val()+"&";
			});
			console.log(postStr);
			
			if(valid){
				//增加集装箱
				$.ajax({
				    type : "POST", 
				    url : "_insert.php?table=wContainers",
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
	  
		function addInput(object){
			console.log("执行addInput");
			if(object.type == "select"){
				var html = "<label  class=\"control-label\" for=\""+object.id+"\">"+object.label+"</label>";
				html += "<select name=\""+object.id+"\" id=\""+object.id+"\" class=\"form-control\" ></select>";
			}
			else{
				var html = "<label  class=\"control-label\" for=\""+object.id+"\">"+object.label+"</label>";
				html +=  "<input  name=\""+object.id+"\" id=\""+object.id+"\" type=\""+object.type+"\" class=\"form-control\" placeholder=\""+object.placeholder+"\" >";
			}
			return html;
		}


		function PlusContainerFunc(){
		//$("#plusContainer").on("click",function(){
			console.log("plusContainer");
			//显示新建集装箱
			var attrs = new Array();
			attrs[0] = {id:"wCSeries",type:"text",label:"箱号",placeholder:"填入箱号",inputStyle:"width:50%"};
			attrs[1] = {id:"wCType",type:"select",label:"箱型",placeholder:"CT 基础型",inputStyle:""};
			attrs[2] = {id:"wCSeal",type:"text",label:"封号",placeholder:"Container SEAL Number",inputStyle:""};
			attrs[3] = {id:"wCTiDAN",type:"text",label:"提单号",placeholder:"提单号",inputStyle:""};
			attrs[4] = {id:"wCShipID",type:"text",label:"船号",placeholder:"",inputStyle:""};
			attrs[5] = {id:"wCDeliver",type:"text",label:"司机信息",placeholder:"送货司机信息 姓名车牌号电话",inputStyle:""};
			attrs[6] = {id:"wCPortFrom",type:"text",label:"出发信息",placeholder:"出发",inputStyle:""};
			attrs[7] = {id:"wCPortTo",type:"text",label:"目的港口",placeholder:"目的港",inputStyle:""};
			attrs[7] = {id:"wCDeadline",type:"date",label:"时间",placeholder:"截止时间",inputStyle:""};
			
			var str = "<form class=\"form\" role=\"form\" id=\"CForm\">";
			str += 		"<div class=\"row\">";
			str += 		"	<div id=\"mainContainer\" class= \"col-lg-6 col-md-6 col-sm-6\" style=\"border-right:1px solid;padding:0px 18px\" >";
			for(var i in attrs){
				str += addInput(attrs[i]);
			}

			str += "<a id=\"newContainer\" onclick=\"newCont_onsubmit()\"  class=\"btn-embossed btn btn-primary form-control\" style=\"width:40%\">新建</a>";

			str += "</div>";
			str += "<div id=\"viceContainer\" class= \"col-lg-6 col-md-6 col-sm-6\"></div></div></form>";
			//console.log(str);
			$("#appOutContainer").html(str);

			$("#wCType").append("<option value=\"defaut\">未知</option>");
			for(var i in cTypes){
				//console.log(cTypes[i]);
				$("#wCType").append("<option value=\""+cTypes[i].type+"\">"+cTypes[i].text+"</option>");
			}

		}

		var cTypes = new Array();
		$.ajax({
		    url : "../containerType.xml",
		   	dataType: 'xml',
		    async : false, 
		    success : function(data){
		    	//console.log(data);
		    	$(data).find("Row").each(function(index,ele){
		    		//var cTypes[index] = new object();
		    		var cType ="";
		    		var cText = "";
		    		//console.log(cTypes);
		    		if(index != 0){
			    		$(ele).find("Data").each(function(ind,el){
			    			//console.log($(el).text());
			    			//0序号 1尺寸 2类型 3说明 4需要转换说明
			    			if(ind == 2) //类型
			    				cType = $(el).text();
			    			if(ind == 3) //说明
			    				cText = $(el).text();

			    		});
			    		
			    		cTypes[index] = {type:cType, text:cText};
		    		}

		    	});
		    	//console.log(cTypes);
	    	}
	  });
	  
		$("#containerSearchInput").on("blur",function(){
			refreshList();
			containerFilter = $("#containerSearchInput").val();
			console.log(containerFilter);
			console.log($("#mainContainer a"));
			$("#mainContainer a").each(function(i){
				if($(this).text().search(containerFilter) != -1){
					$(this).css("display","block");
				}
				else{
					$(this).css("display","none");
				}
				//console.log($(this).text());
			});

		});
	  console.log("script 结束 ");

	  
	</script>
	
	<!-- Bootstrap core js -->
	<!--<script src="dist/js/jquery-1.11.1.js"></script>
  <script src="dist/js/bootstrap.min.js"></script> -->

</html>

