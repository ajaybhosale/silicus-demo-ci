var recordGridColumn = 0;
var nonSorting = [4, 5, 6];
$(document).ready(function () {
    tinymce.init({selector: 'textarea'});
    $(document).on('click', '.deleteRecord', function (event) {
        var currentTarget = event.target;
        var thisObj = this;
        event.preventDefault();
        var recordId = $(this).attr('value'); 
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
               /* if (result)
                {
                    $(thisObj).parent().parent().find('form').submit();
                */
               if (result)
                    {                          
                        var id = $(this).attr('value');                         
                        $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                                }
                            })
                        $.ajax({
                            type: "DELETE",
                            url: siteUrl+'/admin/banner/' + recordId,
                            success: function (data) {                                
                                $("tr#banner" + recordId).remove();
                                $('#recordGrid').DataTable().ajax.reload(); //to refresh datatables
                                $('div#message').html('<div class="alert alert-success"><a aria-label="close" data-dismiss="alert" class="close" href="#">×</a><strong>Success!</strong> Successfully deleted');
                            },
                            error: function (data) {
                                console.log('Error:', data);
                            }
                        });
                    }
            }
        });
    });

    $(document).on('change', '#type', function (event) {
        var type = $(this).val();
        console.log(type);
        if ('image' == type || 'video' == type)
        {
            $('.inputFile').removeClass('hide');
            $('.inputContent').addClass('hide');
        }
        else if ('text' == type)
        {
            $('.inputContent').removeClass('hide');
            $('.inputFile').addClass('hide');
        }
    });
    $('#type').trigger('change');


    //For top position
    /*
     if('undefined' != typeof(top))
     getBannerByPosition(top);
     if('undefined' != typeof(bottom))
     getBannerByPosition(bottom);
     if('undefined' != typeof(left))
     getBannerByPosition(left);
     if('undefined' != typeof(right))
     getBannerByPosition(right);
     */
    ///////////////////
    //Examples of how to assign the Colorbox event to elements
    $(".group1").colorbox({rel: 'group1'});
    $(".group2").colorbox({rel: 'group2', transition: "fade"});
    $(".group3").colorbox({rel: 'group3', transition: "none", width: "75%", height: "75%"});
    $(".group4").colorbox({rel: 'group4', slideshow: true});
    $(".ajax").colorbox();
    $(".youtube").colorbox({iframe: true, innerWidth: 640, innerHeight: 390});
    $(".vimeo").colorbox({iframe: true, innerWidth: 500, innerHeight: 409});
    $(".iframe").colorbox({iframe: true, width: "80%", height: "80%"});
    $(".inline").colorbox({inline: true, width: "50%"});
    $(".callbacks").colorbox({
        onOpen: function () {
            alert('onOpen: colorbox is about to open');
        },
        onLoad: function () {
            alert('onLoad: colorbox has started to load the targeted content');
        },
        onComplete: function () {
            alert('onComplete: colorbox has displayed the loaded content');
        },
        onCleanup: function () {
            alert('onCleanup: colorbox has begun the close process');
        },
        onClosed: function () {
            alert('onClosed: colorbox has completely closed');
        }
    });

    $('.non-retina').colorbox({rel: 'group5', transition: 'none'})
    $('.retina').colorbox({rel: 'group5', transition: 'none', retinaImage: true, retinaUrl: true});

    //Example of preserving a JavaScript event for inline calls.
    $("#click").click(function () {
        $('#click').css({"background-color": "#f00", "color": "#fff", "cursor": "inherit"}).text("Open this window again and this message will still be here.");
        return false;
    });
    //////////////////////
    
    var recordsColumn = [
                            {"data": "name"},
                            {"data": "type"},
                            {"data": "size"},
                            {"data": "status"},                                                     
                            {"data": "edit", "bSortable": false},
                            {"data": "view", "bSortable": false},
                            {"data": "delete", "bSortable": false}
                        ];
    $('#recordGrid').DataTable({
        responsive: true,
        "autoWidth": false,
        "processing": true,
        "serverSide": true,
        "stateSave": true,
        "ajax": siteUrl+'admin/banners/getList',
        "columns": recordsColumn,      
        "columnDefs": [{
                "targets": [3,4, 5, 6],    // Column which need to update. Index start from zero
                "render": function (data, type, row, meta) {
                   if (type === 'display') {                        
                        
                        if (meta.col == 3) {
                            switch (data) {
                                case '0':
                                    data = 'Inactive';
                                    break;
                                case '1':
                                    data = 'Active';
                                    break;
                            }
                        }
                        if (meta.col == 4) {                            
                            data = '<a href="'+siteUrl+'admin/banner/'+data+'/edit" class="inline-link btn-edit glyphicon glyphicon-pencil" title="Edit"></a>';
                        }
                        if (meta.col == 5) {                            
                            data = '<a href="'+siteUrl+'admin/banner/show/'+data+'" class="inline-link glyphicon glyphicon-eye-open" title="View"></a>';
                        }
                        if (meta.col == 6) {
                            data = '<a class="inline-link deleteRecord glyphicon glyphicon-trash" href="#" value="'+data+'" title="Delete"></a>';                            
                        }                         
                    }                                      
                    return data;
                }
            }]
    }); 
});
function getBannerByPosition(vars)
{
    var position = vars['position'];    
    if (vars['links'])
    {
        var i = 0;
        var raw = setInterval(function () {
            if (vars['links'].length == i) {
                i = 0;
            }
            else {
                if ('image' == vars['type'][i])
                {
                    $('.bannerImage' + position).removeClass('hide');
                    $('.bannerText' + position).addClass('hide');
                    $('.bannerImage' + position).attr('src', vars['images'][i]);
                    $(".bannerImage" + position).attr('width', vars['width'][i]);
                    $(".bannerImage" + position).attr('height', vars['height'][i]);
                }
                else
                {
                    $('.bannerText' + position).removeClass('hide');
                    $('.bannerImage' + position).addClass('hide');
                    $('.bannerText' + position).html(vars['images'][i]);
                    $(".bannerText" + position).attr('width', vars['width'][i]);
                    $(".bannerText" + position).attr('height', vars['height'][i]);
                }
                $(".bannerLink" + position).attr('href', vars['links'][i]);
                $(".banners" + position).attr('width', vars['width'][i]);
                $(".banners" + position).attr('height', vars['height'][i]);
                i++;

            }
        }, 1000);
    }
}