<?php require_once("config.php"); //session
?>
<!DOCTYPE html>
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


		<title>统计</title>
		<link rel="stylesheet" href="jqplot/jquery.jqplot.css">
		<style >
		.form-control{
			padding: 0px 10px;
		}
		/*
		 * Callouts
		 *
		 * Not quite alerts, but custom and helpful notes for folks reading the docs.
		 * Requires a base and modifier class.
		 */

		/* Common styles for all types */
		.bs-callout {
		  margin: 20px 0;
		  padding: 20px;
		  border-left: 3px solid #eee;
		}
		.bs-callout h4 {
		  margin-top: 0;
		  margin-bottom: 5px;
		}
		.bs-callout p:last-child {
		  margin-bottom: 0;
		}

		/* Variations */
		.bs-callout-danger {
		  background-color: #fdf7f7;
		  border-color: #eed3d7;
		}
		.bs-callout-danger h4 {
		  color: #b94a48;
		}
		.bs-callout-warning {
		  background-color: #faf8f0;
		  border-color: #faebcc;
		}
		.bs-callout-warning h4 {
		  color: #8a6d3b;
		}
		.bs-callout-info {
		  background-color: #f4f8fa;
		  border-color: #bce8f1;
		}
		.bs-callout-info h4 {
		  color: #34789a;
		}

		.actionContentCell{
			width: 200px;
		}
		</style>
		
		<!-- <link href="dist/css/bootstrap.css" rel="stylesheet"> -->
		<script type="text/javascript" src="jqplot/jquery.jqplot.js"></script>
    
    <script type="text/javascript" src="jqplot/plugins/jqplot.canvasAxisLabelRenderer.min.js"></script>
		<script type="text/javascript" src="jqplot/plugins/jqplot.canvasTextRenderer.min.js"></script>
   	<script type="text/javascript" src="jqplot/plugins/jqplot.dateAxisRenderer.min.js"></script>
   	<script type="text/javascript" src="jqplot/plugins/jqplot.pieRenderer.min.js"></script>
    <script type="text/javascript" src="jqplot/plugins/jqplot.donutRenderer.min.js"></script>

    <script type="text/javascript" src="jqplot/plugins/jqplot.barRenderer.min.js"></script>
		<script type="text/javascript" src="jqplot/plugins/jqplot.categoryAxisRenderer.min.js"></script>
		<script type="text/javascript" src="jqplot/plugins/jqplot.pointLabels.min.js"></script>

		<script type="text/javascript" src="jqplot/plugins/jqplot.highlighter.min.js"></script>
		<script type="text/javascript" src="jqplot/plugins/jqplot.cursor.min.js"></script>
	
  	<!--<script type="text/javascript" src="syntaxhighlighter/scripts/shCore.min.js"></script>
    <script type="text/javascript" src="syntaxhighlighter/scripts/shBrushJScript.min.js"></script>
    <script type="text/javascript" src="syntaxhighlighter/scripts/shBrushXml.min.js"></script>-->
	</head>
	<body >
		<!-- action container -->
			<div id="actionBody" class="row-fluid" >
				<!--
				<div class="bs-callout bs-callout-warning col-lg-6" id="actionOperateInfo" style="position:fixed; opacity:0.9; z-index:99">
					<h4>测试警告</h4>
					<p>这里提供一些刷新的信息，2秒后消失</p>
					<?php var_dump($_SESSION); ?>
				</div>

				<div class="navbar navbar-default" role="navigation">
					<div class="container-fluid">
						<div class="navbar-header">
							<a class="navbar-brand" href="#">动作操作</a>
						</div>

						<div class="navbar-form navbar-left" role="search">
			        <div class="form-group">
			          <input type="search" id="actionSearchInput" class="form-control" placeholder="筛选记录">
			        </div>
			        
			      </div>
			  
					</div>
				</div>
				-->
				
				<div class="panel panel-default " >
					
					<div data-role="content" class="col-lg-12" >	
	          <h5>货舱占用比</h5>	
	          <div id="_graph" data-theme="c" align="center">
							<div id="traysUse"> </div>
	        	</div>
	        </div>
        </div>

        <div class="panel panel-default " >
	        <div data-role="content" class="col-lg-12" >	
	          <h5>仓库吞吐量</h5>	
	          <div id="inOut_graph" data-theme="c" align="center">
						<div id="inOutStatics"></div>
	          	<p id="tips"></p>
	        	</div>
	        </div>
	      </div>

	      <div class="panel panel-default " >
	        <p>人员工作量
	        	<select id="stuffFilter">
	        		<option value="1 DAY">今天</option>
	        		<option value="1 WEEK">近一周</option>
	        		<option value="1 MONTH">近一月</option>
	        		<option value="1 YEAR">近一年</option>
	        		<option value="10 YEAR">全部</option>

	        	</select>
	        </p>
	        <div id="staffWork_Graph" align="center">
						<div id="staffWork_Statics"></div>
	          <p id="staffWork_tips"></p>
	        </div>

      	</div>
      </div>

		
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
		//agent.id = package.agentid
		//package.appid = p
		var userName = "<?php echo $_SESSION['user'];?>";
		var userID = "<?php echo $_SESSION['userID'];?>";
		/*
		var actionType = {"signedApp":"签署库单",
											"editAgent":"修改货代",
											"newAgent":"新建货代",
											"editApp":"修改库单",
											"newApp":"新建入库单",
											"newOutApp":"新建出库单",
											"trayUnload":"下货架",
											"trayLoad":"上货架",
											"unbindTray":"解除托盘",
											"bindTray":"绑定托盘",
											"loadStock":"装载货物",
											"unloadStock":"取出货物",
											"finAppIn":"完成入任务",
											"finAppOut":"完成出任务"


										};
		console.log("测试type");
		console.log( actionType["trayUnLoad"]);
		console.log(actionType);

		console.log(userName);
		outputHint("测试","这是一个输出和定时器<br>用户"+userName+"<br>代码"+userID,100);
		//显示列表
		var query  = "(SELECT * ";
		query 		+= "FROM wActions a, wUsers u ";
		query 		+= "WHERE a.actUserID=u.userID )";
		query 		+= " ORDER by a.actTime DESC;"	
		$.ajax({ 
	    type : "post", 
	    url : "_search.php?query="+query,
	    async : false, 
	    success : function(data){
	    	//console.log(data);
				var head = new Array("操作员","操作类型","入库编号","内容","时间");
				var attr = new Array("wuName","actType","InStockID","actContent","actTime");
				var obj = jQuery.parseJSON(data);
				var link = "wuName";
				//console.log(obj);
				$('#actionBoxContainer').html( FormPanelTable(obj,attr,head,link,"代理商列表"));
	    }
	  });
	 
			
		//增加过滤
		$('#actionSearchInput').on("keyup",function(event){
			//if(event.which == 13){
				var str = $('#actionSearchInput').val();

				$(".actionRow").each(function(){
					$(this).hide();
					if($(this).html().match(str)){
						$(this).show();
					}
				});

			//}
		});

			
		
		//生成表格形式 返回字符串
		function FormPanelTable(obj,attr,head,link,title){
			var output = "";
			output 	+= "<table class=\"table table-condensed table-hover table-responsive\" style=\"font-size:10px;\"";
			output 	+= "<thead>";
			for(var i in head){
				if(head[i]=="内容")
					output += "<th class=\"col-sm-4\">"+head[i]+"</th>";
				else
					output += "<th class=\"col-sm-2\">"+head[i]+"</th>";
			}
			output 	+= "</thead>";
			output 	+= "<tbody>";

			var previousAg = "";
			for(var i in obj){
				output += "<tr class=\"actionRow\">";
				for(var j in attr){
					
					//加跳转
					if(link == attr[j]){
						//console.log("相同");
						output += "<td  >"+obj[i][attr[j]]+"</td>";
					}
					else if("actContent" == attr[j]){
						//console.log("相同");
						output += "<td >"+obj[i][attr[j]]+"</td>";
					}
					else if("actType" == attr[j]){
						console.log(obj[i][attr[j]]);
						output += "<td >"+actionType[ obj[i][attr[j]]]+"</td>";
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
			return output;
		}
		
		function outputHint(title,content){
			var output = "<h4>"+title+"</h4>";
			output += "<p>"+content+"</p>";

			$('#agentOperateInfo').html(output).show();
			setTimeout("$('#agentOperateInfo').hide()",2000);
		}
		*/
	function outputHint(title,content,time){
		var output = "<h4>"+title+"</h4>";
		output += "<p>"+content+"</p>";

		$('#actionOperateInfo').html(output).show();
		setTimeout("$('#actionOperateInfo').hide()",time);
	}
	outputHint("统计","测试",100);
	console.log("test");
	loadGraph();

	function loadGraph(){
		console.log("graph-page图像载入");
		//仓位占用比图像
		$.post("_search.php?table=wTrays",function(data){
			var obj = jQuery.parseJSON(data);
			var traysUse = function(){
			var list = [[['空闲',0],['货架',0],['使用中',0]]];
				//console.log(list);
				
			$.each(obj,function(i,item){
				if(item.twStatus == "空闲"){
					list[0][0][1] = parseInt(list[0][0][1])+1;	
				}
				else if(item.twStatus == "货架")
				{
					list[0][1][1] = parseInt(list[0][1][1])+1;
				}
				else if(item.twStatus == "使用中")
				{
					list[0][2][1] = parseInt(list[0][2][1])+1;
				}
				
			});
			//console.log(list);
			return list;
		};
			 
	  var plot1 = $.jqplot('traysUse', [], {
			
			title:'托盘使用比',
			gridPadding: {top:50, bottom:40, left:0, right:0},
			seriesDefaults:{
				renderer:$.jqplot.PieRenderer, 
				trendline:{ show:false }, 
				rendererOptions: { padding: 8, showDataLabels: true }
			},
			legend:{
				show:true, 
				placement: 'outside', 
				rendererOptions: {
					numberRows: 1
				}, 
				location:'s',
				marginTop: '15px'
			},
			dataRenderer: traysUse 
		});
	});
		  
		  //吞吐量
		  var inPlot = [];
		  var outPlot = [];

		  

		  $.post("_search.php?table=wApplications",function(data){
				 var obj = jQuery.parseJSON(data);
				 //console.log("在这里");
				 //console.log(obj); 
				 
				 $.each(obj,function(i,item){
				     //console.log(item.appBookingDate);
					// console.log(item.appType);
					if(item.appType == "in"){
						inPlot.push([item.appBookingDate,Number(item.appCount)]);
					}
					if(item.appType == "out"){
						outPlot.push([item.appBookingDate,Number(item.appCount)]);
					}
				 });
				 //console.log(inPlot);
				 //console.log(outPlot);
				var plot2 = $.jqplot('inOutStatics', [inPlot,outPlot], {
				  title: '所有计划中的出入库', 
				  axes:{
			          xaxis:{
				          renderer:$.jqplot.DateAxisRenderer, 
				          tickOptions:{formatString:'%b&nbsp;%#d'},
				          min:'2014-01-01', 
				          tickInterval:'12 months'
				        }
			      },
					
				  highlighter: {
		            show: true,
		            sizeAdjust: 7.5
		          },
			      cursor: {
			        show: false
			      }
		  	});	


	  	});

		
		
	}


	$().ready(function(){
		var staffWorkRange = "1 YEAR";	
		staffWorkInGraph(staffWorkRange);
		$("#stuffFilter").on("change",function(){
			console.log($(this).val());
			$("#staffWork_Statics").html("");
			staffWorkRange = $(this).val();
			staffWorkInGraph(staffWorkRange);
		});
	});
	
	
	function staffWorkInGraph(staffWorkRange){
		console.log("staffWorkRange 搜索范围："+staffWorkRange);
	  //人员工作流 staffWork_Graph
	  query ="SELECT app.appID, app.appCount, app.appName, app.appType, act.actTime, user.wuName, act.actType ";
	  query += "FROM wActions act, wApplications app, wUsers user ";
	  query += "WHERE ";
	  //query += "(act.actTime > DATE_SUB(now(), INTERVAL "+staffWorkRange+")) AND ";
	  query += "user.userID=act.actUserID AND act.InStockID=app.InStockID AND ( act.actType = 'finAppIn' OR act.actType = 'finAppOut' )";

	  if(staffWorkRange == "1 DAY"){
	  	query += " AND  TO_DAYS(NOW()) - TO_DAYS(act.actTime) <= 1 " ;
	  }
	  else if( staffWorkRange == "1 WEEK"){
	  	query += " AND  TO_DAYS(NOW()) - TO_DAYS(act.actTime) <= 7 " ;
	  	
	  }
	  else if( staffWorkRange == "1 MONTH"){
	  	query += " AND  TO_DAYS(NOW()) - TO_DAYS(act.actTime) <= 31 " ;
	  	
	  }
	  else if( staffWorkRange == "1 YEAR"){
	  	query += " AND  TO_DAYS(NOW()) - TO_DAYS(act.actTime) <= 365 " ;
	  }
	  else if( staffWorkRange == "10 YEAR"){
	  	query += " AND  TO_DAYS(NOW()) - TO_DAYS(act.actTime) <= 3650 " ;
	  }


	  var name = [];
	  var workIn = [];
	  var workOut = [];
	  console.log(query);
	  $.ajax({ 
	    type : "post", 
	    url : "_search.php?query="+query,
	    async : false, 
	    success : function(data){
	    	//console.log(data);
				//var head = new Array("操作员","操作类型","入库编号","内容","时间");
				//var attr = new Array("wuName","actType","InStockID","actContent","actTime");
				var obj = jQuery.parseJSON(data);
				//var link = "wuName";
				//console.log(obj);
				//轮巡找到的数据
				for(var i in obj){
					//console.log(obj[i].wuName);
					//位置清零
					var find = -1;

					for(var j in name ){
						if(obj[i].wuName == name[j]){
							find = j;
						}
					}

					//console.log("找到的位置"+find+",数量："+obj[i].appCount);
					//找到
					if(find != -1){
						if( workIn[find] === undefined){
							workIn[find] = 0;
							workOut[find] = 0;
						}
						if(obj[i].appType == "in")
							workIn[find] += Number( obj[i].appCount);
						else if(obj[i].appType == "out")
							workOut[find] += Number( obj[i].appCount);
						
						//console.log(name[find]+":"work[])
					}
					else{
						var idx =name.length;
						name[idx] = obj[i].wuName;
						workIn[idx] = 0;
						workOut[idx] = 0;
						if(obj[i].appType == "in")
							workIn[idx] += Number( obj[i].appCount);
						else if(obj[i].appType == "out")
							workOut[idx] += Number( obj[i].appCount);
						
					}
				}
				//console.log(name);
				//console.log(workIn);
				//console.log(workOut);
				//$('#actionBoxContainer').html( FormPanelTable(obj,attr,head,link,"代理商列表"));
	    },
	    done : function(data){
	    	console.log(data);
	    }

  	});
   	//var s1 = [200, 600, 700, 1000];
    //var s2 = [460, -210, 690, 820];
    //var s3 = [-260, -440, 320, 200];
    // Can specify a custom tick Array.
    // Ticks should match up one for each y value (category) in the series.
    //var ticks = ['May', 'June', 'July', 'August'];
     
    var plotStuff = $.jqplot('staffWork_Statics', [workIn, workOut], {
        // The "seriesDefaults" option is an options object that will
        // be applied to all series in the chart.
        seriesDefaults:{
            renderer:$.jqplot.BarRenderer,
            rendererOptions: {fillToZero: true}
        },
        // Custom labels for the series are specified with the "label"
        // option on the series option.  Here a series option object
        // is specified for each series.
        series:[
            {label:'入库数量'},
            {label:'出库数量'}
        ],
        // Show the legend and put it outside the grid, but inside the
        // plot container, shrinking the grid to accomodate the legend.
        // A value of "outside" would not shrink the grid and allow
        // the legend to overflow the container.
        legend: {
            show: true,
            placement: 'outsideGrid'
        },
        axes: {
            // Use a category axis on the x axis and use our custom ticks.
            xaxis: {
                renderer: $.jqplot.CategoryAxisRenderer,
                ticks: name
            },
            // Pad the y axis just a little so bars can get close to, but
            // not touch, the grid boundaries.  1.2 is the default padding.
            yaxis: {
                pad: 1.05,
                tickOptions: {formatString: '$%d'}
            }
        },
        highlighter: {
	        show: true,
	        sizeAdjust: 7.5
	      },
	      cursor: {
	        show: false
	      }
    });
	}
	</script>
</html>