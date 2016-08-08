require.config({
    "paths": {
        "jquery": "./libs/jquery-3.0.1.min",
        "chart": "./libs/Chart.bundle"
    }
});

require(["jquery", "chart", "util"],
function($, Chart, Util){
    loadStatistic($(".weui_bar_item_on").attr("id"));

    $("a.weui_tabbar_item").on("click", function(){
        loadStatistic($(this).attr("id"));
    });

    function loadStatistic (type) {
        if (type === "stature") {
            loadStature();
        } else {
            loadAppetite();
        }
    }

    function loadStature () {
        console.log("loadStature");
        $(".weui_tab_bd").load("./statistic/stature.html");
    }

    function loadAppetite () {
        console.log("loadAppetite");
        $(".weui_tab_bd").load("./statistic/appetite.html");
    }
});