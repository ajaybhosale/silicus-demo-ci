$(document).ready(function(){
    
        $("#showContentDiv").click(function (e) {
             e.preventDefault();
             $("#formDiv").toggle(1600);
        });
        
	$('form.poll-form').on('submit', function(e){

		e.preventDefault();

		var	submittedAnswer = $('input[name="answer"]:checked').val();

		if ( ! submittedAnswer ) {

			$('.poll-form--error').removeClass('hide');

		} else {

			$.post( $( this ).attr('action'), $( this ).serialize() );

			var total = $('.poll-results').data('total') + 1;
			var score, percent;

			$('.poll-result').each(function(index, value){
				score = $(value).data('score');

				if ( submittedAnswer == $(value).data('answer') ) {
					score++;
				}

				percent = Math.round( score / total * 100);
				$(value).find('.result-bg').css('width', percent.toString()+'%');
				$(value).find('span.percent').html(percent.toString()+'%');
			});

			$( this ).addClass('hide');
			$('.poll-btn-enter').addClass('hide');

			$('.poll-results').removeClass('hide');
			$('.poll-btn-back').removeClass('hide');

			 window.setTimeout(function(){
				$('.poll-results').addClass('animate');
			}, 10);
		}
	});

	$('.poll-btn-enter').on('click', function() {
		$('form.poll-form').submit();
		return false;
	});

	$('.poll-btn-back').on('click', function() {
		$('form.poll-form, .poll-btn-enter').removeClass('hide');
		$('.poll-results, .poll-btn-back, .poll-form--error').addClass('hide');
		$('.poll-results').removeClass('animate');
		$('form.poll-form')[0].reset();
		return false;
	});   
        
    //var total = $('.poll-results').data('total') + 1;
    var total = $('.poll-results').data('total');
    var score, percent;
    $('.poll-result').each(function (index, value) {
        score = $(value).data('score');
        percent = Math.round(score / total * 100);        
        if(!isNaN(percent)){
            $(value).find('.result-bg').css('width', percent.toString() + '%');
            $(value).find('span.percent').html(percent.toString() + '%');
        }else{
            $(value).find('.result-bg').css('width', '0%');
            $(value).find('span.percent').html('0%');
        }
    });
    window.setTimeout(function () {
        $('.poll-results').addClass('animate');
    }, 10);
    
    $( document ).on( "click", "#addOption", function(e) {        
        e.preventDefault();        
        var cloneHtml = $( ".option-hide" ).clone().removeClass('option-hide').removeClass('hide').appendTo( ".option-show" );
        cloneHtml.find('input').attr('required',true);
        cloneHtml.find('input').attr('name','title[]');
    });
    
    $( document ).on( "click", "#remove", function(e) {        
        e.preventDefault();        
        $( this ).closest('.option').remove();
    });
    
    var records;   
    var urlRecordList = siteUrl + "admin/poll/getList";
    //var urlRecordEdit = siteUrl + "admin/poll/edit";
    var urlDelete = siteUrl+"admin/poll/delete";       
    var recordsColumn = [
                            {"data": "question"},
                            {"data": "short_code","bSortable": false},
                            {"data": "edit","bSortable": false},                                         
                            {"data": "delete", "bSortable": false}
                        ];                   
    
    $.fn.deleteRecord = function (recordId, obj) {                
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url : urlDelete,
            type: "POST",
            data : "id="+recordId,
            success: function(){
                $.fn.removeRow(obj);
            }
        });
    }
    
    $.fn.editRecord = function (obj) {
        
        var id = obj.attr('eid');
        var prvAttr = obj.attr('has-read');
        
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url : urlRecordEdit,
            type: "POST",
            data: {id: id, publish: prvAttr},
            success: function(data){
                if (data) {
                    obj.attr('has-read', prvAttr == '1' ? '0' : '1');
                    obj.text(prvAttr == '1' ? 'Read' : 'Unread');
                    getNotificationCount();
                }
            }
        });
    }
    
    $.fn.updateRecords = function () {                       
         $('#records tbody').on( 'click', '.clsEdit', function () {        
            $.fn.editRecord($(this));
        });
        
        $('#records tbody').on( 'click', '.clsDelete', function () {
            
        var recordId = $(this).attr('did');                                 
            
             BootstrapDialog.confirm({
                title: 'WARNING',
                message: 'Are you sure you want to delete this record?',
                type: BootstrapDialog.TYPE_DANGER, 
                closable: true, 
                draggable: true,
                btnCancelLabel: 'Cancel', 
                btnOKLabel: 'Ok',                
                callback: function (result) {                    
                    if (result) {                                                                     
                        $.fn.deleteRecord(recordId, $(this).parents('tr'));     
                        BootstrapDialog.show({ message: 'Record deleted successfully!'});                      
                    } 
                }
            });            
        });
    }    
        
    $.fn.removeRow = function (recordIndex) {       
        // Get previous pagination number
        var previousPagination= parseInt( $(".paginate_button.current").data("dt-idx") ) -1;       
              
        // Decide to redraw or not based on the presence of `.deleteBtn` elements.
        var doIdraw=false;
        if( $(".clsDelete").length == 1){
            doIdraw=true;
        }        
        // If the page redraws and a previous pagination existed (except the first)
        if(previousPagination > 1 && doIdraw){
            var previousPage = $(document).find("[data-dt-idx='" + (previousPagination) + "']").click();
        }      
        else{
            records.row(recordIndex).remove().draw(doIdraw);
        }
    }  
    
     records = $('#records').DataTable({
        responsive: true,
        "order": [[0,"desc"]],
        "autoWidth": false,
        "processing": true,
        "serverSide": true,
        "stateSave": true,
        "ajax": urlRecordList,
        "columns": recordsColumn,
        "initComplete": function(settings, j) {           
            $.fn.updateRecords();           
        },        
        "columnDefs": [{
                "targets": [1,2,3],    // Column which need to update. Index start from zero
                "render": function (data, type, row, meta) {
                    if (type === 'display') {                        
                        if (meta.col == 1) {                         
                             data = data = "<pre title='Use code to display poll in view file.'>&#123;&#33;&#33; PollFacade::displayPoll("+data+") &#33;&#33;&#125;</pre>";                                                           
                        }
                        if (meta.col == 2) {
                            data = "&nbsp;&nbsp;<a class='inline-link glyphicon glyphicon-eye-open' href='/admin/poll/show/"+row.edit+"' title='Show'></a>  &nbsp;";
                            data += "&nbsp;<a class='inline-link glyphicon glyphicon-pencil' href='/admin/poll/edit/"+row.edit+"' title='Edit'></a>";
                        }
                        if (meta.col == 3) {
                            data = "<a class='inline-link clsDelete glyphicon glyphicon-trash' href='#' did='"+data+"' title='Delete'></a>";
                        }                                                                      
                    }                                      
                    return data;
                }
            }]
    });               

});