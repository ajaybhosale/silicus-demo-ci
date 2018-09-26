$(document).ready(function(){  
var url = "/admin/survey";
$('#from_date').datetimepicker({       
        format: 'd/m/Y',
        onShow: function (ct) {           
            this.setOptions({
                maxDate: $('#to_date').val() ? $('#to_date').val() : false, formatDate: 'd/m/Y'
            })
        },
    });
    
$('#to_date').datetimepicker({        
        format: 'd/m/Y',
        onShow: function (ct) {           
            this.setOptions({
                minDate: $('#from_date').val() ? $('#from_date').val() : false, formatDate: 'd/m/Y'
            })
        },
    });
$(document).on('change','#category',function(event){    
    $('#category_id').val($(this).val());
})
$(document).on('click','.deleteRecord',function (event) {           
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
                            url: url + '/' + recordId,
                            success: function (data) {                                
                                //$("tr#survey" + recordId).remove();
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
    //display modal form for record editing
    $(document).on('click','.open-modal',function(){
        var val = $(this).attr('value');
        if('undefined' != typeof(val) && '' != val)
        {
            $.get(url+"/"+val+"/edit", function (data) {
                //success data                
                $('#id').val(data.id);
                $('#name').val(data.name);
                $('#from_date').val(data.from_date);
                $('#to_date').val(data.to_date);
                $('#category').val(data.category_id);
                $('#status').val(data.status);
                $('#btn-save').val("update");
                $('#myModal').modal('show');
            }) 
        }
        else
        {            
                $('#btn-save').val("add");
                $('#myModal').modal('show');            
        }
    });
    
    //display modal form for creating new task
    $(document).on('click','#btnAdd',function(){        
        $('#btn-save').val("add");
        $('#frmSurvey').trigger("reset"); 
        $('#myModal').modal('show');
    });
    //Display detail view in popup
    $(document).on('click','#btnView',function(){ 
        var id = $(this).attr('value');
        alert('id = '+id);
        $.ajax({
                type: 'GET',
                url: url+'/show/'+id,
                data: {id: id},
                dataType: 'html',
                success: function (data) {                    
                    if(data.errors)
                    {                        
                        $('div#message').html('<div class="alert alert-error"><a aria-label="close" data-dismiss="alert" class="close" href="#">×</a><strong>Error!</strong> While retrieveing record');
                    }
                    else
                    {  
                        $('div.surveyDetail').html(data);                        
                        $('#modalDetail').modal('show');
                    }
                },
                error: function (data) {                    
                    $('#modalDetail').modal('hide');
                }
            });
        
    });
    //create new task / update existing task
    $("#btn-save").click(function (e) {
        if($('form#frmSurvey').valid()){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            })
            e.preventDefault(); 
            var formData = {
                id: $('#id').val(),
                name: $('#name').val(),
                from_date: $('#from_date').val(),
                to_date: $('#to_date').val(),
                category_id: $('#category_id').val(),
                status: $('#status').val(),
            }
            //used to determine the http verb to use [add=POST], [update=PUT]
            var state = $('#btn-save').val();            
            var type = "POST"; //for creating new resource
            var id = $('#id').val();;
            var my_url = url;
            var msg = 'added';
            if (state == "update"){
                type = "PUT"; //for updating existing resource
                my_url += '/' + id;
                msg = 'updated';
            }else if (state == "add"){                
                my_url += '/create';
            }            
            $.ajax({
                type: type,
                url: my_url,
                data: formData,
                dataType: 'html',
                success: function (data) {                    
                    if(data.errors)
                    {                        
                        $.each(data.errors, function(k,v) {
                            $(document).find('#'+k).after('<span class="help-block">'+v+'<strong></strong></span>');
                            $(document).find('#'+k).parent('div').addClass('has-error');
                        });
                    }
                    else
                    {   
                        var record = data;//'<tr id="survey' + data.id + '">';                       
                        if (state == "add"){ //if user added a new record
                        {  
                            $('#recordGrid').DataTable().ajax.reload(); //to refresh datatables
                        }
                        }else{ //if user updated an existing record

                            $("#survey" + id).replaceWith( record );
                        }                      
                        $('#frmSurvey').trigger("reset");                        
                        $('#myModal').modal('hide');                        
                        $('div#message').html('<div class="alert alert-success"><a aria-label="close" data-dismiss="alert" class="close" href="#">×</a><strong>Success!</strong> Successfully '+ msg);
                    }
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        }
    });

    //For scrolling
    //$('.scroll').jscroll();
    //To display analysis accroding to questions
    $(document).on('click','.queCollapse',function(){
       var queId = $(this).attr('data-id');        
       var className = '.displayChart'+queId;       
       if($(document).find(className).hasClass('hide'))
        {
            $(document).find(className).removeClass('hide');   
        }
        else{
            $(document).find(className).addClass('hide');   
        }
        //Draw graph for first request
       if($(document).find(className).hasClass('loader') && 0 == $.trim($(document).find(className).html()).length)
       {                
            var formData = {
                questionId: queId,
            }            
            $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                })     
            $.ajax({
                        type: 'POST',
                        url: url+'/getAnalysis',
                        data: formData,
                        dataType: 'html',
                        success: function (data) {                    
                            if(data.errors)
                            {                        
                                console.log(data);
                            }
                            else
                            {   
                                $(document).find(className).removeClass('loader');
                                $(document).find(className).html(data);
                                console.log(data);
                            }
                        },
                        error: function (xhr, status, error) {
                            var err = eval("(" + xhr.responseText + ")");
                            //console.log(err.Message);                    
                        }
                    });
                $(document).find(className).removeClass('hide');   
            }
            
    });
        
    var recordsColumn = [                            
                            {"data": "name"},                                                      
                            {"data": "updatedAt"},
                            {"data": "from_date"},
                            {"data": "to_date"},
                            {"data": "response"},
                            {"data": "status"},
                            {"data": "analysis"},
                            {"data": "view", "bSortable": false},
                            {"data": "edit", "bSortable": false},
                            {"data": "delete", "bSortable": false}
                        ]; 
    $('#recordGrid').DataTable({
        responsive: true,
        "autoWidth": false,
        "processing": true,
        "serverSide": true,
        "stateSave": true,
        "ajax": url+'/getList',
        "columns": recordsColumn,      
        "columnDefs": [{
                "targets": [0,5,6,7,8,9],    // Column which need to update. Index start from zero
                "render": function (data, type, row, meta) {                                        
                    if (type === 'display') {  
                        if(meta.col == 0)
                        {
                            data = '<a href="'+url+'/questions/list/'+row.edit+'">'+data+'</a>';
                        }
                        if (meta.col == 5) {
                            switch (data) {
                                case '0':
                                    data = 'Inactive';
                                    break;
                                case '1':
                                    data = 'Active';
                                    break;
                            }
                        }
                        if(meta.col == 6)
                        {   
                            if(null == data)
                               data =  '<i class="fa fa-bar-chart"></i>';
                            else
                                data = '<a href="'+url+'/analyze/'+row.edit+'"><i class="fa fa-bar-chart"></i></a>';
                        }
                        if (meta.col == 7) {                           
                            data = '<a class="inline-link glyphicon glyphicon-eye-open" id="btnView" href="#" value="'+data+'" title="View"></a>'
                        }
                        if (meta.col == 8) {                            
                            data = '<a class="inline-link btn-edit glyphicon glyphicon-pencil open-modal" href="#" value="'+data+'" title="Edit"></a>';
                        }
                        if (meta.col == 9) {                            
                            data = '<a class="inline-link deleteRecord glyphicon glyphicon-trash" href="#" value="'+data+'" title="Delete"></a>';
                        }                         
                    }                                    
                    return data;
                }
            }]
    });  
});
