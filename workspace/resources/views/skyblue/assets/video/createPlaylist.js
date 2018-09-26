//<![CDATA[

var nonSorting = [0, 2, 3];
$(document).ready(function () {
    /**
     * Play selected media file
     * @param {type} file_ids
     * @returns {undefined}
     */
    function playPlaylist(file_ids) {

        $("input[type='checkbox']").prop('checked', false);
        var media_files_array = file_ids.split(",");
        console.log(media_files_array);
        $('.video_file').each(function (index, item) {
            var media_id = $(item).attr("video_id");
            if ($.inArray(media_id, media_files_array) < 0) {
                return;
            }
            $("input[value='" + media_id + "']").prop('checked', true);
            var file = $(item).val();
            addAudioVideo(file);
        });
        myPlaylist.select(0);
        myPlaylist.play();
    }
    /*myPlaylist.playlist.length;*/
    /**
     * Play the selected playlist
     */
    $("#Play_Playlist").click(function () {
        if ($("#playlist_list option").length < 1) {
            $("#Delete_Playlist").parent('div').next(".error").html("Create playlist first.");
            return false;
        }

        var file_ids = $('#playlist_list :selected').val();
        myPlaylist.remove();
        setTimeout(function () {
            playPlaylist(file_ids);
        }, 1000);
    });
    /**
     * Delete the selected playlist
     */
    $("#Delete_Playlist").click(function () {
        var tabId = $('#playlist_list :selected').attr('tableId');
        console.log(tabId);

        if (tabId == undefined) {
            $(this).parent('div').next(".error").html("Create playlist first.");
            return false;
        }
        BootstrapDialog.confirm({
            title: 'WARNING',
            message: 'Are you sure you want to delete this playlist?',
            type: BootstrapDialog.TYPE_DANGER, // <-- Default value is BootstrapDialog.TYPE_PRIMARY
            closable: true, // <-- Default value is false
            draggable: true, // <-- Default value is false
            btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
            btnOKLabel: 'Ok', // <-- Default value is 'OK',
            btnOKClass: '', // <-- If you didn't specify it, dialog type will be used,
            callback: function (result) {
                // result will be true if button was click, while it will be false if users close the dialog directly.
                if (result) {
                    delPlaylist(tabId);
                } else {
                    return false;
                }
            }
        });



    });
    /**
     * Update the playlist
     */
    $("#Update_Playlist").click(function () {
        console.log($('#playlist_list :selected').attr('tableId'));
        if ($("#playlist_list option").length < 1) {
            $("#Delete_Playlist").parent('div').next(".error").html("Create playlist first.");
            return false;
        }
        $('#playlistId').val($('#playlist_list :selected').attr('tableId'));
        $('#playlist_form').attr('action', 'upPlaylist');
        $('#playlist_form').submit();
    });
    /**
     * Create the playlist
     */
    $("#Create_Playlist").click(function () {
        var len = $("[name='playlist[]']:checked").length;

        if (len) {

            if ($("#playlistName").val().length) {
                $('#playlist_form').submit();
            } else {
                $("#Create_Playlist").parent().parent().find(".error").html("Enter playlist name.");
            }
        } else {
            $("#Create_Playlist").parent().parent().find(".error").html("Choose media files.");
        }
    });
    /**
     * Upload new videos
     */
    $("#Upload_Videos").click(function () {
        window.location.href = siteUrl + "video/add";

    });


    function delPlaylist(tabId) {

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            data: {tabId: tabId},
            url: 'delPlaylist',
            success: function (data) {
                if (data == 1) {
                    $("input[type='checkbox']").prop('checked', false);
                    $('#playlist_list :selected').remove();
                }
            },
            error: function () {
                console.log('in error');
            }
        });
    }
});
//]]>
