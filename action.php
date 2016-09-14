<?php
    include "./auth.php";
    if (!auth_check()) {
        header("Location: ./login.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>有事情喽</title>
    <link rel="stylesheet" href="./style/weui.min.css"/>
    <link rel="stylesheet" href="./style/common.css"/>
    <style type="text/css">
        .footer:before {
            border: 0;
        }
        .footer {
            padding-top: 0;
        }
        .footer a.weui_btn {
            width: 40%;
            margin-top: auto;
            margin-bottom: auto;
        }
    </style>
    <script type="text/javascript" src="./js/libs/require.js" data-main="./js/action" ></script>
</head>

<body>
    <div class="container">
        <div class="page slideIn cell">
            <div class="hd">
                <h1 class="page_title"></h1>
            </div>
            <div class="bd">
            </div>
            <div class="weui_cell footer">
                <a id="submit" class="weui_btn weui_btn_primary" href="javascript:;">完成</a>
                <a id="cancel" class="weui_btn weui_btn_primary" href="./index.php">返回</a>
            </div>
        </div>
        <div id="toast_container"></div>
    </div>
</body>