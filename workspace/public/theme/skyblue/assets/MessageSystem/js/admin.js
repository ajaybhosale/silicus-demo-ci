var adminUrl = window.location.href.indexOf('admin');
if (adminUrl > 0) {
    adminUrl = '/admin';
} else {
    adminUrl = '';
}
/*send drafted message*/
function sendMessage($messageId) {
    window.location = adminUrl + '/messages/sentDraftMessage/' + $messageId;
}
/*delete trash message after open*/
function delShowTrash(messageId) {
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
            if (result) {
                window.location = adminUrl + '/messages/delete/' + messageId;
            } else {
                return false;
            }
        }
    });
}
function restoreMessage(messageId) {
    window.location = adminUrl + '/messages/restoreMessage/' + messageId;
}
/*delete trash message after selection*/
function delTrash() {
    var arrayIds = [];
    $("input:checkbox[name=listMessageID]:checked").each(function () {
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
                if (result) {
                    window.location = adminUrl + '/messages/delete/' + arrayIds;
                } else {
                    return false;
                }
            }
        });
    }
}
function openMessageTrash(messageId) {
    window.location = adminUrl + '/messages/showTrashMessage/' + messageId + '/trash';
}
/*delete draft message*/
function delDraft() {
    var arrayIds = [];
    var caption = 'Draft';
    $("input:checkbox[name=listMessageID]:checked").each(function () {
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
                    window.location = adminUrl + '/messages/deleteDraft/' + arrayIds;
                } else {
                    return false;
                }
            }
        });
    }

}
function openMessageDraft(messageId) {
    window.location = adminUrl + '/messages/showDraftMessage/' + messageId + '/drafts';
}
/*delete sent message*/
function delSent() {
    var arrayIds = [];
    var caption = 'Draft';
    $("input:checkbox[name=listMessageID]:checked").each(function () {
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
                    window.location = adminUrl + '/messages/moveToTrashMessageFromSent/' + arrayIds;
                } else {
                    return false;
                }
            }
        });
    }
}
function openMessageSent(messageId) {
    window.location = adminUrl + '/messages/showMessage/' + messageId + '/sent';
}
/*delete inbox message*/
function delInbox() {
    var arrayIds = [];
    $("input:checkbox[name=listMessageID]:checked").each(function () {
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
                    window.location = adminUrl + '/messages/moveToTrashMessageFromInbox/' + arrayIds;
                } else {
                    return false;
                }
            }
        });
    }
}
function openMessageInbox(messageId) {
    window.location = adminUrl + '/messages/showMessage/' + messageId + '/inbox';
}

$(document).ready(function () {
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
    $('button[data-status="draft"]').click(function () {
        $('#status').val($(this).attr('data-status'));
        $('#createMessage').submit();
    });
    $('.btn-trashed').click(function () {
        var messageId = $(this).attr('id');
        var folderName = $(this).attr('foldername');
        if (messageId) {
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
                    if (result) {
                        if (folderName == 'inbox')
                            window.location = adminUrl + '/messages/moveToTrashMessageFromInbox/' + messageId;
                        if (folderName == 'sent')
                            window.location = adminUrl + '/messages/moveToTrashMessageFromSent/' + messageId;
                        if (folderName == 'drafts')
                            window.location = adminUrl + '/messages/deleteDraft/' + messageId;
                    } else {
                        return false;
                    }
                }
            });
        }

    });

    $(".btn-back").click(function () {
        var folderName = $(this).attr('foldername');
        window.location = adminUrl + '/messages/' + folderName;
    });
    $(".btn-reply").click(function () {
        var messageId = $(this).attr('id');
        var folderName = $(this).attr('foldername');
        $('#show-message').load(adminUrl + '/messages/reply' + '/' + messageId + '/' + folderName);
    });

    $(".btn-forward").click(function () {
        var messageId = $(this).attr('id');
        var folderName = $(this).attr('foldername');
        $('#show-message').load(adminUrl + '/messages/forward' + '/' + messageId + '/' + folderName);
    });

});
$.getScript('http://cdnjs.cloudflare.com/ajax/libs/select2/3.4.8/select2.min.js', function () {
    $("#tagPicker").select2({
        closeOnSelect: false
    });
});