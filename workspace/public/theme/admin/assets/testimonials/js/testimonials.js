$(document).ready(function ($) {

    $("#owl-example").owlCarousel({
        items: 1,
        autoPlay: true
    });

    $("#showContentDiv").click(function (e) {
         e.preventDefault();
         $("#formDiv").toggle(1600);
    });
    
    var records;   
    var urlRecordList = siteUrl + "admin/testimonial/testimonialList";
    var urlRecordEdit = siteUrl + "admin/testimonial/status";
    var urlDelete = siteUrl+"admin/testimonial/delete";       
    var recordsColumn = [
                            {"data": "author"},                            
                            {"data": "text"},                            
                            {"data": "status"}, 
                            {"data": "edit","bSortable": false}, 
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
            data: {id: id, publish: prvAttr},
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
        "order": [[0,"desc"]],
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
                "targets": [2,3,4],    // Column which need to update. Index start from zero
                "render": function (data, type, row, meta) {
                    if (type === 'display') {                        
                        if (meta.col == 2) {                              
                            switch (data) {
                                case 0:
                                    data = "<a class='inline-link clsEdit glyphicon glyphicon-remove' href='#' eid='"+row.edit+"' has-publish=1 title='Unpublish'></a>";
                                    break;
                                case 1:
                                    data = "<a class='inline-link clsEdit glyphicon glyphicon-ok' href='#' eid='"+row.edit+"' has-publish=0 title='Publish'></a>";
                                    break;                               
                            }
                        }
                        if (meta.col == 3) {
                            data = "<a class='inline-link clsEdit glyphicon glyphicon-pencil' href='create/"+data+"' title='Edit'></a>";
                        } 
                        if (meta.col == 4) {
                            data = "<a class='inline-link clsDelete glyphicon glyphicon-trash' href='#' did='"+data+"' title='Delete'></a>";
                        }                                                                      
                    }                                      
                    return data;
                }
            }]
    });
});