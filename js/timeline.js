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

    var EXTRA_LIST_ITEM_TMPL = "<div><p><span>开始<span class='from'></span></span></p>" +
                                "<p><span>结束<span class='to'></span></span></p></div>";
    var list, operatingItem;

    $.get("./statistic.php", {type: "date"}, function(data){
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
        $.get("./statistic.php", {type: "list", date: date}, function(data){
            $(".timeline").empty();
            if (data.length > 0) {
                list = data;
                data.sort(function(a, b){
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
            item.mm = parseInt(item.mm);
            if (action === "dining") {
                $(".content-inner", $item).append($(EXTRA_LIST_ITEM_TMPL));
                updateDiningItem($item, item);
            } else if (action === "sleep") {
                $(".content-inner", $item).append($(EXTRA_LIST_ITEM_TMPL));
                updateSleepItem($item, item);
            } else if (action === "shit") {
                updateShitItem($item, item);
            }
            $item.attr("idx", i);
            $list.prepend($item);
        }
    }

    function updateDiningItem ($item, row) {
        $(".content-inner h3", $item).html(row.mm ? "母乳" : "配方");
        var $thumb = $(".thumb", $item);
        $thumb.removeClass("type_mm type_fm");
        $thumb.addClass(row.mm ? "type_mm" : "type_fm");
        $(".thumb span", $item).html(row.start);
        $(".from", $item).html(row.start);
        $(".to", $item).html(row.end);
    }

    function updateSleepItem ($item, row) {
        $(".content-inner h3", $item).html("睡觉");
        var $thumb = $(".thumb", $item);
        if (!$thumb.hasClass("type_sleep")) {
            $thumb.addClass("type_sleep");
        }
        $(".thumb span", $item).html(row.start);
        $(".from", $item).html(row.start);
        $(".to", $item).html(row.end);
    }

    function updateShitItem ($item, row) {
        var $thumb = $(".thumb", $item);
        if (!$thumb.hasClass("type_shit")) {
            $thumb.addClass("type_shit");
        }
        $(".content-inner h3", $item).html("好臭");
        $(".thumb span", $item).html(row.time);
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
            loadData(item);
            $("#edit_dialog").show();
        });
    }

    $("#edit_dialog").on("click", ".weui_btn_dialog.default", function(){
        $("#edit_dialog").hide();
    });

    $("#edit_dialog").on("click", ".weui_btn_dialog.primary", function(){
        var item = gatherData(operatingItem.type);
        item.id = operatingItem.id;
        item.type = operatingItem.type;
        $.post("../actions/dba.php", {type: "update", data: item}, function(respond){
            console.log(respond);
            if (!!respond) {
                $("#edit_dialog").hide();
                applyData(item);
                showToast();
            }
        });
    });

    $("#delete_dialog").on("click", ".weui_btn_dialog.default", function(){
        $("#delete_dialog").hide();
    });

    $("#delete_dialog").on("click", ".weui_btn_dialog.primary", function(){
        var item = operatingItem;
        $.post("../actions/dba.php", {type: "remove", data: item}, function(respond){
            console.log(respond);
            if (!!respond) {
                $("#delete_dialog").hide();
                $("li.event[idx="+ list.indexOf(item) +"]").remove();
                showToast();
            }
        });
    });

    function loadData (item) {
        var action = item.type,
            $container = $("#edit_dialog .weui_dialog_bd");
        $container.find("#date").val(item.date);
        if (action === "shit") {
            $container.find("#time").val(item.time);
        } else {
            $container.find("#start").val(item.start);
            $container.find("#end").val(item.end);
            if (action === "dining") {
                if (item.mm) {
                    $container.find("#mother").prop("checked", true);
                } else {
                    $container.find("#formula").prop("checked", true);
                }
            }
        }
    }

    function applyData (item) {
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
        }
    }
});