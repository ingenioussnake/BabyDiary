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

    MemoItem.prototype.PIC_PREVIEW_CONTENT = "<a class='image_wrapper'><img class='image_content' /></a>";

    MemoItem.prototype.LIST_ITEM_TMPL = '<li class="memo_item">' +
                           '<div class="memo_item_container">' +
                               '<div class="avatarContainer">' +
                                   '<img class="avatar"></img>' +
                               '</div>' + 
                               '<div class="memo_item_content">' +
                                   '<div class="title"></div>' +
                                   '<div class="memo_container">'+
                                        '<div class="memo"></div>'+
                                        '<div class="image_container"></div>'+
                                   '</div>' +
                                   '<div class="footer">' +
                                        '<a class="icon edit" href="javascript:;"></a>' +
                                        '<a class="icon delete" href="javascript:;"></a>' +
                                        '<span class="date"></span>' +
                                   '</div>' + 
                               '</div>' + 
                       '</li>';

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
            memo: this.memo,
            pictures: this.pictures
        };
    };

    MemoItem.prototype.updateData = function (data) {
        this.id = data.id;
        this.date = data.date;
        this.time = data.time;
        this.title = data.title;
        this.memo = data.memo;
        this.pictures = data.pictures;
    };

    MemoItem.prototype.onFormLoaded = function () {
        var that = this,
            $form = $(".action_form"),
            $preview = $(".weui_uploader_files", $form),
            oFileReader, $img;
        this._pic_list = [];
        $("#pics", $form).on("change", function(e){
            for (var i = 0; i < this.files.length; i++) {
                that.preview_upload(this.files[i], $preview, function(path){
                    that._pic_list.push(path);
                    console.log(that._pic_list);
                });
            }
        });
        $(".weui_uploader_files", $form).on("click", "a.delete", function(e){
            var $img = $(e.target).parent(), data, index;
            if (!!$img.attr("pic_id")) {
                data = {id: $img.attr("pic_id")};
            } else {
                index = $("li[preview=true]", $preview).index($img);
                data = {path: that._pic_list[index]};
            }
            $.ajax({
                url: "./db/memo.php?type=removePic",
                type: "POST",
                data: data,
                success: function(respond){
                    if (!!respond) {
                        index !== undefined && that._pic_list.splice(index, 1);
                        $img.remove();
                    }
                }
            });
        });
    };

    MemoItem.prototype.preview_upload = function (file, $preview, cb) {
        var that = this;
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
            if (that.id) {
                oFormData.append("memo_id", that.id);
            }
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
            var i, $img, pictures = data.pictures; 
            for (i = 0; i < pictures.length; i++) {
                $img = $("<li pic_id="+pictures[i]+"><a class='delete' href='javascript:;'></a></li>");
                $img.addClass("weui_uploader_file");
                $img.css("background-image", "url(./db/memo.php?type=picture&id="+pictures[i]+")");
                $(".action_form .weui_uploader_files").append($img);
            }
            // $(".picture_cell").hide();
        }
    };

    MemoItem.prototype.createTimeLine = function ($item, $container) {
        BaseItem.prototype.createTimeLine.apply(this, arguments);
        this.initPreviewPopup();
        return this.$item;
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
        this.addPreview();
    };

    MemoItem.prototype.createListItem = function ($item) {
        BaseItem.prototype.createListItem.apply(this, arguments);
        this.initPreviewPopup();
        return this.$item;
    };

    MemoItem.prototype.updateListItem = function () {
        var $li = this.$item, that = this;
        $li.attr("memo_id", this.id);
        $("img.avatar", $li).attr("src", "./db/memo.php?type=avatar&baby="+this.baby);
        $(".title", $li).text(this.title);      
        $("div.memo", $li).text(this.memo);
        $("div.footer span.date", $li).text(new Date(this.date).toLocaleDateString());
        this.addPreview();
    };

    MemoItem.prototype.addPreview = function () {
        var pictures = this.pictures, $container = $("div.image_container", this.$item);
        if (pictures instanceof Array) {
            var length = pictures.length, i = 0, src, $item;
            for (;i<length;i++) {
                src = "./db/memo.php?type=picture&id="+pictures[i];
                $item = $(this.PIC_PREVIEW_CONTENT);
                $item.attr("href", src);
                $("img", $item).attr("src", src);
                $container.append($item);
            }
        }
    };

    MemoItem.prototype.initPreviewPopup = function () {
        $(".image_container", this.$item).magnificPopup({
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

    return MemoItem;
});