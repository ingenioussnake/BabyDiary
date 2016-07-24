function gatherData (action) {
    var data = { date: $("#date").val() };
    if (action === "dining" || action === "sleep") {
        addEndTime();
        data.start = $("#start").val();
        data.end = $("#end").val();
        if (action === "dining") {
            data.mm = $("#mother").prop("checked") ? 1 : 0
        }
    } else {
        data.time = $("#time").val();
        if (action === "height") {
            data.height = $("#height").val();
        } else if (action === "weight") {
            data.weight = $("#weight").val();
        }
    }
    return data;
}

function addEndTime () {
    var $end = $("#end");
    !$end.val() && $end.val(getTime(new Date()));
}

function getDate (date) {
    return date.getFullYear() + "-" + addNumberPadding(date.getMonth() + 1) + "-" + addNumberPadding(date.getDate());
}

function getTime (date) {
    return addNumberPadding(date.getHours()) + ":" + addNumberPadding(date.getMinutes());
}

function addNumberPadding (num) {
    return (num < 10 ? "0" : "") + num;
}

function getUrlParam(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
    var r = window.location.search.substr(1).match(reg);  //匹配目标参数
    if (r != null) return unescape(r[2]); return null; //返回参数值
}

function showToast () {
    $('#toast').show();
    setTimeout(function () {
        $('#toast').hide();
    }, 2000);
}