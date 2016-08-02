var DiningItem = function (data, $item) {
    this.$item = $item;
    $(".content-inner", this.$item).append($(DiningItem.TIMELINE_CONTENT));
    this.update(data);
};

DiningItem.TIMELINE_CONTENT = "<div><p><h4></h4></p><p><span>开始<span class='from'></span></span></p>" +
                                "<p><span>结束<span class='to'></span></span></p><p><span class='appetite'></span></p>"+
                                "<p><span class='comment'></span></p></div>";

DiningItem.DINING_TYPE = {
    mm: "母乳", fm: "配方", mx: "混合"
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

DiningItem.prototype.update = function (data) {
    this.id = data.id;
    this.date = data.date;
    this.start = data.start;
    this.end = data.end;
    this.food = data.food;
    this.appetite = data.appetite;
    this.comment = data.comment;
    this._updateView();
};

DiningItem.prototype._updateView = function () {
    var $item = this.$item;
    $(".content-inner h3", $item).html("吃饭");
    var $thumb = $(".thumb", $item);
    $thumb.removeClass();
    $thumb.addClass("thumb type_" + this.food);
    $(".thumb span", $item).html(removeNumberTail(this.start));
    $("h4", $item).html(DiningItem.DINING_TYPE[this.food]);
    if (this.food !== "mm") {
        $(".appetite", $item).removeAttr("style");
        $(".appetite", $item).html(this.appetite + "勺");
    } else {
        $(".appetite", $item).css("display", "none");
    }
    $(".comment", $item).html(this.comment);
    $(".from", $item).html(removeNumberTail(this.start));
    $(".to", $item).html(removeNumberTail(this.end));
};

DiningItem.prototype.getAjaxSettings = function () {
    return {url: "../actions/dining.php"};
};