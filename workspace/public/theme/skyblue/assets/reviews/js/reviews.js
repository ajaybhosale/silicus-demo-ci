$(document).ready(function ($) {
    
    $(document).on("click", ".commnet-hollow" , function() {
           $( ".rating span:lt("+($( this ).index() + 1)+")" ).addClass('commnet-fill').removeClass('commnet-hollow');
           $('#rating').val($( this ).index() + 1);
    });
    $(document).on("click", ".commnet-fill" , function() {        
        if(($( this ).index() -1)!=-1){
         $( ".rating span:gt("+($( this ).index()-1)+")" ).addClass("commnet-hollow").removeClass("commnet-fill");
          $('#rating').val($( this ).index() + 1);
        }else{
         $( ".rating span").addClass("commnet-hollow").removeClass("commnet-fill");
         $('#rating').val(0);
        }         
    });
    
   /* $(document).on("mouseenter", ".commnet-hollow-hover" , function() {
           $( ".rating span:lt("+($( this ).index() + 1)+")" ).addClass('commnet-fill-hover').removeClass('commnet-hollow-hover');
    });
    $(document).on("mouseleave", ".commnet-fill-hover" , function() {        
        if(($( this ).index() -1)!=-1){
         $( ".rating span:gt("+($( this ).index()-1)+")" ).addClass("commnet-hollow-hover").removeClass("commnet-fill-hover");
        }else{
         $( ".rating span").addClass("commnet-hollow-hover").removeClass("commnet-fill-hover");
        }   
    });*/

    $('.delete').click(function (e) {
        e.preventDefault();
        delContact($(this).attr('rel'));
    });
    
});
var recordGridColumn = 0;
var nonSorting = [4,5];
function delContact(contactId) {

    BootstrapDialog.confirm({
        title: 'WARNING',
        message: 'Are you sure you want to delete this review?',
        type: BootstrapDialog.TYPE_DANGER, // <-- Default value is BootstrapDialog.TYPE_PRIMARY
        closable: true, // <-- Default value is false
        draggable: true, // <-- Default value is false
        btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
        btnOKLabel: 'Ok', // <-- Default value is 'OK',
        btnOKClass: '', // <-- If you didn't specify it, dialog type will be used,
        callback: function (result) {
            // result will be true if button was click, while it will be false if users close the dialog directly.
            if (result) {
                window.location = "/admin/reviews/delete/" + contactId;
            } else {
                return false;
            }
        }
    });
}