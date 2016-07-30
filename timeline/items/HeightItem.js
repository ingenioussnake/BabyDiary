var HeightItem = function (data, $item) {
    this.$item = $item;
    this.update(data);
};

HeightItem.prototype.getData = function () {
    return {
        id: this.id,
        date: this.date,
        time: this.time,
        height: this.height
    };
};

HeightItem.prototype.update = function (data) {
    this.id = data.id;
    this.date = data.date;
    this.time = data.time;
    this.height = data.height;
    this._updateView();
};

HeightItem.prototype._updateView = function () {
    var $item = this.$item;
    var $thumb = $(".thumb", $item);
    if (!$thumb.hasClass("type_height")) {
        $thumb.addClass("type_height");
    }
    $(".content-inner h3", $item).html("身高：" + this.height + "cm");
    $(".thumb span", $item).html(removeNumberTail(this.time));
};