$(document).ready(function () {

    $('iframe#upload_target').load(function () {
        var bn = $('iframe#upload_target').contents().find('#filesMessage').html();
        $('#iframeText').html(bn);
    });



    $.fn.editable.defaults.mode = 'inline';

    $.fn.editable.defaults.params = function (params) {
        params._token = $("meta[name=csrf-token]").attr("content");
        return params;
    };

    $('#username').editable({
        validate: function (value) {
            if ($.trim(value) == '')
                return 'Value is required.';
        }
    });

    $('#gender').editable({
        source: [
            {value: 'male', text: 'male'},
            {value: 'female', text: 'female'}
        ]
    });

    $('#dateOfBirth').editable({
        format: 'YYYY-MM-DD',
        viewformat: 'DD/MM/YYYY',
        template: 'D/MMMM/YYYY',
        combodate: {
            minYear: 1915,
            maxYear: 2016,
            minuteStep: 1
        }
    });

    $('#position').editable();
    $('#company').editable();
    $('#phone').editable();
    $('#mobilePhone').editable();
    $('#address').editable();
    $('#facebook').editable();
    $('#twitter').editable();
    $('#google').editable();
    $('#biography').editable();
    $('#aboutMe').editable();
    $('#news_letters').editable({source: [
            {value: '0', text: 'No'},
            {value: '1', text: 'Yes'}
        ],
        validate: function (value) {
            if ($.trim(value) == '')
                return 'Value is required.';
        }
    });
    /*signature code start*/
    $('#captureSignature').signature({syncField: '#signature'});
    $('#clear2Button').click(function () {
        $('#captureSignature').signature('clear');
    });
    $('#captureSignature').signature('draw', $('#signature').val());
    $('#jsonSubmit').click(function () {
        $.ajax({
            method: "POST",
            url: "/profile/update",
            data: {name: "signature", value: $('#signature').val(), _token: $('meta[name="csrf-token"]').attr('content')}
        });
    });
    $('#download').click(function () {
        html2canvas($("#captureSignature"), {
            onrendered: function (canvas) {
                var imgData = canvas.toDataURL(
                        'image/png');
                var doc = new jsPDF('p', 'mm');
                doc.addImage(imgData, 'PNG', 10, 10);
                doc.save('profile.pdf');
            }
        });
    });
    /*signature code end*/
    var updateProfile = function (key, value, field) {
        value = $.trim(value);
        if (value != '') {
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: siteUrl + 'profile/update',
                type: 'POST',
                data: {fieldValue: value, field: field},
                success: function (data) {
                }
            });
        }
    }

    //https://github.com/kartik-v/bootstrap-fileinput
    var btnCust = '';
    var fff = $("#avatar").fileinput({
        overwriteInitial: true,
        maxFileSize: 1500,
        showClose: false,
        showCaption: true,
        showBrowse: true,
        browseOnZoneClick: true,
        showRemove: false,
        removeLabel: '',
        removeIcon: '<i class="glyphicon glyphicon-remove"></i>',
        removeTitle: 'Cancel or reset changes',
        elErrorContainer: '#kv-avatar-errors-2',
        msgErrorClass: 'alert alert-block alert-danger',
        defaultPreviewContent: '<img src="' + avatarPath + '" alt="Your Avatar" style="width:160px"><h6 class="text-muted">Click to select</h6>',
        layoutTemplates: {main2: '{preview} ' + btnCust + ' {remove} {browse}'},
        allowedFileExtensions: ["jpg", "png", "gif"]
    });
});
