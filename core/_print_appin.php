<!DOCTYPE html>
<html lang="zh-cn"><!-- 2014.09.28 -->
	<head>
		<meta charset="utf-8">
		
	</head>
<style>
	.noprint{display:none;} /*隐藏不需要的控件*/
	.title{
		text-align: center;
		padding-bottom: 10px;
	}
	.center{
		text-align: center;
	}
	.left{
		text-align: left;
	}
	.right{
		text-align: right;
	}
	.underline{
		text-decoration:underline;
	}
	.padding-left-30{
		padding-left: 30px;
	}
	.padding-top-0{
		margin-top:-26px;
	}
	.textS{ font-size: 14px;}
	.textM{ font-size: 20px;}
	.textL{ font-size: 26px;}

	.fullWidth{ /* 1090全宽 前后留25px缩进 */
		width: 1040px; 
		/*padding: 0px 25px;*/
		margin:0px 25px;
		
	}
	.overflow{ /*利用overflow 撑开float引起的高度折叠 放置于父元素 */
		overflow: hidden; 
	}
	.div31{
		float: left;
		width: 340px;
	}

	.div41{
		float: left;
		width: 260px;
	}
	p{
		margin: 0px;
	}

	
	.inline{
		display: inline-block;
	}
	.div51{
		float: left;
		width: 208px;
	}


	table{ border-collapse:collapse;border-spacing:0;border-left:1px solid #888;border-top:1px solid #888;background:#FFF;}
	th,td{border-right:1px solid #888;border-bottom:1px solid #888;padding:5px 2px;}
	th{font-weight:bold;background:#ccc;}

	/* 清除浮动 */
	.clearfix:before,.clearfix:after {
	    content:"";
	    display:table;
	}
	.clearfix:after {
	    clear:both;
	    overflow:hidden;
	}
	.clearfix {
	    zoom:1; /* for ie6 & ie7 */
	}
	.clear {
	    clear:both;
	    display:block;
	    font-size:0;
	    height:0;
	    line-height:0;
	    overflow:hidden;
	}


</style>
<!--style media=print>
    /* 应用这个样式的在打印时隐藏 */
    .Noprint {
     display: none;
    }
   
		/* 应用这个样式的，从那个标签结束开始另算一页，之后在遇到再起一页，以此类推 */
		.PageNext {
		 page-break-after: always;
		}
</style-->
<body>
		<!--
		<OBJECT classid="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2" id="wb" width="0" height="0"></OBJECT>
		<p class="Noprint">
        <span style="cursor:pointer; color:#0000FF" onclick="javascript:window.open('http://www.chinasvf.com/Webs/Share/printhelp')" class="Noprint">打印帮助</span>
        <span style="cursor:pointer; color:#0000FF" onclick="printpreview();">打印预览</span>
        <span style="cursor:pointer; color:#0000FF" onclick="prints();" class="Noprint">打印</span>
    </p>
		-->

<h1 class="title underline">title underline</h1>
<div class="center padding-top-0 textS" id="subtitle">地址:宁波北仑大港工业城甬江路17号 电话:0754-278687818,27687809 id=subtitle</div>
<div class="fullWidth overflow" id="beforeTableDiv1">
	<div class="left div41">货代名称：深圳华运 beforeTableDiv1 div41 left center</div>
	<div class="div41 center">fullWidth</div>
	<div class="div41 center">英文船名：THALASSANIKI </div>
	<div class="right div41 ">航次:0821W </div>
</div>

<div style="clearfix"></div>
<table class="fullWidth" style="margin:10px 25px" id="printTable">
	<!--<tr>
		<td>printTable</td>
		<td>进仓编号</td>
		<td>作业号</td>
		<td>送货单位</td>
		<td>货名</td>
		<td>唛头</td>
		<td>件数</td>
		<td>体积</td>
		<td>托数</td>
		<td>仓</td>
		<td>位</td>
		<td>列</td>
		<td>超期天数</td>
		<td>超期费</td>

	</tr>
	<tr>
		<td>COSU6090688820</td>
		<td>NBEF15040003A</td>
		<td>201503260033</td>
		<td>鄞州富汇塑胶</td>
		<td>胶带</td>
		<td>TIL</td>
		<td>50</td>
		<td>3.335</td>
		<td>2</td>
		<td>2</td>
		<td>b</td>
		<td>5</td>
		<td>0</td>
		<td>0.00</td>
	</tr>
	<tr>
		<td>COSU6090688820</td>
		<td>NBEF15040003A</td>
		<td>201503310001 </td>
		<td>湖州福陪德有</td>
		<td>宠物用品 </td>
		<td>TILAUS </td>
		<td>52</td>
		<td>4.901</td>
		<td>2</td>
		<td>2</td>
		<td>b</td>
		<td>5</td>
		<td>0</td>
		<td>0.00</td>
	</tr>
	<tr>
		<td>COSU6090688820</td>
		<td>NBEF15040003A</td>
		<td>201503260033</td>
		<td>建德明博 </td>
		<td>西服套  </td>
		<td>TZL </td>
		<td>80</td>
		<td>3.335</td>
		<td>2</td>
		<td>2</td>
		<td>b</td>
		<td>5</td>
		<td>0</td>
		<td>0.00</td>
	</tr>
	<tr>
		<td>COSU6090688820</td>
		<td>NBEF15040003A</td>
		<td>201503260033</td>
		<td>余姚双鹤电子</td>
		<td>电子机件 </td>
		<td>TIL</td>
		<td>70</td>
		<td>1.3235</td>
		<td>2</td>
		<td>2</td>
		<td>b</td>
		<td>5</td>
		<td>0</td>
		<td>0.00</td>
	</tr>
	<tr>
		<td>COSU6090688820</td>
		<td>NBEF15040003A</td>
		<td>201503260033</td>
		<td>天津奥森物流 </td>
		<td>玩具 </td>
		<td>TIL</td>
		<td>21</td>
		<td>3.335</td>
		<td>2</td>
		<td>2</td>
		<td>b</td>
		<td>5</td>
		<td>0</td>
		<td>0.00</td>
	</tr>
	<tr>
		<td>COSU6090688820</td>
		<td>NBEF15040003A</td>
		<td>201503260033</td>
		<td>天津奥森物流 </td>
		<td>玩具 </td>
		<td>TIL</td>
		<td>21</td>
		<td>3.335</td>
		<td>2</td>
		<td>2</td>
		<td>b</td>
		<td>5</td>
		<td>0</td>
		<td>0.00</td>
	</tr>
	<tr>
		<td>COSU6090688820</td>
		<td>NBEF15040003A</td>
		<td>201503260033</td>
		<td>天津奥森物流 </td>
		<td>玩具 </td>
		<td>TIL</td>
		<td>21</td>
		<td>3.335</td>
		<td>2</td>
		<td>2</td>
		<td>b</td>
		<td>5</td>
		<td>0</td>
		<td>0.00</td>
	</tr>
	<tr>
		<td>COSU6090688820</td>
		<td>NBEF15040003A</td>
		<td>201503260033</td>
		<td>天津奥森物流 </td>
		<td>玩具 </td>
		<td>TIL</td>
		<td>21</td>
		<td>3.335</td>
		<td>2</td>
		<td>2</td>
		<td>b</td>
		<td>5</td>
		<td>0</td>
		<td>0.00</td>
	</tr>

	<tr>
		<td colspan=6>合计:</td>
		<td>394</td>
		<td>29.385</td>
		<td>20</td>
		<td colspan=4></td>
		<td>0.00</td>
	</tr>-->

</table>
<!--
<table style="margin:10px 25px; width:350px">
	<tr>
		<th>费用名称</th>
		<th>费用金额</th>
	</tr>
	<tr>
		<td>吊机费</td>
		<td>50.00</td>
	</tr>
	<tr>
		<td>换纸箱费</td>
		<td>33.00</td>
	</tr>
	<tr>
		<td>条形码费</td>
		<td>10.00</td>
	</tr>
	<tr>
		<td>预录费</td>
		<td>10.00</td>
	</tr>
	<tr>
		<td>装箱费</td>
		<td>260.00</td>
	</tr>
	<tr>
		<td>合计</td>
		<td>363</td>
	</tr>
</table>-->

<div class="fullWidth">
	<div class="div51 left">调度: _______________</div>
	<div class="div51 left">理货: _______________</div>
	<div class="div51 left">铲车: _______________</div>
	<div class="div51 left">处理: _______________</div>
</div>
<!--object id="WebBrowser" classid="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2" height="0" width="0"></object-->

</body>

<script src="../js/jquery.js"></script>
<script language="JavaScript">
	$().ready(function(){
		var id = <?php echo $_GET["appID"]; ?>;
		console.log(id);
	

		//查找代理商序号
		var queryStr= "SELECT * FROM `wAppIn` WHERE `appID`="+id;
		var theApp;
		$.ajax({
		    type : "get", 
		    url : "_search.php?query="+queryStr,
		    async : false, 
		    success : function(data){
		    	//console.log(data);
		    	theApp = jQuery.parseJSON(data)[0];
		    	//console.log(obj);
		    	//appInNumToday += obj[0][0];
	    	}
	  });

	  //查找代理商序号
	  
		var queryStr= "SELECT * FROM `wUsers` WHERE 1";
		var users ; //{id=>"name",id=>"name",...}
		$.ajax({
		    type : "get", 
		    url : "_search.php?query="+queryStr,
		    async : false, 
		    success : function(data){
		    	//console.log(data);
		    	users = jQuery.parseJSON(data);
		    	
		    	//console.log(obj);
		    	//appInNumToday += obj[0][0];
	    	}
	  });

	  //查找所有的货物规格和位置
		var queryStr= "SELECT * FROM wAgents WHERE wAgents.waID = "+theApp["agentID"];
		var theAgent; //{id=>"name",id=>"name",...}
		$.ajax({
		    type : "get", 
		    url : "_search.php?query="+queryStr,
		    async : false, 
		    success : function(data){
		    	//console.log(data);
		    	theAgent = jQuery.parseJSON(data)[0];
		    	
		    	//console.log(obj);
		    	//appInNumToday += obj[0][0];
	    	}
	  });

	  //查找所有的货物规格和位置
		var queryStr= "SELECT * FROM wFee WHERE wfAppID = "+theApp["appID"];
		var Fee; //{id=>"name",id=>"name",...}
		$.ajax({
		    type : "get", 
		    url : "_search.php?query="+queryStr,
		    async : false, 
		    success : function(data){
		    	//console.log(data);
		    	Fee = jQuery.parseJSON(data);
		    	
		    	//console.log(obj);
		    	//appInNumToday += obj[0][0];
	    	}
	  });

		//查找所有的货物规格和位置
		var queryStr= "SELECT * FROM `wUnit` u, `wTrays` t WHERE u.trayID = t.wtID AND t.wtAppID = "+theApp["appID"];
		var Items ; //{id=>"name",id=>"name",...}
		var unitTypeCount = 0;
		$.ajax({
		    type : "get", 
		    url : "_search.php?query="+queryStr,
		    async : false, 
		    success : function(data){
		    	//console.log(data);
		    	Items = jQuery.parseJSON(data);
		    	
		    	for(var i=0; i<Items.length; i++){
		    		if(Items[i]['itemType']>unitTypeCount)
		    			unitTypeCount = Items[i]['itemType'];
		    	}
	    	}
	  });
	  console.log(theAgent);
	  console.log(theApp);
		console.log(Items);
	  console.log(users);
	  console.log("货物类型"+unitTypeCount);
	  console.log("本库单"+theApp["appID"]+theApp["appName"]);
	  console.log("费用");
	  console.log(Fee);
	  var person= Array();
	  for(var i =0; i<users.length; i++){
	  	if(users[i]['userID'] === theApp['OpCounter']){
	  		person["counter"] = users[i]['wuName'];
	  	}
	  	if(users[i]['userID'] === theApp['OpInput']){
	  		person["input"] = users[i]['wuName'];
	  	}
	  	if(users[i]['userID'] === theApp['OpFork']){
	  		person["fork"] = users[i]['wuName'];
	  	}
	  		
	  }
	  console.log(person);


	  $(".title").html("宁波通州储运  x号库");


	  //表格前
	  var beforeTableDiv1 ="<div class=\"left div31\">"+"作业号："+theApp['appSeries']+"</div>";
	  beforeTableDiv1+= "<div class=\"center div31\">"+"预进时间："+theApp['appBookingDate']+"</div>";
	  beforeTableDiv1+= "<div class=\"right div31\">"+"状态"+theApp['appStatus']+"</div>";
	  $("#beforeTableDiv1").html(beforeTableDiv1);


	  //表格
	  var tr1 = "<tr >";
	 	tr1 += "<td colspan=4>进仓编号:"+theApp["InStockID"]+"</td>";
	 	tr1 += "<td colspan=8>货代单位:"+theAgent["waName"]+"</td>";
	 	tr1 += "</tr>";

	 	var tr2 = "<tr>";
	 	tr2 += "<td colspan=4>品名:"+theApp["appName"]+"</td>";
	 	tr2 += "<td colspan=4>送货单位:"+theApp["deliverComp"]+"</td>";
	 	tr2 += "<td colspan=4>送货车信息:"+theApp["deliverTruckID"]+"-"+theApp["deliverMobile"]+"</td>";
	 	tr2 += "</tr>";

	 	var tr3 = "<tr>";
	 	tr3 += "<td colspan=4>包装规格:暂无数据</td>";
	 	tr3 += "<td colspan=4>预进件数:"+theApp["appPreCount"]+"</td>";
	 	tr3 += "<td colspan=4>实收件数:"+theApp["appCount"]+"</td>";
	 	tr3 += "</tr>";

	 	var tr4 = "<tr>";
	 	tr4 += "<td colspan=12>唛头:"+theApp["appMaitou"]+"</td>";
	 	tr4 += "</tr>";


	 	var tr6 = "<tr>";
	 	tr6 += "<td colspan=3>输单:"+person["input"]+"</td>";
	 	tr6 += "<td colspan=3>理货员:"+person["counter"]+"</td>";
	 	tr6 += "<td colspan=3>叉车:"+person["fork"]+"</td>";
	 	tr6 += "<td colspan=3>审核:</td>";
	 	tr6 += "</tr>";

	 	var tr7 = "<tr>";
	 	tr7 += "<td colspan=3>托盘数:"+Items.length+"</td>";
	 	tr7 += "</tr>";

	 	var tr8 = "<tr>";
	 	tr8 += "<td>类型</td>";
	 	tr8 += "<td colspan=3>规格</td>";
	 	tr8 += "<td>托数</td>";
	 	tr8 += "<td colspan=3>每托数目</td>";
	 	tr8 += "<td colspan=2>仓位</td>";
	 	tr8 += "<td>总件数</td>";
	 	tr8 += "</tr>";

	 	$("#printTable").html("");
	 	$("#printTable").append(tr1);
	 	$("#printTable").append(tr2);
	 	$("#printTable").append(tr3);
	 	$("#printTable").append(tr4);
	 	$("#printTable").append(tr6);
	 	$("#printTable").append(tr7);
	 	$("#printTable").append(tr8);

	 	var trItem = Array();
	 	for(var i =0 ; i<unitTypeCount; i++){
	 		var ts = i+1;
	 		var item = Array();
	 		item.tCount = 0;
	 		item.tTray = 0;
	 		for(var j=0; j<Items.length; j++){
	 			if(Items[j]["itemType"] == i){
		 			item.type = Items[j]["itemType"];
		 			if(item.size==undefined){
			 			item.size = Items[j]["length"]+"cm*"+Items[j]["width"]+"cm*"+Items[j]["height"]+"cm";
			 		}
			 		if(item.count==undefined){
				 		item.count = Items[j]["count"];//+"-"+ 	Items[j]["count"];
				 	}
				 	else{
				 		item.count +="-"+ 	Items[j]["count"];
				 	}

				 	if(item.pos == undefined){
				 		item.pos = Items[j]['wSlotID'];
				 	}
				 	else if(item.pos.indexOf(Items[j]['wSlotID'])>-1){
				 		//nothing
				 	}
				 	else{
				 		item.pos += "/"+Items[j]["wSlotID"];
				 	}
				 	item.tTray ++;
				 	item.tCount += Number(Items[j]["count"]);


	 			}
	 		}
	 		trItem[i] = item;
	 	}
	 	console.log("trItem");
	 	console.log(trItem);

	 	for(var i=0; i<trItem.length; i++){
		 	var tr9 = "<tr>";
		 	tr9 += "<td>"+trItem[i].type+"</td>";
		 	tr9 += "<td colspan=3>"+trItem[i].size+"</td>";
		 	tr9 += "<td>"+trItem[i].tTray+"</td>";
		 	tr9 += "<td colspan=3>"+trItem[i].count+"</td>";
		 	tr9 += "<td colspan=2>"+trItem[i].pos+"</td>";
		 	tr9 += "<td>"+trItem[i].tCount+"</td>";
		 	tr9 += "</tr>";
		 	$("#printTable").append(tr9);
	  }


	  var tr10 = "<tr colspan=3>";
	  var tValue = 0;
	  for(var i=0; i<Fee.length; i++){
	  	tr10 += "<td colspan=3>"+Fee[i]["wfName"]+":"+Fee[i]['wfValue']+"</td>";
	  	tValue += Number(Fee[i]['wfValue']);
	  }
	  tr10 += "<td>合计："+tValue+"</td>";
	  tr10+="</tr>";

	  $("#printTable").append(tr10);

  });
/*
    var hkey_root,hkey_path,hkey_key;
    hkey_root="HKEY_CURRENT_USER";
    hkey_path="\\Software\\Microsoft\\Internet Explorer\\PageSetup\\";
    //配置网页打印的页眉页脚为空
    function pagesetup_null(){   
        try{
            var RegWsh = new ActiveXObject("WScript.Shell");           
            hkey_key="header";           
            RegWsh.RegWrite(hkey_root+hkey_path+hkey_key,"");
            hkey_key="footer";
            RegWsh.RegWrite(hkey_root+hkey_path+hkey_key,"");
            //&b 第&p页/共&P页 &b
        }catch(e){}
    }
    //配置网页打印的页眉页脚为默认值
    function pagesetup_default(){
        try{
            var RegWsh = new ActiveXObject("WScript.Shell");
            hkey_key="header";
            RegWsh.RegWrite(hkey_root+hkey_path+hkey_key,"&w&b页码，&p/&P")
            hkey_key="footer";
            RegWsh.RegWrite(hkey_root+hkey_path+hkey_key,"&u&b&d");
        }catch(e){}
    }   
     //打印选区内容
    function doPrint() {
        pagesetup_null();
        bdhtml=window.document.body.innerHTML; 
        sprnstr="<!--startprint-->"; 
        eprnstr="<!--endprint-->"; 
        prnhtml=bdhtml.substr(bdhtml.indexOf(sprnstr)+17); 
        prnhtml=prnhtml.substring(0,prnhtml.indexOf(eprnstr)); 
        window.document.body.innerHTML=prnhtml; 
        window.print(); 
    }
    //打印页面预览
    function printpreview(){
        pagesetup_null();
        //wb.printing.header = "居左显示&b居中显示&b居右显示页码，第&p页/共&P页";
        //wb.printing.footer = "居左显示&b居中显示&b居右显示页码，第&p页/共&P页";
        try{
            wb.execwb(7,1);
        }catch(e){
            alert("您的浏览器不支持此功能,请选择'文件'->'打印预览'");
        }
    }
    //打印
    function prints(){
        pagesetup_null();
        //wb.printing.header = "居左显示&b居中显示&b居右显示页码，第&p页/共&P页";
        //wb.printing.footer = "居左显示&b居中显示&b居右显示页码，第&p页/共&P页";
        try{
            wb.execwb(6,1);
        }catch(e){
            alert("您的浏览器不支持此功能");
        }
    }*/
</script>

</html>