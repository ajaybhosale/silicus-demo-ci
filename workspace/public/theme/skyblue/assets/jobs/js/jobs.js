var recordGridColumn = 0;
var nonSorting = [0, 5, 6, 7];
function delJob(id) {
    var url = siteUrl + 'admin/job/delete/' + id;
    BootstrapDialog.confirm({
        title: 'CONFIRM',
        message: 'Are you sure you want to delete this job?',
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

    var form = document.getElementById("job_form");
    var jobTitle = $('#jobTitle').val();
    var jobLocation = $('#jobLocation').val();
    var jobDescription = $('#jobDescription').val();
    var expFrom = $('#expFrom').val();
    var expTo = $('#expTo').val();
    var website = $('#website').val();
    var validate_website = /^(http|https):\/\/(([a-zA-Z0-9$\-_.+!*'(),;:&=]|%[0-9a-fA-F]{2})+@)?(((25[0-5]|2[0-4][0-9]|[0-1][0-9][0-9]|[1-9][0-9]|[0-9])(\.(25[0-5]|2[0-4][0-9]|[0-1][0-9][0-9]|[1-9][0-9]|[0-9])){3})|localhost|([a-zA-Z0-9\-\u00C0-\u017F]+\.)+([a-zA-Z]{2,}))(:[0-9]+)?(\/(([a-zA-Z0-9$\-_.+!*'(),;:@&=]|%[0-9a-fA-F]{2})*(\/([a-zA-Z0-9$\-_.+!*'(),;:@&=]|%[0-9a-fA-F]{2})*)*)?(\?([a-zA-Z0-9$\-_.+!*'(),;:@&=\/?]|%[0-9a-fA-F]{2})*)?(\#([a-zA-Z0-9$\-_.+!*'(),;:@&=\/?]|%[0-9a-fA-F]{2})*)?)?$/;
    var number_validation = /^[0-9]+$/;

    var jobCategoryId = $('#jobCategoryId').val();
    var employerId = $('#employerId').val();
    var openings = $('#openings').val();
    form.onsubmit = function () {

        if (jobTitle == '') {
            $('#jobTitle_error').show().html('Please enter job title');
            $('#job').addClass('active');
            $('#tab1').addClass('tab-pane active in col-sm-8');
            $('#tab2').removeClass('active');
            $('#setting').removeClass('active');
            return false;
        } else {
            $('#jobTitle_error').hide();
        }

        if (jobLocation == '') {
            $('#jobLocation_error').show().html('Please enter job location');
            $('#job').addClass('active');
            $('#tab1').addClass('tab-pane active in col-sm-8');
            $('#tab2').removeClass('active');
            $('#setting').removeClass('active');
            return false;
        } else {
            $('#jobLocation_error').hide();
        }

        if (jobDescription == '') {
            $('#jobDescription_error').show().html('Please enter job description');
            $('#job').addClass('active');
            $('#tab1').addClass('tab-pane active in col-sm-8');
            $('#tab2').removeClass('active');
            $('#setting').removeClass('active');
            return false;
        } else {
            $('#jobDescription_error').hide();
        }

        if (expFrom == '') {
            $('#expFrom_error').show().html('Please enter from experience');
            $('#job').addClass('active');
            $('#tab1').addClass('tab-pane active in col-sm-8');
            $('#tab2').removeClass('active');
            $('#setting').removeClass('active');
            return false;
        } else {
            if (!number_validation.test(expFrom)) {
                $('#expFrom_error').show().html('Please enter only number');
                return false;
            } else {
                $('#expFrom_error').hide();
            }
        }

        if (expTo == '') {
            $('#expTo_error').show().html('Please enter to experience');
            $('#job').addClass('active');
            $('#tab1').addClass('tab-pane active in col-sm-8');
            $('#tab2').removeClass('active');
            $('#setting').removeClass('active');
            return false;
        } else {
            if (!number_validation.test(expTo)) {
                $('#expTo_error').show().html('Please enter only number');
                return false;
            }
            if (expFrom > expTo) {
                $('#expTo_error').show().html('Experience to value must be greated than or equal to Experience from');
                $('#job').addClass('active');
                $('#tab1').addClass('tab-pane active in col-sm-8');
                $('#tab2').removeClass('active');
                $('#setting').removeClass('active');
                return false;
            } else {
                $('#expTo_error').hide();

            }
        }

        if (jobCategoryId == '') {
            $('#jobCategoryId_error').show().html('Please select job category');
            $('#setting').addClass('active');
            $('#tab2').addClass('tab-pane active in col-sm-8');
            $('#tab1').removeClass('active');
            $('#job').removeClass('active');
            return false;
        } else {
            $('#jobCategoryId_error').hide();
        }

        if (employerId == '') {
            $('#employerId_error').show().html('Please select employer');
            $('#setting').addClass('active');
            $('#tab2').addClass('tab-pane active in col-sm-8');
            $('#tab1').removeClass('active');
            $('#job').removeClass('active');
            return false;
        } else {
            $('#employerId_error').hide();
        }

        if (openings == '') {
            $('#openings_error').show().html('Please enter number of openings');
            $('#setting').addClass('active');
            $('#tab2').addClass('tab-pane active in col-sm-8');
            $('#tab1').removeClass('active');
            $('#job').removeClass('active');
            return false;
        } else {
            if (!number_validation.test(openings)) {
                $('#openings_error').show().html('Please enter only number');
                return false;
            } else if (openings <= 0) {
                $('#openings_error').show().html('Please enter number greater than 0');
                return false;
            } else {
                $('#openings_error').hide();
            }
        }

        if (website != '') {
            if (!validate_website.test(website)) {
                $('#website_error').show().html('Please enter valid url e.g http://www.example.com');
                $('#setting').addClass('active');
                $('#tab2').addClass('tab-pane active in col-sm-8');
                $('#tab1').removeClass('active');
                $('#job').removeClass('active');
                return false;
            } else {
                $('#website_error').hide();
            }
        }

    }

}




$(document).ready(function () {
    var records;
    var urlRecordList = siteUrl + "admin/job/getJobsList";
    var urlRecordEdit = siteUrl + "admin/job/updateJobStatus";
    var urlDelete = siteUrl + "admin/job/delete";

    var recordsColumn = [
        {"data": "logo"},
        {"data": "job_title"},
        {"data": "job_location"},
        {"data": "employer_name"},
        {"data": "category_name"},
        {"data": "publish"},
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
                message: 'Are you sure you want to delete this job?',
                type: BootstrapDialog.TYPE_DANGER,
                closable: true,
                draggable: true,
                btnCancelLabel: 'Cancel',
                btnOKLabel: 'Ok',
                callback: function (result) {
                    if (result) {
                        $.fn.deleteRecord(recordId, $(this).parents('tr'));
                        BootstrapDialog.show({message: 'Job deleted successfully!'});
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
                "targets": [0, 1, 2, 3, 4, 5, 6, 7], // Column which need to update. Index start from zero
                "render": function (data, type, row, meta) {
                    if (type === 'display') {

                        if (meta.col == 0) {
                            data = "<img src='" + siteUrl + '/job_logo/small/' + row.logo + "'>";
                        }

                        if (meta.col == 1) {
                            data = "" + row.job_title + "";
                        }

                        if (meta.col == 2) {
                            data = "" + row.job_location + "";
                        }

                        if (meta.col == 3) {
                            data = "" + row.employer_name + "";
                        }

                        if (meta.col == 4) {
                            data = "" + row.category_name + "";
                        }

                        if (meta.col == 5) {
                            switch (data) {
                                case '0':
                                    data = data = '<a href="javascript:void(0)" class="inline-link clsEdit" eid="' + row.delete + '" has-read=1 title="Click here to" id="row_' + row.delete + '" onclick="#">Unpublish</a>';
                                    break;
                                case '1':
                                    data = data = '<a href="javascript:void(0)" class="inline-link clsEdit" eid="' + row.delete + '" has-read=0 title="Click here to" id="row_' + row.delete + '" onclick="#">Publish</a>';
                                    break;
                            }
                        }

                        if (meta.col == 6) {
                            data = "<a class='inline-link clsEdit glyphicon glyphicon-pencil' href='" + siteUrl + 'admin/job/edit/' + row.id + "' did='" + row.id + "'></a>";
                        }

                        if (meta.col == 7) {
                            data = "<a class='inline-link clsDelete delete glyphicon glyphicon-trash' href='javascript:void(0)' did='" + row.id + "'></a>";
                        }

                    }
                    return data;
                }
            }]
    });
});