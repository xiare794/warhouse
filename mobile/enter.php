<?php 
    session_start();
 ?>
<!DOCTYPE html> 
<html>
<head>
    <?php
    	//2014.09版本 ref
        //include("_db.php"); 
        //include("functions_manage.php");
        //如果没有用户信息，就跳转回登陆页面
        if(!isset ($_SESSION['user']) ){
            //$_SESSION['previewPage'] = curPageURL();
            //echo curPageURL();
            echo "<meta HTTP-EQUIV=\"REFRESH\" content=\"0; url=login.php\">";
        }
        else {
            ;
            //nothing
        }
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">

    <title>Ware House Mobile Cient</title>
    
    <!-- 第二套 -->
    <link rel="stylesheet" href="jquery-mobile/jquery.mobile-1.4.0.min.css" />
    <link rel="stylesheet" href="jquery-mobile/jqueryMobileRoller1.0.css" />
    <link rel="stylesheet" href="jqplot/jquery.jqplot.css"  >
    <script class="include" type="text/javascript" src="jquery-mobile/jquery-1.9.1.js"></script>
    <script src="jquery-mobile/jquery.mobile-1.4.0.min.js"></script> 
    <script type="text/javascript" src="jqplot/jquery.jqplot.js"></script>
    
    <script type="text/javascript" src="jqplot/plugins/jqplot.canvasAxisLabelRenderer.min.js"></script>
	<script type="text/javascript" src="jqplot/plugins/jqplot.canvasTextRenderer.min.js"></script>
   	<script type="text/javascript" src="jqplot/plugins/jqplot.dateAxisRenderer.min.js"></script>
   	<script type="text/javascript" src="jqplot/plugins/jqplot.pieRenderer.min.js"></script>
    <script type="text/javascript" src="jqplot/plugins/jqplot.donutRenderer.min.js"></script>
	
  	<script type="text/javascript" src="syntaxhighlighter/scripts/shCore.min.js"></script>
    <script type="text/javascript" src="syntaxhighlighter/scripts/shBrushJScript.min.js"></script>
    <script type="text/javascript" src="syntaxhighlighter/scripts/shBrushXml.min.js"></script>
    
    <style>
		#curTime{ 
			font-size:36px;
			text-align:center;
		}
		.lightBlue{
			color:#38c;	
		}

        /* 带背景色的状态文字 */
        /* 托盘空闲中 */
        .status-idle{
            background-color: #e9e9e9;
            color:rgb(102, 102, 102);
        }
        .status-owt{
            background-color: #f18d05;
            color: white;
        }
        .status-shelf{
            background-color: #7db500;
            color: white;
        }
        .status-other{
            background-color: #87318c;
            color: white;
        }



	</style>
</head>
<body> 

    <!-- 首页　　　-->
    <div data-role="page" id="page" data-theme="a">
        <div data-role="header" data-theme="a">
            <!--<a href="#page"  class="ui-btn ui-corner-all ui-shadow ui-icon-back ui-btn-icon-left ui-btn-icon-notext">HOME</a>-->
            <h5 >威尔豪斯仓储终端</h5>
            <a class="ui-btn-right" id="logout" href="#"><?php echo $_SESSION['user']."|登出"; ?></a>
        </div>
        <div data-role="content" data-position="fixed" >
            <h1 id="curTime">下午 14:35</h1>
            <h2 id="taskWaited"></h2>
            <a onclick="location.reload(true);" data-role="button">重新连接服务器</a>
            <!--<p>作为一个终端用户，你应该可以在这里检查每单的货物，可以查对应代理商的订单，查目前所有单，并筛选。终端屏幕大小有限，无法很强大，功能应该尽量集中。应该有几类方向性指引，1.使用说明，2.实时查看，3.执行管理员动作，4.对动作进行管理</p>
            <ul data-role="listview" class="ui-icon-cloud">
                <li data-theme="c"><a href="#appListPage" class="ui-icon-cloud">任务</a></li>
                <li><a href="#scanTraysPage" class="ui-icon-cloud">查找</a></li>
                <li><a href="#trayListPage" id="getTrayListBtn" class="ui-icon-minus">绑定托盘</a></li>       
                <li><a href="view.html" rel="external">状况</a></li>
            </ul>
            <p id="debug" data-theme="a" ></p>	
            -->
        </div>
        <div data-role="footer" data-position="fixed" >
            <div data-role="navbar" >
                <ul>
                    <li data-theme="c"><a href="#appListPage" class="ui-icon-cloud"><h2>任务</h2></a></li>
                    <li><a href="#scanTraysPage" class="ui-icon-cloud"><h2>查找</h2></a></li>
                    <li><a href="#trayListPage" id="getTrayListBtn" class="ui-icon-minus"><h2>绑定</h2></a></li>       
                    <li><a href="#InfoPage"><h2>库状态</h2></a></li>       
                    <li><a href="#shortCut"><h2>快捷</h2></a></li>

                    
                </ul>
            </div>
        </div>
    </div>
    
    <!-- 任务页　-->
    <div data-role="page" id="appListPage" data-theme="b">
        <!-- panel 是选项页 -->
        <div data-role="panel" id="mypanel" data-theme="b">
            <!-- panel content goes here -->
            <a href="#" data-rel="close" class="ui-btn ui-shadow ui-corner-all ui-icon-delete ui-btn-icon-notext">Delete</a>
            <fieldset data-role="controlgroup" data-theme="b" data-type="horizontal">
                <legend class="ui-mini">出入库筛选:</legend>
                <input type="radio" name="appType" id="appType-in" value="on" checked="checked" data-mini="true">
                <label for="appType-in" data-mini="true">入库</label>
                <input type="radio" name="appType" id="appType-out" value="off" data-mini="true">
                <label for="appType-out" data-mini="true">出库</label>
            </fieldset>
            
            <fieldset data-role="controlgroup" data-theme="b" data-type="horizontal">
                <legend class="ui-mini">时间顺序:</legend>
                <input type="radio" name="timeDescend" id="timeDescend-On" value="on" checked="checked" data-mini="true">
                <label for="timeDescend-On" data-mini="true">近期优先</label>
                <input type="radio" name="timeDescend" id="timeDescend-Off" value="off" data-mini="true">
                <label for="timeDescend-Off" data-mini="true">按登入日期</label>
            </fieldset>
            
            <fieldset data-role="controlgroup" data-theme="b" data-type="horizontal">
                <legend class="ui-mini" data-mini="true">正在进行筛选:</legend>
                <input type="radio" name="filter-assigned" id="filter-assigned" value="on" checked="checked" class="ui-mini" data-mini="true">
                <label for="filter-assigned" data-mini="true">正在进行</label>
                <input type="radio" name="filter-assigned" id="filter-assigned-no" value="off" data-mini="true">
                <label for="filter-assigned-no" class="ui-mini">显示全部</label>
            </fieldset>
            
            <fieldset data-role="controlgroup" data-theme="b" data-type="horizontal">
                <legend class="ui-mini" data-mini="true">显示详情:</legend>
                <input type="radio" name="filter-detail" id="filter-detail" value="on" class="ui-mini" data-mini="true">
                <label for="filter-detail" data-mini="true">是</label>
                <input type="radio" name="filter-detail" id="filter-detail-no" value="off" checked="checked" data-mini="true">
                <label for="filter-detail-no" class="ui-mini">否</label>
            </fieldset>
            <fieldset data-role="controlgroup" data-theme="b" data-type="horizontal">
                <legend class="ui-mini" data-mini="true">仅显示未完成:</legend>
                <input type="radio" name="filter-unComplete" id="filter-unComplete-yes" value="on" class="ui-mini" checked="checked" data-mini="true">
                <label for="filter-unComplete-yes" data-mini="true">是</label>
                <input type="radio" name="filter-unComplete" id="filter-unComplete" value="off"  data-mini="true">
                <label for="filter-unComplete" class="ui-mini">否</label>
            </fieldset>
            	<label for="slider-fill-mini">显示单据数量</label>
				<input type="range" name="slider-fill-mini" id="slider-fill-mini" value="5" min="3" max="20" data-mini="true" data-highlight="true" data-theme="b" data-track-theme="b">
            <p id="filter_result"></p>
        </div><!-- /panel -->
        <div data-role="header">
            <a href="#page" class="ui-btn ui-corner-all ui-shadow ui-icon-back ui-btn-icon-left ui-btn-icon-notext">HOME</a>
            <h1>任务列表</h1>
            <a href="#mypanel" id="dynamicPanel" data-icon="gear" class="ui-btn-right">筛选</a>
        </div>
        <div data-role="content" id="appContainer">	
            <ul data-role="listview" id="appContent" >
            </ul>
            <ul data-role="listview" id="appInContent" style="padding-bottom:15px">
            </ul>
            <ul data-role="listview" id="appOutContent" style="padding-bottom:15px">
            </ul>
        </div>
        <div data-role="footer" data-position="fixed">
            <h4><a class="ui-btn ui-shadow ui-corner-all ui-icon-carat-d" onClick="loadMoreItem()">加载更多</a></h4>
        </div>
    </div>
    
    <!-- 任务页内页 -->
    <div data-role="page" id="theapp" data-theme="c">
        <div data-role="header">
            <h1>具体某个物单</h1>
            
        </div>
        <div id="theapphint">
            货物提示
        </div>
        <div data-role="content" id="theappContent">	
            <table data-role="table" class="ui-responsive ui-body-d ui-shadow table-stripe" data-mode="columntoggle" id="my-table">
                <thead>
                    <tr>
                        <th data-priority="1" >data-priority="1"</th>
                        <th data-priority="2" >data-priority="2"</th>
                        <th data-priority="3" >data-priority="3"</th>
                        <th data-priority="4" >data-priority="4"</th>
                        <th data-priority="5" >data-priority="5"</th>
                    </tr>
                <thead>
                <tbody>
                    <tr> <td>Say Somthing</td><td>Okay, bit Boring</td><td>save that </td><td>figure it please</td></tr>
                </tbody>

            </table>
                
        </div>
        
        <div data-role="footer" data-position="fixed">
            <div data-role="navbar" >
				<ul><!--href="#fillTrayDialog" -->
					<li><a id="addNewTrayForApp"><h2>增加托盘</h2></a></li>
					<li id="appCompleteBtn"><a ><h2>完成</h2></a></li>
				</ul>
			</div><!-- /navbar -->
        </div>
        
        <!-- popup 是选项页 
        <div data-role="popup" id="appCompletePopup" data-theme="a" class="ui-corner-all">
            <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
             <div style="padding:10px 20px;">
                
                <h3>确认入库单完成</h3>
                <p id="appCompleteHint"></p>
                <a class="ui-btn ui-btn-icon-left ui-icon-check" id="appCompleteBtn">确认</a>
             </div>
        </div>旧的弹出按钮 /panel -->
    </div>
    
    <!-- 托盘绑定页 -->
    <div data-role="page" id="trayListPage">
        <div data-role="header">
            <h1>选取托盘并绑定</h1>
        </div>
        <div data-role="content" id="trayList">	
            <p>暂无数据，请退出刷新</p>	
        </div>
        <div data-role="footer" data-position="fixed">
            <h4>Footer</h4>
        </div>
    </div>

    <!-- 托盘绑定页 -->
    <div data-role="page" id="shortCut">
        <div data-role="header">
            <h1>快捷动作(临时)</h1>
        </div>
        <div data-role="content" id="shortCutContent" > 
            <a data-role="button" data-theme="d" id="addNewTrays" data-inline="true" >增加临时托盘</a>   
            <a data-role="button" data-theme="d" id="randonRFID" data-inline="true" >使得TESTRFIDCODE托盘随机rfid</a>   
        
        </div>
        <div data-role="footer" data-position="fixed">
            <h4>下导航</h4>
        </div>
    </div>
    
    <!-- 托盘绑定页　-->
    <div data-role="page" id="bindTrayPage">
        <div data-role="header">
            <h1>扫描并点击绑定</h1>
        </div>
        <div data-role="content" id="trayList">
            <form method="POST" action="addTrayRecord.php" id="BindTrayDialogForm">
            <li class="ui-field-contain">
              <label for="DialogTrayID">托盘编号</label>
              <input type="text" name="DialogTrayID" id="DialogTrayID">
            </li>
            <p class="pleaseScan"> 请扫描RFID </p>
            <li class="ui-field-contain">
              <label for="slotPos">RFID编码</label>
              <input type="text" name="DialogRfid" id="DialogRfid" >
            </li>
            </form>
            <p id="DialogRfidHint" ></p>
            
        </div>
        <div data-role="footer" data-position="fixed">
        	<div data-role="navbar" data-theme="c" >
            	<ul>
                	<li><a data-role="button" data-theme="d" id="applyBindBtn" data-inline="true" >绑定托盘</a></li>
                    <li><a data-role="button" data-theme="d" id="applyUnbindBtn" data-inline="true" >解除绑定</a></li>
                </ul>
            </div>
        </div>
    </div>
    
     <!-- 扫描到rfid信息 页面　-->
    <div data-role="page" id="scanTraysPage">
        <div data-role="header">
            <h1>扫描面前托盘</h1>
        </div>
        <div data-role="content" id="scanTrayContent">
        	<ul data-role="listview" id="scanTrayContentUL">
            </ul>
            <!--<p>
            	<a data-role="button" data-theme="d" data-inline="true" onClick="onceScaned(654321)">假装扫描６５４３２１</a
        >
        		<a data-role="button" data-theme="d" data-inline="true" onClick="onceScaned(123)">假装扫描123</a
        >
            </p>-->
            </div>
        <div data-role="footer" data-position="fixed">
        	<div data-role="navbar" data-theme="c" >
	        	<ul>
	        		<li><a data-role="button" data-theme="d" id="clearScanResult" data-inline="true" ><h2>清除记录</h2></a></li>
	        	</ul>
	        	
	        </div>
        </div>
    </div>   
    
    
    <!-- 托盘信息页，内页 -->
    <div data-role="page" id="fillTrayDialog" data-theme="b">
        <div data-role="header" >
            托盘
        </div>
        <div style="font-size:0.8em; padding:9px; margin:0px" id="fillTrayPage_app" >
        </div>
        <div style="font-size:0.5em; padding:9px; margin:0px" id="fillTrayPage_unitList">

        </div>
        
        <div data-role="collapsible-set">
            
                <div data-role="collapsible" data-mini="true" id="trayBindNav">
                    <h4>绑定项</h4>
                    <div style="font-size:1em">
                        <div id="shelfHint"></div>
                        <a class='ui-btn fillTrayAct'id="bindTray2AppBtn">绑定托盘</a>
                        <a class='ui-btn fillTrayAct'id="releaseTray">解除绑定</a>
                        <span id="bindTray2AppBtnHint">123</span>
                    </div>
                </div>
            <!--增加Unit Nav结束-->

            <div data-role="navbar" >
                <div data-role="collapsible" data-mini="true" id="trayAddNav">
                    <h4>装上货物</h4>
                    <div style="font-size:1em">
                        <form method="POST" id="addUnitTrayDialogForm">
                            <fieldset class="ui-grid-a">
                                <div class="ui-block-a">
                                    <p style="text-align:center">现在时间</p>
                                </div>
                                <div class="ui-block-b">
                                    <input type="text" name="updateTime" class="currentTime" value="123" class="ui-btn-inline" >
                                </div>
                                <div class="ui-block-a">
                                    <p style="text-align:center">数量(单位：箱)</p>
                                </div>
                                <div class="ui-block-b">
                                    <input type="number" name="count" id="dialogtrayCaseCount" value="1">
                                </div>
                                <div class="ui-block-a">
                                    <p style="text-align:center">重量(单位：kg)</p>
                                </div>
                                <div class="ui-block-b">
                                    <input type="number" name="weight" id="wareUnit_Weight" value="100">
                                </div>
                                <div class="ui-block-a">
                                    <p style="text-align:center">长度(单位：cm)</p>
                                </div>
                                <div class="ui-block-b">
                                    <input type="number" name="length" id="wareUnit_Length" value="100">
                                </div>
                                <div class="ui-block-a">
                                    <p style="text-align:center">宽度(单位：cm)</p>
                                </div>
                                <div class="ui-block-b">
                                    <input type="number" name="width" id="wareUnit_Width" value="100">
                                </div>
                                <div class="ui-block-a">
                                    <p style="text-align:center">高度(单位：cm)</p>
                                </div>
                                <div class="ui-block-b">
                                    <input type="number" name="height" id="wareUnit_Height" value="100">
                                </div>
                                <div class="ui-block-a">
                                    <div id="TrayDialogFormExtra"></div>
                                </div>                               
                            </fieldset>
                            <input type="hidden" name="appID"  id="appID" >
                            <input type="hidden" name="trayID" id="trayID"  >
                            <input type="hidden" name="wiName" id="wiName" >
                        </form>
                        <a class='ui-btn'id="addUnit2Item">确认增加</a>
                        <div id="TrayDialogFormHint4add"></div>
                    </div>
                </div>
            </div><!--增加Unit Nav结束-->

            <div data-role="navbar" >
                <div data-role="collapsible" data-mini="true" id="trayRemoveNav">
                    <h4>卸下货物</h4>
                    <div style="font-size:1em">
                        <form method="POST" id="unloadTrayDialogForm">
                            <p>选择某一个货物</p>
                            <select id="unitUnloadSelect">
                                <option value="default">default</option>
                            </select>
                            <fieldset class="ui-grid-a">
                                <div class="ui-block-a">
                                    <p style="text-align:center">卸下数量</p>
                                </div>
                                <div class="ui-block-b">
                                    <input type="number" name="toRemove" id="UnitToRemoveNum">
                                </div>
                            </fieldset>
                        </form>
                        <a class='ui-btn'id="removeUnit">全部卸下</a>
                        <a class='ui-btn'id="removePartUnit">卸下固定数量</a>
                        <div id="TrayDialogFormHint4Remove"></div>
                    </div>
                </div>
            </div><!--减少Unit Nav结束-->

            <div data-role="navbar" >
                <div data-role="collapsible" data-mini="true" id="trayShelfNav">
                    <h4>上下货架</h4>
                    <div id="tray2ShelfHelperNavHint"></div>
                    <div style="font-size:1em">
                        <div id="tray2ShelfHint"></div>
                        <div data-role="collapsible" data-mini="true" id="tray2ShelfHelperNavNew">
                            <h4>货架选择</h4>
                            <div style="font-size:0.8em">
                                <div id="tsPosHint"></div>
                                <form>
                                    <select id="slotChar">
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                    </select>
                                    <select id="slotNum">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                        <option value="18">18</option>
                                        <option value="19">19</option>
                                        <option value="20">20</option>
                                        <option value="21">21</option>
                                        <option value="22">22</option>
                                        <option value="23">23</option>
                                        <option value="24">24</option>
                                        <option value="25">25</option>
                                        <option value="26">26</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                        <div data-role="collapsible" data-mini="true" id="tray2ShelfHelperNav">
                            <h4>货架选择旧</h4>
                            <div style="font-size:0.8em">
                                <div id="tray2ShelfHelperNavHint"></div>
                                <input type="checkbox" data-role="flipswitch" name="flip-checkbox-1" id="flip-OldStyle-tray2S">
                                <form>
                                    <select id="slotHouse">
                                        <option value="default">default</option>
                                    </select>
                                    <select id="slotRow">
                                        <option value="default">default</option>
                                    </select>
                                    <select id="slotCol">
                                        <option value="default">default</option>
                                    </select>
                                    <select id="slotFloor">
                                        <option value="default">default</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                        <a class='ui-btn'id="load2Shelf">上架</a>
                        <a class='ui-btn'id="unload4Shelf">下架</a>
                    </div>
                </div>
            </div><!--上下货架Unit Nav结束-->

            <a class='ui-btn'id="passDoorTemp">临时过仓库</a>
        </div>
        
        <div data-role="footer" data-position="fixed" id="fillTrayDialogActionFooter" style="display:none" >

            <div data-role="navbar">
            	<ul>
                	<li><a id="fillTrayDialogInSubmit" class="ui-btn ui-shadow ui-corner-all">装盘</a></li>
                    <li><a id="LoadTrayInSlot"  class="ui-btn ui-shadow ui-corner-all ui-state-disabled">上货架</a></li>
                    <li><a id="unLoadTrayOutSlot"  class="ui-btn ui-shadow ui-corner-all ui-state-disabled" >下货架</a></li>
                    <li><a id="fillTrayDialogOutSubmit" class="ui-btn ui-shadow ui-corner-all">装车</a></li>
                </ul>
                
            </div>
        </div>
        
        <div data-role="panel" id="selectTrayPanel">
        	<a href="#" data-rel="close" class="ui-btn ui-shadow ui-corner-all ui-icon-delete ui-btn-icon-notext">Delete</a>
            <p>查询php数据查看可选</p>
            <div id="slotOptionDiv">
            </div>
            <p id="slotHint"></p>
        </div>
        
        <div data-role="panel" id="selectSize">
        	<a href="#" data-rel="close" class="ui-btn ui-shadow ui-corner-all ui-icon-delete ui-btn-icon-notext">Delete</a>
            <p>选择尺寸</p>
            <div id="selectSizeMenu">
            </div>
        </div>
        
        <div data-role="popup" id="FillTraypopupDialog" data-theme="a"  data-dismissible="false" style="max-width:400px;">
            <div data-role="header" >
            <h1>Delete Page?</h1>
            </div>
            <div role="main" class="ui-content">
                <p class="ui-title">Are you sure you want to delete this page?</p>
                <a href="#" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b" data-rel="back">明白</a>
            </div>
        </div>
    </div>
    
    <!-- 信息显示 -->
    <div data-role="page" id="InfoPage">
        <div data-role="header">
        	<a href="#page" class="ui-btn ui-corner-all ui-shadow ui-icon-back ui-btn-icon-left ui-btn-icon-notext">HOME</a>
            <h1>仓库统计</h1>
        </div>
        <div data-role="content" id="InfoPageContent">	
            <p>货舱占用比</p>	
            <div id="_graph" data-theme="c" align="center">
					<div id="traysUse"> </div>
            </div>
            <p>吞吐量</p>	
            <div id="inOut_graph" data-theme="c" align="center">
					<div id="inOutStatics"> </div>
                    <p id="tips"></p>
            </div>
        </div>
        <div data-role="footer" data-position="fixed">
            <h4>Footer</h4>
        </div>
    </div>
</body>
<script type="application/javascript">
	//Global
	var _app = {};
	_app.filter = {};

    //user的信息;
    var userName = "<?php echo $_SESSION['user'];?>";
    var userID = "<?php echo $_SESSION['userID'];?>";

    console.log("用户"+userName+"|用户ID"+userID);
    
    $("#clearScanResult").on("click",function(){
    	$('#scanTrayContentUL').html("");
    });

    $("#addNewTrays").on("click",function(){
        var query = "INSERT INTO `wTrays` ( `rfid`, `twStatus`) VALUES ('TESTRFIDCODE','空闲')";
        $.post("phpSearch.php?insert="+query,function(data){
            console.log("结果:"+data);
            $("#shortCutContent").append(data);
        });
    });

    $("#randonRFID").on("click",function(){
        var query = "SELECT * FROM `wTrays` WHERE `rfid`='TESTRFIDCODE' ";
        $.post("phpSearch.php?query="+query,function(data){
            console.log("结果:"+data);
            //$("#shortCutContent").append(data);
            var obj = jQuery.parseJSON(data);
            for( var i in obj){
                var rfid = "TESTRFIDCODE"+obj[i].wtID;
                $.get("phpUpdate.php?table=wTrays&&idAttr=wtID&&idValue="+obj[i].wtID+"&&tAttr=rfid&&tValue="+rfid);
                
            }
        });
    });
</script>
<script type="application/javascript" src="enter-js.js"></script>

    
    
</html>
