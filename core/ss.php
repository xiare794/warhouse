<?php
	include_once("_db.php");
	include_once("functions_manage.php");
	//include_once("sRspFunctions.php");
	include_once("coreFunction");
	//SocketServer Test Program
	if(!($sock = socket_create(AF_INET, SOCK_STREAM, 0)))
	{
		$errorcode = socket_last_error();
		$errormsg = socket_strerror($errorcode);
		 
		die("Couldn't create socket: [$errorcode] $errormsg \n");
	}	 
	echo "Socket created \n";
	 
	//服务器地址
	//$ssAdd = "192.168.1.117";
	//$ssAdd = "localhost";
	//$ips['localhost'] = "192.168.1.117";
	$ips['localhost'] = "192.168.1.106";
	
	// Bind the source address
	if( !socket_bind($sock, $ips['localhost'] , 8001) )
	{
		$errorcode = socket_last_error();
		$errormsg = socket_strerror($errorcode);
		 
		die("Could not bind socket : [$errorcode] $errormsg \n");
	}
 
	echo "Socket bind OK \n";
	 
	if(!socket_listen ($sock , 10))
	{
		$errorcode = socket_last_error();
		$errormsg = socket_strerror($errorcode);
		 
		die("Could not listen on socket : [$errorcode] $errormsg \n");
	}
	 
	echo "Socket listen OK \n";
	 
	echo "Waiting for incoming connections... \n";
	echo "//trays action[pID:tray:tID]\n//example[3:tray:23]\n";
	
	//start loop to listen for incoming connections
	while (1) 
	{
		//Accept incoming connection - This is a blocking call
		$client =  socket_accept($sock);
		 
		//display information about the client who is connected
		if(socket_getpeername($client , $address , $port))
		{
			echo "Client $address : $port is now connected to us. \n";
		}
		 
		//read data from the incoming socket
		$input = socket_read($client, 1024000);
			echo "Client Say ".$input."\n";
		$response = ProcessCommand($input);
		/*
		$command = explode(":",$input);	
		//process input
	    //托盘项pID:tray:tID
		//例子3:tray:23
		//是托盘项
		//var_dump($command);
		if($command[1] == 'tray'){
			//检查托盘23是否被占用
			$ss = getTableByA_equel_B('wtrays','wtID',$command[2]);
			var_dump($ss);
			if(getTableByA_equel_B('wtrays','wtID',$command[2]) == '忙碌'){
				//  占用托盘23的是否是3号货物包
				//if(getTableByA_equel_B('wtrays','wpID',$command[0])==){
					//		可能是盘货之类的操作，无作为	
				//	echo "taken by this,doing check act./n";
				//}
				//else{
					
				//}
				//	如果被其他货物包占用或预订
				//		报错
				echo "busy";
			}
			else
			{
				echo "idle";
			}
		//可以被占用修改tray23的状态，为被预订或占用，改其货物包为3
		//&将货物包的托盘使用增加tray23
	    }
		*/
		//作业项pID:command(1=开始入库，2,=写标签/3=叉车取货/进门/入货位/盘货记录/取货出库/出门/装车/出库完成)
		//pID:new:typeID:{}
		//PID:update:typeID:{}
		/*
		if($command[1] == 'somethingelse'){
			//var_dump($command);
			$result = insertRecords($command);
			
			$response = "OK...operate ".$result."\n";
			$response .= "Continue tape message";
			//$response .= "Enter a message and press enter,";
			 
			// Display output  back to client
			socket_write($client, $response);
		}*/
		socket_write($client, $response);
	}
	
	mysqli_close($connection);
?>