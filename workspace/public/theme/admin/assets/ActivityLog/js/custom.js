$(document).ready(function () {
    var records;
    var urlRecordList = siteUrl + "admin/activity-log/getList";
    var recordsColumn = [
        {"data": "user_id", "bSortable": false},
        {"data": "email_id"},
        {"data": "controller"},
        {"data": "module"},
        {"data": "description"},
        {"data": "action"},
        {"data": "ip_address", "bSortable": false},
        {"data": "created_at"}
    ];


    records = $('#records').DataTable({
        responsive: true,
        "order": [[7, "desc"]],
        "autoWidth": false,
        "processing": true,
        "serverSide": true,
        "stateSave": true,
        "ajax": urlRecordList + '/0',
        "columns": recordsColumn,
        dom: 'Bfrtip',
        buttons: [
            'csv', 'excel', 'pdf', 'pageLength'
        ],
    });

    $('#logduration').change(function () {
        var duration = $('#logduration option:selected').val();
        var newurlRecordList = urlRecordList + '/' + duration;
        records.ajax.url(newurlRecordList).load();
    })

});
