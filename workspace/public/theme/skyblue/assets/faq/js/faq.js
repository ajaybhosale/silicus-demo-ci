
$(document).ready(function(){  
    
    $("#showContentDiv").click(function (e) {
         e.preventDefault();
         $("#formDiv").toggle(1600);
    });
        
    $(document).on('click','.deleteRecord',function (event) {           
        var currentTarget = event.target;  
        var thisObj = this; 
        event.preventDefault();
        BootstrapDialog.confirm({
                    message: 'Do you really want to delete this record?',
                    type: BootstrapDialog.TYPE_DANGER, 
                    title: 'WARNING',
                    message: 'Are you sure you want to delete this Faq?',
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
                            $(thisObj).parent().parent().find('form').submit();
                        }
                    }
                });
    });

    $(document).on('click', '.panel-heading', function(e){
    var $this = $(this);
	if(!$this.hasClass('panel-collapsed')) {
		$this.parents('.panel').find('.panel-body').slideUp();
		$this.addClass('panel-collapsed');
		$this.find('i').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
	} else {
		$this.parents('.panel').find('.panel-body').slideDown();
		$this.removeClass('panel-collapsed');
		$this.find('i').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
	}
    });
    $('.panel-heading').trigger('click');
    
    var records;   
    var urlRecordList = siteUrl + "admin/faqs/getList";    
    var urlRecordEdit = siteUrl + "admin/faqs/status";    
    var urlDelete = siteUrl+"admin/faqs/delete";       
    var recordsColumn = [
                            {"data": "question"},
                            {"data": "answer"},
                            {"data": "status"},                            
                            {"data": "edit", "bSortable": false},
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
                    obj.text(prvAttr == '1' ? 'Publish' : 'Unpublish');
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
                "targets": [2, 3, 4],    // Column which need to update. Index start from zero
                "render": function (data, type, row, meta) {
                    if (type === 'display') {                        
                        if (meta.col == 2) {                            
                            switch (data) {
                                case 0:
                                    data = data = "<a class='inline-link clsEdit' href='#' eid='"+row.edit+"' has-publish=1 >Unpublish</a>";
                                    break;
                                case 1:                                    
                                    data = data = "<a class='inline-link clsEdit' href='#' eid='"+row.edit+"' has-publish=0 >Publish</a>";
                                    break;                               
                            }
                        }
                        if (meta.col == 3) {
                            data = "<a class='inline-link' href='/admin/faq/"+data+"/edit' >Edit</a>";
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
