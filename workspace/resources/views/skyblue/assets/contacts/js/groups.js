var recordGridColumn = 0;
var nonSorting = [2, 3];
function delGroup(url) {
    BootstrapDialog.confirm({
        title: 'CONFIRM',
        message: 'Are you sure you want to delete this group?',
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
    $('#groupName_error').hide();
    var default_group = $('#default_group').val();
    showGroupView(default_group);

    $('#search').keyup(function () {
        var valThis = $(this).val().toLowerCase();

        $('#navList>div').each(function () {
            var text = $(this).text().toLowerCase();
            (text.indexOf(valThis) > -1) ? $(this).show() : $(this).hide();
        });
    });
});

function showGroupView(id) {
    $.ajax({
        url: siteUrl + 'contacts/viewGroup',
        headers:
                {
                    'X-CSRF-Token': $('input[name="_token"]').val()
                },
        type: 'POST',
        data: {id: id},
        success: function (data) {
            var container = $('#showGroupViewDiv');
            if (data) {
                container.html(data);
            }
        }
    });
}

function saveGroupView() {

    var groupId = $("#groupId").val();
    var groupName = $("#groupName").html();

    if (groupName == '') {
        $('#groupName_error').show();
        return false;

    } else {
        $('#groupName_error').hide();
    }

    $.ajax({
        url: siteUrl + 'contacts/updateGroup',
        headers:
                {
                    'X-CSRF-Token': $('input[name="_token"]').val()
                },
        type: 'POST',
        data: {groupId: groupId, groupName: groupName},
        success: function (data) {
            var container = $('#groupList');
            if (data) {
                container.html(data);
            }
            showGroupView(groupId);
        },
        error: function (data) {

        }
    });

}