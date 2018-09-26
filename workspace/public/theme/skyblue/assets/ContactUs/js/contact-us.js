var recordGridColumn = 0;
var nonSorting = [4, 5, 6];
$(window).load(function () {

    var formBuilder = $(document.getElementById('fb-template')).formBuilder();
    $('section#snap .error').remove();
    $("#submit-form").click(function () {
        var formData = formBuilder.data('formBuilder').formData;
        var formRenderOpts = {
            render: false,
            formData: formData
        };
        var markup = new FormRenderFn(formRenderOpts).markup;
        if ($('[name="id"]').val() && markup.trim() != 'No form data.'.trim()) {
            $('[name="html"]').val(markup);
        } else if ($('[name="id"]').val() == 0) {
            $('[name="html"]').val(markup);
        }


        var mailTo = $('#mailTo').val();
        var mailFrom = $('#mailFrom').val();
        var mailSubject = $('#mailSubject').val();
        var mailHeaders = $('#mailHeaders').val();
        var mailBody = $('#mailBody').val();
        var email_validation = /^\w+([\.-]?\ w+)*@\w+([\.-]?\ w+)*(\.\w{2,3})+$/;

        if (mailTo == '' || mailFrom == '' || mailSubject == '' || mailHeaders == '' || mailBody == '') {
            $('#form').removeClass('active');
            $('#mail').addClass('active');
            $('#tab1').removeClass('active');
            $('#tab2').addClass('tab-pane active in col-sm-8');
            $('#tab3').removeClass('active');
            $('#tab4').removeClass('active');
            return false;
        }


        var senderMessageSuccess = $('#senderMessageSuccess').val();
        var senderMessageFailed = $('#senderMessageFailed').val();
        var validationErrors = $('#validationErrors').val();
        var submissionSpam = $('#submissionSpam').val();
        var termsValidation = $('#termsValidation').val();
        var requiredField = $('#requiredField').val();
        var maxLength = $('#maxLength').val();
        var minLength = $('#minLength').val();
        var invalidDate = $('#invalidDate').val();
        var earlierDate = $('#earlierDate').val();
        var laterDate = $('#laterDate').val();
        var fileUploadFailed = $('#fileUploadFailed').val();
        var fileUploadInvalidFileType = $('#fileUploadInvalidFileType').val();
        var largeFile = $('#largeFile').val();
        var fileUploadPHPError = $('#fileUploadPHPError').val();
        var invalidNumberFormat = $('#invalidNumberFormat').val();
        var smallerNumber = $('#smallerNumber').val();
        var largerNumber = $('#largerNumber').val();
        var wrongAnswer = $('#wrongAnswer').val();
        var invalidEmail = $('#invalidEmail').val();
        var invalidURL = $('#invalidURL').val();
        var invalidPhone = $('#invalidPhone').val();

        if (senderMessageSuccess == '' || senderMessageFailed == '' || validationErrors == '' ||
                submissionSpam == '' || termsValidation == '' || requiredField == '' || maxLength == '' || minLength == '' ||
                invalidDate == '' || earlierDate == '' || laterDate == '' || fileUploadFailed == '' || fileUploadInvalidFileType == '' || largeFile == ''
                || fileUploadPHPError == '' || invalidNumberFormat == '' || smallerNumber == '' || largerNumber == '' || wrongAnswer == '' || invalidEmail == '' || invalidURL == '' || invalidPhone == '') {
            $('#form').removeClass('active');
            $('#mail').removeClass('active');
            $('#messages').addClass('active');
            $('#tab1').removeClass('active');
            $('#tab3').addClass('tab-pane active in col-sm-8');
            $('#tab2').removeClass('active');
            $('#tab4').removeClass('active');
            return false;
        }


    });

});



$(document).ready(function () {

    if ($("#useMailTwo").prop("checked") == true) {
        $('#show_autoresponder').show();
    } else {
        $('#show_autoresponder').hide();
    }

    $("#useMailTwo").click(function () {
        if ($('#useMailTwo').is(":checked"))
        {
            $('#show_autoresponder').show();
        } else {
            $('#show_autoresponder').hide();
        }
    });


    $('#error_mailTo').hide();
    $('#error_mailFrom').hide();
    $('#error_valid_mailTo').hide();
    $('#error_valid_mailFrom').hide();
    $('#error_mailSubject').hide();
    $('#error_mailHeaders').hide();
    $('#error_mailBody').hide();

    var max_fields = 10; //maximum input boxes allowed
    var wrapper = $("#tab4"); //Fields wrapper
    var add_button = $(".add_field_button"); //Add button ID

    var x = 1; //initlal text box count
    $(add_button).click(function (e) { //on add input button click
        e.preventDefault();
        if (x < max_fields) { //max input box allowed
            x++; //text box increment
            $(wrapper).append('<div><div class="col-sm-12 form-group" style="padding-left: 0px;"><label>Address </label><a href="#" style="margin-left: 80%;" class="remove_field">Remove</a><textarea class="form-control" name="locationAddress[]" id="locationAddress[]"></textarea></div><span style="color: red;"></span><div class="col-sm-12 form-group" style="padding-left: 0px;"><label>Email </label><input type="text" name="locationEmail[]" id="locationEmail[]" class="form-control" required="required" value=""></div><span style="color: red;"></span></div></div>'); //add input box
        }
    });

    $(wrapper).on("click", ".remove_field", function (e) { //user click on remove text
        e.preventDefault();
        if (x == 0) {
            $('.remove_field').hide();
        }
        $(this).parent('div').parent('div').remove();
        x--;
    })

    var records;
    var urlRecordList = siteUrl + "admin/contactus/getList";
    var urlRecordEdit = siteUrl + "admin/contactus/updateFormStatus";
    var urlDelete = siteUrl + "admin/contactus/form/delete";
    var recordsColumn = [
        {"data": "form_name"},
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
                message: 'Are you sure you want to delete this form?',
                type: BootstrapDialog.TYPE_DANGER,
                closable: true,
                draggable: true,
                btnCancelLabel: 'Cancel',
                btnOKLabel: 'Ok',
                callback: function (result) {
                    if (result) {
                        $.fn.deleteRecord(recordId, $(this).parents('tr'));
                        BootstrapDialog.show({message: 'Form deleted successfully!'});
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
                                    data = data = '<a href="javascript:void(0)" class="inline-link clsEdit" eid="' + row.delete + '" has-read=1 title="Click here to" id="row_' + row.delete + '">Unpublish</a>';
                                    break;
                                case '1':
                                    data = data = '<a href="javascript:void(0)" class="inline-link clsEdit" eid="' + row.delete + '" has-read=0 title="Click here to" id="row_' + row.delete + '">Publish</a>';
                                    break;
                            }
                        }

                        if (meta.col == 2) {
                            data = "<a class='inline-link clsEdit glyphicon glyphicon-pencil' href='" + siteUrl + 'admin/contactus/form/edit/' + row.edit + "'></a>";
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