	<?php
	include_once("_db.php"); //连接数据库专用文件 
	include_once("functions_manage.php");
	//得到数据库
	if(isset($_GET['db']))
		$db=$_GET['db']; //获取页码 
	else
		$db = "warePackages";
	
	$tableItemCount = getTableLength($db); //总记录数
	//得到页码
	if(isset($_GET['page']))
		$page=$_GET['page']; //获取页码 
	else
		$page = 1;
	
	//$result = $db->select("article", "page", "cata=1"); 
	//$total = $db->db_num_rows($result); //总记录数 
	 
	$pagesize=10; //每页显示数 
	$pageCount=ceil($tableItemCount/$pagesize); //总页数
	
	
	if(isset($page)){
	   
	   $startPage=($page-1)*$pagesize;
	   $query = "SELECT * FROM `".$db."` ";
	   $query .= "LIMIT ".$startPage.",".$pagesize;
	   //$query .= " ORDER BY UNIX_TIMESTAMP(appBookingDate) DESC";
	   if ($result = mysqli_query($connection, $query)) {
	?>
    <?php
		//获取货代名称的列表
		$clientQuery = "SELECT * FROM `wAgents` ";
		$clientArray = array();
		if( $cResult = mysqli_query($connection, $clientQuery) )
		while($row = mysqli_fetch_array($cResult)){
			$clientArray[$row['waID']] = $row['waName'];
		}
		
		
	?>
	
	<?php if($db=="warePackages") { ?>
		<table class="table table-condensed table-hover " style="font-size:10px">
			<thead><th >ID</th><th>货代名称</th><th>进仓编号</th><th>简短备忘</th><th>操作</th></thead>
			<tbody>
				<?php
					while($row = mysqli_fetch_array($result)){
						
						//获取相同wpID的进仓编号的列表
						//echo "<br>"."row['wpID'] = ".$row['wpID']."<br>";
						$appQuery = "SELECT * FROM `wApplications` WHERE `wpID` = ".$row['wpID'];
						//$clientQuery = "SELECT * FROM `wAgents` ";
						//echo $appQuery;
						$inStockArray = array();
						if( $aResult = mysqli_query($connection, $appQuery) ){
						
							while($arow = mysqli_fetch_array($aResult)){
								$inStockArray[$arow['wpID']] = $arow['InStockID'];
								//var_dump($arow['InStockID']);// $arow['InStockID'];
							}
						}
						if(!$aResult){
						 	echo '[we have a problem]: '.mysqli_error($connection);
						}
						//echo "在哪？";
						//var_dump($inStockArray);
						
						echo "<tr>";
						echo "<td>".$row['wpID']."</td>";
						echo "<td>".$clientArray[$row['wpAgentID']]."</td>";
						//var_dump($inStockArray);
						echo "<td>".$inStockArray[(int)$row['wpID']]."</td>";
						echo "<td>".$row['wNotePrivate']."</td>";
						
						echo "<td><a class=\"showPackBtn\" id=\"".$row['wpID']."\">查看</a></td>";
						//echo "<td><a class=\"packEditBtn\" id=\"".$row['wpID']."\">修改</a></td>";
						
						echo "</tr>";
					}
				}
			}
				?>
				
			</tbody>
		</table>
	<?php 
		}
	?>
	<?php 
	if($db=="wApplications") { 
		if(isset($page)){ 
		   $startPage=($page-1)*$pagesize;
		   $OPT="";
		   if(isset($_GET['para'])){
				if($_GET['para'] == "AppUnSign"){
					$OPT = " WHERE `appSignned`=0";
				}
				if($_GET['para'] == "AppUnComp"){
					$OPT = " WHERE `appComplete`=0";
				}
				if($_GET['para'] == "AppIn"){
					$OPT = " WHERE `appType`= \"in\"";
				}
				if($_GET['para'] == "AppOut"){
					$OPT = " WHERE `appType`= \"out\"";
				}
				if($_GET['para'] == "AppMove"){
					$OPT = " WHERE `appType`= \"move\"";
				}
		   }
		   if(isset($_GET['key'])){
			if($_GET['key'] != null ){
				if($OPT == "")
					$OPT = " WHERE ";
				else
					$OPT .= " AND ";
					
				$OPT .= "`appName` like \"%".$_GET['key']."%\"";
				$OPT .= "or `INStockID` like \"%".$_GET['key']."%\"";
				$OPT .= "or `wpID` like \"%".$_GET['key']."%\"";
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
	   
	   
		   $query = "SELECT * FROM `".$db."`";
		   $query .= $OPT;
		   $query .= " ORDER BY `appBookingDate` DESC";
		   //getActual Count first
		   $rawCount = getTableLengthByQuery($query);
		   $query .=" LIMIT ".$startPage.",".$pagesize;
	   
	   //echo $query;
	   if ($result = mysqli_query($connection, $query)) {
	
		
	?>
		<table class="table table-condensed table-hover" style="font-size:10px;" count="<?php echo $rawCount; ?>" >
			<thead>
				<th>ID</th>
				<th>货包</th>
				<th>app名</th>
				<th>类型</th>
				<th>数量</th>
				<th>app时间</th>
				<th>完成</th>
				<th>签发</th>
				<th>操作</th>
				<th>编码</th>
				<th>唛头</th>
				<th>送货人信息</th>
				<th>特殊要求</th>
				<th>操作员</th>
				<th>所属托盘</th>
				<th>所属货架</th>
				<th>打印</th>

			</thead>
			<tbody>
				<?php
					while($row = mysqli_fetch_array($result)){
						echo "<tr>";
						echo "<td>".$row['appID']."</td>";
						echo "<td>".$row['wpID']."</td>";
						echo "<td>".$row['appName']."</td>";
						if($row['appType'] == "in"){
							echo "<td><span class=\"glyphicon glyphicon-import\" style=\"color:DarkOliveGreen \"></span></td>";
						}
						else if($row['appType'] == "out"){
							echo "<td><span class=\"glyphicon glyphicon-export\" style=\"color:DeepSkyBlue \"></span></td>";
						}
						else{
							echo "<td>---</td>";
						}
						//数量
						echo "<td>".$row['appCount']."</td>";
						//时间
						echo "<td>".$row['appBookingDate']."</td>";
						//echo "<td>".$row['appOperator']."</td>";
						
						
						//库单完成状态
						if($row['appComplete']==1)
							echo "<td>".$row['appComplete']."</td>";
							//echo "<td><span class=\"glyphicon glyphicon-check\" style=\"color:green\"></span></td>";
						else
							echo "<td><span class=\"glyphicon glyphicon-unchecked\" style=\"color:grey\"></span></td>";
						
						
						if($row['appSignned'] == 0)
							echo "<td><a id=\"".$row['appID']."\" data-toggle=\"modal\" data-target=\"#appSignedModal\"  class=\"btn btn-xs btn-success btn-signedApp\">签发</a></td>";
						else
							echo "<td><span class=\"glyphicon glyphicon-check\" style=\"color:green\"></span></td>";
						
						//修改按钮glyphicon-edit
						echo "<td><a id=\"".$row['appID']."\" href=\"#appOpPanel\" class=\"appOpPanelBtn btn btn-primary btn-xs\" > <span class=\"glyphicon glyphicon-edit\" style=\"color:white\"></span></a>";
						echo "<a id=\"".$row['appID']."\" href=\"#appOpPanel\" class=\"appOpPanelDetail btn btn-warning btn-xs\" > <span class=\"glyphicon glyphicon-zoom-in\" style=\"color:white\"></span></a>";
						echo "</td>";

						//编码
						echo "<td>".$row['InStockID']."</td>";
						//唛头
						echo "<td>".$row['appMaitou']."</td>";
						//送货人信息
						echo "<td>".$row['deliverComp']."/".$row['deliverTruckID']."/".$row['deliverDriver']."/".$row['deliverMobile']."</td>";
						//特殊要求
						echo "<td>".$row['extraInfo']."</td>";
						//特殊要求
						echo "<td>".$row['appOperator']."</td>";
						//货盘
						echo "<td>点击获取</td>";
						//货架
						echo "<td>点击获取</td>";
						//打印
						echo "<td>点击打印</td>";


						echo "</tr>";
					}
				}
			}
			?>
				
			</tbody>
		</table>
		<script>
			//js代码是为了获取库单部分准备的
			//执行：签署操作
			//执行：修改操作
			//执行：查询详细操作
			$('.btn-signedApp').click(function(){
			  //component.php + appID 是显示
				$('#modalSign').load("component.php?appID="+$(this).attr('id')+"&&userName="+userName);
				//addAgentMemo("signed");
				//签发按钮
				$('#signedBtn').click(function(){
					//显示提示
					var hint = "<div class=\"alert alert-success\" id=\"hintAlert\"><a href=\"#\" class=\"alert-link\">成功签发</a></div>";
					$('#appListHeader').append(hint);
					
					//增加备忘 actUserID,actType,(actTime),InStockID,actContent
					var actUserID = userID;
					var actType = "signedApp";
					var InStockID = $('#AppSignBoxInStockID').html();
					var type = $('#AppSignBoxAppType').html();
					var actContent = userName+"签署了入库编号"+InStockID+"的"+type+"操作";
					var actTime = "";
					var query = "_insertAct.php?actUserID="+actUserID+"&&actType="+actType+"&&InStockID="+InStockID+"&&actContent="+actContent+"&&actTime=";
					$.post( query,function(data){
						console.log(actContent+"的备忘已添加");
					});
					
					//签署动作，修改app签署属性 ;component.php + sign 是签署
					$('#hintAlert').load("component.php?sign="+$('#appSignedUl').attr('key'),function(){
						ReloadAppTxt();
					});
					//关闭弹出框
					$('#appSignedModal').modal('toggle');
					//刷新库单
					
				});
			});
			/*增加备忘
			function addAgentMemo(type){
				console.log("签发备忘");
				console.log($('#appKey').attr('key'));
				var query = "?actUserID=";//+userID+"&&actType="+type+"Agent"+"&&actTime=--&&actContent="+memo;
					console.log("_insertAct.php"+query);
					/*
					$.post( "_insertAct.php"+query,function(data){
						//console.log(data);
						outputHint("修改货代结果",data,3000);
					});
				//此处增加备忘
				//需要userID，actType actTime,#inStockID,#trayID,#trayID,#slotID,actContent
			}*/		

			addEditBtnEvent();
			//修改库单响应
			//需要更新，修改ui，修改对应数据，跳转页面
			function addEditBtnEvent(){
				
				//修改库单响应
				$('.appOpPanelBtn').click(function(){
					//修改对应数据
					$.post("_search.php?table=wApplications&&attr=appID&&val="+$(this).attr('id'),function(data){
						//console.log(data);
						var obj = jQuery.parseJSON(data);
						$('#createNewApp').html("修改"+obj[0].appID+"号货单");
						$('#appName').val(obj[0].appName);
						$('#InStockID').val(obj[0].InStockID);
						$('#appMaitou').val(obj[0].appMaitou);
						$('#appCount').val(obj[0].appCount);
						$('#formappID').val(obj[0].appID);
						$.post("_search.php?table=warePackages&&attr=wpID&&val="+obj[0].wpID,function(data){
							var obj = jQuery.parseJSON(data);
							//console.log(obj[0].wpAgentID);
							$('.selectpicker').val(obj[0].wpAgentID);
							//$('.selectpicker').selectpicker('val',obj[0].wpAgentID);
						});

						//修改ui
						$('#generateOutAppBtn').removeAttr("disabled");
						$('#generateOutAppBtn').removeClass("btn-default");
						$('#generateOutAppBtn').addClass("btn-primary");
						editAppShow();

						//页面跳转 ref
						jumpPage("#appOpPanel","#navEditAppBtn");
					});
				});


				$('.appOpPanelDetail').on("click",function(){
					console.log("查找货单"+$(this).attr('id'));
					//库单id= $(this).attr('id')
					//显示详细情况div = #appDetailBox
					//console.log( "appDetail.php?appID="+ $(this).attr('id') );
					$('#appDetailBox').load("appDetail.php?appID="+$(this).attr('id'));
					jumpPage("#appDetailBox","#navViewAppBtn");
				});
			}
	</script>
	<?php 
		}
	?>

	
	