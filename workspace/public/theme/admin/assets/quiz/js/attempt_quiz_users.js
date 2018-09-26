$(document).ready(function () {
    var records;
    var urlRecordList = siteUrl + "/admin/quiz/review/getUsersList/" + $('#records').attr('quiz-id');
    var recordsColumn = [
        {"data": "username"},
        {"data": "time", "bSortable": false},
        {"data": "created_at"},
        {"data": "user_id"},
        {"data": "unique_id"}
    ];
    records = $('#records').DataTable({
        responsive: true,
        "order": [[0, "desc"]],
        "autoWidth": false,
        "processing": true,
        "serverSide": true,
        "stateSave": true,
        "ajax": urlRecordList,
        "columns": recordsColumn,
        "columnDefs": [{
                "targets": [0], // Column which need to update. Index start from zero
                "render": function (data, type, row, meta) {
                    if (meta.col == 0) {
                        data = "<a href='/admin/quiz/review/" + $('#records').attr('quiz-id') + '/' + row.user_id + '/' + row.unique_id + "'>" + data + "</a>";
                    }
                    return data;
                }
            }]
    });
    records.columns([3, 4]).visible(false);
});