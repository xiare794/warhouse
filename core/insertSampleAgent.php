<!DOCTYPE html>
<html lang="cn">
  <head>
    <meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html"; charset="utf-8">
  </head>
  <body>
<?php 
	include("_db.php");
	global $connection;
	$agents = array(
		array(
		    "name" => "大船金三元有限公司",
		    "type" => "电子产品",
		    "tel" => "0459－6343198",
		    "addr" => "杨春，让胡路区龙南裙楼30号",
		    "quan" => "100",
		),
		array(
		    "name" => "北京智通仁和科技发展有限公司",
		    "type" => "电子产品",
		    "tel" => "010－82535697",
		    "addr" => "北京智通仁和科贸店科贸大厦3层3A003",
		    "quan" => "100",
		),
		array(
		    "name" => "山东青岛探索科技有限公司",
		    "type" => "数码",
		    "tel" => "0532－3832367",
		    "addr" => "青岛市台东一路10号",
		    "quan" => "10",
		)
	);
	for( $i =0; $i<count($agents); $i++){
		$query = "INSERT INTO wAgents VALUES (null, 
			\"".$agents[$i]['name']."\", 
			\"".$agents[$i]['type']."\", 
			\"".$agents[$i]['tel']."\",
			\"".$agents[$i]['addr']."\",
			null,
			".(int)$agents[$i]['quan'].",null,null);";
		echo $query."<br>";
		$result = mysqli_query($connection, $query);
		if(!$result){
		 echo '[we have a problem]: '.mysqli_error($connection);
		}
		else{
			echo "<br>创建了一个代理";
		}
	}

?>

</body>
</html>