var recordGridColumn = 0;
var editcol = 2;
var deletecol = 3;
var nonSorting = [2, 3];
function delJobEmployer(id) {
    url = siteUrl + '/admin/job/employer/delete/' + id;
    BootstrapDialog.confirm({
        title: 'CONFIRM',
        message: 'Are you sure you want to delete this employer?',
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


function validate_employer_form() {
    var form = document.getElementById("addEmployerForm");
    var employerName = $("#employerName").val();
    var email = $("#employerEmail").val();
    var email_validation = /^\w+([\.-]?\ w+)*@\w+([\.-]?\ w+)*(\.\w{2,3})+$/;
    var contactNumber = $("#employerContactNumber").val();
    var contactNumber_validation = /^(\+?1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/
    var employerLocation = $("#employerLocation").val();


    form.onsubmit = function () {
        if (employerName == '') {
            $('#employerName_error').show().html('Please enter employer name');
            return false;
        } else {
            $('#employerName_error').hide();
        }

        if (email == '') {
            $('#employerEmail_error').show().html('Please enter email');
            return false;
        }
        else {
            if (!email_validation.test(email)) {
                $('#employerEmail_error').show().html('Please enter valid email');
                return false;
            } else {
                $('#employerEmail_error').hide();
            }
        }

        if (contactNumber == '') {
            $('#employerContactNumber_error').show().html('Please enter contact number');
            return false;
        }
        else {
            if (!contactNumber_validation.test(contactNumber)) {
                $('#employerContactNumber_error').show().html('Please enter valid contact number');
                return false;
            } else {
                $('#employerContactNumber_error').hide();
            }
        }

        if (employerLocation == '') {
            $('#employerLocation_error').show().html('Please enter employer location');
            return false;
        } else {
            $('#employerLocation_error').hide();
        }

    }

}

$(document).ready(function () {
    var records;
    var urlRecordList = siteUrl + "admin/job/employer/getEmployerList";
    var urlRecordEdit = siteUrl + "admin/pages/updatePageStatus";
    var urlDelete = siteUrl + "admin/job/employer/delete";
    var recordsColumn = [
        {"data": "employer_name"},
        {"data": "status", "bSortable": false},
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
                message: 'Are you sure you want to delete this employer?',
                type: BootstrapDialog.TYPE_DANGER,
                closable: true,
                draggable: true,
                btnCancelLabel: 'Cancel',
                btnOKLabel: 'Ok',
                callback: function (result) {
                    if (result) {
                        $.fn.deleteRecord(recordId, $(this).parents('tr'));
                        BootstrapDialog.show({message: 'Employer deleted successfully!'});
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
                        if (meta.col == 1) {

                            switch (data) {
                                case '0':
                                    data = data = 'Inactive';
                                    break;
                                case '1':
                                    data = data = 'Active';
                                    break;
                            }
                        }


                        if (meta.col == 2) {
                            data = "<a class='inline-link clsEdit glyphicon glyphicon-pencil' href='" + siteUrl + 'admin/job/employer/edit/' + row.edit + "' eid='" + row.edit + "'></a>";
                        }

                        if (meta.col == 3) {
                            data = "<a class='inline-link clsDelete delete glyphicon glyphicon-trash' href='javascript:void(0)' did='" + row.delete + "'></a>";
                        }

                    }
                    return data;
                }
            }]
    });
});