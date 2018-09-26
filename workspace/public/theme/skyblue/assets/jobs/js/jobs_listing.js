var recordGridColumn = 0;
var nonSorting = [4];
$().ready(function () {
    $('#showJobForm').hide();
    $(".pagination-class").hide();
    $(".pagination-class").slice(0, 10).show(); // select the first ten
    $(window).scroll(function () {
        if ($(window).scrollTop() + window.innerHeight == $(document).height()) {
            $(".pagination-class:hidden").slice(0, 10).fadeIn("slow");
        }
    });

    $('#search').keyup(function () {
        var valThis = $(this).val().toLowerCase();
        $('#list>div').each(function () {
            var text = $(this).text().toLowerCase();
            (text.indexOf(valThis) > -1) ? $(this).show() : $(this).hide();
        });
    });

});
function openApplyJobForm(status) {
    if (status == 1) {
        $('#showJobForm').show('slow');
    } else {
        $('#fullName_error').hide();
        $('#email_error').hide();
        $('#contactNumber_error').hide();
        $('#message_error').hide();
        $('#showJobForm').hide('slow');
    }
}

function validate_jobs_form() {
    var form = document.getElementById("jobApplicationForm");
    var fullName = $("#fullName").val();
    var name_validation = /^[A-Za-z ]+$/;
    var email = $("#email").val();
    var email_validation = /^\w+([\.-]?\ w+)*@\w+([\.-]?\ w+)*(\.\w{2,3})+$/;
    var contactNumber = $("#contactNumber").val();
    var contactNumber_validation = /^(\+?1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/
    var message = $("#message").val();


    form.onsubmit = function () {

        if (fullName == '') {
            $('#fullName_error').show().html('Please enter full name');
            return false;
        } else {
            if (!name_validation.test(fullName)) {
                $('#fullName_error').show().html('Please enter valid full name');
                return false;
            } else {
                $('#fullName_error').hide();
            }
        }

        if (email == '') {
            $('#email_error').show().html('Please enter email');
            return false;
        }
        else {
            if (!email_validation.test(email)) {
                $('#email_error').show().html('Please enter valid email');
                return false;
            } else {
                $('#email_error').hide();
            }
        }

        if (contactNumber == '') {
            $('#contactNumber_error').show().html('Please enter contact number');
            return false;
        }
        else {
            if (!contactNumber_validation.test(contactNumber)) {
                $('#contactNumber_error').show().html('Please enter valid contact number');
                return false;
            } else {
                $('#contactNumber_error').hide();
            }
        }

        if (message == '') {
            $('#message_error').show().html('Please enter message');
            return false;
        } else {
            $('#message_error').hide();
        }

    }

}

function getCheckBoxValues() {
    var myArray = [];
    $(":checkbox:checked").each(function () {
        myArray.push(this.value);
    });

    var categoryIds = myArray.join(",");
    $.ajax({
        url: siteUrl + 'job/search',
        headers:
                {
                    'X-CSRF-Token': $('input[name="_token"]').val()
                },
        type: 'POST',
        data: {categoryIds: categoryIds},
        success: function (data) {
            var container = $('#pageData');
            if (data) {
                container.html(data);
            }

            $('#showJobForm').hide();
            $(".pagination-class").hide();
            $(".pagination-class").slice(0, 10).show(); // select the first ten
            $(window).scroll(function () {
                if ($(window).scrollTop() + window.innerHeight == $(document).height()) {
                    $(".pagination-class:hidden").slice(0, 10).fadeIn("slow");
                }
            });

        }
    });

}