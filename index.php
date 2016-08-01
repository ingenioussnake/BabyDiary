<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>宝宝日记</title>
    <link rel="stylesheet" href="./style/weui.min.css"/>
    <link rel="stylesheet" href="./style/common.css"/>
    <style type="text/css">
        i.icon {
            background-size: contain;
        }

        .icon_dining {
            background-image: url("./images/icons/icon_dining.png");
        }
        .icon_sleep {
            background-image: url("./images/icons/icon_sleep.png");
        }
        .icon_shit {
            background-image: url("./images/icons/icon_shit.png");
        }
        .icon_height {
            background-image: url("./images/icons/icon_height.png");
        }
        .icon_weight {
            background-image: url("./images/icons/icon_weight.png");
        }
        .icon_memo {
            background-image: url("./images/icons/icon_memo.png");
        }
        .icon_timeline {
            background-image: url("./images/icons/icon_timeline.png");
        }
        .icon_statistic {
            background-image: url("./images/icons/icon_statistic.png");
        }
        .icon_profile {
            background-image: url("./images/icons/icon_profile.png");
        }
    </style>
</head>
<?php

    $users = array('Miran' => 'Leed');

    $user = $_SERVER['PHP_AUTH_USER'];
    $pass = $_SERVER['PHP_AUTH_PW'];

    $validated = (in_array($user, array_keys($users))) && ($pass == $users[$user]);

    if (!$validated) {
        header('WWW-Authenticate: Basic realm="My Realm"');
        header('HTTP/1.0 401 Unauthorized');
        echo 'Not authorized';
        exit;
    }
?> 
<body>
    <div class="container">
        <div class="home slideIn">
            <div class="hd">
                <h1 class="page_title">宝宝日记</h1>
            </div>
            <div class="bd">
                <div class="weui_grids">
                    <a class="weui_grid" href="./actions/new.html?type=dining">
                        <div class="weui_grid_icon">
                            <i class="icon icon_dining"></i>
                        </div>
                        <p class="weui_grid_label">吃饭</p>
                    </a>
                    <a class="weui_grid" href="./actions/new.html?type=sleep">
                        <div class="weui_grid_icon">
                            <i class="icon icon_sleep"></i>
                        </div>
                        <p class="weui_grid_label">睡觉</p>
                    </a>
                    <a class="weui_grid" href="./actions/new.html?type=shit">
                        <div class="weui_grid_icon">
                            <i class="icon icon_shit"></i>
                        </div>
                        <p class="weui_grid_label">拉臭</p>
                    </a>
                    <a class="weui_grid" href="./actions/new.html?type=height">
                        <div class="weui_grid_icon">
                            <i class="icon icon_height"></i>
                        </div>
                        <p class="weui_grid_label">身高</p>
                    </a>
                    <a class="weui_grid" href="./actions/new.html?type=weight">
                        <div class="weui_grid_icon">
                            <i class="icon icon_weight"></i>
                        </div>
                        <p class="weui_grid_label">体重</p>
                    </a>
                    <a class="weui_grid" href="./actions/new.html?type=memo">
                        <div class="weui_grid_icon">
                            <i class="icon icon_memo"></i>
                        </div>
                        <p class="weui_grid_label">心情</p>
                    </a>
                    <a class="weui_grid" href="./timeline/timeline.html">
                        <div class="weui_grid_icon">
                            <i class="icon icon_timeline"></i>
                        </div>
                        <p class="weui_grid_label">一天</p>
                    </a>
                    <!-- <a class="weui_grid" href="./statistic/statistic.html"> -->
                    <a class="weui_grid" href="javascript:;">
                        <div class="weui_grid_icon">
                            <i class="icon icon_statistic"></i>
                        </div>
                        <p class="weui_grid_label">统计</p>
                    </a>
                    <a class="weui_grid" href="./profile/profile.html">
                        <div class="weui_grid_icon">
                            <i class="icon icon_profile"></i>
                        </div>
                        <p class="weui_grid_label">档案</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>