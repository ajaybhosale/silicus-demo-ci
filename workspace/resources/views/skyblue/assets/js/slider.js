$(document).ready(function () {

});

$(document).on('submit', '#ModalForm', function (event) {
    var values = $(this).serialize();

    alert(values);
    return false;
});

$(document).on('click', '#addSlider', function (event) {
    var url = '/slider/create';
    viewPage(url);
});

$(document).on('click', '.deleteRecord', function (event) {
    var currentTarget = event.target;
    var thisObj = this;
    event.preventDefault();
    bootbox.confirm({
        message: 'Do you really want to delete this record?',
        buttons: {
            'confirm': {
                label: 'Yes',
            },
            'cancel': {
                label: 'No',
            }
        },
        callback: function (result) {
            if (result)
            {
                $(thisObj).parent().parent().find('form').submit();
            }
        }
    });
});

function viewPage(url) {
    $.ajax({
        url: url,
        type: "GET",
        dataType: "json",
        success: function (data) {
            $('#myModal').find('.modal-body').empty();
            $('#myModal').find('.modal-body').html(data.html);
        }
    });
}

function viewUploader() {
    top.parent.$.colorbox({
        iframe: true,
        width: "75%",
        height: "75%",
        href: "fileUploaderView?type=SLIDER",
        title: "Upload Slider Image",
        onComplete: function () {
            $("#cboxClose").attr("title", "Close");
        },
        onClosed: function () {
            var fileName = $('#fileName').val();
            showUploadedFile(fileName);
        }
    });
}

function showUploadedFile(fileName) {
    $.ajax({
        url: 'slider/getimage',
        type: "POST",
        data: {image: fileName, _token: $("input[name='_token']").val()},
        dataType: "json",
        success: function (data) {
            $('#thimage').empty();
            var img = $('<img />',
                    {id: 'thumbnailUrl',
                        src: data.thumbnailUrl,
                        width: 80
                    })
                    .appendTo($('#thimage'));

            var change = $('<a>', {id: 'changeImg', html: 'Change Image', href: '#', style: 'cursor:pointer;', onclick: 'removeImage(' + data.name + ');', image: data.name}).appendTo($('#thimage'));

            $('#thimage').show();
            $('#clicktoupload').hide();
        }
    });
}

function removeImage(data) {
    alert(data.toSource());
}