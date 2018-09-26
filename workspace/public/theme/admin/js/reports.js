$(document).ready(function () {
    //Date range picker
    $('#reservation').daterangepicker();

    $("#downloadReport").click(function () {
        var strReservation = $('#reservation').val();
        $('#reservation').val(strReservation);
        $("#downloadReportForm").submit();
    });
});
