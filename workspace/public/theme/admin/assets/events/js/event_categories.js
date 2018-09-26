$().ready(function () {
    $("#showContentDiv").click(function () {
        $("#fromDiv").toggle(1600);
    });

});


$(document).ready(function () {
    var records;
    var urlRecordList = siteUrl + "admin/events/category/eventsCatJsonList";
    var urlDelete = siteUrl + "admin/events/category/delete";
    var urlRecordPublish = siteUrl + "admin/events/category/updateEventsCatStatus";
    var urlRecordEdit = siteUrl + "admin/events/category/edit";
    /* column name should be match with database table field names*/
    var recordsColumn = [
        {"data": "category_name"},
        {"data": "status"},
        {"data": "edit", "bSortable": false},
        {"data": "delete", "bSortable": false}
    ];

    $.fn.publishRecord = function (obj) {
        var id = obj.attr('pid');
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: urlRecordPublish,
            type: "POST",
            data: {id: id},
            success: function (data) {
                if (data) {
                    obj.html(data);
                }
            }
        });
    }

    $.fn.editRecord = function (obj) {
        var id = obj.attr('eid');
        window.location.href = urlRecordEdit + '/' + id;
    }

    $.fn.deleteRecord = function (recordId, obj) {
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: urlDelete,
            type: "POST",
            data: {id: recordId},
            success: function (data) {
                $.fn.removeRow(obj);
            }
        });
    }

    $.fn.updateRecords = function () {

        $('#records tbody').on('click', '.clsPublish', function () {
            $.fn.publishRecord($(this));
        });

        $('#records tbody').on('click', '.clsEdit', function () {
            $.fn.editRecord($(this));
        });

        $('#records tbody').on('click', '.clsDelete', function () {
            var recordId = $(this).attr('did');
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
                "targets": [1, 2, 3], // Column which need to update. Index start from zero
                "render": function (data, type, row, meta) {

                    if (type === 'display') {

                        if (meta.col == 1) { /* Its yours 3rd column i.e. status*/
                            switch (data) {
                                case '0':
                                    data = data = "<a class='inline-link clsPublish' href='javascript:void(0)' pid='" + row.edit + "' >Unpublished</a>";
                                    break;
                                case '1':
                                    data = data = "<a class='inline-link clsPublish' href='javascript:void(0)' pid='" + row.edit + "' >Published</a>";
                                    break;
                            }
                        }
                        if (meta.col == 2) {  /* Its yours 3rd column i.e. status*/
                            data = "<a class='inline-link clsEdit' href='javascript:void(0)' eid='" + data + "'>Edit</a>";
                        }
                        if (meta.col == 3) {
                            data = "<a class='inline-link clsDelete' href='javascript:void(0)' did='" + data + "'>Delete</a>";
                        }
                    }
                    return data;
                }
            }]
    });
});