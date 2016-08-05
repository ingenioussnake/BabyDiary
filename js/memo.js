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
    var offset = 0, list = [], operatingItem;
    getMemos();
    
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

    $("#edit_dialog_container").load("./fragments/edit_dialog.html", initEditDialog);
    $("#delete_dialog_container").load("./fragments/delete_dialog.html", initDeleteDialog);
    $("#toast_container").load("./fragments/toast.html");

    
    function getMemos () {
        $.get("./db/memo.php", {offset: offset, size: ACTIVE_COUNT, type: "list"}, function(data){
            console.log(data);
            addMemos(data);
        }, "json");
    }
    
    function addMemos (data) {
        var item, $item, $list = $("#memo_list"), length = data.length, listLength = list.length;
        for (var i = 0; i < length; i++) {
            item = new MemoItem(data[i]);
            $item = item.createListItem($list);
            $item.attr("idx", listLength + i);
            list.push(item);
        }
        if (length === ACTIVE_COUNT) {
            $("a.more").show();
        } else {
            $("a.more").hide();
        }
    }

    function showEditDialog (item) {
        item.createForm($("#edit_dialog .weui_dialog_bd"), function(){
            $("#edit_dialog").show();
        });
    }

    function showDeleteDialog () {
        $("#delete_dialog").show();
    }
    function initEditDialog () {
        $("#edit_dialog").on("click", ".weui_btn_dialog.default", function(){
            $("#edit_dialog").hide();
        });

        $("#edit_dialog").on("click", ".weui_btn_dialog.primary", function(){
            var data = operatingItem.getFormData();
            data.id = operatingItem.id;
            $.ajax({
                url: "./db/memo.php?type=update&action=memo",
                type: "POST",
                data: data,
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

    function updateItem (data) {
        operatingItem.update(data);
        operatingItem.updateListItem();
    }

    function removeItem (item) {
        $("li.memo_item[idx="+ list.indexOf(item) +"]", "#memo_list").remove();
    }
});