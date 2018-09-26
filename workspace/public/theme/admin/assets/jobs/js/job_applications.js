var recordGridColumn = 0;
var nonSorting = [3];

function delJobApplication(id) {
    var url = siteUrl + 'admin/job/application/delete/' + id;
    BootstrapDialog.confirm({
        title: 'CONFIRM',
        message: 'Are you sure you want to delete this job application?',
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
    tinymce.init({
        selector: 'textarea',
        height: 200,
        width: 815,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table contextmenu paste code'
        ],
        toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        content_css: []
    });
});
function updateJobStatus(id, publish) {
    $.ajax({
        url: siteUrl + '/admin/job/updateJobStatus',
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

function validataJobForm() {
    var job_title = $('#job_title').val();
    var job_location = $('#job_location').val();
    var jobCategoryId = $('#jobCategoryId').val();
    var employerId = $('#employerId').val();
    if (job_title == '' || job_location == '') {
        $('#job').addClass('active');
        $('#tab1').addClass('tab-pane active in col-sm-8');
        $('#tab2').removeClass('active');
        $('#setting').removeClass('active');
        return false;
    }

    if (jobCategoryId == '' || employerId == '') {
        $('#setting').addClass('active');
        $('#tab2').addClass('tab-pane active in col-sm-8');
        $('#tab1').removeClass('active');
        $('#job').removeClass('active');
        return false;
    }

}


$(document).ready(function () {
    var records;
    var urlRecordList = siteUrl + "admin/job/application/getApplicationList";
    var urlRecordEdit = siteUrl + "admin/pages/updatePageStatus";
    var urlDelete = siteUrl + "admin/job/application/delete";
    var recordsColumn = [
        {"data": "full_name"},
        {"data": "job_title"},
        {"data": "job_location"},
        {"data": "category_name"},
        {"data": "resume", "bSortable": false},
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
                message: 'Are you sure you want to delete this job application?',
                type: BootstrapDialog.TYPE_DANGER,
                closable: true,
                draggable: true,
                btnCancelLabel: 'Cancel',
                btnOKLabel: 'Ok',
                callback: function (result) {
                    if (result) {
                        $.fn.deleteRecord(recordId, $(this).parents('tr'));
                        BootstrapDialog.show({message: 'Job application deleted successfully!'});
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
                            data = "" + row.job_title + "";
                        }


                        if (meta.col == 2) {
                            data = "" + row.job_location + "";
                        }

                        if (meta.col == 3) {
                            data = "" + row.category_name + "";
                        }

                        if (meta.col == 4) {
                            data = "<a class='inline-link glyphicon glyphicon-eye-open' target='_blank' href='" + siteUrl + 'job_applications/' + row.resume + "' eid='" + row.edit + "'></a>";
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