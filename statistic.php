<?php
    include "./auth.php";
    if (!auth_check()) {
        header("Location: ./login.php");
    }
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
<title>宝宝在长大</title>
<link rel="stylesheet" href="./style/weui.min.css"/>
<link rel="stylesheet" href="./style/common.css"/>
<link rel="stylesheet" href="./style/segment.css"/>
<link rel="stylesheet" href="./style/statistic.css"/>
<script type="text/javascript" src="./js/libs/iscroll-probe.js"></script>
<script type="text/javascript" src="./js/libs/require.js" data-main="./js/statistic" ></script>
</head>
<body>
    <div class="container" id="container">
        <div class="tabbar">
            <div class="weui_tab">
                <div class="hd statistic_header">
                    <a class="icon icon_back" href="./index.php"></a>
                    <h1 class="page_title">宝宝加油</h1>
                    <a class="icon icon_new new_action" href="#"></a>
                </div>
                <div class="weui_tab_bd statistic_container">

                </div>
                <div class="weui_tabbar statistic_selecter">
                    <a href="javascript:;" class="weui_tabbar_item weui_bar_item_on" id="appetite">
                        <div class="weui_tabbar_icon">
                            <img src="./images/icons/icon_dining.png" alt="">
                        </div>
                        <p class="weui_tabbar_label">饭量</p>
                    </a>
                    <a href="javascript:;" class="weui_tabbar_item" id="growth">
                        <div class="weui_tabbar_icon">
                            <img src="./images/icons/icon_growth.png" alt="">
                        </div>
                        <p class="weui_tabbar_label">身材</p>
                    </a>
                    <a href="javascript:;" class="weui_tabbar_item" id="sleep" >
                        <div class="weui_tabbar_icon">
                            <img src="./images/icons/icon_sleep.png" alt="">
                        </div>
                        <p class="weui_tabbar_label">睡眠</p>
                    </a>
                    <a href="javascript:;" class="weui_tabbar_item" id="shit" >
                        <div class="weui_tabbar_icon">
                            <img src="./images/icons/icon_shit.png" alt="">
                        </div>
                        <p class="weui_tabbar_label">拉臭</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div id="toast_container"></div>
    <div id="loading_toast_container"></div>
</body>
</html>