<?php require_once("config.php"); //session?>
<!DOCTYPE html>
<html lang="cn">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="使用配置说明">
    <meta name="author" content="Ref">
    
    <title>使用配置说明</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.css" rel="stylesheet">
  </head>

  <body>
	<?php include("header.php"); ?>
	<div class="container" style="margin-top:60px">
		<div class="panel panel-default">
		  <div class="panel-heading"><h2>配置服务器</h2></div>
		  <div class="panel-body">
			<ol>
				<li>下载并安装wamp,使其正常工作(右下角的W图标变绿),并在服务器可以访问localhost,在浏览器地址栏直接键入localhost，可以进入http://localhost/ 。</li>
				<li>配置服务器,wamp的mysql初始有root用户，进入phpmyadmin管理登陆页面，用户root密码无，进入管理界面，新建数据库用户，目前的配置是user:xiare794;ps:123;主机:localhost(本地)。设置完毕后退出，使用新设置的用户密码登陆</li>
				<li>打开用于和外围设备通讯的SOCKET服务，默认情况下是关闭的，这里需要修改两个配置文件，一个是服务器使用的php程序配置，一个是本机使用的程序配置，地址分别为C:\wamp\bin\apache\Apache2.4.4\bin\php.ini; C:\wamp\bin\php\php5.4.16\php.ini 这个地址规则仅适用于windows&&wamp。</li>
				<li>配置windows环境变量，我的电脑->属性->高级系统配置->环境变量->系统变量->PATH 修改，在最后一项上加入C:\wamp\bin\php\php5.4.16， 即本机php的运行程序的目录路径,做完这些环境配置基本结束，重新启动。</li>
			</ol>
		  </div>
		</div>
		<div class="panel panel-default">
		  <div class="panel-heading"><h2>基本框架</h2></div>
		  <div class="panel-body col-md-offset-1" style="width:70%">
			<h3>内部网络</h3>
			<div class="row" style="font:50%">
				<div class="col-md-1 btn btn-default btn-sm">终端电脑</div>
				<div class="col-md-1 btn btn-default btn-sm">...</div>
				<div class="col-md-1 btn btn-default btn-sm">...</div>
				<div class="col-md-1 btn btn-default btn-sm">终端电脑</div>
				<div class="col-md-1 btn btn-default btn-sm">手持辅助电脑</div>
				<div class="col-md-1 btn btn-default btn-sm">...</div>
				<div class="col-md-1 btn btn-default btn-sm">...</div>
				<div class="col-md-1 btn btn-default btn-sm">手持辅助电脑</div>
				<div class="col-md-1 btn btn-default btn-sm">仓库门控制器</div>
				<div class="col-md-1 btn btn-default btn-sm">...</div>
				<div class="col-md-1 btn btn-default btn-sm">叉车控制器</div>
				<div class="col-md-1 btn btn-default btn-sm">货架控制器</div>
			</div>
			<div class="row">
				<div class="col-md-4 btn btn-primary btn-lg">浏览器终端</div>
				<div class="col-md-4 btn btn-success btn-lg">RFID手持设备</div>
				<div class="col-md-4 btn btn-warning btn-lg">RFID固定设备</div>
			</div>
			<div class="row">
				<div class="col-md-4 btn btn-default btn-lg">服务器网页解释</div>
				<div class="col-md-8 btn btn-default btn-lg">服务器Socket解释</div>
			</div>
			<div class="row">
				<div class="col-md-12 btn btn-default btn-lg">服务器</div>
			</div>
			<h3>内部网络</h3>
			<p>外部网络</p>
		  </div>
		</div>
		<div class="panel panel-default">
		  <div class="panel-heading"><h2>基本流程</h2></div>
		  <div class="panel-body" >
			<div class="col-md-12" >
				<h3>系统使用分三部分</h3>
				<ol>
					<li>使用前的配置，包括建立数据库，创建仓库的基本信息，做一部分的关联工作</li>
					<li>在每次实际操作货物前，需要对货物信息建立，包括代理商、货物概况、相关管理人员确认，</li>
					<li>直接接触到货物时，数据库应有相对应的<code>经过审批</code>确认的出入库单</li>
				</ol>
			</div>
			<div class="col-md-12" >
				<h3>数据结构</h3>
				<p>每一单存储货物有一个<code>货物包</code>，包含了若干张<code>出入库表单</code>，记录了货物包的运转手续，每张表单中包含了几个<code>动作</code>，记录了货物包的运转过程</p>
				<p>大体的工作流程如下：</p>
				<ol>
					<li>新建一个货物包、填写货物基本属性</li>
					<li>为此货物包建立一个入库单，此时入库单状态为开启，<strong>一个货物包同一时间只能有一个开启的表单</strong></li>
					<li>审批这个开启的表单，对表单进行签名操作，使表单合法可用</li>
					<li>等待货物运到仓库</li>
					<li>运到仓库后以此执行”开始入库“、”写标签“、”叉车运输“、”过门“、”入货位“等操作，以上操作必须顺序执行，开启的入库单，会更新记录执行结束的操作</li>
					<li>以上操作全部结束时，表单状态该为关闭，表单完成度为100%</li>
					<li>达到上一条状态时，才可以新建移库单或者出库单，执行过程类似入库单</li>
				</ol>
			</div>
			  <div class="col-md-12" >
				  <hr>
				  <h3>动作列表</h3>
					  <p>合法的动作必须符合当前<code>货物包</code>中所开启的表单，并且也要符合动作的连续性，比如在<code>写标签</code>动作完成之前就不能执行<code>叉车运输</code>的动作。</p>
				  
				  <pre>
//过程动作表
$actList = array(
"入库"=>array( "开始入库"=>"loadIn",
	"写标签"=>"tagged",
	"叉车运输"=>"forkLoad",
	"过门"=>"passDoor",
	"入货位"=>"insertSlot"),
 "盘货"=>array( "盘货"=>"check"),
 "移库"=>array( "开始移库"=>"moveStart",
	"叉车运输"=>"forkLoad",
	"入货位"=>"insertSlot"),
 "出库"=>array( "取货出库"=>"ejectSlot",
	"过门"=>"passDoor",
	"装车"=>"truckLoad",
	"出库完成"=>"fin")
);
				</pre>
				<pre class="prettyprint">  web管理地图/
				  <span class="muted">├──</span><a href="wPackage.php">货物包管理/</a>
				  <span class="muted">├──</span> <a href="#">仓库管理</a>/
				  <span class="muted">	└──</span> <a href="setHouse.php">创建仓库模型</a>
				  <span class="muted">├──</span> 托盘管理/
				  <span class="muted">	└──</span> <a href="trayList.php">托盘列表</a>/
				  <span class="muted">├──</span> 人员管理/
				  <span class="muted"> 	├──</span> docs.css
				  <span class="muted">	└──</span> <a href="userManage.php">工作分配列表</a>/				  
				  <span class="muted">├──</span> 留空
				  <span class="muted">├──</span> 留空
				  <span class="muted">├──</span> <a href="setHouse.php">创建仓库模型</a>
				  <span class="muted">└──</span> <a href="taskToDo.php">准备进行的工作</a>
				  <span class="muted">    ├──</span> <a href="deviceList.php">设备列表</a>
				  <span class="muted">    ├──</span> 留空
				  <span class="muted">    ├──</span> 留空
				  <span class="muted">    ├──</span> 留空
				  <span class="muted">    └──</span> 留空
				</pre>
			    </div>
		  </div>
		</div>
	</div> <!-- /container -->
	<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../js/jquery.js"></script>
    <script src="../js/bootstrap.min.js"></script>
  </body>
</html>