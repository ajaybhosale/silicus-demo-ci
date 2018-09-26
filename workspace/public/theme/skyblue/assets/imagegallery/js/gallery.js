function delGallery(galleryId) {
    BootstrapDialog.confirm({
        title: 'CONFIRM',
        message: 'Are you sure you want to delete this gallery?',
        type: BootstrapDialog.TYPE_DANGER, // <-- Default value is BootstrapDialog.TYPE_PRIMARY
        closable: true, // <-- Default value is false
        draggable: true, // <-- Default value is false
        btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
        btnOKLabel: 'Ok', // <-- Default value is 'OK',
        btnOKClass: '', // <-- If you didn't specify it, dialog type will be used,
        callback: function (result) {
            // result will be true if button was click, while it will be false if users close the dialog directly.
            if (result) {
                window.location = "/admin/image-gallery/deleteGallery/" + galleryId;
            } else {
                return false;
            }
        }
    });
}
$(document).ready(function () {
    var records;
    var urlRecordList = siteUrl + "admin/image-gallery/getGalleryList";
    var recordsColumn = [
        {"data": "gallery_name"},
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
                "targets": [0, 1, 2], // Column which need to update. Index start from zero
                "render": function (data, type, row, meta) {
                    if (meta.col == 0) {
                        data = "<a href='/admin/image-gallery/list/" + row.delete + "'>" + data + "</a>";
                    }
                    if (meta.col == 1) {
                        data = "<a class='glyphicon glyphicon-pencil' href='/admin/image-gallery/edit/" + row.edit + "'>Edit</a>";
                    }
                    if (meta.col == 2) {
                        data = "<a class='glyphicon glyphicon-trash' onclick='delGallery(" + row.delete + ")' href='javascript:void(0)'>Delete</a>";
                    }
                    return data;
                }
            }]
    });
});