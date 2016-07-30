$(function(){
    var LIST_ITEM_TMPL = "<li class='event'>"+
                    "<input type='radio' name='tl-group' checked/>"+
                    "<label></label>"+
                    "<div class='thumb'><span></span></div>"+
                    "<div class='content-perspective'>"+
                        "<div class='content'>"+
                            "<div class='content-inner'>"+
                                "<h3></h3>"+
                                "<p class='item_tlb'>"+
                                    "<a class='edit' href='javascript:;'></a>"+
                                    "<a class='delete' href='javascript:;'></a>"+
                                "</p>"+
                            "</div>"+
                        "</div>"+
                    "</div>"+
                "</li>"

    var DINING_LIST_ITEM_TMPL = "<div><p><h4></h4></p><p><span>开始<span class='from'></span></span></p>" +
                                "<p><span>结束<span class='to'></span></span></p><p><span class='appetite'></span></p>"+
                                "<p><span class='comment'></span></p></div>";
    var SLEEP_LIST_ITEM_TMPL = "<div><p><span>开始<span class='from'></span></span></p>" +
                                "<p><span>结束<span class='to'></span></span></p></div>";
    var MEMO_LIST_ITEM_TMPL = "<div><p><span class='memo'></span></p>"+
                                "<div class='weui_uploader_bd'>"+
                                    "<ul class='weui_uploader_files'></ul>"+
                                "</div>"+
                              "</div>";

    var DINING_TYPE = {mm: "母乳", fm: "配方", mx: "混合"};
    var list, operatingItem;

    $.get("./timeline.php", {type: "date"}, function(data){
        console.log(data);
        if (data.length > 0) {
            addDate(data);
            getList();
        }
    }, "json");

    $("#date_slt").on("change", getList);

    $(".timeline").on("click", ".edit", function(e){
        editItem(getItemIndex($(e.target)));
    });

    $(".timeline").on("click", ".delete", function(e){
        deleteItem(getItemIndex($(e.target)));
    });

    function addDate (data) {
        var $date = $("#date_slt");
        $date.empty();
        for (var i = 0; i < data.length; i++) {
            var $option = $("<option />");
            $option.val(data[i]);
            $option.html(data[i]);
            $date.append($option);
        }
    }

    function getList () {
        var date = $("#date_slt").val();
        $.get("./timeline.php", {type: "list", date: date}, function(data){
            $(".timeline").empty();
            if (data.length > 0) {
                list = data;
                data.sort(function(a, b){
                    a.date = a.date.replace(/-/g, "/");
                    b.date = b.date.replace(/-/g, "/");
                    // for ios
                    return new Date(a.date + " " + (a.start || a.time)) - new Date(b.date + " " + (b.start || b.time));
                });
                console.log(data);
                addItems(data);
            }
        }, "json");
    }

    function addItems (data) {
        var $list = $(".timeline"),
            action, item, $item,
            generator;
        for (var i = 0; i < data.length; i++) {
            item = data[i];
            action = item.type;
            $item = $(LIST_ITEM_TMPL);
            item.baby = parseInt(item.baby);
            if (action === "dining") {
                $(".content-inner", $item).append($(DINING_LIST_ITEM_TMPL));
                updateDiningItem($item, item);
            } else if (action === "sleep") {
                $(".content-inner", $item).append($(SLEEP_LIST_ITEM_TMPL));
                updateSleepItem($item, item);
            } else if (action === "shit") {
                updateShitItem($item, item);
            } else if (action === "height") {
                updateHeightItem($item, item);
            } else if (action === "weight") {
                updateWeightItem($item, item);
            } else if (action === "memo") {
                $(".content-inner", $item).append($(MEMO_LIST_ITEM_TMPL));
                updateMemoItem($item, item);
                // loadMemoPictures(item.id);
            }
            $item.attr("idx", i);
            $list.prepend($item);
        }
    }

    function updateDiningItem ($item, row) {
        $(".content-inner h3", $item).html("吃饭");
        var $thumb = $(".thumb", $item);
        $thumb.removeClass();
        $thumb.addClass("thumb type_" + row.food);
        $(".thumb span", $item).html(removeNumberTail(row.start));
        $("h4", $item).html(DINING_TYPE[row.food]);
        if (row.food !== "mm") {
            $(".appetite", $item).html(row.appetite + "勺");
        } else {
            $(".appetite", $item).css("display", "none");
        }
        $(".comment", $item).html(row.comment);
        $(".from", $item).html(removeNumberTail(row.start));
        $(".to", $item).html(removeNumberTail(row.end));
    }

    function updateSleepItem ($item, row) {
        $(".content-inner h3", $item).html("睡觉");
        var $thumb = $(".thumb", $item);
        if (!$thumb.hasClass("type_sleep")) {
            $thumb.addClass("type_sleep");
        }
        $(".thumb span", $item).html(removeNumberTail(row.start));
        $(".from", $item).html(removeNumberTail(row.start));
        $(".to", $item).html(removeNumberTail(row.end));
    }

    function updateShitItem ($item, row) {
        var $thumb = $(".thumb", $item);
        if (!$thumb.hasClass("type_shit")) {
            $thumb.addClass("type_shit");
        }
        $(".content-inner h3", $item).html("好臭");
        $(".thumb span", $item).html(removeNumberTail(row.time));
    }

    function updateHeightItem ($item, row) {
        var $thumb = $(".thumb", $item);
        if (!$thumb.hasClass("type_height")) {
            $thumb.addClass("type_height");
        }
        $(".content-inner h3", $item).html("身高：" + row.height + "cm");
        $(".thumb span", $item).html(removeNumberTail(row.time));
    }

    function updateWeightItem ($item, row) {
        var $thumb = $(".thumb", $item);
        if (!$thumb.hasClass("type_weight")) {
            $thumb.addClass("type_weight");
        }
        $(".content-inner h3", $item).html("体重：" + row.weight + "kg");
        $(".thumb span", $item).html(removeNumberTail(row.time));
    }

    function updateMemoItem ($item, row) {
        var $thumb = $(".thumb", $item);
        if (!$thumb.hasClass("type_memo")) {
            $thumb.addClass("type_memo");
        }
        $(".content-inner h3", $item).html(row.title);
        $(".content-inner .memo", $item).html(row.memo);
        $(".thumb span", $item).html(removeNumberTail(row.time));
    }

    function loadMemoPictures (id) {
        $.get("./timeline.php", {type: "picture", id: id}, function(data){
            console.log(data);
        });
    }

    function getItemIndex ($inner) {
        return $inner.closest("li.event").attr("idx");
    }

    function editItem (index) {
        operatingItem = list[index];
        showEditDialog(operatingItem);
    }

    function deleteItem (index) {
        operatingItem = list[index];
        $("#delete_dialog").show();
    }

    function showEditDialog (item) {
        var action = item.type;
        $("#edit_dialog .weui_dialog_bd").load("../actions/" + action + "_fragment.html", function(respond){
            if (!respond) return;
            applyData(item);
            $("#edit_dialog").show();
        });
    }

    $("#edit_dialog").on("click", ".weui_btn_dialog.default", function(){
        $("#edit_dialog").hide();
    });

    $("#edit_dialog").on("click", ".weui_btn_dialog.primary", function(){
        var item = loadData();
        item.id = operatingItem.id;
        item.type = operatingItem.type;
        var url = (item.type === "dining") ? "../actions/dining.php" : "../actions/action.php"; //TODO: ugly
        $.post(url, {type: "update", data: item}, function(respond){
            console.log(respond);
            if (!!respond) {
                $("#edit_dialog").hide();
                updateData(item);
                showToast();
            }
        });
    });

    $("#delete_dialog").on("click", ".weui_btn_dialog.default", function(){
        $("#delete_dialog").hide();
    });

    $("#delete_dialog").on("click", ".weui_btn_dialog.primary", function(){
        var item = operatingItem;
        $.post("../actions/action.php", {type: "remove", data: item}, function(respond){
            console.log(respond);
            if (!!respond) {
                $("#delete_dialog").hide();
                $("li.event[idx="+ list.indexOf(item) +"]").remove();
                showToast();
            }
        });
    });

    function updateData (item) {
        var $item = $("li.event[idx="+ list.indexOf(operatingItem) +"]"),
            action = item.type;
        if (item.date !== operatingItem.date) {
            $item.remove();
            return;
        }
        if (action === "dining") {
            updateDiningItem($item, item);
        } else if (action === "sleep") {
            updateSleepItem($item, item);
        } else if (action === "shit") {
            updateShitItem($item, item);
        } else if (action === "height") {
            updateHeightItem($item, item);
        } else if (action === "weight") {
            updateWeightItem($item, item);
        }
    }
});