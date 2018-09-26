$(document).ready(function(){  
    load_question();
    
$(document).on('click','.btnLink',function(){    
    //On click of button check if question is mandatory or optional 
    var questionId = $('#question_id').val();
    var btnVal = $(this).val();
    load_question(btnVal);      
});
});
function load_question(btnVal)
{   
    //To redirect on complete page if survey is completed
    var dataType = '';
    if('done' == btnVal)
        dataType = 'JSON';
    else
        dataType = 'HTML';
   var formData = {
                questionId: $('#question_id').val(),
                survey_id: $('#survey_id').val(),
                answer: $("input[type='radio'][name='options']:checked").val(),
                txtAnswer: $('textarea#answer').val(),
                btnVal: btnVal
            }            
    $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            })     
    $.ajax({
                type: 'POST',
                url: '/survey/postsurvey',
                data: formData,
                dataType: dataType,
                success: function (data) {                    
                    if(true == data.redirect)
                    {                        
                        window.location.href='/survey/complete';
                    }
                    else
                    {   
                        $(document).find('.question_data').html(data);
                        console.log(data);
                    }
                },
                error: function (xhr, status, error) {
                    var err = eval("(" + xhr.responseText + ")");
                    //console.log(err.Message);                    
                }
            });
}
