function getDate (date) {
    return date.getFullYear() + "-" + addNumberPadding(date.getMonth() + 1) + "-" + addNumberPadding(date.getDate());
}

function getTime (date) {
    return addNumberPadding(date.getHours()) + ":" + addNumberPadding(date.getMinutes());
}

function addNumberPadding (num) {
    return (num < 10 ? "0" : "") + num;
}

function removeNumberTail (num) {
    return num.substr(0, 5);
}

function getUrlParam(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
    var r = window.location.search.substr(1).match(reg);  //匹配目标参数
    if (r != null) return unescape(r[2]); return null; //返回参数值
}

function showToast ($toast) {
    $toast.show();
    setTimeout(function () {
        $toast.hide();
    }, 2000);
}