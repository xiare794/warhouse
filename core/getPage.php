<?php
	include_once("_db.php"); //连接数据库专用文件 
	include_once("functions_manage.php");
	$users = getUser();

	//var_dump($users);
	//得到数据库 目前就AppIn用
	if(isset($_GET['db']))
		$db=$_GET['db']; 
	else
		$db = "none";
	
	//$tableItemCount = getTableLength($db); //总记录数
	
	//得到页码
	if(isset($_GET['page']))
		$page=$_GET['page']; //获取页码 
	else
		$page = 1;
	$pagesize=10; //每页显示数 
	//$pageCount=ceil($tableItemCount/$pagesize); //总页数
	
	
	
	if($db=="wAppIn") {
		
		if(isset($page)){ 
		   $startPage=($page-1)*$pagesize;
		   $OPT="";
		   if(isset($_GET['para'])){
					if($_GET['para'] == "AppUnSign"){
						$OPT = " WHERE `appStatus`=0";
					}
					if($_GET['para'] == "AppUnComp"){
						$OPT = " WHERE `appStatus`<3";
					}
					//if($_GET['para'] == "AllApp"){
					//	$OPT = " WHERE ";
					//}
				
		   }
		   if(isset($_GET['key'])){
				if($_GET['key'] != null ){
					if($OPT == "")
						$OPT = " WHERE ";
					else
						$OPT .= " AND ";
						
					$OPT .= "`appName` like \"%".$_GET['key']."%\"";
					$OPT .= "or `INStockID` like \"%".$_GET['key']."%\"";
					//$OPT .= "or `wpID` like \"%".$_GET['key']."%\"";
					$OPT .= "or `appMaitou` like \"%".$_GET['key']."%\"";
				}
		   }
		   
		   if(isset($_GET['from']) && isset($_GET['to'])){
				if( ($_GET['from'] != null ) && ($_GET['to'] != null ) ){
					if($OPT == "")
						$OPT = " WHERE ";
					else
						$OPT .= " AND ";
					$OPT .= " `appBookingDate` BETWEEN \"".$_GET['from']."\" AND \"".$_GET['to']."\"";
				}
		   }
		   
		   //筛选完成
		   if(isset($_GET['filterComplete'])){
		   		if($_GET['filterComplete'] == "true"){
		   			if($OPT == "")
		   				$OPT = " WHERE ";
	   				else {
	   					$OPT .= " AND "	;
	   				}
	   				$OPT .= " `appStatus` < 3 ";
		   		}
		   }
	   
	   
		   $query = "SELECT * FROM `".$db."`";
		   $query .= $OPT;
		   $query .= " ORDER BY `appBookingDate` DESC";
		   //getActual Count first
		   $itemCount = getTableLengthByQuery($query);
		   $pageCount=ceil($itemCount/$pagesize); //页数
		   $query .=" LIMIT ".$startPage.",".$pagesize;
	   
	   //echo $query;
	   if ($result = mysqli_query($connection, $query)) {
				?>
		<table class="table table-condensed table-hover" style="font-size:85%;" count="<?php echo $itemCount; ?>" >
			<thead>
				<td>状态</td>
				<th class="col-md-2">进仓编号</th>
				<th class="col-md-1">送货单位</th>
				<th class="col-md-1">货名</th>
				<th class="col-md-1">数量</th>
				<th class="col-md-1">app时间</th>
				
				<th>操作员</th>
				<th>操作</th>
				<th>所属托盘</th>
			</thead>
			<tbody>
				<?php
					while($row = mysqli_fetch_array($result)){ //0未签署,1签署进仓未完成,2进仓完成,3入库单完成，4出库完成
						echo "<tr>";
						if ($row["appStatus"] == 0) //未签署
							echo "<td>等签署</td>";//"class=\"warning\" ";
						else if ( ($row["appStatus"] == 1) || ($row["appStatus"] == 2)) //已签署进仓未完成
							echo "<td>入库中</td>";//"class=\"danger\" ";
						else if ($row["appStatus"] == 3 ) //已完成入库
							echo "<td>完成</td>";//"class=\"success\" ";
						else if ($row["appStatus"] == 4 ) //已完成入库
							echo "<td class=\"info\">已出库</td>";//"class=\"success\" ";
						else
							echo "<td></td>";
						//进仓编号
						echo "<td >".$row['InStockID']."</td>";
						//送货单位
						echo "<td>".$row['deliverComp']."</td>";
						//货品名
						echo "<td>".$row['appName']."</td>";
						//数量
						echo "<td>".$row['appPreCount']."</td>";

						//时间
						echo "<td>".explode(" ",$row['appBookingDate'])[0]."</td>";
						
						//库单完成状态
						/*
						if( $_GET['filterDetail']=="false")
							if($row['appStatus']==3)
								echo "<td>完成</td>";
								//echo "<td><span class=\"glyphicon glyphicon-check\" style=\"color:green\"></span></td>";
							else
								echo "<td><span class=\"glyphicon glyphicon-unchecked\" style=\"color:grey\"></span></td>";
						*/
						
						if($row['appStatus'] == 0)
							echo "<td><a id=\"".$row['appID']."\" data-toggle=\"modal\" data-target=\"#appSignedModal\"  class=\"btn btn-xs btn-success btn-signedApp\">签发</a>";
						else
							echo "<td><span style=\"width:42px\" class=\"glyphicon glyphicon-check\" style=\"color:green\"></span>";
						
						//修改按钮glyphicon-edit
						echo "<a id=\"".$row['appID']."\" href=\"#appOpPanel\" class=\"appOpPanelBtn btn btn-primary btn-xs\" > <span class=\"glyphicon glyphicon-edit\" style=\"color:white\"></span></a>";
						echo "<a id=\"".$row['appID']."\" href=\"#appOpPanel\" class=\"appOpPanelDetail btn btn-warning btn-xs\" > <span class=\"glyphicon glyphicon-zoom-in\" style=\"color:white\"></span></a>";
						echo "<a href=\"#\" appId = \"".$row['appID']."\" class=\"btn btn-success btn-xs ws-print\" > <span class=\"glyphicon glyphicon-print\" ></span></a>";
						if($row['appStatus'] == 2)
						echo "<a href=\"#\" appId = \"".$row['appID']."\" class=\"btn btn-success btn-xs ws-fullComplete\" > <span class=\"glyphicon glyphicon-ok\" ></span></a>";
							
						echo "</td>";

						
						//操作员
						echo "<td>".$users[$row['OpInput']]["wuName"]."</td>";
						//货盘
						echo "<td><a key=\"".$row['appID']."\" class=\"getTrays btn btn-primary btn-xs\">点击获取</a></td>";
						//唛头
						/*
						if( $_GET['filterDetail']=="false")echo "<td>".$row['appMaitou']."</td>";
						//送货人信息
						if( $_GET['filterDetail']=="false")echo "<td>".$row['deliverComp']."/".$row['deliverTruckID']."/".$row['deliverDriver']."/".$row['deliverMobile']."</td>";
						//特殊要求
						if( $_GET['filterDetail']=="false")echo "<td>---</td>";
						
						//货架
						if( $_GET['filterDetail']=="false")echo "<td>点击获取</td>";
						//打印
						if( $_GET['filterDetail']=="false")echo "<td><a class=\"appOpPrint btn\" appID=\"".$row['appID']."\" >打印</a></td>";
						*/

						echo "</tr>";
					}
				}// if isset(Page)
			}//
			?>
				
			</tbody>
		</table>
		<nav>
		  <ul class="pagination" style="display: table;width: auto;margin-left: auto;margin-right: auto" >
		    <li class="disabled"><a href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
		    <li class="active"><a href="#">1 </a></li>
		    <li ><a href="#">2 <span class="sr-only"></span></a></li>
		    <li><a href="#" aria-label="Next"><span aria-hidden="true">»</span></a></li>
		  </ul>
		</nav>
		<script>
			//打印库单
			//开启新窗口，穿过appID appID作为参数
			$('.appOpPrint').on("click",function(data){
				var appID = $(this).attr('appID');
				var url = "printable.php";//options.url + ( ( options.url.indexOf('?') < 0 && options.data != "" ) ? '?' : '&' ) + options.data;
		    var thePopup = window.open( "printable.php?appID="+appID, "_blank", "menubar=0,location=0,height=595,width=842,resizeable=no",true);
				var thePopup = window.open( "printable.php?appID="+appID, "_blank", "menubar=0,location=0,height=595,width=842,resizeable=no",true);

			});

			$(document).ready(function(){
				//getpage完成后
				var pageCount = <?php echo $pageCount; ?>; //目前符合项
				var currentPage = appFilter.page;
				//每次重新获取pagination
				var liStr = "<li><a href=\"#\" aria-label=\"Previous\"><span aria-hidden=\"true\">&laquo;</span></a></li>"
				for(var i = 1; i <pageCount+1; i++){
					liStr += "<li><a href=\"#\">"+i+"</a></li>";
				}
				liStr += "<li><a href=\"#\" aria-label=\"Next\"><span aria-hidden=\"true\">»</span></a></li>";
				
				$(".pagination").html(liStr);
				//每次刷新disable 和active
				if(currentPage == 1){
					$(".pagination li:first").addClass("disabled");
					$(".pagination li:eq(1)").addClass("active");
				}
				else if(currentPage < pageCount){
					$(".pagination li:eq("+currentPage+")").addClass("active");
				}
				else if(currentPage == pageCount){
					$(".pagination li:last").addClass("disabled");
					$(".pagination li:eq("+pageCount+")").addClass("active");
				}


				//跳转代码
				$(".pagination a").click(function(){
					//如果是内容aria-label Preview或Next跳转前页或后页

					//否则直接获取
					appFilter.page = $(this).html();
					ReloadAppTxt();
					//
				});
			});
			//appFilter.page =2;
			//console.log("getPage内打印---"+appFilter.printInfo());

			//js代码是为了获取库单部分准备的
			//执行：签署操作
			//执行：修改操作
			//执行：查询详细操作
			$('.btn-signedApp').click(function(){
			  //component.php + appID 是显示
				$('#modalSign').load("component.php?appID="+$(this).attr('id')+"&&userName="+userName);
				//addAgentMemo("signed");
				//签发按钮
				$('#signedBtn').unbind("click").click(function(){
					console.log("签发"+$('#appSignedUl').attr('key'));
					
					//增加备忘 actUserID,actType,(actTime),InStockID,actContent
					var actUserID = userID;
					var actType = "signedApp";
					var InStockID = $('#AppSignBoxInStockID').html();
					var type = $('#AppSignBoxAppType').html();
					var actContent = userName+"签署了入库编号"+InStockID+"的"+type+"操作";
					var actTime = "";
					var query = "_insertAct.php?actUserID="+actUserID+"&&actType="+actType+"&&InStockID="+InStockID+"&&actContent="+actContent+"&&actTime=";
					
					//$.post( query,function(data){
					//	addRemind("库单签署",actContent,5000,"bs-callout-info");
					//});
					$.ajax({ 
	          type : "post", 
	          url :  query, 
	          async : false, 
	          success : function(data){ 
	          	console.log("post"+query+"|||||data"+data);
	            addRemind("库单签署",actContent,5000,"bs-callout-info");

	          } 
          }); 
					
					//签署动作，修改app签署属性 ;component.php + sign 是签署
					//原来是打印至hintAlert 改为屏幕提示
					$.ajax({ 
	          type : "post", 
	          url :  "component.php?sign="+$('#appSignedUl').attr('key'), 
	          async : false, 
	          success : function(data){ 
	          	addRemind("库单记录添加",data,5000,"bs-callout-info");
	          } 
	        });
	        ReloadAppTxt();
					//$.post("component.php?sign="+$('#appSignedUl').attr('key'),function(data) {
					//	addRemind("库单记录添加",data,5000,"bs-callout-info");

					//});
					
					//$('#hintAlert').load("component.php?sign="+$('#appSignedUl').attr('key'),function(){
					//	ReloadAppTxt();
					//});
					//关闭弹出框
					$('#appSignedModal').modal('hide');
					
					
					
				});
			});
			

			addEditBtnEvent();
			//修改库单响应
			//需要更新，修改ui，修改对应数据，跳转页面
			function addEditBtnEvent(){
				
				//修改库单响应
				
				$('.appOpPanelBtn').click(function(){
					//修改对应数据
					
					$.post("_search.php?table=wAppIn&&attr=appID&&val="+$(this).attr('id'),function(data){
						console.log(data);
						var obj = jQuery.parseJSON(data);
						$('#createNewApp').html("修改"+obj[0].appID+"号货单");

						//修改对应
						$("#AppEditModalForm #appSeries").val(obj[0].appSeries);
						//货代
						$('#AppEditModalForm #InStockID').val(obj[0].InStockID);
						$('#AppEditModalForm #appName').val(obj[0].appName);
						$('#AppEditModalForm #appPreCount').val(obj[0].appPreCount);
						$("#AppEditModalForm #appBookingDate").val(obj[0].appBookingDate);

						$("#AppEditModalForm #deliverComp").val(obj[0].deliverComp);
						$("#AppEditModalForm #deliverDriver").val(obj[0].deliverDriver);
						$("#AppEditModalForm #deliverMobile").val(obj[0].deliverMobile);
						$("#AppEditModalForm #deliverTruckID").val(obj[0].deliverTruckID);
						$("#AppEditModalForm #deliverReceipt").val(obj[0].deliverReceipt);

						var options = {
							"backdrop":true,
							"keyboard":true,
							"show":true
						}
						$("#AppEditInBlock").modal(options);
						$('#AppEditInBlock').unbind("hidden.bs.modal").on('hidden.bs.modal', function (e) {
						  // do something...
						  console.log("modal 重新增加关闭事件");
						});
					});

				});
				

				$('.appOpPanelDetail').on("click",function(){
					console.log("查找货单"+$(this).attr('id'));
					//库单id= $(this).attr('id')
					//显示详细情况div = #appDetailBox
					console.log( "appDetail.php?appID="+ $(this).attr('id') );
					$('#appDetailBox').load("appDetail.php?appID="+$(this).attr('id'));
					jumpPage("#appDetailBox","#navViewAppBtn");
				});
				
				$('.getTrays').on("click",function() {
					console.log( $(this).attr("key"));
					$(".navBlock").hide();
					$($('#trayBox')).show();
					$("#trayBox").load("trays.php?trayKey="+$(this).attr("key"));
				});

				$(".ws-print").on("click",function(){
					console.log($(this).attr("appId"));
					showModelDialog("_print_appin.php?appID="+$(this).attr("appId"),210,299);

					// showModelDialog("_print.php?appId="+$(this).attr("appId"),210,299)

				});

				
				$(".ws-fullComplete").on("click",function(){
					console.log($(this).attr("appId"));
					$.get("coreFunction.php",
						{FullCompleteAppIn:$(this).attr("appId")},
						function(data){
						addRemind("库单检查后完成",data,5000,"bs-callout-info");
						//alert(data);
					});
				});

			}

			function showModelDialog(page,width,height){
				  console.log(width + height);
				  var obj = new Object();
          obj.name="51js";
			    window.showModalDialog(page,obj,"dialogWidth=1090px;dialogHeight=794px");
			    //if(re==1){
			    //    window.location.reload();
			    //}
			}


	</script>
	<?php 
		}
	?>

	
	