$(document).ready(function(){
    
    $("#showContentDiv").click(function (e) {
         e.preventDefault();
         $("#formDiv").toggle(1600);
    });
    
    $('#submitPoll').click(function(){    
    $('.poll-message').removeClass('alert-success').removeClass('alert-error').html('');              
    $.ajax({
            method: 'POST',
            url: siteUrl + 'poll/vote',
            data: {
            pollOptionId:$('[name="pollanswer[]"]:checked').val(),
            pollId:$('#pollId').val(),
            _token:$('meta[name="csrf-token"]').attr('content')            
            },
            dataType: 'json',
          }).done(function( msg ) {
              $('.poll-message').addClass(msg.code).html(msg.message);              
                //window.location = siteUrl + 'admin/menu/list/'+catId;
          }); 
    });	
});
