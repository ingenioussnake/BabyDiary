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
<title>心情记录</title>
<link rel="stylesheet" href="./style/weui.min.css"/>
<link rel="stylesheet" href="./style/common.css"/>
<link rel="stylesheet" href="./style/memo.css"/>
<link rel="stylesheet" href="./style/magnific-popup.css"/>
<script type="text/javascript" src="./js/libs/require.js" data-main="./js/memo" ></script>
</head>
<body>
    <div class="container" id="container">
        <div class="panel button">
            <div class="hd">
                <a class="icon icon_back" href="./index.php"></a>
                <h1 class="page_title">心情点滴</h1>
                <a class="icon icon_new" href="./action.php?type=memo"></a>

            </div>
            <div class="bd">
                <ul id="memo_list"></ul>
            </div>
            <div class="button_sp_area">
                <a href="javascript:;" class="weui_btn weui_btn_plain_default more">更多</a>
            </div>
        </div>
    </div>
    <div id="toast_container"></div>
    <div id="loading_toast_container"></div>
    <div id="edit_dialog_container"></div>
    <div id="delete_dialog_container"></div>
</body>
</html>