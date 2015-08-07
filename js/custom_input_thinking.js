/* HTML 代码
	*确定表单名这里是agentID
	*搜索对应是的CODE
	<div class="col-sm-6" id="agentID_input">
		<input type="text" id="agentID_input_value"/>
	</div>

	*js
	inputThinking("agentID",1,"wAgents");

	//异化显示部分
	str += "<li class=\"ThinkIt\" data=\""+obj[i].waID+"\">"+obj[i].CODE+"-"+obj[i].waName+"</li>";

	如何解决
*/

/** 
* 联想输入框的实现 
* 
* @param objid 页面输入框对应的obj标签的Id 
* @param flag 联想输入对应的操作,数字,与action中实现对应 
* @param table 联想对应的表名
*/ 
function inputThinking(objid, flag, table) { 
	// 输入框父节点Id 
	var pid = objid + "_input"; //例如agentID_input;
	//输入框的Id 
	var inputId = objid + "_input_value"; //例如agentID_input_value;
	//输入框的联想框
	var thinkingID = objid+"_thinking"; //例如agentID_thinking

	//console.log("显示"+pid);
	// 获取输入框宽度，根据宽度自适应 
	var width = $("#" + pid).css("width");
	//console.log($("#" + pid));
	if(width && width.indexOf('px') > 0) { 
		width = width.substring(0, width.indexOf('px')) - 2; 
	} 
	else { 
		width = 158; 
	} 

	// 定义联想输入页面HTML, 定义输入框及联想输入页面的状态,状态1表示焦点状态，状态0表示非焦点状态 #428bca
	var tipHTML = "<div id=\""+thinkingID+"\" style='background-color:#BBEEFF;padding-left:5px; color:#555;position:fixed;z-index:999999;border:1px solid #CDE4EF; width:" + width + "px;overflow:hidden;display:none;'><li>&nbsp;</li></div>";
	//tipHTML += "<input type='hidden' id='inputId' value='" + inputId + "'>";
	//tipHTML += "<input type='hidden' id='status' value='1'>";
	//tipHTML += "<input type='hidden' id='thinking_status' value='0'>";
	tipHTML += "<input type='hidden' id=\""+objid+"\" name=\""+objid+"\" value='null' >";
	tipHTML += "<style>#"+thinkingID+" li.sel{background:#4d77bb;color:white;} #"+thinkingID+" li{font-size:70%;height:18px;overflow:hidden;}</style>" 
	
	/********** 将联想输入页面嵌入原始页面 **********/ 
	//$("#" + inputId).parent().append(tipHTML);
	$(tipHTML).insertAfter($("#" + inputId));

	//console.log( "thinkingID");
	//console.log( $("#"+thinkingID) );

	/********** 绑定输入框事件 **********/ 

	// 输入框内容修改事件 
	$("#" + inputId).bind("keyup", function(e) {
		//console.log("keyup");
		if (e.keyCode == 38) { 
			// 向上 
			next(false,thinkingID); 
		} else if(e.keyCode == 40) { 
			// 向下 
			next(true,thinkingID); 
		} else if(e.keyCode == 13){
			var obj = get($("#"+thinkingID+" li.sel"), 0); 
			selectThisEnt($(obj).html(),thinkingID,inputId);
			$("#"+objid).val($(obj).attr("data"));
		} 
		else { 
			//先把小写改大写
			$("#" + inputId).val($("#" + inputId).val().toUpperCase());
			showThinking(inputId, flag, table,thinkingID,objid);
			
		} 
	});

	//去除Enter的提交
	$("#"+inputId).bind("keydown",function(e){
		if(e.keyCode == 13){
			//console.log("enter");
			var obj = get($("#"+thinkingID+" li.sel"), 0); 
			selectThisEnt($(obj).html(),thinkingID,inputId);
			$("#"+objid).val($(obj).attr("data"));
			return false;
		}

	});

} 

/** 
* 根据状态刷新数据，并显示联想输入框 
* 
* @param inputId 页面input输入框对应的Id 
* @param flag 联想输入对应的操作,数字,与action中实现对应 
*/ 
function showThinking(inputId, flag, table,thinkingID,objid) { 
	var value = $.trim($("#" + inputId).val());

	// 只有输入框非空的时候才调用输入联想 
	if(value && value != null && value != '') { 
	// 发送ajax初始化输入联想页面 
		$.ajax({ 
			type: "POST", 
			async: false, 
			// 调用的action定义 
			url: "../core/_search.php?table="+table, 
			data: "", 
			// 回调函数 
			success: function(data) { 
				// 返回的如果是异常则跳过 
				//没有抛出异常或者成功返回结果判断
				var obj;
				
				if(obj= jQuery.parseJSON(data)){
					var str = "<ul>";
					var code ="";
					var find = false;
					var none = true;
					for(var i in obj){
						//console.log(obj[i].CODE);
						code = obj[i].CODE;
						find = false;
						find = code.indexOf($("#"+inputId).val());
						//console.log("="+$("#"+inputId).val());
						//console.log(find);
						//if(obj[i].indexOf())
						if( find != -1){
							str += "<li class=\"ThinkIt\" data=\""+obj[i].waID+"\">"+obj[i].CODE+"-"+obj[i].waName+"</li>";
							if(none)
								none = false;
						}
						str+="</li>";
					}

					if(none){
						str = "未搜寻到符合项";
					}
					


					//<li class=\"it\">adf</li><li class=\"it\">fdsf</li></ul>";

					$("#"+thinkingID).html(str);
					// 显示联想输入页面 
					$("#"+thinkingID).css("display", "block");

					$(".ThinkIt").on("click",function(){
						selectThisEnt($(this).html(),thinkingID,inputId);
						$("#"+objid).val($(this).attr("data"));
					});
				}
				else{
					$("#"+thinkingID).html("查询有误");
				}
			}, 
			error: function(msg) { 
				alert(msg); 
				console.log("error");
			} 
			}); 
	} else { 
		$("#"+thinkingID).html("请输入所要查询的代码"); 
		console.log("else");
	} 
} 

function next(flag,thinkingID) { 
	var obj = get($("#"+thinkingID+" li.sel"), 0); 
	var next = null; 
	if(obj) { 
		next = get(flag? $(obj).next("li"): $(obj).prev("li"), 0); 
	} 
	else { 
		next = flag? $("#"+thinkingID + " li").first(): $("#"+thinkingID+" li").last(); 
	} 
	if(next) { 
		if(obj) { 
			$(obj).removeClass("sel"); 
		}

		$(next).addClass("sel"); 
	} 
} 

function get(array, index) { 
	if(array.length && array.length > index) { 
		return array[index]; 
	} 
	else { 
		return null; 
	} 
} 

/** 
* 将选择的数据填充到输入框 
* 
* @param name 显示的数据内容 
*/ 
function selectThisEnt(name,thinkingID,inputId) { 
	$("#" + inputId).val(name); 
	$("#" + inputId).change(); // 触发change事件，更新输入联想提示内容 
	$("#"+thinkingID).css('display', 'none'); 
} 

/** 
* 将选择的节点数据填充到输入框 
* 
* @param obj 选择的节点 
*/ 
function selectThinking(obj) { 
	$("#" + $("#inputId").val()).val($(obj).text()); 
	$("#" + $("#inputId").val()).change(); // 触发change事件，更新输入联想提示内容 
	$("#"+thinkingID).css('display', 'none'); 
}