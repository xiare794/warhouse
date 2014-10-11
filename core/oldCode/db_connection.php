<html>
	<head>
	<meta charset="utf-8">
	
	</head>
	
	<body>

<?php
	ini_set('display_errors','On');
	error_reporting(E_ALL);
	//local var
	$host = "192.168.1.10";
	$databaseName = "stockDB";
	$user = "xiare794";
	$pass = "123";
	//$tableName = "items";
	
	$connection = mysqli_connect($host,$user,$pass);
	//选择数据表
	//$dbs = mysql_select_db($databaseName, $connection);
	
	//Test if succeed
	if(mysqli_connect_error()){
		die("Database connection failed:".
			mysql_connection_error() .
			"(" . mysqli_connection_errno().")"
			);
	}
	
	// Create database
	$sql="CREATE DATABASE ".$databaseName;
	if (mysqli_query($connection,$sql))
	  {
	  echo "Database my_db created successfully";
	  }
	else
	  {
	  echo "Error creating database: " . mysqli_error($connection);
	  }
	echo "<br><br>";
	
	//Create table wPackages
	$connection=mysqli_connect($host,$user,$pass,$databaseName);
	//制作货物包table wp = ware Package
	$query = "CREATE TABLE warePackages 
	(
	wpID INT NOT NULL AUTO_INCREMENT, 
	PRIMARY KEY(wpID),
	wpAgentID INT,
	wpInAppID INT,
	wpMoveAppID CHAR(30),
	wpOutAppID INT,
	wpCurrTrayID CHAR(20),
	wpLossStatus CHAR(30),
	wpLocked BOOL,
	wpFullName CHAR(50),
	wpCount INT,
	wpPackageStander CHAR(30),
	wpCustomerStatus CHAR(30),
	wpMAITOU CHAR(30),
	wpTotalWeight INT,
	wpWeight INT,
	wpVolume INT,
	wpInStockDays INT,
	wpPlanDays INT,
	wNotePrivate CHAR(100),
	wNotePublic CHAR(100),
	wCloseInfo CHAR(100)
	)";
	if (mysqli_query($connection,$query))
	  {
	  echo "Database table created successfully";
	  }
	else
	  {
	  echo "Error creating table: " . mysqli_error($connection);
	  }
	  
	  
	//制作货代记录数据表table wa=Ware Agent
	echo "<br><br>";
	//Create 货代table
	$connection=mysqli_connect($host,$user,$pass,$databaseName);
	$query = "CREATE TABLE wAgents 
	(
	waID INT NOT NULL AUTO_INCREMENT, 
	PRIMARY KEY(waID),
	waName CHAR(20),
	waType CHAR(20),
	waTel CHAR(20),
	waAddr CHAR(50),
	waEmail CHAR(20),
	waQuan INT,
	waCurrLog CHAR(100),
	waNote CHAR(100)
	)";
	if (mysqli_query($connection,$query))
	  {
	  echo "Database table created successfully";
	  }
	else
	  {
	  echo "Error creating table 货代记录数据表: " . mysqli_error($connection);
	  }
	  
	//制作入库、移库、出库单数据表table app= Application
	echo "<br><br>";
	//Create 表单table
	$connection=mysqli_connect($host,$user,$pass,$databaseName);
	$query = "CREATE TABLE wApplications 
	(
	appID INT NOT NULL AUTO_INCREMENT, 
	PRIMARY KEY(appID),
	wpID INT,
	appName CHAR(20),
	appCount INT,
	appIncludeItems CHAR(100),
	appType CHAR(30),
	appBookingDate DATE,
	appDate DATE,
	appOperator CHAR(30),
	appFromTrayID CHAR(100),
	appToTrayID CHAR(100),
	appDeviceID INT,
	appCameraFile CHAR(30),
	appComplete BOOL,
	appStatus CHAR(20),
	appSignned CHAR(30)
	)";
	if (mysqli_query($connection,$query))
	  {
	  echo "Database table created successfully";
	  }
	else
	  {
	  echo "Error creating table 入库、移库、出库单数据表: " . mysqli_error($connection);
	  }
	  
	//托盘数据表table wt=Ware Tray 
	echo "<br><br>";
	//Create 托盘table
	$connection=mysqli_connect($host,$user,$pass,$databaseName);
	$query = "CREATE TABLE wTrays 
	(
	wtID INT NOT NULL AUTO_INCREMENT, 
	PRIMARY KEY(wtID),
	twStatus CHAR(30),
	wSlotID INT,
	actID CHAR(50),
	twItemsID CHAR(100),
	wpID INT
	)";
	if (mysqli_query($connection,$query))
	  {
	  echo "Database table created successfully";
	  }
	else
	  {
	  echo "Error creating table 托盘数据表: " . mysqli_error($connection);
	  }
	  
	//仓位数据表table ws = ware slot 
	echo "<br><br>";
	//Create 仓位Table
	$connection=mysqli_connect($host,$user,$pass,$databaseName);
	$query = "CREATE TABLE wSlots 
	(
	wSlotID INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(wSlotID),
	tsWareHouse INT,
	tsPosRow INT,
	tsPosCol INT,
	tsPosFloor INT,
	wtID INT
	)";
	if (mysqli_query($connection,$query))
	  {
	  echo "Database table created successfully";
	  }
	else
	  {
	  echo "Error creating table 托盘数据表: " . mysqli_error($connection);
	  }
	  
	  
	//制作作业（动作）数据表
	//Table store information about wareHouse cell
	//table act = actions
	echo "<br><br>";
	//Create table
	$connection=mysqli_connect($host,$user,$pass,$databaseName);
	$query = "CREATE TABLE wActions 
	(
	actID INT NOT NULL AUTO_INCREMENT, 
	PRIMARY KEY(actID),
	wpID INT,
	actType CHAR(50),
	actOperator CHAR(50),
	appID INT,
	actDate DATE,
	actComplete BOOL,
	actDevID INT
	)";
	if (mysqli_query($connection,$query))
	  {
	  echo "Database table created successfully";
	  }
	else
	  {
	  echo "Error creating table 作业（动作）数据表: " . mysqli_error($connection);
	  }
	  
	//制作门进记录数据表
	//table io = input/output Records
	echo "<br><br>";
	//Create table
	$connection=mysqli_connect($host,$user,$pass,$databaseName);
	$query = "CREATE TABLE wIORecords 
	(
	ioID INT NOT NULL AUTO_INCREMENT, 
	PRIMARY KEY(ioID),
	wpID INT,
	ioDate DATE,
	ioNote CHAR(50)
	)";
	if (mysqli_query($connection,$query))
	  {
	  echo "Database table created successfully";
	  }
	else
	  {
	  echo "Error creating table 门进记录数据表: " . mysqli_error($connection);
	  }
	  
	//制作单件货物数据表
	//table wareItems = wi
	echo "<br><br>";
	//Create table
	$connection=mysqli_connect($host,$user,$pass,$databaseName);
	$query = "CREATE TABLE wareItems 
	(
	wiID INT NOT NULL AUTO_INCREMENT, 
	PRIMARY KEY(wiID),
	wpID INT,
	wiName CHAR(50),
	wiStatus CHAR(50),
	wiNote CHAR(100)
	)";
	if (mysqli_query($connection,$query))
	  {
	  echo "Database table created successfully";
	  }
	else
	  {
	  echo "Error creating table 制作单件货物数据表: " . mysqli_error($connection);
	  }
	  
	echo "<br><br>";
	$connection=mysqli_connect($host,$user,$pass,$databaseName);
	$query = "CREATE TABLE wUsers 
	(
	userID INT NOT NULL AUTO_INCREMENT, 
	PRIMARY KEY(userID),
	wuName CHAR(20),
	wuNamePY CHAR(30),
	wuPassword CHAR(50),
	admin INT,
	manager INT,
	operator INT
	)";
	if (mysqli_query($connection,$query))
	  {
	  echo "Database table created successfully";
	  }
	else
	  {
	  echo "Error creating table 制作用户数据表: " . mysqli_error($connection);
	  }
	  
?>

	</body>
</html>