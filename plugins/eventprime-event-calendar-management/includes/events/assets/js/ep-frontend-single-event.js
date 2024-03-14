jQuery( function( $ ) {
    $( document ).ready(function() {
        $( 'body' ).addClass( 'ep-event-detail-template' );
        // hide default theme featured image and title
        if( $( '.ep-event-detail-template .wp-block-post-featured-image' ).length > 0 ) {
            $( '.ep-event-detail-template .wp-block-post-featured-image' ).hide();
        }
        if( $( '.ep-event-detail-template .wp-block-post-title' ).length > 0 ) {
            $( '.ep-event-detail-template .wp-block-post-title' ).hide();
        }
        if( $( '.ep-event-detail-template header.entry-header' ).length > 0 ){
            $( '.ep-event-detail-template header.entry-header' ).hide();
        }
        $( document ).on( 'click', '#ep_sl_venue_more', function() {
            $( '#venue_hidden_details' ).toggle( 500 );
            $(this).toggleClass('ep-arrow-active');
            if ($(this).text() === 'expand_more'){
                $(this).text('expand_less');
            }else{
                $(this).text('expand_more');
            }
        });
      
        let owl = $( '.ep-image-slider' );
        owl.owlCarousel({
            loop:false,
            margin:10,
            nav:true,
            navText: ["<div class='nav-button owl-prev'>‹</div>", "<div class='nav-button owl-next'>›</div>"],
            dots: false,
            responsive:{
                0:{
                    items:1
                },
                600:{
                    items:3
                },
                1000:{
                    items:5
                }
            }
        });

        /* if( $( '.ep-image-slider' ).length > 0 ) {
            $('.ep-image-slider').responsiveSlides({
                auto: false, 
                speed: 500, 
                timeout: 4000, 
                pager: true, 
                nav: true, 
                random: false, 
                pause: true, 
                pauseControls: true, 
                prevText: "", 
                nextText: "", 
                maxwidth: "", 
                //navContainer: ".ep-single-event-nav", 
                manualControls: "",
                //namespace: "ep-rslides"
            });
        } */
        
        // Map
        setTimeout( function(){
            if( eventprime.global_settings.gmap_api_key ) {
                ep_load_google_map( 'ep-event-venue-map' );
            }
        }, 1000);
        
        // show event calendar
        setTimeout( function() {
            var calendarEl = document.getElementById( 'ep_single_event_recurring_events' );
            if( calendarEl ) {
                // set column header format
                let column_header_format = 'long';
                let calendar_column_header_format = eventprime.global_settings.calendar_column_header_format;
                if( calendar_column_header_format == 'ddd' ) {
                    column_header_format = 'short';
                }
                // hide prev and next month rows
                let hide_calendar_rows = true;
                if(eventprime.global_settings.hide_calendar_rows == 1){
                    hide_calendar_rows = false;
                }
                // set day max events
                let day_max_events = eventprime.global_settings.show_max_event_on_calendar_date;
                if( !day_max_events ) {
                    day_max_events = 2;
                }
                var calendar = new FullCalendar.Calendar( calendarEl, {
                    headerToolbar: {
                        left: 'prevYear,prev,next,nextYear',
                        center: 'title',
                        right: 'today'
                    },
                    initialView: 'dayGridMonth',
                    navLinks: true,
                    dayMaxEvents: day_max_events,
                    editable: false,
                    events: em_front_event_object.em_event_data.cal_events,
                    locale: em_front_event_object.em_event_data.local,
                    firstDay: em_front_event_object.em_event_data.start_of_week,
                    dayHeaderFormat: { weekday: column_header_format },
                    showNonCurrentDates: hide_calendar_rows,
                    eventDidMount: function(info) {
                        if (info.event.extendedProps.hasOwnProperty('bg_color')) {
                            var epColorRgb = info.event.extendedProps.bg_color;
                            var avoid = "rgb";
                            var eprgbRemover = epColorRgb.replace(avoid, '');
                            var emColor_bg = eprgbRemover.substring(eprgbRemover.indexOf('(') + 1, eprgbRemover.indexOf(')'))
                            info.el.style.backgroundColor =  `rgba(${emColor_bg},0.25)`;
                            info.el.style.borderColor =  `rgba(${emColor_bg},1)`;
                        }
                        var textColor = '';
                        if (info.event.extendedProps.hasOwnProperty('type_text_color')) {
                            textColor = info.event.extendedProps.type_text_color;
                        }
                        if (info.event.extendedProps.hasOwnProperty('event_text_color')) {
                            textColor = info.event.extendedProps.event_text_color;
                        }
                        if(textColor){
                            var fc_time = info.el.querySelector('.fc-time');
                            if(fc_time){
                                fc_time.style.color = textColor;
                                if(em_calendar_data.hide_time_on_front_calendar == 1){
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
                        }
                        $( info.el ).append( info.event.extendedProps.popup_html );
                    },
                    eventMouseEnter: function( info ) {
                        let pop_block = info.el.querySelector('.ep_event_detail_popup');
                        pop_block.style.display = 'block';
                    },
                    eventMouseLeave: function(info){
                        let pop_block = info.el.querySelector('.ep_event_detail_popup');
                        pop_block.style.display = 'none';
                    },
                });
            
                calendar.render();
            }
        }, 2000);
        

        // event filter list toggle
        if( $( '#ep-event-view-selector' ).length > 0 ) {
            $( '#ep-event-view-selector, .ep-box-dropdown-overlay' ).click( function(){
                $( '.ep-event-views-content' ).slideToggle();
                $( '#ep-event-view-selector' ).toggleClass( 'ep-box-dropdown-open' );
            });
        }

        // decrement ticket quentity
        $( document ).on( 'click', '.ticket_minus', function() {
            let parent_id = $( this ).data( 'parent_id' );
            let qty = $( '#ep_event_ticket_qty_' + parent_id ).val();
            if( qty != 0 ) {
                --qty;
            }
            let max_allowed_qty = $( '#ep_event_ticket_qty_' + parent_id ).attr( 'max' );
            if( qty > max_allowed_qty ) {
                qty = max_allowed_qty;
            }
            $( '#ep_event_ticket_qty_' + parent_id ).val( qty );
            // update price
            ep_update_tickets_data( 'minus', parent_id );
        });

        // increment ticket quentity
        $( document ).on( 'click', '.ticket_plus', function() {
            let parent_id = $( this ).data( 'parent_id' );
            let qty = $( '#ep_event_ticket_qty_' + parent_id ).val();
            let max_allowed_qty = $( '#ep_event_ticket_qty_' + parent_id ).attr( 'max' );
            if( qty != max_allowed_qty ) {
                ++qty;
            }
            if( qty > max_allowed_qty ) {
                qty = max_allowed_qty;
            }
            $( '#ep_event_ticket_qty_' + parent_id ).val( qty );
            // update price
            ep_update_tickets_data( 'plus', parent_id );
        });

        // check for event gallery images
        if( $( '#ep_single_event_image_gallery' ).length > 0 ) {
            let slide_duration = eventprime.global_settings.event_detail_image_slider_duration;
            $('#ep_single_event_image_gallery').responsiveSlides({
                auto: eventprime.global_settings.event_detail_image_auto_scroll, 
                speed: 500, 
                timeout: ( slide_duration * 1000 ), 
                pager: true, 
                nav: true, 
                random: false, 
                pause: true, 
                pauseControls: true, 
                prevText: "", 
                nextText: "", 
                maxwidth: "", 
                navContainer: ".ep-single-event-nav", 
                manualControls: "",
                namespace: "ep-rslides"
            });
        }

        // ticket date scroller
        let ep_event_ticket_dates_length = $( '.ep-ticket-btn-radio .ep_event_ticket_date_option' ).length;
        if( ep_event_ticket_dates_length > 1 ) {
            let loaded_event_id = em_front_event_object.em_event_data.event.em_id;
            let ticket_elems = document.getElementById( 'ep_child_event_id_'+loaded_event_id );
            let tic_left_offset = ticket_elems.offsetLeft;
            // apply animation
            $( '.ep-ticket-btn-radio' ).animate({
                scrollLeft: tic_left_offset
            }, 2000);
            
            // Move Recurring data left right on click arrow icons

            const dateRightBtn = document.querySelector('.ep-move-right');
            const dateLeftBtn = document.querySelector('.ep-move-left');
            dateRightBtn.addEventListener("click", function (event) {
                const epTicketBox = document.querySelector('.ep-ticket-btn-radio');
                epTicketBox.scrollLeft += 15;
                event.preventDefault();
            });

            dateLeftBtn.addEventListener("click", function (event) {
                const epTicketBox = document.querySelector('.ep-ticket-btn-radio');
                epTicketBox.scrollLeft -= 15;
                event.preventDefault();
            });
            
            // Move Recurring data left right on mouseWheel
            const mouseWheel = document.querySelector('.ep-ticket-btn-radio');
            if (mouseWheel) {
                mouseWheel.addEventListener('wheel', function (e) {
                    const ticketBtnMove = 15;
                    if (e.deltaY > 0)
                        mouseWheel.scrollLeft += ticketBtnMove;
                    else
                        mouseWheel.scrollLeft -= ticketBtnMove;
                    e.preventDefault();
                });
            }
        }
        
        ///  Event Cover Image Setting
        var styleElement = $("<style>");
        // Set the text content to include dynamic variables in :root
        var styleContent = ":root {\n";
        if(eventprime.global_settings.event_detail_image_width){
           styleContent += "  --ep-imageWidht: " + eventprime.global_settings.event_detail_image_width + "px" +  ";\n";
        }   else{
            styleContent += "  --ep-imageWidht: " + 'auto' + ";\n"; 
        }
        
        if(eventprime.global_settings.event_detail_image_height){
            if(eventprime.global_settings.event_detail_image_height == 'custom'){
               styleContent += "  --ep-imageHeight: " + eventprime.global_settings.event_detail_image_height_custom + "px"+ ";\n";  
            } else{
                styleContent += "  --ep-imageHeight: " + eventprime.global_settings.event_detail_image_height + ";\n"; 
            }
        } else{
            styleContent += "  --ep-imageHeight: " + 'auto' + ";\n"; 
        }
        
        if(eventprime.global_settings.event_detail_image_height_custom){
            styleContent += "  --ep-image-custom-height: " + eventprime.global_settings.event_detail_image_height_custom + ";\n";
        }
        
        if(eventprime.global_settings.event_detail_image_align){
            styleContent += "  --ep-imageAlign: " + eventprime.global_settings.event_detail_image_align + ";\n";
        }else{
            styleContent += "  --ep-imageAlign: " + 'center' + ";\n";
        }
       
        styleContent += "}\n";
        styleElement.text(styleContent);

        // Append the <style> element to the <head> of the document
        $("head").append(styleElement);
        if(eventprime.global_settings.event_detail_image_align){
            jQuery('.ep-single-event-slide-container').addClass(`ep-text-${eventprime.global_settings.event_detail_image_align}`);
            jQuery('#ep_single_event_image').addClass(`ep-slide-align-${eventprime.global_settings.event_detail_image_align}`);
            if(eventprime.global_settings.event_detail_image_align !== "center"){
                jQuery('.ep-single-event-slide-container').removeClass(`ep-text-center`); 
            }
        }
        
        if(eventprime.global_settings.event_detail_image_width){
            jQuery('#ep_single_event_image_gallery').addClass(`ep-slide-image-100`);
        } else{
            jQuery('#ep_single_event_image_gallery').addClass(`ep-slide-image-auto`);  
        }
        //Ends
        
    });

    // load google map on single event page
    function ep_load_google_map( element_id ) {
        let map_element_id = document.getElementById( element_id );
        if( map_element_id ) {
            let address = $( '#' + element_id ).data( 'venue_address' );
            let lat = $( '#' + element_id ).data( 'venue_lat' );
            let lng = $( '#' + element_id ).data( 'venue_lng' );
            if( lat && lng ) {
                let zoom_level = $( '#' + element_id ).data( 'venue_zoom_level' );
                if( !zoom_level ) {
                    zoom_level = 16;
                }
                let coordinates = { lat: lat, lng: lng };
                var map = new google.maps.Map( map_element_id, {
                    center: coordinates,
                    zoom: zoom_level
                });
                const marker = new google.maps.Marker({
                    position: coordinates,
                    map: map,
                });
            }
        }
    }

    // update ticket price
    var volume_based_offer_data = [];var volumn = [];
    /* let volume_based_offers_len = $( '#ep_single_modal_ticket_' + ticket_id + ' .em_ticket_volumn_based_offer' ).length;
    if( volume_based_offers_len > 0 ) {
        $( '#ep_single_modal_ticket_' + ticket_id + ' .em_ticket_volumn_based_offer' ).each(function() {
            let offer_data = $( this ).data( 'offer_data' );
            if( offer_data ) {
                volume_based_offer_data.push( JSON.parse( offer_data ) );
            }
        });
    } */

    function ep_update_tickets_data( action, ticket_id ) {
        if( ticket_id ) {
            booking_ticket_options_data = 0;
            $( '#ep_single_event_before_checkout_error_msg' ).html( '' );
            let row_ticket_price = $( '#ep_ticket_price_' + ticket_id ).data( 'row_ticket_price' );
            let qty = $( '#ep_event_ticket_qty_' + ticket_id ).val();
            let single_ticket_detail_id = 'single_ticket_detail' + ticket_id;
            let ep_added_ticket_option = $( '#ep_event_booking_ticket' ).attr( 'data-ticket_options' );
            if( !ep_added_ticket_option ) {
                ep_added_ticket_option = [];
            } else{
                ep_added_ticket_option = JSON.parse( ep_added_ticket_option );
            }
            let additional_fee_data = [], total_offer_discount_val = 0, total_offer_discount_text = '', offer_applied_text = '', offer_amount = 0;
            if( qty > 0 ) {
                let ticket_price = row_ticket_price * qty;
                let ticket_price_subtotal = parseFloat( ticket_price );
                let tickets_data = $( '#ep_single_modal_ticket_' + ticket_id ).data( 'ticket_data' );
                let offer_applied = get_applied_offer_data( ticket_id, qty );
                $('#ep_event_offer_data').val( JSON.stringify(offer_applied));
                if( offer_applied.length > 0 ) {
                    offer_applied_text = offer_applied.length;
                    if( offer_applied.length > 1 ) {
                        offer_applied_text += ' ' + em_front_event_object.em_event_data.multi_offfer_applied;
                    } else{
                        offer_applied_text += ' ' + em_front_event_object.em_event_data.one_offfer_applied;
                    }
                    // calculate offer amount
                    let tps = ticket_price_subtotal;
                    $.each( offer_applied, function( ofn, ofn_data ) {

                        if( ( ofn_data.em_ticket_offer_type == "role_based" ) && !( ofn_data.em_ticket_offer_user_roles.includes( $("input[name=ep_current_user_role]").val() ) ) ) {
                            offer_applied_text = ""; 
                            total_offer_discount_val = 0;
                            return;
                        }

                        let ep_apply_this_offer = 1;
                        let offer_ticket_qty = qty;
                        // check if ticket remaining for offer
                        let ep_ticket_single_offer_data = $( '#ep_single_ticket_offer_' + ticket_id + '_' + ofn_data.uid ).data( 'offer_data' );
                        if( ep_ticket_single_offer_data && ep_ticket_single_offer_data.em_remaining_ticket_to_offer && ep_ticket_single_offer_data.em_remaining_ticket_to_offer > 0 ) {
                            let em_remaining_ticket_to_offer = ep_ticket_single_offer_data.em_remaining_ticket_to_offer;
                            if( em_remaining_ticket_to_offer < qty ) {
                                offer_ticket_qty = em_remaining_ticket_to_offer;
                            }
                        }
                        let offer_ticket_subtotal = row_ticket_price * offer_ticket_qty;
                        offer_ticket_subtotal = parseFloat( offer_ticket_subtotal );
                        let discount_val = 0;
                        let discount_amount = ofn_data.em_ticket_offer_discount;
                        let discount_amount_type = ofn_data.em_ticket_offer_discount_type;
                        if( discount_amount_type == "percentage" ) {
                            discount_val = ( discount_amount/100 ) * offer_ticket_subtotal;
                            if( discount_val > 0 ) {
                                total_offer_discount_val += parseFloat( discount_val );
                                tps -= discount_val;
                            }
                        } else{
                            discount_val = discount_amount * qty;
                            if( discount_val > 0 ) {
                                total_offer_discount_val += parseFloat( discount_val );
                                tps -= discount_val;
                            }
                        }
                    });

                    if( tps < 0 ) {
                        tps = 0;
                    }

                    // check for max offer
                    let ticket_single_data = $( '#ep_single_modal_ticket_' + ticket_id ).data( 'ticket_data' );
                    let multiple_offers_max_discount = ticket_single_data.multiple_offers_max_discount;
                    if( multiple_offers_max_discount && multiple_offers_max_discount > 0 ) {
                        let max_discount_val = multiple_offers_max_discount;
                        if( total_offer_discount_val > max_discount_val ) {
                            //set the maximum discount
                            total_offer_discount_val = parseFloat( max_discount_val );
                            ticket_price_subtotal -= max_discount_val;
                            offer_applied_text = em_front_event_object.em_event_data.max_offer_applied;
                        } else{
                            ticket_price_subtotal = tps;
                        }
                    } else{
                        ticket_price_subtotal = tps;
                    }

                    if( total_offer_discount_val != 0 ) {
                        total_offer_discount_text = ' - ' + ep_format_price_with_position( total_offer_discount_val );
                    }
                }
                
                // format the ticket price
                let formated_price = ep_format_price_with_position( ticket_price );
                if( $( '#ep_event_booking_ticket #' + single_ticket_detail_id ).length > 0 ) {
                    $( '#ep_single_ticket'+ ticket_id +'_qty' ).html( qty );
                    $( '#ep_single_ticket'+ ticket_id +'_price' ).html( formated_price );
                    // offer applied text
                    $( '#ep_single_ticket'+ticket_id+'_offer_text' ).html( offer_applied_text );
                    // offer amount
                    $( '#ep_single_ticket'+ticket_id+'_offer_value' ).html( total_offer_discount_text );

                    // add additional fees
                    if( tickets_data.additional_fees ) {
                        $( JSON.parse( tickets_data.additional_fees ) ).each( function(ind, data ) {
                            let multi_add_fee = data.price * qty;
                            $( '#ep_additional_price_' + ticket_id + '_' + ind ).html( ep_format_price_with_position( multi_add_fee ) );
                            ticket_price_subtotal = parseFloat( ticket_price_subtotal ) + parseFloat( multi_add_fee );
                        });
                    }
                    $( '#ep_single_ticket'+ ticket_id +'_subtotal' ).html( ep_format_price_with_position( ticket_price_subtotal ) );
                    $( '#ep_single_ticket'+ ticket_id +'_subtotal' ).data( 'row_subtotal',  ticket_price_subtotal);
                } else{
                    let tic_data = '<div class="ep-box-row ep-text-small ep-my-2 ep-rounded ep-p-2 ep-single-modal-ticket-row" id="'+ single_ticket_detail_id +'">';
                        tic_data += '<div class="ep-box-col-6"> <span id="ep_single_ticket'+ticket_id+'_name">'+ tickets_data.name +'</span> x <span class="ep-text-muted" id="ep_single_ticket'+ticket_id+'_qty">'+ qty +'</span></div>';
                        tic_data += '<div class="ep-box-col-6 ep-text-end " id="ep_single_ticket'+ticket_id+'_price">'+ formated_price +'</div>';
                        // add additional fees
                        if( tickets_data.additional_fees ) {
                            $( JSON.parse( tickets_data.additional_fees ) ).each( function(ind, data ) {
                                tic_data += '<div class="ep-box-col-6 ep-text-small">'+ data.label +'</div>';
                                tic_data += '<div class="ep-box-col-6 ep-text-end ep-text-small" id="ep_additional_price_'+ticket_id+'_'+ind+'">'+ ep_format_price_with_position( data.price ) +'</div>';
                                ticket_price_subtotal = parseFloat( ticket_price_subtotal ) + parseFloat( data.price );
                                let add_fee = { label: data.label, price: data.price };
                                additional_fee_data.push( add_fee );
                            })
                        }
                        tic_data += '<div class="ep-box-col-6 ep-text-small"><em id="ep_single_ticket'+ticket_id+'_offer_text">' + offer_applied_text + '</em></div>';
                        tic_data += '<div class="ep-box-col-6 ep-text-end  ep-text-small" id="ep_single_ticket'+ticket_id+'_offer_value">' + total_offer_discount_text + '</div>';
                        tic_data += '<div class="ep-box-col-6 ep-fw-bold mt-2"> '+ em_front_event_object.em_event_data.subtotal_text +' </div>';
                        tic_data += '<div class="ep-box-col-6 ep-text-end ep-fw-bold mt-2 ep-ticket-subtotal-row" id="ep_single_ticket'+ticket_id+'_subtotal" data-row_subtotal="'+ ticket_price_subtotal +'">'+ ep_format_price_with_position( ticket_price_subtotal )+'</div>';
                    tic_data += '</div>';
                    $( '#ep_event_booking_ticket' ).append( tic_data );
                }
                // ticket variable
                let ticket_found = 0;
                $.each( ep_added_ticket_option, function( idx, ticket_option ) {
                    if( ticket_option.id == ticket_id ) {
                        ticket_found = 1;
                        ticket_option.qty = qty;
                        ticket_option.offer = total_offer_discount_val,
                        ticket_option.subtotal = ticket_price_subtotal;
                        ep_added_ticket_option[idx] = ticket_option;
                        return false;
                    }
                });
                if( ticket_found == 0 ) {
                    let ticket_var_data = {
                        id: ticket_id,
                        category_id: tickets_data.category_id,
                        name: tickets_data.name,
                        price: $( '#ep_ticket_price_' + ticket_id ).data( 'row_ticket_price' ),
                        qty: qty,
                        offer: total_offer_discount_val,
                        additional_fee: additional_fee_data,
                        subtotal: ticket_price_subtotal
                    }
                    ep_added_ticket_option.push( ticket_var_data );
                }
                booking_ticket_options_data = ep_added_ticket_option.length;
                $( '#ep_event_booking_ticket' ).attr( 'data-ticket_options', JSON.stringify( ep_added_ticket_option ) );
            } else{
                $( '#' + single_ticket_detail_id ).remove();
                // remove ticket option from data attr
                let ep_added_ticket_option = $( '#ep_event_booking_ticket' ).attr( 'data-ticket_options' );
                if( ep_added_ticket_option ) {
                    ep_added_ticket_option = JSON.parse( ep_added_ticket_option );
                    // check if this ticket exist
                    $.each( ep_added_ticket_option, function( idx, ticket_option ) {
                        if( ticket_option.id == ticket_id ) {
                            ep_added_ticket_option.splice( idx, 1 );
                            return false;
                        }
                    });
                    booking_ticket_options_data = ep_added_ticket_option.length;
                    $( '#ep_event_booking_ticket' ).attr( 'data-ticket_options', JSON.stringify( ep_added_ticket_option ) );
                }
            }
            
            if( booking_ticket_options_data > 0 ) {
                $( '#ep_single_event_checkout_btn' ).removeAttr( 'disabled' );
            } else{
                $( '#ep_single_event_checkout_btn' ).attr( 'disabled', 'disabled' );
            }

            calculate_tickets_total();
        }
    }

    // Calculate tickets total
    function calculate_tickets_total() {
        let ticket_total = 0;
        $( '#ep_event_booking_ticket .ep-ticket-subtotal-row' ).each( function() {
            let row_subtotal = $( this ).data( 'row_subtotal' );
            ticket_total = parseFloat( ticket_total ) + parseFloat( row_subtotal );
        });
        // check for fixed event price
        if( $( '#ep_event_fixed_price' ).length > 0 ) {
            let fixed_price = $( '#ep_event_fixed_price' ).data( 'fixed_price' );
            if( fixed_price && fixed_price > 0 ) {
                ticket_total = parseFloat( ticket_total ) + parseFloat( fixed_price );
            }
        }
        if( ticket_total > 0 ) {
            $( '#ep_ticket_price_total' ).html( ep_format_price_with_position( ticket_total ) );
        } else{
            if( eventprime.global_settings.hide_0_price_from_frontend == 1 ) {
                $( '#ep_ticket_price_total' ).html( em_front_event_object.em_event_data.free_text );
            } else{
                $( '#ep_ticket_price_total' ).html( ep_format_price_with_position( ticket_total ) );
            }
        }
        
    }

    // get applied offer data
    function get_applied_offer_data( ticket_id, qty ) {
        let applied_offer_data = [];
        if( ticket_id ) {
            let all_applied_offers_len = $( '#ep_single_modal_ticket_' + ticket_id + ' .ep-event-offer-applied').length;
            if( all_applied_offers_len > 0 ) {
                let all_event_tickets = em_front_event_object.em_event_data.event.all_tickets_data;
                if( all_event_tickets ) {
                    let offer_numbers = [];
                    $( '#ep_single_modal_ticket_' + ticket_id + ' .ep-event-offer-applied' ).each( function() {
                        let ofid = this.id;
                        if( ofid ) {
                            let ofidnum = ofid.split( 'ep_event_offer_' + ticket_id + '_' )[1];
                            if( ofidnum ) {
                                offer_numbers.push( parseInt( ofidnum ) );
                            }
                        }
                    });
                    if( offer_numbers.length > 0 ) {
                        let offer_done = 0;
                        $.each( all_event_tickets, function( ind, ticket_data) {
                            if( ticket_data.id == ticket_id ) {
                                if( ticket_data.offers ) {
                                    let ticket_offers = JSON.parse( ticket_data.offers );
                                    if( ticket_offers && ticket_offers.length > 0 ) {
                                        let multiple_offers_option = ticket_data.multiple_offers_option;
                                        $.each( ticket_offers, function( ofid, ofdata ) {
                                            let idnum = ofid;
                                            ++idnum;
                                            if( offer_numbers.indexOf( idnum ) > -1 ) {
                                                if( ofdata.em_ticket_offer_type == "volume_based" ) {
                                                    let em_ticket_offer_volumn_count = parseInt( ofdata.em_ticket_offer_volumn_count, 10 );
                                                    if( em_ticket_offer_volumn_count <= qty ) {
                                                        $( '#ep_event_offer_' + ticket_id + '_' + idnum ).show();
                                                        applied_offer_data.push( ofdata );
                                                    } else{
                                                        $( '#ep_event_offer_' + ticket_id + '_' + idnum ).hide();
                                                    }
                                                } else{
                                                    applied_offer_data.push( ofdata );
                                                }
                                                // if multiple offer option is first one then return after apply
                                                if( multiple_offers_option == 'first_offer' ) {
                                                    offer_done = 1;
                                                    return false;
                                                }
                                            }
                                        });
                                        if( offer_done == 1 ) {
                                            return false;
                                        }
                                    }
                                }
                            }
                        });
                    }
                }
            }
        }
        return applied_offer_data;
    }

    // reload signle page for upcoming date click
    $( document ).on( 'click', 'input[name="em_single_event_ticket_date"]', function() {
        let no_load = $( this ).data( 'no_load' );
        if( no_load === 'no-load' ) return false;
        
        let event_id = $( this ).data( 'event_id' );
        $('.ep-event-loader').show();
        let data = { 
            action  : 'ep_load_event_single_page', 
            security: em_front_event_object.em_event_data.single_event_nonce,
            event_id: event_id,
        };
        $.ajax({
            type        : "POST",
            url         : eventprime.ajaxurl,
            data        : data,
            success     : function( response ) {
                if( response.success == true ) {
                    let event_data = response.data;
                    if( event_data ) {
                        $( '#ep_single_event_detail_page_content' ).html( event_data );

                        // ticket date scroller
                        let ep_event_ticket_dates_length = $( '.ep-ticket-btn-radio .ep_event_ticket_date_option' ).length;
                        if( ep_event_ticket_dates_length > 1 ) {
                            let ticket_elems = document.getElementById( 'ep_child_event_id_' + event_id );
                            let tic_left_offset = ticket_elems.offsetLeft;
                            // apply animation
                            $( '.ep-ticket-btn-radio' ).animate({
                                scrollLeft: tic_left_offset
                            }, 2000);
                            
                            // Move Recurring data left right on click arrow icons

                            const dateRightBtn = document.querySelector('.ep-move-right');
                            const dateLeftBtn = document.querySelector('.ep-move-left');

                            dateRightBtn.addEventListener("click", function (event) {
                                const epTicketBox = document.querySelector('.ep-ticket-btn-radio');
                                epTicketBox.scrollLeft += 15;
                                event.preventDefault();
                            });

                            dateLeftBtn.addEventListener("click", function (event) {
                                const epTicketBox = document.querySelector('.ep-ticket-btn-radio');
                                epTicketBox.scrollLeft -= 15;
                                event.preventDefault();
                            });
                            
                            
                            // Move Recurring data left right on mouseWheel

                            const mouseWheel = document.querySelector('.ep-ticket-btn-radio');
                            if (mouseWheel) {
                                mouseWheel.addEventListener('wheel', function (e) {
                                    const ticketBtnMove = 15;

                                    if (e.deltaY > 0)
                                        mouseWheel.scrollLeft += ticketBtnMove;
                                    else
                                        mouseWheel.scrollLeft -= ticketBtnMove;
                                    e.preventDefault();
                                });
                            }
                            
                            
                        }
                    }
                    $('.ep-event-loader').hide();
                }
            }
        }); 
    });

    // load event html on date click
    /* function ep_load_event_on_date_click( event_data, load_ticket = false ) {
        // image url
        if( event_data.image_url ) {
            $( '#ep_single_event_image img' ).attr( 'src', event_data.image_url );
        }
        // event name
        $( '#ep_single_event_image' ).attr( 'alt', event_data.em_name );
        // event type
        if( event_data.event_type_details && event_data.event_type_details.name ) {
            $( '#ep_single_event_event_type' ).html( event_data.event_type_details.name );
        }
        // start date
        if( $( '#ep_single_event_start_date' ).length > 0 && event_data.fstart_date ) {
            $( '#ep_single_event_start_date' ).html( event_data.fstart_date );
        }
        // start time
        if( $( '#ep_single_event_start_time' ).length > 0 && event_data.em_start_time ) {
            $( '#ep_single_event_start_time' ).html( event_data.em_start_time );
        }
        // end date & time
        if( $( '#ep_single_event_end_date_time' ).length > 0 && event_data.fend_date ) {
            $( '#ep_single_event_end_date_time' ).html( event_data.fend_date + ', ' + event_data.em_end_time );
        }
        // start and end diff
        if( $( '#ep_single_event_start_end_diff' ).length > 0 && event_data.start_end_diff ) {
            $( '#ep_single_event_start_end_diff' ).html( event_data.start_end_diff );
        }
        // title
        $( '#ep_single_event_title' ).html( event_data.em_name );
        // venue address
        if( $( '#ep_single_event_venue_address' ).length > 0 && event_data.venue_details && event_data.venue_details.em_address ) {
            $( '#ep_single_event_venue_address' ).html( event_data.venue_details.em_address );
        }
        // organizers
        if( $( '#ep_single_event_organizers' ).length > 0 ) {
            if( event_data.organizer_details && event_data.organizer_details.length > 0 ) {
                let org_data = '';
                $.each( event_data.organizer_details, function( idx, org_data ) {
                    org_data += '<a href="'+org_data.organizer_url+'" target="_blank">';
                        org_data += '<span class="ep-text-small ep-mr-4">';
                            org_data += '<img src="'+org_data.image_url+'" alt="'+org_data.name+'" style="width:24px; height: auto;">';
                            org_data += '<span class="ep-align-middle">' + org_data.name + '</span>';
                        org_data += '</span>';
                    org_data += '</a>';
                });
                if( org_data ) {
                    $( '#ep_single_event_organizers' ).html( org_data );
                }
            }
        }
        // performers
        if( $( '#ep_single_event_performers' ).length > 0 ) {
            if( event_data.performer_details && event_data.performer_details.length > 0 ) {
                let performer_data = '';
                $.each( event_data.performer_details, function( idx, performer_data ) {
                    performer_data += '<div class="ep-event-performer ep-d-inline-flex ep-flex-column ep-py-2">';
                        performer_data += '<div class="ep-performer-pic-wrapper ep-mx-auto">';
                            performer_data += '<a href="'+performer_data.performer_url+'" target="_blank">';
                                performer_data += '<img class="ep-performer-pic" src="'+performer_data.image_url+'" width="96" height="96">';
                            performer_data += '</a>';
                        performer_data += '</div>';
                        performer_data += '<div class="ep-performer-content-wrapper ep-align-self-center ep-pt-2 ep-text-center ep-text-small">';
                            performer_data += '<div class="ep-performer-name ep-fw-bold ep-text-small ep-text-truncate">'
                                performer_data += '<a href="'+performer_data.performer_url+'" target="_blank">';
                                    performer_data += performer_data.name;
                                performer_data += '</a>';
                            performer_data += '</div>';
                        performer_data += '</div>';
                    performer_data += '</div>';
                });
                if( performer_data ) {
                    $( '#ep_single_event_performers' ).html( performer_data );
                }
            }
        }
        // description
        if( $( '#ep_single_event_description' ).length > 0 ) {
            $( '#ep_single_event_description' ).html( event_data.description );
        }
        // child_events check
        if( event_data.child_events ) {
            if( event_data.child_events.length == 0 ) {
                $( '#ep-more-dates' ).hide();
            } else{
                $( '#ep-more-dates' ).show();
            }
        }
        // price range
        let ticket_price_range = event_data.ticket_price_range;
        let price_html = '';
        // if multiple = 1, then show price range
        if( ticket_price_range ) {
            if( ticket_price_range.multiple == 1 ) {
                price_html += '<h6 class="ep-fs-6 ep-fw-bold">';
                    price_html += em_front_event_object.em_event_data.starting_from_text;
                    price_html += ' ' + ep_format_price_with_position( ticket_price_range.min );
                price_html += '</h6>';
                price_html += '<h6 class="ep-fs-6">' + ep_format_price_with_position( ticket_price_range.min ) + ' - ' + ep_format_price_with_position( ticket_price_range.max ) + '</h6>';
            } else if( ticket_price_range.multiple == 0 ){ // show price only
                price_html += '<h6 class="ep-fs-6">'+ ep_format_price_with_position( ticket_price_range.price ) +'</h6>';
            }

            // check for button
            $( '#ep_single_event_ticket_now_wrapper #ep_single_event_ticket_now_btn' ).show();
        }
        $( '#ep_single_event_ticket_price' ).html( price_html );
        // remove 'Get Ticket Now' button
        if( ticket_price_range.length == 0 ) {
            $( '#ep_single_event_ticket_now_wrapper #ep_single_event_ticket_now_btn' ).hide();
        }
        
        // all available offers list
        let offers_html = '';
        if( event_data.all_offers_data && event_data.all_offers_data.length > 0 ) {
            get_ticket_offer_date( event_data.all_offers_data, event_data );
        } else{
            offers_html += '<div class="ep-text-small ep-bg-warning ep-bg-opacity-10 ep-p-2 ep-text-danger ep-rounded ep-mt-3">'+em_front_event_object.em_event_data.no_offer_text+'</div>';
            $( '#ep_single_event_available_offers' ).html( offers_html );
        }
        
        // QR code
        if( event_data.qr_code ) {
            $( '#ep_single_event_qr_code' ).attr( 'src', event_data.qr_code );
        }

        // venue other events
        if( event_data.venue_other_events && event_data.venue_other_events.length > 0 ) {
            $( '#ep_event_venue_other_event_tab' ).show();
            let other_event_html = '';
            $.each( event_data.venue_other_events, function( idx, other_event_data ) {
                other_event_html += '<div class="ep-box-row ep-align-items-center ep-mb-2">';
                    other_event_html += '<div class="ep-box-col ep-ml-2 ep-mr-1">';
                        other_event_html += '<img class="ep-rounded-circle ep-sl-other-event-img" src="'+other_event_data.image_url+'" width="60px" height="60px">';
                    other_event_html += '</div>';
                    other_event_html += '<div class="ep-box-col-8">';
                        other_event_html += '<div class="ep-fw-bold ep-text-small">';
                            other_event_html += other_event_data.name;
                        other_event_html += '</div>';
                        other_event_html += '<div class="ep-text-small ep-text-muted ep-desc-truncate">';
                            other_event_html += other_event_data.description;
                        other_event_html += '</div>';
                    other_event_html += '</div>';
                    other_event_html += '<div class="ep-box-col"><input type="button" class="ep-btn ep-btn-outline-primary ep-btn-sm ep_load_other_events_tickets_data" data-event_id="'+other_event_data.id+'" value="'+em_front_event_object.em_event_data.book_ticket_text+'"></div>';
                other_event_html += '</div>';
            });
            $( '#ep-sl-other-events' ).html( other_event_html );
        } else{
            $( '#ep_event_venue_other_event_tab' ).hide();
        }

        // update venue detail
        if( event_data.venue_details ) {
            if( event_data.venue_details.image_url ) {
                $( '#ep_event_venue_main_image' ).attr( 'src', event_data.venue_details.image_url );
            }
            if( event_data.venue_details.venue_url ) {
                $( '#ep_event_venue_url' ).attr( 'href', event_data.venue_details.venue_url );
            }
            if( event_data.venue_details.em_address ) {
                $( '#ep_event_venue_address' ).html( event_data.venue_details.em_address );
            }
            if( event_data.venue_details.description ) {
                $( '#ep_event_venue_description' ).html( event_data.venue_details.description );
            }
        }
        // update venue attribute for map
        let venue_address = '', venue_lat = '', venue_lng = '', venue_zoom_level = '';
        if( event_data.venue_details ) {
            venue_address    = event_data.venue_details.em_address;
            venue_lat        = event_data.venue_details.em_lat;
            venue_lng        = event_data.venue_details.em_lng;
            venue_zoom_level = event_data.venue_details.em_zoom_level;
            $( '#ep-event-venue-map' ).data( 'venue_address', venue_address );
            $( '#ep-event-venue-map' ).data( 'venue_lat', parseFloat( venue_lat ) );
            $( '#ep-event-venue-map' ).data( 'venue_lng', parseFloat( venue_lng ) );
            $( '#ep-event-venue-map' ).data( 'venue_zoom_level', parseInt( venue_zoom_level, 10 ) );
            // load map
            ep_load_google_map( 'ep-event-venue-map' );
        } else{
            $( '#ep-event-venue-map' ).html( '' );
        }
        
        // ticket modal start
        // left side tickets section
        if( event_data.all_tickets_data && event_data.all_tickets_data.length > 0 ) {
            let modal_ticket_section = '';
            $.each( event_data.all_tickets_data, function(ind, tic_data ) {
                modal_ticket_section += "<div class='ep-box-row ep-mb-5' id='ep_single_modal_ticket_"+tic_data.id+"' data-ticket_id='"+tic_data.id+"' data-ticket_data='"+JSON.stringify( tic_data )+"'>";
                    modal_ticket_section += '<div class="ep-box-col-12 ep-fs-5 ep-fw-bold">'+tic_data.name+'</div>';
                    modal_ticket_section += '<div class="ep-box-col-12 ep-text-small">';
                        if( tic_data.category_id && tic_data.category_id != '0' ) {
                            modal_ticket_section += '<span class="material-icons-outlined ep-fs-6 ep-align-middle">folder</span>';
                            modal_ticket_section += get_ticket_category_name( event_data, tic_data.category_id );
                            modal_ticket_section += '<span class="border-end border-2 mx-2"></span>';
                        }
                        modal_ticket_section += '<span class="material-icons-outlined ep-fs-6 ep-align-middle">groups</span>'+ em_front_event_object.em_event_data.capacity_text + ':' +  tic_data.capacity;
                    modal_ticket_section += '</div>';
                    modal_ticket_section += '<div class="ep-box-col-12 ep-text-small ep-py-2">'+tic_data.description+'<a href="#">more</a></div>';
                    modal_ticket_section += '<div class="ep-box-col-12">';
                        modal_ticket_section += '<div class="ep-text-small ep-text-white ep-mt-2">';
                            modal_ticket_section += '<span class="ep-bg-danger ep-py-1 ep-px-2 ep-rounded-1 ep-text-smalll">' + tic_data.capacity + em_front_event_object.em_event_data.ticket_left_text + '</span>';
                        modal_ticket_section += '</div>';
                        modal_ticket_section += '<div class="my-3">';
                            modal_ticket_section += '<span class="ep-fs-5 ep-fw-bold" id="ep_ticket_price_'+tic_data.id+'" data-row_ticket_price="'+tic_data.price+'">' + ep_format_price_with_position( tic_data.price ) + '</span>';
                            modal_ticket_section += '<em class="ms-2 fw-normal ep-text-dark ep-text-small">2 Offers Applied</em>';
                        modal_ticket_section += '</div>';

                        // qty
                        modal_ticket_section += '<div class="ep-btn-group btn-group-sm ep-mb-2" role="group">';
                            modal_ticket_section += '<button type="button" class="ep-btn ep-btn-outline-dark ep-px-3 ticket_minus" data-parent_id="'+tic_data.id+'"> - </button>';
                            modal_ticket_section += '<input type="number" name="em_ticket_qty_'+tic_data.id+'" id="ep_event_ticket_qty_'+tic_data.id+'" class="ep-btn ep-btn-outline-dark ep-px-3" min="0" max="'+tic_data.capacity+'" value="0">';
                            modal_ticket_section += '<button type="button" class="ep-btn ep-btn-outline-dark ep-px-3 ticket_plus" data-parent_id="'+tic_data.id+'"> + </button>';
                        modal_ticket_section += '</div>';
                    modal_ticket_section += '</div>';

                    // allow cancellation
                    modal_ticket_section += '<div class="ep-box-col-12 ep-text-small ep-mt-2 ep-text-muted">';
                        if( tic_data.allow_cancellation == 1 ) { 
                            modal_ticket_section += '<div class="ep-text-small ep-d-inline-flex ep-mr-3">';
                                modal_ticket_section += '<span class="material-icons-outlined ep-fs-6 ep-align-top">task_alt</span>' + em_front_event_object.em_event_data.allow_cancel_text;
                            modal_ticket_section += '</div>';
                        }
                        // min and max
                        modal_ticket_section += '<div class="ep-text-small ep-d-inline-flex ep-mr-3 ep-align-items-center">';
                            modal_ticket_section += '<span class="material-icons-outlined ep-fs-6 ep-align-top">task_alt</span>'+ em_front_event_object.em_event_data.min_qty_text + ':' + tic_data.min_ticket_no;
                        modal_ticket_section += '</div>';
                        modal_ticket_section += '<div class="ep-text-small ep-d-inline-flex">';
                            modal_ticket_section += '<span class="material-icons-outlined ep-fs-6 ep-align-top">task_alt</span>'+ em_front_event_object.em_event_data.max_qty_text + ':' + tic_data.max_ticket_no;
                        modal_ticket_section += '</div>';

                    modal_ticket_section += '</div>';
                modal_ticket_section += '</div>';
            });

            if( modal_ticket_section ) {
                $( '#ep_event_ticket_modal_left' ).html( modal_ticket_section );
            }
        }

        // right side ticket section
        $( '#ep_event_ticket_modal_right_image img' ).attr( 'src', event_data.image_url );
        $( '#ep_event_ticket_modal_right_image img' ).attr( 'alt', event_data.name );
        // right side venue
        if( event_data.venue_details && event_data.venue_details.name ) {
            let venue_data = '<span class="material-icons-outlined ep-fs-6 ep-align-middle">place</span>';
            venue_data += event_data.venue_details.name;
            $( '#ep_event_ticket_modal_right_venue_name' ).html( venue_data );
        } else{
            $( '#ep_event_ticket_modal_right_venue_name' ).hide();
        }
        // right side date time
        if( event_data.fstart_date ) {
            let event_date_time_data = '<span class="material-icons-outlined ep-fs-6 ep-align-middle" style="position:relative; bottom: 3px;">event</span>';
            event_date_time_data += event_data.fstart_date;
            if( event_data.em_start_time ) {
                event_date_time_data += event_data.em_start_time;
            }
            $( '#ep_event_ticket_modal_right_date_time' ).html( event_date_time_data );
        } else{
            $( '#ep_event_ticket_modal_right_date_time' ).hide();
        }
        // flush old ticket data
        $( '#ep_event_booking_ticket' ).html( '' );

        if( $( '#ep_event_ticket_modal_right_fixed_fee' ).length > 0 ) {
            let fixed_price = $( '#ep_event_fixed_price' ).data( 'fixed_price' );
            if( fixed_price ) {
                $( '#ep_ticket_price_total' ).html( ep_format_price_with_position( fixed_price ) );
            }
        } else{
            let fixed_price_html = '';
            if( event_data.em_fixed_event_price && event_data.em_fixed_event_price > 0 ) {
                fixed_price_html = '<div class="ep-box-row" id="ep_event_ticket_modal_right_fixed_fee">';
                fixed_price_html += '<div class="ep-box-col-6">' + em_front_event_object.em_event_data.event_fees_text + '</div>';
                fixed_price_html += '<div class="ep-box-col-6 ep-text-end" id="ep_event_fixed_price" data-fixed_price="'+event_data.em_fixed_event_price+'">'+ep_format_price_with_position( event_data.em_fixed_event_price )+'</div>';
            }
            $( '#ep_event_ticket_modal_right_total' ).prepend ( fixed_price_html );
            $( '#ep_ticket_price_total' ).html( ep_format_price_with_position( event_data.em_fixed_event_price ) );
        }

        if( load_ticket && load_ticket == true ) {
            if( $( '#ep_single_event_ticket_now_btn' ).length > 0 ) {
                $( '#ep_single_event_ticket_now_btn' ).trigger( 'click' );
                $( '#ep-event-ticket-checkout-modal .ep-error-message' ).html( '' );
            }
        }
    } */

    $( document ).on( 'click', '#ep_single_event_ticket_now_btn', function() {
        $( '#ep-event-ticket-checkout-modal .ep-error-message' ).html( '' );
        $( '[ep-modal="ep_single_event_page_ticket_modal"]' ).fadeIn(100);
        $( 'body' ).addClass( 'ep-modal-open-body' );
        if($('.ticket_plus').length === 1){
            var qty_plus = $('#ep-event-ticket-checkout-modal').find('.ticket_plus').filter(':visible:first');
            if (qty_plus != null) {
                if($(qty_plus).parent().find('input').val() < 1){
                    qty_plus.click(); 
                }
            }
        }
    });

    //get ticket offer date
    /* function get_ticket_offer_date( offer_data, event_data ) {
        let data = { 
            action    : 'ep_load_event_offers_date', 
            security  : em_front_event_object.em_event_data.single_event_nonce,
            offer_data: JSON.stringify( offer_data ),
            event_data: JSON.stringify( event_data ),
        };
        $.ajax({
            type    : "POST",
            url     : eventprime.ajaxurl,
            data    : data,
            success : function( response ) {
                let res_data = [], offers_html = '';
                if( response.success == true ) {
                    if( response.data ) {
                        res_data = response.data;
                    }
                    let count = 1;
                    offers_html += '<div class="ep-text-small ep-bg-success ep-bg-opacity-10 ep-p-2 ep-text-success ep-rounded ep-mt-3">'+em_front_event_object.em_event_data.offer_applied_text+'</div>';
                    offers_html += '<div id="ep_single_event_offers_list">';
                        $.each( event_data.all_offers_data, function( idx, offer_data ) {
                            offers_html += '<div class="ep-my-2 ep-py-2 ep-text-small ep-event-offer-single" id="ep_event_offer_num'+count+'">';
                                offers_html += '<div class="ep-fw-bold ep-text-uppercase">';
                                    offers_html += '<span class="ep-fs-5 material-icons-outlined offer-icon ep-align-bottom ep-text-warning ep-mr-1">local_offer</span>';
                                    offers_html += offer_data.em_ticket_offer_name;
                                offers_html += '</div>';
                                offers_html += '<div class="ep-offer-desc">' + offer_data.em_ticket_offer_description + '</div>';
                                if( offer_data.uid in res_data ) {
                                    offers_html += '<div class="ep-text-small ep-text-muted">'+res_data[offer_data.uid]+'</div>';
                                }
                            offers_html += '</div>';
                            count++;
                        });
                    offers_html += '</div>';
                    $( '#ep_single_event_available_offers' ).html( offers_html );
                }
            }
        });
    } */

    // get ticket category name
    /* function get_ticket_category_name( event_data, category_id ) {
        let cat_name = '';
        if( event_data && category_id ) {
            let ticket_categories = event_data.ticket_categories;
            if( ticket_categories && ticket_categories.length > 0 ) {
                for( let t = 0; t < ticket_categories.length; t++ ) {
                    if( ticket_categories[t].id && ticket_categories[t].id == category_id ) {
                        cat_name = ticket_categories[t].name;
                        return false;
                    }
                }
            }
        }
        return cat_name;
    } */


    
    // go to checkout
    $( document ).on( 'click', '#ep_single_event_checkout_btn', function() {
        let ep_event_booking_ticket = $( '#ep_event_booking_ticket' ).attr( 'data-ticket_options' );
        if( ep_event_booking_ticket ) {
            // check for allowed quantity of tickets
            var ticket_error = 0;
            $.each( JSON.parse( ep_event_booking_ticket ), function( idx, tic_data ){
                let ticid = tic_data.id;
                $( '#em_ticket_qty_error_' + ticid ).html( ' ' );
                let min_allowed = $( '#ep_event_ticket_qty_' + ticid ).data( 'min_allowed' );
                if( tic_data.qty < min_allowed ) {
                    ticket_error = 1;
                    $( '#em_ticket_qty_error_' + ticid ).html( 'Minimum allowed quantity of this ticket is ' + min_allowed );
                    return false;
                }
            });
            if( ticket_error == 0 ) {
                let booking_event_id = $( '#ep_event_booking_event_id' ).val();
                let ep_event_offer_data = $( '#ep_event_offer_data' ).val();
                let booking_data = {
                    ticket: ep_event_booking_ticket,
                    event: booking_event_id,
                    ep_event_offer_data:ep_event_offer_data,
                };
                //console.log(booking_data)
                // check if rsvp booking data saved in the session
                let ep_event_rsvp_booking_data = sessionStorage.getItem( 'ep_event_rsvp_booking_data' );
                if( ep_event_rsvp_booking_data ) {
                    booking_data.rsvp_booking_data = ep_event_rsvp_booking_data
                }
                $( '#ep_event_booking_data' ).val( JSON.stringify( booking_data ) );

                $( '#ep_event_booking_form' ).submit();
            }
        } else{
            $( '#ep_single_event_before_checkout_error_msg' ).html( em_front_event_object.em_event_data.no_ticket_message );
        }
    });

    // show login option to show the tickets
    $( document ).on( 'click', '#ep_tickets_show_login', function() {
        $( '#ep_tickets_need_login' ).fadeIn( 500 );
    });

    // show more offers
    $( document ).on( 'click', '#ep_show_more_event_offers', function() {
        $( '#ep_single_event_offers_list .ep-event-offer-single' ).fadeIn( 500 );
        $( '#ep_show_more_event_offers' ).remove();
    });

    // load other events tickets data
    $( document ).on( 'click', '.ep_load_other_events_tickets_data', function() {
        let event_id = $( this ).data( 'event_id' );
        $('.ep-event-loader').show();
        let data = { 
            action  : 'ep_load_event_single_page', 
            security: em_front_event_object.em_event_data.single_event_nonce,
            event_id: event_id,
        };
        $.ajax({
            type        : "POST",
            url         : eventprime.ajaxurl,
            data        : data,
            success     : function( response ) {
                if( response.success == true ) {
                    let event_data = response.data;
                    //ep_load_event_on_date_click( event_data, true );
                    if( event_data ) {
                        $( '#ep_single_event_detail_page_content' ).html( event_data );

                        if( $( '#ep_single_event_ticket_now_btn' ).length > 0 ) {
                            $( '#ep_single_event_ticket_now_btn' ).trigger( 'click' );
                            $( '#ep-event-ticket-checkout-modal .ep-error-message' ).html( '' );
                        }
                    }
                    $('.ep-event-loader').hide();
                }
            }
        });
    });
    
    
    $(document).ready(function () {
        var maxLength = 300;
        $(".ep-ticket-description-text").each(function () {
            var myStr = $(this).text();
            if ($.trim(myStr).length > maxLength) {
                $(this).next('.ep-show-more').show();
                var newStr = myStr.substring(0, maxLength);
                var removedStr = myStr.substring(maxLength, $.trim(myStr).length);
                $(this).empty().html(newStr);
                $(this).append('<span class="ep-ticket-description-hide ep-item-hide">' + removedStr + '</span>')
            }
        });

        $(this).on('click', '.ep-show-more', function (event) {
            event.preventDefault();
            $(this).prev('.ep-ticket-description-text').find('.ep-ticket-description-hide').removeClass('ep-item-hide');
            $(this).hide();
            $(this).next('.ep-show-less').show();
        });

        $(this).on('click', '.ep-show-less', function (event) {
            event.preventDefault();
            $(this).prev().prev('.ep-ticket-description-text').find('.ep-ticket-description-hide').addClass('ep-item-hide');
            $(this).hide();
            $(this).prev('.ep-show-more').show();
        });
        
    });
    
    // show event more dates modal
    $( document ).on( 'click', '#ep_event_more_child_dates', function() {
        $( '[ep-modal="ep-get-other-date"]' ).fadeIn(100);
        $( 'body' ).addClass( 'ep-modal-open-body' );
    });

    // hide event more dates modal
    $( document ).on( 'click', '#ep_close_other_date_modal', function() {
        $( '[ep-modal="ep-get-other-date"]' ).fadeOut(100);
        $( 'body' ).removeClass( 'ep-modal-open-body' );
    });
    
    // close ticket modal
    $( document ).on( 'click', '#ep_event_close_ticket_modal', function() {
        $( '[ep-modal="ep_single_event_page_ticket_modal"]' ).fadeOut(100);
        $( 'body' ).removeClass( 'ep-modal-open-body' );
    });
    
    // ticket disabled section
    $( document ).on( 'click', '.ep-ticket-disabled', function() {
        $( '.ep-error-message' ).text( '' );
        let parent_id = $( this ).find( '.ep-ticket-disabled-action' ).data( 'parent_id' );
        let dis_reason = $( this ).find( '.ep-ticket-disabled-action' ).data( 'dis_reason' );
        if( parent_id ) {
            if( !dis_reason ) {
                dis_reason = 'user_login';
            }
            let error_msg = em_front_event_object.em_event_data.ticket_disable_login;
            if( dis_reason == 'user_role' ) {
                error_msg = em_front_event_object.em_event_data.ticket_disable_role;
            }
            $( '#em_ticket_qty_error_' + parent_id ).text( error_msg );
        }
    });
    
});