<!DOCTYPE html>
<html lang="cn">
  <head>
    <meta charset="utf-8">
  </head>
<body>

<?php
	//建立Socket
	if( function_exists("socket_create") )
		echo "socket_create defined";

	if(!($sock = socket_create(AF_INET, SOCK_STREAM, 0)))
	{
		$errorcode = socket_last_error();
		$errormsg = socket_strerror($errorcode);
		 
		die("Couldn't create socket: [$errorcode] $errormsg \n");
	}
	 
	echo "Socket created \n";
	
	//连接Socket
	if(!socket_connect($sock , '192.168.1.106' , 8001))
	{
		$errorcode = socket_last_error();
		$errormsg = socket_strerror($errorcode);
		 
		die("Could not connect: [$errorcode] $errormsg \n");
	}
	 
	echo "Connection established \n";
	$message = "Greeting from Pad Hello\r\n\r\n";
 
	//发送
	//Send the message to the server
	if( ! socket_send ( $sock , $message , strlen($message) , 0))
	{
		$errorcode = socket_last_error();
		$errormsg = socket_strerror($errorcode);
		 
		die("Could not send data: [$errorcode] $errormsg \n");
	}
	 
	echo "Message send successfully \n";
	
	//Now receive reply from server
	if(socket_recv ( $sock , $buf , 2045 , MSG_WAITALL ) === FALSE)
	{
		$errorcode = socket_last_error();
		$errormsg = socket_strerror($errorcode);
		 
		die("Could not receive data: [$errorcode] $errormsg \n");
	}
	 
	//print the received message
	echo $buf;
?>

</body>
</html>