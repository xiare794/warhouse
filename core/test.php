<!DOCTYPE html>
<html lang="zh-cn"><!-- 2014.09.28 -->
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="WSLayout">
		<meta name="author" content="Ref">


		<title>记录</title>

		
		<link href="../css/bootstrap.css" rel="stylesheet">
	</head>
	<body >
		<h1>Test Sandbox Page</h1>
		<div class="container">
			<div class="row">
				<?php
					var_dump($_POST);
					$_POST = null;
				?>
				<form action="test.php" method="post">
					<div class="col-sm-6" id="agentID_input">
						<input type="text" id="agentID_input_value" autocomplete="off"/>
					</div>
					<div class="col-sm-6">456</div>
					<div class="col-sm-6" id="OagentID_input">
						<input type="text" id="OagentID_input_value" autocomplete="off"/>
					</div>
					<div class="col-sm-6">456</div>
					<button type="submit">显示</button>
				</form>
			</div>
		</div>

	</body>
	<!-- Bootstrap core js -->
	<script src="../js/jquery.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/custom_input_thinking.js"></script>
  <script type="text/javascript">
  	inputThinking("agentID",1,"wAgents");
  	inputThinking("OagentID",1,"wAgents");
  	
  </script>
</html>