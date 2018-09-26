 $(document).ready(function () {
    var records;   
    var urlRecordList = siteUrl + "admin/notification/getList";
    var urlRecordEdit = siteUrl + "admin/notification/mark-as-read";
    var urlDelete = siteUrl+"admin/notification/delete";       
    var recordsColumn = [
                            {"data": "text"},
                            {"data": "type"},
                            {"data": "createdAt"},
                            {"data": "status"},                            
                            {"data": "delete", "bSortable": false}
                        ];                   
    
    $.fn.deleteRecord = function (recordId, obj) {                
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url : urlDelete,
            type: "POST",
            data : "id="+recordId,
            success: function(){
                $.fn.removeRow(obj);
            }
        });
    }
    
    $.fn.editRecord = function (obj) {
        
        var id = obj.attr('eid');
        var prvAttr = obj.attr('has-read');
        
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url : urlRecordEdit,
            type: "POST",
            data: {id: id, publish: prvAttr},
            success: function(data){
                if (data) {
                    obj.attr('has-read', prvAttr == '1' ? '0' : '1');
                    obj.text(prvAttr == '1' ? 'Read' : 'Unread');
                    getNotificationCount();
                }
            }
        });
    }
    
    $.fn.updateRecords = function () {               
        
         $('#records tbody').on( 'click', '.clsEdit', function () {        
            $.fn.editRecord($(this));
        });
        
        $('#records tbody').on( 'click', '.clsDelete', function () {
            
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
                        BootstrapDialog.show({ message: 'Record deleted successfully!'});                      
                    } 
                }
            });            
        });
    }    
        
    $.fn.removeRow = function (recordIndex) {       
        // Get previous pagination number
        var previousPagination= parseInt( $(".paginate_button.current").data("dt-idx") ) -1;       
              
        // Decide to redraw or not based on the presence of `.deleteBtn` elements.
        var doIdraw=false;
        if( $(".clsDelete").length == 1){
            doIdraw=true;
        }        
        // If the page redraws and a previous pagination existed (except the first)
        if(previousPagination > 1 && doIdraw){
            var previousPage = $(document).find("[data-dt-idx='" + (previousPagination) + "']").click();
        }      
        else{
            records.row(recordIndex).remove().draw(doIdraw);
        }
    }    
    
     records = $('#records').DataTable({
        responsive: true,
        "order": [[2,"desc"]],
        "autoWidth": false,
        "processing": true,
        "serverSide": true,
        "stateSave": true,
        "ajax": urlRecordList,
        "columns": recordsColumn,
        "initComplete": function(settings, j) {           
            $.fn.updateRecords();           
        },        
        "columnDefs": [{
                "targets": [3, 4],    // Column which need to update. Index start from zero
                "render": function (data, type, row, meta) {
                    if (type === 'display') {                        
                        if (meta.col == 3) {
                            switch (data) {
                                case '0':
                                    data = data = "<a class='inline-link clsEdit' href='#' eid='"+row.delete+"' has-read=1 >Unread</a>";
                                    break;
                                case '1':
                                    data = data = "<a class='inline-link clsEdit' href='#' eid='"+row.delete+"' has-read=0 >Read</a>";
                                    break;                               
                            }
                        }
                        if (meta.col == 4) {
                            data = "<a class='inline-link clsDelete' href='#' did='"+data+"'>Delete</a>";
                        }                                                                      
                    }                                      
                    return data;
                }
            }]
    });                    
});    