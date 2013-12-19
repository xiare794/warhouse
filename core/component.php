

<?php
	//在UL内显示 需要appList提供的appID
	//范例如图<li class="list-group-item">Cras justo odio</li>
	include "coreFunction.php";
	
	if( isset($_GET['appID'] )){
		echo "<ul class=\"list-group\" id=\"appSignedUl\">";
		$theApp = getAPPbyID($_GET['appID']);
			echo "<li class=\"list-group-item\" id=\"appKey\" key=\"".$theApp['appID']."\">APPID：".$theApp['appID']."</li>";
			echo "<li class=\"list-group-item\">APP名称:".$theApp['appName']."</li>";
			echo "<li class=\"list-group-item\">货物包:".$theApp['wpID']."</li>";
			echo "<li class=\"list-group-item\">".$appTypeTextArray[$theApp['appType']]."</li>";
			echo "<li class=\"list-group-item\">目前在托盘#".$theApp['appFromTrayID']."上</li>";
			echo "<li class=\"list-group-item\">状态：".$statusTextArray[$theApp['appStatus']]."</li>";
			echo "<li class=\"list-group-item\">";
				if($theApp['appSignned'])
				echo "未签署";
			echo "</li>";
			echo "<li class=\"list-group-item alert-info\"> <div class=\"alert alert-info\">签署此页面意味着允许对货物包".$theApp['wpID']."进行".$appTypeTextArray[$theApp['appType']]."对应的操作</div></li>";
		echo "</ul>";
	}
	
	
	if(isset($_GET['sign'])){
		passApp($_GET['sign']);
	}
?>
	
