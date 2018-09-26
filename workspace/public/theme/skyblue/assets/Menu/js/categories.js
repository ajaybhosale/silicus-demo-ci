$(document).ready(function ($) {  
  
  $('.delete').click(function (e) {
      e.preventDefault();
      delContact($(this).attr('rel'));           
  });
  
});

var recordGridColumn = 0;
var nonSorting = [2,3,4];
function delContact(id) {   
    
    BootstrapDialog.confirm({
        title: 'WARNING',
        message: 'Are you sure you want to delete this Category Menu?',
        type: BootstrapDialog.TYPE_DANGER, // <-- Default value is BootstrapDialog.TYPE_PRIMARY
        closable: true, // <-- Default value is false
        draggable: true, // <-- Default value is false
        btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
        btnOKLabel: 'Ok', // <-- Default value is 'OK',
        btnOKClass: '', // <-- If you didn't specify it, dialog type will be used,
        callback: function(result) {
        // result will be true if button was click, while it will be false if users close the dialog directly.
            if (result) {
                 window.location = siteUrl + "admin/menu/category/delete/" + id;    
            } else {
                return false;
            }
        }
    });
}