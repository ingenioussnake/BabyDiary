require.config({
    "paths": {
        "jquery": "./libs/jquery-3.0.1.min",
        "jquery.popup": "./libs/jquery.bpopup.min"
    },
    "shim": {
        "jquery.popup": ["jquery"]
    }
});

require(["model/DiningItem", "model/SleepItem", "model/ShitItem", "model/HeightItem", "model/WeightItem", "model/MemoItem", "jquery", "jquery.popup"],
function(DiningItem, SleepItem, ShitItem, HeightItem, WeightItem, MemoItem, $){

    var LIST_ITEM_TMPL = "<li class='event'>"+
                    "<input type='radio' name='tl-group'/>"+
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
                "</li>";

    var list = [], operatingItem, birthday,
        dateOffset = 0,
        DATE_SIZE = 15;

    $.get("./db/profile.php", {type: "basic"}, function(info){
        birthday = new Date(info.birthday);
        $("#baby_name").html(info.name);
        getDates();
    }, "json");

    $("#date_slt").on("change", function(){
        var date = $("#date_slt").val();
        if (date === "more") {
            dateOffset += DATE_SIZE;
            $("#date_slt option[value=more]").remove();
            getDates();
        } else {
            getList();
            showDayth();
        }
    });

    $(".timeline").on("click", ".edit", function(e){
        editItem(getItemIndex($(e.target)));
    });

    $(".timeline").on("click", ".delete", function(e){
        deleteItem(getItemIndex($(e.target)));
    });

    $("#edit_dialog_container").load("./fragments/edit_dialog.html", initEditDialog);
    $("#delete_dialog_container").load("./fragments/delete_dialog.html", initDeleteDialog);
    $("#toast_container").load("./fragments/toast.html");

    function getDates () {
        $.get("./db/timeline.php", {type: "date", offset: dateOffset, size: DATE_SIZE}, function(data){
            console.log(data);
            if (data.length > 0) {
                addDate(data);
                showDayth();
                getList();
            }
        }, "json");
    }

    function showDayth () {
        var date = new Date($("#date_slt").val());
        $(".dayth span").html((date - birthday) / (1000 * 60 * 60 * 24) + 1); // birtyday is the first day (not 0th)
    }

    function addDate (data) {
        var $date = $("#date_slt"), $option;
        // $date.empty();
        for (var i = 0; i < data.length; i++) {
            $option = $("<option />");
            $option.val(data[i]);
            $option.html(data[i]);
            $date.append($option);
        }
        if (data.length === DATE_SIZE) {
            $option = $("<option />");
            $option.val("more");
            $option.html("更多");
            $date.append($option);
        }
    }

    function getList () {
        var date = $("#date_slt").val();
        $.get("./db/timeline.php", {type: "list", date: date}, function(data){
            $(".timeline").empty();
            if (data.length > 0) {
                data.sort(function(a, b){
                    // for ios
                    return new Date(b.item.date.replace(/-/g, "/") + " " + (b.item.start || b.item.time)) - 
                            new Date(a.item.date.replace(/-/g, "/") + " " + (a.item.start || a.item.time));
                });
                addItems(data);
            }
        }, "json");
    }

    function addItems (data) {
        var $list = $(".timeline"), $item, item;
        list = [];
        for (var i = 0; i < data.length; i++) {
            $item = $(LIST_ITEM_TMPL);
            $item.attr("idx", i);
            $list.append($item);
            item = createItem(data[i].item, data[i].action);
            item.createTimeLine($item, $(".content-inner", $item));
            list.push({action: data[i].action, item: item});
        }
        $("li:first input:radio", $list).prop("checked", true);
    }

    function createItem (data, action) {
        var ItemClass;
        switch (action) {
            case "dining":
                ItemClass = DiningItem;
            break;
            case "sleep":
                ItemClass = SleepItem;
            break;
            case "shit":
                ItemClass = ShitItem;
            break;
            case "height":
                ItemClass = HeightItem;
            break;
            case "weight":
                ItemClass = WeightItem;
            break;
            case "memo":
                ItemClass = MemoItem;
            break;
        }
        return new ItemClass(data);
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
        var action = item.action;
        item.item.createForm($("#edit_dialog .weui_dialog_bd"), function(){
            $("#edit_dialog").show();
        });
    }

    function initEditDialog () {
        $("#edit_dialog").on("click", ".weui_btn_dialog.default", function(){
            $("#edit_dialog").hide();
        });

        $("#edit_dialog").on("click", ".weui_btn_dialog.primary", function(){
            var item = operatingItem.item,
                data = item.getFormData(),
                ajaxSettings = $.extend({
                    url: "action.php",
                    type: "POST",
                    data: data,
                    success: function(respond){
                        console.log(respond);
                        if (!!respond) {
                            $("#edit_dialog").hide();
                            updateData(data);
                            showToast();
                        }
                    }
                }, item.AJAX_SETTINGS);
            if (data instanceof FormData) {
                data.append("id", item.id);
            } else {
                data.id = item.id;
            }
            ajaxSettings.url = "./db/" + ajaxSettings.url + "?type=update&action=" + operatingItem.action;
            $.ajax(ajaxSettings);
        });
    }

    function initDeleteDialog () {
        $("#delete_dialog").on("click", ".weui_btn_dialog.default", function(){
            $("#delete_dialog").hide();
        });

        $("#delete_dialog").on("click", ".weui_btn_dialog.primary", function(){
            var item = operatingItem.item,
                ajaxUrl = item.AJAX_SETTINGS.url || "action.php";
            ajaxUrl = "./db/" + ajaxUrl + "?type=remove&action=" + operatingItem.action;
            $.post(ajaxUrl, {id: item.id}, function(respond){
                console.log(respond);
                if (!!respond) {
                    $("#delete_dialog").hide();
                    removeItem(operatingItem);
                    showToast();
                }
            });
        });
    }

    function updateData (data) {
        var newDate = data instanceof FormData ? data.get("date") : data.date;
        if (newDate !== operatingItem.item.date) {
            removeItem(operatingItem);
            return;
        }
        operatingItem.item.update(data);
        operatingItem.item.updateTimeline();
    }

    function removeItem (item) {
        var index = list.indexOf(item);
        $("li.event[idx="+ index +"]").remove();
    }
});