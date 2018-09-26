<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.14.0/jquery.validate.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.0/js/responsive.bootstrap.min.js"></script>
<section class="content-header">
    <h1>Contact address </h1>
</section>
<section class="content"                    >
    <div class="                        row">
        <div class="item  col-xs-4 col-lg-4">
            <h3><a href="{{url()}}/company/{{$strCompanyId}}/create/address" class="btn btn-success"><i class="fa fa-plus"></i>Add new address</a></h3>
        </div>
    </div>
    <div class="panel-default">
        <div class="panel-body">

            <div style="border: 1px solid #ddd;padding: 10px;margin-top: 10px;">
                <table id="recordGrid" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>

                            <th>Nickname</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Edit</th>
                            <th>View</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Nickname</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Edit</th>
                            <th>View</th>
                            <th>Delete</th>
                        </tr>
                    </tfoot>
                </table>
            </div> <!--/.tab-content-->
        </div> <!--/.media-body-->

    </div> <!--/.media-->
</section>
<script type="text/javascript">
$(document).ready(function () {
    var comapnyId = <?php echo $strCompanyId ?>;
    var siteURL = '<?php echo url() ?>';
    console.log(siteURL);
    var recordsColumn = [
        {"data": "nickname"},
        {"data": "email"},
        {"data": "phone"},
        {"data": "edit", "bSortable": false},
        {"data": "view", "bSortable": false},
        {"data": "delete", "bSortable": false}
    ];
    var parameters = "{'companyId':'" + comapnyId + "'}";
    console.log('document ready');
    $('#recordGrid').DataTable({
        responsive: true,
        "autoWidth": false,
        "processing": true,
        "serverSide": true,
        "stateSave": true,
        "ajax": siteURL + '/company/' + comapnyId + '/getAddresses',
        "columns": recordsColumn,
        "columnDefs": [{
                "targets": [3, 4, 5], // Column which need to update. Index start from zero
                "render": function (data, type, row, meta) {
                    if (type === 'display') {

                        if (meta.col == 3) {
                            data = '<a href="' + siteURL + '/company/' + comapnyId + '/address/' + data + '" class="inline-link btn-edit glyphicon glyphicon-pencil" title="Edit">Edit</a>';
                        }
                        if (meta.col == 4) {
                            data = '<a href="' + siteURL + '/company/' + comapnyId + '/address/' + data + '/view" class="inline-link glyphicon glyphicon-eye-open" title="View">View</a>';
                        }
                        if (meta.col == 5) {
                            data = '<a class="inline-link deleteRecord glyphicon glyphicon-trash" href="#" value="' + data + '" title="Delete">Delete</a>';
                        }
                    }
                    return data;
                }
            }]
    });
});
</script>
