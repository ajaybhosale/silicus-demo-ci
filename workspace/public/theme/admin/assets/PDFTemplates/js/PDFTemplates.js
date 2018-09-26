var recordGridColumn = 0;
var nonSorting = [4, 5];

function delTemplate(id) {
    var url = siteUrl + 'admin/pdf/template/delete/' + id;
    BootstrapDialog.confirm({
        title: 'CONFIRM',
        message: 'Are you sure you want to delete this template?',
        type: BootstrapDialog.TYPE_DANGER, // <-- Default value is BootstrapDialog.TYPE_PRIMARY
        closable: true, // <-- Default value is false
        draggable: true, // <-- Default value is false
        btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
        btnOKLabel: 'Ok', // <-- Default value is 'OK',
        btnOKClass: '', // <-- If you didn't specify it, dialog type will be used,
        callback: function (result) {
            // result will be true if button was click, while it will be false if users close the dialog directly.
            if (result) {
                window.location = url;
            } else {
                return false;
            }
        }
    });
}

$().ready(function () {
    $("#showContentDiv").click(function () {
        $("#fromDiv").toggle(1600);
    });
});
function updateTemplateStatus(id, publish) {
    $.ajax({
        url: siteUrl + '/admin/pdf/template/updateTemplateStatus',
        headers:
                {
                    'X-CSRF-Token': $('input[name="_token"]').val()
                },
        type: 'POST',
        data: {id: id, publish: publish},
        success: function (data) {
            var container = $('#pageData');
            if (data) {
                container.html(data);
            }
        }
    });
}

function validataPageForm() {
    var name = $('#name').val();
    var slug = $('#slug').val();
    var metaTitle = $('#metaTitle').val();
    var metaKeyword = $('#metaKeyword').val();
    var metaDescription = $('#metaDescription').val();
    var pageCategoryId = $('#pageCategoryId').val();
    if (name == '' || slug == '') {
        $('#page').addClass('active');
        $('#tab1').addClass('tab-pane active in col-sm-8');
        $('#tab2').removeClass('active');
        $('#tab3').removeClass('active');
        $('#meta').removeClass('active');
        $('#setting').removeClass('active');
        return false;
    }
    if (metaTitle == '' || metaKeyword == '' || metaDescription == '') {
        $('#meta').addClass('active');
        $('#tab2').addClass('tab-pane active in col-sm-8');
        $('#tab1').removeClass('active');
        $('#tab3').removeClass('active');
        $('#page').removeClass('active');
        $('#setting').removeClass('active');
        return false;
    }
    if (pageCategoryId == '') {
        $('#setting').addClass('active');
        $('#tab3').addClass('tab-pane active in col-sm-8');
        $('#tab1').removeClass('active');
        $('#tab2').removeClass('active');
        $('#page').removeClass('active');
        $('#meta').removeClass('active');
        return false;
    }

}



$(document).ready(function () {
    var records;
    var urlRecordList = siteUrl + "admin/pdf/template/getTemplateList";
    var urlRecordEdit = siteUrl + "admin/pdf/template/updateTemplateStatus";
    var urlDelete = siteUrl + "admin/pdf/template/delete";

    var recordsColumn = [
        {"data": "name"},
        {"data": "preview", "bSortable": false},
        {"data": "previewpdf", "bSortable": false},
        {"data": "publish", "bSortable": false},
        {"data": "edit", "bSortable": false},
        {"data": "delete", "bSortable": false}
    ];
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

    $.fn.editRecord = function (obj) {

        var id = obj.attr('eid');
        var prvAttr = obj.attr('has-read');

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: urlRecordEdit,
            type: "POST",
            data: {id: id, publish: prvAttr},
            success: function (data) {
                if (data) {
                    obj.attr('has-read', prvAttr == '1' ? '0' : '1');
                    obj.text(prvAttr == '1' ? 'Publish' : 'Unpublish');
                    getNotificationCount();
                }
            }
        });
    }

    $.fn.updateRecords = function () {

        $('#records tbody').on('click', '.clsEdit', function () {
            $.fn.editRecord($(this));
        });
        $('#records tbody').on('click', '.clsDelete', function () {

            var recordId = $(this).attr('did');
            BootstrapDialog.confirm({
                title: 'WARNING',
                message: 'Are you sure you want to delete this template?',
                type: BootstrapDialog.TYPE_DANGER,
                closable: true,
                draggable: true,
                btnCancelLabel: 'Cancel',
                btnOKLabel: 'Ok',
                callback: function (result) {
                    if (result) {
                        $.fn.deleteRecord(recordId, $(this).parents('tr'));
                        BootstrapDialog.show({message: 'Template deleted successfully!'});
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
                "targets": [1, 2, 3, 4, 5], // Column which need to update. Index start from zero
                "render": function (data, type, row, meta) {
                    if (type === 'display') {

                        if (meta.col == 1) {
                            data = "<a class='glyphicon glyphicon-eye-open' target='_blank' href='" + siteUrl + '/admin/pdf/template/preview/' + row.preview + "' pid='" + row.preview + "'></a>";
                        }

                        if (meta.col == 2) {
                            data = "<a class='glyphicon glyphicon-eye-open' target='_blank' href='" + siteUrl + '/admin/pdf/previewPDf/' + row.preview + "' pid='" + row.preview + "'></a>";
                        }


                        if (meta.col == 3) {
                            switch (data) {
                                case '0':
                                    data = data = '<a href="javascript:void(0)" class="inline-link clsEdit" eid="' + row.delete + '" has-read=1 title="Click here to" id="row_' + row.delete + '" onclick="#">Unpublish</a>';
                                    break;
                                case '1':
                                    data = data = '<a href="javascript:void(0)" class="inline-link clsEdit" eid="' + row.delete + '" has-read=0 title="Click here to" id="row_' + row.delete + '" onclick="#">Publish</a>';
                                    break;
                            }
                        }
//                        if (meta.col == 4) {
//                            data = "<a class='inline-link clsPreview btn btn-info' target='_blank' href='" + siteUrl + row.slug + "' pid='" + row.preview + "'><span class='glyphicon glyphicon-zoom-in'></span> Preview</a>";
//                        }

                        if (meta.col == 4) {
                            data = "<a class='inline-link clsEdit glyphicon glyphicon-pencil' href='" + siteUrl + 'admin/pdf/template/edit/' + row.edit + "'></a>";
                        }

                        if (meta.col == 5) {
                            data = "<a class='inline-link clsDelete delete glyphicon glyphicon-trash' href='javascript:void(0)' did='" + row.delete + "'></a>";
                        }

                    }
                    return data;
                }
            }]
    });
});