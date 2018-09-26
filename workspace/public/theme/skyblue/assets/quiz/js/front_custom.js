$(document).ready(function () {
    loadQuestion();
    $("body").on("click", "#questionSubmit", function () {
        loadQuestion();
    });
    $("body").on("click", "#questionPrev", function () {
        loadQuestionPrev();
    });


    $('#future_date').countdowntimer({
        minutes: $('#quizTime').val(),
        timeUp: function () {
            window.location = '/quiz/' + $('#quizId').val() + '/finish';
        }
    });
});
function loadQuestion() {
    $.ajax({
        method: 'POST',
        url: siteUrl + 'quizajax',
        data: {
            quizId: $('#quizId').val(),
            questionId: $('#questionId').val(),
            quizAnswer: $('.quizAnswer:checked,textarea.quizAnswer').val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        dataType: 'html',
    }).done(function (template) {
        $('.test-area').html(template);
    });
}

function loadQuestionPrev() {
    $.ajax({
        method: 'POST',
        url: siteUrl + 'quizajaxprev',
        data: {
            quizId: $('#quizId').val(),
            questionId: $('#questionId').val(),
            quizAnswer: $('.quizAnswer:checked,textarea.quizAnswer').val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        dataType: 'html',
    }).done(function (template) {
        $('.test-area').html(template);
    });
}