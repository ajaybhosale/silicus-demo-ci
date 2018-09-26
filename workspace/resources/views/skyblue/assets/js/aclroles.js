$(document).ready(function(){  
var url = "/aclroles";
$(document).on('click','.deleteRecord',function (event) {           
    var currentTarget = event.target;  
    var thisObj = this; 
    event.preventDefault();
    var recordId = $(this).val();   
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
                                $("tr#aclroles" + recordId).remove();
                                window.location.reload();
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
        var val = $(this).val();
        if('undefined' != typeof(val) && '' != val)
        {
            $.get(url+"/"+val+"/edit", function (data) {
                //success data                
                $('#id').val(data.id);
$('#name').val(data.name);
$('#slug').val(data.slug);
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
        console.log('on click');
        $('#btn-save').val("add");
        $('#frmAclroles').trigger("reset"); 
        $('#myModal').modal('show');
    });
    //Display detail view in popup
    $(document).on('click','#btnView',function(){ 
        var id = $(this).val();
        $.ajax({
                type: 'GET',
                url: url+'/show/'+id,
                data: {id: id},
                dataType: 'json',
                success: function (data) {                    
                    if(data.errors)
                    {                        
                        $('div#message').html('<div class="alert alert-error"><a aria-label="close" data-dismiss="alert" class="close" href="#">×</a><strong>Error!</strong> While retrieveing record');
                    }
                    else
                    {  
                         $('div#modalDetail').find('#name').html(data.name);
$('div#modalDetail').find('#slug').html(data.slug);
$('div#modalDetail').find('#status').html(data.status);

                        $('#modalDetail').modal('show');
                    }
                },
                error: function (data) {
                    console.log('Error:', data);
                    $('#modalDetail').modal('hide');
                }
            });
        
    });
    //create new task / update existing task
    $("#btn-save").click(function (e) {
        if($('form#frmAclroles').valid()){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            })
            e.preventDefault(); 
            var formData = {
                id: $('#id').val(),
name: $('#name').val(),
slug: $('#slug').val(),
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
                        var record = '<tr id="aclroles' + data.id + '">';
                        record += '<td>' + data.name + '</td><td>' + data.slug + '</td><td>' + data.status + '</td>';
                        record += '<td><button class="btn btn-warning btn-xs btn-edit open-modal" value="' + data.id + '">Edit</button>  ';
                        record += '<button class="btn btn-info btn-xs btn-detail" id="btnView" value="' + data.id + '">View</button>  ';
                        record += '<button class="btn btn-danger btn-xs btn-delete deleteRecord" value="' + data.id + '">Delete</button></td></tr>';

                        if (state == "add"){ //if user added a new record
                            {
                            $('#aclroles-list').append(record);
                            window.location.reload();
                        }
                        }else{ //if user updated an existing record

                            $("#aclroles" + id).replaceWith( record );
                        }                      
                        $('#frmAclroles').trigger("reset");                        
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
    //initialise datatables
    $('#recordGrid').DataTable({
        "order": [[0, "desc"]],        
        "columnDefs": [
                        { "orderable": false, "targets": [3] }
                      ]
    });


    
});
