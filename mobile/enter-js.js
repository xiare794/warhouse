// JavaScript Document

_app.filter.loadListStart = 0;
_app.filter.loadListStep = 5;
_app.filter.allApp = false;
_app.filter.appType = "in";
_app.filter.timeDescend = true;
_app.filter.detail = false;
_app.filter.onlyUnComplete = true;


var stringGetter;
var iMessage;

console.log("时间"+getFormatTime());
/*********
android端调用函数
*/
function refreshScan(e){
	//console.log("scan Event被执行一次");
	if(stringGetter != null){
		var theString = stringGetter.getString();
		_app.rfid = theString;
	}
	_app.rfid = theString;
	//扫描进来的rfid码可能是A;B;C的形式
	//分割导入，然后多次执行
	var rfids =theString.split(";");
	//如果在fillTrayDialog页面
	for(var i = 0; i<rfids.length; i++){
		//托盘页
		if($.mobile.activePage[0].id == "fillTrayDialog"){
			$.post("phpSearch.php?table=wTrays&&attr=rfid&&val="+rfids[i],function(data){
					var obj = jQuery.parseJSON(data);
					_app.currentTray = obj[0];
					updateTrayForftdPage();
					if(isNaN( parseInt(_app.currentTray.wtAppID))){
						$('#trayShortInfo').html("进仓编号:"+_app.currentApp.InStockID+"<br>"+"RFID:"+_app.currentTray.rfid+"<br>"+_app.currentTray.wtAppID);	
						
						$('#fillTrayDialogInSubmit').removeClass("ui-state-disabled");
					}
					else{
						$('#trayShortInfo').html("进仓编号:"+_app.currentApp.InStockID+"<br>"+"RFID:<span style=\"color:#933\">"+_app.currentTray.rfid+"</span>"+"<br>"+_app.currentTray.wtAppID);
						$('#fillTrayDialogInSubmit').addClass("ui-state-disabled");
						
					}
			});
		}
		if($.mobile.activePage[0].id == "theapp"){
			var hint="";
			$.post("phpSearch.php?table=wTrays&&attr=rfid&&val="+rfids[i],function(data){
					var obj = jQuery.parseJSON(data);
					_app.currentTray = obj[0];
					hint += "托盘#"+_app.currentTray.wtID+"<br>";
					if(_app.currentTray.wtAppID==""){
						hint += "可用";
					}
					else{
						hint += "已被"+_app.currentTray.wtAppID+"使用";
						_app.currentTray = null;
					}
					$('#theapphint').html(hint);	
			});
		}
		//进入托盘与RFID码绑定页面的响应
		if($.mobile.activePage[0].id == "bindTrayPage"){
			$('#DialogRfid').val(rfids[i]);
			$(".pleaseScan").html("扫描到一张rfid卡");
			$('#DialogRfidHint').html("");
		}
		//如果是扫描托盘界面
		if($.mobile.activePage[0].id == "scanTraysPage"){
			//alert(rfids[i]);
			onceScaned(rfids[i]);
			
		}
	}
	
	//console.log(_app.rfid);

}

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

function getFormatTime(){
	var currentTime = new Date();
	var month = currentTime.getMonth();
	var day = currentTime.getDate();
	var year = currentTime.getFullYear();
	var hour = currentTime.getHours();
	var miniute = currentTime.getMinutes();
	var second = currentTime.getSeconds();
	var timeStr = year+"-"+month+"-"+day+" "+hour+":"+miniute+":"+second;
	console.log("功能getFormatTime"+timeStr);
	
	return timeStr;
}
/*********
托盘扫描页的函数
*/
function onceScaned(rfid){
	var output = "";
	
	$.post("phpSearch.php?table=wTrays&&attr=rfid&&val="+rfid,function(data){
		//得到同rfid的托盘
		var obj = jQuery.parseJSON(data);
		var repeat = false;
		//console.log("fdsafdsahjk");
		//检测已存在
		$('#scanedTrayFooter').html(rfid);
		
		$('.trayli').each(function(index){
			//console.log($(this).html());
			if(String(rfid) == $(this).attr('data')){
				repeat = true;
				$('#scanedTrayFooter').html("重复了"+rfid);
				//return;
			}
		});
		if(repeat == false && obj.length){
			
				//console.log($.isNumeric(obj[0].wtAppID));
				if($.isNumeric(obj[0].wtAppID)){
					$.post("phpSearch.php?table=wApplications&&attr=appID&&val="+obj[0].wtAppID,function(dataapp){
						var objApp = jQuery.parseJSON(dataapp);
						console.log(objApp[0]);
						output += "<li class=\"trayli\" data=\""+obj[0].rfid+"\">";
						output += "<a href=\"#fillTrayDialog\" class=\"trayLink\""+"data=\""+obj[0].rfid+"\" >";
						output += "<p>托盘"+obj[0].wtID+"目前"+obj[0].twStatus+"|"+obj[0].rfid +"</p>";
						output += "<p>123"+objApp[0].InStockID+"[货物]:"+objApp[0].appName+"</p>";
						output +="</a></li>";
						$('#scanTrayContentUL').append(output);
						refreshPage($('.ui-page-active'));
						addListenerToTrayLink();
					});
				}
				else{
					output += "<li class=\"trayli\" data=\""+obj[0].rfid+"\">";
					output += "<p>托盘"+obj[0].wtID+"目前"+obj[0].twStatus+"|"+obj[0].rfid +"</p>";
					output += "</li>";
					$('#scanTrayContentUL').append(output);
					refreshPage($('.ui-page-active'));
				}
				
				
			
		}
		//已存在　退出
		//if(repeat) return false;
		
		//if(repeat === false){
			//console.log(obj);
			/*if(obj.length>0){
				if(obj[0].wtAppID != "" && obj[0].wtAppID != "NULL"){
					var tray = obj[0];
					$.post("phpSearch.php?table=wApplications&&attr=appID&&val="+tray.wtAppID,function(dataapp){
						var objApp = jQuery.parseJSON(dataapp);
						
						//console.log(objApp);
						//output += "<li>"+dataapp+"</li>";
						
						output += "<li><a href=\"#fillTrayDialog\" class=\"trayLink\"";
						output += "data=\""+obj[0].rfid+"\" count=\""+obj[0].twWareCount;
						output += "\"><p>托盘"+objApp[0].InStockID+"[货物]:"+objApp[0].appName+"</p>";
						output += "<p class=\"prevRfid\">"+tray.rfid+"</p></a>";
						output += "</li>";
						
						$('#scanTrayContentUL').append(output);
						refreshPage($('.ui-page-active'));
						
						
						addListenerToTrayLink();
						
					});
				}
			}
			else{
				output += "<li>托盘"+"[rfid]:<p class=\"prevRfid\">"+String(rfid)+"</p></li>";
				$('#scanTrayContentUL').append(output);
				refreshPage($('.ui-page-active'));	
			}*/
		//}
		
		
	});
	
}
//$('#scanTrayContent').html( $('#scanTrayContent').html() + "<br>"+theString);

/*********
表单列表页的函数
*/
function getApps(start,step,allApp,timeReverse,detail){
	//console.log(_app);
	if(typeof(start)==='undefined') start = _app.filter.loadListStart;
	if(typeof(step)==='undefined') step = _app.filter.loadListStep;
	if(typeof(allApp)==='undefined') allApp = _app.filter.allApp;
	if(typeof(timeReverse)==='undefined') timeReverse = _app.filter.timeDescend;
	if(typeof(detail)==='undefined') detail = _app.filter.detail;
		
		//console.log("getData init");
		//var url = "getAppList.php?start="+start+"&&step="+step+"&&Assigned="+allApp+"&&timeDescend="+timeReverse+"&&detail="+detail+"&&appType="+_app.filter.appType+"&&onlyUnCom="+_app.filter.onlyUnComplete;
		var url = "phpSearch.php?table=wApplications&&attr=appComplete&&val=0";
		var apps;
		$.ajax({ 
      type : "post", 
      url : url,
      async : false, 
      success : function(data){
      	//console.log(data);
      	console.log("ajax获取app列表");
      	apps = jQuery.parseJSON(data);
      	//console.log(url);
      	//console.log(apps);
      }
    });

		var input = "<li>入库</li>";//<ul data-role=\"listview\" >";
		var output = "<li>出库</li>";//<ul data-role=\"listview\" >";
		
		$.each(apps, function(i, item) {
			if(apps[i].appType == "in"){
				input += "<li><a class=\"appListItem\" href=\"#theapp\" data=\""+apps[i].wpID+"\" appID=\""+apps[i].appID+"\">";
				input += getSizedText(apps[i].appName,8,90);
				input += getSizedText(apps[i].InStockID,7,70);
				input += "入&nbsp;";
				input += getSizedText(apps[i].appCount+"件",5,25) ;
				input += "</a></li>";
			}
			else if(apps[i].appType == "out"){
				output += "<li><a class=\"appListItem\" href=\"#theapp\" data=\""+apps[i].wpID+"\" appID=\""+apps[i].appID+"\">";
				output += getSizedText(apps[i].appName,8,90);
				output += getSizedText(apps[i].InStockID,7,70);
				output += "库&nbsp;";
				output += getSizedText(apps[i].appCount+"件",5,25);
				output += "</a></li>";
			}
				
		});
		//output += "</ul>";
		$('#appInContent').html(input);
		$('#appInContent').listview("refresh");
		$('#appOutContent').html(output);
		$('#appOutContent').listview("refresh");
		addAppListListen();

}
//新更新App List 内容(st)
//更新App List 内容
function getFormatApps(start,step,allApp,timeReverse,detail){
	//console.log(_app);
	if(typeof(start)==='undefined') start = _app.filter.loadListStart;
	if(typeof(step)==='undefined') step = _app.filter.loadListStep;
	if(typeof(allApp)==='undefined') allApp = _app.filter.allApp;
	if(typeof(timeReverse)==='undefined') timeReverse = _app.filter.timeDescend;
	if(typeof(detail)==='undefined') detail = _app.filter.detail;
	//_app.filter.appType
	
	//console.log("getData init");
	var url = "getAppList.php?start="+start+"&&step="+step+"&&Assigned="+allApp+"&&timeDescend="+timeReverse+"&&detail="+detail+"&&appType="+_app.filter.appType+"&&onlyUnCom="+_app.filter.onlyUnComplete;
	//console.log("刷新App列表"+url);
	//console.log(url);
	$.post(url,function(data){
		//console.log(data);
		var obj = jQuery.parseJSON(data);
		var output="";
		//detail模式
		if(detail == false){
			 $.each(obj, function(i, item) {
				output += "<li><a class=\"appListItem\" href=\"#theapp\" data=\""+obj[i].wpID+"\" appID=\""+obj[i].appID+"\">";
				output += "<p>";
				output += "<strong>"+getSizedText(obj[i].appName,8,90)+"</strong>";
				output += getSizedText(obj[i].InStockID,7,50);

				var typeStr = "未知";
				if(obj[i].appType == "in")
					typeStr = "入库";
				else if(obj[i].appType == "out")
					typeStr = "出库";

				output += typeStr+"&nbsp;&nbsp;";
				output += getSizedText(obj[i].appCount+"件",5,25) +"</p>";
				output += "</a></li>";
			});
		 }
		 else{
			$.each(obj, function(i, item) {
				output += "<li><a class=\"appListItem\" href=\"#theapp\" data=\""+obj[i].wpID+"\" ><h3>包号:"+obj[i].wpID+"----货品名称:"+obj[i].appName;
				output +=  "<strong>----操作类型"+obj[i].appType+"</strong></h3><h3>操作人:"+obj[i].appOperator+"-----麦头:"+obj[i].appMaitou+"-----货物量:"+obj[i].appCount+"</h3></a>";
				output += "</li>";
			}); 
		 }
		//增加到尾部
		$("#appContent").append(output);
		//刷新样式
		//更新起始、组数
		_app.filter.loadListStart = parseInt(start)+parseInt(step);
		//console.log(_app);
		addAppListListen();
		/*
		if(obj.length>0){
			refreshPage($('#appListPage ul'));
		}*/
	});
}
//加载更多app
function loadMoreItem(){
	getApps();
}
/*
if(iMessage != null){
	iMessage.show("JS 运行成功，点击back返回系统");
}
*/
//刷新页面
function refreshPage(page){
	page.trigger('pagecreate'); 
	$('.ui-page-active ul').listview('refresh');
}

/*********
表单页的函数
*/
//给page #theapp的格式化
function getFormatAppPage(idx){
//根据appid显示app页面
	$.post("getAppList.php?idx="+idx,function(data){
		//console.log("$.post(getAppList.php?idx="+idx);
		var obj = jQuery.parseJSON(data);
		//显示上半部分内容
		var output = "";
		output += "<ul data-role=\"listview\" id=\"appPageUL\">";
		
		output += "<li><p>"+getSizedText("货物包 "+obj[0].wpID,8,60)+" | ";
		output += getSizedText("名称 "+obj[0].appName,10,100)+" | ";
		output += getSizedText("麦头 "+obj[0].appMaitou,10,100)+"</p></li>";
		output += "<li><p>"+getSizedText("更新时间 "+obj[0].appBookingDate,24,179)+" | ";
		output += getSizedText("装载 "+obj[0].appCount,10,100)+"</p></li>";
		output += "<li class=\"ui-field-contain\" data-role=\"list-divider\">托盘列表 </li>";
		output += "</ul>";
		$('#theappContent').html(output);

		//对dialogForm复制
		$('#dialogAppID').val(obj[0].appID);
		var totalCount = obj[0].appCount;
		var onTrayCount = 0;
		var onTruckCount = 0;
		
	//Search Trays 查询对应属于App的托盘
		//按表单类型分别
		var attrStr = "&&attr=wtAppID&&val="+obj[0].appID;
		if(obj[0].appType == "out"){
			attrStr = "&&attr=wtAppoutID&&val="+obj[0].appID;
		}
		$.post("phpSearch.php?table=wTrays"+attrStr,function(data){
			var obj = jQuery.parseJSON(data);
			//console.log(obj);
			//全部托盘都在货架上的状态量 状态量保持真则可以完成入库
			var StatusAllShelf = true;
			//全部托盘都在叉车(使用中的)的状态量 状态量保持真则可以完成出库
			var StatusAllTruck = true;
			
			var output ="";
			for(var i=0; i<obj.length; i++){
				output += "<li><a href=\"#fillTrayDialog\" class=\"trayLink\" data=\""+obj[i].rfid+"\" count=\""+obj[i].twWareCount+"\" style=\"padding:5px 40px;\">";
				
				//console.log("这是在哪？");
				//console.log(_app.currentApp.appType);
				//console.log(_app.currentApp.appType == "in");
				//console.log(data);
				
				if(obj[i].twStatus == "仓库内" || obj[i].twStatus == "仓库外"){
					output += "<img src=\"resource/inUse.png\" alt=\"使用中\" class=\"ui-li-icon ui-corner-none\">";
					onTruckCount += parseInt(obj[i].twWareCount);
					if(StatusAllShelf)
						StatusAllShelf = false;
					if(StatusAllTruck)
						StatusAllTruck = false;
				}
				if(obj[i].twStatus == "货架"){
					output += "<img src=\"resource/inSlot.png\" alt=\"货架\" class=\"ui-li-icon ui-corner-none\">";
					if(StatusAllTruck)
						StatusAllTruck = false;
				}
				output += "<p class=\"status-otw\">";
				output += getSizedText("托盘"+obj[i].wtID,8,70)+getSizedText(obj[i].UpdateTime.split(' ')[0]+"|"+obj[i].UpdateTime.split(' ')[1],19,80);
				output += "</p>";
				
				$.ajax({ 
          type : "post", 
          url : "phpSearch.php?table=wareUnit&&attr=trayID&&val="+obj[i].wtID,
          async : false, 
          success : function(data){
          	var units = jQuery.parseJSON(data); 
            console.log("got"+units);
            for(var j=0; j<units.length; j++){
            	output += "<p>";
							output += getSizedText("单品 "+units[j].count+"箱",10,70);
							output += getSizedText("尺寸:"+units[j].width+"x"+units[j].length+"x"+units[j].height+"cm ",20,120);
							output += getSizedText("重量"+units[j].count+"kg",10,50);
							output += "</p>";

            }
          } 
        }); 

				output += "</a></li>";
				console.log("post sequence");

				onTrayCount　+= parseInt(obj[i].twWareCount);
			}
			if(_app.currentApp.appType == "in"){
				$('#theapp h1').html("入库单:"+_app.currentApp.appID);
				output += "<li><p>"+getSizedText("货物装载："+onTrayCount+"/"+totalCount+"箱",20,160);
				output += getSizedText("完成度："+100*(onTrayCount/totalCount).toFixed(3)+"%",12,120)+"</p></li>";
				
				//激活下方按钮
				$('#addNewTrayForApp').show();
				$('#addNewTrayForApp').unbind().on('click',function(){
					if(!_app.currentTray){
						$('#theapphint').html("请先扫描空托盘");
					}
					else{
						$('#theapphint').html("增加托盘"+_app.currentTray.rfid);
						$.mobile.navigate("#fillTrayDialog");
					}
				});

				//$('.addNewTray').removeClass("ui-state-disabled");
				//$('.inComeBtn').removeClass("ui-state-disabled");
			}
			if(_app.currentApp.appType == "out"){
				$('#theapp h1').html("出库单:"+_app.currentApp.appID);
				output += "<li><p style=\"lightBlue\">"+onTrayCount+"箱货物等待卸货</p></li>";
				
				//激活下方按钮
				$('#addNewTrayForApp').hide();
				//$('#inComeBtn').addClass("ui-state-disabled");
			}
			//console.log(onTrayCount);
			//表头
			//console.log(obj[0].appType);
			
			$(output).appendTo('#appPageUL');
			

			$("#appCompleteBtn").click(function(){
				console.log("总数"+totalCount+";托盘上货物数量:"+onTrayCount+";任务类型"+_app.currentApp.appType);
				//$('body').animate({ scrollTop: $("#theapphint").offset() });
				$('#theapphint').html("总数"+totalCount+";托盘上货物数量:"+onTrayCount+";任务类型"+_app.currentApp.appType);
				
				//完成任务动作	 
				if( (totalCount == onTrayCount)&&(StatusAllShelf)&&(_app.currentApp.appType == "in") ){
					$('#theapphint').html( $('#theapphint').html() + "<br>正在进行完成任务");
					$.post("phpUpdate.php?table=wApplications&&idAttr=appID&&idValue="+_app.appSelected+"&&tAttr=appComplete&&tValue=1",function(data){
						if(data == "1"){
							$('#theapphint').html( "此任务<br>已经完成");
							//完成入任务
							var memo ={actUserID:userID,actType:"finAppIn",actTime:getFormatTime(),InStockID:_app.currentApp.InStockID,appID:_app.currentApp.appID,actContent:userName+"完成了"+_app.currentApp.appID+"任务"+totalCount+"箱"+_app.currentApp.appName+"的入库"};
							addMemo(memo);
						}
						else{
							$('#theapphint').html( "此任务完成失败");
						}
					});
				}
				else if( (0 == onTrayCount)&&(StatusAllTruck)&&(_app.currentApp.appType == "out") ){
					$('#theapphint').html( $('#theapphint').html() + "<br>正在进行出库任务"+"<br>需要完整代码enter-js.js 425line");
					$.post("phpUpdate.php?table=wApplications&&idAttr=appID&&idValue="+_app.appSelected+"&&tAttr=appComplete&&tValue=1",function(data){
						if(data == "1"){
							$('#theapphint').html( "此任务<br>已经完成");
							//完成任务
							var memo ={actUserID:userID,actType:"finAppOut",actTime:getFormatTime(),InStockID:_app.currentApp.InStockID,appID:_app.currentApp.appID,actContent:userName+"完成了"+_app.currentApp.appID+"任务"+totalCount+"箱"+_app.currentApp.appName+"的出库"};
							addMemo(memo);
						}
						else{
							$('#theapphint').html( "此任务完成失败");
						}
					});
				}
				else{
					$('#theapphint').html("货物数量:"+totalCount+"<br>托盘内数量:"+onTrayCount+"<br>不匹配完成条件:<br>入库需要所有托盘入货架，货物齐备<br>出库需要托盘解除绑定");
				}
				$(window).scrollTop($("#theapphint").offset());
			});

			$("#theappContent").trigger("create");
			$('.footer').trigger('create'); 
			
			/*修改某一个托盘 这部分代码只负责传递数据　不负责显示　显示在pageshow*/
			addListenerToTrayLink();
		
			
		});
	//Search Trays End
			
			

		
		$('#dialogInStockID').val(obj[0].InStockID);
		
		//查询对应wTrays确定仓位信息
		
	
		
	});
}

function addAppListListen(){
	$('.appListItem').unbind('click').click(function(e){
			console.log("某个货单被点击");
			_app.wpSelected = parseInt($(this).attr('data'));
			_app.appSelected = parseInt($(this).attr('appID'));
			$.post("phpSearch.php?table=wApplications&&attr=appID&&val="+_app.appSelected,function(data){
					var obj = jQuery.parseJSON(data);
					_app.currentApp = obj[0];
				});
	});
}

function homedisplayTime(){
	$('#curTime').html(getTime());
	setTimeout('homedisplayTime()',1000);
}
$().ready(function() {
	console.log("开始ready");
	
	$('#taskWaited').html("");
	$.getJSON('getAppList.php?type=out&&unfinished=true',function(data){
		$('#taskWaited').append("目前等待出库任务"+data.length+"件<br>");
	});
	$.getJSON('getAppList.php?type=in&&unfinished=true',function(data){
		$('#taskWaited').append("目前等待入库任务"+data.length+"件<br>");
	});
	
	//getFormatApps();
	//refreshScan();
	setTimeout('homedisplayTime()',1000);
	
	$("div#page").on("pageshow",function(e){
		console.log("再次进入首页");
		setTimeout('homedisplayTime()',1000);
		$('#taskWaited').html("");
		$.getJSON('getAppList.php?type=out&&unfinished=true',function(data){
			$('#taskWaited').append("目前等待出库任务"+data.length+"件<br>");
		});
		$.getJSON('getAppList.php?type=in&&unfinished=true',function(data){
			$('#taskWaited').append("目前等待入库任务"+data.length+"件<br>");
		});
		$("#appContent").html("");
		_app.filter.loadListStart = 0;
		_app.filter.loadListStep = $('#slider-fill-mini').val();
		//getFormatApps();
	});
	
	//进入App列表页面
	$("div#appListPage").on("pageshow",function(e){
		console.log("进入应用Page");
		
    getApps();
            
		//绑定 option panel的关闭事件;
		$( "#mypanel" ).panel({
		  close: function( event, ui ) {
			  //如果只筛选签字过的
			  if($('#filter-assigned:checked').val()=="on"){
				  _app.filter.allApp = true;
			  }
			  else{
				  _app.filter.allApp = false;
			  }
			  //筛选时间顺序
			  if($('#timeDescend-On:checked').val()=="on"){
				  _app.filter.timeDescend = true;
			  }
			  else{
				  _app.filter.timeDescend = false;
			  }
			  //筛选细节显示
			  if($('#filter-detail:checked').val()=="on"){
				  _app.filter.detail = true;
			  }
			  else{
				  _app.filter.detail = false;
			  }
			  
			  //出入库筛选
			  if($('#appType-in:checked').val()=="on"){
				  _app.filter.appType = "in";
			  }
			  else{
				  _app.filter.appType = "out";
			  }
			  //筛选未完成
			  if($('#filter-unComplete-yes:checked').val()=="on"){
				  _app.filter.onlyUnComplete = true;
			  }
			  else{
				  _app.filter.onlyUnComplete = false;
			  }
			  //console.log($('#filter-unComplete-yes:checked'));
			  
			  //console.log("筛选入库"+_app.filter.appType);
			  $("#appContent").html("");
			  _app.filter.loadListStart = 0;
			  _app.filter.loadListStep = $('#slider-fill-mini').val();
			  getApps();
			  
		   }
		});
		//getFormatApps();
	});
	
	//进入某个库单页面
	$("div#theapp").on("pageshow",function(e){

		
		getFormatAppPage(_app.appSelected);
		console.log("进入库单页面");
		$('#theapphint').html("");
		//console.log($('.addNewTray'));
		//每次进入库单页面尝试清理正用托盘
		_app.currentTray = null;
	});
	
	//rfid卡绑定的刷新信息
	$("div#bindTrayPage").on("pageshow",function(e){
		$(".pleaseScan").html("请扫描");
		$('#DialogRfidHint').html("");
	});
	
	
	
	//进入托盘信息更改页面
	$("div#fillTrayDialog").on("pageshow",function(e,data){
		//刷新托盘显示
		showUnitList();

		$('#fillTrayPage_app').html("什么都没有");
		$('#bindTray2AppBtnHint').html("");
		//托盘动作界面刷新function
		updateTrayPageFunction();
		

		//解除托盘绑定
		$('#releaseTray').unbind("click").on('click',function(){
			//针对wTrays的操作，先查询wtID下的Uint，托盘为空后
			if($("#releaseTray").attr("trayEmpty") == "true"){
				$('#bindTray2AppBtnHint').html("托盘为空，可以继续");
				
				$.get("phpUpdate.php?table=wTrays&&idAttr=wtID&&idValue="+_app.currentTray.wtID+"&&tAttr=wtAppID&&tValue=");
				$.get("phpUpdate.php?table=wTrays&&idAttr=wtID&&idValue="+_app.currentTray.wtID+"&&tAttr=wtAppOutID&&tValue=");
				$.get("phpUpdate.php?table=wTrays&&idAttr=wtID&&idValue="+_app.currentTray.wtID+"&&tAAttr=wpID&&tValue=");
				$.get("phpUpdate.php?table=wTrays&&idAttr=wtID&&idValue="+_app.currentTray.wtID+"&&tAAttr=updateTime&&tValue="+getFormatTime());
				$.get("phpUpdate.php?table=wTrays&&idAttr=wtID&&idValue="+_app.currentTray.wtID+"&&tAttr=twStatus&&tValue=空闲");
				$('#bindTray2AppBtnHint').html("托盘已和此货单解除关系");
				updateTrayPageFunction();
				//解除托盘绑定，需要记录托盘ID，时间，stockID，人名
				var memo ={actUserID:userID,actType:"unbindTray",actTime:getFormatTime(),InStockID:_app.currentApp.InStockID,appID:_app.currentApp.appID,trayID:_app.currentTray.wtID,actContent:userName+"解除了"+_app.currentTray.wtID+"托盘和任务的关系"};
				addMemo(memo);

			}
			else{
				$('#bindTray2AppBtnHint').html("托盘不为空，先卸下货物");
			}
		});
		//设置select范围
		$('#unitUnloadSelect').unbind("blur").on('blur',function(){
			console.log("unitUnloadSelect onBlur");
			 var checkText=$("#unitUnloadSelect").find("option:selected").attr("maxCount");
			 console.log("max:"+checkText); 
			 $('#UnitToRemoveNum').attr("max",parseInt(checkText)).attr("min",parseInt(1));
			 $('#UnitToRemoveNum').on('blur',function(){
			 		console.log("离开卸下数量input"+$('#UnitToRemoveNum').val());
			 		if($('#UnitToRemoveNum').val()>parseInt($('#UnitToRemoveNum').attr('max'))){
			 			$('#UnitToRemoveNum').val(parseInt(checkText));
			 			console.log("强行设置为"+checkText);
			 		}
			 		if($('#UnitToRemoveNum').val()<1){
			 			$('#UnitToRemoveNum').val(1);
			 			console.log("强行设置为1");
			 		}
			 });
		});

		//全部卸下动作 修改unit的库单、托盘号、数量、时间
  	$('#removeUnit').unbind("click").on("click",function(data){
  		console.log("卸下一次货物，全部"+$('#unitUnloadSelect').val());
			$.get("phpSearch.php?table=wareUnit&&attr=wiID&&val="+$('#unitUnloadSelect').val(),function(result){
				console.log(result);
				var unit = jQuery.parseJSON(result);
				$('#TrayDialogFormHint4Remove').html(unit[0].wiName+"货物"+unit[0].count+"件全部取出");
			});
			$.get("phpUpdate.php?table=wareUnit&&idAttr=wiID&&idValue="+$('#unitUnloadSelect').val()+"&&tAttr=appID&&tValue=");
			$.get("phpUpdate.php?table=wareUnit&&idAttr=wiID&&idValue="+$('#unitUnloadSelect').val()+"&&tAttr=trayID&&tValue=");
			$.get("phpUpdate.php?table=wareUnit&&idAttr=wiID&&idValue="+$('#unitUnloadSelect').val()+"&&tAttr=count&&tValue=0");
			$.get("phpUpdate.php?table=wareUnit&&idAttr=wiID&&idValue="+$('#unitUnloadSelect').val()+"&&tAttr=updateTime&&tValue="+getFormatTime());
			//$.get("phpUpdate.php?table=wareUnit&&idAttr=wiID&&idValue="+$('#unitUnloadSelect').val()+"&&tAttr=wiCloseNote&&tValue=appID"+_app.currentApp.appID+"count"+_app.currentApp.appID");
  		showUnitList();
  		updateTrayPageFunction();
  		var memo ={actUserID:userID,actType:"unloadStock",actTime:getFormatTime(),InStockID:_app.currentApp.InStockID,appID:_app.currentApp.appID,trayID:_app.currentTray.wtID,actContent:userName+"卸下了"+_app.currentTray.wtID+"托盘的货物,"+unit[0].wiName+"货物"+unit[0].count+"箱"};
			addMemo(memo);
  	});
		//部分卸下动作，提取数量，
  	$('#removePartUnit').unbind("click").on("click",function(data){
  		console.log("卸下一次货物，部分");

  		$.get("phpSearch.php?table=wareUnit&&attr=wiID&&val="+$('#unitUnloadSelect').val(),function(result){
				console.log(result);

				var unit = jQuery.parseJSON(result);
				var result = unit[0].count-$('#UnitToRemoveNum').val();

				if(result > 0){
					$('#TrayDialogFormHint4Remove').html(unit[0].wiName+"货物"+$('#UnitToRemoveNum').val()+"件被取出,剩余"+result+"件");
					$.get("phpUpdate.php?table=wareUnit&&idAttr=wiID&&idValue="+$('#unitUnloadSelect').val()+"&&tAttr=count&&tValue="+result);
					showUnitList();
		  		updateTrayPageFunction();
		  		var memo ={actUserID:userID,actType:"unloadStock",actTime:getFormatTime(),InStockID:_app.currentApp.InStockID,appID:_app.currentApp.appID,trayID:_app.currentTray.wtID,actContent:userName+"卸下了"+_app.currentTray.wtID+"托盘中"+$('#UnitToRemoveNum').val()+"箱货物,剩余"+result+"箱"};
					addMemo(memo);
				}
				else if(result==0){
					$.get("phpUpdate.php?table=wareUnit&&idAttr=wiID&&idValue="+$('#unitUnloadSelect').val()+"&&tAttr=appID&&tValue=");
					$.get("phpUpdate.php?table=wareUnit&&idAttr=wiID&&idValue="+$('#unitUnloadSelect').val()+"&&tAttr=trayID&&tValue=");
					$.get("phpUpdate.php?table=wareUnit&&idAttr=wiID&&idValue="+$('#unitUnloadSelect').val()+"&&tAttr=count&&tValue=0");
					$.get("phpUpdate.php?table=wareUnit&&idAttr=wiID&&idValue="+$('#unitUnloadSelect').val()+"&&tAttr=updateTime&&tValue="+getFormatTime());
					showUnitList();
					updateTrayPageFunction();
					var memo ={actUserID:userID,actType:"unloadStock",actTime:getFormatTime(),InStockID:_app.currentApp.InStockID,appID:_app.currentApp.appID,trayID:_app.currentTray.wtID,actContent:userName+"卸下了"+_app.currentTray.wtID+"托盘的货物"};
					addMemo(memo);	
				}
				else{
					$('#TrayDialogFormHint4Remove').html("取出失败货物不够");
				}
			});
  	});
		
		//给几个select增加blur动作
		//选中仓动作
		$('#slotHouse').unbind("change").on('change',function(){
				$.ajax({ 
		    type : "post", 
		    url : "phpGetSlot.php?tsWareHouse="+$('#slotHouse').val(),
		    async : false, 
		    success : function(data){
		    	console.log("查询"+$('#slotHouse').val()+"仓可用行");
		    	slots = jQuery.parseJSON(data);
		    	var opt = "<option>default</option>";
		    	for (var i = slots.length - 1; i >= 0; i--) {
		    		opt += "<option value=\""+slots[i].tsPosRow+"\" >第"+slots[i].tsPosRow+"行</option>";
		    	}
		    	$('#slotRow').html(opt);
		    	$('#slotHouse').selectmenu("refresh");
					$('#slotRow').selectmenu("refresh");
					$('#tray2ShelfHelperNavHint').html("自选货架:"+$('#slotHouse').val()+"-"+$('#slotRow').val()+"-"+$('#slotCol').val()+"-"+$('#slotFloor').val());
		    }

			});
		});
		//选中行动作
		$('#slotRow').unbind("change").on('change',function(){
				$.ajax({ 
		    type : "post", 
		    url : "phpGetSlot.php?tsWareHouse="+$('#slotHouse').val()+"&&tsPosRow="+$('#slotRow').val(),
		    async : false, 
		    success : function(data){
		    	slots = jQuery.parseJSON(data);
		    	var opt ="<option>default</option>";
		    	for (var i = slots.length - 1; i >= 0; i--) {
		    		opt += "<option value=\""+slots[i].tsPosCol+"\" >第"+slots[i].tsPosCol+"列</option>";
		    	}
		    	$('#slotCol').html(opt);
					$('#slotCol').selectmenu("refresh");
					$('#slotRow').selectmenu("refresh");
					$('#tray2ShelfHelperNavHint').html("自选货架:"+$('#slotHouse').val()+"-"+$('#slotRow').val()+"-"+$('#slotCol').val()+"-"+$('#slotFloor').val());
		    }
			});
		});
		//选中列动作
		$('#slotCol').unbind("change").on('change',function(){
				$.ajax({ 
		    type : "post", 
		    url : "phpGetSlot.php?tsWareHouse="+$('#slotHouse').val()+"&&tsPosRow="+$('#slotRow').val()+"&&tsPosCol="+$('#slotCol').val(),
		    async : false, 
		    success : function(data){
		    	slots = jQuery.parseJSON(data);
		    	var opt = "<option>default</option>";
		    	for (var i = slots.length - 1; i >= 0; i--) {
		    		opt += "<option value=\""+slots[i].tsPosFloor+"\" >第"+slots[i].tsPosFloor+"层</option>";
		    	}
		    	$('#tray2ShelfHelperNavHint').html("自选货架:"+$('#slotHouse').val()+"-"+$('#slotRow').val()+"-"+$('#slotCol').val()+"-"+$('#slotFloor').val());
		    
		    	$('#slotFloor').html(opt);
					$('#slotCol').selectmenu("refresh");
					$('#slotFloor').selectmenu("refresh");
					}
			});
		});
		//选中层动作
		$('#slotFloor').unbind("change").on('change',function(){
			$('#tray2ShelfHelperNavHint').html("自选货架:"+$('#slotHouse').val()+"-"+$('#slotRow').val()+"-"+$('#slotCol').val()+"-"+$('#slotFloor').val());
			
			$('#slotFloor').selectmenu("refresh");
		});

		//上架动作
		$('#load2Shelf').unbind("click").on("click",function(){
			if(_app.currentTray.twStatus == "仓库内"){
				//如果托盘是在仓库内才能上架
				
			}
			else {
				$("#tray2ShelfHelperNavHint").html("托盘还在仓库外，需要先运进仓库才能执行上架操作");
				return;
			}
			//检查输入是否正确
			console.log("开始上架");
			var pos = $('#tray2ShelfHelperNavHint').html().split(':');
			
			if(pos.length != '2'){
				console.log("位置不正确");
				console.log(pos);
				console.log(pos.length);
			}
			else{
				var para = pos[1].split('-');
				if( para.length != 4){
					console.log("位置不正确,不够4个参数");
				}
				else{
					var _isNum = true;
					for(var i in para){
						if(isNaN(parseInt(para[i],10))){
							_isNum = false;
							break;
						}
					}
					if(_isNum){
						//参数正确，开始上架
						var slotID = 0;
						$.ajax({ 
					    type : "post", 
					    url : "phpSearch.php?table=wSlots",
					    async : false, 
					    success : function(data){
					    	slots = jQuery.parseJSON(data);
					    	for(var i in slots){
					    		if(slots[i].tsWareHouse == para[0] && slots[i].tsPosRow == para[1] && slots[i].tsPosCol == para[2] && slots[i].tsPosFloor == para[3] ){
						    		
						    		//找到托盘
						    		if(slots[i].wtID == 0){
						    			//为空
						    			slotID = slots[i].wSlotID;
						    			console.log("找到合适仓位"+slots[i].wSlotID);
						    			break;
						    		}
						    		else{
						    			console("找到合适仓位，并不为空");
						    		}
					    		}
					    	}
					    }
					  });
						//上架按slot位置，修改slot的wtID
						if(slotID != 0){
								console.log("修改Slot的托盘号"+_app.currentTray.wtID);
								if( $.get("phpUpdate.php?table=wSlots&&idAttr=wSlotID&&idValue="+slotID+"&&tAttr=wtID&&tValue="+_app.currentTray.wtID) == "0")
									console.log("修改slot的wtID出错");
						}
						//修改trays的状态、wSlotID updateTime
						if(slotID != 0){
								
								if( $.get("phpUpdate.php?table=wTrays&&idAttr=wtID&&idValue="+_app.currentTray.wtID+"&&tAttr=wSlotID&&tValue="+slotID) == "0")
									console.log("修改Trays的托盘位出错");
								if( $.get("phpUpdate.php?table=wTrays&&idAttr=wtID&&idValue="+_app.currentTray.wtID+"&&tAttr=updateTime&&tValue="+getFormatTime()) == "0")
									console.log("修改Trays的更新时间出错");
							  if( $.get("phpUpdate.php?table=wTrays&&idAttr=wtID&&idValue="+_app.currentTray.wtID+"&&tAttr=twStatus&&tValue=货架",function(){
							  	updateTrayPageFunction();
							  }) == "0")
									console.log("修改Trays的状态出错");
								
								
						}
						$("#tray2ShelfHelperNavHint").html("托盘"+_app.currentTray.wtID+"号放入"+slotID+"仓位");
						$('#tray2ShelfHint').html("托盘已入货架");
						
						//上货架
						var memo ={actUserID:userID,actType:"trayLoad",actTime:getFormatTime(),InStockID:_app.currentApp.InStockID,appID:_app.currentApp.appID,trayID:_app.currentTray.wtID,slotID:slotID,actContent:userName+"操作"+_app.currentTray.wtID+"托盘上架"};
						addMemo(memo);
						showUnitList();
						
					}//参数正确，上架if 结束
				}
			}
		});//上架动作结束

		//下架动作
		$("#unload4Shelf").unbind("click").on('click',function(){
			//查询此托盘对应app是否为未完成
			//补丁
			var phpSearch = "phpSearch.php?table=wApplications&&attr=appID&&val="+_app.currentTray.wtAppID;
			if(_app.currentApp.appType == "out")
				phpSearch = "phpSearch.php?table=wApplications&&attr=appID&&val="+_app.currentTray.wtAppOutID;
			//如果未完成，下架动作不成功
			$.ajax({ 
		    type : "post", 
		    url : phpSearch,
		    async : false, 
		    success : function(data){
		    	apps = jQuery.parseJSON(data);
		    	if(apps[0].appComplete == "1"){
		    		$('#tray2ShelfHint').html('托盘所属任务已完成，无法下架');
		    	}
		    	else{
		    		//根据托盘ID获取仓位ID
						console.log( _app.currentTray.wSlotID);
						//下架按slotID，修改slot的wtID 至0
						if( $.get("phpUpdate.php?table=wSlots&&idAttr=wSlotID&&idValue="+_app.currentTray.wSlotID+"&&tAttr=wtID&&tValue=0") == "0")
							console.log("修改slot的wtID出错");
						//修改wtray的状态至　仓库内
						if( $.get("phpUpdate.php?table=wTrays&&idAttr=wtID&&idValue="+_app.currentTray.wtID+"&&tAttr=twStatus&&tValue=仓库内",function(data){
							updateTrayPageFunction();
						}) == "0")
							console.log("修改Trays的状态出错");
						if( $.get("phpUpdate.php?table=wTrays&&idAttr=wtID&&idValue="+_app.currentTray.wtID+"&&tAttr=wSlotID&&tValue=0") == "0")
							console.log("修改Trays的托盘位出错");
						if( $.get("phpUpdate.php?table=wTrays&&idAttr=wtID&&idValue="+_app.currentTray.wtID+"&&tAttr=updateTime&&tValue="+getFormatTime()) == "0")
							console.log("修改Trays的更新时间出错");

						$('#tray2ShelfHint').html('托盘进行下架动作');
						//下架记录 ref
						var memo ={actUserID:userID,actType:"trayUnload",actTime:getFormatTime(),InStockID:_app.currentApp.InStockID,trayID:_app.currentTray.wtID,appID:_app.currentApp.appID,slotID:_app.currentTray.wSlotID,actContent:userName+"操作"+_app.currentTray.wtID+"托盘下架"};
						addMemo(memo);
						showUnitList();
		    	}
		    }
		  });
			
  		
		});
		//绑定动作任务
		$('#bindTray2AppBtn').unbind("click").on("click",function(){
			console.log("bindTray2AppBtn wokrs");
			//未扫描托盘
			if(!_app.currentTray){
				$('#bindTray2AppBtnHint').html("请先扫描空托盘");
			}
			else{
				//托盘已用，包括本app
				if(_app.currentTray.wtAppID != ""){
					$('#bindTray2AppBtnHint').html("此托盘已经存在于某个App中，请先清理托盘");
				}
				else{
					//修改托盘的状态，app归属
					var type = "";
					if(_app.currentApp.appType == "in"){ type = "&&tAttr=wtAppID&&tValue="+_app.currentApp.appID; }
					else if( _app.currentApp.appType == "out"){ type = "&&tAttr=wtOutAppID&&tValue="+_app.currentApp.appID; }

					$.get("phpUpdate.php?table=wTrays&&idAttr=rfid&&idValue="+_app.currentTray.rfid+type,function(result){
						if(result == "1"){
	          		$('#bindTray2AppBtnHint').html("托盘绑定成功");
	          		$.get("phpUpdate.php?table=wTrays&&idAttr=rfid&&idValue="+_app.currentTray.rfid+"&&tAttr=twStatus&&tValue=仓库外");
	          		$.get("phpUpdate.php?table=wTrays&&idAttr=rfid&&idValue="+_app.currentTray.rfid+"&&tAttr=wpID&&tValue="+_app.currentApp.wpID);
	          		$.get("phpUpdate.php?table=wTrays&&idAttr=rfid&&idValue="+_app.currentTray.rfid+"&&tAttr=UpdateTime&&tValue="+getFormatTime());
          			showUnitList();
 						 		updateTrayPageFunction();
 						 		//绑托盘记录
 						 		var memo ={actUserID:userID,actType:"bindTray",actTime:getFormatTime(),InStockID:_app.currentApp.InStockID,appID:_app.currentApp.appID,trayID:_app.currentTray.wtID,actContent:userName+"对"+_app.currentApp.InStockID+"绑定了一个新托盘"+_app.currentTray.wtID};
								addMemo(memo);
          	}
          	else{
          		$('#bindTray2AppBtnHint').html("绑定失败");
          		console.log(result);
          	}
					});	
				}
      }
		});

		//对托盘添加货物的动作
		$("#addUnit2Item").unbind("click").on("click",function(){
				//给隐藏项赋值
			$('#appID').val(_app.currentApp.appID);
			$('#trayID').val(_app.currentTray.wtID);
			$('#wiName').val(_app.currentApp.appName);
			console.log("addUnit2Item click 执行了一次");
			//使用php添加动作
			$.post( "addTrayRecord.php", $( "#addUnitTrayDialogForm" ).serialize())
				.done(function(data){
				//console.log(data);
				if(data[0]=="1"){
					$('#TrayDialogFormHint4add').html("成功添加");
					showUnitList();
					//上货记录
				 	var memo ={actUserID:userID,actType:"loadStock",actTime:getFormatTime(),InStockID:_app.currentApp.InStockID,appID:_app.currentApp.appID,trayID:_app.currentTray.wtID,actContent:userName+"对"+_app.currentApp.InStockID+"下的"+_app.currentTray.wtID+"托盘装载了"+$('#dialogtrayCaseCount').val()+"箱货物"};
					addMemo(memo);
				}
				else{
					console.log(data);
					$('#TrayDialogFormHint4add').html("增加货物失败"+data);
				}
				showUnitList();
			});
		});		
	});

	
	//进入某个库单页面
	$("div#InfoPage").on("pageshow",function(e){
		console.log("进入信息显示界面");
		loadGraph();
		//console.log($('.addNewTray'));
	});
		 
	console.log("结束ready.js");
});//结束ready
	
	
	//绑定托盘的列表页
	$("div#trayListPage").on("pageshow",function(e){
		$.post("phpSearch.php?table=wTrays",function(data){
				var output="<ul data-role=\"listview\" data-theme=\"e\" data-divider-theme=\"b\" ";
				output +=" data-filter=\"true\" data-filter-placeholder=\"搜索托盘关键字\" data-inset=\"true\" >";
				var obj = jQuery.parseJSON(data);
				for(var i = 0; i<obj.length; i++){
					output += "<li><a href=\"#bindTrayPage\"";
					if(obj[i].twStatus == "空闲"){
						output += " data-theme=\"e\" ";
					}
					if(obj[i].twStatus == "仓库内" || obj[i].twStatus == "仓库外" ){
						output += " data-theme=\"b\" ";
					}
					output += " class=\"trayClick\" id=\""+obj[i].wtID+"\" rfid=\""+obj[i].rfid+"\">";
					output += obj[i].wtID + "#" + obj[i].rfid;
					output += "</a></li>";
				}
				output += "</ul>"
				$('#trayList').html(output);
				$('#trayList').trigger("create");
				//添加点击某个托盘的事件
				$('.trayClick').click(function(data){
					//console.log($(this).attr('id')+$(this).attr('rfid'));
					$('#DialogTrayID').val($(this).attr('id'));
					if($(this).attr('rfid')!='undefined')
					$('#DialogRfid').val($(this).attr('rfid'));
					});
				$('#applyBindBtn').click(function(){
						$.post( "addTrayRecord.php", $( "#BindTrayDialogForm" ).serialize())
						.done(function(data){
							//console.log("addTrayRecord#BindTrayDialogForm");
							if(data==1){
								$('#DialogRfidHint').html("绑定成功");	
							}
							else{
								$('#DialogRfidHint').html(data);	
							}
						});
				});
				
				$('#applyUnbindBtn').click(function(){
						console.log('unbind click');
						$('#DialogRfid').val("unbind");
						$.post( "addTrayRecord.php", $( "#BindTrayDialogForm" ).serialize())
						.done(function(data){
							//console.log("addTrayRecord#BindTrayDialogForm");
							if(data==1){
								$('#DialogRfidHint').html("解除绑定成功");	
							}
							else{
								$('#DialogRfidHint').html(data);	
							}
						});
				});
				
			});
	
	});
	
	//仓位选择
	$("div#selectTrayPanel").panel({
		open:function(event,ui){
			console.log("仓位选择");
			//每次清空Div内容
			$('#slotOptionDiv').html("");
			phpLoadSlotSelect();
		}
	});
	
	//尺寸选择
	$('div#selectSize').panel({
		open:function(event,ui){
			var str= "<form method=\"POST\" id=\"AddNewSizeForm\">";
			str += "<label for=\"length\">长度:(单位cm)</label><input type=\"number\" name=\"length\" placeholder=\"长\"  >";
			str += "<label for=\"length\">宽度:(单位cm)</label><input type=\"number\" name=\"width\" placeholder=\"宽\">";
			str += "<label for=\"length\">高度:(单位cm)</label><input type=\"number\" name=\"height\" placeholder=\"高\">";
			str += "<label for=\"length\">重量:(单位kg)</label><input type=\"text\" name=\"weight\" placeholder=\"重量\">";
			str += "<a class=\"ui-btn\" id=\"addNewSize\">添加</a>"
			str += "</form>";
			$('#selectSizeMenu').html(str);	
			
			$("#addNewSize").on("click", function(){
				$.post( "itemDimension.php?addNew=true", $( "#AddNewSizeForm" ).serialize())
				.done(function(data){
					console.log(data);
				});
			});
		},
		close:function(event,ui){
			//关闭是重新载入尺寸选项
			console.log("关闭选相框");
			$.getJSON('ItemDimension.php?getLi=true',function(data){
				console.log(data);
				$('#trayCaseSize').empty();
				for(var i in data){
					var label = data[i].length+"cm * "+data[i].width+"cm * "+data[i].height+"cm   "+data[i].weight+"kg";
					$('#trayCaseSize').append("<option value=\""+data[i].wiID+"\">"+label+"</option>");
				}
				$('#trayCaseSize').selectmenu("refresh");
			});	
		}
		
		
	});
		
	
function onPCTest(){
	getTrayByRfid("123456789321");
	//$('#afterScan').css("display","block");
	//$('#DialogRfidHint').css("display","block");
	//$('.pleaseScan').css("display","none");
}


//查找RFID码对应的托盘，并确认属性可用，更新DialogRfidHint显示文字
function getTrayByRfid(rfid){
	$.post("phpSearch.php?rfid="+rfid,function(data){
		//console.log("getTrayByRfid(rfid)");
		$('#DialogRfidHint').text(data);
		});
}


function setStyle(x)
{
	document.getElementById(x).style.background="yellow";
}


//托盘点击
function addListenerToTrayLink(){
	//添加click事件
	console.log("加click事件");
	$(".trayLink").click(function(){
			_app.editTrayRfid = $(this).attr("data");
			_app.currentAct = "EditTrayInApp";
			$.post("phpSearch.php?table=wTrays&&attr=rfid&&val="+_app.editTrayRfid,function(data){
				var obj = jQuery.parseJSON(data);
				_app.currentTray = obj[0];
				console.log("点击的托盘在于哪个AppID下");
				console.log(_app);
			});
			
	});	
	
}

/*  ****fillTrayDialog页内用的函数
*/
function updateTrayForftdPage(){
	//update by _app
	$('#TheTrayInfo').empty();
	$('#TheTrayInfo').append("<li>更新时间:\t"+_app.currentTray.UpdateTime+"</li>");
	$('#TheTrayInfo').append("<li>RFID:\t"+_app.currentTray.rfid+"</li>");
	$('#TheTrayInfo').append("<li>出入库单:\t"+_app.currentTray.wtAppID+"</li>");
	$('#TheTrayInfo').append("<li>托盘编码:\t"+_app.currentTray.wtID+"</li>");
	$("#TrayDialogFormExtra").append("<input  name=\"DialogRfid\" value=\""+_app.currentTray.rfid+"\" />");
}

function updateAppForftdPage(){
	$('#TheAppInfo').empty();
	$('#TheAppInfo').append("<li><a class=\"StockClick\">进仓编号:\t"+_app.currentApp.InStockID+"</a></li>");
	$('#TheAppInfo').append("<li>货品名称:\t"+_app.currentApp.appName+"</li>");
	$('#TheAppInfo').append("<li>货品麦头:\t"+_app.currentApp.appMaitou+"</li>");
	var typeString = "<li>类型:\t";
	if(_app.currentApp.appType === "in")
		typeString += "入库</li>";
	if(_app.currentApp.appType === "out")
		typeString += "出库</li>";
		
	var compString = "<li>完成情况:\t";
	if(_app.currentApp.appComplete == 1)
		compString += "完成</li>";
	else
		compString += "未完成</li>";
	$('#TheAppInfo').append(typeString+compString);
	
	$("#TrayDialogFormExtra").append("<input  name=\"InStockID\" value=\""+_app.currentApp.InStockID+"\" />");
	$("#TrayDialogFormExtra").append("<input  name=\"appID\" value=\""+_app.currentApp.appID+"\" />");
	$("#TrayDialogFormExtra").append("<input  name=\"wpID\" value=\""+_app.currentApp.wpID+"\" />");
}
function phpLoadSlotSelect(){
	console.log("phpLoadSlotSelect");
	$.post("phpSearch.php?table=wSlots",function(data){
		//console.log(data);
		var obj = jQuery.parseJSON(data);
		var select_str = "";

		select_str = "<label for=\"select-row\" class=\"select\">选择仓位行</label>";
		select_str += "<select name=\"select-row\" id=\"select-row\">";
		
		var row = new Array();
		for(var i =0; i<obj.length; i++){
			if(row[obj[i].tsPosRow] != null){
				if(obj[i].wtID == null)
				row[obj[i].tsPosRow]++;
			}
			else{
				row[obj[i].tsPosRow] = 1;	
			}
		}
		
		for(var i in row){
			select_str += "<option value=\""+i+"\">"+i+"排"+"(仓位剩余:"+row[i]+")</option>";
		}
		select_str += "</select >";
		
		select_str += "<label for=\"select-col\" class=\"select\">选择仓位列</label>";
		select_str += "<select name=\"select-col\" id=\"select-col\">";
		select_str += "<option >等待选择行</option>";
		select_str += "</select>";
		select_str += "<label for=\"select-floor\" class=\"select\">选择仓位层</label>";
		select_str += "<select name=\"select-floor\" id=\"select-floor\">";
		select_str += "<option >等待选择列</option>";
		select_str += "</select>";
		$("#slotOptionDiv").prepend(select_str);
		
		//由于手持及机器对on('focus',function)事件的不支持，只能改成change.
		//initial配置
		var trayID = 0;
		for(var i =0; i<obj.length; i++){
			if(obj[i].wtID == null){
				pos = obj[i].wSlotID;
				trayID = i;
				break;
			}
		}
		$('#select-row').val(obj[trayID].tsPosRow);
		loadCol($('#select-row').val(),obj);
		loadFloor($('#select-row').val(),$('#select-col').val(),obj);
		checkTrayID();
		$("#slotHint").html("系统推荐:"+$('#select-row').val()+"行"+$('#select-col').val()+"列"+$('#select-floor').val()+"层"+"的位置");
		//所以变更为初始推荐一个号码最小的或者最合适的位置
		
		//加载变更位置
		$('#select-row').on("change",function(){
			loadCol(this.value,obj);
			$("#slotHint").html("当前选择"+$('#select-row').val()+"行"+$('#select-col').val()+"列"+$('#select-floor').val()+"层"+"的位置");
			//$('#slotHint').html("选中的行"+this.value);	
			checkTrayID();
		});
		$('#select-col').on("change",function(){
			loadFloor($('#select-row').val(),this.value,obj);
			$("#slotHint").html("当前选择"+$('#select-row').val()+"行"+$('#select-col').val()+"列"+$('#select-floor').val()+"层"+"的位置");
			checkTrayID();
		});
		$('#select-floor').on("change",function(){
			//var slotHintStr = "当前选择:"
			$("#slotHint").html("当前选择"+$('#select-row').val()+"行"+$('#select-col').val()+"列"+$('#select-floor').val()+"层"+"的位置");
			checkTrayID();
		});
		
		function checkTrayID(){
			var pos="未选择";
			for(var i =0; i<obj.length; i++){
				if(	obj[i].tsPosRow == $('#select-row').val() && obj[i].tsPosCol == $('#select-col').val() && obj[i].tsPosFloor == $('#select-floor').val() ){
					pos = obj[i].wSlotID;
				}
			}
			$("#DialogSlotPosition").val(pos);	
		}
		
	});
	function loadCol(index,obj){
		
		colOpStr = "";
		col  = new Array();
		for(var i =0; i<obj.length; i++){
			if(obj[i].tsPosRow == index && obj[i].wtID == null){
				if( col[obj[i].tsPosCol] != null)
					col[obj[i].tsPosCol]++;
				else
					col[obj[i].tsPosCol] = 1;
			}
		}
		
		for(var i in col){
			colOpStr += "<option value=\""+i+"\">"+i+"排"+"(仓位剩余:"+col[i]+")</option>";
		}
		$("#select-col").html(colOpStr);
		
	}
	function loadFloor(indexRow,indexCol,obj){
		FloorOpStr = "";
		floorAr  = new Array();
		for(var i =0; i<obj.length; i++){
			if(obj[i].tsPosRow == indexRow　&& obj[i].tsPosCol == indexCol && obj[i].wtID == null){
				if( floorAr[obj[i].tsPosFloor] != null)
					floorAr[obj[i].tsPosFloor]++;
				else
					floorAr[obj[i].tsPosFloor] = 1;
			}
		}
		
		for(var i in floorAr){
			FloorOpStr += "<option value=\""+i+"\">"+i+"层"+"(仓位剩余:"+floorAr[i]+")</option>";
		}
		$("#select-floor").html(FloorOpStr);
	}	
	
}
	
function filterBtn(btn){
	$('#fillTrayDialogActionFooter a').addClass("ui-state-disabled");
	btn.removeClass("ui-state-disabled");	
}


//graph-page 的图像输入
function loadGraph(){
		console.log("graph-page图像载入");
		//仓位占用比图像
		$.post("getTrays.php",function(data){
			 var obj = jQuery.parseJSON(data);
			 var traysUse = function(){
				var list = [[['空闲',0],['仓库外',0],['仓库内',0],['货架',0]]];
				//console.log(list);
				
				$.each(obj,function(i,item){
					if(item.twStatus == "空闲"){
						list[0][0][1] = parseInt(list[0][0][1])+1;	
					}
					else if(item.twStatus == "仓库外")
					{
						list[0][1][1] = parseInt(list[0][1][1])+1;
					}else if(item.twStatus == "仓库内")
					{
						list[0][2][1] = parseInt(list[0][2][1])+1;
					}
					else if(item.twStatus == "货架")
					{
						list[0][3][1] = parseInt(list[0][3][1])+1;
					}
					
				});
				//console.log(list);
					return list;
			 };
			 
			 var plot1 = $.jqplot('traysUse', [], {
			
				title: "仓库托盘使用比率",
				gridPadding: {top:0, bottom:38, left:0, right:0},
				seriesDefaults:{
					renderer:$.jqplot.PieRenderer, 
					trendline:{ show:false }, 
					rendererOptions: { padding: 8, showDataLabels: true }
				},
				legend:{
					show:true, 
					placement: 'outside', 
					rendererOptions: {
						numberRows: 1
					}, 
					location:'s',
					marginTop: '15px'
				},
				dataRenderer: traysUse 
			});
			$('.ui-page-active').trigger('pagecreate');
		  });
		  
		  //吞吐量
		  var inPlot = [];
		  var outPlot = [];
		  $.post("getAppList.php?all=1",function(data){
			 var obj = jQuery.parseJSON(data);
			 //console.log(obj); 
			 
			 $.each(obj,function(i,item){
				 console.log(item.appType);
				if(item.appType == "in"){
					inPlot.push([item.appBookingDate,Number(item.appCount)]);
				}
				if(item.appType == "out"){
					outPlot.push([item.appBookingDate,Number(item.appCount)]);
				}
			 });
			var plot2 = $.jqplot('inOutStatics', [inPlot,outPlot], {
			  title: '所有计划中的出入库', 
			  axes:{
		        xaxis:{
		          renderer:$.jqplot.DateAxisRenderer, 
		          tickOptions:{formatString:'%b %#d'},
		          min:'June 16, 2013', 
		          tickInterval:'2 months'
		        }
		      },
				series:[{lineWidth:4, markerOptions:{style:'square'}}]
		  	});	
		  });
		 
	}

/*  ****结束fillTrayDialog页内用的函数
*/

function goback() {
	window.history.back()
	console.log("gobaock失败");
	return false;
}


function getSizedText(txt,size,width){
	var output = "<span style=\"width:"+width+"px; display:inline-block;\" >"
	if(isInt(size)){
		if(size>=txt.length){
			output += txt;
		}
		else{
			output += txt.slice(0,size-1)+".";
		}
	}
	else{
		output += "函数尺寸有误"+txt;
	}
	output += "</span>";
	return output;

}

function isInt(n) {
   return typeof n === 'number' && parseFloat(n) == parseInt(n, 10) && !isNaN(n);
} // 6 characters

function showUnitList(){
	console.log('function showUnitList');
	var out = "";
	if(_app.currentTray){
		$.ajax({ 
	    type : "post", 
	    url : "phpSearch.php?table=wareUnit&&attr=trayID&&val="+_app.currentTray.wtID,
	    async : false, 
	    success : function(data){
	    	out += getSizedText("[商品:数量]:",10,90);
				out += getSizedText("尺寸:(cm)",20,120);
				out += getSizedText("重量:(kg)",10,90);
				out += "<hr>";
	    	var units = jQuery.parseJSON(data);
	    	units.count = 0;
	    	units.weight = 0; 
	      for(var j=0; j<units.length; j++){
	      	out += getSizedText(units[j].wiName+":"+units[j].count+"箱",10,90);
	      	units.count += parseInt(units[j].count);
					out += getSizedText(+units[j].width+"x"+units[j].length+"x"+units[j].height,20,120);
					var weight = parseFloat(units[j].weight)*parseInt(units[j].count);
					out += getSizedText(weight.toString(),10,50);
					units.weight += weight;
					out += "<hr>";
	      }
	      out += getSizedText("合计:"+units.count.toString()+"箱",10,70);
				out += getSizedText("",20,120);
				out += getSizedText(units.weight.toString(),10,90);
				out += "<hr>";
	    	if(units.length == 0){
	    		$("#releaseTray").attr("trayEmpty",Boolean(true));
	    	}else{
	    		$("#releaseTray").attr('trayEmpty',false);
	    	}

	    	//更新托盘上货物数量
	    	$.get("phpUpdate.php?table=wTrays&&idAttr=wtID&&idValue="+_app.currentTray.wtID+"&&tAttr=twWareCount&&tValue="+units.count,
	    		function(data){
	    			_app.currentTray.twWareCount = units.count;
	    			console.log('货物xx'+_app.currentTray.twWareCount);
	    			console.log(data);
	    		}
	    	);

	    	console.log('货物'+_app.currentTray.twWareCount);
	    	
	    } 
	  });
  }
  $('#fillTrayPage_unitList').html(out);
  //$("#fillTrayPage_lv").listview("refresh");
}

function updateTrayPageFunction(){
	console.log("托盘页正在刷新");
	//选项先全部禁用并收起
		$('#trayBindNav').hide();
		$('#trayAddNav').hide();
		$('#trayRemoveNav').hide();
		$('#trayShelfNav').hide();
	/*
	$.post("phpSearch.php?table=wTrays&&attr=rfid&&val="+_app.currentTray.rfid,function(data){
		var obj = jQuery.parseJSON(data);
		_app.currentTray = obj[0];
		console.log(_app.currentTray);
	});
*/
  

	$.ajax({ 
    type : "post", 
    url : "phpSearch.php?table=wTrays&&attr=rfid&&val="+_app.currentTray.rfid,
    async : false, 
    success : function(data){
    	var obj = jQuery.parseJSON(data);
			_app.currentTray = obj[0];
			console.log(_app.currentTray.twStatus);

    }
  });

	//console.log(_app.currentTray.twStatus);
	//0. 托盘是否属于本货单 即托盘无绑定
	if(!_app.currentTray){
		//打印显示
		var str= getSizedText(_app.currentApp.InStockID+"  "+_app.currentApp.appName,35,200);
		$('#fillTrayPage_app').html(str);
		//使能绑定选项
		$('#trayBindNav').show();
		$('#bindTray2AppBtn').show();
		$('#releaseTray').hide();
	}
	else{
		//已有正在使用的托盘
		var str= getSizedText(_app.currentApp.InStockID+"  "+_app.currentApp.appName+" "+_app.currentTray.twWareCount+"箱",20,160);
		$('#fillTrayPage_app').html(str);
		$('.currentTime').val(getFormatTime());
		//1.装盘阶段则可以添加；一旦添加需要更改托盘归属；非空后可以完成装盘，转入上货架阶段
		//2.上货架阶段，完成转入货架阶段
		//3.下货架阶段，完成转入装车或分流阶段；
		//3.1 装车阶段需要将货物从托盘依次取出
		//3.2 分流阶段需要将部分货物取出后，上货架
		console.log(_app.currentTray+"是正在访问的托盘");
		console.log("状态是："+_app.currentTray.twStatus);
		if(_app.currentTray.twStatus == "空闲"){
			//使能绑定项
			$('#trayBindNav').show();
			$('#bindTray2AppBtn').show();
			$('#releaseTray').hide();
			
		}
		else if(_app.currentTray.twStatus == "仓库外" || _app.currentTray.twStatus == "仓库内"){
			//使能进箱
			$('#trayAddNav').show();
			//条件使能解除绑定
			$('#trayBindNav').show();
			$('#bindTray2AppBtn').hide();
			$('#releaseTray').show();
			//条件使能出箱 ...处理其中选项
			$('#trayRemoveNav').show();
			//获取选项				
			$.ajax({ 
		    type : "post", 
		    url : "phpSearch.php?table=wareUnit&&attr=trayID&&val="+_app.currentTray.wtID,
		    async : false, 
		    success : function(data){
		    	var units = jQuery.parseJSON(data);
		    	var opt="";
		      for(var j=0; j<units.length; j++){
		      	opt += "<option value=\""+units[j].wiID+"\" maxCount=\""+units[j].count+"\">"+units[j].wiName+":"+units[j].count+"箱|"+units[j].length+"x"+units[j].width+"x"+units[j].height+"</option>";
		      }
		      $('#unitUnloadSelect').html(opt);
					$('#unitUnloadSelect').selectmenu("refresh");
				}
			});
			//使能上架
			$('#trayShelfNav').show();
			$('#tray2ShelfHelperNav').show();
			$('#unload4Shelf').hide();
			$('#load2Shelf').show();
			var slots;
			//上架时上架位置建议
			$.ajax({ 
			    type : "post", 
			    url : "phpSearch.php?table=wSlots&&attr=wtID&&val=0",
			    async : false, 
			    success : function(data){
			    	slots = jQuery.parseJSON(data);
			    	$('#tray2ShelfHelperNavHint').html("推荐货架为"+slots[0].tsWareHouse+"仓"+slots[0].tsPosRow+"行"+slots[0].tsPosCol+"列"+slots[0].tsPosFloor+"层"+":"+slots[0].tsWareHouse+"-"+slots[0].tsPosRow+"-"+slots[0].tsPosCol+"-"+slots[0].tsPosFloor);
			    	
					}
			});
			$.ajax({ 
			    type : "post", 
			    url : "phpGetSlot.php",
			    async : false, 
			    success : function(data){
			    	slots = jQuery.parseJSON(data);
			    	var opt = "<option>default</option>";
			    	for (var i = slots.length - 1; i >= 0; i--) {
			    		opt += "<option value=\""+slots[i].tsWareHouse+"\" >货仓"+slots[i].tsWareHouse+"号</option>";
			    	}
			    	$('#slotHouse').html(opt);
						$('#slotHouse').selectmenu("refresh");
			    }
		  });
		}
		else if(_app.currentTray.twStatus == "货架"){
			//使能下架
			$('#trayShelfNav').show();
			$('#load2Shelf').hide();
			$('#unload4Shelf').show();
			$('#tray2ShelfHelperNav').hide();
			
		}
	}
}

function addMemo(memo){
	console.log("添加记录");
	console.log(memo);
	var text = "";
	for(var x in memo){
		text += x+"-"+memo[x]+";";
	}
	console.log(text);

	var query = "insertAct.php?"
	for(var x in memo){
		query+= x+"="+memo[x]+"&&";
	}
	query = query.substring(0,query.length-2);
	$.post( query,function(data){
		console.log(data);
		//outputHint("修改货代结果",data,3000);
	});
	console.log("添加记录结束");
}

console.log("到这里了");
$('#logout').on("click",function(data){
	console.log("logout");
	window.location.href = "login.php?logout=true";
	//$('#logout').click(function () {
	//	window.location.href = "login.php?logout=true";
	//})
})