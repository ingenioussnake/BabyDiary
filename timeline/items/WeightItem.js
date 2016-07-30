var WeightItem = function (data, $item) {
    this.$item = $item;
    this.update(data);
};

WeightItem.prototype.getData = function () {
    return {
        id: this.id,
        date: this.date,
        time: this.time,
        weight: this.weight
    };
};

WeightItem.prototype.update = function (data) {
    this.id = data.id;
    this.date = data.date;
    this.time = data.time;
    this.weight = data.weight;
    this._updateView();
};

WeightItem.prototype._updateView = function () {
    var $item = this.$item;
    var $thumb = $(".thumb", $item);
    if (!$thumb.hasClass("type_weight")) {
        $thumb.addClass("type_weight");
    }
    $(".content-inner h3", $item).html("体重：" + this.weight + "kg");
    $(".thumb span", $item).html(removeNumberTail(this.time));
};