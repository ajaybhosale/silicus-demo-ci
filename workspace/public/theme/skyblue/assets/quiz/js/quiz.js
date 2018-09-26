function delQuiz($quizId) {
    BootstrapDialog.confirm({
        title: 'CONFIRM',
        message: 'All the questions related to this quiz will be deleted. Are you sure you want to delete?',
        type: BootstrapDialog.TYPE_DANGER, // <-- Default value is BootstrapDialog.TYPE_PRIMARY
        closable: true, // <-- Default value is false
        draggable: true, // <-- Default value is false
        btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
        btnOKLabel: 'Ok', // <-- Default value is 'OK',
        btnOKClass: '', // <-- If you didn't specify it, dialog type will be used,
        callback: function (result) {
            // result will be true if button was click, while it will be false if users close the dialog directly.
            if (result) {
                window.location = '/admin/quiz/list/delete/' + $quizId;
            } else {
                return false;
            }
        }
    });
}
$(document).ready(function () {
    $('.panel-body').hide();
    $('#cancel').click(function () {
        $('.panel-body').toggle(1600);
    })
    $('#addQuiz').click(function () {
        $('.panel-body').toggle(1600);
    });
    var records;
    var urlRecordList = siteUrl + "/admin/quiz/category/getQuizList";
    var recordsColumn = [
        {"data": "name"},
        {"data": "category"},
        {"data": "created_by"},
        {"data": "time"},
        {"data": "status", "bSortable": false},
        {"data": "edit", "bSortable": false},
        {"data": "delete", "bSortable": false}
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
                "targets": [0, 5, 6], // Column which need to update. Index start from zero
                "render": function (data, type, row, meta) {
                    if (meta.col == 0) {
                        data = "<a href='/admin/quiz/list/questions/" + row.edit + "'>" + data + "</a>";
                    }
                    if (meta.col == 5) {
                        data = "<a class='glyphicon glyphicon-pencil' href='/admin/quiz/list/edit/" + row.edit + "'>Edit</a>";
                    }
                    if (meta.col == 6) {
                        data = "<a class='glyphicon glyphicon-trash' onclick='delQuiz(" + row.delete + ")' href='javascript:void(0)'>Delete</a>";
                    }
                    return data;
                }
            }]
    });


});