define(["jquery", "model/BaseItem", "lrz", "util"], function($, BaseItem, lrz, Util){
    var MemoItem = function () {
        BaseItem.apply(this, arguments);
        this._pic_list = [];
    }
    $.extend(MemoItem.prototype, BaseItem.prototype);

    MemoItem.prototype.FORM_FRAGMENT = "./fragments/memo_fragment.html";

    MemoItem.prototype.TIMELINE_CONTENT = "<div><p><span class='memo'></span></p>"+
                                    "<div class='image_container'></div>"+
                                  "</div>";

    MemoItem.prototype.FORM_TITLE = "宝宝爱你咯";

    MemoItem.prototype.AJAX_SETTINGS = {
        url: "memo.php"
    };

    MemoItem.PICTURE_MAX_SIZE = 1.5 * 1024 * 1024;

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
        this.id = data.id;
        this.date = data.date;
        this.time = data.time;
        this.title = data.title;
        this.memo = data.memo;
    };

    MemoItem.prototype.onFormLoaded = function () {
        var that = this,
            $form = $(".action_form"),
            $preview = $(".weui_uploader_files", $form),
            oFileReader, $img;
        this._pic_list = [];
        $("#pics", $form).on("change", function(e){
            for (var i = 0; i < this.files.length; i++) {
                preview_upload(this.files[i], $preview, function(path){
                    that._pic_list.push(path);
                    console.log(that._pic_list);
                });
            }
        });
        $(".weui_uploader_files", $form).on("click", "a.delete", function(e){
            var $img = $(e.target).parent(),
                index = $("li[preview=true]", $preview).index($img);
            $.ajax({
                url: "./db/memo.php?type=removePic",
                type: "POST",
                data: {path: that._pic_list[index]},
                success: function(respond){
                    if (!!respond) {
                        that._pic_list.splice(index, 1);
                        $img.remove();
                    }
                }
            });
        });
    };

    function preview_upload (file, $preview, cb) {
        lrz(file, {
            width: 800,
            fieldName: "picture",
            quality: file.size < MemoItem.PICTURE_MAX_SIZE ? 1 : 0.8
        }).then(function(rst){
            var $img = $("<li preview='true'><a class='delete' href='javascript:;'></a></li>");
            $img.addClass("weui_uploader_file");
            $img.css("background-image", "url("+rst.base64+")");
            $preview.append($img);
            return rst;
        }).then(function(rst){
            var oFormData = rst.formData;
            oFormData.append("length", rst.fileLen);
            $.ajax({
                url: "./db/memo.php?type=upload",
                type: "POST",
                data: oFormData,
                contentType: false, // 告诉jQuery不要去处理发送的数据
                processData: false,  // 告诉jQuery不要去设置Content-Type请求头
                success: function (respond, status) {
                    if (status === "success") {
                        cb(respond);
                    }
                }
            });
        });
    }

    MemoItem.prototype.getFormData = function () {
        var $form = $(".action_form");
        return {
            "date": $("#date", $form).val(),
            "time": $("#time", $form).val(),
            "title": $("#title", $form).val(),
            "memo": $("#memo", $form).val(),
            "pictures": this._pic_list
        };
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

    MemoItem.prototype.createTimeLine = function ($item, $container) {
        BaseItem.prototype.createTimeLine.apply(this, arguments);
        $(".image_container", $container).magnificPopup({
            delegate: 'a',
            type: 'image',
            mainClass: 'mfp-with-zoom mfp-img-mobile',
            image: {
                verticalFit: true
            },
            gallery: {
                enabled: true,
                navigateByImgClick: false
            },
            zoom: {
                enabled: true,
                duration: 200, // don't foget to change the duration also in CSS
            }
        });
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
        $.get("./db/memo.php", {type: "pic_count", id: this.id}, function (data) {
            var length = data.length, i = 0, src;
            if (data instanceof Array && length > 0) {
                for (;i<length;i++) {
                    src = "./db/memo.php?type=picture&id="+data[i];
                    $("div.image_container", $item).append("<a class='image_content' href='"+src+"'><img class='imageContent' src='"+src+"' /></a>");
                }
            }
        }, "json");
    };

    return MemoItem;
});