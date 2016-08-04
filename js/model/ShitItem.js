define(["jquery", "model/BaseItem", "util"], function($, BaseItem, Util){
    var ShitItem = function () {
        BaseItem.apply(this, arguments);
    }
    $.extend(ShitItem.prototype, BaseItem.prototype);

    ShitItem.prototype.FORM_FRAGMENT = "./fragments/shit_fragment.html";

    ShitItem.prototype.FORM_TITLE = "宝宝拉臭咯";

    ShitItem.prototype.getData = function () {
        return {
            id: this.id,
            date: this.date,
            time: this.time
        };
    };

    ShitItem.prototype.updateData = function (data) {
        this.id = data.id;
        this.date = data.date;
        this.time = data.time;
    };

    ShitItem.prototype.getFormData = function () {
        var $form = $(".action_form");
        return { 
            date: $("#date", $form).val(),
            time: $("#time", $form).val()
        };
    };

    ShitItem.prototype.setFormData = function (data) {
        var $form = $(".action_form");
        if (!data) {
            var curr = new Date();
            $("#date", $form).val(Util.getDate(curr));
            $("#time", $form).val(Util.getTime(curr));
        } else {
            $("#date", $form).val(data.date);
            $("#time", $form).val(data.time);
        }
    };

    ShitItem.prototype.updateTimeline = function () {
        var $item = this.$item;
        var $thumb = $(".thumb", $item);
        if (!$thumb.hasClass("type_shit")) {
            $thumb.addClass("type_shit");
        }
        $(".content-inner h3", $item).html("好臭");
        $(".thumb span", $item).html(Util.removeNumberTail(this.time));
    };

    return ShitItem;
});