var recordGridColumn = 0;
var nonSorting = [];
function delContact(url) {
    BootstrapDialog.confirm({
        title: 'CONFIRM',
        message: 'Are you sure you want to delete this contact?',
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

function showView(id) {
    $.ajax({
        url: siteUrl + 'contacts/viewContact',
        headers:
                {
                    'X-CSRF-Token': $('input[name="_token"]').val()
                },
        type: 'POST',
        data: {id: id},
        success: function (data) {
            var container = $('#showViewDiv');
            if (data) {
                container.html(data);
            }
        }
    });
}

function validate_contact_form() {

    var firstName = $("#firstName").val();
    var lastName = $("#lastName").val();
    var email = $("#email").val();
    var address = $("#address").val();
    var phone = $("#phone").val();
    var company = $("#company").val();
    var website = $("#website").val();
    var facebookLink = $("#facebookLink").val();
    var twitterLink = $("#twitterLink").val();
    var linkedinLink = $("#linkedinLink").val();
    var gplusLink = $("#gplusLink").val();
    var profession = $("#profession").val();
    var fileName = $("#fileName").val();

    var form = document.getElementById("addContactForm");
    var re = /^(http[s]?:\/\/){0,1}(www\.){0,1}[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,5}[\.]{0,1}/;
    var name_validation = /^[A-Za-z ]+$/;
    var email_validation = /^\w+([\.-]?\ w+)*@\w+([\.-]?\ w+)*(\.\w{2,3})+$/;
    form.onsubmit = function () {
        if (firstName == '') {
            $('#firstname_error').show();
            return false;
        } else {
            if (!name_validation.test(firstName)) {
                $('#firstname_error').show();
                return false;

            } else {
                $('#firstname_error').hide();
                return true;
            }
        }

        if (lastName == '') {
            $('#lastname_error').show();
            return false;
        } else {
            if (!name_validation.test(lastName)) {
                $('#lastname_error').show();
                return false;

            } else {
                $('#lastname_error').hide();
                return true;
            }
        }

        if (email != '') {
            if (!email_validation.test(firstName)) {
                $('#email_error').show();
                return false;

            } else {
                $('#email_error').hide();
                return true;
            }
        }


        if (website != '') {
            if (!re.test(website)) {
                $('#website_error').show();
                return false;
            }
        } else {
            $('#website_error').hide();
        }

        if (facebookLink != '') {
            if (!re.test(facebookLink)) {
                $('#facebook_error').show();
                return false;
            }
        } else {
            $('#facebook_error').hide();
        }
        if (twitterLink != '') {
            if (!re.test(twitterLink)) {
                $('#twitter_error').show();
                return false;
            }
        } else {
            $('#twitter_error').hide();
        }
        if (linkedinLink != '') {
            if (!re.test(linkedinLink)) {
                $('#linkedin_error').show();
                return false;
            }
        } else {
            $('#linkedin_error').hide();
        }
        if (gplusLink != '') {
            if (!re.test(gplusLink)) {
                $('#gplus_error').show();
                return false;
            }
        } else {
            $('#gplus_error').hide();
        }
    }
}

function saveView() {

    var id = $("#userId").val();
    var groupId = $("#groupId").val();
    var firstName = $("#firstName").html();
    var lastName = $("#lastName").html();
    var email = $("#email").html();
    var address = $("#address").html();
    var phone = $("#phone").html();
    var company = $("#company").html();
    var website = $("#website").html().replace(/<br>/gi, "\n");
    var facebookLink = $("#facebookLink").html();
    var twitterLink = $("#twitterLink").html();
    var linkedinLink = $("#linkedinLink").html();
    var gplusLink = $("#gplusLink").html();
    var profession = $("#profession").html();
    var fileName = $("#fileName").val();

    var re = /^(http[s]?:\/\/){0,1}(www\.){0,1}[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,5}[\.]{0,1}/;
    if (website != '') {
        if (!re.test(website)) {
            $('#website_error').show();
            return false;
        }
    } else {
        $('#website_error').hide();
    }

    if (facebookLink != '') {
        if (!re.test(facebookLink)) {
            $('#facebook_error').show();
            return false;
        }
    } else {
        $('#facebook_error').hide();
    }
    if (twitterLink != '') {
        if (!re.test(twitterLink)) {
            $('#twitter_error').show();
            return false;
        }
    } else {
        $('#twitter_error').hide();
    }
    if (linkedinLink != '') {
        if (!re.test(linkedinLink)) {
            $('#linkedin_error').show();
            return false;
        }
    } else {
        $('#linkedin_error').hide();
    }
    if (gplusLink != '') {
        if (!re.test(gplusLink)) {
            $('#gplus_error').show();
            return false;
        }
    } else {
        $('#gplus_error').hide();
    }

    $.ajax({
        url: siteUrl + 'contacts/updateContactAjax',
        headers:
                {
                    'X-CSRF-Token': $('input[name="_token"]').val()
                },
        type: 'POST',
        data: {id: id, firstName: firstName, lastName: lastName, email: email, address: address, phone: phone, company: company, website: website, facebookLink: facebookLink, twitterLink: twitterLink, linkedinLink: linkedinLink, gplusLink: gplusLink, profession: profession, groupId: groupId, fileName: fileName},
        success: function (data) {
            var container = $('#contactList');
            showView(id)
            if (data) {
                container.html(data);
            }
        },
        error: function (data) {
            $("#valfirstName").html(data.responseJSON.firstName);
            $("#vallastName").html(data.responseJSON.lastName);
            $("#valemail").html(data.responseJSON.email);
            $("#valphone").html(data.responseJSON.phone);

        }
    });
}
$().ready(function () {

    var default_contact = $('#default_contact').val();
    showView(default_contact);

    $('#search').keyup(function () {
        var valThis = $(this).val().toLowerCase();

        $('#navList>div').each(function () {
            var text = $(this).text().toLowerCase();
            (text.indexOf(valThis) > -1) ? $(this).show() : $(this).hide();
        });
    });
});