<!DOCTYPE html>
<html lang="cn">
  <head>
  	<?php
  		include("_db.php");
  	?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="../css/bootstrap.css" rel="stylesheet">
    <title>维尔豪斯打印页面</title>
    <style type="text/css">
    td,th{ font-size: 9px}
    </style>
  </head>
  <body>
  	<div class="container-fluid">
  		<?php
  			if(!isset($_GET['appID'])){
  				//如果没有库单打印
  				echo "<!--";
  			}
  		?>
		  <div class="row">
		    <div class="col-xs-8 col-sm-8 col-md-8">
		    		<h1>通州仓储1号库 进库单</h1>
		    </div>
		    <div class="col-xs-4 col-sm-4 col-md-4">
		    		<p>注：入库完成后在调度中心索取有效回执单</>
		    </div>
		    <div class="col-xs-12 col-sm-12 col-md-12">
		    		<table class="table table-striped">
		    			<thead>
		    				<th>货代名</th>
		    				<th>类型</th>
		    				<th>联系方式</th>
		    				<th>地址</th>
		    				<th>联系人</th>
		    			</thead>
		    			
			    			<tr>
			    				<td>北京智通仁和科技发展有限公司</td>
			    				<td>海鲜虾</td>
			    				<td>010－82535697</td>
			    				<td>北京智通仁和科贸店科贸大厦3层3A003</td>
			    				<td>李刚</td>
			    			</tr>
		    			
		    			<div id="tableContent">

		    			</div>
		    		</table>



		    </div>
		  </div>

		  <?php
		  	if(!isset($_GET['appID'])){
  				//如果没有库单打印
  				echo "-->";
  			}
		  ?>
		</div><!-- row结束 -->

  </body>
  <script src="../js/jquery.js"></script>
  <script type="text/javascript">
  	var appID = "<?php echo $_GET['appID'];?>";
  	
		console.log("test"+appID);
  	$.post("_search.php?table=wApplications&&attr=appID&&val="+appID,function(data){
  		var obj = jQuery.parseJSON(data);
  		//console.log(obj);
  		var output = "";
  		var headL = "";
  		var valL = "";
  		var line = 0;
  		var cols = 5;
  		for(var key in obj[0]) {
  			if(!Number(key) && Number(key) != 0)
  			{//console.log(key +"-"+obj[0][key]);
	  			if(line == 0){
		  			headL = "<thead>";
		  			valL  = "<tbody><tr>";
	  			}
					headL += "<th>"+key+"</th>";
					valL += "<td>"+obj[0][key]+"</td>";
	  			if(line == cols){
	  				headL += "</thead>";
		  			valL  += "</tr></tbody>";
	  			}
	  			line ++;
	  			if(line == cols){
	  				line = 0;
	  				output += headL+valL;
	  			}
	  		}
  			//console.log(key+ "-" + obj[0][key]);
  		}
  		console.log(output);
  		$("table").append(output);
  		//$("table").refresh();

  	});
  </script>
</html>