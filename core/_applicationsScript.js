//定义处理applications页面里的脚本
$("#AppInFilterSwitch").on("click",function(){
	console.log("点击\"#AppInFilterSwitch\"");

	if( $(this).hasClass("glyphicon-chevron-down")){
		$(this).removeClass("glyphicon-chevron-down");
		$(this).addClass("glyphicon-chevron-up");
		$("#AppFilter #appsOption").slideDown(300);
	}
	else{
		$(this).addClass("glyphicon-chevron-down");
		$(this).removeClass("glyphicon-chevron-up");
		$("#AppFilter #appsOption").slideUp(300);
	}
});

//新建货单 弹出modal
$("#createAppInNav").on("click",function(){
	var options = {
		"backdrop":true,
		"keyboard":true,
		"show":true
	}
	$("#newAppInBlock").modal(options);
	$('#newAppInBlock').unbind("hidden.bs.modal").on('hidden.bs.modal', function (e) {
	  // do something...
	  console.log("modal 重新增加关闭事件");
	});
	//$("#submitNewAgent")
});
