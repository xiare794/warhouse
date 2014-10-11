
<?php
	ini_set('mysqli.default_so​cket', '/tmp/mysql5.sock');
	include_once("_db.php");
	include_once("functions_manage.php");
	//include_once("sRspFunctions.php");
	include_once("coreFunction.php");
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
	$ips['localhost'] = "10.0.0.4";
	
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
	 
	echo "Waiting for incoming connections... 等待连接\n";
	//echo "//trays action[pID:tray:tID]\n//example[3:tray:23]\n";
	
	//start loop to listen for incoming connections
	$max_clients = 10;
	while (1) 
	{
		 
		//准备一个队列
		$read = array();
		
		//第一个socket是主socket
		$read[0] = $sock;
		//增加连接上的socket
	    for ($i = 0; $i < $max_clients; $i++)
	    {
	        if($client_socks[$i] != null)
	        {
	            $read[$i+1] = $client_socks[$i];
	        }
	    }
	    
	    //运行选择SELECT命令 - blocking call
        if(socket_select($read , $write , $except , null) === false)
        {
            $errorcode = socket_last_error();
            $errormsg = socket_strerror($errorcode);
         
            die("Could not listen on socket : [$errorcode] $errormsg \n");
        }
        
        //if ready contains the master socket, then a new connection has come in
        if (in_array($sock, $read)) 
        {
            for ($i = 0; $i < $max_clients; $i++)
            {
                if ($client_socks[$i] == null) 
                {
                    $client_socks[$i] = socket_accept($sock);
                     
                    //display information about the client who is connected
                    if(socket_getpeername($client_socks[$i], $address, $port))
                    {
                        echo "Client $address : $port is now connected to us. \n";
                    }
                     
                    //Send Welcome message to client
                    $message = "Welcome to php socket server version 1.0 \n";
                    $message .= "Enter a message and press enter, and i shall reply back \n";
                    $message .= "scan rfid marker,and do action.\n";
                    socket_write($client_socks[$i] , $message);
                    break;
                }
            }
        }
        //检查终端发送的消息
        for ($i = 0; $i < $max_clients; $i++)
        {
            if (in_array($client_socks[$i] , $read))
            {
                $input = socket_read($client_socks[$i] , 1024);
                if ($input == null) 
                {
                	echo "null";
                    //zero length string meaning disconnected, remove and close the socket
                    unset($client_socks[$i]);
                    socket_close($client_socks[$i]);
                }
                else {
                	
                	$cmd = explode(":", $input); // cmd[0]是deviceID，cmd[1]是托盘号;
                	if(count($cmd)== 2)
                		checkDevice($cmd[0],$client_socks[$i]);
                	else if(count($cmd)== 3){
                		doComplete($cmd[2],$client_socks[$i]);
                	}
                }
     
                $n = trim($input);
     
                $output = "OK ... $input";
                 
                echo "Sending output to client \n";
                 
                //send response to client
                socket_write($client_socks[$i] , $output);
            }
        }
	}
	
	
	function allhtmlentities($string) { 
	    if ( strlen($string) == 0 ) 
	        return $string; 
	    $result = ''; 
	    $string = htmlentities($string, HTML_ENTITIES); 
	    $string = preg_split("//", $string, -1, PREG_SPLIT_NO_EMPTY); 
	    $ord = 0; 
	    for ( $i = 0; $i < count($string); $i++ ) { 
	        $ord = ord($string[$i]); 
	        if ( $ord > 127 ) { 
	            $string[$i] = '&#' . $ord . ';'; 
	        } 
	    } 
	    return implode('',$string); 
	} 
	
	function checkDevice($deviceID,$sock) {
		echo "get in checkDevice";
		echo "deviceID".$deviceID."\n";
		$file = "../deviceBind.json";
		$json  = json_decode(file_get_contents($file),true);
		//$output ="";
		echo $deviceID;
		for( $i = 0; $i<count($json); $i++){
			if( $deviceID == $json[$i]["deviceID"]) {
				//如果设备是手持 则去查找负责的package和托盘
				$valid =false;
				if($json[$i]["deviceID"][0]  == "h"){
					//此时 operator，device和tray对应
					//echo $json[$i]["operator"]."-t:".$json[$i]["deviceID"]."\n";
					$poolTask = getSignedApp();
					
					
					global $statusTextArray;
					for($j = 0 ; $j<count($poolTask); $j++){
						//echo $poolTask[$j]['appOperator'];
						if( $poolTask[$j]['appOperator'] == $json[$i]["operator"]){
							$valid =true;
							echo "p:".$poolTask[$j]['appOperator'];
							echo ";name:".$poolTask[$j]['appName'];
							echo ";tray".$poolTask[$j]['appFromTrayID']."\n";
							$output = "you are ".$poolTask[$j]['appOperator'];
							$output .= ",are you handling ".$poolTask[$j]['appFromTrayID']."right now\n";
							$output .= "0 for ruku complete \n1 for chachequhuo complete";
							//$output = iconv("ASCII","UTF-8",$output);
							var_dump($output);
							socket_write($sock, $output );
						}
						else {
							
						}
					}
					
					//如果deviceID 开头为h,又未找到
					if(!$valid)
					socket_write($sock , "not signed app for you\n");
				}
				else if($json[$i]["deviceID"][0]  == "d") {
					echo "门型reader";
					//如果是门型，则去试图执行下一步操作
				}
				else {
					echo "非常规类型设备";
				}
			}
			else {//如果找不到刷新marker	
			}	
		}
	}
	
	echo "断开连接";
	//mysqli_close($connection);
	//socket_close($client);
?>
