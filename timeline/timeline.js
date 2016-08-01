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
                "</li>";

    var list = [], operatingItem, birthday;

    $.get("./timeline.php", {type: "date"}, function(data){
        console.log(data);
        if (data.length > 0) {
            addDate(data);
            getList();
            $.get("../profile/profile.php", {type: "birthday"}, function(data){
                console.log(data);
                birthday = new Date(data);
                showBirthday();
            });
        }
    }, "json");

    

    $("#date_slt").on("change", function(){
        getList();
        showBirthday();
    });

    $(".timeline").on("click", ".edit", function(e){
        editItem(getItemIndex($(e.target)));
    });

    $(".timeline").on("click", ".delete", function(e){
        deleteItem(getItemIndex($(e.target)));
    });

    function showBirthday () {
        var date = new Date($("#date_slt").val());
        $(".dayth span").html((date - birthday) / (1000 * 60 * 60 * 24));
    }

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
                data.sort(function(a, b){
                    // for ios
                    return new Date(a.date.replace(/-/g, "/") + " " + (a.start || a.time)) - 
                            new Date(b.date.replace(/-/g, "/") + " " + (b.start || b.time));
                });
                addItems(data);
            }
        }, "json");
    }

    function addItems (data) {
        var $list = $(".timeline"), $item;
        for (var i = 0; i < data.length; i++) {
            $item = $(LIST_ITEM_TMPL);
            $item.attr("idx", i);
            $list.prepend($item);
            list.push({type: data[i].type, item: createItem(data[i], $item)});
        }
    }

    function createItem (data, $item) {
        var tl_item;
        switch (data.type) {
            case "dining":
                tl_item = new DiningItem(data, $item);
            break;
            case "sleep":
                tl_item = new SleepItem(data, $item);
            break;
            case "shit":
                tl_item = new ShitItem(data, $item);
            break;
            case "height":
                tl_item = new HeightItem(data, $item);
            break;
            case "weight":
                tl_item = new WeightItem(data, $item);
            break;
            case "memo":
                tl_item = new MemoItem(data, $item);
            break;
        }
        return tl_item;
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
            applyData(item.item.getData());
            $("#edit_dialog").show();
        });
    }

    $("#edit_dialog").on("click", ".weui_btn_dialog.default", function(){
        $("#edit_dialog").hide();
    });

    $("#edit_dialog").on("click", ".weui_btn_dialog.primary", function(){
        var data = loadData();
        var item = operatingItem.item;
        data.id = item.id;
        data.type = operatingItem.type;
        var url = item.getAjaxUrl ? item.getAjaxUrl() : "../actions/action.php";
        $.post(url, {type: "update", data: data}, function(respond){
            console.log(respond);
            if (!!respond) {
                $("#edit_dialog").hide();
                updateData(data);
                showToast($("#toast"));
            }
        });
    });

    $("#delete_dialog").on("click", ".weui_btn_dialog.default", function(){
        $("#delete_dialog").hide();
    });

    $("#delete_dialog").on("click", ".weui_btn_dialog.primary", function(){
        var item = operatingItem.item.getData();
        item.type = operatingItem.type;
        $.post("../actions/action.php", {type: "remove", data: item}, function(respond){
            console.log(respond);
            if (!!respond) {
                $("#delete_dialog").hide();
                removeItem(operatingItem);
                showToast($("#toast"));
            }
        });
    });

    function updateData (data) {
        if (data.date !== operatingItem.item.date) {
            removeItem(operatingItem);
            return;
        }
        operatingItem.item.update(data);
    }

    function removeItem (item) {
        var index = list.indexOf(item);
        $("li.event[idx="+ index +"]").remove();
        list.splice(index, 1);
    }
});