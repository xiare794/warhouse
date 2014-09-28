<!DOCTYPE html>
<html lang="cn">
  <head>
    <meta charset="utf-8">
    <title>等待执行动作页</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Loading Bootstrap -->
    <link href="../css/bootstrap.css" rel="stylesheet">

    <!-- Loading Flat UI -->
    <link href="../css/flat-ui.css" rel="stylesheet">
    <link href="../css/flat-ui-demo.css" rel="stylesheet">

    <link rel="shortcut icon" href="../images/favicon.ico">
	<style>
			.bs-glyphicons {
			  padding: 0px;
			  list-style: none;
			  overflow: hidden;
			  margin: 0px;
			}
			.bs-glyphicons li {
			  float: left;
			  width: 120px;
			  height: 120px;
			  padding: 0px;
			  margin: 2px;
			  font-size: 12px;
			  text-align: center;
			  border: 1px solid #ddd;
			  list-style: none;
			  
			}
			.bs-glyphicons .glyphicon {
			  margin-top: 0px;
			  margin-bottom: 0px;
			  font-size: 20px;
			}
			.bs-glyphicons .glyphicon-class {
			  display: block;
			  text-align: center;
			}
			.bs-glyphicons li:hover {
			  background-color: rgba(86,61,124,.1);
			}
	</style>
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
    <![endif]-->
  </head>
  <body>
    <!-- /.container -->


    <div class="container">
    	<div class="row">
    		<div class="col-md-4 col-xs-4 ">
    			<h1 class="">待入库</h1>
    			<?php
    				include "coreFunction.php";
    				$taskPool = getSignedApp("in");
    				refreshTaskJson();
    				//验证人员
    				echo "<ul class=\"bs-glyphicons\">";
    					for($i=0; $i<count($taskPool); $i++){
    						echo "<li>任务".$i.":".$taskPool[$i]['appName']."--等待执行".$statusTextArray[$taskPool[$i]['appStatus']].$taskPool[$i]['appOperator'].$taskPool[$i]['appFromTrayID']."</li>";
    					}
    				echo "</ul>";
    				//验证托盘
    				// current time
    				echo date('o-F-d aG:i:s') . "\n";
    			?>
    		</div>
    		<div class="col-md-4 col-xs-4">
    			<h1 >待出库</h1>
    			<?php
       				$taskPool = getSignedApp("out");
    				//验证人员
    				if(count($taskPool)>0){
	    				echo "<ul class=\"bs-glyphicons\">";
	    					for($i=0; $i<count($taskPool); $i++){
	    						echo "<li>任务".$i.":".$taskPool[$i]['appName']."--等待执行".$statusTextArray[$taskPool[$i]['appStatus']].$taskPool[$i]['appOperator']."</li>";
	    					}
	    				echo "</ul>";
	    			}
	    			else {
	    				echo "<h4>尚无等待出库的货物</h4>";
	    			}
    			?>
    		</div>
    		<div class="col-md-4 col-xs-4" id="trayBind">
    			
    		
    		
    		</div>
	
	</div>

<!-- Load JS here for greater good =============================-->
    <script src="../js/jquery.js"></script>
    <script src="../js/jquery-ui-1.10.3.custom.min.js"></script>
    <script src="../js/jquery.ui.touch-punch.min.js"></script>
    <script src="../js/bootstrap.js"></script>
    <script src="../js/bootstrap-select.js"></script>
    <script src="../js/bootstrap-switch.js"></script>
    <script src="../js/flatui-checkbox.js"></script>
    <script src="../js/flatui-radio.js"></script>
    <script src="../js/jquery.tagsinput.js"></script>
    <script src="../js/jquery.placeholder.js"></script>
    <script language="JavaScript">
     function myrefresh() {
      window.location.reload(); 
     }
     setTimeout('myrefresh()',100000);
     //指定1秒刷新一次 
    </script>
    <script type="text/javascript">
    	$().ready( function () {
    		$.getJSON( "../trayBind.json", function( data ) {
    		  var items = [];
    		  $.each( data, function( key, val ) {
    		    items.push( "<li id='" + key + "'>" + val['deviceID'] + "</li>" );
    		  });
    		 
    		  $( "<ul/>", {
    		    "class": "my-new-list",
    		    html: items.join( "" )
    		  }).appendTo( "#trayBind" );
    		});
    	
    	
    	} )
    </script>
  </body>
</html>
