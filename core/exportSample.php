<?php

	
	//输出xlsx phpExcelLib
	require_once 'phpExcelLib/PHPExcel.php';
	//定义EOL 
	define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
	
	//获取数据
	include_once("_db.php");
	$query = "SELECT `agentID`,`InStockID`,`appName`,`appMaitou`,`appCount`,`appPreCount`,`appBookingDate`,`appStatus` FROM wAppIn ";
	$query .= "WHERE (`appBookingDate` BETWEEN '2014-12-01' and '2014-12-30') AND (`appStatus`=3)";
	$List = array();
	if ($result = mysqli_query($connection, $query)) {
		while($row = mysqli_fetch_array($result)){
			array_push($List,$row);
		}
	}
	else{
		echo "query -".$query."-<br>";
		 echo '[we have a problem]: '.mysqli_error($connection);
	}

	error_reporting(E_ALL);
	/** Include PHPExcel */
	// Create new PHPExcel object
	//echo date('H:i:s') , " Create new PHPExcel object" , EOL;
	$objPHPExcel = new PHPExcel();

	// Set document properties
	//echo date('H:i:s') , " Set document properties" , EOL;
	$objPHPExcel->getProperties()->setCreator("维尔豪斯 Ref")
								 ->setLastModifiedBy("维尔豪斯 Ref")
								 ->setTitle("通州仓储2号库")
								 ->setSubject("货单打印")
								 ->setDescription("一段时间内的货单详细打印 setDescription")
								 ->setKeywords("office 2007 openxml php")
								 ->setCategory("货单打印 category");

	// Create a first sheet, representing sales data
	//echo date('H:i:s') , " Add some data" , EOL;
	$objPHPExcel->setActiveSheetIndex(0);
	
	
	$Chr=array("A","B","C","D","E","F","G","H");
	$row = 1;
	foreach ($List as $app){
		$Chrkey = 0;
		foreach ($app as $key => $value) {
			if(is_numeric ($key)){
				$pos = $Chr[$Chrkey].$row;
				$objPHPExcel->getActiveSheet()->setCellValue($pos, $value);
				$Chrkey ++;
			}
		}
		$row++;
	}

	// Merge cells 合并单元格
	//echo date('H:i:s') , " Merge cells" , EOL;
	//$objPHPExcel->getActiveSheet()->mergeCells('A18:E22');
	//$objPHPExcel->getActiveSheet()->mergeCells('A28:B28');		// Just to test...
	//$objPHPExcel->getActiveSheet()->unmergeCells('A28:B28');	// Just to test...
	
	// Protect cells 保护单元格
	//echo date('H:i:s') , " Protect cells" , EOL;
	//$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);	// Needs to be set to true in order to enable any worksheet protection!
	//$objPHPExcel->getActiveSheet()->protectCells('A3:E13', 'PHPExcel');

	// Set cell number formats 设置数字格式
	//echo date('H:i:s') , " Set cell number formats" , EOL;
	//$objPHPExcel->getActiveSheet()->getStyle('E4:E13')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE);

	// Set column widths 设置列宽度
	//echo date('H:i:s') , " Set column widths" , EOL;
	//$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	//$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
	//$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);

	// Set page orientation and size 设置方向和尺寸
	echo date('H:i:s') , " Set page orientation and size" , EOL;
	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
	
	// Set fonts 设置字体
	//echo date('H:i:s') , " Set fonts" , EOL;
	$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setName('Candara');
	$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
	$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
	
	$fileName = "test_excel";
	$fileName .= "_.xlsx";
	$fileName = iconv("utf-8", "gb2312", $fileName);
  
  $objPHPExcel->setActiveSheetIndex(0);
  //将输出重定向到一个客户端web浏览器(Excel2007)
  //注意，php转excel是将这个页面打印的所有东西转成excel，如果
  //打印了多余的东西，会报错
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header("Content-Disposition: attachment; filename=\"$fileName\"");
  header('Cache-Control: max-age=0');

  try{
	  //输出excel 2007
	  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save(iconv("UTF-8","gb2312","若果.xlsx"));
		//$objWriter->save(str_replace('.php', '.xlsx', "only_chinese.php")); //__FILE__ 
	}
	catch(Exception $e)
	{
	 	echo 'Message: ' .$e->getMessage();
	}

	//原先的输出$objWriter->save('php://output');
	exit;


?>