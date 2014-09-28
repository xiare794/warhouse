<?php require_once("config.php"); //session ?>
<!DOCTYPE html>
<html lang="cn">
  <head>
    <meta charset="utf-8">
    <title>仓库用户管理页</title>
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
	<?php include "header.php"; ?>
	

    <div class="container" style="padding-top: 80px;">
    	<?php
    		//var_dump($_POST);
    		include "_db.php";
    		global $connection;
    		if(!isset($_POST['admin'])) $_POST['admin'] = 0;
    		if(!isset($_POST['manage'])) $_POST['manage'] = 0;
    		if(!isset($_POST['operator'])) $_POST['operator'] = 0;
    		
    		
    		if(isset($_POST['newUser'])){
    			//检查重名;
    			$nameInvalid = false;
    			$newName = $_POST['userName'];
    			$query = "SELECT * FROM wUsers where `wuName` = \"".$newName."\"";
    			$result = mysqli_query($connection,$query);
    			if(!$result){
    			 echo '[we have a problem]: '.mysqli_error($connection);
    			 
    			}
    			else{
    				while( $row = mysqli_fetch_array($result) ){
    					$nameInvalid = true;
    				}
    			}
    			
    			
    			if(!$nameInvalid){ 
	    			$query = "INSERT INTO wUsers VALUES (null, ";
	    			$query .= "'".$_POST['userName']."',";
	    			$query .= "'".$_POST['userNamePY']."',";
	    			$query .= "'".$_POST['userPassword']."',";
	    			$query .= "'".$_POST['admin']."',";
	    			$query .= "'".$_POST['manage']."',";
	    			$query .= "'".$_POST['operator']."')";
	    			//echo $query;
	    			$result = mysqli_query($connection,$query);
	    			if(!$result){
	    			 echo '[we have a problem]: '.mysqli_error($connection);
	    			 //return "mysql error";
	    			}
	    			else
	    			 echo "新建成功";
	    		}
	    		else {
	    			echo "重名，新建失败";
	    		}
    		}
    		if(isset($_GET['delete'])){
    			$query = "DELETE FROM wUsers where `userID` = \"".$_GET['delete']."\"";
    			$result = mysqli_query($connection,$query);
    			if(!$result){
    			 echo '[we have a problem]: '.mysqli_error($connection);
    			 //return "mysql error";
    			}
    			else
    			 echo "删除成功";
    		
    		}
    	?>
    	<div class="row">
    		<!-- Modal -->
    		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    		  <div class="modal-dialog">
    		    <div class="modal-content">
    		      <div class="modal-header">
    		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    		        <h4 class="modal-title" id="myModalLabel">用户表单</h4>
    		      </div>
    		      <div class="modal-body" style="padding: 15px;">
    		        <form method="post" action="userManage.php">
    		        	<div class="input-group" style="padding-top: 10px;">
    		        		<span class="input-group-addon">姓名</span> 
	    		        	<input type="text" value="" class="form-control" name="userName" placeholder="输入用户名" >
	    	        	</div>
	    	        	<div class="input-group" style="padding-top: 10px;">
	    	        		<span class="input-group-addon">姓名全拼</span> 
	    	        		<input type="text" value="" class="form-control" name="userNamePY" placeholder="输入大写全拼" >
	    	        	</div>
	    	        	<div class="input-group" style="padding-top: 10px;">
	    	        		<span class="input-group-addon">密码</span> 
	    	        		<input type="password" value="" class="form-control" name="userPassword" placeholder="输入大写全拼" >
	    	        	</div>
	    	   
	    	           	<div class="input-group" style="padding-top: 10px;">
	    	           		<label class="checkbox" for="checkbox1" id="checkbox1lb">
	    	           		    管理员权限
	    	           		    <span class="icons"><span class="first-icon fui-checkbox-unchecked"></span><span class="second-icon fui-checkbox-checked"></span></span><input type="checkbox" name="admin" value="" id="checkbox1" data-toggle="checkbox">
	    	           		    
	    	           		 </label>    		
	    	           	</div>
	    	           	<div class="input-group" style="padding-top: 2px;">
	    	           		<label class="checkbox" for="checkbox2" id="checkbox2lb">
	    	           		    经理权限
	    	           		    <span class="icons"><span class="first-icon fui-checkbox-unchecked"></span><span class="second-icon fui-checkbox-checked"></span></span><input type="checkbox" name="manage" value="" id="checkbox2" data-toggle="checkbox">
	    	           		    
	    	           		 </label>    		
	    	           	</div>
	    	           	<div class="input-group" style="padding-top: 2px;">
	    	           		<label class="checkbox" for="checkbox2" id="checkbox3lb">
	    	           		    操作员权限
	    	           		    <span class="icons"><span class="first-icon fui-checkbox-unchecked"></span><span class="second-icon fui-checkbox-checked"></span></span><input type="checkbox" name="operator" value="" id="checkbox3" data-toggle="checkbox">
	    	           		    
	    	           		 </label>    		
	    	           	</div>
    		        	
    		        
    		      </div>
    		      <div class="modal-footer">
    		        <button type="button" class="btn btn-default" data-dismiss="modal">取消变更</button>
    		        <input type="submit" class="btn btn-primary" name="newUser" value="新建用户"></input>
    		      </div>
    		      </form>
    		    </div><!-- /.modal-content -->
    		  </div><!-- /.modal-dialog -->
    		</div><!-- /.modal -->
    		
    		<div class="col-md-8 col-xs-8 ">
    			<h4>用户管理</h4>
    			<?php
    				include "_db.php";
    				global $connection;
    				$Users = array();
    				$query = "SELECT * FROM `wUsers` WHERE 1";
    				$result = mysqli_query($connection, $query);
    				//var_dump($result);
    				if(!$result){
    				 echo '[we have a problem]: '.mysqli_error($connection);
    				}
    				while( $row = mysqli_fetch_array($result) ){
    					$userArray = array('userID'=>$row['userID'],
    										'wuName'=>$row['wuName'],
    										'wuNamePY'=>$row['wuNamePY'],
    										'wuPassword'=>$row['wuPassword'],
    										'admin'=>$row['admin'],
    										'manage'=>$row['manager'],
    										'operator'=>$row['operator']
    										);
    					array_push($Users,$userArray);
    				}
    				
    			?>
    			
    				<?php
    					for($i = 0; $i<count($Users); $i++){
    				?>		
    						
							<label class="radio" id="<?php echo $Users[$i]['userID'];?>" >
					            <span class="icons"><span class="first-icon fui-radio-unchecked"></span><span class="second-icon fui-radio-checked"></span></span><input type="radio" name="optionsRadios" id="optionsRadios2" value="option1" data-toggle="radio" checked="checked">
					        
					<?php
						echo "[".$Users[$i]['userID']."]".$Users[$i]['wuName']."      ";
						echo "权限{";
						if( $Users[$i]['admin'] == 1)
							echo "数据库管理员,";
						if( $Users[$i]['manage'] == 1)
							echo "经理,";
						if( $Users[$i]['operator'] == 1)
							echo "操作员";
						echo "}";
						echo "</label>";
					
					    					
						}
    				?>
    			<button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
    			  新建用户
    			</button>
    			
    			<button class="btn btn-primary btn-lg" id="deletebtn" >
    			  删除选中用户
    			</button>	
    			
    		</div>
    		
    		<div class="col-md-4 col-xs-4">
    			
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
    <script type="text/javascript">
    	$().ready( function () {
    		console.log($("#checkbox2").parent());
    		console.log($("#checkbox2").attr('checked'));
    		$('#checkbox1lb').change(function () {
    			if( $(this).hasClass('checked') )
    				$('#checkbox1').val(1);
    			else {
    				$('#checkbox1').val(0);
    			}
    		});
    		$('#checkbox2lb').change(function () {
    			if( $(this).hasClass('checked') )
    				$('#checkbox2').val(1);
    			else {
    				$('#checkbox2').val(0);
    			}
    		});
    		$('#checkbox3lb').change(function () {
    			if( $(this).hasClass('checked') )
    				$('#checkbox3').val(1);
    			else {
    				$('#checkbox3').val(0);
    			}
    		});
    		$('#deletebtn').click(function () {
    			console.log( $('.col-md-8 .checked').attr('id'));
    			location.href = "userManage.php?delete="+$('.col-md-8 .checked').attr('id');
    		});
       	} )
    </script>
  </body>
</html>
