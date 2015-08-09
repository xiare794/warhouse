

<?php
	//在UL内显示 需要appList提供的appID
	//范例如图<li class="list-group-item">Cras justo odio</li>
	include "coreFunction.php";
	
	if( isset($_GET['appID'] )){
		$theApp = getAPPbyID($_GET['appID']);
		$theTrays = getTraysbyAppID($_GET['appID']);
		//var_dump($theTrays);
		
		
		echo "序号:".$theApp['appSeries'];
		echo "<span class=\"pull-right\">入库编号:<span id=\"AppSignBoxInStockID\">".$theApp['InStockID']."</span></span>";
		echo "<ul class=\"list-group\" id=\"appSignedUl\" key=\"".$theApp['appID']."\">"; //key属性确定了签署哪一页-->
			echo "<li class=\"list-group-item\">状态:<span class=\"pull-right\">";
				if($theApp['appStatus']==0)
				echo "未签署";
			  else
			  echo "未知";
			echo "</span></li>";

			echo "<li class=\"list-group-item alert-info\">时间:<span class=\"pull-right\">".$theApp["appBookingDate"]."</span></li>";
			echo "<li class=\"list-group-item\">送货人<span class=\"pull-right\">".$theApp["deliverComp"]."<code>司机".$theApp["deliverDriver"]."</code>".$theApp["deliverTruckID"]."</span></li>";
			//echo $theApp["appBookingDate"]."<br>";
			//echo "<code>".$theApp["deliverComp"]."</code><code>司机".$theApp["deliverDriver"]."</code><code>".$theApp["deliverTruckID"]."</code><br>";
			echo "<li class=\"list-group-item\">".$theApp["appPreCount"]."箱<span class=\"pull-right\">".$theApp["appName"]."请求入库</span></li>";
			
			
			

			//echo "<br>正在签署货物包<code>".$theApp['appName']."</code>的<span id=\"AppSignBoxAppType\">入库</span>操作</div></li>";
		echo "</ul>";
		echo "<code>签署人:".$_GET['userName']."</code>";
	}
	
	
	if(isset($_GET['sign'])){
		passApp($_GET['sign']);
	}
?>
	
