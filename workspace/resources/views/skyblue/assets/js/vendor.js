
$(document).ready(function () {    
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
                            url: siteUrl+'/admin/vendor/' + recordId,
                            success: function (data) {                                
                                $("tr#vendor" + recordId).remove();
                                $('#recordGrid').DataTable().ajax.reload(); //to refresh datatables
                                $('div#message').html('<div class="alert alert-success"><a aria-label="close" data-dismiss="alert" class="close" href="#">×</a><strong>Success!</strong> Successfully deleted');
                            },
                            error: function (data) {
                                console.log('Error:', data);
                            }
                        });
                    }
            }
        });
    });
    var recordsColumn = [
                            {"data": "name"},
                            {"data": "status"},
                            {"data": "url"},                            
                            {"data": "edit", "bSortable": false},
                            {"data": "delete", "bSortable": false}
                        ];
    $('#recordGrid').DataTable({
        responsive: true,
        "autoWidth": false,
        "processing": true,
        "serverSide": true,
        "stateSave": true,
        "ajax": 'vendors/getList',
        "columns": recordsColumn,      
        "columnDefs": [{
                "targets": [3,4],    // Column which need to update. Index start from zero
                "render": function (data, type, row, meta) {
                   if (type === 'display') {                        
                        
                        if (meta.col == 3) {                            
                            data = '<a href="vendor/'+data+'/edit" class="inline-link btn-edit glyphicon glyphicon-pencil" title="Edit"></a>';
                        }
                        if (meta.col == 4) {
                            data = '<a class="inline-link deleteRecord glyphicon glyphicon-trash" href="#" value="'+data+'" title="Delete"></a>';                            
                        }                         
                    }                                      
                    return data;
                }
            }]
    });   
});
