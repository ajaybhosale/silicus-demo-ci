function delQuestion($questionId, $quizId) {
    BootstrapDialog.confirm({
        title: 'CONFIRM',
        message: 'Are you sure you want to delete?',
        type: BootstrapDialog.TYPE_DANGER, // <-- Default value is BootstrapDialog.TYPE_PRIMARY
        closable: true, // <-- Default value is false
        draggable: true, // <-- Default value is false
        btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
        btnOKLabel: 'Ok', // <-- Default value is 'OK',
        btnOKClass: '', // <-- If you didn't specify it, dialog type will be used,
        callback: function (result) {
            // result will be true if button was click, while it will be false if users close the dialog directly.
            if (result) {
                window.location = '/admin/quiz/list/question/delete/' + $questionId + '/' + $quizId;
            } else {
                return false;
            }
        }
    });
}
$(document).ready(function () {
    $('.addlist').hide();
    $('#cancel').click(function () {
        $('.panel-body').toggle(1600);
    })
    $('#addQuestion').click(function () {
        $('.panel-body').toggle(1600);
    });

    $('.true-false').hide();
    $('.options').hide();
    $('.quiz_answer').hide();
    var questionType = $('#questionType').val();
    if (questionType == 1) {
        $('.true-false').show();
        $('.options').show();
        $('.quiz_answer').show();
        $('#quizAnswer').html("<option value='option1'>Option1</option><option value='option2'>Option2</option><option value='option3'>Option3</option><option value='option4'>Option4</option>");
    } else if (questionType == 2) {
        $('.true-false').show();
        $('.quiz_answer').show();
        $('#quizAnswer').html("<option value='option1'>Option1</option><option value='option2'>Option2</option>");
    }
    $("#quizAnswer").val($("#questionTypeAnser").val());
    $('#questionType').change(function () {
        questionType = $('#questionType').val();
        $('.true-false').hide();
        $('.options').hide();
        $('.quiz_answer').hide();
        if (questionType == 1) {
            $('.true-false').show();
            $('.options').show();
            $('.quiz_answer').show();
            $('#quizAnswer').html("<option value='option1'>Option1</option><option value='option2'>Option2</option><option value='option3'>Option3</option><option value='option4'>Option4</option>");
        } else if (questionType == 2) {
            $('.true-false').show();
            $('.quiz_answer').show();
            $('#quizAnswer').html("<option value='option1'>Option1</option><option value='option2'>Option2</option>");
        }
    });
    var records;
    var urlRecordList = siteUrl + "admin/quiz/list/questions/getQuestionList/" + $('#records').attr('quiz-id');
    var recordsColumn = [
        {"data": "question"},
        {"data": "option1", "bSortable": false},
        {"data": "option2", "bSortable": false},
        {"data": "option3", "bSortable": false},
        {"data": "option4", "bSortable": false},
        {"data": "answer", "bSortable": false},
        {"data": "question_type"},
        {"data": "created_by"},
        {"data": "status", "bSortable": false},
        {"data": "edit", "bSortable": false},
        {"data": "delete", "bSortable": false}
    ];
    records = $('#records').DataTable({
        responsive: true,
        "order": [[0, "desc"]],
        "autoWidth": false,
        "processing": true,
        "serverSide": true,
        "stateSave": true,
        "ajax": urlRecordList,
        "columns": recordsColumn,
        "columnDefs": [{
                "targets": [6, 9, 10], // Column which need to update. Index start from zero
                "render": function (data, type, row, meta) {
                    if (meta.col == 6) {
                        data = data == 1 ? 'Objective' : data == 2 ? 'True/False' : 'Subjective';
                    }
                    if (meta.col == 9) {
                        data = "<a class='glyphicon glyphicon-pencil' href='/admin/quiz/list/question/edit/" + data + '/' + $('#records').attr('quiz-id') + "'>Edit</a>";
                    }
                    if (meta.col == 10) {
                        data = "<a class='glyphicon glyphicon-trash' onclick='delQuestion(" + data + ',' + $('#records').attr('quiz-id') + ")' href='javascript:void(0)'>Delete</a>";
                    }
                    return data;
                }
            }]
    });



});