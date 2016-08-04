define(["jquery", "model/BaseItem", "util"], function($, BaseItem, Util){
    var SleepItem = function () {
        BaseItem.apply(this, arguments);
    }
    $.extend(SleepItem.prototype, BaseItem.prototype);

    SleepItem.prototype.FORM_FRAGMENT = "./fragments/sleep_fragment.html";

    SleepItem.prototype.TIMELINE_CONTENT = "<div><p><span>开始<span class='from'></span></span></p>" +
                                    "<p><span>结束<span class='to'></span></span></p></div>";

    SleepItem.prototype.FORM_TITLE = "宝宝睡觉咯";

    SleepItem.prototype.getData = function () {
        return {
            id: this.id,
            date: this.date,
            start: this.start,
            end: this.end
        };
    };

    SleepItem.prototype.updateData = function (data) {
        this.id = data.id;
        this.date = data.date;
        this.start = data.start;
        this.end = data.end;
    };

    SleepItem.prototype.getFormData = function () {
        var $form = $(".action_form"),
            $end = $("#end", $form);
        !$end.val() && $end.val(Util.getTime(new Date()));
        return { 
            date: $("#date", $form).val(),
            start: $("#start", $form).val(),
            end: $("#end", $form).val()
        };
    };

    SleepItem.prototype.setFormData = function (data) {
        var $form = $(".action_form");
        if (!data) {
            var curr = new Date();
            $("#date", $form).val(Util.getDate(curr));
            $("#start", $form).val(Util.getTime(curr));
        } else {
            $("#date", $form).val(data.date);
            $("#start", $form).val(data.start);
            $("#end", $form).val(data.end);
        }
    };

    SleepItem.prototype.updateTimeline = function () {
        var $item = this.$item;
        $(".content-inner h3", $item).html("睡觉");
        var $thumb = $(".thumb", $item);
        if (!$thumb.hasClass("type_sleep")) {
            $thumb.addClass("type_sleep");
        }
        $(".thumb span", $item).html(Util.removeNumberTail(this.start));
        $(".from", $item).html(Util.removeNumberTail(this.start));
        $(".to", $item).html(Util.removeNumberTail(this.end));
    };

    return SleepItem;
});