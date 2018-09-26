var recordGridColumn = 0;
var nonSorting = [4, 5, 6];
function delPage(id) {
    var url = siteUrl + 'admin/pages/delete/' + id;
    BootstrapDialog.confirm({
        title: 'WARNING',
        message: 'Are you sure you want to delete this page?',
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
    $("#showContentDiv").click(function () {
        $("#fromDiv").toggle();
    });
    tinymce.init({
        selector: 'textarea',
        height: 200,
        width: 815,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table contextmenu paste code'
        ],
        toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        content_css: []
    });
});
function updatePageStatus(id, publish) {
    $.ajax({
        url: siteUrl + '/admin/pages/updatePageStatus',
        headers:
                {
                    'X-CSRF-Token': $('input[name="_token"]').val()
                },
        type: 'POST',
        data: {id: id, publish: publish},
        success: function (data) {
            var container = $('#pageData');
            if (data) {
                container.html(data);
            }
        }
    });
}

function validataPageForm() {
    var name = $('#name').val();
    var slug = $('#slug').val();
    var metaTitle = $('#metaTitle').val();
    var metaKeyword = $('#metaKeyword').val();
    var metaDescription = $('#metaDescription').val();
    var pageCategoryId = $('#pageCategoryId').val();
    if (name == '' || slug == '') {
        $('#page').addClass('active');
        $('#tab1').addClass('tab-pane active in col-sm-8');
        $('#tab2').removeClass('active');
        $('#tab3').removeClass('active');
        $('#meta').removeClass('active');
        $('#setting').removeClass('active');
        return false;
    }
    if (metaTitle == '' || metaKeyword == '' || metaDescription == '') {
        $('#meta').addClass('active');
        $('#tab2').addClass('tab-pane active in col-sm-8');
        $('#tab1').removeClass('active');
        $('#tab3').removeClass('active');
        $('#page').removeClass('active');
        $('#setting').removeClass('active');
        return false;
    }
    if (pageCategoryId == '') {
        $('#setting').addClass('active');
        $('#tab3').addClass('tab-pane active in col-sm-8');
        $('#tab1').removeClass('active');
        $('#tab2').removeClass('active');
        $('#page').removeClass('active');
        $('#meta').removeClass('active');
        return false;
    }

}