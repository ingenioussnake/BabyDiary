define(["jquery", "model/BaseItem", "util"], function($, BaseItem, Util){
    var DiningItem = function () {
        BaseItem.apply(this, arguments);
    }
    $.extend(DiningItem.prototype, BaseItem.prototype);

    DiningItem.DINING_TYPE = {
        mm: "母乳", fm: "配方", mx: "混合"
    };

    DiningItem.prototype.FORM_FRAGMENT = "./fragments/dining_fragment.html";

    DiningItem.prototype.TIMELINE_CONTENT = "<div><p><h4></h4></p><p><span>开始<span class='from'></span></span></p>" +
                                    "<p><span>结束<span class='to'></span></span></p><p><span class='appetite'></span></p>"+
                                    "<p><span class='comment'></span></p></div>";

    DiningItem.prototype.FORM_TITLE = "宝宝吃饭咯";

    DiningItem.prototype.AJAX_SETTINGS = {
        url: "dining.php"
    };

    DiningItem.prototype.getData = function () {
        return {
            id: this.id,
            date: this.date,
            start: this.start,
            end: this.end,
            food: this.food,
            appetite: this.appetite,
            comment: this.comment
        };
    };

    DiningItem.prototype.updateData = function (data) {
        this.id = data.id;
        this.date = data.date;
        this.start = data.start;
        this.end = data.end;
        this.food = data.food;
        this.appetite = data.appetite;
        this.comment = data.comment;
    };

    DiningItem.prototype.onFormLoaded = function () {
        $(".action_form input:radio").on("change", function(){
            if ($("input[name=food]:radio:checked").val() !== "mm") {
                $(".appetite_cell").removeAttr("style");
            } else {
                $(".appetite_cell").css("display", "none");
                $(".appetite_cell input").val(0);
            }
        });
    };

    DiningItem.prototype.getFormData = function () {
        var $form = $(".action_form"),
            $end = $("#end", $form);
        !$end.val() && $end.val(Util.getTime(new Date()));
        return {
            date: $("#date", $form).val(),
            start: $("#start", $form).val(),
            end: $("#end", $form).val(),
            food: $("input[name=food]:radio:checked").val(),
            appetite: $("#appetite").val() || 0,
            comment: $("#comment").val()
        };
    };

    DiningItem.prototype.setFormData = function (data) {
        var $form = $(".action_form");
        if (!data) {
            var curr = new Date();
            $("#date", $form).val(Util.getDate(curr));
            $("#start", $form).val(Util.getTime(curr));
        } else {
            $("#date", $form).val(data.date);
            $("#start", $form).val(data.start);
            $("#end", $form).val(data.end);
            $("input[value=" + data.food + "]:radio").prop("checked", true);
            if (data.food !== "mm") {
                $(".appetite_cell").removeAttr("style");
            }
            $("#appetite").val(data.appetite);
            $("#comment").val(data.comment);
        }
    };

    DiningItem.prototype.updateTimeline = function () {
        var $item = this.$item;
        $(".content-inner h3", $item).html("吃饭");
        var $thumb = $(".thumb", $item);
        $thumb.removeClass();
        $thumb.addClass("thumb type_" + this.food);
        $(".thumb span", $item).html(Util.removeNumberTail(this.start));
        $("h4", $item).html(DiningItem.DINING_TYPE[this.food]);
        if (this.food !== "mm") {
            $(".appetite", $item).removeAttr("style");
            $(".appetite", $item).html(this.appetite + "勺");
        } else {
            $(".appetite", $item).css("display", "none");
        }
        $(".comment", $item).html(this.comment);
        $(".from", $item).html(Util.removeNumberTail(this.start));
        $(".to", $item).html(Util.removeNumberTail(this.end));
    };

    return DiningItem;
});