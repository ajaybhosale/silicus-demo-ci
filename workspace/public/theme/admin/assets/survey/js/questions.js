$(document).ready(function(){  
var url = "/admin/survey/question";
//To delete selected single record
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
                            url: url + '/delete/' + recordId,
                            success: function (data) {                                
                                $('#recordGrid').DataTable().ajax.reload(); //to refresh datatables
                                $('div#message').html('<div class="alert alert-success"><a aria-label="close" data-dismiss="alert" class="close" href="#">Ã—</a><strong>Success!</strong> Successfully deleted');
                            },
                            error: function (data) {
                                console.log('Error:', data);
                            }
                        });
                    }
                }
            });
});
//To delete multiple records
$(document).on('click','.deleteAllRecords',function (event) {      
    var selected = [];
    $('#recordGrid input.checkbox:checked').each(function() {
        selected.push($(this).attr('name'));
    });   
    console.log(selected.length);
    if(0 == selected.length)
    {
        //bootbox.alert('Please select at least one record.');
        bootbox.alert('Please select at least one record.', function (result) {
                                    return;
        });
    }
    else
    {
        var currentTarget = event.target;  
        var thisObj = this; 
        event.preventDefault();
        var recordIds = selected;
        bootbox.confirm({
                    message: 'Do you really want to delete selected records?',
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
                            $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                                    }
                                })
                            $.ajax({
                                type: "DELETE",
                                url: url + '/delete/'+recordIds,
                               // data: {ids:recordIds},
                                success: function (data) {    
                                    console.log(data);
                                    $("tr#question" + recordIds).remove();
                                    //window.location.reload();
                                    $('div#message').html('<div class="alert alert-success"><a aria-label="close" data-dismiss="alert" class="close" href="#">Ã—</a><strong>Success!</strong> Successfully deleted');
                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                }
                            });
                        }
                    }
                });
        }
});
    $(document).on('click','#checkAll',function(){        
        if($(this).is(":checked"))
        {
            $('#recordGrid').find('.checkbox').prop('checked',true);
        }
        else
        {
            $('#recordGrid').find('.checkbox').prop('checked',false);
        }
    });
    //To show/hide fields depend on question type
    $(document).on('change','select#type',function(){           
        var typeVal = $(this).val();                        
        if('boolean' == typeVal)
        {
            $('div.option3, div.option4').addClass('hide');            
        }
        else if('multioption' == typeVal)
        {
            $('div.option1, div.option2, div.option3, div.option4').removeClass('hide');
        }
        else if('subjective' == typeVal)
        {
            $('div.option1, div.option2, div.option3, div.option4').addClass('hide');
        }
    });
    //display modal form for record editing
    $(document).on('click','.open-modal',function(){
        var val = $(this).attr('value');
        if('undefined' != typeof(val) && '' != val)
        {
            $.get(url+"/"+val+"/edit", function (data) {
                //success data                
                $('#id').val(data.id);
                $('#question').val(data.question);               
                $('#option1').val(data.option1);
                $('#option2').val(data.option2);
                $('#option3').val(data.option3);
                $('#option4').val(data.option4);
                $('#type').val(data.type);
                $('#status').val(data.status);
                $('#btn-save').val("update");
                $('#myModal').modal('show');
                $('select#type').trigger('change');
            }) 
        }
        else
        {            
                $('#btn-save').val("add");
                $('#myModal').modal('show');  
                $('select#type').trigger('change');
        }
    });
    
    
    //display modal form for creating new task
    $(document).on('click','#btnAdd',function(){           
        $('#btn-save').val("add");
        $('#frmQuestion').trigger("reset"); 
        $('#myModal').modal('show');
    });
    //Display detail view in popup
    $(document).on('click','#btnView',function(){ 
        var id = $(this).attr('value');
        $.ajax({
                type: 'GET',
                url: url+'/show/'+id,
                data: {id: id},
                dataType: 'json',
                success: function (data) {                    
                    if(data.errors)
                    {                        
                        $('div#message').html('<div class="alert alert-error"><a aria-label="close" data-dismiss="alert" class="close" href="#">Ã—</a><strong>Error!</strong> While retrieveing record');
                    }
                    else
                    {  
                        $('div#modalDetail').find('#question').html(data.question);
                        $('div#modalDetail').find('#type').html(data.type);
                        $('div#modalDetail').find('#option1').html(data.option1);
                        $('div#modalDetail').find('#option2').html(data.option2);
                        $('div#modalDetail').find('#option3').html(data.option3);
                        $('div#modalDetail').find('#option4').html(data.option4); 
                        var required;
                        if('1' == data.required)                        
                            required = 'Mandatory'
                        else
                            required = 'Optional'
                        $('div#modalDetail').find('#required').html(required);
                        var status;
                        if('1' == data.status)                        
                            status = 'Active'
                        else
                            status = 'Inactive'
                        $('div#modalDetail').find('#status').html(status);
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
        if($('form#frmQuestion').valid()){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            })
            e.preventDefault(); 
            var formData = {
                id: $('#id').val(),
                question: $('#question').val(),
                survey_id: $('#survey_id').val(),
                option1: $('#option1').val(),
                option2: $('#option2').val(),
                option3: $('#option3').val(),
                option4: $('#option4').val(),
                type: $('#type').val(),             
                required: $('#required').val(),  
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
                dataType: 'json',
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
                        var record = '<tr id="question' + data.id + '">';
                        var status='';
                        if('1' == data.status)                        
                            status = 'Active'
                        else
                            status = 'Inactive'
                        var required='';
                        if('1' == data.required)                        
                            required = 'Mandatory'
                        else
                            required = 'Optional'
                        record += '<td><input class="checkbox" name="'+data.id+'" id="'+data.id+'" type="checkbox"></td><td>' + data.question + '</td><td>' + data.type + '</td><td>' + required + '</td> <td>' + status + '</td>';                        
                        record += '<td><a class="inline-link glyphicon glyphicon-eye-open" id="btnView" href="#" value="'+data.id+'" title="View"></a></td>';
                        record += '<td><a class="inline-link btn-edit glyphicon glyphicon-pencil open-modal" href="#" value="'+data.id+'" title="Edit"></a></td>';
                        record += '<td><a class="inline-link deleteRecord glyphicon glyphicon-trash" href="#" value="'+data.id+'" title="Delete"></a></td></tr>';

                        if (state == "add"){ //if user added a new record
                        {                            
                            $('#recordGrid').DataTable().ajax.reload(); //to refresh datatables
                        }
                        }else{ //if user updated an existing record

                            $("#question" + id).replaceWith( record );
                        }                      
                        $('#frmQuestion').trigger("reset");                        
                        $('#myModal').modal('hide');                        
                        $('div#message').html('<div class="alert alert-success"><a aria-label="close" data-dismiss="alert" class="close" href="#">Ã—</a><strong>Success!</strong> Successfully '+ msg);
                    }
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        }
    });    
    var recordsColumn = [
                            {"data": "checkbox", "bSortable": false},
                            {"data": "question"},                                                      
                            {"data": "type"},
                            {"data": "required"},
                            {"data": "status"},
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
                "targets": [0,3,4,5,6,7],    // Column which need to update. Index start from zero
                "render": function (data, type, row, meta) {                                        
                    if (type === 'display') {  
                        if(meta.col == 0)
                        {                               
                            data = '<input class="checkbox" name="'+data+'" id="'+data+'" type="checkbox">';                         
                        }
                        if (meta.col == 3) {
                            switch (data) {
                                case '0':
                                    data = 'Optional';
                                    break;
                                case '1':
                                    data = 'Mandatory';
                                    break;
                            }
                        }
                        if (meta.col == 4) {
                            switch (data) {
                                case '0':
                                    data = 'Inactive';
                                    break;
                                case '1':
                                    data = 'Active';
                                    break;
                            }
                        }
                        if (meta.col == 5) {                            
                            data = '<a class="inline-link glyphicon glyphicon-eye-open" id="btnView" href="#" value="'+data+'" title="View"></a>';
                        }
                        if (meta.col == 6) {                            
                            data = '<a class="inline-link btn-edit glyphicon glyphicon-pencil open-modal" href="#" value="'+data+'" title="Edit"></a>';
                        }
                        if (meta.col == 7) {                            
                            data = '<a class="inline-link deleteRecord glyphicon glyphicon-trash" href="#" value="'+data+'" title="Delete"></a>';
                        }                         
                    }                                      
                    return data;
                }
            }]
    });  

});
