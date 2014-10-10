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


		<title>代理商</title>
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
	</head>
	<body >
		<!-- action container -->
			<div id="actionBody" class="row" >
				<!--<div class="bs-callout bs-callout-warning col-lg-6" id="actionOperateInfo" style="position:fixed; opacity:0.9; z-index:99">
					<h4>测试警告</h4>
					<p>这里提供一些刷新的信息，2秒后消失</p>
					<?php var_dump($_SESSION); ?>
				</div>-->
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

				
				<div class="panel panel-default " id="actionBoxContainer">
					
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
		var actionType = {	"signedApp":"签署库单",
							"editAgent":"修改货代",
							"newAgent":"新建货代",
							"editApp":"修改库单",
							"deleteApp":"删除库单",
							"newApp":"新建入库单",
							"newOutApp":"新建出库单",
							"trayUnload":"下货架",
							"trayLoad":"上货架",
							"unbindTray":"解除托盘",
							"bindTray":"绑定托盘",
							"loadStock":"装载货物",
							"unloadStock":"取出货物",
							"finAppIn":"完成入任务",
							"finAppOut":"完成出任务",
							"traysIn":"入门操作",
							"traysOut":"出门操作"
						};
		console.log("测试type");
		console.log( actionType["traysIn"]);
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
				var head = new Array("操作员","操作类型","入库编号","时间","内容");
				var attr = new Array("wuName","actType","InStockID","actTime","actContent");
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
			console.log(obj);
			var output = "";
			output 	+= "<table class=\"table table-condensed table-hover table-responsive\" style=\"font-size:85%;\"";
			output 	+= "<thead>";
			for(var i in head){
				if(head[i]=="内容")
					output += "<th>"+head[i]+"</th>";
				else
					output += "<th>"+head[i]+"</th>";
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
					else if("actTime" == attr[j]){
						//console.log("相同");
						output += "<td >"+obj[i][attr[j]]+"</td>";
					}
					else if("actType" == attr[j]){
						console.log(obj[i][attr[j]]);
						output += "<td >"+actionType[ obj[i][attr[j]]]+"</td>";
					}
					else{
						output += "<td >"+obj[i][attr[j]]+"</td>";
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
		/*
		function outputHint(title,content){
			var output = "<h4>"+title+"</h4>";
			output += "<p>"+content+"</p>";

			$('#agentOperateInfo').html(output).show();
			setTimeout("$('#agentOperateInfo').hide()",2000);
		}*/
		function outputHint(title,content,time){
			var output = "<h4>"+title+"</h4>";
			output += "<p>"+content+"</p>";

			$('#actionOperateInfo').html(output).show();
			setTimeout("$('#actionOperateInfo').hide()",time);
		}


		
	</script>
</html>