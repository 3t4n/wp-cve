jQuery( function( $ ) {
    
    $( document ).ready( function() {
        $( ".ep_event_options_panel:first-of-type" ).show();
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

        $( "#accordion" ).accordion({
            collapsible: true
        });

        $( '.ep-fes-multiselect' ).select2({
            theme: 'classic',
            placeholder: 'Select',
            //dropdownCssClass: 'ep-ui-show-on-top'
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
                newdate += '<label class="ep-form-label">' + em_event_fes_object.additional_date_text + '</label>';
                newdate += '<div class="ep-event-start-time">';
                    newdate += '<input type="text" name="em_event_add_more_dates['+next_row+'][date]" class="ep-form-control epDatePicker" autocomplete="off">';
                newdate += '</div>';
            newdate += '</div>';

            newdate += '<div class="ep-box-col-3 ep-meta-box-data">';
                newdate += '<label class="ep-form-label">' + em_event_fes_object.additional_time_text + ' ' + em_event_fes_object.optional_text + '</label>';
                newdate += '<div class="ep-event-start-time">';
                    newdate += '<input type="text" name="em_event_add_more_dates['+next_row+'][time]" class="ep-form-control epTimePicker" autocomplete="off">';
                newdate += '</div>';
            newdate += '</div>';

            newdate += '<div class="ep-box-col-3 ep-meta-box-data">';
                newdate += '<label class="ep-form-label">' + em_event_fes_object.additional_label_text + '</label>';
                newdate += '<div class="ep-event-start-time">';
                    newdate += '<input type="text" name="em_event_add_more_dates['+next_row+'][label]" placeholder="'+ em_event_fes_object.additional_label_text +'" class="ep-form-control ep-ad-event-label" autocomplete="off">';
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
            dateFormat: 'yy-mm-dd',
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
    $( document ).on( 'click', '.ep-org-add-more', function() {
        let dataInput = $( this ).data( 'input' );
        if( dataInput == 'phone' ) {
            let removeTitle = $( this ).data( 'remove_title' );
            let fieldHtml = '<div class="ep-input-btn-wrap ep-d-flex ep-org-phone ep-org-data-field ep-mt-2">';
            fieldHtml += '<input type="text" class="ep-org-data-input ep-form-input ep-input-text ep-form-control" name="em_organizer_phones[]" placeholder="Phone">';
            fieldHtml += '<button type="button" class="ep-org-remove ep-btn ep-btn-outline-danger ep-px-4 ep-ml-2y" data-input="phone" title="'+removeTitle+'">-</button>';
            fieldHtml += '</div>';
            $( ".ep-organizers-phone" ).append( fieldHtml );
        }

        if( dataInput == 'email' ) {
            let removeTitle = $( this ).data( 'remove_title' );
            let fieldHtml = '<div class="ep-input-btn-wrap ep-d-flex ep-org-email ep-org-data-field ep-mt-2">';
            fieldHtml += '<input type="email" class="ep-org-data-input ep-form-input ep-input-text ep-form-control" name="em_organizer_emails[]" placeholder="Email">';
            fieldHtml += '<button type="button" class="ep-org-remove ep-btn ep-btn-outline-danger ep-px-4 ep-ml-2" data-input="email" title="'+removeTitle+'">-</button>';
            fieldHtml += '</div>';
            $( ".ep-organizers-email" ).append( fieldHtml );
        }

        if( dataInput == 'website' ) {
            let removeTitle = $( this ).data( 'remove_title' );
            let fieldHtml = '<div class="ep-input-btn-wrap ep-d-flex ep-org-website ep-org-data-field ep-mt-2">';
            fieldHtml += '<input type="text" class="ep-org-data-input ep-form-input ep-input-text ep-form-control" name="em_organizer_websites[]" placeholder="Website">';
            fieldHtml += '<button type="button" class="ep-org-remove button ep-btn ep-btn-outline-danger ep-px-4 ep-ml-2" data-input="website" title="'+removeTitle+'">-</button>';
            fieldHtml += '</div>';
            $( ".ep-organizers-website" ).append( fieldHtml );
        }

        //datepicker
        $( '.epDatePicker' ).datepicker({
            changeYear: true,
            changeMonth: true,
            dateFormat: 'yy-mm-dd',
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
    });

    $( document ).on( 'click', '.ep-org-remove', function() {
        $( this ).closest( '.ep-org-data-field' ).remove();
    });

    // submit the form
    $( document ).on( 'click', '.ep-frontend-event-form-submit', function(e) {
        e.preventDefault();
        let submitBtn = $(this); 
        var required_fields = em_event_fes_object.event_required_fields;
        var validated = true;
        if($('#ep_name').val() === ''){
            show_toast( 'error', em_event_fes_object.event_name_error );
            return false;
        }
        var em_desc = get_tinymce_content('em_descriptions');
        if( "fes_event_description" in required_fields ) {
            if(em_desc === ''){
                show_toast( 'error', em_event_fes_object.event_desc_error );
                return false;
            }
        }
        $( '#em_descriptions' ).val( em_desc );

        if($('#em_start_date').val() === ''){
            show_toast( 'error', em_event_fes_object.event_start_date_error );
            return false;
        }
        if($('#em_end_date').val() === ''){
            show_toast( 'error', em_event_fes_object.event_end_date_error );
            return false;
        }
        if($("#ep-external-bookings").length) {
            if($('#ep-external-bookings').is(':checked')){
                if($("#ep_event_custom_link").length) {
                    var custom_link = $('#ep_event_custom_link').val();
                    if(custom_link === ''){
                        show_toast( 'error', em_event_fes_object.event_custom_link_error );
                        return false;
                    }else if(!is_valid_url(custom_link)){
                        show_toast( 'error', em_event_fes_object.event_custom_link_val_error );
                        return false;
                    }
                }
            }
        }
        
        if("fes_event_type" in required_fields){
            if($("#ep_event_type").length) {
                var ep_event_type = $('#ep_event_type').val();
                if(ep_event_type === ''){
                    show_toast( 'error', em_event_fes_object.event_type_error );
                    return false;
                }
                if(ep_event_type === 'new_event_type'){
                    if($("#ep_new_event_type_name").length) {
                        var ep_new_event_type_name = $('#ep_new_event_type_name').val();
                        if(ep_new_event_type_name ===''){
                            show_toast( 'error', em_event_fes_object.event_type_name_error );
                            return false;
                        }
                    }
                }
        }
        }
        
        if("fes_event_location" in required_fields){
            if($("#ep_venue").length) {
                var ep_venue = $('#ep_venue').val();
                if(ep_venue === ''){
                    show_toast( 'error', em_event_fes_object.event_venue_error );
                    return false;
                }
                if(ep_venue === 'new_venue'){
                    if($("#ep_new_venue").length) {
                        var ep_new_venue = $('#ep_new_venue').val();
                        if(ep_new_venue ===''){
                            show_toast( 'error', em_event_fes_object.event_venue_name_error );
                            return false;
                        }
                    }
                }
            }
        }
        
        if("fes_event_performer" in required_fields){
            if($("#ep_performer").length) {
                var ep_performer = $('#ep_performer').val();
                if(($("#ep_new_performer").length <=0) &&  ep_performer.length <= 0) {
                    show_toast( 'error', em_event_fes_object.event_performer_error );
                    return false; 
                }
                if($("#ep_new_performer").length) {
                    var ep_new_performer = $('#ep_new_performer').val();
                    if(ep_new_performer === '0'){
                        if(ep_performer.length <= 0){
                            show_toast( 'error', em_event_fes_object.event_performer_error );
                            return false; 
                        }
                    }else{
                        var ep_new_performer_name = $('#ep_new_performer_name').val();
                        if(ep_new_performer_name === ''){
                            show_toast( 'error', em_event_fes_object.event_performer_name_error );
                            return false;  
                        } 
                    }
                }
                
            }
        }
        
        if("fes_event_organizer" in required_fields){
            if($("#ep_organizer").length) {
                var ep_organizer = $('#ep_organizer').val();
                if(($("#ep_new_organizer").length <=0) &&  ep_organizer.length <= 0) {
                    show_toast( 'error', em_event_fes_object.event_organizer_error );
                    return false; 
                }
                if($("#ep_new_organizer").length) {
                    var ep_new_organizer = $('#ep_new_organizer').val();
                    if(ep_new_organizer === '0'){
                        if(ep_organizer.length <= 0){
                            show_toast( 'error', em_event_fes_object.event_organizer_error );
                            return false;  
                        }
                    }else{
                        var ep_new_organizer_name = $('#ep_new_organizer_name').val();
                        if(ep_new_organizer_name === ''){
                            show_toast( 'error', em_event_fes_object.event_organizer_name_error );
                            return false;  
                        } 
                    }
                }
                
            }
        }
        $('.ep-event-loader').show();
        submitBtn.prop('disabled', true);
        var form = $( "#ep_frontend_event_form" );
        let data = { 
            action: 'ep_save_frontend_event_submission', 
            data  : form.serialize(),
            security: em_event_fes_object.fes_nonce
        };
        $.ajax({
            type    : "POST",
            url     : eventprime.ajaxurl,
            data    : data,
            success : function( response ) {
                $('.ep-event-loader').hide();
                if( response.success == true ) {
                    show_toast( 'success', response.data.message );
                    setTimeout( function() {
                        location.reload();
                    }, 2000);
                } else{
                    show_toast( 'error', response.data.error );
                    submitBtn.prop('disabled', false);
                }
            }
        });
    });
    // add more option
    $( document ).on( 'click', '.ep-per-add-more', function() {
        let dataInput = $( this ).data( 'input' );
        let removeTitle = $( this ).data( 'remove_title' );
        let placeholder = $( this ).data( 'placeholder' );
        if( dataInput == 'phone' ) {
            let fieldHtml = '<div class="ep-input-btn-wrap ep-d-flex ep-per-data-field ep-mt-2">';
            fieldHtml += '<input type="tel" class="ep-per-data-input ep-form-input ep-input-text ep-form-control" name="em_performer_phones[]" placeholder="'+placeholder+'">';
            fieldHtml += '<button type="button" class="ep-per-remove ep-btn ep-btn-outline-danger ep-px-4 ep-ml-2" data-input="phone" title="'+removeTitle+'">-</button>';
            fieldHtml += '</div>';
            $( ".ep-performers-phone" ).append( fieldHtml );
        }

        if( dataInput == 'email' ) {
            let fieldHtml = '<div class="ep-input-btn-wrap ep-d-flex ep-per-email ep-per-data-field ep-mt-2">';
            fieldHtml += '<input type="email" class="ep-per-data-input ep-form-input ep-input-text ep-form-control" name="em_performer_emails[]" placeholder="'+placeholder+'">';
            fieldHtml += '<button type="button" class="ep-per-remove ep-btn ep-btn-outline-danger ep-px-4 ep-ml-2" data-input="email" title="'+removeTitle+'">-</button>';
            fieldHtml += '</div>';
            $( ".ep-performers-email" ).append( fieldHtml );
        }

        if( dataInput == 'website' ) {
            let fieldHtml = '<div class="ep-input-btn-wrap ep-d-flex ep-per-website ep-per-data-field ep-mt-2">';
            fieldHtml += '<input type="url" class="ep-per-data-input ep-form-input ep-input-text ep-form-control name="em_performer_websites[]" placeholder="'+placeholder+'">';
            fieldHtml += '<button type="button" class="ep-per-remove ep-btn ep-btn-outline-danger ep-px-4 ep-ml-2" data-input="website" title="'+removeTitle+'">-</button>';
            fieldHtml += '</div>';
            $( ".ep-performers-website" ).append( fieldHtml );
        }
    });
    // remove fields
    $( document ).on( 'click', '.ep-per-remove', function() {
        $( this ).closest( '.ep-per-data-field' ).remove();
    });
    
    // show/hide booking options
    $( document ).on( 'change', 'input[name=em_enable_booking]', function() {
        let booking_type = $( 'input[name=em_enable_booking]:checked' ).val();
        $( '#ep-bookings-options' ).hide();
        $( '#ep-bookings-url' ).hide();
        $( '#ep_existing_tickets_category_list' ).hide();
        if ( booking_type === 'bookings_on' ) {
            $( '#ep-bookings-options' ).fadeIn( 500 );
            $( '#ep_existing_tickets_category_list' ).fadeIn( 500 );
        } else if ( booking_type === 'external_bookings' ) {
            $( '#ep-bookings-url' ).fadeIn( 500 );
        }
    });

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
            $( '#' + parent_row_id + ' .ep-cat-capacity' ).text( em_event_fes_object.ticket_capacity_text + ': '+ cat_capacity );
            $( '#ep-ticket-category-modal' ).find( '.ep-modal-title' ).html( em_event_fes_object.add_ticket_category_text );
        } else{
            let cat_list_count = $( '#ep_existing_tickets_list .ep-cat-list-class' ).length;
            let next_cat_list_count = ++cat_list_count;
            let category_list_data = '';
            let new_cat_row_id = 'ep_ticket_cat_section'+next_cat_list_count;
            category_list_data += "<div class='ep-box-col-12 ep-p-3 ep-border ep-rounded ep-mb-3 ep-bg-white ep-shadow-sm ui-state-default ep-cat-list-class' id='"+new_cat_row_id+"' data-cat_row_data='"+new_cat_data+"'>";
                category_list_data += '<div class="ep-box-row ep-mb-3 ep-items-center">';
                    category_list_data += '<div class="ep-box-col-1"><span class="ep-ticket-cat-sort material-icons ep-cursor-move text-muted" data-parent_id="'+new_cat_row_id+'">drag_indicator</span></div>';
                    category_list_data += '<div class="ep-box-col-5"><h4 class="ep-cat-name">' + cat_name + '</h4></div>';
                    category_list_data += '<div class="ep-box-col-4"><h4 class="ep-cat-capacity">' + em_event_fes_object.ticket_capacity_text + ': '+ cat_capacity +'</h4></div>';
                    category_list_data += '<div class="ep-box-col-1"><span class="ep-ticket-cat-edit material-icons ep-text-muted" data-parent_id="'+new_cat_row_id+'">edit</span></div>';
                    category_list_data += '<div class="ep-box-col-1"><span class="ep-ticket-cat-delete material-icons ep-text-danger" data-parent_id="'+new_cat_row_id+'">delete</span></div>';
                category_list_data += '</div>';
                category_list_data += '<div class="ep-box-col-12 ep-p-3">';
                    category_list_data += '<button type="button" class="button button-large ep-m-3 ep-open-category-ticket-modal" data-id="ep-fes-event-ticket-modal" data-parent_id="'+new_cat_row_id+'">'+em_event_fes_object.add_ticket_text+'</button>';
                category_list_data += '</div>';
                category_list_data += '<div class="ep-ticket-category-section"></div>';
            category_list_data += '</div>';
            $( '#ep_existing_tickets_list' ).append( category_list_data );
        }
        // initiate the category modal
        initiate_the_category_modal();

        // close popup
        $( '.close-popup' ).trigger( 'click' );
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

            $( '#ep-fes-event-category-modal' ).find( '.ep-modal-title' ).html( em_event_fes_object.edit_text + ' ' + name );

            $( '#ep-fes-event-category-modal' ).find( 'button' ).attr( 'data-edit_row_id', parentid );

            $('[ep-modal="ep_fes_event_category_modal"]').fadeIn(200);
            $('body').addClass('ep-modal-open-body');

            // update the button
            $( '#ep-ticket-category-modal #ep_save_ticket_category' ).text( em_event_fes_object.update_text );
        }
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

        // initiate the category modal
        initiate_the_category_modal();
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

    // open category ticket modal
    $( document ).on( 'click', '.ep-open-category-ticket-modal', function() {
        initiate_the_ticket_modal();
        let parent_id = $( this ).data( 'parent_id' );
        if( parent_id ) {
            $( 'input[name=em_ticket_category_id]' ).val( parent_id );
        
            $('[ep-modal="ep_fes_event_ticket_modal"]').fadeIn(200);
            $('body').addClass('ep-modal-open-body');

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

    // add more additional fee row
    $( document ).on( 'click', '#add_more_additional_ticket_fee', function() {
        let ep_fee_row_len = $( '#ep_additional_ticket_fee_wrapper .ep-additional-ticket-fee-row' ).length;
        let next_row_len = ++ep_fee_row_len;
        let additional_fee_row = '';
        let row_id = 'ep_additional_ticket_fee_row'+next_row_len;
        additional_fee_row += '<div class="ep-additional-ticket-fee-row ep-box-row" id="'+row_id+'">';
            additional_fee_row += '<div class="ep-additional-fee ep-box-col-5 ep-mt-3">';
                additional_fee_row += '<input type="text" class="ep-form-control" id="ep_additional_ticket_fee_label'+next_row_len+'" placeholder="'+em_event_fes_object.additional_label_text+'" name="ep_additional_ticket_fee['+next_row_len+'][label]">';
            additional_fee_row += '</div>';
            additional_fee_row += '<div class="ep-additional-fee ep-box-col-5 ep-mt-3">';
                additional_fee_row += '<input type="number" class="ep-form-control" id="ep_additional_ticket_fee_price'+next_row_len+'" placeholder="'+em_event_fes_object.price_text+'" name="ep_additional_ticket_fee['+next_row_len+'][price]" min="0.00" step="0.01">';
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
    });

    // show/hide ticket ends dates
    $( document ).on( 'change', '.ep_ticket_ends_booking_type', function() {
        $( '.ep_ticket_ends_booking_options' ).hide();
        let ends_options = this.value;
        $( '.ep_ticket_ends_booking_' + ends_options ).show();
    });

    // save the ticket tier
    $( document ).on( 'click', '#ep_save_ticket_tier', function() {
        $('body').removeClass('ep-modal-open-body');
        $( '.ep-error-message' ).text( '' );
        var original_data = $( '#ep-fes-event-ticket-modal' ).find(
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

        let em_event_ticket_qty = tickets_data.get( 'capacity' );
        if( em_event_ticket_qty ) {
            // check for max quantity
            let max_qty = $( 'input[name=capacity]' ).attr( 'max' );
            if( max_qty ) {
                if( parseInt( em_event_ticket_qty, 10 ) > parseInt( max_qty, 10 ) ) {
                    $( '#ep_event_ticket_qty_error' ).html( em_event_fes_object.max_capacity_error + ' ' + max_qty );
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
        if( em_min_ticket_no ){
            ticket_tier_data.min_ticket_no = em_min_ticket_no;
        }
        // max ticket number
        let em_max_ticket_no = tickets_data.get( 'max_ticket_no' );
        if( em_max_ticket_no ) {
            if( parseInt(em_max_ticket_no) < parseInt(em_min_ticket_no) ) {
                $( '#ep_event_ticket_max_ticket_error' ).html( em_event_fes_object.max_less_then_min_error );
                document.getElementById( 'ep_max_ticket_no' ).focus();
                return false;
            }
            let capacity = $( 'input[name=capacity]' ).val();
            if( capacity ) {
                if( parseInt( em_max_ticket_no, 10 ) > parseInt( capacity, 10 ) ) {
                    $( '#ep_event_ticket_max_ticket_error' ).html( em_event_fes_object.max_capacity_error + ' ' + capacity );
                    document.getElementById( 'ep_max_ticket_no' ).focus();
                    return false;
                }
            }
            ticket_tier_data.max_ticket_no = em_max_ticket_no;
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
                    ticket_template += '<div class="ep-box-col-3 ep-p-3">';
                        if( em_event_ticket_price ){
                            ticket_template += '<span>'+ ep_format_price_with_position( em_event_ticket_price )+'</span>';
                        }
                    ticket_template += '</div>';
                    ticket_template += '<div class="ep-box-col-3 ep-p-3">';
                        if( em_ticket_category_id ) {
                            ticket_template += '<span>' + em_event_fes_object.ticket_capacity_text + ' ' + em_event_ticket_qty + '/' + cat_capacity + '</span>';
                        } else{
                            ticket_template += '<span>' + em_event_fes_object.ticket_capacity_text + ' ' + em_event_ticket_qty + '</span>';
                        }
                    ticket_template += '</div>';
                    ticket_template += '<div class="ep-box-col-1 ep-p-3">';
                        ticket_template += '<span class="ep-ticket-row-edit material-icons ep-text-muted ep-cursor" data-parent_id="'+template_id+'" data-parent_category_id="'+em_ticket_category_id+'">edit</span>';
                    ticket_template += '</div>';
                    ticket_template += '<div class="ep-box-col-1 ep-p-3">';
                        ticket_template += '<span class="ep-ticket-row-delete material-icons ep-text-danger ep-cursor" data-parent_id="'+template_id+'">delete</span>';
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
        $('[ep-modal="ep_fes_event_ticket_modal"]').fadeOut(200);
        $('body').removeClass('ep-modal-open-body');
        
    });

    // set event end date as start date if empty
    $( document ).on( 'change', '#em_start_date', function() {
        let st_val = this.value;
        let en_val = $( '#em_end_date' ).val();
        if( !en_val ) {
            $( '#em_end_date' ).val( st_val );
        }
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
                            additional_fee_row += '<a href="href="javascript:void(0"" class="ep-delete-additional-ticket-fee-row" data-parent_id="'+row_id+'">Delete</span>';
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
            
            // open popup
            /* let edit_modal = { title: name };
            $( '#ep_event_ticket_tier_modal' ).openPopup({
                anim: (!$(this).attr('data-animation') || $(this).data('animation') == null) ? 'ep-modal-' : $(this).data('animation')
            } , edit_modal ); */

            $('[ep-modal="ep_fes_event_ticket_modal"]').fadeIn(200);
            $('body').addClass('ep-modal-open-body');

        }
    });

    // blank the ticket modal inputs
    function initiate_the_ticket_modal(){
        // blank the inputs
        $( '#ep_event_ticket_tier_modal' ).find( '.ep-modal-title' ).html( em_event_fes_object.add_ticket_text );
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

        $( '#ep_event_ticket_qty' ).removeAttr( 'max' );
    }

    // blank the category modal input
    function initiate_the_category_modal() {
        $( '#ep-ticket-category-modal' ).find( '.ep-modal-title' ).html( em_event_fes_object.add_ticket_category_text );
        $( '#ep-ticket-category-modal #ep_save_ticket_category' ).text( em_event_fes_object.add_text );
        $( '#ep_ticket_category_name' ).val( '' );
        $( '#ep_ticket_category_capacity' ).val( '' );
        $( '#ep-ticket-category-modal' ).find( 'button' ).removeAttr( 'data-edit_row_id' );
    }

    // fire on upload image button
    var file_frame;
    $( document ).on( 'click', '.fes_upload_image_button', function( event ) {
        event.preventDefault();
        // If the media frame already exists, reopen it.
        if ( file_frame ) {
            file_frame.open();
            return;
        }
        // Create the media frame.
        file_frame = wp.media.frames.downloadable_file = wp.media({
            title: em_event_fes_object.choose_image_label,
            button: {
                text: em_event_fes_object.use_image_label
            },
            multiple: false
        });

        // When an image is selected, run a callback.
        file_frame.on( 'select', function() {
            var attachment           = file_frame.state().get( 'selection' ).first().toJSON();
            var attachment_thumbnail = attachment.sizes.thumbnail || attachment.sizes.full;
            jQuery( '#attachment_id' ).val( attachment.id );
            let imageHtml = '<div class="ep-edit-event-image">';
            imageHtml += '<img src="'+attachment_thumbnail.url+'" data-image_id="'+attachment.id+'" width="60">';
            imageHtml + '</div>';
            jQuery( '.ep-edit-event-image' ).html( imageHtml );
        });

        // Finally, open the modal.
        file_frame.open();
    });
});


function get_tinymce_content( editor_id ) {
    if ( jQuery( "#wp-"+editor_id+"-wrap" ).hasClass("tmce-active") ) {
        let editor_content = tinymce.get( editor_id ).getContent();
        if( !editor_content ) {
            editor_content = tinymce.activeEditor.getContent();
        }
        return editor_content;
    }else{
        return jQuery('#'+editor_id).val();
    }
}

function enabled_custom_link_toggle(element){
    if(jQuery(element).is(":checked")) {
        jQuery("#ep_custom_link_enabled_child").show(300);
        jQuery("#ep_custom_link").attr('required','required');
    } else {
        jQuery("#ep_custom_link_enabled_child").hide(200);
        jQuery("#ep_custom_link").removeAttr('required');
    }
}
function fes_event_sites_changed(element){
    if(jQuery(element).val() == 'new_venue'){
        jQuery('#ep_add_new_event_sites_child').show(300);
        jQuery("#ep_new_venue").attr('required','required');
        jQuery("#em-pac-input").attr('required','required');
        jQuery("#ep_seating_type").attr('required','required');
        setupMap();
    }else{
        jQuery('#ep_add_new_event_sites_child').hide(200);
        jQuery("#ep_new_venue").removeAttr('required');
        jQuery("#em-pac-input").removeAttr('required');
        jQuery("#ep_seating_type").removeAttr('required');
    }
}
function fes_event_type_changed(element){
    if(jQuery(element).val() == 'new_event_type'){
        jQuery('#ep_add_new_event_types_child').show(300);
        jQuery("#ep_new_event_type_name").attr('required','required');
        jQuery("#ep_new_event_type_background_color").attr('required','required');
        
    }else{
        jQuery('#ep_add_new_event_types_child').hide(200);
        jQuery("#ep_new_event_type_name").removeAttr('required');
        jQuery("#ep_new_event_type_background_color").removeAttr('required');
    }
}

function ep_venue_seating_change(element){
    if(jQuery(element).val() == 'standings'){
        jQuery('#ep_seating_type_child').show(300);
        jQuery('#ep_standing_capacity').attr('required','required');
    }else{
        jQuery('#ep_seating_type_child').hide(200);
        jQuery('#ep_standing_capacity').removeAttr('required');
    }
}
function fes_add_new_performer_show(element){
    jQuery('#ep_new_performer').val('1');
    jQuery('#ep-fes-add-event-perfomer-child').show(300);
    jQuery('#ep_new_performer_name').attr('required','required');
    jQuery(element).hide();
}
function fes_add_new_performer_hide(element){
    jQuery('#ep_new_performer').val('0');
    jQuery('#ep-fes-add-event-perfomer-child').hide(200);
    jQuery('#ep_new_performer_name').removeAttr('required');
    jQuery('#ep-fes-add-event-performer').show();
}
function fes_add_new_organizer_show(element){
    jQuery('#ep_new_organizer').val('1');
    jQuery('#ep-fes-add-event-organizer-child').show(300);
    jQuery('#ep_new_organizer_name').attr('required','required');
    jQuery(element).hide();
}
function fes_add_new_organizer_hide(element){
    jQuery('#ep_new_organizer').val('0');
    jQuery('#ep-fes-add-event-organizer-child').hide(300);
    jQuery('#ep_new_organizer_name').removeAttr('required');
    jQuery('#ep-fes-add-event-organizer').show();
}

function fes_age_group_changed(element){
    if(jQuery(element).val() == 'custom_group'){
        jQuery('#ep-type-admin-age-group-child').show(300);
        fes_load_age_slider();
    }else{
        jQuery('#ep-type-admin-age-group-child').hide(200);
        fes_load_age_slider();
    }
}
function fes_load_age_slider(){
    jQuery( "#ep-custom-group-range" ).slider({
        range: true,
        min: 0,
        max: 100,
        values: [ 18, 25 ],
        slide: function( event, ui ) {
            jQuery( "#ep-new_event_type_custom_group" ).val( ui.values[ 0 ] + " - " + ui.values[ 1 ] );
        }
    });
}
function setupMap() {
    var gmarkers = []; // To store all the markers
    // Initializing Map
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 8,
        center: {lat: 40.731, lng: -73.997}
    });
    /*var latEl = $("#em_venue_lat");
    var lngEl = $("#em_venue_long");*/
    var addressEl = jQuery("#em-pac-input");
    input = document.getElementById('em-pac-input'); //Searchbox
    var types = document.getElementById('type-selector');
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(types);
    /*if($scope.data.term.zoom_level && $scope.data.term.zoom_level !== ''){
        map.setZoom(parseInt($scope.data.term.zoom_level));
    }*/
    var geocoder = new google.maps.Geocoder;
    var infowindow = new google.maps.InfoWindow;
    var autocomplete = new google.maps.places.SearchBox(input);
    autocomplete.bindTo('bounds', map);
    var marker = new google.maps.Marker({
        map: map,
        anchorPoint: new google.maps.Point(0, -29)
    });
    if(input.value != ''){
        google.maps.event.trigger(autocomplete, 'places_changed');
    }

    // Listener for searchbox changes.
    autocomplete.addListener('places_changed', function () {
        //resetMarkers();
        var places = autocomplete.getPlaces();
        if(places.length == 0)
            return;
        var place = places[0];
        if (!place.geometry) {
            return;
        }
        // If the place has a geometry, then present it on a map.
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            //map.setZoom(8);  // Why 17? Because it looks good.
        }
        var marker = new google.maps.Marker({
            position: place.geometry.location,
            map: map
        });
        var address = '';
        if (place.address_components) {
            address = [
                (place.address_components[0] && place.address_components[0].short_name || ''),
                (place.address_components[1] && place.address_components[1].short_name || ''),
                (place.address_components[2] && place.address_components[2].short_name || '')
            ].join(' ');
        }
        infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
        infowindow.open(map,marker);
        gmarkers.push(marker);
        //updateLatLngInput(place.geometry.location.lat(),place.geometry.location.lng(),map.getZoom());
        updateLatLngInput(place, map);
    });
}

function updateLatLngInput(place, map){
    let lat = place.geometry.location.lat();
    let lang = place.geometry.location.lng();
    let zoom_level = map.getZoom();
    let state = '', country = '', postal_code = '';
    jQuery("#em_lat").val(lat);
    jQuery("#em_lng").val(lang);
    jQuery("#em_zoom_level").val(zoom_level);
    let address_components = place.address_components;
    for( let i = 0; i < address_components.length; i++ ){
        let atype = address_components[i].types;
        if( atype.indexOf('administrative_area_level_1') > -1 ){
            state = address_components[i].long_name;
            jQuery("#em_state").val(state);
        }
        if( atype.indexOf('country') > -1 ){
            country = address_components[i].long_name;
            jQuery("#em_country").val(country);
        }
        if( atype.indexOf('postal_code') > -1 ){
            postal_code = address_components[i].long_name;
            jQuery("#em_postal_code").val(postal_code);
        }
    }
}

function upload_file_media(element){
    var fd = new FormData();
    var file = jQuery(document).find('#'+element.id);
    var individual_file = file[0].files[0];
    fd.append("file", individual_file);
    fd.append('action', 'ep_upload_file_media');  
    
    jQuery.ajax({
        type: 'POST',
        url: eventprime.ajaxurl,
        data: fd,
        contentType: false,
        processData: false,
        success: function(response){
            jQuery(element).closest('div').find('.ep-hidden-attachment-id').val(response.data.attachment_id);
        }
    });
}