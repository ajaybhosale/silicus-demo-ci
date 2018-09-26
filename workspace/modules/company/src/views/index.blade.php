@extends($theme.'.layouts.app')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/css/bootstrap-dialog.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.bootstrap.min.css">
@section('content')
<div class="container mainblock">
    <div class="content-header">
        <h1 style="color: #000">Company address </h1>
    </div>
    <div class="content">
        <div class="                        row">
            <div class="item  col-xs-4 col-lg-4">
                <h3><a href="{{$url}}/company/{{$companyId}}/address/add" class="btn btn-success"><i class="fa fa-plus"></i>Add new address</a></h3>
            </div>
        </div>
        <div class="panel-default">
            <div class="panel-body">

                <div style="border: 1px solid #ddd;padding: 10px;margin-top: 10px;">
                    <table id="recordGrid" class="table table-striped table-hover table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>

                                <th>Nickname</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Nickname</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </tfoot>
                    </table>
                </div> <!--/.tab-content-->
            </div> <!--/.media-body-->
            <meta name="_token" content="{!! csrf_token() !!}" />
        </div> <!--/.media-->
    </div> <!--/.media-->
</div>
@endsection
<script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.14.0/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.0/js/responsive.bootstrap.min.js"></script>



<script type="text/javascript">
$(document).ready(function () {
    var comapnyId = <?php echo $companyId ?>;
    var siteURL = '<?php echo $url ?>';
    console.log(siteURL);
    var recordsColumn = [
        {"data": "nickname"},
        {"data": "email", "bSortable": false},
        {"data": "phone", "bSortable": false},
        {"data": "edit", "bSortable": false},
        {"data": "delete", "bSortable": false}
    ];
    var parameters = "{'companyId':'" + comapnyId + "'}";
    $('#recordGrid').DataTable({
        language: {
            searchPlaceholder: "Search by email..."
        },
        responsive: true,
        "autoWidth": false,
        "processing": true,
        "serverSide": true,
        "stateSave": true,
        "ajax": siteURL + '/company/' + comapnyId + '/getAddresses',
        "columns": recordsColumn,
        "columnDefs": [{
                "targets": [3, 4], // Column which need to update. Index start from zero
                "render": function (data, type, row, meta) {
                    if (type === 'display') {

                        if (meta.col == 3) {
                            data = '<a href="' + siteURL + '/company/' + comapnyId + '/address/' + data + '" class="inline-link btn-edit glyphicon glyphicon-pencil" title="Edit"></a>';
                        }
                        if (meta.col == 4) {
                            data = '<a class="inline-link deleteRecord glyphicon glyphicon-trash" href="#" value="' + data + '" title="Delete"></a>';
                        }
                    }
                    return data;
                }
            }]
    });
    $(document).on('click', '.deleteRecord', function (event) {
        var currentTarget = event.target;
        var thisObj = this;
        event.preventDefault();
        var recordId = $(this).attr('value');
        bootbox.confirm({
            message: 'Do you really want to delete this record?',
            buttons: {
                'confirm': {
                    label: 'Yes',
                },
                'cancel': {
                    label: 'No',
                }
            },
            callback: function (result) {
                /* if (result)
                 {
                 $(thisObj).parent().parent().find('form').submit();
                 */
                if (result)
                {
                    var id = $(this).attr('value');
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    })
                    $.ajax({
                        type: "DELETE",
                        url: siteUrl + '/company/' + comapnyId + '/address/' + recordId,
                        success: function (data) {
                            // $("tr#banner" + recordId).remove();
                            alert('Company address deleted successfully');
                            $('#recordGrid').DataTable().ajax.reload(); //to refresh datatables
                            // $('div#message').html('<div class="alert alert-success"><a aria-label="close" data-dismiss="alert" class="close" href="#">×</a><strong>Success!</strong> Successfully deleted');
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            alert('Error while deleting company address');
                        }
                    });
                }
            }
        });
    });
});
</script>

