define(function(){

    var Util = {};

    Util.getDate = function (date) {
        return date.getFullYear() + "-" + this.addNumberPadding(date.getMonth() + 1) + "-" + this.addNumberPadding(date.getDate());
    };

    Util.getTime = function (date) {
        return this.addNumberPadding(date.getHours()) + ":" + this.addNumberPadding(date.getMinutes());
    };

    Util.addNumberPadding = function (num) {
        return (num < 10 ? "0" : "") + num;
    };

    Util.removeNumberTail = function (num) {
        return num.substr(0, 5);
    };

    Util.getUrlParam = function (name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
        var r = window.location.search.substr(1).match(reg);  //匹配目标参数
        if (r != null) return unescape(r[2]); return null; //返回参数值
    };

    return Util;

});