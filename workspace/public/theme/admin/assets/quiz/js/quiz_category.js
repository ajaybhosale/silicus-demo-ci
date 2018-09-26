function delCategory(categoryId) {
    BootstrapDialog.confirm({
        title: 'CONFIRM',
        message: 'All the quiz related to this category will be deleted. Are you sure you want to delete?',
        type: BootstrapDialog.TYPE_DANGER, // <-- Default value is BootstrapDialog.TYPE_PRIMARY
        closable: true, // <-- Default value is false
        draggable: true, // <-- Default value is false
        btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
        btnOKLabel: 'Ok', // <-- Default value is 'OK',
        btnOKClass: '', // <-- If you didn't specify it, dialog type will be used,
        callback: function (result) {
            // result will be true if button was click, while it will be false if users close the dialog directly.
            if (result) {
                window.location = '/admin/quiz/category/delete/' + categoryId;
            } else {
                return false;
            }
        }
    });
}
$(document).ready(function () {
    var records;
    var urlRecordList = siteUrl + "/admin/quiz/category/getCategoryList";
    var recordsColumn = [
        {"data": "title"},
        {"data": "created_by"},
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
                "targets": [3, 4], // Column which need to update. Index start from zero
                "render": function (data, type, row, meta) {
                    if (meta.col == 3) {
                        data = "<a class='glyphicon glyphicon-pencil' href='/admin/quiz/category/edit/" + row.edit + "'>Edit</a>";
                    }
                    if (meta.col == 4) {
                        data = "<a class='glyphicon glyphicon-trash' onclick='delCategory(" + row.delete + ")' href='javascript:void(0)'>Delete</a>";
                    }
                    return data;
                }
            }]
    });
});