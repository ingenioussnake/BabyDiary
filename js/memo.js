require.config({
    "paths": {
        "jquery": "./libs/jquery-3.0.1.min",
        "jquery.popup": "./libs/jquery.magnific-popup.min",
        "lrz": "./libs/lrz/lrz.bundle"
    },
    "shim": {
        "jquery.popup": ["jquery"]
    }
});

require(["model/MemoItem", "jquery", "jquery.popup"],
function(MemoItem, $){

    var ACTIVE_COUNT = 15;
    var MEMO_ITEM_TMPL = '<li class="memo_item">' +
                           '<div class="memo_item_container">' +
                               '<div class="avatarContainer">' +
                                   '<img class="avatar"></img>' +
                               '</div>' + 
                               '<div class="memo_item_content">' +
                                   '<div class="title"></div>' +
                                   '<div class="memo_container">'+
                                        '<div class="memo"></div>'+
                                        '<div class="image_container"></div>'+
                                   '</div>' +
                                   '<div class="footer">' +
                                        '<a class="icon edit" href="javascript:;"></a>' +
                                        '<a class="icon delete" href="javascript:;"></a>' +
                                        '<span class="date"></span>' +
                                   '</div>' + 
                               '</div>' + 
                       '</li>';

    var MEMO_PREVIEW_ITEM = "<a><img class='imageContent' /></a>";
    var offset = 0, list = [], operatingItem;
    getMemos();

    
    
    function getMemos () {
        $.get("./db/memo.php", {offset: offset, size: ACTIVE_COUNT, type: "list"}, function(data){
            console.log(data);
            addMemos(data);
        }, "json");
    }
    
    function addMemos (data) {
        var item, $item, $list = $("#memo_list"), length = data.length, listLength = list.length;
        for (var i = 0; i < length; i++) {
            item = data[i];
            $item = $(MEMO_ITEM_TMPL);
            updateMemoItem(item, $item, true);
            $item.attr("idx", listLength + i);
            $list.append($item);
            list.push(new MemoItem(item));
        }
        if (length === ACTIVE_COUNT) {
            $("a.more").show();
        } else {
            $("a.more").hide();
        }
    }

    function updateMemoItem (item, $li, updatePicture) {
        $li.attr("memo_id", item.id);
        updatePicture && $("img.avatar", $li).attr("src", "./db/memo.php?type=avatar&baby="+item.baby);
        $(".title", $li).text(item.title);      
        $("div.memo", $li).text(item.memo);
        updatePicture && $.get("./db/memo.php", {type: "pic_count", id: item.id}, function (data) {
            var length = data.length, i = 0, src, $item;
            if (data instanceof Array && length > 0) {
                for (;i<length;i++) {
                    src = "./db/memo.php?type=picture&id="+data[i];
                    $item = $(MEMO_PREVIEW_ITEM);
                    $item.attr("href", src);
                    $("img", $item).attr("src", src);
                    $("div.image_container", $li).append($item);
                }
                $(".memo_container .image_container", $li).magnificPopup({
                    delegate: 'a',
                    type: 'image',
                    mainClass: 'mfp-with-zoom mfp-img-mobile',
                    image: {
                        verticalFit: true
                    },
                    gallery: {
                        enabled: true,
                        navigateByImgClick: false
                    },
                    zoom: {
                        enabled: true,
                        duration: 200, // don't foget to change the duration also in CSS
                    }
                });
            }
        }, "json");
        $("div.footer span.date", $li).text(new Date(item.date).toLocaleDateString());
    }
    
    $("#memo_list").on("click", function(e){
        var $target =$(e.target),
            idx = $target.closest("li.memo_item").attr("idx");
            operatingItem = list[idx];
        if ($target.hasClass("edit")) {
            showEditDialog(operatingItem);
        } else if ($target.hasClass("delete")) {
            showDeleteDialog();
        }
    });

    $(".weui_btn.more").on("click", function(){
        offset = offset + ACTIVE_COUNT;
        getMemos();
    })

    function showEditDialog (item) {
        item.createForm($("#edit_dialog .weui_dialog_bd"), function(){
            $("#edit_dialog").show();
        });
    }

    function showDeleteDialog () {
        $("#delete_dialog").show();
    }

    $("#edit_dialog_container").load("./fragments/edit_dialog.html", initEditDialog);
    $("#delete_dialog_container").load("./fragments/delete_dialog.html", initDeleteDialog);
    $("#toast_container").load("./fragments/toast.html");

    function initEditDialog () {
        $("#edit_dialog").on("click", ".weui_btn_dialog.default", function(){
            $("#edit_dialog").hide();
        });

        $("#edit_dialog").on("click", ".weui_btn_dialog.primary", function(){
            var data = operatingItem.getFormData();
            data.append("id", operatingItem.id);
            $.ajax({
                url: "./db/memo.php?type=update&action=memo",
                type: "POST",
                data: data,
                contentType: false, // 告诉jQuery不要去处理发送的数据
                processData: false, // 告诉jQuery不要去设置Content-Type请求头
                success: function(respond){
                    console.log(respond);
                    if (!!respond) {
                        $("#edit_dialog").hide();
                        updateItem(data);
                        showToast();
                    }
                }
            });
        });
    }

    function initDeleteDialog () {
        $("#delete_dialog").on("click", ".weui_btn_dialog.default", function(){
            $("#delete_dialog").hide();
        });

        $("#delete_dialog").on("click", ".weui_btn_dialog.primary", function(){
            $.post("./db/memo.php?type=remove&action=memo", {id: operatingItem.id}, function(respond){
                console.log(respond);
                if (!!respond) {
                    $("#delete_dialog").hide();
                    removeItem(operatingItem);
                    showToast();
                }
            });
        });
    }

    function updateItem (item) {
        var $li = $("li.memo_item[idx="+ list.indexOf(operatingItem) +"]", "#memo_list");
        var data = {
            baby: item.get("baby"),
            date: item.get("date"),
            id: item.get("id"),
            memo: item.get("memo"),
            title: item.get("title"),
            time: item.get("time"),
        };
        updateMemoItem(data, $li);
    }

    function removeItem (item) {
        $("li.memo_item[idx="+ list.indexOf(item) +"]", "#memo_list").remove();
    }
});