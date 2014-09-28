

<?php
	//在UL内显示 需要appList提供的appID
	//范例如图<li class="list-group-item">Cras justo odio</li>
	include "coreFunction.php";
	
	if( isset($_GET['appID'] )){
		$theApp = getAPPbyID($_GET['appID']);
		$theTrays = getTraysbyAppID($_GET['appID']);
		//var_dump($theTrays);
		
		
		echo "库单信息APP[".$theApp['appID']."]-WP[".$theApp['wpID']."]";
		echo "<span class=\"pull-right\">入库编号:<span id=\"AppSignBoxInStockID\">".$theApp['InStockID']."</span></span>";
		echo "<ul class=\"list-group\" id=\"appSignedUl\" key=\"".$theApp['appID']."\">"; //key属性确定了签署哪一页-->
			echo "<li class=\"list-group-item\">";
				if($theApp['appSignned'])
				echo "未签署";
			echo "</li>";
			echo "<li class=\"list-group-item alert-info\"> <code>".$_GET['userName']."</code><br>正在签署货物包<code>".$theApp['appName'].$theApp['wpID']."</code>的<span id=\"AppSignBoxAppType\">".$appTypeTextArray[$theApp['appType']]."</span>操作</div></li>";
		echo "</ul>";
	}
	
	
	if(isset($_GET['sign'])){
		passApp($_GET['sign']);
	}
?>
	
