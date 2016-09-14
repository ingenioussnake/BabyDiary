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
    <title>宝宝档案</title>
    <link rel="stylesheet" href="./style/weui.min.css"/>
    <link rel="stylesheet" href="./style/common.css"/>
    <script src="./js/libs/jquery-3.0.1.min.js"></script>
    <script type="text/javascript">
        $(function(){
            $.get("./db/profile.php", {type: "info"}, function(data){
                $(".baby_name").html(data.name);
                $(".baby_sex").html(data.sex === "1" ? "男" : "女");
                $(".baby_blood_type").html(data.blood);
                $(".baby_birthday").html(data.birthday);
                $(".baby_weight").html(data.weight);
                $(".baby_height").html(data.height);
            }, "json");
        });
    </script>
</head>

<body>
    <div class="container" id="container">
        <div class="panel">
            <div class="hd">
                <h1 class="page_title">宝宝档案</h1>
            </div>
            <div class="bd">
                <div class="weui_panel weui_panel_access">
                    <div class="weui_panel_bd">
                        <a class="weui_media_box weui_media_appmsg" href="javascript:void(0);">
                            <div class="weui_media_bd">
                                <h4 class="weui_media_title">头像</h4>
                            </div>
                            <div class="weui_media_hd baby_avatar">
                                <img class="weui_media_appmsg_thumb" alt="" src="./db/profile.php?type=avatar">
                            </div>
                        </a>
                    </div>
                </div>
                <div class="weui_panel weui_panel_access">
                    <a class="weui_media_box weui_media_appmsg" href="javascript:void(0);">
                        <div class="weui_media_bd">
                            <h4 class="weui_media_title">姓名</h4>
                        </div>
                        <div class="weui_media_text baby_name"></div>
                    </a>
                    <a class="weui_media_box weui_media_appmsg" href="javascript:void(0);">
                        <div class="weui_media_bd">
                            <h4 class="weui_media_title">性别</h4>
                        </div>
                        <div class="weui_media_text baby_sex"></div>
                    </a>
                    <a class="weui_media_box weui_media_appmsg" href="javascript:void(0);">
                        <div class="weui_media_bd">
                            <h4 class="weui_media_title">血型</h4>
                        </div>
                        <div class="weui_media_text baby_blood_type"></div>
                    </a>
                </div>
                <div class="weui_panel">
                    <div class="weui_panel_hd">出生信息</div>
                    <div class="weui_panel_bd">
                        <a class="weui_media_box weui_media_appmsg" href="javascript:void(0);">
                            <div class="weui_media_bd">
                                <h4 class="weui_media_title">出生日期</h4>
                            </div>
                            <div class="weui_media_text baby_birthday"></div>
                        </a>
                        <a class="weui_media_box weui_media_appmsg" href="javascript:void(0);">
                            <div class="weui_media_bd">
                                <h4 class="weui_media_title">出生体重</h4>
                            </div>
                            <div class="weui_media_text">
                                <span class="baby_weight"></span>
                                <span>kg</span>
                            </div>
                        </a>
                        <a class="weui_media_box weui_media_appmsg" href="javascript:void(0);">
                            <div class="weui_media_bd">
                                <h4 class="weui_media_title">出生身长</h4>
                            </div>
                            <div class="weui_media_text">
                                <span class="baby_height"></span>
                                <span>cm</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>