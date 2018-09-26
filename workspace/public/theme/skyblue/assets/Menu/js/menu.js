var nodeLevel=0,dataId=0,link='',level=0;
$( document ).ready(function() {
    $( document ).on( "mouseenter", ".node-save", function() {
        nodeLevel = $( this ).closest('tr.node').attr('data-level');
        dataId = $( this ).closest('tr.node').attr('data-id');
        level = $( this ).closest('tr.node').attr('data-level');
        link = $( this ).closest('tr.node').find('input[name="link"]').val();
    });
});

$('#gtreetable').gtreetable({  
    'inputWidth':'30%',
  'source': function (id) {
      return {
        type: 'GET',
        url: siteUrl + 'admin/menu/node/list',
        data: { 'id': id,'catId':catId},        
        dataType: 'json',
        error: function(XMLHttpRequest) {
          alert(XMLHttpRequest.status+': '+XMLHttpRequest.responseText);
        }
      }
    },    
   'onSave':function (oNode) {       
       var flag = true;
    var pattern = /^(http[s]?:\/\/){0,1}(www\.){0,1}[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,5}[\.]{0,1}/;   
  
    if(oNode.getName()=='' || link==''){
        flag= false;
        BootstrapDialog.show({
            title: 'Error',
            type:BootstrapDialog.TYPE_DANGER,
            message: 'All fields are required.',
            buttons: [{
                id: 'btn-ok',   
                icon: 'glyphicon glyphicon-check',       
                label: 'OK',
                cssClass: 'btn-primary', 
                autospin: false,
                action: function(dialogRef){    
                    window.location = siteUrl + 'admin/menu/list/'+catId;
                }
            }]
        });
        return false;
    }/*else if(!pattern.test(link) && link!='#'){
        flag= false;
        BootstrapDialog.show({
            title: 'Error',
            type:BootstrapDialog.TYPE_DANGER,
            message: 'Url(s) are not valid in form',
            buttons: [{
                id: 'btn-ok',   
                icon: 'glyphicon glyphicon-check',       
                label: 'OK',
                cssClass: 'btn-primary', 
                autospin: false,
                action: function(dialogRef){    
                    window.location = siteUrl + 'admin/menu/list/'+catId;
                }
            }]
        });
        return false;
    }*/
    if(flag){
       var orderData = [];       
       
       $( '#gtreetable tbody tr.node:visible' ).each(function( i ) {
           if($(this).attr('data-id')){
             orderData[i] = $(this).attr('data-id');
           }else{
             orderData[i] = -1;
           }
       });  
       
       if($('input[name="req-login"]').is(':checked')){
        login_value = $('input[name="req-login"]').val();
       }else{
        login_value = 0;
       }
       
    return {
        type: 'POST',
        url: !oNode.isSaved() ? siteUrl + 'admin/menu/create' : siteUrl + 'admin/menu/create',
        data: {        
          parent: oNode.getParent(),
          name: oNode.getName(),
          link: link,
          position: oNode.getInsertPosition(),
          related: oNode.getRelatedNodeId(),
          req_login:login_value,
          level:level,
          id: dataId,  
          'category_id':catId,
          _token:$('meta[name="csrf-token"]').attr('content'),
          orderData: orderData
        },
        dataType: 'json',      
        success: function(XMLHttpRequest) {
          window.location = siteUrl + 'admin/menu/list/'+catId;
        },
        error: function(XMLHttpRequest) {
          window.location = siteUrl + 'admin/menu/list/'+catId;
        }
      };
    }else{
         window.location = siteUrl + 'admin/menu/list/'+catId;
    }
  },
  manyroots:true,
  draggable:true,
  'onMove': function (oSource, oDestination, position) {     
   var orderData = [];
   
    setTimeout(function(){   
        $( '#gtreetable tbody tr.node:visible' ).each(function( i ) {
            orderData[i] = $(this).attr('data-id');       
        });         
        $.ajax({
            method: 'POST',
            url: siteUrl + 'admin/menu-move',
            data: {
            related: oDestination.getId(),
            position: position,
            id:oSource.getId(),
            _token:$('meta[name="csrf-token"]').attr('content'),
            orderData:orderData
            },
            dataType: 'json',
          }).done(function( msg ) {
                //window.location = siteUrl + 'admin/menu/list/'+catId;
          });         
        }, 300);
        /*return {
          type: 'POST',
          url: siteUrl + 'admin/menu-move',
          data: {
            related: oDestination.getId(),
            position: position,
            id:oSource.getId(),
            _token:$('meta[name="csrf-token"]').attr('content')        
          },
          dataType: 'json',
          error: function(XMLHttpRequest) {
            alert(XMLHttpRequest.status+': '+XMLHttpRequest.responseText);
          }
        };*/
  },
  'onDelete':function (oNode) {
      
      
      
       BootstrapDialog.show({
            title: 'Delete',
            type:BootstrapDialog.TYPE_DANGER,
            closable:false,
            message: 'Are you sure to delete?',
            buttons: [{
                label: 'Ok',
                action: function(dialog) {                    
                    window.location = siteUrl + 'admin/menu/delete/'+oNode.getId()+'/'+catId;
                    /*return {
                        type: 'POST',
                        url:  siteUrl + 'admin/menu/delete',
                         data: {
                          id: oNode.getId(),        
                          _token:$('meta[name="csrf-token"]').attr('content')        
                        },
                        dataType: 'json',
                        success: function(XMLHttpRequest) {
                          window.location = siteUrl + 'admin/menu/list/'+catId;
                        },
                        error: function(XMLHttpRequest) {
                           window.location = siteUrl + 'admin/menu/list/'+catId;
                        }
                      };*/
                }
            }, {
                label: 'Cancel',
                action: function(dialog) {
                    window.location = siteUrl + 'admin/menu/list/'+catId;
                }
            }]
        });
  }
});
