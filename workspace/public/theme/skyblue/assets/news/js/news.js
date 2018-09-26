


$().ready(function () {
    $("#showContentDiv").click(function () {
        $("#fromDiv").toggle(1600);
    });
    $(".cancel_btn").click(function () {
        $("#name").val('');
        $("#fromDiv").toggle(1600);
        tinyMCE.activeEditor.setContent('');
        $("#newsCategoryId").val('');
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


function validataNewsForm() {
    var name = $('#name').val();

    var newsCategoryId = $('#newsCategoryId').val();
    if (name == '') {
        $('#page').addClass('active');
        $('#tab1').addClass('tab-pane active in col-sm-8');
        $('#tab3').removeClass('active');
        $('#setting').removeClass('active');
        return false;
    }
    if (newsCategoryId == '') {
        $('#setting').addClass('active');
        $('#tab3').addClass('tab-pane active in col-sm-8');
        $('#tab1').removeClass('active');
        $('#page').removeClass('active');
        return false;
    }

}
$(document).ready(function () {
    var records;
    var urlRecordList = siteUrl + "admin/news/getList";
    var urlDelete = siteUrl + "admin/news/delete";
    var urlRecordPublish = siteUrl + "admin/news/updateNewsStatus";
    var urlRecordEdit = siteUrl + "admin/news/edit";
    /* column name should be match with database table field names*/
    var recordsColumn = [
        {"data": "name"},
        {"data": "publish"},
        {"data": "edit", "bSortable": false},
        {"data": "delete", "bSortable": false}
    ];

    $.fn.publishRecord = function (obj) {
        var id = obj.attr('pid');
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: urlRecordPublish,
            type: "POST",
            data: {newsId: id},
            success: function (data) {
                if (data) {
                    obj.html(data);
                }
            }
        });
    }

    $.fn.editRecord = function (obj) {
        var id = obj.attr('eid');
        window.location.href = urlRecordEdit + '/' + id;
    }

    $.fn.deleteRecord = function (recordId, obj) {
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: urlDelete,
            type: "POST",
            data: {newsId: recordId},
            success: function (data) {
                $.fn.removeRow(obj);
            }
        });
    }

    $.fn.updateRecords = function () {

        $('#records tbody').on('click', '.clsPublish', function () {
            $.fn.publishRecord($(this));
        });

        $('#records tbody').on('click', '.clsEdit', function () {
            $.fn.editRecord($(this));
        });

        $('#records tbody').on('click', '.clsDelete', function () {
            var recordId = $(this).attr('did');
            BootstrapDialog.confirm({
                title: 'WARNING',
                message: 'Are you sure you want to delete this record?',
                type: BootstrapDialog.TYPE_DANGER,
                closable: true,
                draggable: true,
                btnCancelLabel: 'Cancel',
                btnOKLabel: 'Ok',
                callback: function (result) {
                    if (result) {
                        $.fn.deleteRecord(recordId, $(this).parents('tr'));
                        BootstrapDialog.show({message: 'Record deleted successfully!'});
                    }
                }
            });
        });
    }

    $.fn.removeRow = function (recordIndex) {
        // Get previous pagination number
        var previousPagination = parseInt($(".paginate_button.current").data("dt-idx")) - 1;

        // Decide to redraw or not based on the presence of `.deleteBtn` elements.
        var doIdraw = false;
        if ($(".clsDelete").length == 1) {
            doIdraw = true;
        }
        // If the page redraws and a previous pagination existed (except the first)
        if (previousPagination > 1 && doIdraw) {
            var previousPage = $(document).find("[data-dt-idx='" + (previousPagination) + "']").click();
        }
        else {
            records.row(recordIndex).remove().draw(doIdraw);
        }
    }

    records = $('#records').DataTable({
        responsive: true,
        "autoWidth": false,
        "processing": true,
        "serverSide": true,
        "stateSave": true,
        "ajax": urlRecordList,
        "columns": recordsColumn,
        "initComplete": function (settings, j) {
            $.fn.updateRecords();
        },
        "columnDefs": [{
                "targets": [1, 2, 3], // Column which need to update. Index start from zero
                "render": function (data, type, row, meta) {

                    if (type === 'display') {

                        if (meta.col == 1) { /* Its yours 3rd column i.e. status*/
                            switch (data) {
                                case '0':
                                    data = data = "<a class='inline-link clsPublish' href='javascript:void(0)' pid='" + row.edit + "' >Unpublished</a>";
                                    break;
                                case '1':
                                    data = data = "<a class='inline-link clsPublish' href='javascript:void(0)' pid='" + row.edit + "' >Published</a>";
                                    break;
                            }
                        }
                        if (meta.col == 2) {  /* Its yours 3rd column i.e. status*/
                            data = "<a class='inline-link glyphicon glyphicon-pencil clsEdit' href='javascript:void(0)' eid='" + data + "'></a>";
                        }
                        if (meta.col == 3) {
                            data = "<a class='inline-link clsDelete glyphicon glyphicon-trash' href='javascript:void(0)' did='" + data + "'></a>";
                        }
                    }
                    return data;
                }
            }]
    });
});