define(['jquery'], function($){
    var Item = function (data) {
        this._empty = true;
        this.baby = 1;
        !!data && this.update(data);
    };

    Item.prototype.FORM_FRAGMENT = "";
    Item.prototype.FORM_TITLE = "";
    Item.prototype.TIMELINE_CONTENT = "";
    Item.prototype.LIST_ITEM_TMPL = "";
    Item.prototype.AJAX_SETTINGS = {};

    Item.prototype.getData = function(){};
    Item.prototype.updateData = function(data){};
    Item.prototype.onFormLoaded = function(){};
    Item.prototype.getFormData = function(){};
    Item.prototype.setFormData = function(){};
    Item.prototype.updateTimeline = function(){};
    Item.prototype.updateListItem = function () {};

    Item.prototype.update = function(data){
        this.updateData(data);
        this._empty = false;
    };

    Item.prototype.createForm = function ($container, cb) {
        var that = this;
        $container.load(this.FORM_FRAGMENT, function(respond){
            if (!respond) return;
            that.onFormLoaded();
            that.setFormData(that.isEmpty() ? undefined : that.getData());
            !!cb && cb();
        });
    };

    Item.prototype.createTimeLine = function ($item, $container) {
        this.$item = $item;
        !!this.TIMELINE_CONTENT && $container.append($(this.TIMELINE_CONTENT));
        this.updateTimeline();
        return this.$item;
    };

    Item.prototype.createListItem = function ($container) {
        if (!!this.LIST_ITEM_TMPL) {
            this.$item = $(this.LIST_ITEM_TMPL);
            $container.append(this.$item);
            this.updateListItem();
        }
        return this.$item;
    };

    Item.prototype.isEmpty = function () {
        return this._empty;
    };

    return Item;
});