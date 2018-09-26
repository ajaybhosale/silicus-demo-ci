$(document).ready(function () {
    var records;
    var urlRecordList = siteUrl + "admin/users/getList";
    var urlDelete = siteUrl + "admin/users/delete";
    var urlRecordView = siteUrl + "admin/users/details";
    var urlRecordEdit = siteUrl + "admin/users/update";
    var recordsColumn = [
        {"data": "name"},
        {"data": "email"},
        {"data": "createdAt"},
        {"data": "status"},
        {"data": "view", "bSortable": false},
        {"data": "edit", "bSortable": false},
        {"data": "delete", "bSortable": false}
    ];

    $.fn.displayRecord = function (recordId) {
        BootstrapDialog.show({
            title: 'User Information',
            message: $('<div></div>').load(urlRecordView + '/' + recordId)
        });
    }

    $.fn.editRecord = function (recordId) {
        BootstrapDialog.show({
            title: 'Edit user Information',
            message: $('<div></div>').load(urlRecordEdit + '/' + recordId)
        });

    }

    $.fn.deleteRecord = function (recordId, obj) {
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: urlDelete,
            type: "POST",
            data: "id=" + recordId,
            success: function () {
                $.fn.removeRow(obj);
            }
        });
    }

    $.fn.updateRecords = function () {

        $('#records tbody').on('click', '.clsView', function () {
            $.fn.displayRecord($(this).attr('vid'));
        });

        $('#records tbody').on('click', '.clsEdit', function () {
            $.fn.editRecord($(this).attr('eid'));
        });

        $('#records tbody').on('click', '.clsDelete', function () {

            var recordId = $(this).attr('did');

            if (recordId == 1) {
                BootstrapDialog.show({
                    title: 'WARNING',
                    type: BootstrapDialog.TYPE_DANGER,
                    message: 'You cannot delete this record.'
                });
                return;
            }

            BootstrapDialog.confirm({
                title: 'WARNING',
                message: 'Are you sure you want to delete this record?',
                type: BootstrapDialog.TYPE_DANGER,
                closable: true,
                draggable: true,
                btnCancelLabel: 'Cancel',
                btnOKLabel: 'Ok',
                callback: function (result) {
                    if (result) {
                        $.fn.deleteRecord(recordId, $(this).parents('tr'));
                        BootstrapDialog.show({message: 'Record deleted successfully!'});
                    }
                }
            });
        });
    }

    $.fn.removeRow = function (recordIndex) {
        // Get previous pagination number
        var previousPagination = parseInt($(".paginate_button.current").data("dt-idx")) - 1;

        // Decide to redraw or not based on the presence of `.deleteBtn` elements.
        var doIdraw = false;
        if ($(".clsDelete").length == 1) {
            doIdraw = true;
        }
        // If the page redraws and a previous pagination existed (except the first)
        if (previousPagination > 1 && doIdraw) {
            var previousPage = $(document).find("[data-dt-idx='" + (previousPagination) + "']").click();
        }
        else {
            records.row(recordIndex).remove().draw(doIdraw);
        }
    }


    $.fn.updateData = function () {
        records.draw(false);
    }

    records = $('#records').DataTable({
        responsive: true,
        "autoWidth": false,
        "processing": true,
        "serverSide": true,
        "stateSave": true,
        "ajax": urlRecordList,
        "columns": recordsColumn,
        "initComplete": function (settings, j) {
            $.fn.updateRecords();
        },
        "columnDefs": [{
                "targets": [3, 4, 5, 6], // Column which need to update. Index start from zero
                "render": function (data, type, row, meta) {
                    if (type === 'display') {
                        if (meta.col == 3) {
                            switch (data) {
                                case '0':
                                    data = 'Deleted';
                                    break;
                                case '1':
                                    data = 'Active';
                                    break;
                                case '2':
                                    data = 'Block';
                                    break;
                            }
                        }
                        if (meta.col == 4) {
                            data = "<a class='inline-link clsView glyphicon glyphicon-eye-open' href='#' vid='" + data + "' title='View'></a>";
                        }
                        if (meta.col == 5) {
                            data = "<a class='inline-link clsEdit glyphicon glyphicon-pencil' href='#' eid='" + data + "' title='Edit'></a>";
                        }
                        if (meta.col == 6) {
                            data = "<a class='inline-link clsDelete glyphicon glyphicon-trash' href='#' did='" + data + "' title='Delete'></a>";
                        }
                    }
                    return data;
                }
            }]
    });
});