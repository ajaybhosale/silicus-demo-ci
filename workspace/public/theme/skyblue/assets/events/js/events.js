var recordGridColumn = 1;
var nonSorting = [0];

$().ready(function () {
    $('#fromEndDate').daterangepicker();
    $('.form_datetime').timepicker({'timeFormat': 'H:i'});
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
function updateEventImageStatus(imageId, event) {
    $.ajax({
        url: siteUrl + '/admin/events/updateEventImageStatus',
        headers:
                {
                    'X-CSRF-Token': $('input[name="_token"]').val()
                },
        type: 'POST',
        data: {imageId: imageId},
        success: function (data) {
            if (data) {
                $(event).html(data);
            }
        }
    });
}

function updateEventFeatureImageStatus(imageId, event) {
    console.log(123);
    $.ajax({
        url: siteUrl + '/admin/events/updateEventFeatureImage',
        headers:
                {
                    'X-CSRF-Token': $('input[name="_token"]').val()
                },
        type: 'POST',
        data: {imageId: imageId},
        success: function (data) {
            if (data) {
                $(event).html(data);
            }
        }
    });
}
$(function () {
    /*Showing images from temp folder after upload images*/
    $(document).bind('cbox_closed', function () {
        var fileNameData = $('#fileName').val();
        var obj = $.parseJSON(fileNameData);
        var output = "<table style='width: 100%'>";
        output += "<tr>";
        output += "<td style='padding: 1%;'>Image</td>";
        output += "<td>Name</td>";
        output += "</tr>";
        for (var i in obj)
        {
            var imagePath = "/admin/image-gallery/imagePath/" + obj[i].name;
            output += "<tr><td style='padding: 1%;'><img class=' ' src='" + imagePath + "'></td><td>" + obj[i].name + "</td></tr>";
        }
        output += "<tr><td><a onclick='clearAll()' class='btn btn-success' >Clear</a></td></tr></table> ";
        $('.addImages').html(output);
    });

});

function clearAll() {
    $('.addImages').html('');
    $('#fileName').val('');
}

function deleteSelectedImages(eventId) {
    var arrayIds = [];
    $("input:checkbox[name=listImageID]:checked").each(function () {
        arrayIds.push($(this).attr('key'));
    });
    if (arrayIds.length) {
        BootstrapDialog.confirm({
            title: 'CONFIRM',
            message: 'Are you sure you want to delete selected images?',
            type: BootstrapDialog.TYPE_DANGER, // <-- Default value is BootstrapDialog.TYPE_PRIMARY
            closable: true, // <-- Default value is false
            draggable: true, // <-- Default value is false
            btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
            btnOKLabel: 'Ok', // <-- Default value is 'OK',
            btnOKClass: '', // <-- If you didn't specify it, dialog type will be used,
            callback: function (result) {
                // result will be true if button was click, while it will be false if users close the dialog directly.
                if (result) {
                    $.ajax({
                        url: siteUrl + '/admin/events/deleteImages',
                        headers:
                                {
                                    'X-CSRF-Token': $('input[name="_token"]').val()
                                },
                        type: 'POST',
                        data: {eventId: eventId, imageId: arrayIds},
                        success: function (data) {
                            if (data > 0) {
                                for (image in arrayIds) {
                                    var newId = '#image_' + arrayIds[image];
                                    $(newId).remove();
                                }
                            }
                        }
                    });
                } else {
                    return false;
                }
            }
        });
    }
}

function selectAllImages() {
    if ($("input:checkbox[name=selectAll]:checked")) {
        $("input:checkbox[name=listImageID]").each(function () {
            $(this).prop('checked', true);
        });
    } else {
        $("input:checkbox[name=listImageID]").each(function () {
            $(this).prop('checked', false);
        });
    }
}
$(document).ready(function () {
    $(".selectAll").change(function () {
        $("input:checkbox[name=listImageID]").prop('checked', this.checked);
        $(".selectAll").prop('checked', this.checked);
    });
    $('#recordGrid2').DataTable({
        "order": [[0, "desc"]],
        "aoColumnDefs": [
            {'bSortable': false, 'aTargets': [3, 4, 5]}
        ]
    });


    $("#form_submit_button").click(function () {


        var eventTitle = $('#eventTitle').val();
        var eventDescription = $('#eventDescription').val();

        var number_validation = /^[0-9]+$/;
        var eventCategoryId = $('#eventCategoryId').val();
        if (eventTitle == '') {
            $('#eventTitle_error').show().html('Please enter event title.');

            return false;
        } else {
            $('#eventTitle_error').hide();
        }
        /*
         if (eventDescription == '') {
         $('#eventDescription_error').show().html('Please enter event description.');
         return false;
         } else {
         $('#eventDescription_error').hide();
         }*/
        if (eventCategoryId == '') {
            $('#eventCategoryId_error').show().html('Please select event category');
            return false;
        } else {
            $('#eventCategoryId_error').hide();
        }
        $('#event_form').submit();
    });


});
$(document).ready(function () {
    var records;
    var urlRecordList = siteUrl + "admin/events/getList";
    var urlDelete = siteUrl + "admin/events/delete";
    var urlRecordPublish = siteUrl + "admin/events/updateEventStatus";
    var urlRecordEdit = siteUrl + "admin/events/edit";
    /* column name should be match with database table field names*/
    var recordsColumn = [
        {"data": "event_title"},
        {"data": "from_end_date"},
        {"data": "category_name"},
        {"data": "publish"},
        {"data": "edit", "bSortable": false},
        {"data": "delete", "bSortable": false}
    ];

    $.fn.publishRecord = function (obj) {
        var id = obj.attr('pid');
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: urlRecordPublish,
            type: "POST",
            data: {eventId: id},
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
            data: {eventId: recordId},
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
                "targets": [3, 4, 5], // Column which need to update. Index start from zero
                "render": function (data, type, row, meta) {


                    if (type === 'display') {

                        if (meta.col == 3) { /* Its yours 3rd column i.e. status*/
                            switch (data) {
                                case '0':
                                    data = data = "<a class='inline-link clsPublish' href='javascript:void(0)' pid='" + row.edit + "' >Unpublished</a>";
                                    break;
                                case '1':
                                    data = data = "<a class='inline-link clsPublish' href='javascript:void(0)' pid='" + row.edit + "' >Published</a>";
                                    break;
                            }
                        }
                        if (meta.col == 4) {  /* Its yours 3rd column i.e. status*/
                            data = "<a class='inline-link clsEdit glyphicon glyphicon-pencil' href='javascript:void(0)' eid='" + data + "'></a>";
                        }
                        if (meta.col == 5) {
                            data = "<a class='inline-link clsDelete glyphicon glyphicon-trash' href='javascript:void(0)' did='" + data + "'></a>";
                        }
                    }
                    return data;
                }
            }]
    });
});