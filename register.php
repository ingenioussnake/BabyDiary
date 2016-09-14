<!DOCTYPE html>
<html>
<head>
    <title>注册</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <link rel="stylesheet" href="./style/weui.min.css"/>
    <link rel="stylesheet" href="./style/common.css"/>
    <style type="text/css">
        .weui_uploader_file {
            position: relative;
        }
        a.delete {
            width: 32px;
            height: 32px;
            background-image: url("./images/icons/icon_delete.png");
            background-size: contain;
            position: absolute;
            bottom: 0px;
            right: 0px;
        }
    </style>
    <script type="text/javascript" src="./js/libs/jquery-3.0.1.min.js"></script>
    <script type="text/javascript" src="./js/libs/md5.js"></script>
    <script type="text/javascript" src="./js/libs/lrz/lrz.bundle.js"></script>
    <script type="text/javascript">
        $(function(){
            $("#toast_container").load("./fragments/toast.html");
            $("#submit").click(register);
            $("#avatar").change(onAvatarChange);
            $(".weui_uploader_files").on("click", "a.delete", onAvatarDelete);
            function register () {
                var oFormData = new FormData();
                oFormData.append("username", $("#username").val());
                oFormData.append("password", CryptoJS.MD5($("#password").val()).toString());
                oFormData.append("baby_avatar", $("#avatar").get(0).__resized_file);
                oFormData.append("baby_name", $("#name").val());
                oFormData.append("baby_blood", $("input[name=blood]:checked").val());
                oFormData.append("baby_sex", $("input[name=sex]:checked").val());
                oFormData.append("baby_birthday", $("#birthday").val());
                oFormData.append("baby_weight", $("#weight").val());
                oFormData.append("baby_height", $("#height").val());
                $.ajax({
                    url: "./db/user.php?type=reg",
                    type: "POST",
                    data: oFormData,
                    contentType: false, // 告诉jQuery不要去处理发送的数据
                    processData: false,  // 告诉jQuery不要去设置Content-Type请求头
                    success: function (respond, status) {
                        if (respond === "1") {
                            $("#toast").show();
                            setTimeout(function () {
                                $("#toast").hide();
                                window.location = "./index.php";
                            }, 2000);
                        } else {
                            $(".weui_cells_tips").show();
                        }
                    }
                });
            }

            function onAvatarChange (e) {;
                var file = this.files[0];
                lrz(file, {
                    width: 128,
                    quality: 0.8
                }).then(function(rst){
                    $("input#avatar").get(0).__resized_file = rst.file;
                    var $img = $("<li preview='true'><a class='delete' href='javascript:;'></a></li>");
                    $img.addClass("weui_uploader_file");
                    $img.css("background-image", "url("+rst.base64+")");
                    $("ul.weui_uploader_files").append($img);
                    $("div.weui_uploader_input_wrp").hide();
                    return rst;
                })
            }

            function onAvatarDelete (e) {;
                delete $("input#avatar").get(0).__resized_file;
                $("ul.weui_uploader_files").empty();
                $("div.weui_uploader_input_wrp").show();
            }
        });
    </script>
</head>
<body>
    <div class="container">
        <div class="home slideIn">
            <div class="hd">
                <h1 class="page_title">用户注册</h1>
            </div>
            <div class="bd">
                <div class="weui_cells weui_cells_form action_form">
                    <div class="weui_cells_tips" style="display: none;">注册失败</div>
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
                <div class="weui_cells weui_cells_form action_form">
                    <div class="weui_cell">
                        <div class="weui_cell picture_cell">
                            <div class="weui_cell_bd weui_cell_primary">
                                <div class="weui_uploader">
                                    <div class="weui_uploader_hd weui_cell">
                                        <div class="weui_cell_bd weui_cell_primary">宝宝头像</div>
                                    </div>
                                    <div class="weui_uploader_bd">
                                        <ul class="weui_uploader_files">
                                        </ul>
                                        <div class="weui_uploader_input_wrp">
                                            <input class="weui_uploader_input" type="file" accept="image/*" id="avatar">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="weui_cell">
                        <div class="weui_cell_hd"><label class="weui_label" for="">宝宝姓名</label></div>
                        <div class="weui_cell_bd weui_cell_primary">
                            <input id="name" class="weui_input" type="text" value="">
                        </div>
                    </div>
                    <div class="weui_cell">
                        <div class="weui_cell_hd"><label class="weui_label" for="">宝宝性别</label></div>
                        <div class="weui_cell_bd weui_cell_primary">
                            <input type="radio" placeholder="" value="1" name="sex"><span>男</span>
                            <input type="radio" placeholder="" value="0" name="sex"><span>女</span>
                        </div>
                    </div>
                    <div class="weui_cell">
                        <div class="weui_cell_hd"><label class="weui_label" for="">宝宝血型</label></div>
                        <div class="weui_cell_bd weui_cell_primary">
                            <input type="radio" placeholder="" value="A" name="blood"><span>A</span>
                            <input type="radio" placeholder="" value="B" name="blood"><span>B</span>
                            <input type="radio" placeholder="" value="AB" name="blood"><span>AB</span>
                            <input type="radio" placeholder="" value="O" name="blood"><span>O</span>
                        </div>
                    </div>
                    <div class="weui_cell">
                        <div class="weui_cell_hd"><label class="weui_label" for="">出生日期</label></div>
                        <div class="weui_cell_bd weui_cell_primary">
                            <input id="birthday" class="weui_input" type="date" placeholder="" value="">
                        </div>
                    </div>
                    <div class="weui_cell">
                        <div class="weui_cell_hd"><label class="weui_label" for="">出生体重</label></div>
                        <div class="weui_cell_bd weui_cell_primary">
                            <input id="weight" class="weui_input" type="number" placeholder="" value="" style="width: 4rem;">
                            <span>kg</span>
                        </div>
                    </div>
                    <div class="weui_cell">
                        <div class="weui_cell_hd"><label class="weui_label" for="">出生身长</label></div>
                        <div class="weui_cell_bd weui_cell_primary">
                            <input id="height" class="weui_input" type="number" placeholder="" value="" style="width: 4rem;">
                            <span>cm</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="weui_cell footer">
                <a id="submit" class="weui_btn weui_btn_primary" href="javascript:;">提交</a>
            </div>
        </div>
    </div>
    <div id="toast_container"></div>
</body>
</html>