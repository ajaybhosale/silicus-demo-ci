$(document).ready(function ($) {
    
    $("#showContentDiv").click(function (e) {
         e.preventDefault();
         $("#formDiv").toggle(1600);
    });
    
    if($('.emails').length){
        
        $('.emails').select2({              
           multiple:true, 
            ajax: {
              url: siteUrl+'admin/newsletters/subscribers',
              delay: 1000,  
              type:'GET',
              data: function (params) {                  
                return {
                  q: params, // search term         
                };
              },
              results: function (data, page) {                
                
                var results = [];
                $.each(data.items, function(index, item){
                  results.push({
                    id: item.id,
                    text: item.text
                  });
                });
                return {
                    results: results
                };
              },
              cache: true
            },
            minimumInputLength: 1
        });
        if(subscribers){            
            $(".emails").select2("data", $.parseJSON(subscribers));
        }
    }
      
    if($('textarea').length){
      tinymce.init({
        selector: 'textarea',
        height: 300,
        width: 862,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table contextmenu paste code'
        ],
        toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        content_css: [],
        setup: function (ed) {        
        ed.on('change', function(e) {
            if(ed.getContent()!=''){
                $('#template').val(ed.getContent());
            }else{
                $('#template').val('');
            }
        });
        }
    });
    }
    
    var records;   
    var urlRecordList = siteUrl + "admin/newsletters/getList";
    var urlDelete = siteUrl+"admin/newsletters/delete";       
    var recordsColumn = [
                            {"data": "subject"},
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
                "targets": [1, 2],    // Column which need to update. Index start from zero
                "render": function (data, type, row, meta) {
                    if (type === 'display') {                        
                        if (meta.col == 1) {
                            data = data = "<a class='inline-link clsEdit glyphicon glyphicon-pencil' href='/admin/newsletters/create/"+data+"' title='Edit'></a>";                                                                
                        }
                        if (meta.col == 2) {
                            data = "<a class='inline-link clsDelete glyphicon glyphicon-trash' href='#' did='"+data+"' title='Delete'></a>";
                        }                                                                      
                    }                                      
                    return data;
                }
            }]
    });                    
});