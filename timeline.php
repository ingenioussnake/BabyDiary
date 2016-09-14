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
    <title>宝宝的一天</title>
    <link rel="stylesheet" href="./style/weui.min.css"/>
    <link rel="stylesheet" href="./style/common.css"/>
    <link rel="stylesheet" href="./style/timeline.css"/>
    <link rel="stylesheet" href="./style/timeline/style.css"/>
    <link rel="stylesheet" href="./style/magnific-popup.css"/>
    <script src="./js/libs/ModernizrTimeline.js"></script>
    <script type="text/javascript" src="./js/libs/require.js" data-main="./js/timeline" ></script>
    
</head>

<body ontouchstart>
    <div class="container js_container">
        <div class="slideIn cell">
            <div class="hd">
                <h1 class="page_title" id="baby_name"></h1>
            </div>
            <div class="bd">
                <div class="weui_cells weui_cells_form">
                    <div class="weui_cell header weui_cell_select">
                        <div class="weui_cell_bd weui_cell_primary">
                            <select class="weui_select" name="date" id="date_slt">
                            </select>
                        </div>
                        <div class="weui_cell_hd">
                            <label class="weui_label dayth" for="">（第<span></span>天）</label>
                        </div>
                    </div>
                    <div class="weui_cell timeline_container" id="content_tl">
                        <ul class="timeline">
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div id="edit_dialog_container"></div>
        <div id="delete_dialog_container"></div>
        <div id="toast_container"></div>
        <div id="loading_toast_container"></div>
    </div>
</body>