<?php
	//format
	//phpUpdate.php?table=tName&&idAttr=attr1&&idValue=val1&&tAttr=attr2&&tValue=val2
	
	include_once("_db.php");
	//var_dump($_GET);
	if(isset($_GET['table']) && isset($_GET['idAttr']) && isset($_GET['idValue']) && isset($_GET['tAttr']) && isset($_GET['tValue']) ){
		$query ="UPDATE `".$_GET['table']."` SET `".$_GET['tAttr']."`=\"".$_GET['tValue']."\"  WHERE `".$_GET['idAttr']."`=\"".$_GET['idValue']."\"";
		if ($result = mysqli_query($connection, $query)) {
			echo "1";
			
			//echo $query;
			//var_dump($result);
		}
		else{
			echo $result;
			echo "0";
		}
	}
	else{
		echo $result;
		echo "0";	
	}
	
?>