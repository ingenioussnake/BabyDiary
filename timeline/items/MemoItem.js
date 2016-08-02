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
    if (data instanceof FormData) {
        this.id = data.get("id");
        this.date = data.get("date");
        this.time = data.get("time");
        this.title = data.get("title");
        this.memo = data.get("memo");
    } else {
        this.id = data.id;
        this.date = data.date;
        this.time = data.time;
        this.title = data.title;
        this.memo = data.memo;
    }
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

MemoItem.prototype.getAjaxSettings = function () {
    return {
        url: "../actions/memo.php",
        contentType: false, // 告诉jQuery不要去处理发送的数据
        processData: false  // 告诉jQuery不要去设置Content-Type请求头
    };
};