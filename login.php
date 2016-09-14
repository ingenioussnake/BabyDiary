<?php
    include "./auth.php";
    $error = false;
    $auth = false;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $data = array_map(function($item){return test_input($item);}, $_POST);
        if (login($data)) {
            auth_add($data['username']);
            $auth = true;
        } else {
            $error = true;
        }
    } else {
        $auth = auth_check();
    }
    if ($auth) {
        header("Location: ./index.php");
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function login ($data) {
        $ch = curl_init(getUserUrl());
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    function getUserUrl () {
        $paths = explode("/", $_SERVER['PHP_SELF']);
        $paths[count($paths) - 1] = "db/user.php?type=login";
        return "http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'].implode("/", $paths);
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>登陆</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <link rel="stylesheet" href="./style/weui.min.css"/>
    <link rel="stylesheet" href="./style/common.css"/>
    <script type="text/javascript" src="./js/libs/jquery-3.0.1.min.js"></script>
    <script type="text/javascript" src="./js/libs/md5.js"></script>
    <script type="text/javascript">
        $(function(){
            $("#submit").click(login);
            function login () {
                var params = {username: $("#username").val(), password: CryptoJS.MD5($("#password").val()).toString()};
                var tempform = document.createElement("form");
                tempform.action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>";
                tempform.method = "post";
                tempform.style.display="none";
                for (var x in params) {        
                    var opt = document.createElement("input");        
                    opt.name = x;
                    opt.setAttribute("value",params[x]);
                    tempform.appendChild(opt);
                }
                
                var opt = document.createElement("input");  
                opt.type = "submit";
                opt.name = "postsubmit";
                tempform.appendChild(opt);
                document.body.appendChild(tempform);  
                tempform.submit();
            }
        });
    </script>
</head>
<body>
    <div class="container">
        <div class="home slideIn">
        <?php
            $auth = false;
            if (!$auth) {
        ?>
            <div class="hd">
                <h1 class="page_title">用户登录</h1>
            </div>
            <div class="bd">
                <div class="weui_cells weui_cells_form action_form">
                    <?php if ($error) { ?><div class="weui_cells_tips">登陆失败</div><?php } ?>
                    <div class="weui_cell">
                        <div class="weui_cell_hd"><label class="weui_label" for="">用户名</label></div>
                        <div class="weui_cell_bd weui_cell_primary">
                            <input id="username" class="weui_input" type="text" value="">
                        </div>
                    </div>
                    <div class="weui_cell">
                        <div class="weui_cell_hd"><label class="weui_label" for="">密码</label></div>
                        <div class="weui_cell_bd weui_cell_primary">
                            <input id="password" class="weui_input" type="password" placeholder="" value="">
                        </div>
                    </div>
                    <!-- <div class="weui_cell">
                        <div class="weui_cell_hd"><label class="weui_label" for="">验证码</label></div>
                        <div class="weui_cell_bd weui_cell_primary">
                            <input id="vcode" class="weui_input" type="text" placeholder="" value="">
                        </div>
                    </div> -->
                </div>
            </div>
            <div class="weui_cell footer">
                <a id="submit" class="weui_btn weui_btn_primary" href="javascript:;">提交</a>
            </div>
        <?php } else { ?>
            <div class="hd">
                <h1 class="page_title">您已登录</h1>
            </div>
        <?php }?>
        </div>
    </div>
</body>
</html>