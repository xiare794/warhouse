// JavaScript Document

//按压终端的scan返回一个数据
function getScanValue()
{
	if(stringGetter != null){
		var theString = stringGetter.getString();
		//$('#DialogRfid').val(theString);
		//getTrayByRfid(theString);
		
		//$('#afterScan').css("display","block");
		//$('#DialogRfidHint').css("display","block");
		//$('.pleaseScan').css("display","none");
		return theString;
		
		//return theString;
	}
}

function setValue(){
	console.log("rfid set scan");
	$(this).val(val);	
}