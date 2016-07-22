<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>宝宝日记</title>
    <link rel="stylesheet" href="./style/weui.min.css"/>
    <link rel="stylesheet" href="./style/index.css"/>
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
        <div class="page slideIn button">
            <div class="hd">
                <h1 class="page_title">宝宝日记</h1>
            </div>
            <div class="bd spacing">
                <a class="weui_btn weui_btn_primary" href="./actions/new.html?type=dining">吃饭</a>
                <a class="weui_btn weui_btn_primary" href="./actions/new.html?type=sleep">睡觉</a>
                <a class="weui_btn weui_btn_primary" href="./actions/new.html?type=shit">拉臭</a>
                <a class="weui_btn weui_btn_primary" href="./statistic/timeline.html">时间线</a>
            </div>
        </div>
    </div>
</body>