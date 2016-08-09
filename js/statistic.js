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
    loadStatistic($("#container > .weui_bar_item_on").attr("id"));

    $("#container > a.weui_tabbar_item").on("click", function(){
        loadStatistic($(this).attr("id"));
    });

    function loadStatistic (type) {
        $(".weui_tab_bd.statistic_container").load("./statistic/"+type+"_container.html");
    }
});