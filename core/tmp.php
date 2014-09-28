
<!DOCTYPE html>
<html lang="cn">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../assets/ico/favicon.png">

    <title>WareHouse 管理</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <!--<link href="navbar.css" rel="stylesheet"> -->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../../assets/js/html5shiv.js"></script>
      <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">

      <?php include("header.php");

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron" style="margin-top:65px">
        <p>debug Info<a class="btn btn-lg btn-primary" data-toggle="collapse" data-target="#testForm">View navbar docs &raquo;</a></p>
        <p><?php var_dump($_POST); ?></p>
      </div>
	  <div class="col-lg-12" class="collapse in" >
		<div class="panel panel-default" id="testForm">
		  <div class="panel-heading">
			<h4 class="panel-title">新建表单</h4>
		  </div>
		  <form role="form" action="tmp.php" method="post" class="form-inline">
			<?php 
				$allInputs = array(
					'货物信息'=>array("货物ID","货物类别","货代ID","送货单位","货物名称","货损状态","是否锁定","货物品名","货物件数","包装规格","报关状态","唛头","毛重","净重","体积","堆积天数"),
					'仓位信息'=>array("仓库编号","货位行号","货位列号","货位层号","托盘编号"),
					'操作流程'=>array("作业号","开始入库","写标签","叉车取货","进门","入货位","盘货记录","取货出库","出门","装车","出库完成"),
					'时间'=>array("预进日期","进仓日期","预出日期","出库日期","盘库日期"),
					'其他'=>array("备注-内","备注-外","结算信息"));
				$allInputsName = array("wID","wCategory","wFakeID","wDeliverCompany","wName","wLossStatus","wLocked","wFullName","wPackageCount","wPackageStander","wCustomStatus","wMAITOU","wTotalWeight","wWeight","wVolume","wInStockDays","wWareHouseID","wShelfRow","wShelfCol","wShelfLevel","wPlateID","wProcessID","wStocking","wTagged","wPickingUp","wInWareHouse","wInPlace","wCheckRecord","wSendOut","wOutWareHouse","wInLifter","wPortComplete","wBookedDate","wGotDate","wBookedSentDate","wSentDate","wCheckDate","wNotePrivate","wNotePublic","wCloseInfo");
				
				echo "<div class=\"input-group\"><label>Email address</label></div>";
				
				for($i = 0; $i<count($allInputs); $i++){
					
					echo "<span class=\"input-group-addon\">".$allInputs[$i]."</span>";
					echo "<input type=\"text\" name=\"".$allInputsName[$i]."\" class=\"form-control\" >";
					
				}
			?>
				
			
		<button type="submit" name="submit" class="btn btn-default">确定</button>
		</form>
		</div>
	  </div> <!-- end of col-lg-9 -->
    </div> <!-- /container -->
	<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
	<script>
		$(function(){
			$('#testForm').collapse({
			  toggle: true
			})
			$(".input-group-addon").css('width',"90px");
		});
	</script>
	
  </body>
</html>
