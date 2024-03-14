jQuery( function( $ ) {
    // show event calendar
    var date_format = 'yy-mm-dd';
    if( eventprime.global_settings.datepicker_format ) {
        settings_date_format = eventprime.global_settings.datepicker_format;
        if( settings_date_format ) {
            settings_date_format = settings_date_format.split( '&' )[0];
            if( settings_date_format ) {
                date_format = settings_date_format;
            }
        }
    }

    var calendar = null;
    $( document ).ready(function () {
        let events = em_admin_calendar_event_object.cal_events;
        initilizeEventCalendar(new Date(), events);
    });

    function initilizeEventCalendar( cal_initial_date, events ){
        // set initial view
        let default_view = 'dayGridMonth';
        let calendar_views = {
            'month':    'dayGridMonth',
            'week':     'dayGridWeek',
            'day':      'dayGridDay',
            'listweek': 'listWeek',
        };
        if( eventprime.global_settings.default_cal_view ) {
            default_view = calendar_views[eventprime.global_settings.default_cal_view];
        }
        
        // hide prev and next month rows
        let hide_calendar_rows = true;
        
        // set calendar right view options
        let right_views = ['dayGridMonth','dayGridWeek','dayGridDay','listWeek'];
        
        // set column header format
        let column_header_format = 'long';
        let calendar_column_header_format = eventprime.global_settings.calendar_column_header_format;
        if( calendar_column_header_format == 'ddd' ) {
            column_header_format = 'short';
        }
        // set day max events
        let day_max_events = eventprime.global_settings.show_max_event_on_calendar_date;
        if( !day_max_events ) {
            day_max_events = 2;
        }
        // set 12 and 24 hours
        let hour12 = true;
        if( eventprime.global_settings.time_format == 'HH:mm' ){
            hour12 = false;
        }
        var calendarEl = document.getElementById( 'ep_event_calendar' );
        if( calendarEl ) {
            let eventDashLinkClicked = false;
            calendar = new FullCalendar.Calendar( calendarEl, {
                headerToolbar: {
                    left: 'prevYear,prev,next,nextYear today',
                    center: 'title',
                    right: right_views.toString()
                },
                // views: {
                //     listWeek: { buttonText: 'Agenda' }
                // },
                buttonText: {
                    listWeek: em_admin_calendar_event_object.list_week_btn_text,  
                },
                initialDate: cal_initial_date,
                initialView: default_view,
                navLinks: true, // can click day/week names to navigate views
                dayMaxEvents: day_max_events, // allow "more" link when too many events
                editable: true,
                height: "auto",
                events: events,
                showNonCurrentDates: hide_calendar_rows,
                fixedWeekCount: hide_calendar_rows,
                nextDayThreshold: '00:00',
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: hour12,
                    meridiem: 'short'
                },
                firstDay: em_admin_calendar_event_object.start_of_week,
                locale: em_admin_calendar_event_object.local,
                titleFormat: function (info) {
                    var start = formatDate(info.start.marker, em_admin_calendar_event_object.local, eventprime.global_settings.calendar_title_format); 
                    var end = formatDate(new Date(info.end.marker.getTime() - 86400000), em_admin_calendar_event_object.local, eventprime.global_settings.calendar_title_format);

                    if (start === end) {
                        return start;
                    } else {
                        return start + ' – ' + end;
                    }
                },
                dayHeaderFormat: { weekday: column_header_format },
                eventDidMount: function( info ) {
                    let light_bg_color = '';
                    if (info.event.extendedProps.hasOwnProperty('bg_color')) {
                        var epColorRgb = info.event.extendedProps.bg_color;
                        var avoid = "rgb";
                        var eprgbRemover = epColorRgb.replace(avoid, '');
                        var emColor_bg = eprgbRemover.substring(eprgbRemover.indexOf('(') + 1, eprgbRemover.indexOf(')'))
                        info.el.style.backgroundColor =  `rgba(${emColor_bg},1)`;
                        light_bg_color = info.el.style.backgroundColor;
                        info.el.style.borderColor =  `rgba(${emColor_bg},1)`;
                    }
                    var textColor = light_bg_color;
                    if ( info.event.extendedProps.hasOwnProperty( 'type_text_color' ) ) {
                        textColor = info.event.extendedProps.type_text_color;
                    }
                    if ( info.event.extendedProps.hasOwnProperty( 'event_text_color' ) ) {
                        textColor = info.event.extendedProps.event_text_color;
                    }
                    if(textColor){
                        var fc_time = info.el.querySelector('.fc-time');
                        if(fc_time){
                            fc_time.style.color = textColor;
                            if( em_calendar_data.hide_time_on_front_calendar == 1 ) {
                                fc_time.textContent = '';
                                fc_time.style.color = '';
                            }
                        }
                        var fc_title = info.el.querySelector('.fc-event-title');
                        if(fc_title){
                            fc_title.style.color = textColor;
                        }
                        var fc_list_time = info.el.querySelector('.fc-event-time');
                        if(fc_list_time){
                            fc_list_time.style.color = textColor;
                        }
                        var fc_list_title = info.el.querySelector('.fc-list-item-title');
                        if( fc_list_title ) {
                            fc_list_title.style.color = textColor;
                        }
                        var fc_list_event_time = info.el.querySelector('.fc-list-event-time');
                        if( fc_list_event_time ) {
                            fc_list_event_time.style.color = textColor;
                        }
                        var fc_list_event_dot = info.el.querySelector('.fc-list-event-dot');
                        if( fc_list_event_dot ) {
                            fc_list_event_dot.style.color = textColor;
                        }
                        var fc_list_event_title = info.el.querySelector('.fc-list-event-title');
                        if( fc_list_event_title ) {
                            fc_list_event_title.style.color = textColor;
                        }
                    }
                    $( info.el ).append( info.event.extendedProps.popup_html );
                    
                    // check if click on the popup button
                    var pop_dash_link = info.el.querySelector('.ep_event_popup_action_btn a'); 
                    if( pop_dash_link ) {
                        pop_dash_link.onclick = function(e){
                            eventDashLinkClicked = true;
                        }
                    }
                },
                eventMouseEnter: function( info ) {
                    let pop_block = info.el.querySelector( '.ep_event_detail_popup' );
                    pop_block.style.display = 'block';
                },
                eventMouseLeave: function(info){
                    let pop_block = info.el.querySelector( '.ep_event_detail_popup' );
                    pop_block.style.display = 'none';
                },
                eventClick: function(info){
                    if( eventDashLinkClicked ) return;

                    var event_data = info.event._def.extendedProps
                    //editEventPopup(info, event_data);
                },
                dateClick: function(info) {
                    calPopup(info);
                },
                eventDragStart: function(info){
                    let pop_block = info.el.querySelector('.ep_event_detail_popup');
                    pop_block.style.display = 'none';
                },
                eventDrop: function(info) {
                    dragdropEvent(info);
                }
            });
        
            calendar.render();

            let add_new_event_message = '<div class="ep-admin-notice ep-admin-notice notice notice-info ep-my-3 ep-mx-0"><p>'+em_admin_calendar_event_object.add_event_message+'</p></div>';
            jQuery( add_new_event_message ).insertAfter( '.fc-header-toolbar' );
            // front end link
            /* let frontend_link = '<div><a href="'+em_admin_calendar_event_object.frontend_event_page+'" target="_blank">'+em_admin_calendar_event_object.frontend_label+'</a></div>';
            jQuery( frontend_link ).insertAfter( '.fc-header-toolbar .fc-toolbar-chunk:last-child .fc-button-group' ); */
        }
    }
    
    function reInitilize(events){
        calendar.addEventSource(events);
        calendar.refetchEvents();
    }

    $( 'body' ).on( 'click', '.ep-admin-calendar-event-image', function( event ){
		event.preventDefault();
		const button = $(this);
		const imageId = button.next().next().val();
		const customUploader = wp.media({
			title: em_admin_calendar_event_object.image_title,
			library : {
				type : 'image'
			},
			button: {
				text: em_admin_calendar_event_object.image_text
			},
			multiple: false
		}).on( 'select', function() {
			const attachment = customUploader.state().get( 'selection' ).first().toJSON();
            $('.ep-featured-image').html('<img src="' + attachment.url + '">');
			$('#ep_featured_image_id').val(attachment.id);
            button.hide();
            $('.ep-admin-calendar-event-image-remove').show();
		});

		customUploader.on( 'open', function() {
			if( imageId ) {
			    const selection = customUploader.state().get( 'selection' );
			    attachment = wp.media.attachment( imageId );
			    attachment.fetch();
			    selection.add( attachment ? [attachment] : [] );
			}
		});
		customUploader.open();
	});

	// on remove button click
    $( 'body' ).on( 'click', '.ep-admin-calendar-event-image-remove', function( event ){
        event.preventDefault();
        const button = $(this);
        $('.ep-admin-calendar-event-image-remove').hide();
        $('.ep-admin-calendar-event-image').show();
        $('.ep-featured-image').html('');
	    $('#ep_featured_image_id').val('');
    });
    
    $( document ).on( 'click', '#ep-admin-calendar-event-submit', function(e) {
        e.preventDefault();
        let title      = $('#ep-event-title').val();
        let end_date   = $('#calendar_end_date').val();
        let start_date = $('#calendar_start_date').val();
        let validation = true;
        if( !title ) {
            $('.ep-calendar-event-error').html( em_admin_calendar_event_object.errors.title );
            $('.ep-calendar-event-error').show();
            validation = false;
        }else if( !start_date ) {
            $('.ep-calendar-event-error').html( em_admin_calendar_event_object.errors.start_date );
            $('.ep-calendar-event-error').show();
            validation = false;
        }else if( !end_date ) {
            $('.ep-calendar-event-error').html( em_admin_calendar_event_object.errors.end_date );
            $('.ep-calendar-event-error').show();
            validation = false;
        }else if( $( '#ep-calendar-enable-booking' ).is(':checked') ){
            let price = $('#calendar_booking_price').val();
            if( !price ) {
                $('.ep-calendar-event-error').html( em_admin_calendar_event_object.errors.event_price );
                $('.ep-calendar-event-error').show();
                validation = false; 
            }
            let capacity = $( '#calendar_ticket_capacity' ).val();
            if( !capacity || capacity < 1 ) {
                $('.ep-calendar-event-error').html( em_admin_calendar_event_object.errors.quantity );
                $('.ep-calendar-event-error').show();
                validation = false; 
            }
        } else{
            $('.ep-calendar-event-error').html('');
            $('.ep-calendar-event-error').hide();
            validation = true;
        }
        if( validation ) {
            var calendarEventForm = $('#ep-calendar-event-create-form');
            let event_data = { 
                action: 'ep_calendar_event_create', 
                data  : calendarEventForm.serialize()
            };
            $( '.ep-admin-calendar-loader' ).show();
            $.ajax({
                type    : "POST",
                url     : em_admin_calendar_event_object.ajaxurl,
                data    : event_data,
                success : function( response ) {
                    if( response.data.status === true ) {
                        if( $( '#ep-calendar-event-id' ).val() > 0 ) {
                            var event = calendar.getEventById( $( '#ep-calendar-event-id' ).val() );
                            event.remove();
                        }
                        reInitilize( response.data.event_data );
                        show_toast( 'success', response.data.message );
                        $('#calendarPopup').hide();
                        $( '.ep-admin-calendar-loader' ).hide();
                    } else{
                        $( '.ep-admin-calendar-loader' ).hide();
                        show_toast( 'warning', response.data.message );
                    }
                }
            });  
        }
    });    
    
    $( '#ep-calendar-all-day' ).click(function(e){
        if( $( '#ep-calendar-all-day' ).is( ':checked' ) ){
            $( '#calendar_end_date' ).attr( 'disabled', true );
            $( '#calendar_start_time' ).attr( 'disabled', true ).val('');
            $( '#calendar_end_time' ).attr( 'disabled', true ).val('');
            $( '#calendar_end_date' ).val( $( '#calendar_start_date' ).val() );
        } else{
            $( '#calendar_end_date' ).removeAttr( 'disabled' );
            $( '#calendar_start_time' ).removeAttr( 'disabled' );
            $( '#calendar_end_time' ).removeAttr( 'disabled' );
        }
    });
    
    $('#ep-calendar-enable-booking').click(function(e){
       if( $('#ep-calendar-enable-booking').is(':checked') ){
            $('#ep-calendar-event-booing-helptext, #ep-calendar-enable-booking-child').show();
            $('#calendar_booking_price').removeAttr('disabled');
            $('#calendar_ticket_capacity').removeAttr('disabled');
        }
        else{
            $('#calendar_booking_price').attr('disabled',true);
            $('#calendar_ticket_capacity').attr('disabled',true);
            $('#ep-calendar-event-booing-helptext, #ep-calendar-enable-booking-child').hide();
        } 
    });

    $( '#ep-calendar-event-delete-btn' ).click(function(e){
        e.preventDefault();
        var event_id = $(this).data('id');
        if (!confirm( 'Are you sure?' ) ) return false;
    });

    function calPopup( info ) {
        let date = FullCalendarMoment.toMoment( info.date, calendar );
        let position = info.jsEvent;
        formErrors = [];
        $( '#em_edit_event_title' ).html('');
        $( '.ep-calendar-event-error' ).html('');
        var date_format_setting = eventprime.global_settings.datepicker_format.toUpperCase();
        date_format_setting = date_format_setting.replace('YY', 'YYYY');
        date_formats = date_format_setting.split('&');
        var startDate = date.format(date_formats[0]);
        var endDate = date.format(date_formats[0]);
        $( '#calendar_start_date' ).val( startDate );
        $( '#calendar_end_date' ).val( endDate );
        $( '#calendar_start_date' ).datepicker( { 
            controlType: 'select', 
            dateFormat: date_format,
            beforeShow: function () {
                let end_date = $( '#calendar_end_date' ).val();
                if( end_date ) {
                    $( "#calendar_start_date" ).datepicker("option", {
                        maxDate: end_date
                    });
                }
            },
        } );
        $( '#calendar_end_date' ).datepicker( { 
            controlType: 'select', 
            dateFormat: date_format,
            beforeShow: function () {
                let start_date = $( '#calendar_start_date' ).val();
                if( start_date ) {
                    $( "#calendar_end_date" ).datepicker("option", {
                        minDate: start_date
                    });
                }
            },
        } );
        
        $( '.ep-calendar-model-title' ).html( em_admin_calendar_event_object.errors.popup_new );
        $( '#ep-calendar-event-id, #ep-event-title, #calendar_start_time, #calendar_end_time' ).val('');
        $( '#calendar_end_date, #calendar_start_time, #calendar_end_time, #ep-calendar-enable-booking' ).removeAttr( 'disabled' );
        $( '#ep-calendar-all-day, #ep-calendar-enable-booking' ).prop( 'checked', false );
        $( '#ep-calendar-event-booing-helptext, #ep-calendar-enable-booking-child' ).hide();
        $( '.ep-featured-image' ).html('');
        $( '#ep_featured_image_id, #calendar_ticket_capacity, #calendar_booking_price' ).val('');
        $( '#ep-calendar-booking-row' ).show();

        $( "#ep-calendar-event-type option:selected" ).prop( "selected", false );
        $( "#ep-calendar-event-type option:first" ).prop( "selected", "selected" );

        $( "#ep-calendar-venue option:selected" ).prop( "selected", false );
        $( "#ep-calendar-venue option:first" ).prop( "selected", "selected" );

        $( "#ep-calendar-status option:selected" ).prop( "selected", false );
        $( "#ep-calendar-status option:first" ).prop( "selected", "selected" );

        $( '#ui-datepicker-div' ).addClass( 'ep-ui-cal-date-modal-wrap' );
        
        topY = position.pageY - 20;
        leftX = position.pageX - 500;
        if( position.pageX < 400 ) {
            leftX += 200;
        }
        // timepicker
        $( '#calendar_end_time, #calendar_start_time' ).timepicker({
            timeFormat: 'h:i A',
            step: 15,
           
        });
        $( '#calendarPopup' ).prop( 'style',"left:" + 0 + "px;top:" + 0 + "px;" );
        $( '.ui-timepicker-wrapper' ).addClass( 'ep-ui-cal-time-modal-wrap' );
        $( '#calendarPopup' ).removeClass( 'em_edit_pop' );
        $('#calendarPopup .ep-modal-overlay').removeClass('ep-modal-overlay-fade-in').addClass('ep-modal-overlay-fade-out');
        $('#calendarPopup .ep-modal-wrap-calendar').removeClass('ep-modal-out').addClass('ep-modal-in');
    }
    
    $( document ).on('click', '.ep-modal-overlay, .ep-modal-close', function (e) {
        $('#calendarPopup .ep-modal-overlay').removeClass('ep-modal-overlay-fade-out').addClass('ep-modal-overlay-fade-in');
        $('#calendarPopup .ep-modal-wrap-calendar').removeClass('ep-modal-in').addClass('ep-modal-out');
        $("#calendarPopup").hide();
    });
    
    function dragdropEvent( info ) {
        let start_date = FullCalendarMoment.toMoment( info.event.start, calendar );
        formErrors = [];
        $("#em_edit_event_title").html('');
        var date_format_setting = eventprime.global_settings.datepicker_format.toUpperCase();
        date_format_setting = date_format_setting.replace('YY', 'YYYY');
        date_formats = date_format_setting.split('&');
        var startDate = start_date.format(date_formats[0]);
        if(info.event.end === null){
            var endDate = start_date.format(date_formats[0]);
        } else{
            let end_date = FullCalendarMoment.toMoment(info.event.end, calendar);
            var endDate = end_date.format(date_formats[0]);
        }
        var event_id = info.event.id;
        
        let dropedevent_data = { 
            action: 'ep_calendar_events_drag_event_date', 
            id  : event_id,
            start_date : startDate,
            end_date : endDate
        };
        $( '.ep-admin-calendar-loader' ).show();
        $.ajax({
            type    : "POST",
            url     : em_admin_calendar_event_object.ajaxurl,
            data    : dropedevent_data,
            success : function( response ) {
                if( response.data.status === true ){
                    var event = calendar.getEventById( info.event.id );
                    event.remove();
                    show_toast( 'success', response.data.message );
                    reInitilize(response.data.event_data);
                    $( '.ep-admin-calendar-loader' ).hide();
                } else{
                    jQuery( '.ep-admin-calendar-loader' ).hide();
                    show_toast( 'warning', response.data.message );
                }
            }
        }); 
    }
    
    function editEventPopup( info, data ) {
        $( '.ep-calendar-model-title' ).html( em_admin_calendar_event_object.errors.popup_edit );
        $( '#ep-calendar-event-id' ).val( data.event_id );
        $( '#ep-event-title' ).val( data.event_title );
        $( '#calendar_start_time' ).val( data.start_time );
        $( '#calendar_end_time' ).val( data.end_time );
        if( data.all_day === '1' ){
            $( '#ep-calendar-all-day' ).prop( 'checked' );
        }
        $( '#ep-calendar-venue option[value="'+data.venue+'"]' ).attr( "selected", "selected" );
        $( '#ep-calendar-event-type option[value="'+data.event_type+'"]' ).attr( "selected", "selected" );
        $( '.ep-featured-image' ).html( '<img src="'+data.image+'">' );
        $( '#ep_featured_image_id' ).val( data.thumbnail_id );
        $( '#ep-calendar-status option[value="'+data.status+'"]' ).attr( "selected", "selected" );
        $( '#ep-calendar-booking-row, #ep-calendar-enable-booking-child, #ep-calendar-enable-booking-capacity-child' ).hide();
        $( '#ep-calendar-enable-booking, #calendar_ticket_capacity, #calendar_booking_price' ).attr( 'disabled', true );
        
        let position = info.jsEvent;
        
        $( '#calendar_start_date' ).val( data.event_start_date );
        $( '#calendar_end_date' ).val( data.event_end_date );
        $( '#calendar_start_date' ).datepicker( { controlType: 'select',dateFormat: date_format } );
        $( '#calendar_end_date' ).datepicker( { controlType: 'select',dateFormat: date_format } );
        topY = position.pageY - 20;
        leftX = position.pageX - 500;
        if( position.pageX < 400 ) {
            leftX += 200;
        }
        // timepicker
        $( '#calendar_end_time, #calendar_start_time' ).timepicker({
            timeFormat: 'h:i A',
            step: 15,
        });
        $( '#calendarPopup' ).prop('style',"left:" + leftX + "px;top:" + topY + "px;" );
        $( '#calendarPopup' ).removeClass( 'em_edit_pop' );
        $( '#calendarPopup' ).show();
    }

    function formatDate(date, locale, pattern = 'MMMM, YYYY') {
        if (!(date instanceof Date) || isNaN(date)) {
            return ''; // Handle invalid date
        }
    
        // Construct the formatted date using Intl.DateTimeFormat options
        var options = { year: 'numeric', month: 'long', day: 'numeric' };
        var formatter = new Intl.DateTimeFormat(locale, options);
        var formattedDate = formatter.format(date);
    
        // Extract the month index using the format method
        var monthIndex = date.getMonth();
    
        var monthNames = getMonthNames(locale);
    
        var formattedPattern = pattern
            .replace('YYYY', date.getFullYear())
            .replace('MMMM', monthNames[monthIndex])
            .replace('DD', date.getDate());
    
        return formattedPattern;
    }
    
    
    function getMonthNames(locale) {
      // Use Intl.DateTimeFormat to get month names for the specified locale
      var monthNames = [];
      for (var i = 0; i < 12; i++) {
        var formattedMonth = new Date(2000, i, 1).toLocaleDateString(locale, { month: 'long' });
        monthNames.push(formattedMonth);
      }
      return monthNames;
    }
});