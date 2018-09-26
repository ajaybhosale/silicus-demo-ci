function delImage(imageId, galleryId) {
    BootstrapDialog.confirm({
        title: 'CONFIRM',
        message: 'Are you sure you want to delete this image?',
        type: BootstrapDialog.TYPE_DANGER, // <-- Default value is BootstrapDialog.TYPE_PRIMARY
        closable: true, // <-- Default value is false
        draggable: true, // <-- Default value is false
        btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
        btnOKLabel: 'Ok', // <-- Default value is 'OK',
        btnOKClass: '', // <-- If you didn't specify it, dialog type will be used,
        callback: function (result) {
            // result will be true if button was click, while it will be false if users close the dialog directly.
            if (result) {
                window.location = "/admin/image-gallery/delete/" + imageId + "/" + galleryId;
            } else {
                return false;
            }
        }
    });
}
function deleteSelectedImages(galleryId) {
    var arrayIds = [];
    $("input:checkbox[name=listImageID]:checked").each(function () {
        arrayIds.push($(this).attr('id'));
    });
    if (arrayIds.length) {
        BootstrapDialog.confirm({
            title: 'CONFIRM',
            message: 'Are you sure you want to delete?',
            type: BootstrapDialog.TYPE_DANGER, // <-- Default value is BootstrapDialog.TYPE_PRIMARY
            closable: true, // <-- Default value is false
            draggable: true, // <-- Default value is false
            btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
            btnOKLabel: 'Ok', // <-- Default value is 'OK',
            btnOKClass: '', // <-- If you didn't specify it, dialog type will be used,
            callback: function (result) {
                // result will be true if button was click, while it will be false if users close the dialog directly.
                if (result) {
                    window.location = '/admin/image-gallery/delete/' + arrayIds + '/' + galleryId;
                } else {
                    return false;
                }
            }
        });
    }
}
function delAllImage(galleryId) {
    BootstrapDialog.confirm({
        title: 'CONFIRM',
        message: 'Are you sure you want to delete all images?',
        type: BootstrapDialog.TYPE_DANGER, // <-- Default value is BootstrapDialog.TYPE_PRIMARY
        closable: true, // <-- Default value is false
        draggable: true, // <-- Default value is false
        btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
        btnOKLabel: 'Ok', // <-- Default value is 'OK',
        btnOKClass: '', // <-- If you didn't specify it, dialog type will be used,
        callback: function (result) {
            // result will be true if button was click, while it will be false if users close the dialog directly.
            if (result) {
                window.location = "/admin/image-gallery/deleteAllImages/" + galleryId;
            } else {
                return false;
            }
        }
    });
}
$(document).ready(function () {
    $('a.gallery').colorbox();
    //Showing images from temp folder after upload images
    $('#AddInDb').hide();
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
            output += "<tr><td style='padding: 1%;'><img src='" + imagePath + "'></td><td>" + obj[i].name + "</td></tr>";
        }
        output += "</table>";
        $('.addImages').html(output);
        $('#AddInDb').show();
    });
    //Check all code for delete
    $(".checkAll").click(function () {
        if ($(".checkAll").hasClass('all_checked')) {
            $(".icheckbox_square-blue").removeClass('checked');
            $("input:checkbox").prop('checked', false);
            $(".checkAll").removeClass('all_checked');
            $('.fa-check').addClass('fa-square-o');
            $('.fa-square-o').removeClass('fa-check');
            return;
        }
        $("input:checkbox").prop('checked', true);
        $(".checkAll").addClass('all_checked');
        $('.fa-square-o').addClass('fa-check');
        $('.fa-check').removeClass('fa-square-o');
    });

    var records;
    var urlRecordList = siteUrl + "admin/image-gallery/getImageList/" + $('#records').attr('gallery-id');
    var recordsColumn = [
        {"data": "checkbox", "bSortable": false},
        {"data": "image_name"},
        {"data": "caption"},
        {"data": "description"},
        {"data": "status", "bSortable": false},
        {"data": "edit", "bSortable": false},
        {"data": "delete", "bSortable": false}
    ];
    records = $('#records').DataTable({
        responsive: true,
        "order": [[2, "desc"]],
        "autoWidth": false,
        "processing": true,
        "serverSide": true,
        "stateSave": true,
        "ajax": urlRecordList,
        "columns": recordsColumn,
        "columnDefs": [{
                "targets": [0, 1, 2, 4, 5, 6], // Column which need to update. Index start from zero
                "render": function (data, type, row, meta) {
                    if (meta.col == 0) {
                        data = "<input type='checkbox' id=" + row.checkbox + " class='checkbox' name='listImageID'>";
                    }
                    if (meta.col == 1) {
                        data = "<a class='gallery cboxElement' href='/imagegallery/" + row.image_name + "'><img class='img-responsive' src='/imagegallery/small/" + row.image_name + "' alt=''></a>";
                    }
                    if (meta.col == 2) {
                        var str = row.image_name;
                        data = row.caption != null ? row.caption : str.substring(0, str.indexOf('.'));
                    }
                    if (meta.col == 4) {
                        data = row.status == 1 ? 'published' : 'unpublished';
                    }
                    if (meta.col == 5) {
                        data = "<a class='glyphicon glyphicon-pencil' href='/admin/image-gallery/edit/" + row.edit + '/' + $('#records').attr('gallery-id') + "' >Edit</a>";
                    }
                    if (meta.col == 6) {
                        data = "<a class='glyphicon glyphicon-trash' onclick=delImage(" + row.delete + "," + $('#records').attr('gallery-id') + ") href='javascript:void(0)'>Delete</a>";
                    }
                    return data;
                }
            }]
    });


});