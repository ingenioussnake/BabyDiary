require.config({
    "paths": {
        "jquery": "libs/jquery-3.0.1.min"
    }
});

require(["jquery", "util"], function($, Util){
    var action = Util.getUrlParam("type") || "dining",
        module_name = action.substr(0, 1).toUpperCase() + action.substr(1) + "Item";
    require(["model/" + module_name], function(Item){
        var item = new Item();
        item.createForm($(".bd"));
        $(".page_title").html(item.FORM_TITLE);

        $("#submit").on("click", function(){
            var data = item.getFormData(),
                settings = $.extend({
                    url: "action.php",
                    type: "POST",
                    data: data,
                    success: function (respond) {
                        console.log(respond);
                        respond == 1 && showToast($("#toast"));
                    }
                }, item.AJAX_SETTINGS);
            settings.url = "./db/" + settings.url + "?type=insert&action=" + action;
            $.ajax(settings);
        });
    });
    $("#toast_container").load("./fragments/toast.html");
});