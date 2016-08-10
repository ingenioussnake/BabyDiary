require.config({
    "paths": {
        "jquery": "./libs/jquery-3.0.1.min",
        "jquery.segment": "./libs/segment",
        "chart": "./libs/Chart.min"
    },
    "shim": {
        "jquery.segment": ["jquery"]
    }
});

require(["chart", "util", "jquery", "jquery.segment"],
function(Chart, Util, $){
    loadStatistic($(".statistic_selecter > .weui_bar_item_on").attr("id"));
    $("#toast_container").load("./fragments/toast.html", function(){
        $(".weui_toast_content").html("没有更多数据了");
    });

    $(".statistic_selecter > a.weui_tabbar_item").on("click", function(){
        $(".statistic_selecter > a.weui_tabbar_item").removeClass("weui_bar_item_on");
        $(this).addClass("weui_bar_item_on");
        loadStatistic($(this).attr("id"));
    });

    function loadStatistic (type) {
        $(".weui_tab_bd.statistic_container").load("./statistic/"+type+"_container.html");
    }
});