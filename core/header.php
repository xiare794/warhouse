<!-- Fixed navbar -->
      <style>
        .navbar{
          height:25px;
          min-height: 40px;
        }
        .navbar #logo{
          font-size: 20px;
          padding:5px 25px;
          
        }
        .navbar .nav > li > a{
          padding: 9px 20px;
        }
        .navbar .navbar-brand{
          padding: 5px 20px;
        }
        .navbar .navbar-form{
          padding: 0px 10px;
          max-height: 150px;
          font-size: 12px;
        }
        .navbar .navbar-form .form-control{
          font-size: 10px;
          max-width: 100px;
          height: 30px;
          margin: 5px;
          padding: 1px;
          width: 90px;
        }
      </style>
      <div class="navbar navbar-default navbar-fixed-top">
        
          <a class="navbar-brand" id="logo" href="_applications.php">WareHouse管理Web版</a>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav ">
			<li><a href="wPackage.php">管理总览</a></li>
			
            <li class="dropdown">
              <a href="#" id="drop2" role="button" class="dropdown-toggle" data-toggle="dropdown">网站地图 <b class="caret"></b></a>
              <ul class="dropdown-menu" role="menu" aria-labelledby="drop2">
                <li role="presentation"><a role="menuitem" tabindex="-1" href="_agents.php">货代</a></li>
				<li role="presentation"><a role="menuitem" tabindex="-1" href="_package.php">货物包</a></li>
                <li role="presentation"><a role="menuitem" tabindex="-1" href="_applications.php">表单(出入库单)</a></li>
                <li role="presentation"><a role="menuitem" tabindex="-1" href="#">货架</a></li>
                <li role="presentation" class="divider"></li>
                <li role="presentation"><a role="menuitem" tabindex="-1" href="http://twitter.com/fat">托盘</a></li>
                <li role="presentation"><a role="menuitem" tabindex="-1" href="../mobile/enter.html">移动版</a></li>
                <li role="presentation"><a role="menuitem" tabindex="-1" href="deviceList.php">手持设备</a></li>
                <li role="presentation"><a role="menuitem" tabindex="-1" href="userManage.php">用户管理</a></li>
                <li role="presentation"><a role="menuitem" tabindex="-1" href="wPackage.php">老版综合</a></li>
              </ul>
            </li>
            <li><a href="instruction.php">使用说明</a></li>
          </ul>
          	
          <ul class="nav navbar-nav navbar-right">
            
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                  用户:<?php echo $_SESSION['user']?> <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                  <li><a id="logout">退出登陆</a></li>
                </ul>
              </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
      <script src="../js/jquery.js" type="text/javascript"></script>
      <script type="text/javascript">
      		$('#logout').click(function () {
      			window.location.href = "login.php?logout=true";
      		})
      </script>