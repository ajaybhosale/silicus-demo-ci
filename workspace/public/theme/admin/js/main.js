/* Notification code start*/
var notificationCall = 0;

function notificationHeadList(){     
    $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: siteUrl + 'admin/notification/headList',
            type: 'POST',
            data: {noticeCount: notificationCall},
            success: function (data) {
                if ($.trim(data) != 'blank') {
                    $("#notification").append(data);
                }
                notificationCall++;
            }
        });
}

function getNotificationCount() {        
    $.ajax({
        url: siteUrl + 'admin/notification/getNotificationCount',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'GET',        
        success: function (data) {           
            if (data) {               
                $('.notificationCount').text(data);
            }
        }
    });
}
/* Notification code end*/

$(document).ready(function() {    
    /* Notification code start*/     
        getNotificationCount()        
        notificationHeadList();
        $('#notification').on('scroll', function () {
            if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
                notificationHeadList();
            }
        });  
        
        setInterval(function(){  getNotificationCount(); }, 150000);
    /* Notification code end*/
});