 $(document).ready(function () {
    var records;   
    var urlRecordList = siteUrl + "admin/reminder/sms/getList";
    var urlRecordEdit = siteUrl + "admin/reminder/sms/update";
    var urlDelete = siteUrl + "admin/reminder/sms/delete";       
    var recordsColumn = [
                            {"data": "mobile"},                            
                            {"data": "content"},
                            {"data": "sent"},                                                             
                            {"data": "createdAt"}
                        ];                   
                                          
     records = $('#records').DataTable({
        responsive: true,
        "order": [[3,"desc"]],
        "autoWidth": false,
        "processing": true,
        "serverSide": true,
        "stateSave": true,
        "ajax": urlRecordList,
        "columns": recordsColumn,   
         "columnDefs": [{
                "targets": [3],    // Column which need to update. Index start from zero
                "render": function (data, type, row, meta) {
                    if (type === 'display') {                        
                        if (meta.col == 3) {
                            switch (data) {
                                case 1:
                                    data = "Sent";
                                    break;
                                case 0:
                                    data = "Pending";
                                    break;                               
                            }
                        }                                                                                                                   
                    }                                      
                    return data;
                }
            }]
    });               
    
    $("#showContentDiv").click(function () {
        $("#fromDiv").toggle(1600);
    });         

     
/*
 * Initilize all elements when clicked on SMS link
 */
$(document).on('click', '.sms-cls', function (event) {
    $('.all-user-sms').prop('checked', false);
    $(".sms-part .token").detach();
    $("textarea").val("");
    add_bolder(this);
    remove_msgs();
    $(".template-part,.email-part,.push-part,.phone-part").css("display", "none");
    $(".sms-lbl,.token-wrapper-sms").css("display", "inline-block");
    $(".sms-part").css("display", "block");
    activate_link(this);
});     
    
/*
 * Initilize all elements when clicked on All user checkbox in SMS section
 */
$(document).on('click', '.all-user-sms', function (event) {
    if ($('.all-user-sms').is(':checked')) {
        $(".sms-name").val("");
        $(".sms-name,.sms-lbl,.token-wrapper-sms").css("display", "none");
    } else {
        $(".sms-name").css("display", "block");
        $(".sms-lbl,.token-wrapper-sms").css("display", "inline-block");
    }
});    

/*
 * Save sms data on clicking SAVE button in SMS section
 */
$(document).on('click', '.save-sms', function (event) {
    var sms_text = tinyMCE.get('sms_content').getContent();
    var userStatus = '';
    if ($('.all-user-sms').is(':checked')) {
        userStatus = '1';
    }

    var str = "";
    var error = "";
    $(".sms-part .token-label").each(function (key, val) {          // get all token data from token field
        if ($(this).closest('.invalid').length == 0) {
            str = str + $(this).text() + ",";
        } else {
            error = '1';
        }
    });

    if (error == '1') {
        remove_msgs();
        $(".sms-part").before("<div class='msg-failure'>Please remove Invalid phone no</div>");
        return false;
    }

    var formData = new FormData();
    formData.append('smsText', sms_text);
    formData.append('userStatus', userStatus);
    formData.append('smsUserPhoneNo', str);
    formData.append('_token', $_token);

    $.ajax({
        url: 'reminders/addSms',
        type: "POST",
        data: formData,
        dataType: "json",
        processData: false, // Don't process the files
        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
        success: function (data) {
            if (data.status == "success") {
                remove_msgs();
                $(".sms-part").prepend("<div class='msg-success'>" + data.successMessage + "</div>");
            } else {
                $(".sms-part").before("<div class='msg-failure'>" + data.errorMessage + "</div>");
            }
        },
        error: function (data) {
            var errors = $.parseJSON(data.responseText);
            remove_msgs();
            $.each(errors, function (index, value) {
                $(".sms-part").before("<div class='msg-failure'>" + value + "</div>");
            })
        }
    });
});

/*
 * Get username and phoneno when putting user name in phoneno input text field and enter under "SMS" section. Show formatted result as phonno(username).
 * Hanlde validation
 */
$(document).on("tokenfield:createdtoken", "#phone_input_id", function (e) {
    $.ajax({
        url: siteUrl + 'admin/reminder/getDeviceInfo',
        type: "GET",
        data: {user: e.attrs.value, action: 'phone', _token: $_token},
        dataType: "json",
        success: function (data) {
            if (data.status == "success") {
                $(e.relatedTarget).children().first().text(data.html);
            } else {
                $(e.relatedTarget).addClass('invalid')
            }

        }
    });
});

 tinymce.init({
        selector: 'textarea',
        height: 200,
        width: 670,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table contextmenu paste code'
        ],
        toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        content_css: []
    });
                
});    