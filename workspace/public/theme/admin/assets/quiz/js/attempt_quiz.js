$(document).ready(function () {
    var records;
    var urlRecordList = siteUrl + "/admin/quiz/review/getListAtteptedQuiz";
    var recordsColumn = [
        {"data": "title"},
        {"data": "created_by"},
        {"data": "id"}
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
                        data = "<a href='/admin/quiz/review/" + row.id + "'>" + data + "</a>";
                    }
                    return data;
                }
            }]
    });
    records.columns([2]).visible(false);
});