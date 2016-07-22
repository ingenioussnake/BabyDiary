function getDate (date) {
    return date.getFullYear() + "-" + addNumberPadding(date.getMonth() + 1) + "-" + addNumberPadding(date.getDate());
}

function getTime (date) {
    return addNumberPadding(date.getHours()) + ":" + addNumberPadding(date.getMinutes());
}

function getDateTime (date) {
    return getDate(date) + "T" + getTime(date);
}

function addNumberPadding (num) {
    return (num < 10 ? "0" : "") + num;
}

function addEndTime () {
    var $end = $("#end");
    !$end.val() && $end.val(getTime(new Date()));
}

function showToast () {
    $('#toast').show();
    setTimeout(function () {
        $('#toast').hide();
    }, 5000);
}