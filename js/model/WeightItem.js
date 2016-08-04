define(["jquery", "model/BaseItem", "util"], function($, BaseItem, Util){
    var WeightItem = function () {
        BaseItem.apply(this, arguments);
    }
    $.extend(WeightItem.prototype, BaseItem.prototype);

    WeightItem.prototype.FORM_FRAGMENT = "./fragments/weight_fragment.html";

    WeightItem.prototype.FORM_TITLE = "宝宝长肉咯";

    WeightItem.prototype.getData = function () {
        return {
            id: this.id,
            date: this.date,
            time: this.time,
            weight: this.weight
        };
    };

    WeightItem.prototype.updateData = function (data) {
        this.id = data.id;
        this.date = data.date;
        this.time = data.time;
        this.weight = data.weight;
    };

    WeightItem.prototype.getFormData = function () {
        var $form = $(".action_form");
        return { 
            date: $("#date", $form).val(),
            time: $("#time", $form).val(),
            weight: $("#weight", $form).val()
        };
    };

    WeightItem.prototype.setFormData = function (data) {
        var $form = $(".action_form");
        if (!data) {
            var curr = new Date();
            $("#date", $form).val(Util.getDate(curr));
            $("#time", $form).val(Util.getTime(curr));
        } else {
            $("#date", $form).val(data.date);
            $("#time", $form).val(data.time);
            $("#weight", $form).val(data.weight);
        }
    };

    WeightItem.prototype.updateTimeline = function () {
        var $item = this.$item;
        var $thumb = $(".thumb", $item);
        if (!$thumb.hasClass("type_weight")) {
            $thumb.addClass("type_weight");
        }
        $(".content-inner h3", $item).html("体重：" + this.weight + "kg");
        $(".thumb span", $item).html(Util.removeNumberTail(this.time));
    };

    return WeightItem;
});