<?php 
  session_start(); //session
  
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
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="">
    <meta name="author" content="ref">
    
    <title>WareHouse 登陆</title>

    <link rel="stylesheet" href="jquery-mobile/jquery.mobile-1.4.0.min.css" />
    <link rel="stylesheet" href="jquery-mobile/jqueryMobileRoller1.0.css" />
    <script class="include" type="text/javascript" src="jquery-mobile/jquery-1.9.1.js"></script>
    <script src="jquery-mobile/jquery.mobile-1.4.0.min.js"></script> 

  </head>
  <body>
    <div data-role="page" id="page" data-theme="a">
      <form method="post" action="login.php">
        <div data-role="header" data-theme="a">
          <h1 class="left">仓储登陆</h1>
        </div>
        <div data-role="content" data-position="fixed" >
          
            <div data-role="fieldcontain">
              <label for="user" class="">用户：</label>
              <input type="text" name="login-name" id="user" placeholder="填写用户名...">
              <label for="password" class="">密码：</label>
              <input type="password" name="login-pass" id="password" placeholder="输入密码">
              
          
          <div id="loginHint">
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


        </div>

        <div data-role="footer" data-position="fixed" >
          <input type="submit" href="#" name="login" value="登陆">


      </div>
    </form>
    
    </div>
  </body>
  <script type="text/javascript">
    $().ready( function () {
      
      <?php
        if(isset($_SESSION['user'])){
          if( $_SESSION['user'] != ""){
              echo "window.location.href = \"enter.php\"";
            
          }
        }
        
      ?>
    })
  </script>
</html>