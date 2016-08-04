define(["jquery", "model/BaseItem", "util"], function($, BaseItem, Util){
    var HeightItem = function () {
        BaseItem.apply(this, arguments);
    }
    $.extend(HeightItem.prototype, BaseItem.prototype);

    HeightItem.prototype.FORM_FRAGMENT = "./fragments/height_fragment.html";

    HeightItem.prototype.FORM_TITLE = "宝宝长高咯";

    HeightItem.prototype.getData = function () {
        return {
            id: this.id,
            date: this.date,
            time: this.time,
            height: this.height
        };
    };

    HeightItem.prototype.updateData = function (data) {
        this.id = data.id;
        this.date = data.date;
        this.time = data.time;
        this.height = data.height;
    };

    HeightItem.prototype.setFormData = function (data) {
        var $form = $(".action_form");
        if (!data) {
            var curr = new Date();
            $("#date", $form).val(Util.getDate(curr));
            $("#time", $form).val(Util.getTime(curr));
        } else {
            $("#date", $form).val(data.date);
            $("#time", $form).val(data.time);
            $("#height", $form).val(data.height);
        }
    };

    HeightItem.prototype.getFormData = function () {
        var $form = $(".action_form");
        return { 
            date: $("#date", $form).val(),
            time: $("#time", $form).val(),
            height: $("#height", $form).val()
        };
    };

    HeightItem.prototype.updateTimeline = function () {
        var $item = this.$item;
        var $thumb = $(".thumb", $item);
        if (!$thumb.hasClass("type_height")) {
            $thumb.addClass("type_height");
        }
        $(".content-inner h3", $item).html("身高：" + this.height + "cm");
        $(".thumb span", $item).html(Util.removeNumberTail(this.time));
    };

    return HeightItem;
});