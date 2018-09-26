/*
 *
 * Removes all messages
 */
function remove_msgs() {
    if ($(".msg-success").length > 0) {
        $(".msg-success").remove();
    }
    if ($(".msg-failure").length > 0) {
        $(".msg-failure").remove();
    }
}
/*
 * make text bolder
 */
function add_bolder(param) {
    $("a").removeClass('bolder');
    $(param).addClass("bolder");
}

bkLib.onDomLoaded(function () {
    new nicEditor().panelInstance('template_content');
});

$('#email_input_id').tokenfield({minWidth: 250});
$('#phone_input_id').tokenfield({minWidth: 250});
$('#push_input_id').tokenfield({minWidth: 250});
$(".phone-part").css("display", "none");
$(".email-part").css("display", "none");
$(".sms-part").css("display", "none");
$(".push-part").css("display", "none");

/*
 * Save Template data
 */
$(document).on('click', '.save-template', function (event) {

    var templateName = $(".template-name").val();
    var nicE = new nicEditors.findEditor('template_content');
    templateContent = nicE.getContent();
    if (templateContent == "<br>") {  //by default the editior holds <br> when gets empty, so remove it
        templateContent = '';
    }

    var formData = new FormData();
    formData.append('templateName', templateName);
    formData.append('templateContent', templateContent);
    formData.append('_token', $_token);

    $.ajax({
        url: 'reminders/addTemplate',
        type: "POST",
        data: formData,
        dataType: "json",
        processData: false, // Don't process the files
        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
        success: function (data) {
            if (data.status == 'success') {
                remove_msgs();
                $(".template-part").prepend("<div class='msg-success'>" + data.successMessage + "</div>");
            } else {
                $(".template-part").before("<div class='msg-failure'>" + data.errorMessage + "</div>");
            }
        },
        error: function (data) {
            remove_msgs();
            var errors = $.parseJSON(data.responseText);
            $.each(errors, function (index, value) {
                $(".template-part").before("<div class='msg-failure'>" + value + "</div>");
            })
        }
    });
})
/*
 * Initilize all elements when clicked on Template link
 */
$(document).on('click', '.template-cls', function (event) {
    remove_msgs();
    $(".template-part").css("display", "block");
    $(".email-part,.sms-part,.push-part,.phone-part").css("display", "none");
    add_bolder(this);
    $("input").val("");
    $(".nicEdit-main").text("");
})

/*
 * /*
 * Initilize all elements when clicked on Email link and fetch template list
 */
$(document).on('click', '.email-cls', function (event) {
    $("input").val("");
    $('.all-user-email').prop('checked', false);
    $(".email-part .token").detach();

    add_bolder(this);
    remove_msgs();
    $(".template-part,.sms-part,.push-part,.phone-part").css("display", "none");
    $(".email-part").css("display", "block");
    $(".all-user-email").prop('checked', false);
    $(".email-lbl,.token-wrapper,.subject-lbl").css("display", "inline-block");

    $.ajax({// get template list
        url: 'reminders/getTemplateList',
        type: "GET",
        data: {_token: $_token},
        dataType: "json",
        success: function (data) {
            if (data.status == 'success') {
                $(".temp-list option").remove();
                $(".temp-list").append(data.html);
            }
        }
    });
})

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
})

/*
 * Initilize all elements when clicked on Push Notification link
 */
$(document).on('click', '.push_notification_cls', function (event) {
    $('.all-user-push').prop('checked', false);
    $(".push-part .token").detach();
    $(".push-part textarea").val("");
    $(".push-part input").val("");
    add_bolder(this);
    remove_msgs();
    $(".template-part,.email-part,.sms-part,.phone-part").css("display", "none");
    $(".push-lbl,.token-wrapper-push").css("display", "inline-block");
    $(".push-part").css("display", "block");
})


/*
 * Initilize all elements when clicked on All user checkbox in Email section
 */
$(document).on('click', '.all-user-email', function (event) {
    if ($('.all-user-email').is(':checked')) {
        $(".email-name").val("");
        $(".email-name,.email-lbl,.token-wrapper").css("display", "none");
    } else {
        $(".email-name").css("display", "block");
        $(".email-lbl,.token-wrapper").css("display", "inline-block");
    }
})

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
})

/*
 * Initilize all elements when clicked on All user checkbox in PUSH section
 */
$(document).on('click', '.all-user-push', function (event) {
    if ($('.all-user-push').is(':checked')) {
        $(".push-name").val("");
        $(".push-name,.push-lbl,.token-wrapper-push").css("display", "none");
    } else {
        $(".push-name").css("display", "block");
        $(".push-lbl,.token-wrapper-push").css("display", "inline-block");
    }
})

/*
 * Get username and phoneno when putting user name in phoneno input text field and enter under "EMAIL" section. Show formatted result as phonno(username).
 * Hanlde validation
 */
$(document).on("tokenfield:createdtoken", "#email_input_id", function (e) {
    $.ajax({
        url: 'reminders/getUserInfo',
        type: "GET",
        data: {user: e.attrs.value, action: 'email', _token: $_token},
        dataType: "json",
        success: function (data) {
            if (data.status == 'success' && data.html != "") {
                $(e.relatedTarget).children().first().text(data.html);
            } else {
                $(e.relatedTarget).addClass('invalid');
            }
        }
    });
})

/*
 * Get username and phoneno when putting user name in phoneno input text field and enter under "SMS" section. Show formatted result as phonno(username).
 * Hanlde validation
 */
$(document).on("tokenfield:createdtoken", "#phone_input_id", function (e) {
    $.ajax({
        url: 'reminders/getDeviceInfo',
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
})

/*
 * Get username and phoneno when putting user name in phoneno input text field and enter under "Push Notification" section. Show formatted result as phonno(username).
 * Hanlde validation
 */
$(document).on("tokenfield:createdtoken", "#push_input_id", function (e) {
    $.ajax({
        url: 'reminders/getDeviceInfo',
        type: "GET",
        data: {user: e.attrs.value, action: 'phone', _token: $_token},
        dataType: "json",
        success: function (data) {
            if (data.status == "success") {
                $(e.relatedTarget).children().first().text(data.html);
            } else {
                $(e.relatedTarget).addClass('invalid');
            }
        }
    });
})

/*
 * Save email data on clicking SAVE button in Email section
 */
$(document).on('click', '.save-email', function (event) {

    var subject = $("#subject_input_id").val();
    var templateId = $(".temp-list").val();
    var userStatus = '';
    if ($('.all-user-email').is(':checked')) {
        userStatus = '1';
    }
    var str = "";
    var error = "";
    $(".email-part .token-label").each(function (key, val) {        // get all token data from token field
        if ($(this).closest('.invalid').length == 0) {
            str = str + $(this).text() + ",";
        } else {
            error = '1';
        }
    });

    if (error == '1') {
        remove_msgs();
        $(".email-part").before("<div class='msg-failure'>Please remove Invalid emails</div>");
        return false;
    }

    var formData = new FormData();
    formData.append('templateId', templateId);
    formData.append('userStatus', userStatus);
    formData.append('userSubject', subject);
    formData.append('userEmail', str);
    formData.append('_token', $_token);

    $.ajax({
        url: 'reminders/addEmail',
        type: "POST",
        data: formData,
        dataType: "json",
        processData: false, // Don't process the files
        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
        success: function (data) {
            if (data.status == "success") {
                remove_msgs();
                $(".email-part").prepend("<div class='msg-success'>" + data.successMessage + "</div>");
            } else {
                $(".email-part").before("<div class='msg-failure'>" + data.errorMessage + "</div>");
            }
        },
        error: function (data) {
            var errors = $.parseJSON(data.responseText);
            remove_msgs();
            $.each(errors, function (index, value) {
                $(".email-part").before("<div class='msg-failure'>" + value + "</div>");
            })
        }});
})


/*
 * Save sms data on clicking SAVE button in SMS section
 */
$(document).on('click', '.save-sms', function (event) {
    var sms_text = $("#sms_content").val();
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
})

/*
 * Save push notification data on clicking SAVE button in Push Notification section
 */

$(document).on('click', '.save-push', function (event) {

    var notification_title = $(".notification-title").val();
    var notification_text = $("#push_content").val();
    var fileName = $("#fileName").val();
    var userStatus = '';
    if ($('.all-user-push').is(':checked')) {
        userStatus = '1';
    }
    var str = "";
    var error = "";
    $(".push-part .token-label").each(function (index) {                // get all token data from token field
        if ($(this).closest('.invalid').length == 0) {
            str = str + $(this).text() + ",";
        } else {
            error = '1';
        }
    });

    if (error == '1') {
        remove_msgs();
        $(".push-part").before("<div class='msg-failure'>Please remove Invalid phone no</div>");
        return false;
    }

    var formData = new FormData();
    formData.append('userStatus', userStatus);
    formData.append('notificationUserPhoneNo', str);
    formData.append('notificationTitle', notification_title);
    formData.append('notificationText', notification_text);
    formData.append("fileName", fileName);
    formData.append('_token', $_token);

    $.ajax({
        url: 'reminders/addPushNotification',
        type: "POST",
        data: formData,
        dataType: "json",
        processData: false, // Don't process the files
        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
        success: function (data) {
            if (data.status == "success") {
                remove_msgs();
                $(".push-part").prepend("<div class='msg-success'>" + data.successMessage + "</div>");
            } else {
                $(".push-part").before("<div class='msg-failure'>" + data.errorMessage + "</div>");
            }
        },
        error: function (data) {
            var errors = $.parseJSON(data.responseText);
            remove_msgs();
            $.each(errors, function (index, value) {
                $(".push-part").before("<div class='msg-failure'>" + value + "</div>");
            })
        }
    });

})

$.getScript('http://cdnjs.cloudflare.com/ajax/libs/select2/3.4.8/select2.min.js', function () {
    $("#userEmail").select2({
        closeOnSelect: false
    });
});
