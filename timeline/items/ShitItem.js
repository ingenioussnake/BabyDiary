var ShitItem = function (data, $item) {
    this.$item = $item;
    this.update(data);
};

ShitItem.prototype.getData = function () {
    return {
        id: this.id,
        date: this.date,
        time: this.time
    };
};

ShitItem.prototype.update = function (data) {
    this.id = data.id;
    this.date = data.date;
    this.time = data.time;
    this._updateView();
};

ShitItem.prototype._updateView = function () {
    var $item = this.$item;
    var $thumb = $(".thumb", $item);
    if (!$thumb.hasClass("type_shit")) {
        $thumb.addClass("type_shit");
    }
    $(".content-inner h3", $item).html("好臭");
    $(".thumb span", $item).html(removeNumberTail(this.time));
};