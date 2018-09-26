$(document).ready(function ($) {
    
    $(document).on("click", ".commnet-hollow" , function() {
           $( ".rating span:lt("+($( this ).index() + 1)+")" ).addClass('commnet-fill').removeClass('commnet-hollow');
           $('#rating').val($( this ).index() + 1);
    });
    
    $(document).on("click", ".commnet-fill" , function() {        
        if(($( this ).index() -1)!=-1){
         $( ".rating span:gt("+($( this ).index()-1)+")" ).addClass("commnet-hollow").removeClass("commnet-fill");
          $('#rating').val($( this ).index() + 1);
        }else{
         $( ".rating span").addClass("commnet-hollow").removeClass("commnet-fill");
         $('#rating').val(0);
        }         
    });

    var records;   
    var urlRecordList = siteUrl + "admin/reviews/getList";
    var urlRecordEdit = siteUrl + "admin/reviews/status";
    var urlDelete = siteUrl+"admin/reviews/delete";       
    var recordsColumn = [
                            {"data": "body"},
                            {"data": "users"},
                            {"data": "module_unique_name"},
                            {"data": "module_unique_item"},                            
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
        var prvAttr = obj.attr('has-publish');
        
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url : urlRecordEdit,
            type: "POST",
            data: {id: id, status: prvAttr},
            success: function(data){
                if (data) {
                    obj.attr('has-publish', prvAttr == '1' ? '0' : '1');
                    if(prvAttr == 1){
                       obj.removeClass('glyphicon-remove');
                       obj.addClass('glyphicon-ok');
                       obj.attr('title','Publish');
                    }else{
                       obj.removeClass('glyphicon-ok');
                       obj.addClass('glyphicon-remove');
                       obj.attr('title','Unpublish');
                    }
                    //obj.text(prvAttr == '1' ? 'Publish' : 'Unpublish');
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
                "targets": [4,5],    // Column which need to update. Index start from zero
                "render": function (data, type, row, meta) {
                    if (type === 'display') {                        
                        if (meta.col == 4) {
                            switch (data) {
                                case 0:
                                    data = data = "<a class='inline-link clsEdit glyphicon glyphicon-remove' href='#' eid='"+row.delete+"' has-publish=1 title='Unpublish'></a>";
                                    break;
                                case 1:
                                    data = data = "<a class='inline-link clsEdit glyphicon glyphicon-ok' href='#' eid='"+row.delete+"' has-publish=0 title='Publish'></a>";
                                    break;                               
                            }
                        }
                        if (meta.col == 5) {
                            data = "<a class='inline-link clsDelete glyphicon glyphicon-trash' href='#' did='"+data+"' title='Delete'></a>";
                        }                                                                      
                    }                                      
                    return data;
                }
            }]
    });                    
    
});