define(["jquery", "model/BaseItem", "util"], function($, BaseItem, Util){
    var MemoItem = function () {
        BaseItem.apply(this, arguments);
        this._pic_list = [];
    }
    $.extend(MemoItem.prototype, BaseItem.prototype);

    MemoItem.prototype.FORM_FRAGMENT = "./fragments/memo_fragment.html";

    MemoItem.prototype.TIMELINE_CONTENT = "<div><p><span class='memo'></span></p>"+
                                    "<div class='weui_uploader_bd'>"+
                                        "<ul class='weui_uploader_files'></ul>"+
                                    "</div>"+
                                  "</div>";

    MemoItem.prototype.FORM_TITLE = "宝宝爱你咯";

    MemoItem.prototype.AJAX_SETTINGS = {
        url: "memo.php",
        contentType: false, // 告诉jQuery不要去处理发送的数据
        processData: false  // 告诉jQuery不要去设置Content-Type请求头
    };

    MemoItem.prototype.getData = function () {
        return {
            id: this.id,
            date: this.date,
            time: this.time,
            title: this.title,
            memo: this.memo
        };
    };

    MemoItem.prototype.updateData = function (data) {
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
    };

    MemoItem.prototype.onFormLoaded = function () {
        var that = this,
            $form = $(".action_form"),
            $preview = $(".weui_uploader_files", $form),
            oFileReader, $img;
        this._pic_list = [];
        $("#pics", $form).on("change", function(e){
            for (var i = 0; i < this.files.length; i++) {
                oFileReader = new FileReader();
                oFileReader.onload = function (e) {
                    $img = $("<li><a class='delete' href='javascript:;'></a></li>");
                    $img.addClass("weui_uploader_file");
                    $img.css("background-image", "url("+e.target.result+")");
                    $preview.append($img);
                };
                oFileReader.readAsDataURL(this.files[i]);
                that._pic_list.push(this.files[i]);
            }
        });
        $(".weui_uploader_files", $form).on("click", "a.delete", function(e){
            $img = $(e.target).parent();
            var index = $img.index();
            that._pic_list.splice(index, 1);
            $img.remove();
        });
    };

    MemoItem.prototype.getFormData = function () {
        var $form = $(".action_form"),
            data = new FormData();
        data.append("date", $("#date", $form).val());
        data.append("time", $("#time", $form).val());
        data.append("title", $("#title", $form).val());
        data.append("memo", $("#memo", $form).val());
        this._pic_list.forEach(function(file){
            data.append("picture[]", file);
        });
        return data;
    };

    MemoItem.prototype.setFormData = function (data) {
        var $form = $(".action_form");
        if (!data) {
            var curr = new Date();
            $("#date", $form).val(Util.getDate(curr));
            $("#time", $form).val(Util.getTime(curr));
        } else {
            $("#date", $form).val(data.date);
            $("#time", $form).val(data.time);
            $("#title").val(data.title);
            $("#memo").val(data.memo);
            $(".picture_cell").hide();
        }
    };

    MemoItem.prototype.updateTimeline = function () {
        var $item = this.$item;
        var $thumb = $(".thumb", $item);
        if (!$thumb.hasClass("type_memo")) {
            $thumb.addClass("type_memo");
        }
        $(".content-inner h3", $item).html(this.title);
        $(".content-inner .memo", $item).html(this.memo);
        $(".thumb span", $item).html(Util.removeNumberTail(this.time));
    };

    return MemoItem;
});