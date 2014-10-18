<!DOCTYPE html> 
<html>
	<head>
		<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <title>Ware House Mobile Cient</title>
    <link rel="stylesheet" href="jquery-mobile/jquery.mobile-1.4.0.min.css" />
    <link rel="stylesheet" href="jquery-mobile/jqueryMobileRoller1.0.css" />
    <script class="include" type="text/javascript" src="jquery-mobile/jquery-1.9.1.js"></script>
    <script src="jquery-mobile/jquery.mobile-1.4.0.min.js"></script> 
   </head>
   <style type="text/css">
    #Actions{ 
      font-size:72px;
      text-align:center;
    }

   </style>

   <body>
   	<div data-role="page" id="page" data-theme="a">

       <div data-role="header" data-theme="a">
            <h2 >大屏幕显示文字</h2>
            
        </div>
        <div data-role="content" data-position="fixed" >
            <!--<h5 id="curTime">下午 14:35</h5>-->
            <h1 id="Actions"></h1>
        </div>

    </div>
   </body>

</html>

<script type="text/javascript">
  function homedisplayTime(){
    //$('#curTime').html(getTime());
    setTimeout('homedisplayTime()',1000);
    getLastestAction();
    
  }

  //获取最新的动作
  function getLastestAction(){
    var query ="SELECT * FROM wActions ORDER BY `actID` DESC LIMIT 1";
    $.post("phpSearch.php?query="+query,function(data){
      //console.log(data);
      var obj = jQuery.parseJSON(data);
      var sec = CompareTimeMin(obj[0]['actTime']);

      var min = (sec/60);
      $("#Actions").html("最近发生：<br>"+obj[0]['actContent']);
    });


  }

  $().ready(function(){
    setTimeout('homedisplayTime()',1000);


  });


  //return string
  function getTime(){
    var currentTime = new Date();
    var month = currentTime.getMonth()+1;
    var day = currentTime.getDate();
    var year = currentTime.getFullYear();
    var hour = currentTime.getHours();
    var miniute = currentTime.getMinutes();
    var second = currentTime.getSeconds();
    if(parseInt(month)<10){
      month = "0"+month;  
    }
    if(parseInt(miniute)<10){
      miniute = "0"+miniute;
    }
    if(parseInt(second)<10){
      second = "0"+second;
    }
    if(parseInt(hour)>12){
      hour = "下午"+hour; 
    }
    else{
      hour = "上午"+hour;
    }
    //console.log("获取时间");
    return year+"年"+month+"月"+day+"日<br>"+hour+":"+miniute+":"+second;
  }

  function CompareTimeMin(_t){
    var currentTime = new Date();
    var eventTime = new Date(_t);
    var mins = parseInt((currentTime - eventTime)/1000);
    return mins;
    //console.log(mins);

  }

</script>