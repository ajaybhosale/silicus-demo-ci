 var recordGridColumn = 0;
 var editcol = 4;
var deletecol = 5;
$(document).ready(function(){    


$(document).on('click','.deleteRecord',function (event) {           
    var currentTarget = event.target;  
    var thisObj = this; 
    event.preventDefault();
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
                        $(thisObj).parent().parent().find('form').submit();
                    }
                }
            });
});
});
