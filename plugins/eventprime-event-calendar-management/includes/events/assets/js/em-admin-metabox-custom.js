jQuery( function( $ ) {
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
    $( document ).ready( function() {
        $( ".ep_event_options_panel:first-of-type" ).show();

        //datepicker
        $( '.epDatePicker' ).datepicker({
            changeYear: true,
            changeMonth: true,
            dateFormat: date_format,
            gotoCurrent: true,
            showButtonPanel: true,
            beforeShow: function () {
                let elid = $(this).attr('id');
                // if end date then check for start date and set min date
                if( elid == 'em_end_date' ) {
                    let start_date = $( '#em_start_date' ).val();
                    if( start_date ) {
                        $( "#em_end_date" ).datepicker("option", {
                            minDate: start_date // set min date to start date
                        });
                    }
                }
                if( elid == 'em_recurrence_limit' ) {
                    let start_date = $( '#em_start_date' ).val();
                    if( start_date ) {
                        $( "#em_recurrence_limit" ).datepicker("option", {
                            minDate: start_date // set min date to start date
                        });
                    }
                }
                $('#ui-datepicker-div').addClass( 'ep-ui-show-on-top' );
            },
        });

        // timepicker
        $( '.epTimePicker' ).timepicker({
            timeFormat: 'h:i A',
            step: 15,
        });
        // add class to ui time picker ui-timepicker-wrapper
        $( '.epTimePicker' ).click( function() {
            if( $( '.ui-timepicker-wrapper' ).length > 0 ) {
                $( '.ui-timepicker-wrapper' ).addClass( 'ep-ui-show-on-top' );
            }
        });

        // recurring custom date option
        var cdates = [];
        $( '#ep_recurrence_custom_dates').datepicker({
            changeYear: true,
            changeMonth: true,
            dateFormat: date_format,
            gotoCurrent: true,
            showButtonPanel: true,
            controlType: 'select',
            oneLine: true,
            minDate: new Date(),
            autoClose: false,
            onSelect: function (dateText, inst) {
                addOrRemoveDate( dateText, cdates );
                setTimeout(function(){
                    $( '#recurrence_custom_dates' ).datepicker( "refresh" );
                    $( '#ui-datepicker-div' ).show();
                    $( '#hide_date_picker' ).show();
                }, 300);
            },
        }).focus(function() {
            $( '.ui-datepicker-close' ).click(function() {
                $( '#ui-datepicker-div' ).hide();
                $( '#hide_date_picker' ).hide();
            });
        });
        jQuery(document).on('click','.ep-remove-custom-date', function(e){
            var datetext = jQuery(this).parent().find('.ep-cus-date-cont').html();
            addOrRemoveDate(datetext, cdates);
            
            
        });
        $( "#accordion" ).accordion({
            collapsible: true
        });

        $( "#ep_existing_tickets_category_list, #ep_existing_tickets_list, #ep-existing-tickets-block, #ep_existing_offers_list, .ep-ticket-category-section" ).sortable();
        $( "#ep_existing_tickets_category_list, #ep_existing_tickets_list, #ep-existing-tickets-block, #ep_existing_offers_list, .ep-ticket-category-section" ).disableSelection();
        $( "#ep_existing_tickets_category_list, #ep_existing_tickets_list, #ep-existing-tickets-block, #ep_existing_offers_list, .ep-ticket-category-section" ).sortable({ axis: 'y' });

        // validate post before save
        var form = $("form[name='post']");
        $( form ).find( "input[type='submit']" ).click( function( e ){
            e.preventDefault();
            var formError = 0;
            
            // check if event start date empty
            let em_start_date = $( '#em_start_date' ).val();
            if( !em_start_date ) {
                show_toast( 'error', em_event_meta_box_object.empty_start_date );
                document.getElementById( 'em_start_date' ).focus();
                formError = 1;
            }
            // if end date not empty or end date is not less then start date
            let em_end_date = $( '#em_end_date' ).val();
            if( em_end_date && em_start_date ) {
                if( em_start_date == em_end_date ) {
                    let em_start_time = $( '#em_start_time' ).val();
                    let em_end_time = $( '#em_end_time' ).val();
                    if( em_start_time && em_end_time ) {
                        if( em_start_time == em_end_time ) {
                            show_toast( 'error', em_event_meta_box_object.same_event_start_and_end );
                            document.getElementById( 'em_end_time' ).focus();
                            formError = 1;
                        }
                    }
                }
            }

            // check if end time entered but not start time
            let em_start_time = $( '#em_start_time' ).val();
            let em_end_time = $( '#em_end_time' ).val();
            if( em_end_time ) {
                if( !em_start_time ) {
                    show_toast( 'error', em_event_meta_box_object.end_time_but_no_start_time );
                    document.getElementById( 'em_start_time' ).focus();
                    formError = 1;
                }
            }
            // check if event name empty
            let post_title = $( '#title' ).val();
            if( !post_title ) {
                show_toast( 'error', em_event_meta_box_object.empty_event_title );
                document.getElementById( 'title' ).focus();
                formError = 1;
            }

            // check for child events and then ask for update the children
            let child_events_count = $( '#ep_event_count_child_events' ).val();
            if( child_events_count > 0 && formError == 0 ) {
                let child_events_update_confirm = $( '#ep_event_child_events_update_confirm' ).val();
                if( !child_events_update_confirm ) {
                    formError = 1;
                    $( '#ep_event_recurring_update_children' ).openPopup({
                        anim: (!$( this ).attr( 'data-animation' ) || $( this ).data( 'animation' ) == null) ? 'ep-modal-' : $(this).data('animation')
                    } );
                }
            }
            
            // check for ticket if booking on. This condition should be the last check.
            let em_enable_booking = $( 'input[name=em_enable_booking]:checked' ).val();
            if( em_enable_booking == 'external_bookings' ) {
                $( '#em_custom_link_error_message' ).html( ' ' );
                let em_custom_link = $( '#ep_event_custom_link' ).val();
                if( !em_custom_link ) {
                    let requireString = get_translation_string( 'required' );
                    $( '#em_custom_link_error_message' ).html( requireString );
                    $( '#ep_event_custom_link' ).focus();
                    formError = 1;
                    return false;
                }
            }
            if( em_enable_booking == 'bookings_on' && formError == 0 ) {
                let check_tickets = 1;
                let ep_event_has_ticket = $( '#ep_event_has_ticket' ).val();
                if( ep_event_has_ticket == 0 ) {
                    formError = 1;
                    check_tickets = 0;
                }
                if( check_tickets == 0 ) {
                    $( '#ep_event_booking_turn_off_modal' ).openPopup({
                        anim: (!$(this).attr('data-animation') || $(this).data('animation') == null) ? 'ep-modal-' : $(this).data('animation')
                    } );
                }
            }
            
            //before submit check for the form error
            if( formError == 1 ){
                return false;
            } else{
                $( form ).submit();
            }
        });

        // submit event page on modal continue
        $( document ).on( 'click', '#ep_event_booking_turn_off_continue', function() {
            $( '#ep_event_booking_turn_off_loader' ).addClass( 'is-active' );
            $( '#ep_event_booking_turn_off_cancel' ).attr( 'disabled', 'disabled' );
            $( '#ep_event_booking_turn_off_continue' ).attr( 'disabled', 'disabled' );
            $( form ).submit();
        });

        // check for the recurring events update
        $( document ).on( 'click', '#ep_event_recurring_update_children_confirm', function() {
            let recurrence_confirmation = $( 'input[name=ep_event_update_recurrence_action]:checked').val();
            if( recurrence_confirmation == 'yes' ) {
                $( '#ep_event_child_events_update_confirm' ).val( 'update_children' );
            } else{
                $( '#ep_event_child_events_update_confirm' ).val( 'no-update' );
            }
            $( form ).submit();
        });

        var event_gallery_frame;
        var $image_gallery_ids = $( '#em_gallery_image_ids' );
        var $product_images = $( '#ep_event_gallery_container' ).find(
            'ul.ep_gallery_images'
        );
        // add gallery images
        $( '.ep_add_event_gallery' ).on( 'click', 'a', function ( event ) {
            var $el = $( this );

            event.preventDefault();

            // If the media frame already exists, reopen it.
            if ( event_gallery_frame ) {
                event_gallery_frame.open();
                return;
            }

            // Create the media frame.
            event_gallery_frame = wp.media.frames.product_gallery = wp.media( {
                // Set the title of the modal.
                title: $el.data( 'choose' ),
                button: {
                    text: $el.data( 'update' ),
                },
                states: [
                    new wp.media.controller.Library( {
                        title: $el.data( 'choose' ),
                        filterable: 'all',
                        multiple: true,
                    } ),
                ],
            } );

            // When an image is selected, run a callback.
            event_gallery_frame.on( 'select', function () {
                var selection = event_gallery_frame.state().get( 'selection' );
                var attachment_ids = $image_gallery_ids.val();

                selection.map( function ( attachment ) {
                    attachment = attachment.toJSON();

                    if ( attachment.id ) {
                        attachment_ids = attachment_ids
                            ? attachment_ids + ',' + attachment.id
                            : attachment.id;
                        var attachment_image =
                            attachment.sizes && attachment.sizes.thumbnail
                                ? attachment.sizes.thumbnail.url
                                : attachment.url;

                        $product_images.append(
                            '<li class="ep-gal-img" data-attachment_id="' +
                                attachment.id +
                                '"><img src="' +
                                attachment_image +
                                '" /><div class="ep-gal-img-delete"><span class="em-event-gallery-remove dashicons dashicons-trash"></span></div></li>'
                        );
                    }
                } );

                $image_gallery_ids.val( attachment_ids );
            } );

            // Finally, open the modal.
            event_gallery_frame.open();
        } );
        $( document ).on( 'click', '.em-event-gallery-remove', function(){
            var image_id = $(this).closest('li').data('attachment_id').toString();
            console.log(image_id);
            var gallery_ids = $('#em_gallery_image_ids').val();
            var galleryArr  = gallery_ids.split(',');
            for( var i = 0; i < galleryArr.length; i++){ 
                if ( galleryArr[i] === image_id) { 
                    galleryArr.splice(i, 1); 
                }
            }
            gallery_ids = galleryArr.toString();
            $('#em_gallery_image_ids').val(gallery_ids);
            $(this).closest('li').remove();

        });
        // set custom date picker
        $( document ).on( 'click', '.ep_metabox_custom_date_picker', function() {
            let start = $( this ).data( 'start' );
            let minDate = '', maxDate = '';
            // min
            let em_start_date = $( '#em_start_date' ).val()
            if( start == 'today' ){
                minDate = new Date();
            } else if( start == 'booking_start' ) {
                let ep_ticket_start_booking_custom_date = $( '#ep_ticket_start_booking_custom_date' ).val();
                if( ep_ticket_start_booking_custom_date ) {
                    minDate = ep_ticket_start_booking_custom_date;
                } else{
                    minDate = new Date();
                }
            } else if( start == 'event_start' ){
                minDate = em_start_date;
            }
            // max
            let end = $( this ).data( 'end' );
            let em_end_date = $( '#em_end_date' ).val();
            if( end == 'today' ){
                maxDate = new Date();
            } else if( end == 'event_end' ) {
                if( em_end_date ) {
                    maxDate = em_end_date;
                } else{
                    if( em_start_date ) {
                        maxDate = em_start_date;
                    } else{
                        maxDate = new Date();
                    }
                }
            } else if( end == 'booking_end' ) {
                let ep_ticket_ends_booking_date = $( '#ep_ticket_ends_booking_date' ).val();
                if( ep_ticket_ends_booking_date ) {
                    maxDate = ep_ticket_ends_booking_date;
                } else{
                    if( em_end_date ) {
                        maxDate = em_end_date;
                    } else{
                        minDate = new Date();
                    }
                }
            }
            // add datepicket
            $( '.ep_metabox_custom_date_picker').datepicker({
                changeYear: true,
                changeMonth: true,
                dateFormat: date_format,
                gotoCurrent: true,
                showButtonPanel: true,
                minDate: minDate,
                maxDate: maxDate,
                beforeShow: function () {
                    $('#ui-datepicker-div').addClass( 'ep-ui-show-on-top' );
                },
            });

            $( this ).datepicker( "show" );
        });
    });

    // show/hide panels
    $( document ).on( 'click', '.ep_event_metabox_tabs li a', function(e) {
        e.preventDefault();
        let panelSrc = $( this ).data( 'src' );
        if( $( "#"+panelSrc ).length > 0 ) {
            $( '.ep_event_metabox_tabs li' ).removeClass( 'ep-tab-active' );
            $( this ).closest( 'li' ).addClass( 'ep-tab-active' );
            $( ".ep_event_options_panel" ).hide();
            $( "#"+panelSrc ).show();
            if( panelSrc == 'ep_event_checkout_fields_data' ) {
                $( '#ep_event_attendee_fields_data' ).show();
            }
        }
    });

    // set event end date as start date if empty
    $( document ).on( 'change', '#em_start_date', function() {
        let st_val = this.value;
        let en_val = $( '#em_end_date' ).val();
        if( !en_val ) {
            $( '#em_end_date' ).val( st_val );
        }
    });

    // show/hide event start and end time on all day
    $( document ).on( 'click', '#em_all_day', function() {
        let epEventids = "#em_start_time, #em_end_date, #em_end_time, #ep_hide_event_time, #ep-hide-end-date, #ep_hide_event_end_time";
        if( $( this ).prop( 'checked' ) == true ) {
            $(epEventids).prop("disabled", true);
            $( "#em_start_time" ).addClass('ep-disabled-input');
            $( "#em_end_time" ).addClass('ep-disabled-input');
            $( "#em_end_date" ).addClass('ep-disabled-input');
            let em_start_date = $( '#em_start_date').val();
            if( em_start_date ) {
                $( '#em_end_date').val( em_start_date );
            }
        } else{
            $(epEventids).prop("disabled", false);
            $( "#em_start_time" ).removeClass('ep-disabled-input');
            $( "#em_end_time" ).removeClass('ep-disabled-input');
            $( "#em_end_date" ).removeClass('ep-disabled-input');
        }
    });
    
  
    // show/hide event start time from frontend    
    $(document).on('click', '#ep_hide_event_time', function () {
        if ($(this).is(":checked")) {
            $("#ep-start-time-hidden").fadeIn(500);
        } else {
            $("#ep-start-time-hidden").fadeOut(500);
        }
    });
    
    // hide every thing if start time is hide
    $(document).on('click', '#ep-hide-start-date', function () {
        if($(this).is(":checked")){ 
            $("#ep_hide_event_time").prop('checked', true);
            $('#ep-start-date-hidden').fadeIn(500);
            $("#ep-start-time-hidden").fadeIn(500);
            if( $( '#ep-hide-end-date' ).prop( 'checked') == false ) {
                $( '#ep-hide-end-date' ).trigger( 'click' );
            }
            // show date placeholder option
            $("#ep-date-note").fadeIn(500);
        }else{
            $('#ep-start-date-hidden').fadeOut(500);
            $("#ep-date-note").fadeOut();
        } 
    });

    // show/hide event End time from frontend
    $(document).on('click', '#ep_hide_event_end_time', function () {
        if ($(this).is(":checked")) {
            $("#ep-end-time-hidden").fadeIn(500);
        } else {
            $("#ep-end-time-hidden").fadeOut(500);
        }
    });
    
    $(document).on('click', '#ep-hide-end-date', function () {
        if($(this).is(":checked")){ 
            $("#ep_hide_event_end_time").prop('checked', true);
            $('#ep-end-time-hidden').fadeIn( 500 );
            $("#ep-end-date-hidden").fadeIn( 500 );
        } else{
            $('#ep-end-date-hidden').fadeOut( 500 );
        }
    });

    // show/hide custom note option
    $( document ).on( 'click', 'input[name=em_event_date_placeholder]', function() {
        if( $( '#ep-date-custom-note' ).prop( 'checked') == true ){
            $( '#ep-date-custom-note-content' ).fadeIn( 500 );
        } else{
            $( '#ep-date-custom-note-content' ).fadeOut( 500 );
        }
    });
    
    // Show id Additional Dates Question Wrapper
    $( document ).on( 'click', '#ep-add-more-dates', function() {
        if ($("#ep-add-more-dates").is(":checked")) {
            $(".ep-additional-date-wrapper").fadeIn();
        } else {
            $(".ep-additional-date-wrapper").fadeOut();
        }
    });

    // add new additional date row
    $( document ).on( 'click', '#add_new_date_field', function() {
        let total_rows = $( '#ep-event-additional-date-wrapper .ep-additional-date-row' ).length;
        let next_row = total_rows + 1;
        let next_row_id = 'ep-additional-date-row'+next_row;
        let newdate = '';
        newdate += '<div class=" ep-box-row ep-py-3 ep-mb-3 ep-items-end ep-additional-date-row" id="'+ next_row_id +'">';
            newdate += '<input type="hidden" name="em_event_add_more_dates['+next_row+'][uid]" value="'+Date.now()+'">';
            newdate += '<div class="ep-box-col-3 ep-meta-box-data">';
                newdate += '<label class="ep-form-label">' + em_event_meta_box_object.additional_date_text + '</label>';
                newdate += '<div class="ep-event-start-time">';
                    newdate += '<input type="text" name="em_event_add_more_dates['+next_row+'][date]" class="ep-form-control epDatePicker" autocomplete="off">';
                newdate += '</div>';
            newdate += '</div>';

            newdate += '<div class="ep-box-col-3 ep-meta-box-data">';
                newdate += '<label class="ep-form-label">' + em_event_meta_box_object.additional_time_text + ' ' + em_event_meta_box_object.optional_text + '</label>';
                newdate += '<div class="ep-event-start-time">';
                    newdate += '<input type="text" name="em_event_add_more_dates['+next_row+'][time]" class="ep-form-control epTimePicker" autocomplete="off">';
                newdate += '</div>';
            newdate += '</div>';

            newdate += '<div class="ep-box-col-3 ep-meta-box-data">';
                newdate += '<label class="ep-form-label">' + em_event_meta_box_object.additional_label_text + '</label>';
                newdate += '<div class="ep-event-start-time">';
                    newdate += '<input type="text" name="em_event_add_more_dates['+next_row+'][label]" placeholder="'+ em_event_meta_box_object.additional_label_text +'" class="ep-form-control ep-ad-event-label" autocomplete="off">';
                newdate += '</div>';
            newdate += '</div>';

            newdate += '<div class="ep-box-col-3 ">';
                newdate += '<a href="javascript:void(0)" class="ep-remove-additional-date ep-item-delete" data-parent_id="'+next_row_id+'">Delete</a>';
            newdate += '</div>';

        newdate += '</div>';

        $( '#ep-event-additional-date-wrapper' ).append( newdate );
        //datepicker
        $( '#'+ next_row_id +' .epDatePicker' ).datepicker({
            changeYear: true,
            changeMonth: true,
            dateFormat: date_format,
            gotoCurrent: true,
            showButtonPanel: true,
        });
        // timepicker
        $( '#'+ next_row_id +' .epTimePicker' ).timepicker({
            timeFormat: 'h:i A',
            step: 15,
        });
    });

    $( document ).on( 'click', '.ep-remove-additional-date', function(){
        let dateid = $( this ).data( 'parent_id' );
        if( $( '#'+dateid ).length > 0 ) {
            $( '#'+dateid ).remove();
        }
    })

    // show/hide recurreces
    $( document ).on( 'click', '#ep_enable_recurrence', function() {
        if( $( this ).prop( 'checked' ) == true ) {
            // first check if event start & end date is set or not
            let ev_start_date = $( '#em_start_date' ).val();
            let ev_end_date = $( '#em_end_date' ).val();
            if( !ev_start_date || !ev_end_date ) {
                $('<div>' + em_event_meta_box_object.before_event_recurrence + '<div>').dialog({ 
                    modal:true, 
                    width:600,
                    dialogClass: "em-schedule-no-close",
                    closeOnEscape: false,
                    buttons: {
                        Ok: function() {
                            $( this ).dialog( "close" );
                            $( '#ep_enable_recurrence' ).prop( 'checked', false );
                            $( '.ep_event_date_time a' ).trigger( 'click' );
                        }
                    },
                }); 
            } else{
                $( "#ep_show_recurring_options" ).show();
                let em_recurrence_limit = $( '#em_recurrence_limit' ).val();
                if( !em_recurrence_limit ) {
                    let ev_start_date = $( '#em_start_date' ).val();
                    if( ev_start_date ) {
                        $( '#em_recurrence_limit' ).val( ev_start_date );
                    } else{
                        let newDateVal = new Date();
                        let day = newDateVal.getDate();
                        let month = newDateVal.getMonth() + 1;
                        let year = newDateVal.getFullYear();
                        $( '#em_recurrence_limit' ).val( year + '-' + month + '-' + day );
                    }
                }
            }
        } else{
            // check if event have child events.
            let child_events_count = $( '#ep_event_count_child_events' ).val();
            if( child_events_count && child_events_count > 0 ) {
                // we need to show a prompt message that child events may be deleted.
                $('<div>' + em_event_meta_box_object.repeat_child_event_prompt + '<div>').dialog({ 
                    modal:true, 
                    width:600,
                    dialogClass: "em-schedule-no-close",
                    closeOnEscape: false,
                    buttons: {
                        Ok: function() {
                            $( this ).dialog( "close" );
                            $( "#ep_show_recurring_options" ).hide();
                        },
                        Cancel: function() {
                            $( '#ep_enable_recurrence' ).prop( 'checked', true );
                            $( this ).dialog( "close" );
                        }
                    },
                });
            } else{
                $( "#ep_show_recurring_options" ).hide();
            }
        }
    });

    // enable/disable the end date field
    $( 'input[type=radio][name=em_recurrence_ends]' ).change( function() {
        let endVal = $( this ).val();
        $( '#em_recurrence_limit' ).attr( 'disabled', true );
        $( '#em_recurrence_occurrence_time' ).attr( 'disabled', true );
        if( endVal == 'on' ) {
            $( '#em_recurrence_limit' ).attr( 'disabled', false );
        }
        if( endVal == 'after' ) {
            $( '#em_recurrence_occurrence_time' ).attr( 'disabled', false );
        }
    });

    // show/hide recurring interval related options
    $( document ).on( 'change', '#em_recurrence_interval', function() {
        let intervalVal = $( this ).val();
        $( '#em_show_weekly_options' ).hide();
        $( '#em_show_monthly_options' ).hide();
        $( '#em_show_yearly_options' ).hide();
        $( '#em_show_advanced_options' ).hide();
        $( '#em_show_custom_dates_options' ).hide();
        $( '#em_recurrence_step' ).show();
        $( '#ep_event_recurrence_end_options' ).show();
        $( '#ep_event_repeats_every_step' ).show();
        if( intervalVal == 'weekly' ) {
            $( '#em_show_weekly_options' ).show();
        }
        if( intervalVal == 'monthly' ) {
            $( '#em_show_monthly_options' ).show();
        }
        if( intervalVal == 'yearly' ) {
            $( '#em_show_yearly_options' ).show();
        }
        if( intervalVal == 'advanced' ) {
            $( '#em_show_advanced_options' ).show();
        }
        if( intervalVal == 'custom_dates' ) {
            $( '#em_show_custom_dates_options' ).show();
            $( '#em_recurrence_step' ).hide();
            $( '#ep_event_recurrence_end_options' ).hide();
            $( '#ep_event_repeats_every_step' ).hide();
        }
    });

    // advanced recurring options
    $( document ).on( 'click', '.ep-recurrence-advanced-week-day', function() {
        let ep_recurrence_advanced_dates = [];
        ep_recurrence_advanced_dates = $( '#ep_recurrence_advanced_dates' ).val();
        if( $( this ).hasClass( 'active' ) ) {
            $( this ).removeClass( 'active' );
        } else{
            $( this ).addClass( 'active' );
        }

        let week_num = $( this ).data( 'week_num' );
        let day_num = $( this ).data( 'day_num' );
        let week_day_data = day_num + '-' + week_num;
        if( week_day_data ) {
            if( ep_recurrence_advanced_dates ) {
                ep_recurrence_advanced_dates = JSON.parse( ep_recurrence_advanced_dates );
                let dates_indx = ep_recurrence_advanced_dates.indexOf( week_day_data );
                if( dates_indx > -1 ) {
                    ep_recurrence_advanced_dates.splice( dates_indx, 1 );
                } else{
                    ep_recurrence_advanced_dates.push( week_day_data );
                }
            } else{
                ep_recurrence_advanced_dates = [week_day_data];
            }
            $( '#ep_recurrence_advanced_dates' ).val( JSON.stringify( ep_recurrence_advanced_dates ) );
        }
    });

    // show/hide scheduling
    $( document ).on( 'click', '#em_enable_schedule', function() {
        if( $( this ).prop( 'checked' ) == true ) {
            // first check if event start & end date is set or not
            let ev_start_date = $( '#em_start_date' ).val();
            let ev_end_date = $( '#em_end_date' ).val();
            if( !ev_start_date || !ev_end_date ) {
                $('<div>' + em_event_meta_box_object.before_event_scheduling + '<div>').dialog({ 
                    modal:true, 
                    width:600,
                    dialogClass: "em-schedule-no-close",
                    closeOnEscape: false,
                    buttons: {
                        Ok: function() {
                            $( this ).dialog( "close" );
                            $( '#em_enable_schedule' ).prop( 'checked', false );
                            $( '.ep_event_date_time a' ).trigger( 'click' );
                        }
                    },
                }); 
            } else{
                $( "#ep_show_scheduling_options" ).show();

                // event schedule datepicker
                $( '.hasScheduleDatePicker' ).datepicker({
                    changeYear: true,
                    changeMonth: true,
                    dateFormat: date_format,
                    gotoCurrent: true,
                    showButtonPanel: true,
                    minDate: new Date( $( '#em_start_date' ).val() ),
                    maxDate: new Date( $( '#em_end_date' ).val() ),
                    onSelect: function (dateText, inst) {
                        $( '.em-add-date-schedule' ).show();
                    },
                });
            }
        } else{
            $( "#ep_show_scheduling_options" ).hide();
        }
    });

    // show day block for scheduling
    var sdates = [];
    $( document ).on( 'click', '.em-add-date-schedule', function() {
        let schDate = $( '#em_schedule_date' ).val();
        if( sdates.indexOf( schDate ) < 0 ) {
            sdates.push( schDate );
            let daySchedule = '<div class="ep-add-hourly-section" id="ep_hourly_section_' + sdates.length + '">';
                daySchedule += '<div class="ep-schedule-date-section">';
                    daySchedule += '<h4 class="ep-sch-date">' + schDate + '</h4>';
                    daySchedule += '<div class="ep-meta-box-title">' + em_event_meta_box_object.add_day_title_label + '</div>';
                    daySchedule += '<div class="ep-meta-box-data">';
                        daySchedule += '<div class="ep-event-schedule-day-title">';
                            daySchedule += '<input type="text" name="em_schedule_day_title['+ schDate +'][]" placeholder="' + em_event_meta_box_object.add_day_title_label + '">';
                        daySchedule += '</div>';
                        daySchedule += '<button type="button" class="ep-sch-add-hour-btn" data-schedule_day="' + schDate + '" data-schedule_section="' + sdates.length + '">' + em_event_meta_box_object.add_schedule_btn + '</button>';
                    daySchedule += '</div>';
                daySchedule += '</div>';
                daySchedule += '<div class="ep-schedule-hourly-section"></div>';
            daySchedule += '</div>';
            $( '.ep-event-hourly-schedule-wrapper' ).append( daySchedule );
            $( '#em_schedule_date' ).val('');
        }
    });

    // add new hourly row
    $( document ).on( 'click', '.ep-sch-add-hour-btn', function() {
        let schedule_day = $( this ).data( 'schedule_day' );
        let schedule_section = $( this ).data( 'schedule_section' );
        let total_rows = $( '#ep_hourly_section_' + schedule_section + ' .ep-schedule-hourly-section .ep-hourly-row' ).length;
        let next_row = total_rows + 1;
        let hourlyRow = '<div class="ep-hourly-row" id="ep_hourly_section_'+ schedule_section +'_row_'+ next_row +'">';
            hourlyRow += '<div class="ep-meta-box-data">';
                hourlyRow += '<div class="ep-schedule-hourly-start-time">';
                    hourlyRow += '<input class="hasSchTimePicker" type="text" name="em_schedule_hourly_start_time['+ schedule_day +'][]" placeholder="' + em_event_meta_box_object.start_time_label + '">';
                hourlyRow += '</div>';
                hourlyRow += '<div class="ep-schedule-hourly-end-time">';
                    hourlyRow += '<input class="hasSchTimePicker" type="text" name="em_schedule_hourly_end_time['+ schedule_day +'][]" placeholder="' + em_event_meta_box_object.end_time_label + '">';
                hourlyRow += '</div>';
                hourlyRow += '<div class="ep-schedule-hourly-title">';
                    hourlyRow += '<input type="text" name="em_schedule_hourly_title['+ schedule_day +'][]" placeholder="' + em_event_meta_box_object.add_day_title_label + '">';
                hourlyRow += '</div>';
                hourlyRow += '<div class="ep-schedule-hourly-description">';
                    hourlyRow += '<input type="text" name="em_schedule_hourly_description['+ schedule_day +'][]" placeholder="' + em_event_meta_box_object.description_label + '">';
                hourlyRow += '</div>';
                hourlyRow += '<div class="ep-schedule-hourly-icon">';
                    hourlyRow += '<input type="hidden" name="em_schedule_hourly_icon['+ schedule_day +'][]">';
                    hourlyRow += '<div class="ep-material-icon">';
                        hourlyRow += '<div class="ep-material-icon-search">';
                            hourlyRow += '<p>'+em_event_meta_box_object.icon_text+'</p>';
                        hourlyRow += '</div>';
                        hourlyRow += '<ul>';
                            for( let i = 0; i < em_event_meta_box_object.material_icons.length; i++ ){
                               hourlyRow += '<li class="ep-'+schedule_section+'-'+next_row+'-color-li" data-section="'+schedule_section+'" data-row="'+next_row+'" data-icon_name="'+em_event_meta_box_object.material_icons[i]+'"><i class="material-icons">' + em_event_meta_box_object.material_icons[i] + '</i></li>';
                            };
                        hourlyRow += '</ul>';
                        hourlyRow += '<div class="ep-material-icon-color">';
                            hourlyRow += em_event_meta_box_object.icon_color_text + ' <input type="button" data-jscolor="{value:000000, onChange: changeIconColor(this)}" id="ep_hourly_section_' + schedule_section + '_color_' + next_row + '" data-section="'+schedule_section+'" data-row="'+next_row+'"><input type="hidden" class="ep-hourly-icon-color" name="em_schedule_hourly_icon_color['+ schedule_day +'][]" placeholder="Icon Color" />';
                        hourlyRow += '</div>';
                    hourlyRow += '</div>';
                hourlyRow += '</div>';
                hourlyRow += '<div class="ep-schedule-hourly-performer">';
                hourlyRow += '<label>Select Performer</label>';
                    hourlyRow += '<select multiple="multiple" name="em_schedule_hourly_performer['+ schedule_day +'][]" id="ep_hourly_section_' + schedule_section + '_performer_' + next_row + '">';
                        for( let j = 0; j < em_event_meta_box_object.performers_data.length; j++ ){
                            hourlyRow += '<option value="'+em_event_meta_box_object.performers_data[j].id+'">'+em_event_meta_box_object.performers_data[j].name+'</option>';
                        }
                    hourlyRow += '</select>';
                hourlyRow += '</div>';
                hourlyRow += '<button type="button" class="ep-sch-remove-hourly-row" data-schedule_day_remove="'+ schedule_day +'">' + em_event_meta_box_object.remove_label + '</button>';
            hourlyRow += '</div>';
        hourlyRow += '</div>';

        $( '#ep_hourly_section_' + schedule_section + ' .ep-schedule-hourly-section' ).append( hourlyRow );
        $( '#ep_hourly_section_' + schedule_section + '_row_' + next_row + ' .hasSchTimePicker' ).trigger( 'click' );
        // initialize color picker
        var myColor = new jscolor( $( '#ep_hourly_section_' + schedule_section + '_color_' + next_row )[0] );
        myColor.fromString('#000000');

        $('#ep_hourly_section_' + schedule_section + '_performer_' + next_row).select2({
            theme: "classic"
        });
    });

    // remove hourly row
    $( document ).on( 'click', '.ep-sch-remove-hourly-row', function() {
        $( this ).closest( '.ep-hourly-row' ).remove();
    });

    $( document ).on( 'click', '.hasSchTimePicker', function(){
        $(this).timepicker({
            timeFormat: 'h:i A',
            step: 15,
        });
    });

    // callback after click on the icon
    $( document ).on( 'click', '.ep-material-icon ul li', function() {
        $( '.ep-material-icon ul li' ).removeClass( 'active' );
        $( this ).addClass( 'active' );
        let icon_name = $(this).data('icon_name');
        let section = $(this).data('section');
        let row = $(this).data('row');
        let currentColor = $( '#ep_hourly_section_'+ section +'_color_'+ row ).attr( 'data-current-color' );
        $( '.ep-'+section+'-'+row+'-color-li' ).css( 'background-color', '' );
        $( '.ep-'+section+'-'+row+'-color-li' ).css( 'color', '#666666' );
        $( this ).css( 'background-color', currentColor );
        $( this ).css( 'color', '#FFFFFF' );
    });
    
    // Adds a date if we don't have it yet, else remove it
    function addOrRemoveDate( date, cdates ) {
        var index = $.inArray( date, cdates );
        if ( index >= 0 ) {
            console.log('IOP');
            cdates = removeDate(index, cdates);
        } else{ 
            cdates = addDate(date, cdates);
        }

        let cdate_html = '';
        if( cdates.length > 0 ) {
            cdates.forEach(function ( cus_date, index ) {
                cdate_html += '<span class="ep-event-recurring-custom-date ep-fw-bold ep-mr-2 ep-mb-2  ep-px-2 ep-py-1 ep-border ep-rounded"><span class="ep-cus-date-cont">' + cus_date + '</span><span class="ep-remove-custom-date">&times;</span></span>';
            });
        }
        $( ".ep_selected_dates_data" ).html( cdate_html );
        $( '#ep_recurrence_selected_custom_dates' ).val( JSON.stringify( cdates ) );
    }

    function padNumber( number ) {
        var ret = new String( number );
        if ( ret.length == 1 ) ret = "0" + ret;
        return ret;
    }
    // add date into date array
    function addDate( date, cdates ) {
        if ( $.inArray( date, cdates ) < 0 ) {
            cdates.push( date );
        }
        return cdates;
    }
    // remove date from date array
    function removeDate( index, cdates ) {
        cdates.splice( index, 1 );
        return cdates;
    }
    
    /** Checkout fields */
    // show/hide name sub fields
    $( document ).on( 'click', '#em_event_checkout_name_popup', function() {
        if( $( this ).prop( 'checked' ) == true ) {
            $( '.ep-event-checkout-field-name-sub-row' ).show( 1000 );
        } else{
            $( '.ep-event-checkout-field-name-sub-row' ).hide( 1000 );
        }
    });

    // add checkout fields in event
    $( document ).on( 'click', '#ep_save_checkout_attendee_fields', function() {
        var original_data = $( '#ep_event_checkout_attendee_fields_modal' ).find(
			'input, select, textarea'
		);

        let fields_data = original_data.serialize().split('&');
        let attendee_fields = '';
        $( '#ep_event_checkout_attendee_fields_modal .ep-error-message' ).html( '' );
        if( fields_data.length > 0 && fields_data[0] ){
            // first check for name field
            if( $( '#em_event_checkout_name_popup' ).is( ':checked' ) ) {
                let attendee_name_checkbox_added = 0;
                if( $( '#em_event_checkout_name_first_name_popup' ).is( ':checked' ) ) {
                    attendee_fields += '<div class="ep-box-col-12 ep-bg-white" id="ep_event_checkout_fields_first_name_top">';
                        attendee_fields += '<div class="ep-border ep-rounded-1 ep-p-2 ep-mx-2 ep-mb-2 ep-d-flex ep-align-items-center ep-text-small">';
                        if( attendee_name_checkbox_added == 0 ) {
                            attendee_fields += '<input type="checkbox" name="em_event_checkout_name" value="1" class="ep-form-check-input" id="em_event_checkout_name" checked="checked" style="display:none;">';
                            attendee_name_checkbox_added = 1;
                        }
                        attendee_fields += '<input type="checkbox" name="em_event_checkout_name_first_name" value="1" id="em_event_checkout_name_first_name" checked="checked" style="display:none;">';
                        attendee_fields +='<div class="ep-d-inline-block ep-checkout-field-drag"><span class="material-icons ep-fs-6">drag_indicator</span></div>'
                        attendee_fields += '<div class="ep-d-inline-block ep-ml-3 ep-checkout-field-name">'+$( '#em_event_checkout_name_first_name_popup' ).data( 'label' )+'</div>';
                        attendee_fields += '<div class="ep-d-inline-block ep-mx-auto ep-text-muted">Text</div>';
                        attendee_fields += '<div class="ep-field-options-expand ep-d-inline-block ep-ms-auto"><span class="material-icons ep-cursor ep-event-checkout-fields-expand" data-id="ep_event_checkout_fields_first_name_expand">expand_more</span></div>';
                     attendee_fields += '</div>';
                    attendee_fields += '</div>';
                    attendee_fields += '<div class="ep-box-col-12 ep-event-checkout-fields-expand-section" id="ep_event_checkout_fields_first_name_expand">';
                        attendee_fields +='<div class="checkout-field-options ep-border ep-rounded-1 ep-p-2 ep-py-4 ep-d-flex ep-justify-content-between ep-mx-2 ep-mb-2 ep-text-small">'
                            attendee_fields += '<div class="ep-event-checkout-selected-fields-attributes">';
                            attendee_fields += '<label for="em_event_checkout_name_first_name_required">';
                                if( $( '#em_event_checkout_name_first_name_required_popup' ).is( ':checked' ) ){
                                    attendee_fields += '<input type="checkbox" name="em_event_checkout_name_first_name_required" id="em_event_checkout_name_first_name_required" value="1" checked="checked">';
                                } else{
                                    attendee_fields += '<input type="checkbox" name="em_event_checkout_name_first_name_required" id="em_event_checkout_name_first_name_required" value="1">';
                                }
                                attendee_fields += '<span>'+$( '#em_event_checkout_name_first_name_required_popup' ).data( 'label')+'</span>';
                            attendee_fields += '</label>';
                        attendee_fields += '</div>';
                        attendee_fields += '<div class="ep-event-checkout-selected-fields-remove ep-mt-auto ep-text-end" data-parent-id="ep_event_checkout_fields_first_name_expand"><button type="button" name="'+em_event_meta_box_object.remove_label+'" class="ep-event-checkout-fields-remove button button-large" data-main_id="ep_event_checkout_fields_first_name">'+em_event_meta_box_object.remove_label+'</button></div>';
                         attendee_fields +='</div>'
                        attendee_fields += '</div>';
                }
                if( $( '#em_event_checkout_name_middle_name_popup' ).is( ':checked' ) ) {
                    attendee_fields += '<div class="ep-box-col-12 ep-bg-white" id="ep_event_checkout_fields_middle_name_top">';
                      attendee_fields +='<div class="ep-border ep-rounded-1 ep-p-2 ep-mx-2 ep-mb-2 ep-d-flex ep-align-items-center ep-text-small">';  
                       if( attendee_name_checkbox_added == 0 ) {
                            attendee_fields += '<input type="checkbox" name="em_event_checkout_name" value="1" class="ep-form-check-input" id="em_event_checkout_name" checked="checked" style="display:none;">';
                            attendee_name_checkbox_added = 1;
                        }
                        attendee_fields += '<input type="checkbox" name="em_event_checkout_name_middle_name" value="1" id="em_event_checkout_name_middle_name" checked="checked" style="display:none;">';
                        attendee_fields +='<div class="ep-d-inline-block ep-checkout-field-drag"><span class="material-icons ep-fs-6">drag_indicator</span></div>'
                        attendee_fields += '<div class="ep-d-inline-block ep-ml-3 ep-checkout-field-name">'+$( '#em_event_checkout_name_middle_name_popup' ).data( 'label' )+'</div>';
                        attendee_fields += '<div class="ep-d-inline-block ep-mx-auto ep-text-muted">Text</div>';
                        attendee_fields += '<div class="ep-field-options-expand ep-d-inline-block ep-ms-auto"><span class="material-icons ep-cursor ep-event-checkout-fields-expand" data-id="ep_event_checkout_fields_middle_name_expand">expand_more</span></div>';
                     attendee_fields +='</div>';
                    attendee_fields += '</div>';
                    attendee_fields += '<div class="ep-box-col-12 ep-event-checkout-fields-expand-section" id="ep_event_checkout_fields_middle_name_expand">';
                        attendee_fields += '<div class="checkout-field-options ep-border ep-rounded-1 ep-p-2 ep-py-4 ep-d-flex ep-justify-content-between ep-mx-2 ep-mb-2 ep-text-small">';  
                            attendee_fields += '<div class="ep-event-checkout-selected-fields-attributes">';
                                attendee_fields += '<label for="em_event_checkout_name_middle_name_required">';
                                    if( $( '#em_event_checkout_name_middle_name_required_popup' ).is( ':checked' ) ){
                                        attendee_fields += '<input type="checkbox" name="em_event_checkout_name_middle_name_required" id="em_event_checkout_name_middle_name_required" value="1" checked="checked">';
                                    } else{
                                        attendee_fields += '<input type="checkbox" name="em_event_checkout_name_middle_name_required" id="em_event_checkout_name_middle_name_required" value="1">';
                                    }
                                    attendee_fields += '<span>'+$( '#em_event_checkout_name_middle_name_required_popup' ).data( 'label')+'</span>';
                                attendee_fields += '</label>';
                            attendee_fields += '</div>';
                            attendee_fields += '<div class="ep-event-checkout-selected-fields-remove ep-mt-auto ep-text-end" data-parent-id="ep_event_checkout_fields_middle_name_expand"><button type="button" name="'+em_event_meta_box_object.remove_label+'" class="ep-event-checkout-fields-remove button button-large" data-main_id="ep_event_checkout_fields_middle_name">'+em_event_meta_box_object.remove_label+'</button></div>';
                        attendee_fields += '</div>';
                    attendee_fields += '</div>';
                }
                if( $( '#em_event_checkout_name_last_name_popup' ).is( ':checked' ) ) {
                    attendee_fields += '<div class="ep-box-col-12 ep-bg-white" id="ep_event_checkout_fields_last_name_top">';
                      attendee_fields +='<div class="ep-border ep-rounded-1 ep-p-2 ep-mx-2 ep-mb-2 ep-d-flex ep-align-items-center ep-text-small">'  
                       if( attendee_name_checkbox_added == 0 ) {
                            attendee_fields += '<input type="checkbox" name="em_event_checkout_name" value="1" class="ep-form-check-input" id="em_event_checkout_name" checked="checked" style="display:none;">';
                            attendee_name_checkbox_added = 1;
                        }
                        attendee_fields += '<input type="checkbox" name="em_event_checkout_name_last_name" value="1" id="em_event_checkout_name_last_name" checked="checked" style="display:none;">';
                        attendee_fields +='<div class="ep-d-inline-block ep-checkout-field-drag"><span class="material-icons ep-fs-6">drag_indicator</span></div>';
                        attendee_fields += '<div class="ep-d-inline-block ep-ml-3 ep-checkout-field-name">'+$( '#em_event_checkout_name_last_name_popup' ).data( 'label' )+'</div>';
                        attendee_fields += '<div class="ep-d-inline-block ep-mx-auto ep-text-muted">Text</div>';
                        attendee_fields += '<div class="ep-field-options-expand ep-d-inline-block ep-ms-auto"><span class="material-icons ep-cursor ep-event-checkout-fields-expand" data-id="ep_event_checkout_fields_last_name_expand">expand_more</span></div>';
                      attendee_fields +='</div>' 
                    attendee_fields += '</div>';
                    attendee_fields += '<div class="ep-box-col-12 ep-event-checkout-fields-expand-sectionn" id="ep_event_checkout_fields_last_name_expand">';
                        attendee_fields += '<div class="checkout-field-options ep-border ep-rounded-1 ep-p-2 ep-py-4 ep-d-flex ep-justify-content-between ep-mx-2 ep-mb-2 ep-text-small">';
                            attendee_fields += '<div class="ep-event-checkout-selected-fields-attributes">';
                                attendee_fields += '<label for="em_event_checkout_name_last_name_required">';
                                    if( $( '#em_event_checkout_name_last_name_required_popup' ).is( ':checked' ) ){
                                        attendee_fields += '<input type="checkbox" name="em_event_checkout_name_last_name_required" id="em_event_checkout_name_last_name_required" value="1" checked="checked">';
                                    } else{
                                        attendee_fields += '<input type="checkbox" name="em_event_checkout_name_last_name_required" id="em_event_checkout_name_last_name_required" value="1">';
                                    }
                                    attendee_fields += '<span>'+$( '#em_event_checkout_name_last_name_required_popup' ).data( 'label')+'</span>';
                                attendee_fields += '</label>';
                            attendee_fields += '</div>';
                            attendee_fields += '<div class="ep-event-checkout-selected-fields-remove ep-mt-auto ep-text-end" data-parent-id="ep_event_checkout_fields_last_name_expand"><button type="button" name="'+em_event_meta_box_object.remove_label+'" class="ep-event-checkout-fields-remove button button-large" data-main_id="ep_event_checkout_fields_last_name">'+em_event_meta_box_object.remove_label+'</button></div>';
                        attendee_fields += '</div>';
                    attendee_fields += '</div>';
                }
            }
            // checkout fields selection
            if( $( '.em_event_checkout_field_ids:checked' ).length > 0 ) {
                // updated code
                $( '.em_event_checkout_field_ids:checked' ).each( function() {
                    let field_id = $( this ).val();
                    let field_label = $( this ).data( 'label' );
                    let field_type = $( this ).data( 'type' );
                    attendee_fields += '<div class="ep-box-col-12 ep-bg-white" id="ep_event_checkout_fields_data_'+field_id+'_top">';
                        attendee_fields += '<div class="ep-border ep-rounded-1 ep-p-2 ep-mx-2 ep-mb-2 ep-d-flex ep-align-items-center ep-text-small">';
                            attendee_fields += '<input type="checkbox" name="em_event_checkout_fields_data[]" id="ep_event_checkout_fields_data_'+field_id+'" value="'+field_id+'" checked="checked" style="display:none;">';
                            attendee_fields += '<div class="ep-d-inline-block ep-checkout-field-drag"><span class="material-icons ep-fs-6">drag_indicator</span></div>'
                            attendee_fields += '<div class="ep-d-inline-block ep-ml-3 ep-checkout-field-name">'+field_label+'</div>';
                            attendee_fields += '<div class="ep-d-inline-block ep-mx-auto ep-text-muted">'+field_type+'</div>';
                            attendee_fields += '<div class="ep-field-options-expand ep-d-inline-block ep-ms-auto"><span class="material-icons ep-cursor ep-event-checkout-fields-expand" data-id="ep_event_checkout_fields_data_'+field_id+'_expand">expand_more</span></div>';
                        attendee_fields += '</div>';
                    attendee_fields += '</div>';
                    attendee_fields += '<div class="ep-box-col-12 ep-event-checkout-fields-expand-section" id="ep_event_checkout_fields_data_'+field_id+'_expand">';
                        attendee_fields += '<div class="checkout-field-options ep-border ep-rounded-1 ep-p-2 ep-py-4 ep-d-flex ep-justify-content-between ep-mx-2 ep-mb-2 ep-text-small">';
                            attendee_fields += '<div class="ep-event-checkout-selected-fields-attributes">';
                                attendee_fields += '<label for="ep_event_checkout_fields_data_required_'+field_id+'">';
                                    if( $( '#ep_event_checkout_field_required_' + field_id ).is( ':checked' ) === true ) {
                                        attendee_fields += '<input type="checkbox" name="em_event_checkout_fields_data_required[]" id="ep_event_checkout_fields_data_required_'+field_id+'" value="'+field_id+'" checked="checked"> ';
                                    } else{
                                        attendee_fields += '<input type="checkbox" name="em_event_checkout_fields_data_required[]" id="ep_event_checkout_fields_data_required_'+field_id+'" value="'+field_id+'"> ';
                                    }
                                    attendee_fields += '<span>'+ em_event_meta_box_object.required_text +'</span>';
                                attendee_fields += '</label>';
                            attendee_fields += '</div>';
                            if( em_event_meta_box_object.enabled_attendees_list == 1 ) {
                                attendee_fields += '<div class="ep-event-checkout-selected-fields-attributes">';
                                    attendee_fields += '<label for="ep_event_checkout_fields_data_show_attendee_list_'+field_id+'">';
                                        if( $( '#ep_event_checkout_field_show_attendee_list_' + field_id ).is( ':checked' ) === true ) {
                                            attendee_fields += '<input type="checkbox" name="em_event_checkout_fields_data_show_attendee_list[]" id="ep_event_checkout_fields_data_show_attendee_list_'+field_id+'" value="'+field_id+'" checked="checked"> ';
                                        } else{
                                            attendee_fields += '<input type="checkbox" name="em_event_checkout_fields_data_show_attendee_list[]" id="ep_event_checkout_fields_data_show_attendee_list_'+field_id+'" value="'+field_id+'"> ';
                                        }
                                        attendee_fields += '<span>'+ em_event_meta_box_object.show_in_attendees_list_text +'</span>';
                                    attendee_fields += '</label>';
                                attendee_fields += '</div>';
                            }
                            attendee_fields += '<div class="ep-event-checkout-selected-fields-remove ep-mt-auto ep-text-end" data-parent-id="ep_event_checkout_fields_data_'+field_id+'_expand"><button type="button" name="'+em_event_meta_box_object.remove_label+'" class="ep-event-checkout-fields-remove button button-large" data-main_id="ep_event_checkout_fields_data_'+field_id+'">'+em_event_meta_box_object.remove_label+'</button></div>';
                        attendee_fields += '</div>';
                    attendee_fields += '</div>';
                });
            }
        } else{
            $( '#ep_event_checkout_attendee_fields_modal .ep-error-message' ).html( em_event_meta_box_object.one_checkout_field_req );
            return false;
        }
        $('body').removeClass('ep-modal-open-body');
        if( attendee_fields ) {
            $( '#ep_event_checkout_attendee_fields_container' ).html( attendee_fields );
            $( '#ep_event_checkout_attendee_fields_container' ).show();
            // close the attendee checkout field modal
            $( '#ep_event_checkout_attendee_fields_modal' ).closePopup({
                anim: (!$(this).attr('data-animation') || $(this).data('animation') == null) ? 'ep-modal-' : $(this).data('animation')
            });
        } else{
            if( $( '#em_event_checkout_name_popup' ).is( ':checked' ) ){
                $( '#ep_event_checkout_attendee_fields_modal .ep-error-message' ).html( em_event_meta_box_object.no_name_field_option );
            } else{
                $( '#ep_event_checkout_attendee_fields_modal .ep-error-message' ).html( em_event_meta_box_object.one_checkout_field_req );
            }
            return false;
        }
    });

    // checkout fields name checkbox action
    $( document ).on( 'click', '.ep-event-checkout-name-field input[type="checkbox"]', function() {
        let name = $( this ).attr( 'name' );
        if( $( this ).prop( 'checked' ) == true ) {
            $( 'input[name='+name+']' ).prop( 'checked', true );
        } else{
            $( 'input[name='+name+']' ).prop( 'checked', false );
        }
    });

    // toggle checkout field
    $( document ).on( 'click', '.ep-event-checkout-fields-expand', function() {
        let field_id = $( this ).data( 'id' );
        if( field_id ) {
            if( $( '#' + field_id ).css( 'display' ) == 'block' ) {
                $( '#' + field_id ).css( 'display', 'none' );
                $( this ).html( 'expand_more' );
            } else{
                $( '#' + field_id ).css( 'display', 'block' );
                $( this ).html( 'expand_less' );
            }
        }
    });

    // remove checkout field
    $( document ).on( 'click', '.ep-event-checkout-fields-remove', function() {
        let field_main_id = $( this ).data( 'main_id' );
        if( $( '#' + field_main_id + '_expand' ).length > 0 ) {
            $( '#' + field_main_id + '_expand' ).remove();
        }
        if( $( '#' + field_main_id + '_top' ).length > 0 ) {
            $( '#' + field_main_id + '_top' ).remove();
        }
    });

    //show name required sub fields
    /* $( document ).on( 'click', '.ep-name-sub-fields', function() {
        let field_type = $( this ).data( 'field_type' );
        $( '.ep-'+ field_type +'-required' ).toggle();
    }); */
    // remove selected checkout fields
    $( document ).on( 'click', '.ep-checkout-field-remove', function() {
        let field_id = $( this ).data( 'field_id' );
        if( field_id ) {
            $( '#em_event_checkout_field_id_'+field_id ).prop( 'checked', false );
        }
        $( this ).closest( 'li' ).remove();
    });

    // add checkout fixed fields dialog

    // show/hide terms sub fields
    $( document ).on( 'click', '#em_event_checkout_fixed_terms', function() {
        if( $( this ).prop( 'checked' ) == true ) {
            $( '.ep-event-terms-sub-fields' ).show( 1000 );
        } else{
            $( '.ep-event-terms-sub-fields' ).hide( 1000 );
        }
    });

    // checked on the include if clicked on the required
    // first name
    $( document ).on( 'click', '#em_event_checkout_name_first_name_required_popup', function() {
        if( $( this ).is( ':checked' ) ) {
            if( $( '#em_event_checkout_name_first_name_popup' ).prop( 'checked' ) == false ) {
                $( '#em_event_checkout_name_first_name_popup' ).prop( 'checked', true );
            }
        }
    });
    $( document ).on( 'click', '#em_event_checkout_name_first_name_popup', function() {
        if( !$( this ).is( ':checked' ) ) {
            if( $( '#em_event_checkout_name_first_name_required_popup' ).prop( 'checked' ) == true ) {
                $( '#em_event_checkout_name_first_name_required_popup' ).prop( 'checked', false );
            }
        }
    });
    // middle name
    $( document ).on( 'click', '#em_event_checkout_name_middle_name_required_popup', function() {
        if( $( this ).is( ':checked' ) ) {
            if( $( '#em_event_checkout_name_middle_name_popup' ).prop( 'checked' ) == false ) {
                $( '#em_event_checkout_name_middle_name_popup' ).prop( 'checked', true );
            }
        }
    });
    $( document ).on( 'click', '#em_event_checkout_name_middle_name_popup', function() {
        if( !$( this ).is( ':checked' ) ) {
            if( $( '#em_event_checkout_name_middle_name_required_popup' ).prop( 'checked' ) == true ) {
                $( '#em_event_checkout_name_middle_name_required_popup' ).prop( 'checked', false );
            }
        }
    });
    // last name
    $( document ).on( 'click', '#em_event_checkout_name_last_name_required_popup', function() {
        if( $( this ).is( ':checked' ) ) {
            if( $( '#em_event_checkout_name_last_name_popup' ).prop( 'checked' ) == false ) {
                $( '#em_event_checkout_name_last_name_popup' ).prop( 'checked', true );
            }
        }
    });
    $( document ).on( 'click', '#em_event_checkout_name_last_name_popup', function() {
        if( !$( this ).is( ':checked' ) ) {
            if( $( '#em_event_checkout_name_last_name_required_popup' ).prop( 'checked' ) == true ) {
                $( '#em_event_checkout_name_last_name_required_popup' ).prop( 'checked', false );
            }
        }
    });
    
    // other checkout fields
    $( document ).on( 'click', '.em_event_checkout_field_ids', function() {
        let checkout_field_id = $( this ).val();
        if( checkout_field_id ) {
            if( !$( this ).is( ':checked' ) ) {
                if( $( '#ep_event_checkout_field_required_' + checkout_field_id ).prop( 'checked' ) == true ) {
                    $( '#ep_event_checkout_field_required_' + checkout_field_id ).prop( 'checked', false );
                }
                if( $( '#ep_event_checkout_field_show_attendee_list_' + checkout_field_id ).length > 0 ) {
                    $( '#ep_event_checkout_field_show_attendee_list_' + checkout_field_id ).attr( 'disabled', 'disabled' );
                }
            } else{
                if( $( '#ep_event_checkout_field_show_attendee_list_' + checkout_field_id ).length > 0 ) {
                    $( '#ep_event_checkout_field_show_attendee_list_' + checkout_field_id ).removeAttr( 'disabled' );
                }
            }
        }
    });
    $( document ).on( 'click', '.em_event_checkout_field_requires', function() {
        if( $( this ).is( ':checked' ) ) {
            let checkout_field_id = $( this ).data( 'field_id' );
            if( checkout_field_id ) {
                if( $( '#em_event_checkout_field_id_' + checkout_field_id ).prop( 'checked' ) == false ) {
                    $( '#em_event_checkout_field_id_' + checkout_field_id ).prop( 'checked', true );
                    if( $( '#ep_event_checkout_field_show_attendee_list_' + checkout_field_id ).length > 0 ) {
                        $( '#ep_event_checkout_field_show_attendee_list_' + checkout_field_id ).removeAttr( 'disabled' );
                    }
                }
            }
        }
    });

    // enable/disable terms options
    $( document ).on( 'click', '.ep-terms-sub-fields', function() {
        $( '.ep-event-terms-options' ).attr( 'disabled', 'disabled');
        $( '.ep-sub-field-terms-content-options' ).hide();
        let terms_type = $( this ).data( 'terms_type' );
        if( terms_type == 'content' ){
            $( '.ep-sub-field-terms-content-options' ).show();
        } else{
            $( '#em_event_checkout_terms_'+terms_type ).removeAttr( 'disabled' );
        }
    });
    
    // add checkout fixed fields
    $( document ).on( 'click', '#ep_save_checkout_fixed_fields', function() {
        let em_fixed_content_html = '';
        $( '#ep_event_checkout_fixed_fields_modal .ep-error-message' ).html( '' );
        let booking_fields_exists = '';
        if( $( '#em_event_checkout_fixed_terms' ).is( ':checked' ) ){
            booking_fields_exists = 1;
        }
        var original_data = $( '#ep_event_checkout_booking_fields_table' ).find(
			'input, select, textarea'
		);
        let booking_fields_data = original_data.serialize().split('&');
        if( booking_fields_data.length > 0 && booking_fields_data[0] ){
            booking_fields_exists = 1;
        }
        if( booking_fields_exists ){
            let requireString = get_translation_string( 'required' );
            if( $( '#em_event_checkout_fixed_terms' ).is( ':checked' ) ){
                let em_event_checkout_fixed_terms = $( '#em_event_checkout_fixed_terms' ).val();
                let em_event_checkout_terms_label = $( '#em_event_checkout_terms_label' ).val();
                if( !em_event_checkout_terms_label ) {
                    $( '#ep_event_checkout_fixed_fields_modal #ep_fixed_field_label_error' ).html( requireString );
                    document.getElementById( 'em_event_checkout_terms_label' ).focus();
                    return false;
                }
                let em_event_checkout_terms_option = $( 'input[name="em_event_checkout_terms_option"]:checked' ).val();
                if( !em_event_checkout_terms_option ) {
                    $( '#ep_event_checkout_fixed_fields_modal #ep_event_fixed_field_bottom_error' ).html( em_event_meta_box_object.fixed_field_term_option_required );
                    return false;
                }
                let em_event_checkout_terms_options_value = '';
                if( em_event_checkout_terms_option == 'page' ) {
                    em_event_checkout_terms_options_value = $( '#em_event_checkout_terms_page' ).val();
                    if( !em_event_checkout_terms_options_value ) {
                        $( '#ep_event_checkout_fixed_fields_modal #ep_fixed_field_page_option_error' ).html( requireString );
                        document.getElementById( 'em_event_checkout_terms_page' ).focus();
                        return false;
                    }
                } else if( em_event_checkout_terms_option == 'url' ) {
                    em_event_checkout_terms_options_value = $( '#em_event_checkout_terms_url' ).val();
                    if( !em_event_checkout_terms_options_value ) {
                        $( '#ep_event_checkout_fixed_fields_modal #ep_fixed_field_url_option_error' ).html( requireString );
                        document.getElementById( 'em_event_checkout_terms_url' ).focus();
                        return false;
                    }
                    if( !is_valid_url( em_event_checkout_terms_options_value ) ) {
                        let invalidUrlString = get_translation_string( 'invalid_url' );
                        $( '#ep_event_checkout_fixed_fields_modal #ep_fixed_field_url_option_error' ).html( invalidUrlString );
                        document.getElementById( 'em_event_checkout_terms_url' ).focus();
                        return false;
                    }
                } else if( em_event_checkout_terms_option == 'content' ) {
                    if( $( '#description' ).is(':visible') ) {
                        em_event_checkout_terms_options_value = $('#description').val();
                    } else{
                        em_event_checkout_terms_options_value = tinymce.get('description').getContent();   
                    }
                    if( !em_event_checkout_terms_options_value ) {
                        $( '#ep_event_checkout_fixed_fields_modal #ep_fixed_field_custom_option_error' ).html( requireString );
                        return false;
                    }
                }
            
                em_fixed_content_html += '<div class="ep-box-col-12 ep-bg-white" id="ep_event_checkout_fields_fixed_terms_top">';
                    em_fixed_content_html += '<div class="ep-border ep-rounded-1 ep-p-2 ep-mx-2 ep-mb-2 ep-d-flex ep-align-items-center ep-text-small">';
                        em_fixed_content_html += '<input type="checkbox" name="em_event_checkout_fixed_terms_enabled" value="1" id="em_event_checkout_fixed_terms_enabled" checked="checked" style="display:none;">';
                        em_fixed_content_html += '<input type="hidden" name="em_event_checkout_fixed_terms_label" value="'+em_event_checkout_terms_label+'">';
                        em_fixed_content_html += '<div class="ep-d-inline-block ep-checkout-field-drag"><span class="material-icons ep-fs-6">drag_indicator</span></div>'
                        em_fixed_content_html += '<div class="ep-d-inline-block ep-ml-3 ep-checkout-field-name">' + em_event_checkout_terms_label + '</div>';
                        em_fixed_content_html += '<div class="ep-d-inline-block ep-mx-auto ep-text-muted">' + em_event_checkout_terms_option +'</div>';
                        em_fixed_content_html += '<div class="ep-field-options-expand ep-d-inline-block ep-ms-auto"><span class="material-icons ep-cursor ep-event-checkout-fields-expand" data-id="ep_event_checkout_fields_fixed_terms_expand">expand_more</span></div>';
                    em_fixed_content_html += '</div>';
                em_fixed_content_html += '</div>';

                em_fixed_content_html += '<div class="ep-box-col-12 ep-event-checkout-fields-expand-section" id="ep_event_checkout_fields_fixed_terms_expand">';
                    em_fixed_content_html += '<input type="hidden" name="em_event_checkout_fixed_terms_option" value="'+em_event_checkout_terms_option+'" >';
                    em_fixed_content_html += '<input type="hidden" name="em_event_checkout_fixed_terms_content" value="'+em_event_checkout_terms_options_value+'" >';
                    em_fixed_content_html += '<div class="checkout-field-options ep-border ep-rounded-1 ep-p-2 ep-py-4 ep-d-flex ep-justify-content-between ep-mx-2 ep-mb-2 ep-text-small">'
                        em_fixed_content_html += '<div class="ep-event-checkout-selected-fields-attributes">';
                            if( em_event_checkout_terms_option == 'page' ) {
                                em_fixed_content_html += '<label class="">Page: ' + em_event_meta_box_object.all_site_data[em_event_checkout_terms_options_value] + '</label>';
                            }
                            if( em_event_checkout_terms_option == 'url' ) {
                                em_fixed_content_html += '<span class="em-event-checkout-fixed-terms-option">Url: ' + em_event_checkout_terms_options_value + '</span>';
                            }
                            if( em_event_checkout_terms_option == 'content' ) {
                                em_fixed_content_html += '<span class="em-event-checkout-fixed-terms-option">Content: ' + em_event_checkout_terms_options_value + '</span>';
                            }
                        em_fixed_content_html += '</div>';
                        em_fixed_content_html += '<div class="ep-event-checkout-selected-fields-remove ep-mt-auto ep-text-end" data-parent-id="ep_event_checkout_fields_fixed_terms_expand">';
                            em_fixed_content_html += '<button type="button" name="'+em_event_meta_box_object.remove_label+'" class="ep-event-checkout-fields-remove button button-large" data-main_id="ep_event_checkout_fields_fixed_terms">';
                                em_fixed_content_html += em_event_meta_box_object.remove_label;
                            em_fixed_content_html += '</button>';
                        em_fixed_content_html += '</div>';
                    em_fixed_content_html += '</div>';
                em_fixed_content_html += '</div>';
            }

            // booking fields selection
            if( $( '.em_event_booking_field_ids:checked' ).length > 0 ) {
                // updated code
                $( '.em_event_booking_field_ids:checked' ).each( function() {
                    let field_id = $( this ).val();
                    let field_label = $( this ).data( 'label' );
                    let field_type = $( this ).data( 'type' );
                    em_fixed_content_html += '<div class="ep-box-col-12 ep-bg-white" id="ep_event_booking_fields_data_'+field_id+'_top">';
                        em_fixed_content_html += '<div class="ep-border ep-rounded-1 ep-p-2 ep-mx-2 ep-mb-2 ep-d-flex ep-align-items-center ep-text-small">';
                            em_fixed_content_html += '<input type="checkbox" name="em_event_booking_fields_data[]" id="ep_event_booking_fields_data_'+field_id+'" value="'+field_id+'" checked="checked" style="display:none;">';
                            em_fixed_content_html += '<div class="ep-d-inline-block ep-booking-field-drag"><span class="material-icons ep-fs-6">drag_indicator</span></div>'
                            em_fixed_content_html += '<div class="ep-d-inline-block ep-ml-3 ep-booking-field-name">'+field_label+'</div>';
                            em_fixed_content_html += '<div class="ep-d-inline-block ep-mx-auto ep-text-muted">'+field_type+'</div>';
                            em_fixed_content_html += '<div class="ep-field-options-expand ep-d-inline-block ep-ms-auto"><span class="material-icons ep-cursor ep-event-booking-fields-expand" data-id="ep_event_booking_fields_data_'+field_id+'_expand">expand_more</span></div>';
                        em_fixed_content_html += '</div>';
                    em_fixed_content_html += '</div>';
                    em_fixed_content_html += '<div class="ep-box-col-12 ep-event-booking-fields-expand-section" id="ep_event_booking_fields_data_'+field_id+'_expand">';
                        em_fixed_content_html += '<div class="booking-field-options ep-border ep-rounded-1 ep-p-2 ep-py-4 ep-d-flex ep-justify-content-between ep-mx-2 ep-mb-2 ep-text-small">';
                            em_fixed_content_html += '<div class="ep-event-booking-selected-fields-attributes">';
                                em_fixed_content_html += '<label for="ep_event_booking_fields_data_required_'+field_id+'">';
                                    if( $( '#ep_event_booking_field_required_' + field_id ).is( ':checked' ) === true ) {
                                        em_fixed_content_html += '<input type="checkbox" name="em_event_booking_fields_data_required[]" id="ep_event_booking_fields_data_required_'+field_id+'" value="'+field_id+'" checked="checked"> ';
                                    } else{
                                        em_fixed_content_html += '<input type="checkbox" name="em_event_booking_fields_data_required[]" id="ep_event_booking_fields_data_required_'+field_id+'" value="'+field_id+'"> ';
                                    }
                                    em_fixed_content_html += '<span>'+ em_event_meta_box_object.required_text +'</span>';
                                em_fixed_content_html += '</label>';
                            em_fixed_content_html += '</div>';

                            /* if( em_event_meta_box_object.enabled_attendees_list == 1 ) {
                                em_fixed_content_html += '<div class="ep-event-booking-selected-fields-attributes">';
                                    em_fixed_content_html += '<label for="ep_event_booking_fields_data_show_attendee_list_'+field_id+'">';
                                        if( $( '#ep_event_booking_field_show_attendee_list_' + field_id ).is( ':checked' ) === true ) {
                                            em_fixed_content_html += '<input type="checkbox" name="em_event_booking_fields_data_show_attendee_list[]" id="ep_event_booking_fields_data_show_attendee_list_'+field_id+'" value="'+field_id+'" checked="checked"> ';
                                        } else{
                                            em_fixed_content_html += '<input type="checkbox" name="em_event_booking_fields_data_show_attendee_list[]" id="ep_event_booking_fields_data_show_attendee_list_'+field_id+'" value="'+field_id+'"> ';
                                        }
                                        em_fixed_content_html += '<span>'+ em_event_meta_box_object.show_in_attendees_list_text +'</span>';
                                    em_fixed_content_html += '</label>';
                                em_fixed_content_html += '</div>';
                            } */

                            em_fixed_content_html += '<div class="ep-event-booking-selected-fields-remove ep-mt-auto ep-text-end" data-parent-id="ep_event_booking_fields_data_'+field_id+'_expand"><button type="button" name="'+em_event_meta_box_object.remove_label+'" class="ep-event-checkout-fields-remove button button-large" data-main_id="ep_event_booking_fields_data_'+field_id+'">'+em_event_meta_box_object.remove_label+'</button></div>';
                        em_fixed_content_html += '</div>';
                    em_fixed_content_html += '</div>';
                });
            }
        } else{
            $( '#ep_event_checkout_fixed_fields_modal #ep_event_fixed_field_bottom_error' ).html( em_event_meta_box_object.one_booking_field_req );
            return false;
        }
        $( '#ep_event_checkout_fixed_fields_container' ).html( em_fixed_content_html );
        $( '#ep_event_checkout_fixed_fields_container' ).show();
        $( '#ep_event_checkout_fixed_fields_modal' ).closePopup({
            anim: (!$(this).attr('data-animation') || $(this).data('animation') == null) ? 'ep-modal-' : $(this).data('animation')
        });
        $( 'body' ).removeClass( 'ep-modal-open-body' );
    });

    // toggle booking field
    $( document ).on( 'click', '.ep-event-booking-fields-expand', function() {
        let field_id = $( this ).data( 'id' );
        if( field_id ) {
            if( $( '#' + field_id ).css( 'display' ) == 'block' ) {
                $( '#' + field_id ).css( 'display', 'none' );
                $( this ).html( 'expand_more' );
            } else{
                $( '#' + field_id ).css( 'display', 'block' );
                $( this ).html( 'expand_less' );
            }
        }
    });

    // remove booking field
    $( document ).on( 'click', '.ep-event-booking-fields-remove', function() {
        let field_main_id = $( this ).data( 'main_id' );
        if( $( '#' + field_main_id + '_expand' ).length > 0 ) {
            $( '#' + field_main_id + '_expand' ).remove();
        }
        if( $( '#' + field_main_id + '_top' ).length > 0 ) {
            $( '#' + field_main_id + '_top' ).remove();
        }
    });

    // other booking fields
    $( document ).on( 'click', '.em_event_booking_field_ids', function() {
        let booking_field_id = $( this ).val();
        if( booking_field_id ) {
            if( !$( this ).is( ':checked' ) ) {
                if( $( '#ep_event_booking_field_required_' + booking_field_id ).prop( 'checked' ) == true ) {
                    $( '#ep_event_booking_field_required_' + booking_field_id ).prop( 'checked', false );
                }
                if( $( '#ep_event_booking_field_show_attendee_list_' + booking_field_id ).length > 0 ) {
                    $( '#ep_event_booking_field_show_attendee_list_' + booking_field_id ).attr( 'disabled', 'disabled' );
                }
            } else{
                if( $( '#ep_event_booking_field_show_attendee_list_' + booking_field_id ).length > 0 ) {
                    $( '#ep_event_booking_field_show_attendee_list_' + booking_field_id ).removeAttr( 'disabled' );
                }
            }
        }
    });
    $( document ).on( 'click', '.em_event_booking_field_requires', function() {
        if( $( this ).is( ':checked' ) ) {
            let booking_field_id = $( this ).data( 'field_id' );
            if( booking_field_id ) {
                if( $( '#em_event_booking_field_id_' + booking_field_id ).prop( 'checked' ) == false ) {
                    $( '#em_event_booking_field_id_' + booking_field_id ).prop( 'checked', true );
                    if( $( '#ep_event_booking_field_show_attendee_list_' + booking_field_id ).length > 0 ) {
                        $( '#ep_event_booking_field_show_attendee_list_' + booking_field_id ).removeAttr( 'disabled' );
                    }
                }
            }
        }
    });
    
    //Countdowns tab
    
    countdownActivates();
    $("#ep-countdown-activates-on").change(countdownActivates);
    
    countdownCountto();
    $("#ep-countdown-countto-on").change(countdownCountto);
    
    function countdownActivates() {
        var activateType = $("#ep-countdown-activates-on").val();
        if ( activateType === "right_away" ) {
            $("#ep-countdown-activates-date, #ep-countdown-activates-time, #ep-countdown-activates-days, #ep-countdown-activates-relative-logic, #ep-countdown-activates-event-date").hide();
        }
        if ( activateType === "custom_date" ) {
            $("#ep-countdown-activates-date, #ep-countdown-activates-time").fadeIn();     
            $("#ep-countdown-activates-days, #ep-countdown-activates-relative-logic, #ep-countdown-activates-event-date").fadeOut();
        }
        if ( activateType === "event_date" ) {
            $("#ep-countdown-activates-date, #ep-countdown-activates-time, #ep-countdown-activates-days, #ep-countdown-activates-relative-logic").fadeOut();        
            $("#ep-countdown-activates-event-date").fadeIn();
        }
        if ( activateType === "relative_date" ) {
            $("#ep-countdown-activates-date, #ep-countdown-activates-time").fadeOut();        
            $("#ep-countdown-activates-days, #ep-countdown-activates-relative-logic, #ep-countdown-activates-event-date").fadeIn();
        }
    }
    
    function countdownCountto() {
        var activateType = $("#ep-countdown-countto-on").val();
        if (activateType === "custom_date") {
            $("#ep-countdown-countto-date, #ep-countdown-countto-time").fadeIn();     
            
            $("#ep-countdown-countto-days, #ep-countdown-countto-relative-logic, #ep-countdown-countto-event-date").fadeOut();
        }
        
        if (activateType === "event_date") {
            $("#ep-countdown-countto-date, #ep-countdown-countto-time, #ep-countdown-countto-days, #ep-countdown-countto-relative-logic").fadeOut();     
            $("#ep-countdown-countto-event-date").fadeIn();
        }
        if (activateType === "relative_date") {
            $("#ep-countdown-countto-date, #ep-countdown-countto-time").fadeOut();        
            $("#ep-countdown-countto-days, #ep-countdown-countto-relative-logic, #ep-countdown-countto-event-date").fadeIn();
        }
    }

    // save the countdown temporary
    $( document ).on( 'click', '#ep-event-countdown-save', function() {
        var countdown_data = $( '#ep-event-countdown-wrap' ).find(
			'input, select, textarea'
		);
        let countdown_timer = {};
        let countdown_data_params = new URLSearchParams( countdown_data.serialize() );
        let em_countdown_name = countdown_data_params.get( 'em_countdown_name' );
        if( !em_countdown_name ) {
            let requireString = get_translation_string( 'required' );
            $( '#ep_event_countdown_name_error' ).html( requireString );
            return false;
        }
        countdown_timer.em_countdown_name = em_countdown_name;
        $( 'input[name=em_countdown_name]').val( '' );
        // countdown activate on
        let em_countdown_activate_on = '', em_countdown_activate_on_date = '', em_countdown_activate_on_time = '', em_countdown_activate_on_event_options = '', em_countdown_activate_on_days = '', em_countdown_activate_on_days_options = '';
        em_countdown_activate_on = countdown_data_params.get( 'em_countdown_activate_on' );
        if( em_countdown_activate_on ) {
            countdown_timer.em_countdown_activate_on = em_countdown_activate_on;
            $( 'select[name="em_countdown_activate_on"] option:first' ).attr( 'selected', 'selected' );
            $( 'select[name="em_countdown_activate_on"]' ).trigger( 'change' );
            if( em_countdown_activate_on == 'custom_date' ){
                em_countdown_activate_on_date = countdown_data_params.get( 'em_countdown_activate_on_date' );
                if( em_countdown_activate_on_date ) {
                    countdown_timer.em_countdown_activate_on_date = em_countdown_activate_on_date;
                    $( 'input[name="em_countdown_activate_on_date"]').val( '' );
                }
                em_countdown_activate_on_time = countdown_data_params.get( 'em_countdown_activate_on_time' );
                if( em_countdown_activate_on_time ) {
                    countdown_timer.em_countdown_activate_on_time = em_countdown_activate_on_time;
                    $( 'input[name="em_countdown_activate_on_time"]').val( '' );
                }
            } else if( em_countdown_activate_on == 'event_date' ){
                em_countdown_activate_on_event_options = countdown_data_params.get( 'em_countdown_activate_on_event_options' );
                if( em_countdown_activate_on_event_options ) {
                    countdown_timer.em_countdown_activate_on_event_options = em_countdown_activate_on_event_options;
                    $( 'select[name="em_countdown_activate_on_event_options"] option:first' ).attr( 'selected', 'selected' );
                }
            } else if( em_countdown_activate_on == 'relative_date' ){
                em_countdown_activate_on_days = countdown_data_params.get( 'em_countdown_activate_on_days' );
                if( em_countdown_activate_on_days ) {
                    countdown_timer.em_countdown_activate_on_days = em_countdown_activate_on_days;
                    $( 'input[name="em_countdown_activate_on_days"]').val( '' );
                }
                em_countdown_activate_on_days_options = countdown_data_params.get( 'em_countdown_activate_on_days_options' );
                if( em_countdown_activate_on_days_options ) {
                    countdown_timer.em_countdown_activate_on_days_options = em_countdown_activate_on_days_options;
                    $( 'select[name="em_countdown_activate_on_days_options"] option:first' ).attr( 'selected', 'selected' );
                }
                em_countdown_activate_on_event_options = countdown_data_params.get( 'em_countdown_activate_on_event_options' );
                if( em_countdown_activate_on_event_options ) {
                    countdown_timer.em_countdown_activate_on_event_options = em_countdown_activate_on_event_options;
                    $( 'select[name="em_countdown_activate_on_event_options"] option:first' ).attr( 'selected', 'selected' );
                }
            }
        }
        
        // countdown end on
        let em_countdown_count_to = '', em_countdown_count_to_date = '', em_countdown_count_to_time = '', em_countdown_count_to_event_options = '', em_countdown_count_to_days = '', em_countdown_count_to_days_options = '';
        em_countdown_count_to = countdown_data_params.get( 'em_countdown_count_to' );
        if( em_countdown_count_to == 'custom_date' ) {
            countdown_timer.em_countdown_count_to = em_countdown_count_to;
            $( 'select[name="em_countdown_count_to"] option:first' ).attr( 'selected', 'selected' );
            $( 'select[name="em_countdown_count_to"]' ).trigger( 'change' );
            if( em_countdown_count_to == 'custom_date' ){
                em_countdown_count_to_date = countdown_data_params.get( 'em_countdown_count_to_date' );
                if( em_countdown_count_to_date ) {
                    countdown_timer.em_countdown_count_to_date = em_countdown_count_to_date;
                    $( 'input[name="em_countdown_count_to_date"]').val( '' );
                }
                em_countdown_count_to_time = countdown_data_params.get( 'em_countdown_count_to_time' );
                if( em_countdown_count_to_time ) {
                    countdown_timer.em_countdown_count_to_time = em_countdown_count_to_time;
                    $( 'input[name="em_countdown_count_to_time"]').val( '' );
                }
            }
        } else if( em_countdown_count_to == 'event_date' ){
            em_countdown_count_to_event_options = countdown_data_params.get( 'em_countdown_count_to_event_options' );
            if( em_countdown_count_to_event_options ) {
                countdown_timer.em_countdown_count_to_event_options = em_countdown_count_to_event_options;
                $( 'select[name="em_countdown_count_to_event_options"] option:first' ).attr( 'selected', 'selected' );
            }
        } else if( em_countdown_count_to == 'relative_date' ){
            em_countdown_count_to_days = countdown_data_params.get( 'em_countdown_count_to_days' );
            if( em_countdown_count_to_days ) {
                countdown_timer.em_countdown_count_to_days = em_countdown_count_to_days;
                $( 'input[name="em_countdown_count_to_days"]').val( '' );
            }
            em_countdown_count_to_days_options = countdown_data_params.get( 'em_countdown_count_to_days_options' );
            if( em_countdown_count_to_days_options ) {
                countdown_timer.em_countdown_count_to_days_options = em_countdown_count_to_days_options;
                $( 'select[name="em_countdown_count_to_days_options"] option:first' ).attr( 'selected', 'selected' );
            }
            em_countdown_count_to_event_options = countdown_data_params.get( 'em_countdown_count_to_event_options' );
            if( em_countdown_count_to_event_options ) {
                countdown_timer.em_countdown_count_to_event_options = em_countdown_count_to_event_options;
                $( 'select[name="em_countdown_count_to_event_options"] option:first' ).attr( 'selected', 'selected' );
            }
        }

        // display seconds
        let em_countdown_display_seconds = '';
        em_countdown_display_seconds = countdown_data_params.get( 'em_countdown_display_seconds' );
        if( em_countdown_display_seconds ) {
            countdown_timer.em_countdown_display_seconds = em_countdown_display_seconds;
            $( 'input[name="em_countdown_count_to_days"]').prop( 'checked', 'checked' );
        }

        // timer message
        let em_countdown_timer_message = '';
        em_countdown_timer_message = countdown_data_params.get( 'em_countdown_timer_message' );
        if( em_countdown_timer_message ) {
            countdown_timer.em_countdown_timer_message = em_countdown_timer_message;
            $( 'textarea[name="em_countdown_timer_message"]').val( '' );
        }

        let em_event_countdown_timers = [];
        em_event_countdown_timers = $( '#em_event_countdown_timers' ).val();
        if( em_event_countdown_timers ) {
            em_event_countdown_timers = JSON.parse( em_event_countdown_timers );
            let timer_length = em_event_countdown_timers.length;
            if( timer_length > 0 ) {
                em_event_countdown_timers.push( countdown_timer );
            } 
        } else{
            em_event_countdown_timers = [ countdown_timer ];
        }
        $( '#em_event_countdown_timers' ).val( JSON.stringify( em_event_countdown_timers ) );
        let new_timer_data = ''; let new_timer_innerdata = '';
        let timer_data_len = $( '#ep-existing-countdown-timers .ep-countdown-timer-content' ).length;
        let new_timer_len = timer_data_len + 1;
        let new_countdown_timer = JSON.stringify( countdown_timer );

        new_timer_innerdata += '<div class="ep-box-col-12">';
            new_timer_innerdata += '<span class="ep-mr-3">'+ countdown_timer.em_countdown_name +'</span>';
            new_timer_innerdata += '<span>[ep_cd_1]</span>';
            new_timer_innerdata += '<span class="additional-date-copy material-icons ep-text-primary ep-mr-3" data-parent_id="ep-countdown-timer-content'+new_timer_len+'">content_copy</span>';
            new_timer_innerdata += '<span class="additional-date-edit material-icons ep-text-primary" data-parent_id="ep-countdown-timer-content'+new_timer_len+'">edit</span>';
            new_timer_innerdata += '<span class="additional-date-delete material-icons ep-text-danger" data-parent_id="ep-countdown-timer-content'+new_timer_len+'">delete</span>';
        new_timer_innerdata += '</div>';
        new_timer_innerdata += '<div class="ep-box-col-12">';
            new_timer_innerdata += '<span class="ep-text-muted ep-text-small ep-mr-3">';
                //Activates 2 days before start of event
                if( em_countdown_activate_on == 'right_away' ) {
                    new_timer_innerdata += em_event_meta_box_object.countdown_activated_text;
                } else if( em_countdown_activate_on == 'custom_date' ) {
                    new_timer_innerdata += em_event_meta_box_object.countdown_activate_text + ' ' + em_event_meta_box_object.countdown_on_text + ' ';
                    new_timer_innerdata += em_countdown_activate_on_date + ' ' + em_countdown_activate_on_time;
                } else if( em_countdown_activate_on == 'event_date' ) {
                    new_timer_innerdata += em_event_meta_box_object.countdown_activate_text + ' ' + em_event_meta_box_object.countdown_on_text + ' ';
                    new_timer_innerdata += em_event_meta_box_object.countdown_event_options[em_countdown_activate_on_event_options];
                } else if( em_countdown_activate_on == 'relative_date' ) {
                    new_timer_innerdata += em_event_meta_box_object.countdown_activate_text + ' ' + em_event_meta_box_object.countdown_on_text + ' ';
                    new_timer_innerdata += em_countdown_activate_on_days + ' ' + em_event_meta_box_object.countdown_days_options[em_countdown_activate_on_days_options] + ' ';
                    new_timer_innerdata += em_event_meta_box_object.countdown_event_options[em_countdown_activate_on_event_options];
                }
            new_timer_innerdata += '</span>';
            new_timer_innerdata += '<span class="ep-text-muted ep-text-small ep-mr-3">';
                //Activates 2 days before start of event
                if( em_countdown_count_to == 'custom_date' ) {
                    new_timer_innerdata += em_event_meta_box_object.countdown_ends_text + ' ' + em_event_meta_box_object.countdown_on_text + ' ';
                    new_timer_innerdata += em_countdown_count_to_date + ' ' + em_countdown_count_to_time;
                } else if( em_countdown_count_to == 'event_date' ) {
                    new_timer_innerdata += em_event_meta_box_object.countdown_ends_text + ' ' + em_event_meta_box_object.countdown_on_text + ' ';
                    new_timer_innerdata += em_event_meta_box_object.countdown_event_options[em_countdown_count_to_event_options];
                } else if( em_countdown_count_to == 'relative_date' ) {
                    new_timer_innerdata += em_event_meta_box_object.countdown_ends_text + ' ' + em_event_meta_box_object.countdown_on_text + ' ';
                    new_timer_innerdata += em_countdown_count_to_days + ' ' + em_event_meta_box_object.countdown_days_options[em_countdown_count_to_days_options] + ' ';
                    new_timer_innerdata += em_event_meta_box_object.countdown_event_options[em_countdown_count_to_event_options];
                }
            new_timer_innerdata += '</span>';
        new_timer_innerdata += '</div>';

        let timer_parent_id = $( '#ep-event-countdown-save' ).attr( 'data-edit_id' );
        if( timer_parent_id ) {
            $( '#' + timer_parent_id ).html( new_timer_innerdata );
            $( '#ep-event-countdown-save' ).removeAttr( 'data-edit_id' );
        } else{
            new_timer_data += "<div class='ep-countdown-timer-content' id='ep-countdown-timer-content"+new_timer_len+"' data-timer_data='"+new_countdown_timer+"'>";
                new_timer_data += new_timer_innerdata;
            new_timer_data += '</div>';
        }
        $( '#ep-existing-countdown-timers' ).append( new_timer_data );
        $( '#existing-countdown-wrapper' ).show();
    });

    // Copy countdown timer
    $( document ).on( 'click', '.additional-date-copy', function() {
        let parentid = $( this ).data( 'parent_id' );
        let parent_elem = $( '#' + parentid ).html();
        let timer_data = $( '#' + parentid ).data( 'timer_data' );
        if( timer_data ) {
            timer_data = JSON.stringify( timer_data );
        }

        let new_timer_data = ''; 
        let timer_data_len = $( '#ep-existing-countdown-timers .ep-countdown-timer-content' ).length;
        let new_timer_len = timer_data_len + 1;
        let new_timer_id = 'ep-countdown-timer-content'+new_timer_len;
        new_timer_data += "<dic class='ep-countdown-timer-content' id='"+new_timer_id+"' data-timer_data='"+timer_data+"'>";
            new_timer_data += parent_elem;
        new_timer_data += '</div>';
        $( '#ep-existing-countdown-timers' ).append( new_timer_data );
        $( '#' + new_timer_id + ' .additional-date-copy' ).attr( 'data-parent_id', new_timer_id);
        $( '#' + new_timer_id + ' .additional-date-edit' ).attr( 'data-parent_id', new_timer_id);
        $( '#' + new_timer_id + ' .additional-date-delete' ).attr( 'data-parent_id', new_timer_id);

        let em_event_countdown_timers = [];
        em_event_countdown_timers = $( '#em_event_countdown_timers' ).val();
        if( em_event_countdown_timers ) {
            em_event_countdown_timers = JSON.parse( em_event_countdown_timers );
            let timer_length = em_event_countdown_timers.length;
            if( timer_length > 0 ) {
                timer_data = JSON.parse( timer_data );
                em_event_countdown_timers.push( timer_data );
            } 
        }
        $( '#em_event_countdown_timers' ).val( JSON.stringify( em_event_countdown_timers ) );
    });

    // Delete countdown timer
    $( document ).on( 'click', '.additional-date-delete', function() {
        let parentid = $( this ).data( 'parent_id' );
        if( $( '#' + parentid ).length > 0 ) {
            $( '#' + parentid ).remove();
        }
        let idnum = parentid.split( 'ep-countdown-timer-content' )[1];
        em_event_countdown_timers = $( '#em_event_countdown_timers' ).val();
        if( em_event_countdown_timers ){
            em_event_countdown_timers = JSON.parse( em_event_countdown_timers );
            em_event_countdown_timers.splice( idnum-1, 1 );
            $( '#em_event_countdown_timers' ).val( JSON.stringify( em_event_countdown_timers ) );
        }
        // reset elements
        $( '#ep-existing-countdown-timers .ep-countdown-timer-content' ).each( function(idx, ele) {
            let eleid = idx;
            let neweleid = ++idx;
            let timerid = this.id;
            let eleiddata = 'ep-countdown-timer-content'+neweleid;
            if( timerid !== eleiddata ) {
                $( this ).attr( 'id', eleiddata );
            }
            $( '#' + eleiddata + ' .additional-date-copy' ).attr( 'data-parent_id', eleiddata );
            $( '#' + eleiddata + ' .additional-date-edit' ).attr( 'data-parent_id', eleiddata );
            $( '#' + eleiddata + ' .additional-date-delete' ).attr( 'data-parent_id', eleiddata );
        })
    });

    // edit countdown timer
    $( document ).on( 'click', '.additional-date-edit', function() {
        let parentid = $( this ).data( 'parent_id' );
        let timer_data = $( '#' + parentid ).data( 'timer_data' );
        if( timer_data ){
            let em_countdown_name = timer_data.em_countdown_name;
            // activate on
            $( 'input[name=em_countdown_name]').val( em_countdown_name );
            let em_countdown_activate_on = timer_data.em_countdown_activate_on;
            $( 'select[name="em_countdown_activate_on"]' ).val( em_countdown_activate_on );
            $( 'select[name="em_countdown_activate_on"]' ).trigger( 'change' );
            if( em_countdown_activate_on == 'custom_date' ) {
                let em_countdown_activate_on_date = timer_data.em_countdown_activate_on_date;
                if( em_countdown_activate_on_date ){
                    $( 'input[name="em_countdown_activate_on_date"]').val( em_countdown_activate_on_date );
                }
                let em_countdown_activate_on_time = timer_data.em_countdown_activate_on_time;
                if( em_countdown_activate_on_time ){
                    $( 'input[name="em_countdown_activate_on_time"]').val( em_countdown_activate_on_time );
                }
            } else if( em_countdown_activate_on == 'event_date' ) {
                let em_countdown_activate_on_event_options = timer_data.em_countdown_activate_on_event_options;
                $( 'select[name="em_countdown_activate_on_event_options"]' ).val( em_countdown_activate_on_event_options );
            } else if( em_countdown_activate_on == 'relative_date' ) {
                let em_countdown_activate_on_days = timer_data.em_countdown_activate_on_days;
                if( em_countdown_activate_on_days ) {
                    $( 'input[name="em_countdown_activate_on_days"]').val( em_countdown_activate_on_days );
                }
                let em_countdown_activate_on_days_options = timer_data.em_countdown_activate_on_days_options;
                if( em_countdown_activate_on_days_options ) {
                    $( 'select[name="em_countdown_activate_on_days_options"]' ).val( em_countdown_activate_on_days_options );
                }
                let em_countdown_activate_on_event_options = timer_data.em_countdown_activate_on_event_options;
                if( em_countdown_activate_on_event_options ) {
                    $( 'select[name="em_countdown_activate_on_event_options"]' ).val( em_countdown_activate_on_event_options );
                }
            }

            // count to
            let em_countdown_count_to = timer_data.em_countdown_count_to;
            $( 'select[name="em_countdown_count_to"]' ).val( em_countdown_count_to );
            $( 'select[name="em_countdown_count_to"]' ).trigger( 'change' );
            if( em_countdown_count_to == 'custom_date' ) {
                let em_countdown_count_to_date = timer_data.em_countdown_count_to_date;
                if( em_countdown_count_to_date ){
                    $( 'input[name="em_countdown_count_to_date"]').val( em_countdown_count_to_date );
                }
                let em_countdown_count_to_time = timer_data.em_countdown_count_to_time;
                if( em_countdown_count_to_time ){
                    $( 'input[name="em_countdown_count_to_time"]').val( em_countdown_count_to_time );
                }
            } else if( em_countdown_count_to == 'event_date' ) {
                let em_countdown_count_to_event_options = timer_data.em_countdown_count_to_event_options;
                $( 'select[name="em_countdown_count_to_event_options"]' ).val( em_countdown_count_to_event_options );
            } else if( em_countdown_count_to == 'relative_date' ) {
                let em_countdown_count_to_days = timer_data.em_countdown_count_to_days;
                if( em_countdown_count_to_days ) {
                    $( 'input[name="em_countdown_count_to_days"]').val( em_countdown_count_to_days );
                }
                let em_countdown_count_to_days_options = timer_data.em_countdown_count_to_days_options;
                if( em_countdown_count_to_days_options ) {
                    $( 'select[name="em_countdown_count_to_days_options"]' ).val( em_countdown_count_to_days_options );
                }
                let em_countdown_count_to_event_options = timer_data.em_countdown_count_to_event_options;
                if( em_countdown_count_to_event_options ) {
                    $( 'select[name="em_countdown_count_to_event_options"]' ).val( em_countdown_count_to_event_options );
                }
            }

            // display seconds
            let em_countdown_display_seconds = timer_data.em_countdown_display_seconds;
            if( em_countdown_display_seconds ) {
                $( 'input[name="em_countdown_count_to_days"]').prop( 'checked', em_countdown_display_seconds );
            }

            // timer message
            let em_countdown_timer_message = timer_data.em_countdown_timer_message;
            if( em_countdown_timer_message ) {
                $( 'textarea[name="em_countdown_timer_message"]').val( em_countdown_timer_message );
            }

            // timer id
            $( '#ep-event-countdown-save' ).attr( 'data-edit_id', parentid );
        }
    });
    
    //Ticket Tabs
    $("input[name=em_enable_booking]").change(em_ticket_type_options);
    function get_booking_type() {
        return $("input[name=em_enable_booking]:checked").val();
    }
    function em_ticket_type_options() {
        var booking_type = get_booking_type();
        $( "#ep-bookings-options" ).hide();
        $( "#ep-bookings-url" ).hide();
        $( "#ep_existing_tickets_category_list" ).hide();
        $( "#ep-event-tickets-options" ).hide();
        $( "#ep_event_booking_not_enabled_warning" ).show();
        if( $( '#ep_show_event_expire_warning' ).length == 1 ) {
            $( "#ep_event_booking_not_enabled_warning" ).hide();
        }

        if ( booking_type === 'bookings_on' ) {
            $( "#ep-bookings-options" ).fadeIn( 500 );
            $( "#ep-event-tickets-options" ).show();
            $( "#ep_existing_tickets_category_list" ).show();
            $( "#ep_event_booking_not_enabled_warning" ).hide();
            $( "#ep_event_booking_disabled_warning" ).hide();
        } else if ( booking_type === 'external_bookings' ) {
            $( "#ep-bookings-url" ).fadeIn( 500 );
            $( "#ep_event_booking_disabled_warning" ).show();
        } else if ( booking_type === 'bookings_off' ) {
            if( $( '#ep_event_booking_not_enabled_warning' ).length == 0 || $( '#ep_event_booking_not_enabled_warning' ).css( 'display' ) == 'none' ) {
                $( "#ep_event_booking_disabled_warning" ).show();
            }
        }
    }

    em_ticket_type_options();
    //Ticket Tabs End
    /**
     * Ticket Category related functionality starts
     */
    // save ticket category
    $( document ).on( 'click', '#ep_save_ticket_category', function() {
        $( '#ep_ticket_category_name_error' ).html( '' );
        $( '#ep_ticket_category_capacity_error' ).html( '' );
        let cat_name     = $( '#ep_ticket_category_name' ).val();
        let cat_capacity = $( '#ep_ticket_category_capacity' ).val();
        if( !cat_name ) {
            let requireString = get_translation_string( 'required' );
            $( '#ep_ticket_category_name_error' ).html( requireString );
            return false;
        }
        if( !cat_capacity ) {
            let requireString = get_translation_string( 'required' );
            $( '#ep_ticket_category_capacity_error' ).html( requireString );
            return false;
        }

        // check if edit popup open
        let parent_row_id = '';
        if( this.hasAttribute( 'data-edit_row_id') ) {
            parent_row_id = $( this ).attr( 'data-edit_row_id' );
        }
        let cat_data = { name: cat_name, capacity: cat_capacity, id: '', tickets: '' };
        // check for edit saved category
        if( parent_row_id ) {
            let cat_row_data = $( '#' + parent_row_id ).data( 'cat_row_data' );
            if( cat_row_data ) {
                cat_data.id = cat_row_data.id;
            }
        }
        
        // add each category data in temp
        let em_event_tickets_data = [];
        em_event_tickets_data = $( '#ep_ticket_category_data' ).val();
        if( em_event_tickets_data.length > 0 ) {
            em_event_tickets_data = JSON.parse( em_event_tickets_data );
            let timer_length = em_event_tickets_data.length;
            if( timer_length > 0 ) {
                if( parent_row_id ) {
                    let id_num = parent_row_id.split( 'ep_ticket_cat_section' )[1];
                    em_event_tickets_data[id_num - 1] = cat_data;
                } else{
                    em_event_tickets_data.push( cat_data );
                }
            }
        } else{
            em_event_tickets_data = [ cat_data ];
        }
        $( '#ep_ticket_category_data' ).val( JSON.stringify( em_event_tickets_data ) );
        // add individual category data with row
        let new_cat_data = JSON.stringify( cat_data );
        if( parent_row_id ) {
            $( '#' + parent_row_id ).attr( 'data-cat_row_data', new_cat_data );
            $( '#' + parent_row_id + ' .ep-cat-name' ).text( cat_name );
            $( '#' + parent_row_id + ' .ep-cat-capacity' ).text( em_event_meta_box_object.ticket_capacity_text + ': '+ cat_capacity );
            $( '#ep-ticket-category-modal' ).find( '.ep-modal-title' ).html( em_event_meta_box_object.add_ticket_category_text );
        } else{
            let cat_list_count = $( '#ep_existing_tickets_list .ep-cat-list-class' ).length;
            let next_cat_list_count = ++cat_list_count;
            let category_list_data = '';
            let new_cat_row_id = 'ep_ticket_cat_section'+next_cat_list_count;
            category_list_data += "<div class='ep-box-col-12 ep-p-3 ep-border ep-rounded ep-mb-3 ep-bg-white ep-shadow-sm ui-state-default ep-cat-list-class' id='"+new_cat_row_id+"' data-cat_row_data='"+new_cat_data+"'>";
                category_list_data += '<div class="ep-box-row ep-mb-3 ep-items-center">';
                    category_list_data += '<div class="ep-box-col-1"><span class="ep-ticket-cat-sort ep-cursor-move material-icons text-muted" data-parent_id="'+new_cat_row_id+'">drag_indicator</span></div>';
                    category_list_data += '<div class="ep-box-col-5"><h4 class="ep-cat-name">' + cat_name + '</h4></div>';
                    category_list_data += '<div class="ep-box-col-4"><h4 class="ep-cat-capacity">' + em_event_meta_box_object.ticket_capacity_text + ': '+ cat_capacity +'</h4></div>';
                    category_list_data += '<div class="ep-box-col-1"> <a href="javascript:void(0)" class="ep-ticket-cat-edit ep-text-primary" data-parent_id="'+new_cat_row_id+'">Edit</a></div>';
                    category_list_data += '<div class="ep-box-col-1"><a href="javascript:void(0)" class="ep-ticket-cat-delete ep-item-delete" data-parent_id="'+new_cat_row_id+'">Delete</a></div>';
                category_list_data += '</div>';
                category_list_data += '<div class="ep-box-col-12 ep-p-3">';
                    category_list_data += '<button type="button" class="button button-large ep-m-3 ep-open-category-ticket-modal" data-id="ep_event_ticket_tier_modal" data-parent_id="'+new_cat_row_id+'">'+em_event_meta_box_object.add_ticket_text+'</button>';
                category_list_data += '</div>';
                category_list_data += '<div class="ep-ticket-category-section"></div>';
            category_list_data += '</div>';
            $( '#ep_existing_tickets_list' ).append( category_list_data );
        }
        // initiate the category modal
        initiate_the_category_modal();

        // close category modal
        $( '#ep-ticket-category-modal' ).closePopup({
            anim: (!$(this).attr('data-animation') || $(this).data('animation') == null) ? 'ep-modal-' : $(this).data('animation')
        });
        $( 'body' ).removeClass( 'ep-modal-open-body' );
    });

    // Delete ticket category
    $( document ).on( 'click', '.ep-ticket-cat-delete', function() {
        let parentid = $( this ).data( 'parent_id' );
        // delete saved ids
        let cat_row_data = $( '#' + parentid ).data( 'cat_row_data' );
        if( cat_row_data.id != '' ) {
            let cat_delete_id = cat_row_data.id;
            let del_cat_ids = [];
            del_cat_ids = $( '#ep_ticket_category_delete_ids' ).val();
            if( del_cat_ids ) {
                del_cat_ids = JSON.parse( del_cat_ids );
                del_cat_ids.push( cat_delete_id );
            } else{
                del_cat_ids = [ cat_delete_id ] ;
            }
            $( '#ep_ticket_category_delete_ids' ).val( JSON.stringify( del_cat_ids ) );
        }
        if( $( '#' + parentid ).length > 0 ) {
            $( '#' + parentid ).remove();
        }
        let idnum = parentid.split( 'ep_ticket_cat_section' )[1];
        ep_ticket_category_data = $( '#ep_ticket_category_data' ).val();
        if( ep_ticket_category_data ){
            ep_ticket_category_data = JSON.parse( ep_ticket_category_data );
            ep_ticket_category_data.splice( idnum-1, 1 );
            $( '#ep_ticket_category_data' ).val( JSON.stringify( ep_ticket_category_data ) );
        }
        // reset elements
        $( '#ep_existing_tickets_category_list .ep-cat-list-class' ).each( function(idx, ele) {
            let eleid = idx;
            let neweleid = ++idx;
            let timerid = this.id;
            let eleiddata = 'ep_ticket_cat_section'+neweleid;
            if( timerid !== eleiddata ) {
                $( this ).attr( 'id', eleiddata );
            }
            $( '#' + eleiddata + ' .ep-ticket-cat-sort' ).attr( 'data-parent_id', eleiddata );
            $( '#' + eleiddata + ' .ep-ticket-cat-edit' ).attr( 'data-parent_id', eleiddata );
            $( '#' + eleiddata + ' .ep-ticket-cat-delete' ).attr( 'data-parent_id', eleiddata );
        });
    });

    // edit ticket category
    $( document ).on( 'click', '.ep-ticket-cat-edit', function() {
        let parentid = $( this ).data( 'parent_id' );
        let cat_row_data = $( '#' + parentid ).attr( 'data-cat_row_data' );
        if( cat_row_data ){
            cat_row_data = JSON.parse( cat_row_data );
            let name = cat_row_data.name;
            $( '#ep_ticket_category_name' ).val( name );
            let capacity = cat_row_data.capacity;
            $( '#ep_ticket_category_capacity' ).val( capacity );

            // open popup
            let edit_modal = { title: name, row_id: parentid };
            $( '#ep-ticket-category-modal' ).openPopup({
                anim: (!$(this).attr('data-animation') || $(this).data('animation') == null) ? 'ep-modal-' : $(this).data('animation')
            }, edit_modal );

            // update the button
            $( '#ep-ticket-category-modal #ep_save_ticket_category' ).text( em_event_meta_box_object.update_text );
        }
    });

    // sort ticket category
    $( "#ep_existing_tickets_list" ).on( "sortupdate", function( event, ui ) {
        let em_event_tickets_data = [];
        $( '#ep_existing_tickets_list .ep-cat-list-class' ).each( function(idx, ele) {
            let eleid = idx;
            let neweleid = ++idx;
            let timerid = this.id;
            let eleiddata = 'ep_ticket_cat_section'+neweleid;
            if( timerid !== eleiddata ) {
                $( this ).attr( 'id', eleiddata );
            }
            $( '#' + eleiddata + ' .ep-ticket-cat-sort' ).attr( 'data-parent_id', eleiddata );
            $( '#' + eleiddata + ' .ep-ticket-cat-edit' ).attr( 'data-parent_id', eleiddata );
            $( '#' + eleiddata + ' .ep-ticket-cat-delete' ).attr( 'data-parent_id', eleiddata );
            $( '#' + eleiddata + ' .ep-open-category-ticket-modal' ).attr( 'data-parent_id', eleiddata );

            let cat_row_data = $( '#' + eleiddata ).data( 'cat_row_data' );
            em_event_tickets_data.push( cat_row_data );
        });
        if( em_event_tickets_data ){
            $( '#ep_ticket_category_data' ).val( JSON.stringify( em_event_tickets_data ) );
        }
    });

    // open ticket modal inside category
    $( document ).on( 'click', '.ep-open-category-ticket-modal', function() {
        initiate_the_ticket_modal();
        let parent_id = $( this ).data( 'parent_id' );
        if( parent_id ) {
            $( 'input[name=em_ticket_category_id]' ).val( parent_id );

            $( '#ep_event_ticket_tier_modal' ).openPopup({
                anim: (!$(this).attr('data-animation') || $(this).data('animation') == null) ? 'ep-modal-' : $(this).data('animation')
            } );
            // update remaining capacity
            let cat_row_data = $( '#' + parent_id ).data( 'cat_row_data' );
            if( cat_row_data.capacity ) {
                let cat_capacity = cat_row_data.capacity;
                // check for created tickets
                let ticket_capacity = 0;
                if( $( '#' + parent_id + ' .ep-tickets-cate-ticket-row' ).length > 0 ) {
                    $.each( $( '#' + parent_id + ' .ep-tickets-cate-ticket-row' ), function() {
                        let ticket_row_data = $( this ).data( 'ticket_row_data' );
                        if( ticket_row_data ) { 
                            let em_event_ticket_qty = ticket_row_data.capacity;
                            if( em_event_ticket_qty ) {
                                ticket_capacity = parseInt( ticket_capacity, 10 ) + parseInt( em_event_ticket_qty, 10 );
                            }
                        } 
                    });
                    if( ticket_capacity > 0 ) {
                        cat_capacity = cat_capacity - ticket_capacity;
                    }
                }
                // update the capacity
                let max_label = $( '#ep_ticket_remaining_capacity' ).data( 'max_ticket_label' );
                $( '#ep_ticket_remaining_capacity' ).html( max_label + ': ' + cat_capacity );
                // set max capacity
                if( cat_capacity ) {
                    $( '#ep_event_ticket_qty' ).attr( 'max', cat_capacity );
                }
            }
        }
    });

    // sort category tickets
    $( ".ep-ticket-category-section" ).on( "sortupdate", function( event, ui ) {
        let em_event_tickets_data = [];
        let parent_category_id = $( this ).data( 'parent_category_id' );
        $( this ).find( '.ep-tickets-cate-ticket-row' ).each( function(idx, ele) {
            let neweleid = idx;
            ++neweleid;
            let timerid = this.id;
            let eleiddata = '';
            if( parent_category_id ) {
                eleiddata = 'ep_cat_'+parent_category_id+'_ticket'+neweleid;
            } else{
                eleiddata = 'ep_event_ticket_row'+neweleid;
            }
            if( timerid !== eleiddata ) {
                $( this ).attr( 'id', eleiddata );
            }
            $( '#' + eleiddata + ' .ep-ticket-row-sort' ).attr( 'data-parent_id', eleiddata );
            $( '#' + eleiddata + ' .ep-ticket-row-edit' ).attr( 'data-parent_id', eleiddata );
            $( '#' + eleiddata + ' .ep-ticket-row-delete' ).attr( 'data-parent_id', eleiddata );
            //$( '#' + eleiddata + ' .ep-open-category-ticket-modal' ).attr( 'data-parent_id', eleiddata );

            let ticket_row_data = $( '#' + eleiddata ).data( 'ticket_row_data' );
            em_event_tickets_data.push( ticket_row_data );
        });
        if( em_event_tickets_data ){
            if( parent_category_id ) {
                let category_tickets_data = [];
                let ep_ticket_category_data = $( '#ep_ticket_category_data' ).val();
                if( ep_ticket_category_data ) {
                    ep_ticket_category_data = JSON.parse( ep_ticket_category_data );
                    if( ep_ticket_category_data && ep_ticket_category_data.length > 0 ){
                        $.each( ep_ticket_category_data, function( ids, cat_datas ) {
                            let cat_key = ids;
                            ++cat_key;
                            if( cat_key == parent_category_id ) {
                                cat_datas.tickets = em_event_tickets_data;
                            }
                            category_tickets_data.push( cat_datas );
                        });
                    }
                }
                setTimeout(function() {
                    $( '#ep_ticket_category_data' ).val( JSON.stringify( category_tickets_data ) );
                }, 200)
            }
        }
    });

    /**
     * Ticket Category related functionality ends
     */

    /**
     * Individual Ticket related functionality starts
     */
    // add more additional fee row
    $( document ).on( 'click', '#add_more_additional_ticket_fee', function() {
        let ep_fee_row_len = $( '#ep_additional_ticket_fee_wrapper .ep-additional-ticket-fee-row' ).length;
        let next_row_len = ++ep_fee_row_len;
        let additional_fee_row = '';
        let row_id = 'ep_additional_ticket_fee_row'+next_row_len;
        additional_fee_row += '<div class="ep-additional-ticket-fee-row ep-box-row" id="'+row_id+'">';
            additional_fee_row += '<div class="ep-additional-fee ep-box-col-5 ep-mt-3">';
                additional_fee_row += '<input type="text" class="ep-form-control" id="ep_additional_ticket_fee_label'+next_row_len+'" placeholder="'+em_event_meta_box_object.additional_label_text+'" name="ep_additional_ticket_fee['+next_row_len+'][label]">';
            additional_fee_row += '</div>';
            additional_fee_row += '<div class="ep-additional-fee ep-box-col-5 ep-mt-3">';
                additional_fee_row += '<input type="number" class="ep-form-control" id="ep_additional_ticket_fee_price'+next_row_len+'" placeholder="'+em_event_meta_box_object.price_text+'" name="ep_additional_ticket_fee['+next_row_len+'][price]" min="0.00" step="0.01">';
            additional_fee_row += '</div>';
            additional_fee_row += '<div class="ep-additional-fee ep-box-col-2 ep-mt-3 ep-d-flex ep-items-end ep-pb-2">';
                additional_fee_row += '<a href="javascript:void(0" class="ep-delete-additional-ticket-fee-row" data-parent_id="'+row_id+'">Delete</a>';
            additional_fee_row += '</div>';
        additional_fee_row += '</div>';

        $( '#ep_additional_ticket_fee_wrapper' ).append( additional_fee_row );
    });

    // delete additional fee row
    $( document ).on( 'click', '.ep-delete-additional-ticket-fee-row', function() {
        let parent_id = $( this ).data( 'parent_id' );
        if( $( '#' + parent_id ).length > 0 ) {
            $( '#' + parent_id ).remove();
        }
    });

    // show/hide ticket start dates
    $( document ).on( 'change', '.ep_ticket_start_booking_type', function() {
        $( '.ep_ticket_start_booking_options' ).hide();
        let start_options = this.value;
        $( '.ep_ticket_start_booking_' + start_options ).show();
        if( start_options == 'event_date' ){
            // Disable the option event ends when event_date is selected
            $('#ep_ticket_start_booking_event_option option[value="event_ends"]').prop('disabled', true);
        }
        if( start_options == 'relative_date' ){
            // Enable the option event ends when relative date is selected
            $('#ep_ticket_start_booking_event_option option[value="event_ends"]').prop('disabled', false);
        }
    });

    // show/hide ticket ends dates
    $( document ).on( 'change', '.ep_ticket_ends_booking_type', function() {
        $( '.ep_ticket_ends_booking_options' ).hide();
        let ends_options = this.value;
        $( '.ep_ticket_ends_booking_' + ends_options ).show();
    });

    // ticket visibility user role
    setTimeout( function() {
        if( $( '.ep_user_roles_options' ).length > 0 ) {
            $( '.ep_user_roles_options' ).select2({
                theme: 'classic',
                placeholder: 'Select Role(s)',
                dropdownCssClass: 'ep-ui-show-on-top'
            });
        }
    }, 500);
    
    // ticket visibility
    $( document ).on( 'change', '#ep_tickets_user_visibility', function() {
    	if ( $(this).val() == 'user_roles' ) {
    		$( '#ep_ticket_visibility_user_roles_select' ).fadeIn();
    	} else {
    		$( '#ep_ticket_visibility_user_roles_select' ).fadeOut();
    	}
    });

    // visibility time restriction
    $( document ).on( 'change', '#ep_tickets_visibility_time_restrictions', function() {
        $( '.ep-visibility-time-date-res' ).fadeOut();
        if( this.value == 'between_dates' ) {
            $( '.ep-visibility-time-date-res' ).fadeIn();
        }
    });

    // change offer type
    $( document ).on( 'change', '#ep_ticket_offer_type', function() {
        $( '.offer-fields' ).fadeOut();
        let offer_type = this.value;
        $( '#ep_ticket_offer_' + offer_type).fadeIn( 500 );
    });

    // show/hide offer start
    $( document ).on( 'change', '.ep_offer_start_booking_type', function() {
        $( '.ep_offer_start_booking_options' ).hide();
        let start_options = this.value;
        $( '.ep_offer_start_booking_' + start_options ).show();
    });

    // show/hide offer ends
    $( document ).on( 'change', '.ep_offer_ends_booking_type', function() {
        $( '.ep_offer_ends_booking_options' ).hide();
        let ends_options = this.value;
        $( '.ep_offer_ends_booking_' + ends_options ).show();
    });

    // save offers
    $( document ).on( 'click', '#ep_ticket_add_offer', function() {
        $( '.ep-error-message' ).html( '' );
        var offer_data = $( '#ep_event_ticket_offer_wrapper' ).find(
			'input, select, textarea'
		);
        let ticket_offer_data = {};
        let requireString = get_translation_string( 'required' );
        let offer_data_params = new URLSearchParams( offer_data.serialize() );
        // offer name
        let em_ticket_offer_name = offer_data_params.get( 'em_ticket_offer_name' );
        if( !em_ticket_offer_name ) {
            $( '#ep_event_offer_name_error' ).html( requireString );
            document.getElementById( 'ep_ticket_offer_name' ).focus();
            return false;
        }
        ticket_offer_data.uid = Date.now();
        ticket_offer_data.em_ticket_offer_name = em_ticket_offer_name;
        // offer description
        let em_ticket_offer_description = offer_data_params.get( 'em_ticket_offer_description' );
        if( em_ticket_offer_description ) {
            ticket_offer_data.em_ticket_offer_description = em_ticket_offer_description;
        }
        // show offer
        let em_ticket_show_offer_detail = offer_data_params.get( 'em_ticket_show_offer_detail' );
        if( em_ticket_show_offer_detail ) {
            ticket_offer_data.em_ticket_show_offer_detail = em_ticket_show_offer_detail;
            $( 'input[name=em_ticket_show_offer_detail]').prop( 'checked', false);
        }
        // offer start
        let em_offer_start_booking_type = offer_data_params.get( 'em_offer_start_booking_type' );
        if( em_offer_start_booking_type ) {
            ticket_offer_data.em_offer_start_booking_type = em_offer_start_booking_type;

            if( em_offer_start_booking_type == 'custom_date' ) {
                let em_offer_start_booking_date = offer_data_params.get( 'em_offer_start_booking_date' );
                if( em_offer_start_booking_date ){ 
                    ticket_offer_data.em_offer_start_booking_date = em_offer_start_booking_date;
                }

                let em_offer_start_booking_time = offer_data_params.get( 'em_offer_start_booking_time' );
                if( em_offer_start_booking_time ){ 
                    ticket_offer_data.em_offer_start_booking_time = em_offer_start_booking_time;
                }
            } else if( em_offer_start_booking_type == 'event_date' ) {
                let em_offer_start_booking_event_option = offer_data_params.get( 'em_offer_start_booking_event_option' );
                if( em_offer_start_booking_event_option ) {
                    ticket_offer_data.em_offer_start_booking_event_option = em_offer_start_booking_event_option;
                }
            } else if( em_offer_start_booking_type == 'relative_date' ) {
                let em_offer_start_booking_days = offer_data_params.get( 'em_offer_start_booking_days' );
                if( em_offer_start_booking_days ) {
                    ticket_offer_data.em_offer_start_booking_days = em_offer_start_booking_days;
                }

                let em_offer_start_booking_days_option = offer_data_params.get( 'em_offer_start_booking_days_option' );
                if( em_offer_start_booking_days_option ) {
                    ticket_offer_data.em_offer_start_booking_days_option = em_offer_start_booking_days_option;
                }

                let em_offer_start_booking_event_option = offer_data_params.get( 'em_offer_start_booking_event_option' );
                if( em_offer_start_booking_event_option ) {
                    ticket_offer_data.em_offer_start_booking_event_option = em_offer_start_booking_event_option;
                }
            }
        }
        // offer end
        let em_offer_ends_booking_type = offer_data_params.get( 'em_offer_ends_booking_type' );
        if( em_offer_ends_booking_type ) {
            ticket_offer_data.em_offer_ends_booking_type = em_offer_ends_booking_type;

            if( em_offer_ends_booking_type == 'custom_date' ) {
                let em_offer_ends_booking_date = offer_data_params.get( 'em_offer_ends_booking_date' );
                if( em_offer_ends_booking_date ){ 
                    ticket_offer_data.em_offer_ends_booking_date = em_offer_ends_booking_date;
                }

                let em_offer_ends_booking_time = offer_data_params.get( 'em_offer_ends_booking_time' );
                if( em_offer_ends_booking_time ){ 
                    ticket_offer_data.em_offer_ends_booking_time = em_offer_ends_booking_time;
                }
            } else if( em_offer_ends_booking_type == 'event_date' ) {
                let em_offer_ends_booking_event_option = offer_data_params.get( 'em_offer_ends_booking_event_option' );
                if( em_offer_ends_booking_event_option ) {
                    ticket_offer_data.em_offer_ends_booking_event_option = em_offer_ends_booking_event_option;
                }
            } else if( em_offer_ends_booking_type == 'relative_date' ) {
                let em_offer_ends_booking_days = offer_data_params.get( 'em_offer_ends_booking_days' );
                if( em_offer_ends_booking_days ) {
                    ticket_offer_data.em_offer_ends_booking_days = em_offer_ends_booking_days;
                }

                let em_offer_ends_booking_days_option = offer_data_params.get( 'em_offer_ends_booking_days_option' );
                if( em_offer_ends_booking_days_option ) {
                    ticket_offer_data.em_offer_ends_booking_days_option = em_offer_ends_booking_days_option;
                }

                let em_offer_ends_booking_event_option = offer_data_params.get( 'em_offer_ends_booking_event_option' );
                if( em_offer_ends_booking_event_option ) {
                    ticket_offer_data.em_offer_ends_booking_event_option = em_offer_ends_booking_event_option;
                }
            }
        }
        // offer type
        let em_ticket_offer_type = offer_data_params.get( 'em_ticket_offer_type' );
        if( em_ticket_offer_type ) {
            ticket_offer_data.em_ticket_offer_type = em_ticket_offer_type;

            if( em_ticket_offer_type == 'seat_based' ) {
                let em_ticket_offer_seat_option = offer_data_params.get( 'em_ticket_offer_seat_option' );
                if( em_ticket_offer_seat_option ) {
                    ticket_offer_data.em_ticket_offer_seat_option = em_ticket_offer_seat_option;
                }

                let em_ticket_offer_seat_number = offer_data_params.get( 'em_ticket_offer_seat_number' );
                if( em_ticket_offer_seat_number ) {
                    ticket_offer_data.em_ticket_offer_seat_number = em_ticket_offer_seat_number;
                }
            } else if( em_ticket_offer_type == 'role_based' ) {
                let em_ticket_offer_user_roles = offer_data_params.get( 'em_ticket_offer_user_roles' );
                if( em_ticket_offer_user_roles ) {
                    ticket_offer_data.em_ticket_offer_user_roles = $( '#em_ticket_offer_user_roles' ).val();
                }
            } else if( em_ticket_offer_type == 'volume_based' ) {
                let em_ticket_offer_volumn_count = offer_data_params.get( 'em_ticket_offer_volumn_count' );
                if( em_ticket_offer_volumn_count ) {
                    ticket_offer_data.em_ticket_offer_volumn_count = em_ticket_offer_volumn_count;
                }
            } else if( em_ticket_offer_type == 'count_based' ) {
                let em_ticket_offer_min_ticket_count = offer_data_params.get( 'em_ticket_offer_min_ticket_count' );
                if( em_ticket_offer_min_ticket_count ) {
                    ticket_offer_data.em_ticket_offer_min_ticket_count = em_ticket_offer_min_ticket_count;
                }

                let em_ticket_offer_ticket_type = offer_data_params.get( 'em_ticket_offer_ticket_type' );
                if( em_ticket_offer_ticket_type ) {
                    ticket_offer_data.em_ticket_offer_ticket_type = em_ticket_offer_ticket_type;
                }

                let em_ticket_offer_free_tickets = offer_data_params.get( 'em_ticket_offer_free_tickets' );
                if( em_ticket_offer_free_tickets ) {
                    ticket_offer_data.em_ticket_offer_free_tickets = em_ticket_offer_free_tickets;
                }
            }
        }
        // offer discount type
        let em_ticket_offer_discount_type = offer_data_params.get( 'em_ticket_offer_discount_type' );
        if( em_ticket_offer_discount_type ) {
            ticket_offer_data.em_ticket_offer_discount_type = em_ticket_offer_discount_type;
        } else{
            $( '#ep_ticket_offer_discount_type_error' ).html( requireString );
            document.getElementById( 'ep_ticket_offer_discount_type' ).focus();
            return false;
        }
        // offer discount
        let em_ticket_offer_discount = offer_data_params.get( 'em_ticket_offer_discount' );
        if( em_ticket_offer_discount ) {
            if( em_ticket_offer_discount_type == 'percentage' ) {
                if( em_ticket_offer_discount > 100 ) {
                    $( '#ep_ticket_offer_discount_error' ).html( em_event_meta_box_object.offer_per_more_then_100 );
                    document.getElementById( 'ep_ticket_offer_discount' ).focus();
                    return false;
                }
            }
            ticket_offer_data.em_ticket_offer_discount = em_ticket_offer_discount;
        } else{
            $( '#ep_ticket_offer_discount_error' ).html( requireString );
            document.getElementById( 'ep_ticket_offer_discount' ).focus();
            return false;
        }

        // check if edit popup open
        let parent_row_id = '';
        if( this.hasAttribute( 'data-edit_offer_id') ) {
            parent_row_id = $( this ).attr( 'data-edit_offer_id' );
        }

        let ep_event_ticket_offers = [];
        ep_event_ticket_offers = $( '#ep_event_ticket_offers' ).val();
        if( ep_event_ticket_offers ) {
            ep_event_ticket_offers = JSON.parse( ep_event_ticket_offers );
            let ticket_offer_len = ep_event_ticket_offers.length;
            if( ticket_offer_len > 0 ) {
                if( parent_row_id ) {
                    console.log(parent_row_id);
                    let id_num = parent_row_id.split( 'ep_ticket_offer_section' )[1];
                    ep_event_ticket_offers[id_num - 1] = ticket_offer_data;
                } else{
                    ep_event_ticket_offers.push( ticket_offer_data );
                }
            } else{
                ep_event_ticket_offers = [ ticket_offer_data ];
            }
        } else{
            ep_event_ticket_offers = [ ticket_offer_data ];
        }
        if( ep_event_ticket_offers ) {
            $( '#ep_event_ticket_offers' ).val( '' );
        }
        $( '#ep_event_ticket_offers' ).val( JSON.stringify( ep_event_ticket_offers ) );

        // initiate the offer section
        initiate_the_offer_section();

        // add individual offer data with row
        let new_ticket_offer_data = JSON.stringify( ticket_offer_data );
        let offer_list_count = $( '#ep_existing_offers_list .ep-offer-list-class' ).length;
        let next_offer_list_count = ++offer_list_count;
        let offer_list_data = '';
        let new_offer_row_id = 'ep_ticket_offer_section'+next_offer_list_count;
        let ep_offer_type = $( '#ep_ticket_offer_type option[value="'+ em_ticket_offer_type + '"' ).text();
        if( parent_row_id ) {
            $( '#' + parent_row_id ).data( 'offer_row_data', ticket_offer_data );
            $( '#' + parent_row_id + ' .ep-offer-name' ).text( em_ticket_offer_name );
            $( '#' + parent_row_id + ' .ep-offer-type' ).text( ep_offer_type );
            $( '#ep_ticket_add_offer' ).text( em_event_meta_box_object.add_text + ' ' + em_event_meta_box_object.offer_text );
            $( '#ep_ticket_add_offer' ).removeAttr( 'data-edit_offer_id' );
        } else{
            offer_list_data += "<div class='ep-box-row ep-border ep-rounded ep-my-2 ep-mx-0 ep-bg-white ep-items-center ep-shadow-sm ep-offer-list-class' id='"+new_offer_row_id+"' data-offer_row_data='"+new_ticket_offer_data+"'>";
                offer_list_data += '<div class="ep-box-col-2 ep-p-2">';
                    offer_list_data += '<span class="material-icons ep-cursor-move text-muted" data-parent_id="'+new_offer_row_id+'">drag_indicator</span>';
                offer_list_data += '</div>';
                offer_list_data += '<div class="ep-box-col-4 ep-p-2">';
                    offer_list_data += '<span class="ep-offer-name">'+em_ticket_offer_name+'</span>';
                offer_list_data += '</div>';
                offer_list_data += '<div class="ep-box-col-4 ep-p-2">';
                    offer_list_data += '<span class="ep-offer-type">'+ ep_offer_type +'</span>';
                offer_list_data += '</div>';
                offer_list_data += '<div class="ep-box-col-1 ep-p-2"><a href="javascript:void(0)" class="ep-ticket-offer-edit ep-text-primary ep-cursor" data-parent_id="'+new_offer_row_id+'">Edit</a></div>';
                offer_list_data += '<div class="ep-box-col-1"><a href="javascript:void(0)" class="ep-ticket-offer-delete ep-text-danger ep-cursor" data-parent_id="'+new_offer_row_id+'">Delete</a></div>';
            offer_list_data += '</div>';
            $( '#ep_existing_offers_list' ).append( offer_list_data );
            $( '#ep_ticket_offers_wrapper').show();
        }

    });

    // Delete ticket offer
    $( document ).on( 'click', '.ep-ticket-offer-delete', function() {
        let parentid = $( this ).data( 'parent_id' );
        if( $( '#' + parentid ).length > 0 ) {
            $( '#' + parentid ).remove();
        }
        let idnum = parentid.split( 'ep_ticket_offer_section' )[1];
        ep_event_ticket_offers = $( '#ep_event_ticket_offers' ).val();
        if( ep_event_ticket_offers ){
            ep_event_ticket_offers = JSON.parse( ep_event_ticket_offers );
            ep_event_ticket_offers.splice( idnum-1, 1 );
            $( '#ep_event_ticket_offers' ).val( JSON.stringify( ep_event_ticket_offers ) );
        }
        // reset elements
        $( '#ep_existing_offers_list .ep-offer-list-class' ).each( function(idx, ele) {
            let eleid = idx;
            let neweleid = ++idx;
            let timerid = this.id;
            let eleiddata = 'ep_ticket_offer_section'+neweleid;
            if( timerid !== eleiddata ) {
                $( this ).attr( 'id', eleiddata );
            }
            $( '#' + eleiddata + ' .ep-ticket-offer-sort' ).attr( 'data-parent_id', eleiddata );
            $( '#' + eleiddata + ' .ep-ticket-offer-edit' ).attr( 'data-parent_id', eleiddata );
            $( '#' + eleiddata + ' .ep-ticket-offer-delete' ).attr( 'data-parent_id', eleiddata );
        });
    });

    // sort ticket offers
    $( "#ep_existing_offers_list" ).on( "sortupdate", function( event, ui ) {
        let em_event_tickets_data = [];
        $( '#ep_existing_offers_list .ep-offer-list-class' ).each( function(idx, ele) {
            let eleid = idx;
            let neweleid = ++idx;
            let timerid = this.id;
            let eleiddata = 'ep_ticket_offer_section'+neweleid;
            if( timerid !== eleiddata ) {
                $( this ).attr( 'id', eleiddata );
            }
            $( '#' + eleiddata + ' .ep-ticket-cat-sort' ).attr( 'data-parent_id', eleiddata );
            $( '#' + eleiddata + ' .ep-ticket-cat-edit' ).attr( 'data-parent_id', eleiddata );
            $( '#' + eleiddata + ' .ep-ticket-cat-delete' ).attr( 'data-parent_id', eleiddata );

            let offer_row_data = $( '#' + eleiddata ).data( 'offer_row_data' );
            em_event_tickets_data.push( offer_row_data );
        });
        if( em_event_tickets_data ){
            $( '#ep_event_ticket_offers' ).val( JSON.stringify( em_event_tickets_data ) );
        }
    });

    // edit ticket offer
    $( document ).on( 'click', '.ep-ticket-offer-edit', function() {
        let parentid = $( this ).data( 'parent_id' );
        let offer_row_data = $( '#' + parentid ).data( 'offer_row_data' );
        if( offer_row_data ){
            let em_ticket_offer_name = offer_row_data.em_ticket_offer_name;
            $( 'input[name=em_ticket_offer_name]' ).val( em_ticket_offer_name );

            let em_ticket_offer_description = offer_row_data.em_ticket_offer_description;
            if( em_ticket_offer_description ) {
                $( 'textarea[name=em_ticket_offer_description]').val( em_ticket_offer_description );
            }

            let em_ticket_show_offer_detail = offer_row_data.em_ticket_show_offer_detail;
            if( em_ticket_show_offer_detail ) {
                $( 'input[name=em_ticket_show_offer_detail]').prop( 'checked', true );
            }
            // set offer start
            let em_offer_start_booking_type = offer_row_data.em_offer_start_booking_type;
            if( em_offer_start_booking_type ) {
                $( 'select[name=em_offer_start_booking_type]' ).val( em_offer_start_booking_type );
                $( 'select[name=em_offer_start_booking_type]').trigger( 'change' );
                if( em_offer_start_booking_type == 'custom_date' ) {
                    let em_offer_start_booking_date = offer_row_data.em_offer_start_booking_date;
                    if( em_offer_start_booking_date ){ 
                        $( 'input[name=em_offer_start_booking_date]' ).val( em_offer_start_booking_date );
                    }
    
                    let em_offer_start_booking_time = offer_row_data.em_offer_start_booking_time;
                    if( em_offer_start_booking_time ){ 
                        $( 'input[name=em_offer_start_booking_time]' ).val( em_offer_start_booking_time );
                    }
                } else if( em_offer_start_booking_type == 'event_date' ) {
                    let em_offer_start_booking_event_option = offer_row_data.em_offer_start_booking_event_option;
                    if( em_offer_start_booking_event_option ) {
                        $( 'select[name="em_offer_start_booking_event_option"]' ).val( em_offer_start_booking_event_option );
                    }
                } else if( em_offer_start_booking_type == 'relative_date' ) {
                    let em_offer_start_booking_days = offer_row_data.em_offer_start_booking_days;
                    if( em_offer_start_booking_days ) {
                        $( 'input[name=em_offer_start_booking_days]' ).val( em_offer_start_booking_days );
                    }
    
                    let em_offer_start_booking_days_option = offer_row_data.em_offer_start_booking_days_option;
                    if( em_offer_start_booking_days_option ) {
                        $( 'select[name="em_offer_start_booking_days_option"]' ).val( em_offer_start_booking_days_option );
                    }
    
                    let em_offer_start_booking_event_option = offer_row_data.em_offer_start_booking_event_option;
                    if( em_offer_start_booking_event_option ) {
                        $( 'select[name="em_offer_start_booking_event_option"]' ).val( em_offer_start_booking_event_option );
                    }
                }
            }

            // set offer ends
            let em_offer_ends_booking_type = offer_row_data.em_offer_ends_booking_type;
            if( em_offer_ends_booking_type ) {
                $( 'select[name=em_offer_ends_booking_type]' ).val( em_offer_ends_booking_type );
                $( 'select[name=em_offer_ends_booking_type]').trigger( 'change' );
                if( em_offer_ends_booking_type == 'custom_date' ) {
                    let em_offer_ends_booking_date = offer_row_data.em_offer_ends_booking_date;
                    if( em_offer_ends_booking_date ){ 
                        $( 'input[name=em_offer_ends_booking_date]' ).val( em_offer_ends_booking_date );
                    }
    
                    let em_offer_ends_booking_time = offer_row_data.em_offer_ends_booking_time;
                    if( em_offer_ends_booking_time ){ 
                        $( 'input[name=em_offer_ends_booking_time]' ).val( em_offer_ends_booking_time );
                    }
                } else if( em_offer_ends_booking_type == 'event_date' ) {
                    let em_offer_ends_booking_event_option = offer_row_data.em_offer_ends_booking_event_option;
                    if( em_offer_ends_booking_event_option ) {
                        $( 'select[name="em_offer_ends_booking_event_option"]' ).val( em_offer_ends_booking_event_option );
                    }
                } else if( em_offer_ends_booking_type == 'relative_date' ) {
                    let em_offer_ends_booking_days = offer_row_data.em_offer_ends_booking_days;
                    if( em_offer_ends_booking_days ) {
                        $( 'input[name=em_offer_ends_booking_days]' ).val( em_offer_ends_booking_days );
                    }
    
                    let em_offer_ends_booking_days_option = offer_row_data.em_offer_ends_booking_days_option;
                    if( em_offer_ends_booking_days_option ) {
                        $( 'select[name="em_offer_ends_booking_days_option"]' ).val( em_offer_ends_booking_days_option );
                    }
    
                    let em_offer_ends_booking_event_option = offer_row_data.em_offer_ends_booking_event_option;
                    if( em_offer_ends_booking_event_option ) {
                        $( 'select[name="em_offer_ends_booking_event_option"]' ).val( em_offer_ends_booking_event_option );
                    }
                }
            }

            // set offer type
            let em_ticket_offer_type = offer_row_data.em_ticket_offer_type;
            if( em_ticket_offer_type ) {
                $( 'select[name="em_ticket_offer_type"]' ).val( em_ticket_offer_type )
                $( '#ep_ticket_offer_type' ).trigger( 'change' );
                if( em_ticket_offer_type == 'seat_based' ) {
                    let em_ticket_offer_seat_option = offer_row_data.em_ticket_offer_seat_option;
                    if( em_ticket_offer_seat_option ) {
                        $( 'select[name="em_ticket_offer_seat_option"]' ).val( em_ticket_offer_seat_option )
                    }

                    let em_ticket_offer_seat_number = offer_row_data.em_ticket_offer_seat_number;
                    if( em_ticket_offer_seat_number ) {
                        $( 'input[name=em_ticket_offer_seat_number]').val( em_ticket_offer_seat_number );
                    }
                } else if( em_ticket_offer_type == 'role_based' ) {
                    let em_ticket_offer_user_roles = offer_row_data.em_ticket_offer_user_roles;
                    if( em_ticket_offer_user_roles ) {
                        $( 'select[name="em_ticket_offer_user_roles"]' ).val( em_ticket_offer_user_roles );
                        $( '#em_ticket_offer_user_roles' ).trigger( 'change' );
                    }
                } else if( em_ticket_offer_type == 'volume_based' ) {
                    let em_ticket_offer_volumn_count = offer_row_data.em_ticket_offer_volumn_count;
                    if( em_ticket_offer_volumn_count ) {
                        $( 'input[name=em_ticket_offer_volumn_count]').val( em_ticket_offer_volumn_count );
                    }
                } else if( em_ticket_offer_type == 'count_based' ) {
                    let em_ticket_offer_min_ticket_count = offer_row_data.em_ticket_offer_min_ticket_count;
                    if( em_ticket_offer_min_ticket_count ) {
                        $( 'input[name=em_ticket_offer_min_ticket_count]').val( em_ticket_offer_min_ticket_count );
                    }

                    let em_ticket_offer_ticket_type = offer_row_data.em_ticket_offer_ticket_type;
                    if( em_ticket_offer_ticket_type ) {
                        $( 'select[name="em_ticket_offer_ticket_type"]' ).val( em_ticket_offer_ticket_type )
                    }

                    let em_ticket_offer_free_tickets = offer_row_data.em_ticket_offer_free_tickets;
                    if( em_ticket_offer_free_tickets ) {
                        $( 'input[name=em_ticket_offer_free_tickets]').val( em_ticket_offer_free_tickets );
                    }
                }
            }
            // set offer discount type
            let em_ticket_offer_discount_type = offer_row_data.em_ticket_offer_discount_type;
            if( em_ticket_offer_discount_type ) {
                $( 'select[name="em_ticket_offer_discount_type"]' ).val( em_ticket_offer_discount_type )
            }
            // set offer discount
            let em_ticket_offer_discount = offer_row_data.em_ticket_offer_discount;
            if( em_ticket_offer_discount ) {
                $( 'input[name=em_ticket_offer_discount]').val( em_ticket_offer_discount );
            }

            $( '#ep_ticket_add_offer' ).text( em_event_meta_box_object.update_text + ' ' + em_event_meta_box_object.offer_text );
            $( '#ep_ticket_add_offer' ).attr( 'data-edit_offer_id', parentid );
        }
    });

    // save the ticket tier
    $( document ).on( 'click', '#ep_save_ticket_tier', function() {
        $('body').removeClass('ep-modal-open-body');
        $( '.ep-error-message' ).text( '' );
        var original_data = $( '#ep_event_ticket_tier_modal' ).find(
			'input, select, textarea'
		);
        let tickets_data = new URLSearchParams( original_data.serialize() );
        let ticket_tier_data = {};
        let requireString = get_translation_string( 'required' );
        let em_event_ticket_name = tickets_data.get( 'name' );
        if( !em_event_ticket_name ) {
            $( '#ep_event_ticket_name_error' ).html( requireString );
            document.getElementById( 'ep_event_ticke_name' ).focus();
            return false;
        }
        ticket_tier_data.name = em_event_ticket_name;

        let em_event_ticket_description = tickets_data.get( 'description' );
        if( em_event_ticket_description ) {
            ticket_tier_data.description = em_event_ticket_description;
        }
        // icon
        let ticket_icon = tickets_data.get( 'icon' );
        if( ticket_icon ) {
            ticket_tier_data.icon = ticket_icon;
        }

        let em_event_ticket_qty = tickets_data.get( 'capacity' );
        if( em_event_ticket_qty ) {
            // check for max quantity
            let max_qty = $( 'input[name=capacity]' ).attr( 'max' );
            if( max_qty ) {
                if( parseInt( em_event_ticket_qty, 10 ) > parseInt( max_qty, 10 ) ) {
                    $( '#ep_event_ticket_qty_error' ).html( em_event_meta_box_object.max_capacity_error + ' ' + max_qty );
                    document.getElementById( 'ep_event_ticket_qty' ).focus();
                    return false;
                }
            }
            ticket_tier_data.capacity = em_event_ticket_qty;
        } else{
            $( '#ep_event_ticket_qty_error' ).html( requireString );
            document.getElementById( 'ep_event_ticket_qty' ).focus();
            return false;
        }

        let em_event_ticket_price = tickets_data.get( 'price' );
        if( em_event_ticket_price ) {
            ticket_tier_data.price = em_event_ticket_price;
        }
        // additional fees
        let additional_fee_arr = [];
        let additional_fee_wrapper_len = $( '#ep_additional_ticket_fee_wrapper .ep-additional-ticket-fee-row' ).length;
        if( additional_fee_wrapper_len > 0 ) {
            $( '#ep_additional_ticket_fee_wrapper .ep-additional-ticket-fee-row' ).each( function( ind, data ) {
                let new_ind = ++ind;
                if( $( '#ep_additional_ticket_fee_label' + new_ind ).length > 0 ) {
                    let label_val = $( '#ep_additional_ticket_fee_label' + new_ind ).val();
                    let price_val = $( '#ep_additional_ticket_fee_price' + new_ind ).val();
                    let add_data = { label: label_val, price: price_val };
                    additional_fee_arr.push( add_data );
                }
            });
        }
        ticket_tier_data.ep_additional_ticket_fee_data = additional_fee_arr;

        // allow cancellation
        let em_allow_cancellation = tickets_data.get( 'allow_cancellation' );
        if( em_allow_cancellation ){
            ticket_tier_data.allow_cancellation = em_allow_cancellation;
        }
        // show remaining tickets
        let em_show_remaining_tickets = tickets_data.get( 'show_remaining_tickets' );
        if( em_show_remaining_tickets ){
            ticket_tier_data.show_remaining_tickets = em_show_remaining_tickets;
        }
        // start booking
        let em_ticket_start_booking_type = tickets_data.get( 'em_ticket_start_booking_type' );
        let parse_start_booking_date = '';
        if( em_ticket_start_booking_type ) {
            ticket_tier_data.em_ticket_start_booking_type = em_ticket_start_booking_type;
            if( em_ticket_start_booking_type == 'custom_date' ) {
                let em_ticket_start_booking_date = tickets_data.get( 'em_ticket_start_booking_date' );
                if( em_ticket_start_booking_date ){ 
                    ticket_tier_data.em_ticket_start_booking_date = em_ticket_start_booking_date;
                }

                let em_ticket_start_booking_time = tickets_data.get( 'em_ticket_start_booking_time' );
                if( em_ticket_start_booking_time ){ 
                    ticket_tier_data.em_ticket_start_booking_time = em_ticket_start_booking_time;
                }
            } else if( em_ticket_start_booking_type == 'event_date' ) {
                let em_ticket_start_booking_event_option = tickets_data.get( 'em_ticket_start_booking_event_option' );
                if( em_ticket_start_booking_event_option ){ 
                    ticket_tier_data.em_ticket_start_booking_event_option = em_ticket_start_booking_event_option;
                }
            } else if( em_ticket_start_booking_type == 'relative_date' ) {
                let em_ticket_start_booking_days = tickets_data.get( 'em_ticket_start_booking_days' );
                if( em_ticket_start_booking_days ){ 
                    ticket_tier_data.em_ticket_start_booking_days = em_ticket_start_booking_days;
                }

                let em_ticket_start_booking_days_option = tickets_data.get( 'em_ticket_start_booking_days_option' );
                if( em_ticket_start_booking_days_option ){ 
                    ticket_tier_data.em_ticket_start_booking_days_option = em_ticket_start_booking_days_option;
                }

                let em_ticket_start_booking_event_option = tickets_data.get( 'em_ticket_start_booking_event_option' );
                if( em_ticket_start_booking_event_option ){ 
                    ticket_tier_data.em_ticket_start_booking_event_option = em_ticket_start_booking_event_option;
                }
            }
        }
        //end booking
        let em_ticket_ends_booking_type = tickets_data.get( 'em_ticket_ends_booking_type' );
        if( em_ticket_ends_booking_type ) {
            ticket_tier_data.em_ticket_ends_booking_type = em_ticket_ends_booking_type;
            if( em_ticket_ends_booking_type == 'custom_date' ) {
                let em_ticket_ends_booking_date = tickets_data.get( 'em_ticket_ends_booking_date' );
                if( em_ticket_ends_booking_date ){ 
                    ticket_tier_data.em_ticket_ends_booking_date = em_ticket_ends_booking_date;
                }

                let em_ticket_ends_booking_time = tickets_data.get( 'em_ticket_ends_booking_time' );
                if( em_ticket_ends_booking_time ){ 
                    ticket_tier_data.em_ticket_ends_booking_time = em_ticket_ends_booking_time;
                }
            } else if( em_ticket_ends_booking_type == 'event_date' ) {
                let em_ticket_ends_booking_event_option = tickets_data.get( 'em_ticket_ends_booking_event_option' );
                if( em_ticket_ends_booking_event_option ){ 
                    ticket_tier_data.em_ticket_ends_booking_event_option = em_ticket_ends_booking_event_option;
                }
            } else if( em_ticket_ends_booking_type == 'relative_date' ) {
                let em_ticket_ends_booking_days = tickets_data.get( 'em_ticket_ends_booking_days' );
                if( em_ticket_ends_booking_days ){ 
                    ticket_tier_data.em_ticket_ends_booking_days = em_ticket_ends_booking_days;
                }

                let em_ticket_ends_booking_days_option = tickets_data.get( 'em_ticket_ends_booking_days_option' );
                if( em_ticket_ends_booking_days_option ){ 
                    ticket_tier_data.em_ticket_ends_booking_days_option = em_ticket_ends_booking_days_option;
                }

                let em_ticket_ends_booking_event_option = tickets_data.get( 'em_ticket_ends_booking_event_option' );
                if( em_ticket_ends_booking_event_option ){ 
                    ticket_tier_data.em_ticket_ends_booking_event_option = em_ticket_ends_booking_event_option;
                }
            }
        }
        // show sale start and end dates
        let em_show_ticket_booking_dates = tickets_data.get( 'show_ticket_booking_dates' );
        if( em_show_ticket_booking_dates ){
            ticket_tier_data.show_ticket_booking_dates = em_show_ticket_booking_dates;
        }
        // min ticket number
        let em_min_ticket_no = tickets_data.get( 'min_ticket_no' );
        if( em_min_ticket_no == 0 ){
            $( '#ep_event_ticket_min_ticket_error' ).html( em_event_meta_box_object.min_ticket_no_zero_error );
            document.getElementById( 'ep_min_ticket_no' ).focus();
            return false;
        }
        if( em_min_ticket_no ){
            ticket_tier_data.min_ticket_no = em_min_ticket_no;
        }
        // max ticket number
        let em_max_ticket_no = tickets_data.get( 'max_ticket_no' );
        if( em_max_ticket_no == 0 ){
            $( '#ep_event_ticket_max_ticket_error' ).html( em_event_meta_box_object.max_ticket_no_zero_error );
            document.getElementById( 'ep_max_ticket_no' ).focus();
            return false;
        }
        if( em_max_ticket_no ) {
            if( parseInt(em_max_ticket_no) < parseInt(em_min_ticket_no) ) {
                $( '#ep_event_ticket_max_ticket_error' ).html( em_event_meta_box_object.max_less_then_min_error );
                document.getElementById( 'ep_max_ticket_no' ).focus();
                return false;
            }
            let capacity = $( 'input[name=capacity]' ).val();
            if( capacity ) {
                if( parseInt( em_max_ticket_no, 10 ) > parseInt( capacity, 10 ) ) {
                    $( '#ep_event_ticket_max_ticket_error' ).html( em_event_meta_box_object.max_capacity_error + ' ' + capacity );
                    document.getElementById( 'ep_max_ticket_no' ).focus();
                    return false;
                }
            }
            ticket_tier_data.max_ticket_no = em_max_ticket_no;
        }

        // ticket template
        let em_ticket_template = tickets_data.get( 'em_ticket_template' );
        if( em_ticket_template ) {
            ticket_tier_data.ticket_template_id = em_ticket_template;
        }
        
        // visibility
        let em_tickets_user_visibility = tickets_data.get( 'em_tickets_user_visibility' );
        if( em_tickets_user_visibility ){
            ticket_tier_data.em_tickets_user_visibility = em_tickets_user_visibility;
            if( em_tickets_user_visibility == 'user_roles' ) {
                let em_ticket_visibility_user_roles = tickets_data.get( 'em_ticket_visibility_user_roles[]' );
                if( em_ticket_visibility_user_roles ) {
                    em_ticket_visibility_user_roles = $( '#ep_ticket_visibility_user_roles' ).val();
                    ticket_tier_data.em_ticket_visibility_user_roles = em_ticket_visibility_user_roles;
                }
            }
        }
        let em_ticket_for_invalid_user = tickets_data.get( 'em_ticket_for_invalid_user' );
        if( em_ticket_for_invalid_user ){
            ticket_tier_data.em_ticket_for_invalid_user = em_ticket_for_invalid_user;
        }
        // time restriction
        let em_tickets_visibility_time_restrictions = tickets_data.get( 'em_tickets_visibility_time_restrictions' );
        if( em_tickets_visibility_time_restrictions ){
            ticket_tier_data.em_tickets_visibility_time_restrictions = em_tickets_visibility_time_restrictions;
            if( em_tickets_visibility_time_restrictions == 'between_dates' ) {
                // start visibility
                let ep_ticket_start_visibility = tickets_data.get( 'ep_ticket_start_visibility' );
                if( ep_ticket_start_visibility ) {
                    ticket_tier_data.ep_ticket_start_visibility = ep_ticket_start_visibility;
                    if( ep_ticket_start_visibility == 'custom_date' ) {
                        let em_visibility_start_booking_date = tickets_data.get( 'em_visibility_start_booking_date' );
                        if( em_visibility_start_booking_date ){ 
                            ticket_tier_data.em_visibility_start_booking_date = em_visibility_start_booking_date;
                        }
                        let em_visibility_start_booking_time = tickets_data.get( 'em_visibility_start_booking_time' );
                        if( em_visibility_start_booking_time ){ 
                            ticket_tier_data.em_visibility_start_booking_time = em_visibility_start_booking_time;
                        }
                    } else if( ep_ticket_start_visibility == 'event_date' ) {
                        let em_visibility_start_booking_event_option = tickets_data.get( 'em_visibility_start_booking_event_option' );
                        if( em_visibility_start_booking_event_option ){ 
                            ticket_tier_data.em_visibility_start_booking_event_option = em_visibility_start_booking_event_option;
                        }
                    } else if( ep_ticket_start_visibility == 'relative_date' ) {
                        let em_visibility_start_booking_days = tickets_data.get( 'em_visibility_start_booking_days' );
                        if( em_visibility_start_booking_days ){ 
                            ticket_tier_data.em_visibility_start_booking_days = em_visibility_start_booking_days;
                        }

                        let em_visibility_start_booking_days_option = tickets_data.get( 'em_visibility_start_booking_days_option' );
                        if( em_visibility_start_booking_days_option ){ 
                            ticket_tier_data.em_visibility_start_booking_days_option = em_visibility_start_booking_days_option;
                        }

                        let em_visibility_start_booking_event_option = tickets_data.get( 'em_visibility_start_booking_event_option' );
                        if( em_visibility_start_booking_event_option ){ 
                            ticket_tier_data.em_visibility_start_booking_event_option = em_visibility_start_booking_event_option;
                        }
                    }
                }
                //end visibility
                let em_visibility_ends_booking_type = tickets_data.get( 'em_visibility_ends_booking_type' );
                if( em_visibility_ends_booking_type ) {
                    ticket_tier_data.em_visibility_ends_booking_type = em_visibility_ends_booking_type;
                    if( em_visibility_ends_booking_type == 'custom_date' ) {
                        let em_visibility_ends_booking_date = tickets_data.get( 'em_visibility_ends_booking_date' );
                        if( em_visibility_ends_booking_date ){ 
                            ticket_tier_data.em_visibility_ends_booking_date = em_visibility_ends_booking_date;
                        }

                        let em_visibility_ends_booking_time = tickets_data.get( 'em_visibility_ends_booking_time' );
                        if( em_visibility_ends_booking_time ){ 
                            ticket_tier_data.em_visibility_ends_booking_time = em_visibility_ends_booking_time;
                        }
                    } else if( em_visibility_ends_booking_type == 'event_date' ) {
                        let em_visibility_ends_booking_event_option = tickets_data.get( 'em_visibility_ends_booking_event_option' );
                        if( em_visibility_ends_booking_event_option ){ 
                            ticket_tier_data.em_visibility_ends_booking_event_option = em_visibility_ends_booking_event_option;
                        }
                    } else if( em_visibility_ends_booking_type == 'relative_date' ) {
                        let em_visibility_ends_booking_days = tickets_data.get( 'em_visibility_ends_booking_days' );
                        if( em_visibility_ends_booking_days ){ 
                            ticket_tier_data.em_visibility_ends_booking_days = em_visibility_ends_booking_days;
                        }

                        let em_visibility_ends_booking_days_option = tickets_data.get( 'em_visibility_ends_booking_days_option' );
                        if( em_visibility_ends_booking_days_option ){ 
                            ticket_tier_data.em_visibility_ends_booking_days_option = em_visibility_ends_booking_days_option;
                        }

                        let em_visibility_ends_booking_event_option = tickets_data.get( 'em_visibility_ends_booking_event_option' );
                        if( em_visibility_ends_booking_event_option ){ 
                            ticket_tier_data.em_visibility_ends_booking_event_option = em_visibility_ends_booking_event_option;
                        }
                    }
                }
            }
        }
        // offers
        let ep_ticket_offer_name = $( '#ep_ticket_offer_name' ).val();
        if( ep_ticket_offer_name ) {
            $( '#ep_ticket_offer_not_save_error' ).html( em_event_meta_box_object.offer_not_save_error_text );
            return false;
        }
        let em_event_ticket_offers = tickets_data.get( 'offers' );
        if( em_event_ticket_offers ) {
            ticket_tier_data.offers = em_event_ticket_offers;
        }

        // multiple offer option
        let multiple_offers_option = tickets_data.get( 'multiple_offers_option' );
        if( multiple_offers_option ) {
            ticket_tier_data.multiple_offers_option = multiple_offers_option;
        }

        // ticket offer max discount
        let multiple_offers_max_discount = tickets_data.get( 'multiple_offers_max_discount' );
        if( multiple_offers_max_discount ) {
            ticket_tier_data.multiple_offers_max_discount = multiple_offers_max_discount;
        }

        initiate_the_ticket_modal();

        let cat_id = '', template_id = '', cat_ticket_len = '', next_cat_ticket_len = '', cat_capacity = '';
        let em_ticket_category_id = tickets_data.get( 'em_ticket_category_id' );
        if( em_ticket_category_id ){
            cat_ticket_len = $( '#' + em_ticket_category_id + ' .ep-tickets-cate-ticket-row' ).length;
            next_cat_ticket_len = ++cat_ticket_len;
            cat_id = em_ticket_category_id.split( 'ep_ticket_cat_section' )[1];
            template_id = 'ep_ticket_category_' + cat_id + '_ticket_' + next_cat_ticket_len;
            let cat_row_data = $( '#' + em_ticket_category_id ).data( 'cat_row_data' );
            cat_capacity = cat_row_data.capacity;
        } else{
            /* cat_ticket_len = $( '#ep_existing_tickets_list .ep-tickets-cate-ticket-row' ).length;
            next_cat_ticket_len = ++cat_ticket_len;
            template_id = 'ep_ticket_list_' + next_cat_ticket_len; */
            
            cat_ticket_len = $( '#ep_existing_individual_tickets_list .ep-tickets-indi-ticket-row' ).length;
            next_cat_ticket_len = ++cat_ticket_len;
            template_id = 'ep_ticket_list_' + next_cat_ticket_len;
        }

        let em_ticket_id = tickets_data.get( 'em_ticket_id' );
        if( em_ticket_id ) {
            if( isNaN( ticket_tier_data.id ) ) {
                template_id = em_ticket_id
            }
            ticket_tier_data.id = em_ticket_id;
        } else{
            ticket_tier_data.id = template_id;
        }
        let new_ticket_tier_data = JSON.stringify( ticket_tier_data );

        let em_ticket_parent_div_id = tickets_data.get( 'em_ticket_parent_div_id' );
        if( em_ticket_parent_div_id ) {
            $( '#' + em_ticket_parent_div_id ).remove();
        }
        let ticket_template = '';
        if( em_ticket_category_id ) {
            ticket_template += "<div class='ep-box-row ep-tickets-cate-ticket-row' id='"+template_id+"' data-ticket_row_data='"+new_ticket_tier_data+"'>";
        } else{
            ticket_template += "<div class='ep-box-row ep-tickets-indi-ticket-row' id='"+template_id+"' data-ticket_row_data='"+new_ticket_tier_data+"'>";
        }
            ticket_template += '<div class="ep-box-col-12">';
                ticket_template += '<div class="ep-box-row ep-border ep-rounded ep-ml-2 ep-my-1 ep-mr-2 ep-bg-white ep-items-center ui-state-default">';
                    ticket_template += '<div class="ep-box-col-1 ep-p-3">';
                        ticket_template += '<span class="ep-ticket-row-sort ep-cursor-move material-icons ep-text-muted">drag_indicator</span>';
                    ticket_template += '</div>';
                    ticket_template += '<div class="ep-box-col-3 ep-p-3">';
                        ticket_template += '<span>'+em_event_ticket_name+'</span>';
                    ticket_template += '</div>';
                    ticket_template += '<div class="ep-box-col-2 ep-p-3">';
                        if( em_event_ticket_price ){
                            ticket_template += '<span>'+ ep_format_price_with_position( em_event_ticket_price )+'</span>';
                        }
                    ticket_template += '</div>';
                    ticket_template += '<div class="ep-box-col-3 ep-p-3">';
                        if( em_ticket_category_id ) {
                            ticket_template += '<span>' + em_event_meta_box_object.ticket_capacity_text + ' ' + em_event_ticket_qty + '/' + cat_capacity + '</span>';
                        } else{
                            ticket_template += '<span>' + em_event_meta_box_object.ticket_capacity_text + ' ' + em_event_ticket_qty + '</span>';
                        }
                    ticket_template += '</div>';
                    ticket_template += '<div class="ep-box-col-1 ep-p-3">';
                        ticket_template += ' <a href="javascript:void(0)" class="ep-ticket-row-edit ep-text-primary ep-cursor" data-parent_id="'+template_id+'" data-parent_category_id="'+em_ticket_category_id+'">Edit</a>';
                    ticket_template += '</div>';
                    ticket_template += '<div class="ep-box-col-1 ep-p-3">';
                        ticket_template += '<span class="ep-ticket-row-delete ep-text-danger ep-cursor" data-parent_id="'+template_id+'">Delete</span>';
                    ticket_template += '</div>';
                ticket_template += '</div>';
            ticket_template += '</div>';
        ticket_template += '</div>';

        // check if category id passed
        if( em_ticket_category_id ) { //add ticket inside the category
            // if edit actual saved ticket
            if( isNaN( ticket_tier_data.id ) && ( $( '#' + ticket_tier_data.id ).length > 0 ) ) {
                $( '#' + ticket_tier_data.id ).replaceWith( ticket_template );
                $( '#' + ticket_tier_data.id ).attr( 'data-ticket_row_data', new_ticket_tier_data );
            } else{
                $( '#' + em_ticket_category_id + ' .ep-ticket-category-section' ).append( ticket_template );
            }
            $( 'input[name=em_ticket_category_id]' ).val( '' );
            // add ticket data inside the category data
            let ca_row_number = em_ticket_category_id.split( 'ep_ticket_cat_section' )[1];
            ca_row_number--;
            let ep_ticket_category_data = $( '#ep_ticket_category_data' ).val();
            if( ep_ticket_category_data ) {
                ep_ticket_category_data = JSON.parse( ep_ticket_category_data );
                $.each( ep_ticket_category_data, function( idx, data ) {
                    if( idx == ca_row_number ) {
                        if( data.tickets ) {
                            let found_tic_data = 0;
                            // check if ticket exist
                            $.each( data.tickets, function( tid, tdata ) {
                                if( tdata.id == ticket_tier_data.id ) {
                                    data.tickets[tid] = ticket_tier_data;
                                    found_tic_data = 1;
                                    return false;
                                }
                            });
                            if( !found_tic_data ) {
                                data.tickets.push( ticket_tier_data )
                            }
                        } else{
                            data.tickets = [ticket_tier_data];
                        }
                    }
                    ep_ticket_category_data[idx] = data;
                });
                $( '#ep_ticket_category_data' ).val( JSON.stringify( ep_ticket_category_data ) );
            }
        } else{ // add ticket individually
            if( isNaN( ticket_tier_data.id ) && ( $( '#' + ticket_tier_data.id ).length > 0 ) ) {
                $( '#' + ticket_tier_data.id ).replaceWith( ticket_template );
                $( '#' + ticket_tier_data.id ).attr( 'data-ticket_row_data', new_ticket_tier_data );
            } else{
                $( '#ep_existing_individual_tickets_list' ).append ( ticket_template );
            }

            let ep_ticket_individual_data = $( '#ep_ticket_individual_data' ).val();
            if( ep_ticket_individual_data ) {
                ep_ticket_individual_data = JSON.parse( ep_ticket_individual_data );
                let found_tic_data = 0;
                $.each( ep_ticket_individual_data, function( ind_tic_id, ind_tic_data ) {
                    if( ind_tic_data.id == ticket_tier_data.id ) {
                        ep_ticket_individual_data[ind_tic_id] = ticket_tier_data;
                        found_tic_data = 1;
                        return false;
                    }
                });
                if( !found_tic_data ) {
                    ep_ticket_individual_data.push( ticket_tier_data );
                }
            } else{
                ep_ticket_individual_data = [ticket_tier_data];
            }
            $( '#ep_ticket_individual_data' ).val( JSON.stringify( ep_ticket_individual_data ) );
        }
        // Update event has ticket
        $( '#ep_event_has_ticket' ).val( 1 );
        // close ticket modal
        $( '#ep_event_ticket_tier_modal' ).closePopup({
            anim: (!$(this).attr('data-animation') || $(this).data('animation') == null) ? 'ep-modal-' : $(this).data('animation')
        });
        
    });

    // delete ticket
    $( document ).on( 'click', '.ep-ticket-row-delete', function() {
        let parent_id = $( this ).data( 'parent_id' );
        let ticket_data = $( '#' + parent_id ).data( 'ticket_row_data' );
        if( ticket_data ) {
            let ticket_data_id = ticket_data.id;
            if( ticket_data_id ) {
                let ep_ticket_individual_delete_ids = $( '#ep_ticket_individual_delete_ids' ).val();
                if( ep_ticket_individual_delete_ids ) {
                    ep_ticket_individual_delete_ids = JSON.parse( ep_ticket_individual_delete_ids );
                    ep_ticket_individual_delete_ids.push( ticket_data_id );
                } else{
                    ep_ticket_individual_delete_ids = [ticket_data_id];
                }
                $( '#ep_ticket_individual_delete_ids' ).val( JSON.stringify( ep_ticket_individual_delete_ids ) );
            }
        }
        $( '#' + parent_id ).remove();
    });

    // edit tickets
    $( document ).on( 'click', '.ep-ticket-row-edit', function() {
        initiate_the_ticket_modal();
        let parentid = $( this ).data( 'parent_id' );
        let ticket_row_data = $( '#' + parentid ).data( 'ticket_row_data' );
        if( ticket_row_data ){
            //console.log(ticket_row_data);
            $( 'input[name=em_ticket_parent_div_id]' ).val( parentid );
            
            let ticket_id = ticket_row_data.id;
            if( ticket_id ) {
                $( 'input[name=em_ticket_id]' ).val( ticket_id );
            }
            // check for parent category
            let parent_category_id = $( this ).data( 'parent_category_id' );
            if( parent_category_id ) {
                $( 'input[name=em_ticket_category_id]' ).val( parent_category_id );
                
                // update remaining capacity
                let cat_row_data = $( '#' + parent_category_id ).data( 'cat_row_data' );
                if( cat_row_data.capacity ) {
                    let cat_capacity = cat_row_data.capacity;
                    // check for created tickets
                    let ticket_capacity = 0;
                    if( $( '#' + parent_category_id + ' .ep-tickets-cate-ticket-row' ).length > 0 ) {
                        $.each( $( '#' + parent_category_id + ' .ep-tickets-cate-ticket-row' ), function() {
                            let cat_ticket_row_data = $( this ).data( 'ticket_row_data' );
                            if( cat_ticket_row_data ) { 
                                let em_event_ticket_qty = cat_ticket_row_data.capacity;
                                if( em_event_ticket_qty ) {
                                    ticket_capacity = parseInt( ticket_capacity, 10 ) + parseInt( em_event_ticket_qty, 10 );
                                }
                            } 
                        });
                        if( ticket_capacity > 0 ) {
                            cat_capacity = cat_capacity - ticket_capacity;
                        }
                    }
                    // update the capacity
                    // add the ticket capacity to max var
                    cat_capacity = parseInt( cat_capacity, 10 ) + parseInt( ticket_row_data.capacity, 10 );
                    let max_label = $( '#ep_ticket_remaining_capacity' ).data( 'max_ticket_label' );
                    $( '#ep_ticket_remaining_capacity' ).html( max_label + ': ' + cat_capacity );
                    // set max capacity
                    if( cat_capacity ) {
                        $( '#ep_event_ticket_qty' ).attr( 'max', cat_capacity );
                    }
                }
            }

            let name = ticket_row_data.name;
            $( '#ep_event_ticke_name' ).val( name );
            
            let description = ticket_row_data.description;
            $( '#ep_event_ticke_description' ).val( description );

            // icon pending
            let icon_url = ticket_row_data.icon_url;
            if( icon_url ) {
                $( '#ep_event_ticket_icon' ).val( ticket_row_data.icon );
                let imageHtml = '<span class="ep-event-offer-icon ep-d-flex ep-mt-2">';
                    imageHtml += '<i class="ep-remove-event-offer-icon dashicons dashicons-trash ep-cursor"></i>';
                    imageHtml += '<img src="'+icon_url+'" data-image_id="'+ticket_row_data.icon+'" width="50">';
                imageHtml += '</span>';
                $( '#ep_event_ticket_icon_image' ).html( imageHtml );
            }

            let capacity = ticket_row_data.capacity;
            $( '#ep_event_ticket_qty' ).val( capacity );

            let price = ticket_row_data.price;
            $( '#ep_event_ticket_price' ).val( price );
            // additional fee
            let additional_fees = ticket_row_data.additional_fees;
            if( additional_fees && additional_fees.length > 0 ) {
                additional_fees = JSON.parse( additional_fees );
                let next_row_len = 1;
                $.each( additional_fees, function( inx, data ) {
                    let additional_fee_row = '';
                    let row_id = 'ep_additional_ticket_fee_row'+next_row_len;
                    additional_fee_row += '<div class="ep-additional-ticket-fee-row ep-box-row" id="'+row_id+'">';
                        additional_fee_row += '<div class="ep-additional-fee ep-box-col-5 ep-mt-3">';
                            additional_fee_row += '<input type="text" class="ep-form-control" id="ep_additional_ticket_fee_label'+next_row_len+'" placeholder="'+em_event_meta_box_object.additional_label_text+'" name="ep_additional_ticket_fee['+next_row_len+'][label]" value="'+data.label+'">';
                        additional_fee_row += '</div>';
                        additional_fee_row += '<div class="ep-additional-fee ep-box-col-5 ep-mt-3">';
                            additional_fee_row += '<input type="number" class="ep-form-control" id="ep_additional_ticket_fee_price'+next_row_len+'" placeholder="'+em_event_meta_box_object.price_text+'" name="ep_additional_ticket_fee['+next_row_len+'][price]" min="0.00" step="0.01" value="'+data.price+'">';
                        additional_fee_row += '</div>';
                        additional_fee_row += '<div class="ep-additional-fee ep-box-col-2 ep-mt-3 ep-d-flex ep-items-end ep-pb-2">';
                            additional_fee_row += '<a class="ep-delete-additional-ticket-fee-row" data-parent_id="'+row_id+'">Delete</span>';
                        additional_fee_row += '</div>';
                    additional_fee_row += '</div>';
                    $( '#ep_additional_ticket_fee_wrapper' ).append( additional_fee_row );
                    next_row_len++;
                });
            }
            // allow cancellation
            if( ticket_row_data.allow_cancellation == 1 ) {
                $( '#ep_allow_cancellation' ).prop( 'checked', 'checked' );
            }
            // show remaining tickets
            if( ticket_row_data.show_remaining_tickets == 1 ) {
                $( '#ep_show_remaining_tickets' ).prop( 'checked', 'checked' );
            }
            // start booking
            let booking_starts = ticket_row_data.booking_starts;
            if( booking_starts ) {
                booking_starts = JSON.parse( booking_starts );
                $( '#ep_ticket_start_booking_type' ).val( booking_starts.em_ticket_start_booking_type );
                if( booking_starts.em_ticket_start_booking_type == 'custom_date' ) {
                    if( booking_starts.em_ticket_start_booking_date ) {
                        $( '#ep_ticket_start_booking_date' ).val( booking_starts.em_ticket_start_booking_date );
                    }
                    if( booking_starts.em_ticket_start_booking_time ) {
                        $( '#ep_ticket_start_booking_time' ).val( booking_starts.em_ticket_start_booking_time );
                    }
                } else if( booking_starts.em_ticket_start_booking_type == 'event_date' ) {
                    $( '#ep_ticket_start_booking_type' ).trigger( 'change' );
                    let event_option = booking_starts.em_ticket_start_booking_event_option;
                    if( event_option ) {
                        $( '#ep_ticket_start_booking_event_option' ).val( event_option );
                    }
                } else{
                    $( '#ep_ticket_start_booking_type' ).trigger( 'change' );
                    let start_days = booking_starts.em_ticket_start_booking_days;
                    if( start_days ) {
                        $( '#ep_ticket_start_booking_days' ).val( start_days );
                    }
                    let start_days_option = booking_starts.em_ticket_start_booking_days_option;
                    if( start_days_option ) {
                        $( '#ep_ticket_start_booking_days_option' ).val( start_days_option );
                    }
                    let event_option = booking_starts.em_ticket_start_booking_event_option;
                    if( event_option ) {
                        $( '#ep_ticket_start_booking_event_option' ).val( event_option );
                    }
                }
            }
            // end booking
            let booking_ends = ticket_row_data.booking_ends;
            if( booking_ends ) {
                booking_ends = JSON.parse( booking_ends );
                $( '#ep_ticket_ends_booking_type' ).val( booking_ends.em_ticket_ends_booking_type );
                if( booking_ends.em_ticket_ends_booking_type == 'custom_date' ) {
                    if( booking_ends.em_ticket_ends_booking_date ) {
                        $( '#ep_ticket_ends_booking_date' ).val( booking_ends.em_ticket_ends_booking_date );
                    }
                    if( booking_ends.em_ticket_ends_booking_time ) {
                        $( '#ep_ticket_ends_booking_time' ).val( booking_ends.em_ticket_ends_booking_time );
                    }
                } else if( booking_ends.em_ticket_ends_booking_type == 'event_date' ) {
                    $( '#ep_ticket_ends_booking_type' ).trigger( 'change' );
                    let event_end_option = booking_ends.em_ticket_ends_booking_event_option;
                    if( event_end_option ) {
                        $( '#ep_ticket_ends_booking_event_option' ).val( event_end_option );
                    }
                } else{
                    $( '#ep_ticket_ends_booking_type' ).trigger( 'change' );
                    let ends_days = booking_ends.em_ticket_ends_booking_days;
                    if( ends_days ) {
                        $( '#ep_ticket_ends_booking_days' ).val( ends_days );
                    }
                    let ends_days_option = booking_ends.em_ticket_ends_booking_days_option;
                    if( ends_days_option ) {
                        $( '#ep_ticket_ends_booking_days_option' ).val( ends_days_option );
                    }
                    let event_end_option = booking_ends.em_ticket_ends_booking_event_option;
                    if( event_end_option ) {
                        $( '#ep_ticket_ends_booking_event_option' ).val( event_end_option );
                    }
                }
            }

            // show sale start and ends on front
            if( ticket_row_data.show_ticket_booking_dates == 1 ) {
                $( '#ep_show_ticket_booking_dates' ).prop( 'checked', 'checked' );
            }
            // min and max tickets
            $( '#ep_min_ticket_no' ).val( ticket_row_data.min_ticket_no );
            $( '#ep_max_ticket_no' ).val( ticket_row_data.max_ticket_no );
            
            // individuals pending
            let visibility = ticket_row_data.visibility;
            if( visibility ) {
                visibility = JSON.parse( visibility );
                $( '#ep_tickets_user_visibility' ).val( visibility.em_tickets_user_visibility );
                if( visibility.em_tickets_user_visibility == 'user_roles' ) {
                    $( '#ep_tickets_user_visibility' ).trigger( 'change' );
                    let user_roles_val = visibility.em_ticket_visibility_user_roles;
                    if( user_roles_val ) {
                        $( '#ep_ticket_visibility_user_roles' ).val( user_roles_val );
                        $( '#ep_ticket_visibility_user_roles' ).trigger( 'change' );
                    }
                }
                
                let em_ticket_for_invalid_user = visibility.em_ticket_for_invalid_user;
                if( em_ticket_for_invalid_user ) {
                    $( '#ep_ticket_for_'+ em_ticket_for_invalid_user +'_user' ).prop( 'checked', 'checked' );
                }
                // time
                $( '#ep_tickets_visibility_time_restrictions' ).val( visibility.em_tickets_visibility_time_restrictions );
            }

            // offers
            let all_offers = ticket_row_data.offers;
            if( all_offers ) {
                all_offers = JSON.parse( all_offers );
                let next_offer_list_count = 1;
                $.each( all_offers, function( idx, data ) {
                    let offer_list_data = '';
                    let new_ticket_offer_data = JSON.stringify( data );
                    let new_offer_row_id = 'ep_ticket_offer_section'+next_offer_list_count;
                    let ep_offer_type = $( '#ep_ticket_offer_type option[value="'+ data.em_ticket_offer_type + '"' ).text();
                    offer_list_data += "<div class='ep-box-row ep-border ep-rounded ep-m-3 ep-bg-white ep-items-center ep-offer-list-class' id='"+new_offer_row_id+"' data-offer_row_data='"+new_ticket_offer_data+"'>";
                        offer_list_data += '<div class="ep-box-col-2 ep-p-2">';
                            offer_list_data += '<span class="material-icons ep-cursor-move text-muted" data-parent_id="'+new_offer_row_id+'">drag_indicator</span>';
                        offer_list_data += '</div>';
                        offer_list_data += '<div class="ep-box-col-4 ep-p-2">';
                            offer_list_data += '<span class="ep-offer-name">'+data.em_ticket_offer_name+'</span>';
                        offer_list_data += '</div>';
                        offer_list_data += '<div class="ep-box-col-4 ep-p-2">';
                            offer_list_data += '<span class="ep-offer-type">'+ ep_offer_type +'</span>';
                        offer_list_data += '</div>';
                        offer_list_data += '<div class="ep-box-col-1 ep-p-2"><a href="javascript:void(0)" class="ep-ticket-offer-edit ep-text-primary" data-parent_id="'+new_offer_row_id+'">Edit</a></div>';
                        offer_list_data += '<div class="ep-box-col-1"><a href="javascript:void(0)" class="ep-ticket-offer-delete ep-text-danger" data-parent_id="'+new_offer_row_id+'">Delete</a></div>';
                    offer_list_data += '</div>';

                    $( '#ep_existing_offers_list' ).append( offer_list_data );

                    let ep_event_ticket_offers = [];
                    ep_event_ticket_offers = $( '#ep_event_ticket_offers' ).val();
                    if( ep_event_ticket_offers ) {
                        ep_event_ticket_offers = JSON.parse( ep_event_ticket_offers );
                        let timer_length = ep_event_ticket_offers.length;
                        if( timer_length > 0 ) {
                            ep_event_ticket_offers.push( JSON.parse( new_ticket_offer_data ) );
                        } 
                    } else{
                        ep_event_ticket_offers = [ JSON.parse( new_ticket_offer_data ) ];
                    }
                    $( '#ep_event_ticket_offers' ).val( JSON.stringify( ep_event_ticket_offers ) );

                    $( '#ep_ticket_offers_wrapper').show();

                    next_offer_list_count++;
                });
            }

            // multiple offers
            $( '#em_multiple_offers_option' ).val( ticket_row_data.multiple_offers_option );

            // max discount
            $( '#em_multiple_offers_max_discount' ).val( ticket_row_data.multiple_offers_max_discount );

            // ticket dropdown
            if( ticket_row_data.ticket_template_id ){
                $( '#ep-event-ticket-template' ).val( ticket_row_data.ticket_template_id );
            }
            
            // open popup
            let edit_modal = { title: name /* , row_id: parentid */ };
            $( '#ep_event_ticket_tier_modal' ).openPopup({
                anim: (!$(this).attr('data-animation') || $(this).data('animation') == null) ? 'ep-modal-' : $(this).data('animation')
            } , edit_modal );
        }
    });

    // recurrence event title options
    $( document ).on( 'click', '#ep_add_slug_in_event_title', function() {
        if( $( this ).prop( 'checked' ) == true ) {
            $( '.ep_recurrence_title_format_options' ).show( 500 );
        } else{
            $( '.ep_recurrence_title_format_options' ).hide( 500 );
            $( '.ep_recurrence_title_modifier' ).hide( 500 );
        }
    });

    // select recurrence title format option
    $( document ).on( 'change', '#ep_event_slug_type_options', function() {
        if( $( this ).val() != '' ) {
            $( '.ep_recurrence_title_modifier' ).show( 500 );
        } else{
            $( '.ep_recurrence_title_modifier' ).hide( 500 );
        }
    });

    // fire on upload offer icon button
    var file_frame;
    $( document ).on( 'click', '.upload_offer_icon_button', function( event ) {
        event.preventDefault();
        // If the media frame already exists, reopen it.
        if ( file_frame ) {
            file_frame.open();
            return;
        }
        // Create the media frame.
        file_frame = wp.media.frames.downloadable_file = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Use Image'
            },
            multiple: false
        });
        // When an image is selected, run a callback.
        file_frame.on( 'select', function() {
            attachments = file_frame.state().get('selection');
            attachment_ids = [];
            attachments.map(function (attachment) {
                attachment = attachment.toJSON();
                var imageObj = attachment.sizes.thumbnail === undefined ? {src: [attachment.sizes.full.url], id: attachment.id} : {src: [attachment.sizes.thumbnail.url], id: attachment.id};
                if( imageObj ) {
                    let imageSrc = imageObj.src[0];
                    let imageHtml = '<span class="ep-event-offer-icon ep-d-flex ep-mt-2"><i class="ep-remove-event-offer-icon dashicons dashicons-trash ep-cursor"></i><img src="'+imageSrc+'" data-image_id="'+attachment.id+'" width="50"></span>';
                    $( '#ep_event_ticket_icon_image' ).html(imageHtml);
                }
                // Pushing attachment ID in model
                attachment_ids.push(attachment.id);
            });
            $( '#ep_event_ticket_icon' ).val( attachment_ids );
        });
        // Finally, open the modal.
        file_frame.open();
    });

    // remove ticket image
    $( document ).on( 'click', '.ep-remove-event-offer-icon', function() {
        $( '#ep_event_ticket_icon_image' ).html( '' );
        $( '#ep_event_ticket_icon' ).val( '' );
    });

    // open the ticket modal action to blank the old inputs
    $( document ).on( 'click', '#ep_event_open_ticket_modal', function() {
        initiate_the_ticket_modal();
    });

    // open the ticket category modal action to blank the old inputs
    $( document ).on( 'click', '#ep_event_open_category_modal', function() {
        initiate_the_category_modal();
    });


    // blank the ticket modal inputs
    function initiate_the_ticket_modal(){
        // blank the inputs
        $( '#ep_event_ticket_tier_modal' ).find( '.ep-modal-title' ).html( em_event_meta_box_object.add_ticket_text );
        $( 'input[name=em_ticket_category_id]' ).val( '' );
        $( 'input[name=em_ticket_id]' ).val( '' );
        $( 'input[name=em_ticket_parent_div_id]' ).val( '' );

        $( 'input[name="name"]' ).val( '' );
        $( 'textarea[name="description"]' ).val( '' );
        $( 'input[name="icon"]' ).val( '' );
        $( '#ep_event_ticket_icon_image' ).html( '' );
        $( 'input[name="capacity"]' ).val( '' );
        $( '#ep_ticket_remaining_capacity' ).text( '' );
        $( 'input[name="price"]' ).val( '' );
        $( '#ep_additional_ticket_fee_wrapper' ).html( '' );
        $( 'input[name="allow_cancellation"]' ).prop( 'checked', false );
        $( 'input[name="show_remaining_tickets"]' ).prop( 'checked', false );
        //start booking date
        $( 'select[name="em_ticket_start_booking_date"] option:first' ).attr( 'selected', 'selected' );
        $( 'select[name="em_ticket_start_booking_date"]' ).trigger( 'change' );
        $( 'input[name="em_ticket_start_booking_date"]' ).val( '' );
        $( 'input[name="em_ticket_start_booking_time"]' ).val( '' );
        $( 'select[name="em_ticket_start_booking_event_option"] option:first' ).attr( 'selected', 'selected' );
        $( 'input[name="em_ticket_start_booking_days"]' ).val( '' );
        $( 'select[name="em_ticket_start_booking_days_option"] option:first' ).attr( 'selected', 'selected' );
        $( 'select[name="em_ticket_start_booking_event_option"] option:first' ).attr( 'selected', 'selected' );
        // end booking date
        $( 'select[name="em_ticket_ends_booking_type"] option:first' ).attr( 'selected', 'selected' );
        $( 'select[name="em_ticket_ends_booking_type"]' ).trigger( 'change' );
        $( 'input[name="em_ticket_ends_booking_date"]' ).val( '' );
        $( 'input[name="em_ticket_ends_booking_time"]' ).val( '' );
        $( 'select[name="em_ticket_ends_booking_event_option"] option:first' ).attr( 'selected', 'selected' );
        $( 'input[name="em_ticket_ends_booking_days"]' ).val( '' );
        $( 'select[name="em_ticket_ends_booking_days_option"] option:first' ).attr( 'selected', 'selected' );
        $( 'select[name="em_ticket_ends_booking_event_option"] option:first' ).attr( 'selected', 'selected' );

        $( 'input[name="show_ticket_booking_dates"]' ).prop( 'checked', false );
        $( 'input[name="min_ticket_no"]' ).val( '' );
        $( 'input[name="max_ticket_no"]' ).val( '' );
        // visibility
        $( 'select[name="em_tickets_user_visibility"] option:first' ).attr( 'selected', 'selected' );
        $( 'select[name="em_tickets_user_visibility"]' ).trigger( 'change' );
        $( 'select[name="em_ticket_visibility_user_roles"] option:first' ).attr( 'selected', 'selected' );
        $( 'select[name="em_tickets_visibility_time_restrictions"] option:first' ).attr( 'selected', 'selected' );
        $( 'select[name="em_tickets_visibility_time_restrictions"]' ).trigger( 'change' );
        $( 'select[name="ep_ticket_start_visibility"] option:first' ).attr( 'selected', 'selected' );
        $( 'select[name="ep_ticket_start_visibility"]' ).trigger( 'change' );
        $( 'input[name="em_visibility_start_booking_date"]' ).val( '' );
        $( 'input[name="em_visibility_start_booking_time"]' ).val( '' );
        $( 'select[name="em_visibility_start_booking_event_option"] option:first' ).attr( 'selected', 'selected' );
        $( 'input[name="em_visibility_start_booking_days"]' ).val( '' );
        $( 'select[name="em_visibility_start_booking_days_option"] option:first' ).attr( 'selected', 'selected' );
        $( 'select[name="em_visibility_start_booking_event_option"] option:first' ).attr( 'selected', 'selected' );

        $( 'select[name="em_visibility_ends_booking_type"] option:first' ).attr( 'selected', 'selected' );
        $( 'select[name="em_visibility_ends_booking_type"]' ).trigger( 'change' );
        $( 'input[name="em_visibility_ends_booking_date"]' ).val( '' );
        $( 'input[name="em_visibility_ends_booking_time"]' ).val( '' );
        $( 'select[name="em_visibility_ends_booking_event_option"] option:first' ).attr( 'selected', 'selected' );
        $( 'input[name="em_visibility_ends_booking_days"]' ).val( '' );
        $( 'select[name="em_visibility_ends_booking_days_option"] option:first' ).attr( 'selected', 'selected' );
        $( 'select[name="em_visibility_ends_booking_event_option"] option:first' ).attr( 'selected', 'selected' );
        // offers
        $( 'input[name=offers]' ).val( '' );
        initiate_the_offer_section();
        $( '#ep_existing_offers_list' ).html( '' );

        $( '#ep_event_ticket_qty' ).removeAttr( 'max' );
    }

    // blank the category modal input
    function initiate_the_category_modal() {
        $( '#ep-ticket-category-modal' ).find( '.ep-modal-title' ).html( em_event_meta_box_object.add_ticket_category_text );
        $( '#ep-ticket-category-modal #ep_save_ticket_category' ).text( em_event_meta_box_object.add_text );
        $( '#ep_ticket_category_name' ).val( '' );
        $( '#ep_ticket_category_capacity' ).val( '' );
        $( '#ep-ticket-category-modal' ).find( 'button' ).removeAttr( 'data-edit_row_id' );
    }

    // generate live example for recurring event title
    $( document ).on( 'change', '#ep_event_slug_type_options', function() {
        let ep_event_slug_type_value = this.value;
        let ep_recurring_example_title = $('#title').val();
        ep_recurring_example_title = ep_recurring_example_title.replace(/\s/g, "-");
        let ep_recurring_example_start_date = $('#em_start_date').val();
        
        if( ep_event_slug_type_value == 'prefix'){
            $('.ep-recurring-event-example-title-date').text( ' For example: '+ ep_recurring_example_start_date +'-'+ ep_recurring_example_title );
            $('.ep-recurring-event-example-title-number').text( ' For example: 1'+'-'+ ep_recurring_example_title );
        }
        if( ep_event_slug_type_value == 'suffix'){
            $('.ep-recurring-event-example-title-date').text(  ' For example: '+ep_recurring_example_title +'-'+ep_recurring_example_start_date );
            $('.ep-recurring-event-example-title-number').text( ' For example: '+ep_recurring_example_title +'-'+'1' );
        }
    });

    // blank the ticket modal inputs
    function initiate_the_offer_section(){
        // offers
        $( 'input[name=em_ticket_offer_name]' ).val( '' );
        $( 'textarea[name=em_ticket_offer_description]').val( '' );
        $( 'input[name=em_ticket_show_offer_detail]' ).prop( 'checked', false );
        $( 'select[name="em_offer_start_booking_type"] option:first' ).attr( 'selected', 'selected' );
        $( 'select[name="em_offer_start_booking_type"]' ).trigger( 'change' );
        $( 'input[name=em_offer_start_booking_date]' ).val( '' );
        $( 'input[name=em_offer_start_booking_time]' ).val( '' );
        $( 'select[name="em_offer_ends_booking_type"] option:first' ).attr( 'selected', 'selected' );
        $( 'select[name="em_offer_ends_booking_type"]' ).trigger( 'change' );
        $( 'input[name=em_offer_ends_booking_date]' ).val( '' );
        $( 'input[name=em_offer_ends_booking_time]' ).val( '' );
        $( 'input[name=em_offer_start_booking_days]' ).val( '' );
        $( 'input[name=em_offer_ends_booking_days]' ).val( '' );
        $( 'select[name="em_ticket_offer_type"]' ).val( '' );
        $( 'select[name="em_ticket_offer_type"] option:first' ).attr( 'selected', 'selected' );
        $( 'select[name="em_ticket_offer_type"]' ).trigger( 'change' );
        $( 'select[name="em_ticket_offer_user_roles"]' ).val( '' );
        $( 'select[name="em_ticket_offer_seat_option"]' ).val( '' );
        $( 'select[name="em_ticket_offer_seat_option"] option:first' ).attr( 'selected', 'selected' );
        $( 'input[name=em_ticket_offer_seat_number]' ).val( '' );
        $( 'select[name="em_ticket_offer_discount_type"] option:first' ).attr( 'selected', 'selected' );
        $( 'input[name=em_ticket_offer_discount]' ).val( '' );
    }
    
    // show/hide panels
    $( document ).on( 'click', '.ep_event_checkout_fields_tabs li a', function(e) {
        e.preventDefault();
        let panelSrc = $( this ).data( 'src' );
        if( $( "#"+panelSrc ).length > 0 ) {
            $(this).removeClass( 'ep-tab-active' );
            $(this).closest( 'li a' ).addClass( 'ep-tab-active' );
            $( ".ep_event_checkout_fields_panel" ).hide();
            $( "#"+panelSrc ).show();
        }
    });

    // on click on the text color field
    $( document ).on( 'change', '#em_event_text_color_field', function() {
        let em_event_text_color_field = this.value;
        if( em_event_text_color_field ) {
            $( '#em_event_text_color' ).val( em_event_text_color_field );
        }
    });

    // first time on load
    $( '.ep_result_start_from_type_options' ).hide();
    let until_type_val_init = $( '#ep_result_start_from_type' ).val();
    $( '.ep_result_start_from_type_' + until_type_val_init ).show();
    // trigget on chage allow until type
    $( document ).on( 'change', '#ep_result_start_from_type', function() {
        $( '.ep_result_start_from_type_options' ).hide();
        let until_type_val = $( this ).val();
        $( '.ep_result_start_from_type_' + until_type_val ).show();
    });

    // edit booking options
    $( document ).on( 'click', '#ep_allow_edit_booking', function() {
        $( '#ep_edit_booking_date_options' ).hide();
        if ( $( this ).is( ':checked' ) ) {
            $( '#ep_edit_booking_date_options' ).show();
        }
    });

    // trigget on chage allow edit booking date type
    $( document ).on( 'change', '#ep_edit_booking_date_type', function() {
        $( '.ep_edit_booking_date_type_options' ).hide();
        let edit_booking_date_type_val = $( this ).val();
        $( '.ep_edit_booking_date_type_' + edit_booking_date_type_val ).show();
    });
    
    
});
