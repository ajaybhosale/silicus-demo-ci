$(document).ready(function () {
    $('#recordGrid').DataTable({
        "order": [[recordGridColumn, "desc"]],
        "aoColumnDefs": [
            {'bSortable': false, 'aTargets': nonSorting}
        ]
    });
});