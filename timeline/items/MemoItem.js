var MemoItem = function (data, $item) {
    this.$item = $item;
    $(".content-inner", this.$item).append($(MemoItem.TIMELINE_CONTENT));
    this.update(data);
};

MemoItem.TIMELINE_CONTENT = "<div><p><span class='memo'></span></p>"+
                                "<div class='weui_uploader_bd'>"+
                                    "<ul class='weui_uploader_files'></ul>"+
                                "</div>"+
                              "</div>";


MemoItem.prototype.getData = function () {
    return {
        id: this.id,
        date: this.date,
        time: this.time,
        title: this.title,
        memo: this.memo
    };
};

MemoItem.prototype.update = function (data) {
    this.id = data.id;
    this.date = data.date;
    this.time = data.time;
    this.title = data.title;
    this.memo = data.memo;
    this._updateView();
};

MemoItem.prototype._updateView = function () {
    var $item = this.$item;
    var $thumb = $(".thumb", $item);
    if (!$thumb.hasClass("type_memo")) {
        $thumb.addClass("type_memo");
    }
    $(".content-inner h3", $item).html(this.title);
    $(".content-inner .memo", $item).html(this.memo);
    $(".thumb span", $item).html(removeNumberTail(this.time));
};