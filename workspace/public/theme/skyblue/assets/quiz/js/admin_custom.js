var nonSorting = [];
var recordGridColumn = 0;
$(document).ready(function () {
    recordGridColumn = $('#recordGrid .ysort').index();
    var nsortable = $('table tr.headings th.nsort');
    $.each(nsortable, function () {
        nonSorting.push($(this).index());
    });
});