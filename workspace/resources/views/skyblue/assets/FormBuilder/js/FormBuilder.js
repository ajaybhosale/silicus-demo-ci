$(document).ready(function ($) {
    $('.delete').click(function (e) {
          e.preventDefault();
          delContact($(this).attr('rel'));           
    });
});

 var recordGridColumn = 0;
function delContact(contactId) {   
    
    BootstrapDialog.confirm({
        title: 'WARNING',
        message: 'Are you sure you want to delete this form?',
        type: BootstrapDialog.TYPE_DANGER, // <-- Default value is BootstrapDialog.TYPE_PRIMARY
        closable: true, // <-- Default value is false
        draggable: true, // <-- Default value is false
        btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
        btnOKLabel: 'Ok', // <-- Default value is 'OK',
        btnOKClass: '', // <-- If you didn't specify it, dialog type will be used,
        callback: function(result) {
        // result will be true if button was click, while it will be false if users close the dialog directly.
            if (result) {
                 window.location = siteUrl+"admin/form/remove/" + contactId;
            } else {
                return false;
            }
        }
    });
}

$(window).load(function () {
    if(document.getElementById('fb-template')){
    'use strict';
        var formBuilder = $(document.getElementById('fb-template')).formBuilder();
        $('div#snap .error').remove();

        $("#submit-form").click(function () {
          var formData = formBuilder.data('formBuilder').formData;
           $('div#snap .error').remove();

           if($('[name="form_name"]').val().trim()==''){
               $('[name="form_name"]').closest('.form-group').append('<span class="error">Form Name Required.</span>');
               return false;
           }
           if($('#fb-template').val().trim()=='No form data.' || $('#fb-template').val().trim()==''){
               $('#fb-template').closest('.form-builder').append('<span class="error">Form HTML Required.</span>');
               return false;
           }

            var formRenderOpts = {
                render: false,
                formData: formData
            };
            var markup = new FormRenderFn(formRenderOpts).markup;

            if($('[name="id"]').val() && markup.trim() != 'No form data.'.trim()){
               $('[name="html"]').val(markup);
            }else if($('[name="id"]').val()==0){
                $('[name="html"]').val(markup);
            }

            html2canvas($("#frmb-0"), {
                onrendered: function (canvas) {
                    //theCanvas = canvas;
                    //document.body.appendChild(canvas);

                    canvas.toBlob(function (blob) {
                        //saveAs(blob, "Dashboard.png");
                        var data = new FormData();
                        data.append('file', blob);
                        data.append('file-name', $('[name="form_name"]').val() + '.png');
                        data.append('_token', $('[name="_token"]').val());
                        $.ajax({
                            url: siteUrl + "admin/form/save-file",
                            type: 'POST',
                            data: data,
                            contentType: false,
                            processData: false,
                            success: function (data) {
                                //alert("boa!");
                            },
                            error: function () {
                                //alert("not so boa!");
                            }
                        });
                    });
                }
            });             
           $('#snap form').submit();
        });
    }
});

