$(document).ready(function () {
    strBaseUrl = document.location.origin;
    $_token = $("#csrf").val();
});

$('body').on('click', '#add_edit_folder', function () {
    $('#save_folder').html('Add folder');
    $('#add_edit_folder_section').show('slow');
    $('#folderName-error').empty();
    $("#folderName").val('');
    $("#folderName").attr('data-id', '');
    $('#add_edit_folder').hide();
});

$('body').on('click', '#add_task_link', function () {
    $("#add_task_section").attr('data-id', '');
    $("#add_task").text('Add Task');
    $('#add_task_section').show('slow');
    $('#add_task_link').hide('slow');
    $('#task-error').empty();
    $('#taskTitle').val('');
    $('.datepicker').val('');
});

$('body').on('click', '#cancel_task_section', function () {
    var intTaskId = $('#add_task_section').attr('data-id');

    if ('' !== intTaskId) {
        $('#task_' + intTaskId).show('slow');
    }

    $('#add_task_section').hide('slow');
    $('#add_task_link').show('slow');
});

$('body').on('click', '#cancel_folder_section', function () {
    $('#add_edit_folder_section').hide('slow');
    $('#add_edit_folder').show('slow');
    if ('' != $("#folderName").attr('data-id')) {
        $("#custom_folder_" + $("#folderName").attr('data-id')).show('slow');
        $("#folderName").attr('data-id', '');
    }
});

$('body').on('click', '#edit_folder', function () {
    $("#folderName").val($('#custom_folder_' + $(this).parents('ul').attr('data-id')).children('a').attr('data-name'));
    $("#custom_folder_" + $(this).parents('ul').attr('data-id')).hide();
    $('#folderName-error').empty();
    $("#folderName").attr('data-id', $(this).parents('ul').attr('data-id'));
    $('#save_folder').html('Save');
    $('#add_edit_folder_section').show('slow');
    $('#add_edit_folder').hide();
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
$('body').on('click', '#save_folder', function () {
    if ('' == $('#folderName').attr('data-id')) {
        var strUrl = '/folder/add';
    } else {
        var strUrl = '/folder/edit';
    }

    saveFolder(strUrl);
});
$('body').on('click', '#remove_folder', function () {
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

$('body').on('click', '#edit_task', function () {
    var intTaskId = $(this).parents('ul').attr('data-id');
    $('#task-error').empty();
    $("#add_task_link").hide();
    $('#taskTitle').val($('#task_' + intTaskId).find('.title').text());
    $('#dueDate').val($('#task_' + intTaskId).find('.due_date').attr('data-due_date').split("-").reverse().join("-"));
    $('#task_' + intTaskId).hide();
    $("#add_task_section").show('slow');
    $("#add_task_section").attr('data-id', intTaskId);
    $("#add_task").text('Save Task');
});

$('body').on('click', '#remove_task', function () {
    var folderId = '';
    var folderName = '';
    if (undefined != $('.list-group-item-warning').children('a').attr('data-id')) {
        folderId = $('.list-group-item-warning').children('a').attr('data-id');
    }

    folderName = $('.list-group-item-warning').children('a').attr('data-name');

    $.ajax({
        url: '/task/remove',
        type: "POST",
        data: {id: $(this).parents('ul').attr('data-id'), _token: $_token},
        dataType: "json",
        success: function (data, textStatus, jqXHR) {
            if (data.success)
            {
                listTasks(folderName, folderId);
            }
        }
    });
});
$('body').on('click', '#complete_task', function () {
    var folderId = '';
    var folderName = '';
    if (undefined != $('.list-group-item-warning').children('a').attr('data-id')) {
        folderId = $('.list-group-item-warning').children('a').attr('data-id');
    }

    folderName = $('.list-group-item-warning').children('a').attr('data-name');

    $.ajax({
        url: '/task/complete',
        type: "POST",
        data: {id: $(this).parents('ul').attr('data-id'), _token: $_token},
        dataType: "json",
        success: function (data, textStatus, jqXHR) {
            if (data.success)
            {
                listTasks(folderName, folderId);
            }
        }
    });
});
$('body').on('click', '#archive_folder', function () {
    $.ajax({
        url: '/folder/archive',
        type: "POST", data: {id: $(this).parents('ul').attr('data-id'), _token: $_token},
        dataType: "json",
        success: function (data, textStatus, jqXHR) {
            if (data.success)
            {
                listFolders();
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            var errors = jqXHR.responseJSON;

            $strError = '';
            $.each(errors, function (key, value) {
                $strError += value + ' ';

            });

            alert($strError);
        }
    });
});

$('body').on('click', '#add_task', function () {
    if ('' == $('#add_task_section').attr('data-id')) {
        var strUrl = '/task/add';
    } else {
        var strUrl = '/task/edit';
    }
    saveTask(strUrl);
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
function saveTask(strUrl) {

    var folderId = '';
    var folderName = '';
    if (undefined != $('.list-group-item-warning').children('a').attr('data-id')) {
        folderId = $('.list-group-item-warning').children('a').attr('data-id');
    }

    folderName = $('.list-group-item-warning').children('a').attr('data-name');

    $.ajax({
        url: strUrl,
        type: "POST",
        data: {id: $('#add_task_section').attr('data-id'), folderName: folderName, folderId: folderId, taskTitle: $('#taskTitle').val(), dueDate: $('#dueDate').val(), _token: $_token},
        dataType: "json",
        success: function (data, textStatus, jqXHR) {
            if (data.success)
            {
                listTasks(folderName, folderId);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            var errors = jqXHR.responseJSON;

            $('#task-error').empty();

            $strError = '<p style="color:red">';
            $.each(errors, function (key, value) {
                $strError += value + ' ';

            })

            $strError += '</p>';
            $('#task-error').append($strError);
        }
    });
}
function listTasks(folderName, folderId) {
    $.ajax({
        url: '/tasks/list',
        type: "POST",
        data: {folderId: folderId, folderName: folderName, _token: $_token},
        dataType: "json",
        success: function (data, textStatus, jqXHR) {
            if (data.success)
            {
                $('#load_tasks_list').empty();
                $('#load_tasks_list').html(data.html);
                $('#add_task_section').hide();
                $('#add_task_link').show();
            }
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
                $('#user_folder_list').empty();
                $('#user_folder_list').html(data.html);
                $('#add_edit_folder_section').hide();
                $('#add_edit_folder').show();
            }
        }
    });
}
