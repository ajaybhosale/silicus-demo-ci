$(document).ready(function () {
    strBaseUrl = document.location.origin;
    $_token = $("#csrf").val();
});

$('body').on('click', '#add_edit_folder_drive', function () {
    $('#save_folder_drive').html('Add folder');
    $('#add_edit_folder_drive_section').show('slow');
    $('#folderName-error').empty();
    $("#folderName").val('');
    $("#folderName").attr('data-id', '');
    $('#add_edit_folder_drive').hide();
});

$('body').on('click', '#cancel_folder_drive_section', function () {
    $('#add_edit_folder_drive_section').hide('slow');
    $('#add_edit_folder_drive').show('slow');
    if ('' != $("#folderName").attr('data-id')) {
        $("#custom_folder_drive_" + $("#folderName").attr('data-id')).show('slow');
        $("#folderName").attr('data-id', '');
    }
});

$('body').on('click', '#edit_folder_drive', function () {
    $("#folderName").val($('#custom_folder_drive_' + $(this).parents('ul').attr('data-id')).children('a').attr('data-name'));
    $("#custom_folder_drive_" + $(this).parents('ul').attr('data-id')).hide();
    $('#folderName-error').empty();
    $("#folderName").attr('data-id', $(this).parents('ul').attr('data-id'));
    $('#save_folder_drive').html('Save');
    $('#add_edit_folder_drive_section').show('slow');
    $('#add_edit_folder_drive').hide();
});

$('body').on('click', '.list-group-item a', function () {
    if (undefined == $(this).attr('id')) {
        $('.list-group-item-warning').removeClass('list-group-item-warning');
        $(this).parent('li').addClass('list-group-item-warning');
    }

    if (undefined != $(this).attr('data-name')) {
        $('#task_header').children().text($('.list-group-item-warning a span:first').text());
        listTasks($(this).attr('data-name'), $(this).attr('data-id'));
    }
});

$('body').on('click', '#save_folder_drive', function () {
    if ('' == $('#folderName').attr('data-id')) {
        var strUrl = '/folder/add';
    } else {
        var strUrl = '/folder/edit';
    }

    saveFolder(strUrl);
});

$('body').on('click', '#remove_folder_drive', function () {
    $.ajax({
        url: '/folder/remove',
        type: "POST",
        data: {id: $(this).parents('ul').attr('data-id'), _token: $_token},
        dataType: "json",
        success: function (data, textStatus, jqXHR) {
            if (data.success)
            {
                listFolders();
            }
        }
    });
});


function saveFolder(strUrl) {
    $.ajax({
        url: strUrl,
        type: "POST",
        data: {id: $('#folderName').attr('data-id'), folderName: $('#folderName').val(), _token: $_token},
        dataType: "json",
        success: function (data, textStatus, jqXHR) {
            if (data.success)
            {
                listFolders();
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            var errors = jqXHR.responseJSON;

            $('#folderName-error').empty();

            $strError = '<p style="color:red">';
            $.each(errors, function (key, value) {
                $strError += value + ' ';

            })

            $strError += '</p>';
            $('#folderName-error').append($strError);
        }
    });
}

function listFolders() {
    $.ajax({
        url: '/folders/list',
        type: "GET",
        data: {_token: $_token},
        dataType: "json",
        success: function (data, textStatus, jqXHR) {
            if (data.success)
            {
                $('#user_folder_drive_list').empty();
                $('#user_folder_drive_list').html(data.html);
                $('#add_edit_folder_drive_section').hide();
                $('#add_edit_folder_drive').show();
            }
        }
    });
}
