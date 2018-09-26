 $(document).ready(function () {
    var records;   
    var urlRecordList = siteUrl + "admin/reminder/email/getList";
    var urlRecordEdit = siteUrl + "admin/reminder/email/update";
    var urlDelete = siteUrl + "admin/reminder/email/delete";       
    var recordsColumn = [
                            {"data": "email"},                            
                            {"data": "subject"},
                            {"data": "name"},             
                            {"data": "sent"},                            
                            {"data": "createdAt"}
                        ];                   
                                          
     records = $('#records').DataTable({
        responsive: true,
        "order": [[1,"desc"]],
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
    
    $(document).on('click', '.all-user-email', function (event) {
        if ($('.all-user-email').is(':checked')) {
            $(".email-name").val("");
            $(".email-name,.email-lbl,.token-wrapper").css("display", "none");
        } else {
            $(".email-name").css("display", "block");
            $(".email-lbl,.token-wrapper").css("display", "inline-block");
        }
    });

    $('#submit').click(function () {
        $('.text-danger').remove();
        var $userStatus = 0;
        if ($('.all-user-email').is(':checked')) {
            $userStatus = '1';
        }
        $.ajax({
            type: "POST",
            url: "/admin/reminder/email/addEmail",
            dataType: "json",
            data: {_token: $_token, userEmail: $('#userEmail:visible').val(), userSubject: $('#userSubject').val(), template_id: $('#template_id').val(), userStatus: $userStatus},
            success: function (response) {
                if (response.message == 'success') {
                    window.location = "/admin/reminder/email";
                }               
            },
            error: function (data) {
                var errors = data.responseJSON;                
                $.each(errors, function (i, val) {                                                          
                    $('#' + i).closest('div').append('<label class="text-danger">' + val + '</label>');
                });
            }
        });
    });
    
    $.getScript('http://cdnjs.cloudflare.com/ajax/libs/select2/3.4.8/select2.min.js', function () {
        $("#userEmail").select2({
            closeOnSelect: false
        });
    });               
});    