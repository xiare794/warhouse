<?php 
	require_once("config.php"); //session
	
	if(isset($_GET['logout'])){
		
		if($_GET['logout'] == true){
			$_SESSION['user'] = "";
			$_SESSION['userID'] = "";
			echo "<meta HTTP-EQUIV=\"REFRESH\" content=\"0; url=login.php\">";	
		}
	}	
	
?>
<!DOCTYPE html>
<html lang="cn">
  <head>
    <meta charset="utf-8">
    <title>维尔豪斯登陆页面</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Loading Bootstrap -->
    <link href="../css/bootstrap.css" rel="stylesheet">

    <!-- Loading Flat UI 
    <link href="../css/flat-ui.css" rel="stylesheet">
    <link href="../css/flat-ui-demo.css" rel="stylesheet">-->
    <style>
    .login-icon img,h4,h1{
    	display: inline-block;
    	font-size: 30px;
    	line-height: 1px;
    }
    .login{
    	width: 400px;
    	margin: 0 auto;
    }

    .macInput {
		  display: block;
		  border: none;
		  border-radius: 20px;
		  padding: 5px 8px;
		  color: #333;
		  box-shadow: 
		    inset 0 2px 0 rgba(0,0,0,.2), 
		    0 0 4px rgba(0,0,0,0.1);
		}

		.macInput:focus { 
		  outline: none;
		  box-shadow: 
		    inset 0 2px 0 rgba(0,0,0,.2), 
		    0 0 4px rgba(0,0,0,0.1),
		    0 0 5px 1px #51CBEE;
		}

		.inline-link-2 {
		  display: inline-block;
		  border-bottom: 1px dashed rgba(0,0,0,0.4);

		  /* Font styles */
		  text-decoration: none;
		  color: #777;
		}

		.inline-link-2:hover   { border-bottom-style: dotted; }
		.inline-link-2:active  { border-bottom-style: solid; }
		.inline-link-2:visited { border-bottom: 1px solid #97CAF2; }

    </style>

  </head>
<body>	
	<div class="container" style="padding-top: 10px">
	  <div class="login">
        <div class="login-screen">
          <div class="login-icon">
            <img src="../images/bitbucket-logo.png" alt="Welcome to Mail App">
            <h4>RFID系统<br/><hr> <small>欢迎登陆</small></h4>
          </div>
				  <form method="POST" action="login.php">
		          <div class="login-form">
		            <div class="form-group">
		            	<label class="login-field-icon fui-user" for="login-name">用户名</label>
		              <input type="text" class="form-control login-field macInput" value="" placeholder="输入姓名" name="login-name" id="login-name">
		              
		            </div>
		
		            <div class="form-group">
		            	<label class="login-field-icon fui-lock" for="login-pass">密码</label>
		              <input type="password" class="form-control login-field macInput" value="" placeholder="输入密码" name="login-pass" id="login-pass">
		            </div>
		            <div id="hint" style="color:gray;margin-bottom:15px" class="inline-link-2" >
		            	<?php
						      	//var_dump($_POST);
						      	include "_db.php";
						      	global $connection;
						      	//echo "POST<br>";
						      	//var_dump($_POST);
						      	if(isset($_POST["login-name"])){
							      	if( $_POST["login-name"] != ""){
								      	$query = "SELECT `wuPassword`,`userID` FROM wUsers WHERE `wuName`= \"".$_POST["login-name"]."\"";
								      	$result = mysqli_query($connection, $query);
								      	if(!$result){
								      	 echo '[we have a problem]: '.mysqli_error($connection);
								      	 echo "<br>服务器连接出错";
								      	}
								      	else{
								      		

									      	while($row = mysqli_fetch_array($result)){
									      		//var_dump($row);
									      		//var_dump($_POST['login-pass']);
									      		if( $row['wuPassword'] == $_POST['login-pass']){
									      			$_SESSION['user'] = $_POST['login-name'];
									      			$_SESSION['userID'] = $row['userID'];
									      			echo "登陆成功";
									      		}
									      	}
									      	if( mysqli_num_rows($result) == 0){
									      		echo "没有找到该用户:".$_POST["login-name"];
									      	}
									      	else{
									      		echo "用户密码不匹配";
									      	}
									      }
								    	}
								    	else{
								    		echo "请输入用户名及密码";
								    	}
										}
						      ?>
		            </div>
		
		            <input type="submit" class="btn btn-primary btn-lg btn-block" href="#" name="login" value="登陆">
		            
		          </div>
			      </form>
          </div>
        </div>
      </div>
      
    </div>
<!-- Load JS here for greater good =============================-->
  <script src="../js/jquery.js"></script>
  <!-- 
  <script src="../js/jquery-ui-1.10.3.custom.min.js"></script>
  <script src="../js/jquery.ui.touch-punch.min.js"></script>
  <script src="../js/bootstrap.js"></script>
  <script src="../js/bootstrap-select.js"></script>
  <script src="../js/bootstrap-switch.js"></script>
  <script src="../js/flatui-checkbox.js"></script>
  <script src="../js/flatui-radio.js"></script>
  <script src="../js/jquery.tagsinput.js"></script>
  <script src="../js/jquery.placeholder.js"></script>
-->
  <script type="text/javascript">
  	$().ready( function () {
  		
  		<?php
  			if(isset($_SESSION['user'])){
  				if( $_SESSION['user'] != ""){
	  				if( isset($_SESSION['previewPage'])){
	  					echo "window.location.href = \"".$_SESSION['previewPage']."\"";
	  				}
	  				else {
	  					echo "window.location.href = \"wPackage.php\"";
	  				}
  				}
  			}
  			
  		?>
  	})
  </script>
</body>
</html>