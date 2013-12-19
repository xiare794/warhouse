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
	   
	   
	   if ($result = mysqli_query($connection, $query)) {
	?>
	
	<?php if($db=="warePackages") { ?>
		<table class="table table-condensed table-hover table-bordered" style="font-size:10,">
			<thead><th>ID</th><th>名称</th><th>库单</th><th>托盘号</th><th>代理商</th><th>件数</th><th>重/体积</th><th>简短备忘</th><th>操作</th></thead>
			<tbody>
				<?php
					while($row = mysqli_fetch_array($result)){
						echo "<tr>";
						echo "<td>".$row['wpID']."</td>";
						echo "<td>".$row['wpFullName']."</td>";
						echo "<td>".$row['wpInAppID']."/".$row['wpMoveAppID']."/".$row['wpOutAppID']."</td>";
						echo "<td>".$row['wpCurrTrayID']."</td>";
						echo "<td>".$row['wpAgentID']."</td>";
						echo "<td>".$row['wpCount']."</td>";
						echo "<td>".$row['wpTotalWeight']."kg/".$row['wpVolume']."立方米</td>";
						echo "<td>".$row['wNotePrivate']."</td>";
						echo "<td><a class=\"packEditBtn\" id=\"".$row['wpID']."\">修改</a></td>";
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
		if(isset($page)){ 
	   $startPage=($page-1)*$pagesize;
	   $OPT="";
	   if(isset($_GET['para'])){
			if($_GET['para'] == "AppUnSign"){
				$OPT = " WHERE `appSignned`=0 ";
			}
			if($_GET['para'] == "AppUnComp"){
				$OPT = " WHERE `appComplete`=0 ";
			}
			if($_GET['para'] == "AppIn"){
				$OPT = " WHERE `appType`= \"in\" ";
			}
			if($_GET['para'] == "AppOut"){
				$OPT = " WHERE `appType`= \"move\" ";
			}
			if($_GET['para'] == "AppMove"){
				$OPT = " WHERE `appType`= \"out\" ";
			}
	   }
	   
	   
	   $query = "SELECT * FROM `".$db."`";
	   $query .= $OPT;
	   $query .="LIMIT ".$startPage.",".$pagesize;
	   
	   //echo $query;
	   if ($result = mysqli_query($connection, $query)) {
	
		if($db=="wApplications") { 
	?>
		<table class="table table-condensed table-hover table-bordered" style="font-size:10,">
			<thead>
				<th>ID</th>
				<th>货包</th>
				<th>app名</th>
				<th>类型</th>
				<th>预定</th>
				<th>app时间</th>
				<th>托盘</th>
				<th>状态</th>
				<th>完成</th>
				<th>签发状态</th>
				<th>操作</th>
			</thead>
			<tbody>
				<?php
					while($row = mysqli_fetch_array($result)){
						echo "<tr>";
						echo "<td>".$row['appID']."</td>";
						echo "<td>".$row['wpID']."</td>";
						echo "<td>".$row['appName']."</td>";
						echo "<td>".$row['appType']."</td>";
						echo "<td>".$row['appBookingDate']."</td>";
						echo "<td>".$row['appDate']."</td>";
						//echo "<td>".$row['appOperator']."</td>";
						echo "<td>".$row['appFromTrayID']."/".$row['appToTrayID']."</td>";
						echo "<td>".$row['appStatus']."</td>";
						echo "<td>".$row['appComplete']."</td>";
						echo "<td>".$row['appSignned']."</td>";
						if($row['appSignned'] == 0)
							echo "<td><a id=\"".$row['appID']."\" data-toggle=\"modal\" data-target=\"#appSignedModal\"  class=\"btn btn-xs btn-success\">签发</a></td>";
						else
							echo "<td>已签发</td>";
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
	
	