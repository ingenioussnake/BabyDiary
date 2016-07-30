var SleepItem = function (data, $item) {
    this.$item = $item;
    $(".content-inner", this.$item).append($(SleepItem.TIMELINE_CONTENT));
    this.update(data);
};

SleepItem.TIMELINE_CONTENT = "<div><p><span>开始<span class='from'></span></span></p>" +
                                "<p><span>结束<span class='to'></span></span></p></div>";

SleepItem.prototype.getData = function () {
    return {
        id: this.id,
        date: this.date,
        start: this.start,
        end: this.end
    };
};

SleepItem.prototype.update = function (data) {
    this.id = data.id;
    this.date = data.date;
    this.start = data.start;
    this.end = data.end;
    this._updateView();
}

SleepItem.prototype._updateView = function () {
    var $item = this.$item;
    $(".content-inner h3", $item).html("睡觉");
    var $thumb = $(".thumb", $item);
    if (!$thumb.hasClass("type_sleep")) {
        $thumb.addClass("type_sleep");
    }
    $(".thumb span", $item).html(removeNumberTail(this.start));
    $(".from", $item).html(removeNumberTail(this.start));
    $(".to", $item).html(removeNumberTail(this.end));
};