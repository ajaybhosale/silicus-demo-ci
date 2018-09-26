$(document).ready(function () {
    var data = '';

    if (results && ' ' != results.categories_entries && results.categories_entries)
        data = $.parseJSON($("<div/>").html(results.categories_entries).text());
    else
        data = '';

    var bkColor = '';
    var txtColor = '';

    if (results && results.category && 'undefined' != typeof (results.category) && results.category.background_color)
        bkColor = results.category.background_color;
    else
        bkColor = 'pink';

    if (results && results.category && 'undefined' != typeof (results.category) && results.category.text_color)
        txtColor = results.category.text_color;
    else
        txtColor = 'yellow';

    var CSRF_TOKEN = $(document).find('#calendarEntry input[name="_token"]').val();
    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        height: 550,
        editable: true,
        eventDrop: function (event, delta, revertFunc) {
            $.ajax({
                method: "POST",
                url: results.url + "calendar/entry/store",
                data: {id: event.id, eventName: event.title, startDate: event.start.format(), endDate: event.end.format(), _token: CSRF_TOKEN, isAjax: 1}
            }).done(function (data) {
            });
        },
        eventClick: function (calEvent, jsEvent, view) {
            $.ajax({
                method: "POST",
                url: results.url + "calendar/entry/load",
                data: {id: calEvent.id, _token: CSRF_TOKEN, isAjax: 1},
                dataType: 'JSON'
            }).done(function (data)
            {
                $.fancybox({
                    type: 'inline',
                    href: '#calendarEntry',
                    width: 400,
                    height: 690,
                    overlayShow: true,
                    hideOnOverlayClick: false,
                    hideOnContentClick: false,
                    enableEscapeButton: false,
                    showCloseButton: true,
                    modal: true,
                    autoSize: false,
                    afterShow: function () {
                        $('.fancybox-skin').append('<a title="Close" class="fancybox-item fancybox-close" href="javascript:jQuery.fancybox.close();"></a>');
                    }
                });
                $(document).find('#calendarEntry button').attr('data-event-id', data['calendar_entry']['id']);
                $(document).find('#calendarEntry input[name="id"]').val(data['calendar_entry']['id']);
                $(document).find('#calendarEntry input[name="event_name"]').val(data['calendar_entry']['event_name']);
                $(document).find('#calendarEntry textarea[name="description"]').text(data['calendar_entry']['description']);
                $(document).find('#calendarEntry input[name="start_date"]').val(data['calendar_entry']['start_date'].replace(/T/g, " "));
                $(document).find('#calendarEntry input[name="end_date"]').val(data['calendar_entry']['end_date'].replace(/T/g, " "));
                $(document).find('#calendarEntry input[name="location"]').val(data['calendar_entry']['location'].replace(/T/g, " "));
                //$(document).find('#calendarEntry #email_input_id').tokenfield('setTokens', data['emails']);
                $(document).find('#calendarEntry .display_accepted').html(data['acceptedEmails']);
                $(document).find('#calendarEntry .display_invitees').html(data['emails']);
                if (data['calendar_entry']['repeat_event'] == 1) {
                    $(document).find('#event_by').val(data['calendar_entry']['event_by']);
                    $(document).find('#calendarEntry input[name="starts_on"]').val(data['calendar_entry']['starts_on']);
                    $(document).find('#calendarEntry input[name="ends_on"]').val(data['calendar_entry']['ends_on']);
                    $(document).find('#calendarEntry select[name="month_by"]').val(data['calendar_entry']['month_by']);
                    $(document).find('#calendarEntry input[name="repeat_event"]').val(data['calendar_entry']['repeat_event']);
                    $(document).find('#calendarEntry input[name="repeat_event"]').prop('checked', true).triggerHandler('click');
                    $(document).find('input:checkbox[value="' + data['calendar_entry']['week_day'] + '"]').prop('checked', true);
                    var str = data['calendar_entry']['days'];
                    var temp = new Array();
                    temp = str.split(",");
                    $.each(temp, function (index, value) {
                        $(document).find('input:checkbox[value="' + value + '"]').prop('checked', true);
                    });
                }
            });
        },
        eventSources: [
            {
                events: data,
                color: bkColor, //'pink', // an option!
                textColor: txtColor//'yellow' // an option!
            }
        ],
        eventLimit: true, // for all non-agenda views
        views: {
            agenda: {
                eventLimit: 2 // adjust to 6 only for agendaWeek/agendaDay
            }
        },
        dayClick: function (date, jsEvent, view) {
            $.fancybox({
                type: 'inline',
                href: '#calendarEntry',
                width: 400,
                height: 690,
                overlayShow: true,
                hideOnOverlayClick: false,
                hideOnContentClick: false,
                enableEscapeButton: false,
                showCloseButton: true,
                modal: true,
                autoSize: false,
                afterShow: function () {
                    $('.fancybox-skin').append('<a title="Close" class="fancybox-item fancybox-close" href="javascript:jQuery.fancybox.close();">< /a>');
                    $(document).find('#calendarEntry input[name="event_name"]').val("");
                    $(document).find('#calendarEntry textarea[name="description"]').text("");
                    $(document).find('#calendarEntry input[name="start_date"]').val(date.format() + " " + "10:00:00");
                    $(document).find('#calendarEntry input[name="end_date"]').val("");
                    $(document).find('#repeatEvent').prop("checked", false);
                    $(document).find('#calendarEntry .interval').hide();
                    $(document).find('#calendarEntry button').attr('data-event-id', '');
                }
            });
        }
    });

    $('.various').fancybox({
        closeClick: true,
        openEffect: 'none',
        closeEffect: 'none',
        width: 400,
        height: 524,
        overlayShow: true,
        hideOnOverlayClick: false,
        hideOnContentClick: false,
        enableEscapeButton: false,
        showCloseButton: true,
        modal: true,
        autoSize: false,
        afterShow: function () {
            //get id of edited record if exist
            var id = $(this.element).attr("id");
            if (id && 'undefined' != typeof (id))
            {
                $.ajax({
                    method: "POST",
                    url: results.url + "calendar/getCategory",
                    data: {id: id, _token: CSRF_TOKEN, isAjax: 1}
                }).done(function (data) {
                    console.log(data);
                    console.log('category name = ' + data.category_name);
                    $(document).find('#addCategory input[name="category_name"]').val(data.category_name);
                    $(document).find('#addCategory input[name="id"]').val(id);
                    if ('undefined' != typeof (data.description))
                        $(document).find('#addCategory input[name="description"]').val(data.description);
                    $(document).find('#addCategory input[name="text_color"]').val(data.text_color);
                    $(document).find('#addCategory input[name="background_color"]').val(data.background_color);
                });

            }
            else
                $('form#frmCategory')[0].reset();
            $('.fancybox-skin').append('<a title="Close" class="fancybox-item fancybox-close" href="javascript:jQuery.fancybox.close();"></a>');
        }
    });

    $('#start_date').datetimepicker({
        formatTime: 'g:i A',
        format: 'Y-m-d H:i:00',
        onShow: function (ct) {
            console.log('end date = ' + $('#end_date').val());
            this.setOptions({
                maxDate: $('#end_date').val() ? $('#end_date').val() : false, formatDate: 'Y-m-d H:i:00'
            })
        },
    });
    $('#starts_on').datetimepicker({
        format: 'Y-m-d',
        onClose: function (selectedDate) {
        }
    });
    $('#end_date').datetimepicker({
        formatTime: 'g:i A',
        format: 'Y-m-d H:i:00',
        onShow: function (ct) {
            console.log('start date=' + $('#start_date').val());
            this.setOptions({
                minDate: $('#start_date').val() ? $('#start_date').val() : false, formatDate: 'Y-m-d H:i:00'
            })
        },
    });
    $('#ends_on').datetimepicker({
        format: 'Y-m-d',
        onClose: function (selectedDate) {
            $("#starts_on").datetimepicker("option", "minDate", selectedDate);
        }
    });
    $('.calColorPicker').colpick({
        layout: 'hex',
        submit: 0,
        onChange: function (hsb, hex, rgb, el, bySetColor) {
            //$(el).css('border-color','#'+hex);
            // Fill the text box just if the color was set using the picker, and not the colpickSetColor function.
            if (!bySetColor)
                $(el).val('#' + hex);
        }
    }).keyup(function () {
        $(this).colpickSetColor(this.value);
    });
    for (i = 1; i <= 30; i++) {
        $('#month_by').append($('<option>', {
            value: i,
            text: i
        }));
    }

    $('#repeat_event').click(function () {
        if ($(this).is(":checked")) {
            $('.interval').fadeIn();
            $(this).val('1');
        } else {
            $('.interval').fadeOut();
            $(this).val('');
        }
        $('#event_by').trigger('change');
    });
    if ($('#repeat_event').is(":checked")) {
        $('.interval').fadeIn();
        $('#event_by').trigger('change');
        $(this).val('1');
    } else
        $(this).val('');

    $('#event_by').change(function () {
        if ($(document).find(this).val() == 'M') {
            $(document).find('.weekly').addClass('hide');
            $(document).find('.monthly').removeClass('hide');
        } else if ($(document).find(this).val() == 'W') {
            $(document).find('.monthly').addClass('hide');
            $(document).find('.weekly').removeClass('hide');
        }
    });
    $('#event_by').trigger('change');
    //Validate event form
    $(document).on('click', '#eventSubmit', function () {
        if ($('form#frmEvent').valid())
        {
            $('form#frmEvent').submit();
        }
        else
            return false;
    });
    //Validate category form
    $(document).on('click', '#categorySubmit', function () {
        if ($('form#frmCategory').valid())
        {
            $('form#frmCategory').submit();
        }
        else
            return false;
    });
    //Initialise tokenfield
    $('#email_input_id').tokenfield({minWidth: 250});
    //Remove category
    $(document).on('click', '.removeCategory', function (event) {
        var currentTarget = event.target;
        var thisObj = this;
        event.preventDefault();
        var recordId = $(this).attr('value');
        bootbox.confirm({
            message: 'Do you really want to delete this category?',
            buttons: {
                'confirm': {
                    label: 'Yes',
                },
                'cancel': {
                    label: 'No',
                }
            },
            callback: function (result) {
                if (result)
                {
                    // var id = $(this).attr('value');
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    })
                    $.ajax({
                        type: "DELETE",
                        url: results.url + '/calendar/delete/' + recordId,
                        dataType: "JSON",
                        success: function (data) {
                            console.log('Error:', data.status);
                            if (data.status == 'error')
                            {
                                bootbox.alert(data.message, function (result) {
                                    return;
                                });
                            }
                            else {
                                bootbox.alert(data.message, function (result) {
                                    window.location.reload();
                                });
                            }
                        },
                        error: function (data) {
                            console.log('Error:', data);
                            var errors = data.responseJSON;

                            $strError = '';
                            $.each(errors, function (key, value) {
                                $strError += value + ' ';

                            });

                            alert($strError);
                        }
                    });
                }
            }
        });
    });
    //Show/hide details
    $(document).on('click', '#invite_accepted', function () {
        if ($('.display_accepted').is(':visible'))
            $('.display_accepted').hide();
        else
            $('.display_accepted').show();
    });
    $(document).on('click', '#show_invitees', function () {
        if ($('.display_invitees').is(':visible'))
            $('.display_invitees').hide();
        else
            $('.display_invitees').show();
    });
    //Remove event
    $(document).on('click', '.rmvEvent', function (event) {
        var currentTarget = event.target;
        var thisObj = this;
        event.preventDefault();
        var recordId = $(this).attr('data-event-id');
        bootbox.confirm({
            message: 'Do you really want to delete this event?',
            buttons: {
                'confirm': {
                    label: 'Yes',
                },
                'cancel': {
                    label: 'No',
                }
            },
            callback: function (result) {
                if (result)
                {
                    $.ajax({
                        type: "DELETE",
                        url: results.url + '/calendar/event/delete/' + recordId,
                        data: {id: recordId, _token: CSRF_TOKEN, isAjax: 1},
                        dataType: "JSON",
                        success: function (data) {
                            if (data.status == 'error')
                            {
                                bootbox.alert(data.message, function (result) {
                                    return;
                                });
                            }
                            else {
                                bootbox.alert(data.message, function (result) {
                                    window.location.reload();
                                });
                            }
                        },
                        error: function (data) {
                            console.log('Error:', data);
                            var errors = data.responseJSON;

                            $strError = '';
                            $.each(errors, function (key, value) {
                                $strError += value + ' ';

                            });

                            alert($strError);
                        }
                    });
                }
            }
        });
    });
});

