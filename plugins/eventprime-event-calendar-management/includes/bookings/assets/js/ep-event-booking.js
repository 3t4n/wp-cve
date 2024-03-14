jQuery( function( $ ) {
    var ep_currency = eventprime.global_settings.currency;
    if( !ep_currency ){
        ep_currency = 'USD';
    }
    var progress = 260, progress_bar = 2.6, progress_percentage = 100, progressInterval, progressBarInterval;
    if( eventprime.global_settings.checkout_page_timer && eventprime.global_settings.checkout_page_timer > 0 ) {
        progress = parseInt( eventprime.global_settings.checkout_page_timer, 10 ) * 60;
        progress_bar = progress * 10;
    }
    $( document ).ready( function() {
        if( $( '.ep-checkout-time' ).length > 0 ) {
            progressInterval = setInterval( decreaseCheckoutProgress, 1000 );
            progressBarInterval = setInterval( decreaseProgressBar, progress_bar );
        }
    });
    
    // checkout timer
    function decreaseCheckoutProgress() {
        progress = progress - 1;
        //$( '.ep-progress-bar' ).css( 'width', progress + "%" );
        $( '.ep-checkout-time' ).text( progress );
        
        if ( progress < 50 ) {
            $( '.ep-progress-bar' ).removeClass( 'bg-dark' );
            $( '.ep-progress-bar' ).addClass( 'bg-danger' );
        }

        if ( progress <= 0 ) {
            clearInterval( progressInterval );
            $( '.ep-event-loader' ).show();
            let data = { 
                action      : 'ep_booking_timer_complete', 
                security    : ep_event_booking.flush_booking_timer_nonce,
                booking_data: JSON.stringify( ep_event_booking.booking_data ),
            };

            $.ajax({
                type    : "POST",
                url     : eventprime.ajaxurl,
                data    : data,
                success : function( response ) {
                    if( response.success == true ) {
                        let booking_expired_html = '<div class="ep-box-row ep-mt-5 ep-mb-3">';
                            booking_expired_html += '<div class="ep-box-col-12 ep-px-0 ep-mt-3">';
                                booking_expired_html += '<span class="ep-text-small ep-bg-danger ep-bg-opacity-10 ep-p-2 ep-text-danger ep-rounded ep-mt-3">';
                                    booking_expired_html += ep_event_booking.booking_item_expired;
                                booking_expired_html += '</span>';
                            booking_expired_html += '</div>';
                        booking_expired_html += '</div>';
                        $( '#ep_event_checkout_page' ).html( booking_expired_html );
                        
                        setTimeout( function(){
                            let redirect_url = ep_event_booking.previous_event_url;
                            if( !redirect_url ) {
                                redirect_url = ep_event_booking.event_page_url;
                            }
                            location.href = redirect_url;
                        }, 2000 );
                    }
                }
            });
        }
    }
    // progress bar
    function decreaseProgressBar() {
        progress_percentage = progress_percentage - 1;
        $( '.ep-progress-bar' ).css( 'width', progress_percentage + "%" );
        
        if ( progress_percentage < 50 ) {
            $( '.ep-progress-bar' ).removeClass( 'bg-dark' );
            $( '.ep-progress-bar' ).addClass( 'bg-danger' );
        }

        if( progress_percentage <= 0 ) {
            clearInterval( progressBarInterval );
        }
    }

    sessionStorage.setItem( "allow_process_for_payment_step", 0 );
    // action on click on the checkout button
    $( document ).on( 'click', '#ep_event_booking_checkout_btn', function() {
        let active_step = $( this ).data( 'active_step' );
        var enabled_guest_booking = ep_event_booking.enabled_guest_booking;
        var enabled_woocommerce_integration = ep_event_booking.enabled_woocommerce_integration;
        var enabled_woocommerce_checkout = ep_event_booking.enabled_woocommerce_checkout;
        var ep_enabled_product = ep_event_booking.booking_data.event.em_enable_product;
        var ep_wc_products_count = 0;
        if( ep_event_booking.booking_data.event && ep_event_booking.booking_data.event.em_selectd_products ) {
            var ep_wc_products_count = ep_event_booking.booking_data.event.em_selectd_products.length;
        }
        var show_guest_booking_form = 0; var allow_process_for_payment_step = 0;
        if( active_step == 1 ) {
            var attendees_data = $( '#ep_event_booking_attendee_section' ).find(
                'input, select, textarea'
            );
            var error = 0;
            let requireString = get_translation_string( 'required' );
            let invalid_email_string = get_translation_string( 'invalid_email' );
            let invalid_phone_string = get_translation_string( 'invalid_phone' );
            let invalid_number_string = get_translation_string( 'invalid_number' );

            $( attendees_data ).each( function() {
                let input_name = $( this ).attr( 'name' );
                let attr_id = $( 'input[name="' + input_name + '"]' ).attr( 'id' );
                if( attr_id ) {
                    $( '#' + attr_id + '_error' ).text( '' );
                    if( $( '#' + attr_id ).attr( 'required' ) ) {
                        let input_val = $( '#' + attr_id ).val();
                        if( !input_val ) {
                            $( '#' + attr_id + '_error' ).text( requireString );
                            document.getElementById( attr_id ).focus();
                            error = 1;
                            return false;
                        }
                    }

                    // check for types
                    let type = $( '#' + attr_id ).attr( 'type' );
                    // check email type
                    if( type == 'email' ) {
                        let input_val = $( '#' + attr_id ).val();
                        if( input_val ) {
                            if( !is_valid_email( input_val ) ) {
                                $( '#' + attr_id + '_error' ).text( invalid_email_string );
                                document.getElementById( attr_id ).focus();
                                error = 1;
                                return false;
                            }
                        }
                    } else if( type == 'tel' ) { // check tel type
                        let input_val = $( '#' + attr_id ).val();
                        if( input_val ) {
                            if( !is_valid_phone( input_val ) ) {
                                $( '#' + attr_id + '_error' ).text( invalid_phone_string );
                                document.getElementById( attr_id ).focus();
                                error = 1;
                                return false;
                            }
                        }
                    } else if( type == 'number' ) { // check number type
                        let input_val = $( '#' + attr_id ).val();
                        if( input_val ) { 
                            if( isNaN( input_val ) ) {
                                $( '#' + attr_id + '_error' ).text( invalid_number_string );
                                document.getElementById( attr_id ).focus();
                                error = 1;
                                return false;
                            }
                        }
                    } else if( type == 'checkbox' || type == 'radio' ) { // check checkbox and radio type
                        $( '#' + attr_id + '_error' ).text( '' );
                        if( $( '#' + attr_id ).attr( 'required' ) ) {
                            let checkbox_checked_len = $( 'input[name="' + input_name + '"]:checked' ).length;
                            if( !checkbox_checked_len ) {
                                $( '#' + attr_id + '_error' ).text( requireString );
                                document.getElementById( attr_id ).focus();
                                error = 1;
                                return false;
                            }
                        }
                    }
                }

                // check for dropdown and textarea
                let attr_id_st = '';
                if( $( 'select[name="' + input_name + '"]' ).length > 0 ) {
                    attr_id_st = $( 'select[name="' + input_name + '"]' ).attr( 'id' );
                } else if( $( 'textarea[name="' + input_name + '"]' ).length > 0 ) {
                    attr_id_st = $( 'textarea[name="' + input_name + '"]' ).attr( 'id' );
                }
                if( attr_id_st ) {
                    $( '#' + attr_id_st + '_error' ).text( '' );
                    if( $( '#' + attr_id_st ).attr( 'required' ) ) {
                        let input_val = $( '#' + attr_id_st ).val();
                        if( !input_val ) {
                            $( '#' + attr_id_st + '_error' ).text( requireString );
                            document.getElementById( attr_id_st ).focus();
                            error = 1;
                            return false;
                        }
                    }
                }
            });

            if( error == 0 ) {
                let chkUserId = $( 'input[name=ep_event_booking_user_id]' ).val();

                // user registration
                if( chkUserId == 0 && enabled_guest_booking == 0 ) {
                    $( '#ep_event_booking_attendee_section' ).hide( 500 );
                    $( '#ep_event_booking_checkout_user_section' ).show( 500 );
                    $( '#ep-woocommerce-checkout-forms' ).hide();

                    let filled_user_registration_detail = 0;
                    $( '.ep-error-message' ).text( '' );
                    // check user name
                    let user_name_val = $( '#ep_event_checkout_rg_form_user_name' ).val();
                    if( user_name_val ) {
                        $( '.ep-event-loader' ).show();
                        let registration_data = { 
                            action: 'ep_rg_check_user_name', 
                            user_name: user_name_val,
                            security: ep_event_booking.event_registration_form_nonce
                        };
                        $.ajax({
                            type    : "POST",
                            url     : eventprime.ajaxurl,
                            data    : registration_data,
                            success : function( response ) {
                                $( '.ep-event-loader' ).hide();
                                if( response.success == false ) {
                                    $( '#ep_event_checkout_rg_form_user_name_error' ).text( response.data.error );
                                    document.getElementById( 'ep_event_checkout_rg_form_user_name' ).focus();
                                    error = 1;
                                    filled_user_registration_detail = 0;
                                    return false;
                                } else{
                                    filled_user_registration_detail = 1;
                                }
                            }
                        });
                    } else{
                        // check if first name entered
                        let rg_form_first_name = $( '#ep_event_checkout_rg_form_first_name' ).val();
                        if( !rg_form_first_name ) {
                            document.getElementById( 'ep_event_checkout_rg_form_first_name' ).focus();    
                        } else{
                            $( '#ep_event_checkout_rg_form_user_name_error' ).text( requireString );
                            document.getElementById( 'ep_event_checkout_rg_form_user_name' ).focus();
                        }
                        error = 1;
                        filled_user_registration_detail = 0;
                        return false;
                    }

                    // check email
                    let email_val = $( '#ep_event_checkout_rg_form_email' ).val();
                    if( email_val ) {
                        if( is_valid_email( email_val ) ) {
                            $( '.ep-event-loader' ).show();
                            let registration_data = { 
                                action: 'ep_rg_check_email', 
                                email: email_val,
                                security: ep_event_booking.event_registration_form_nonce
                            };
                            $.ajax({
                                type    : "POST",
                                url     : eventprime.ajaxurl,
                                data    : registration_data,
                                success : function( response ) {
                                    $( '.ep-event-loader' ).hide();
                                    if( response.success == false ) {
                                        $( '#ep_event_checkout_rg_form_email_error' ).text( response.data.error );
                                        document.getElementById( 'ep_event_checkout_rg_form_email' ).focus();
                                        error = 1;
                                        filled_user_registration_detail = 0;
                                        return false;
                                    } else{
                                        filled_user_registration_detail = 1;
                                    }
                                }
                            });
                        } else{
                            $( '#ep_event_checkout_rg_form_email_error' ).text( invalid_email_string );
                            document.getElementById( 'ep_event_checkout_rg_form_email' ).focus();
                            error = 1;
                            filled_user_registration_detail = 0;
                            return false;
                        }
                    } else{
                        $( '#ep_event_checkout_rg_form_email_error' ).text( requireString );
                        document.getElementById( 'ep_event_checkout_rg_form_email' ).focus();
                        error = 1;
                        filled_user_registration_detail = 0;
                        return false;
                    }

                    // check password
                    let password_val = $( '#ep_event_checkout_rg_form_password' ).val();
                    if( !password_val ) {
                        $( '#ep_event_checkout_rg_form_password_error' ).text( requireString );
                        document.getElementById( 'ep_event_checkout_rg_form_password' ).focus();
                        error = 1;
                        filled_user_registration_detail = 0;
                        return false;
                    } else{
                        filled_user_registration_detail = 1;
                    }
                    
                    if(ep_event_booking.enable_captcha_registration == 1){
                        if(grecaptcha && grecaptcha.getResponse().length == 0){
                            $( '#ep_event_checkout_rg_form_captcha_error' ).text( requireString );
                            error = 1;
                            filled_user_registration_detail = 0;
                        }else{
                            filled_user_registration_detail = 1;
                        }
                    }
                   /*  if( filled_user_registration_detail == 1 ) {
                        if( enabled_woocommerce_integration == 0 ) {
                            $( '#ep_event_booking_checkout_user_section' ).hide( 500 );
                            sessionStorage.setItem( "allow_process_for_payment_step", 1 );
                        } else{
                            if( $( '#ep-woocommerce-checkout-forms' ).length > 0 ) {
                                $( '#ep-woocommerce-checkout-forms' ).show( 500 );
                                let scrollToWoocommerce = $( '#ep-woocommerce-checkout-forms' );
                                $('html, body').stop().animate({
                                    'scrollTop': scrollToWoocommerce.offset().top
                                }, 800, 'swing' );
                            }
                        }
                    } */
                    $( '#ep-woocommerce-checkout-forms' ).hide();
                    if( filled_user_registration_detail == 1 ) {
                        if( enabled_woocommerce_integration == 1 && ep_enabled_product == 1 && ep_wc_products_count > 0 ) {
                            $( '#ep_event_checkout_registration_form' ).hide( 500 );
                            if( $( '#ep-woocommerce-checkout-forms' ).length > 0 ) {
                                $( '#ep-woocommerce-checkout-forms' ).show( 500 );
                                let scrollToWoocommerce = $( '#ep-woocommerce-checkout-forms' );
                                $('html, body').stop().animate({
                                    'scrollTop': scrollToWoocommerce.offset().top
                                }, 800, 'swing' );
                            }
                        } else{
                            $( '#ep_event_booking_checkout_user_section' ).hide( 500 );
                            sessionStorage.setItem( "allow_process_for_payment_step", 1 );
                        }
                    }
                    
                }

                // if guest booking check
                if( enabled_guest_booking == 1 && sessionStorage.getItem( "allow_process_for_payment_step" ) == 0 ) {
                    if( chkUserId == 0 ) {
                        if( $( '#ep_gb_booking_info_section' ).length > 0 ) {
                            $( '#ep_event_booking_attendee_section' ).hide( 500 );
                            $( '#ep_event_booking_checkout_user_section' ).show( 500 );
                            $( '#ep_event_booking_payment_section' ).hide();
                            if( enabled_woocommerce_integration == 1 && ep_enabled_product == 1 && ep_wc_products_count > 0 ) {
                                $( '#ep-woocommerce-checkout-forms' ).hide();
                                let woocommerce_integration_button = '<button type="button" class="ep-btn ep-btn-warning ep-box-w-100 ep-mb-2 step1" id="ep_woocommerce_integration_checkout_button" data-active_step="1">'+ep_event_booking.checkout_text+'</button>';
                                $( woocommerce_integration_button ).insertAfter( '#ep_event_booking_checkout_btn' );
                                $( '#ep_woocommerce_integration_checkout_button' ).hide();
                            }
                            sessionStorage.setItem( "allow_process_for_payment_step", 0 );
                            $( this ).hide();
                            let guest_button = '<button type="button" class="ep-btn ep-btn-warning ep-box-w-100 ep-mb-2 step1" id="ep_guest_booking_checkout_button" data-active_step="1">'+ep_event_booking.checkout_text+'</button>';
                            $( guest_button ).insertAfter( '#ep_event_booking_checkout_btn' );
                        } else{
                            sessionStorage.setItem( "allow_process_for_payment_step", 1 );
                        }
                    } else{
                        if( $( '#ep-woocommerce-checkout-forms' ).length > 0 ) {
                            sessionStorage.setItem( "allow_process_for_payment_step", 0 );
                            $( this ).hide();
                            $( '#ep_event_booking_attendee_section' ).hide( 500 );
                            $( '#ep_event_booking_checkout_user_section' ).show( 500 );
                            $( '#ep_event_booking_payment_section' ).hide();
                            $( '#ep-woocommerce-checkout-forms' ).show( 500 );
                            // $( '#ep_woocommerce_integration_checkout_button' ).show( 500 );
                            let woocommerce_integration_button = '<button type="button" class="ep-btn ep-btn-warning ep-box-w-100 ep-mb-2 step1" id="ep_woocommerce_integration_checkout_button" data-active_step="1">'+ep_event_booking.checkout_text+'</button>';
                            $( woocommerce_integration_button ).insertAfter( '#ep_event_booking_checkout_btn' );
                        } else{
                            sessionStorage.setItem( "allow_process_for_payment_step", 1 );
                        }
                    }
                } else{
                    if( chkUserId && chkUserId > 0 ) {
                        sessionStorage.setItem( "allow_process_for_payment_step", 1 );
                    }
                }

                // if woo checkout enabled then show the add to cart button
                if( enabled_woocommerce_checkout == 1 ){
                    if( $( '#ep_event_booking_cart_btn' ).length > 0 ) {
                        $( '#ep_event_booking_cart_btn' ).hide();
                    }
                }

                // woocommerce integration check
                if( enabled_woocommerce_integration == 1 && ep_enabled_product == 1 && ep_wc_products_count > 0 ){ 
                    if( enabled_guest_booking == 1 ){
                        if( chkUserId == 0 ) {
                            if( $( '#ep-woocommerce-checkout-forms' ).length > 0 ) {
                                $( '#ep_event_booking_attendee_section' ).hide( 500 );
                                $( '#ep_event_booking_checkout_user_section' ).show( 500 );
                                $( '#ep_event_booking_payment_section' ).hide();
                                sessionStorage.setItem( "allow_process_for_payment_step", 0 );
                                $( this ).hide();
                                // let woocommerce_integration_button = '<button type="button" class="ep-btn ep-btn-warning ep-box-w-100 ep-mb-2 step1" id="ep_woocommerce_integration_checkout_button" data-active_step="1">'+ep_event_booking.checkout_text+'</button>';
                                // $( woocommerce_integration_button ).insertAfter( '#ep_event_booking_checkout_btn' );
                            } else{
                                if( chkUserId ) {
                                    sessionStorage.setItem( "allow_process_for_payment_step", 1 );
                                }
                            }
                        } else{
                           
                            // sessionStorage.setItem( "allow_process_for_payment_step", 1 );
                            if( $( '#ep-woocommerce-checkout-forms' ).length > 0 ) {
                                sessionStorage.setItem( "allow_process_for_payment_step", 0 );
                                $( '#ep-woocommerce-checkout-forms' ).show( 500 );
                                $( '#ep_woocommerce_integration_checkout_button' ).show( 500 );
                            }
                        }
                    }else{
                        if( chkUserId == 0 && sessionStorage.getItem( "allow_process_for_payment_step" ) == 0 ) {
                            if( $( '#ep-woocommerce-checkout-forms' ).length > 0 ) {
                                $( '#ep_event_booking_attendee_section' ).hide( 500 );
                                $( '#ep_event_booking_checkout_user_section' ).show( 500 );
                                $( '#ep_event_booking_payment_section' ).hide();
                                sessionStorage.setItem( "allow_process_for_payment_step", 0 );
                                $( this ).hide();
                                let woocommerce_integration_button = '<button type="button" class="ep-btn ep-btn-warning ep-box-w-100 ep-mb-2 step1" id="ep_woocommerce_integration_checkout_button" data-active_step="1">'+ep_event_booking.checkout_text+'</button>';
                                $( woocommerce_integration_button ).insertAfter( '#ep_event_booking_checkout_btn' );
                            } else{
                                sessionStorage.setItem( "allow_process_for_payment_step", 1 );
                            }
                        } else if( chkUserId != 0 ) {
                            if( $( '#ep-woocommerce-checkout-forms' ).length > 0 ) {
                                $( '#ep_event_booking_attendee_section' ).hide( 500 );
                                $( '#ep_event_booking_checkout_user_section' ).show( 500 );
                                $( '#ep_event_booking_payment_section' ).hide();
                                sessionStorage.setItem( "allow_process_for_payment_step", 0 );
                                $( this ).hide();
                                let woocommerce_integration_button = '<button type="button" class="ep-btn ep-btn-warning ep-box-w-100 ep-mb-2 step1" id="ep_woocommerce_integration_checkout_button" data-active_step="1">'+ep_event_booking.checkout_text+'</button>';
                                $( woocommerce_integration_button ).insertAfter( '#ep_event_booking_checkout_btn' );
                            } else{
                                sessionStorage.setItem( "allow_process_for_payment_step", 1 );
                            }
                        } else{
                            sessionStorage.setItem( "allow_process_for_payment_step", 1 );
                        }
                    }
                }
                
                // if allow for payment process step
                if( sessionStorage.getItem( "allow_process_for_payment_step" ) == 1 ){
                    // update top icons
                    $( '#ep_booking_step1' ).removeClass( 'ep-bg-warning' );
                    $( '#ep_booking_step1' ).addClass( 'ep-bg-light' );
                    $( '#ep_booking_step2' ).removeClass( 'ep-bg-light' );
                    $( '#ep_booking_step2' ).addClass( 'ep-bg-warning' );
                    $( '#ep_booking_step1' ).html( 'done' );
                    $('#ep-booking-step-2').removeClass('ep-text-muted');
                    $('#ep-booking-step-2').addClass('ep-text-dark');
                    $( '#ep_event_booking_attendee_section' ).hide( 500 );
                    $( '#ep-woocommerce-checkout-forms' ).hide( 500 );
                    
                    loadPaymentSection();
                    if( chkUserId > 0 ) {
                        $( '#ep_event_booking_checkout_user_section' ).show( 500 );
                    } else{
                        $( '#ep_event_booking_checkout_user_section' ).hide( 500 );
                    }

                    $( this ).data( 'active_step', 2 );
                    if( $( this ).hasClass( 'step1' ) ) {
                        $( this ).removeClass( 'step1' );
                        $( this ).addClass( 'step2' );
                    }
                    let booking_price = $( 'input[name=ep_event_booking_total_price]' ).val();
                    if( parseFloat( booking_price, 10 )  > 0 ) {
                        $( this ).hide();
                    } else{
                        $( this ).show();
                        $( this ).html( ep_event_booking.confirm_booking_text );
                    }
                }
            }
        }
        else if( active_step == 2 ) {
            var form = $( "#ep_event_checkout_form" );
            let data = { 
                action: 'ep_save_event_booking', 
                data  : form.serialize(),
            };
            jQuery('.ep-event-loader').show();
            $.ajax({
                type    : "POST",
                url     : eventprime.ajaxurl,
                data    : data,
                success : function( response ) {
                    if( response.success == true ) {
                        if( response.data.payment_method == "paypal" ) {
                            let order_id      = response.data.order_id;
                            let booking_total = response.data.booking_total;
                            let item_total    = response.data.item_total;
                            if( $( '#ep_paypal_item_number' ).length > 0 ) {
                                $( '#ep_paypal_item_number' ).val( item_total );
                            }
                            if( $( '#ep_paypal_item_price' ).length > 0 ) {
                                $( '#ep_paypal_item_price' ).val( booking_total );
                            }
                            if( $( '#ep_paypal_order_id' ).length > 0 ) {
                                $( '#ep_paypal_order_id' ).val( order_id );
                            }
                            if( $( '#ep_paypal_order_return_url' ).length > 0 ) {
                                let return_url = $( '#ep_paypal_order_return_url' ).val();
                                return_url = return_url + '=' + order_id;
                                $( '#ep_paypal_order_return_url' ).val( return_url );
                            }
                            $( '#ep_paypal_payment_form' ).submit();
                        } else{
                            if( response.data.redirect ) {
                                location.href = response.data.redirect;
                            }
                        }
                    }else{
                        show_toast( 'error', response.data.error );
                    }
                    jQuery('.ep-event-loader').hide();
                }
            });
        }
    });

    // check for login modal
    $( document ).on( 'click', '#ep_checkout_login_modal_id', function() {
        $( 'input[name=redirect]' ).val( 'no-redirect' );
    });

    $( document ).on( 'afterEPLogin', function(e, response) {
        // get user id
        let userId = response.response.data.user.ID;
        // update user id value
        $( 'input[name=ep_event_booking_user_id]' ).val( userId );

        // hide user section
        //$( '#ep_event_booking_checkout_user_section' ).hide( 500 );
        //reload user section
        loadCheckoutUserSection( userId );

        // hide popup
        $( '[ep-modal="ep_checkout_login_modal"]' ).fadeOut(200);
        $( 'body' ).removeClass( 'ep-modal-open-body' );

        e.preventDefault();
    });

    // show/hide payment option
    $( document ).on('change', 'input[type=radio][name=payment_processor]', function() {
        if ( this.value == 'paypal' ) {
            $( '#ep-paypal-button-container' ).show(500);
        } else{
            $( '#ep-paypal-button-container' ).hide(500);  
        }
    });

    //Show Hide Attendees Section

    $(document).on('click', '.ep-event-booking-attendee .ep-event-booking-attendee-head', function () {
        $(this).parent().toggleClass('ep-attendee-box-close');
        $(this).siblings().slideToggle();
        $(this).toggleClass('ep-rounded-1');
        let toggleText = $(this).find('.ep-event-attendee-handler');
       
        if (toggleText.text() === 'expand_more'){
            toggleText.text('expand_less');
        } else{
           toggleText.text('expand_more');
        }
        return false;
    });
    
    $( document ).ready(function() {
        var booking_data = ep_event_booking.booking_data;
        sessionStorage.setItem('ep_booking_data', ep_event_booking.booking_data);
        sessionStorage.setItem('ep_booking_tickets', ep_event_booking.booking_data.tickets);

        if ( ep_event_booking.booking_data && ep_event_booking.booking_data.event && ep_event_booking.booking_data.event.em_fixed_event_price && ep_event_booking.booking_data.event.em_fixed_event_price > 0) {
            sessionStorage.setItem('ep_event_fee', ep_event_booking.booking_data.event.em_fixed_event_price);
        }else{
            sessionStorage.setItem('ep_event_fee', 0);
        }
        var coupon = {
            'code' : '',
            'discount_type' : '',
            'discount_amount' : btoa(0),
            'coupon_value' : ''
        };
        const couponArray = JSON.stringify(coupon);
        sessionStorage.setItem('ep_coupon', couponArray);
    });

    // set/unset the terms field
    $( document ).on( 'click', '#ep_booking_attendee_fixed_term_field', function() {
        if ( $( this ).is( ':checked' ) ) {
            $( this ).val( 1 );
        } else{
            $( this ).val( '' );
        }
    });

    // show/hide the seat info
    $( document ).on( 'click', '.ep-ls-expand-seat-info', function() {
        let seat_ticket_id = $( this ).data( 'ticket_id' );
        let expand_html = $( this ).html();
        if( expand_html == 'expand_less' ) {
            $( this ).html( 'expand_more' );
        } else{
            $( this ).html( 'expand_less' );
        }
        $( '#ep_single_ticket'+seat_ticket_id+'_seat_info' ).toggle( 500 );
    });

    // update booking action
    $( document ).on( 'click', '#ep_event_update_booking_button', function() {
        let booking_attendees_data = $( '#ep_event_booking_attendee_section' ).find(
            'input, select, textarea'
        );
        let error = 0;
        let requireString = get_translation_string( 'required' );
        let invalid_email_string = get_translation_string( 'invalid_email' );
        let invalid_phone_string = get_translation_string( 'invalid_phone' );
        let invalid_number_string = get_translation_string( 'invalid_number' );
        
        $( booking_attendees_data ).each( function() {
            let input_name = $( this ).attr( 'name' );
            let attr_id = $( 'input[name="' + input_name + '"]' ).attr( 'id' );
            if( attr_id ) {
                $( '#' + attr_id + '_error' ).text( '' );
                if( $( '#' + attr_id ).attr( 'required' ) ) {
                    let input_val = $( '#' + attr_id ).val();
                    if( !input_val ) {
                        $( '#' + attr_id + '_error' ).text( requireString );
                        document.getElementById( attr_id ).focus();
                        error = 1;
                        return false;
                    }
                }

                // check for types
                let type = $( '#' + attr_id ).attr( 'type' );
                // check email type
                if( type == 'email' ) {
                    let input_val = $( '#' + attr_id ).val();
                    if( input_val ) {
                        if( !is_valid_email( input_val ) ) {
                            $( '#' + attr_id + '_error' ).text( invalid_email_string );
                            document.getElementById( attr_id ).focus();
                            error = 1;
                            return false;
                        }
                    }
                } else if( type == 'tel' ) { // check tel type
                    let input_val = $( '#' + attr_id ).val();
                    if( input_val ) {
                        if( !is_valid_phone( input_val ) ) {
                            $( '#' + attr_id + '_error' ).text( invalid_phone_string );
                            document.getElementById( attr_id ).focus();
                            error = 1;
                            return false;
                        }
                    }
                } else if( type == 'number' ) { // check number type
                    let input_val = $( '#' + attr_id ).val();
                    if( input_val ) { 
                        if( isNaN( input_val ) ) {
                            $( '#' + attr_id + '_error' ).text( invalid_number_string );
                            document.getElementById( attr_id ).focus();
                            error = 1;
                            return false;
                        }
                    }
                } else if( type == 'checkbox' || type == 'radio' ) { // check checkbox and radio type
                    $( '#' + attr_id + '_error' ).text( '' );
                    if( $( '#' + attr_id ).attr( 'required' ) ) {
                        let checkbox_checked_len = $( 'input[name="' + input_name + '"]:checked' ).length;
                        if( !checkbox_checked_len ) {
                            $( '#' + attr_id + '_error' ).text( requireString );
                            document.getElementById( attr_id ).focus();
                            error = 1;
                            return false;
                        }
                    }
                }
            }

            // check for dropdown and textarea
            let attr_id_st = '';
            if( $( 'select[name="' + input_name + '"]' ).length > 0 ) {
                attr_id_st = $( 'select[name="' + input_name + '"]' ).attr( 'id' );
            } else if( $( 'textarea[name="' + input_name + '"]' ).length > 0 ) {
                attr_id_st = $( 'textarea[name="' + input_name + '"]' ).attr( 'id' );
            }
            if( attr_id_st ) {
                $( '#' + attr_id_st + '_error' ).text( '' );
                if( $( '#' + attr_id_st ).attr( 'required' ) ) {
                    let input_val = $( '#' + attr_id_st ).val();
                    if( !input_val ) {
                        $( '#' + attr_id_st + '_error' ).text( requireString );
                        document.getElementById( attr_id_st ).focus();
                        error = 1;
                        return false;
                    }
                }
            }
        });
        if( error == 0 ) {
            var form = $( "#ep_event_edit_booking_form" );
            let data = { 
                action: 'ep_update_event_booking_action', 
                data  : form.serialize(),
            };
            $('.ep-event-loader').show();
            $.ajax({
                type    : "POST",
                url     : eventprime.ajaxurl,
                data    : data,
                success : function( response ) {
                    if( response.success == true ) {
                        show_toast( 'success', response.data.message );
                        setTimeout( function() {
                            location.href = response.data.redirect_url;
                        }, 2000);
                    } else{
                        show_toast( 'error', response.data.error );
                    }
                    $('.ep-event-loader').hide();
                }
            });
        }
    });
    
});

// load payment section
function loadPaymentSection_old() {
    console.log(ep_event_booking)
    var ep_currency = eventprime.global_settings.currency;
    if( !ep_currency ){
        ep_currency = 'USD';
    }
    var booking_price = jQuery( 'input[name=ep_event_booking_total_price]' ).val();
    
    if( booking_price > 0 ) {
        jQuery( '#ep_event_booking_payment_section' ).show( 500 );
        if( !ep_event_booking.is_payment_method_enabled && booking_price > 0 ) {
            jQuery( '#ep_event_booking_checkout_btn' ).remove();
        } else{
            if( eventprime.global_settings.paypal_processor == 1 ) {
                let paypal_client_id = eventprime.global_settings.paypal_client_id;
                //let default_payment_processor = eventprime.global_settings.default_payment_processor;
                let default_payment_processor = ep_event_booking.default_payment_processor;
                if( paypal_client_id ) {
                    if( ep_event_booking.booking_data.tickets ) {
                        if( !default_payment_processor || default_payment_processor == 'undefined' || default_payment_processor == 'paypal_processor' ){
                            jQuery( '#paypal_payment' ).prop( 'checked', true );
                            jQuery( '#ep-paypal-button-container' ).show(500);
                        }
                        let booking_tickets = ep_event_booking.booking_data.tickets;
                        if( booking_tickets ) {
                            var total_price = 0;var total_discount = 0;
                            var items = [];
                            var random_order_id = Math.random().toString( 36 ).substring( 2, 7 );
                            jQuery.each( booking_tickets, function(idx, data ) {
                                let price = data.price;
                                //total_price = parseFloat( total_price ) + parseFloat( price );
                                if( data.additional_fee && data.additional_fee.length > 0 ) {
                                    jQuery.each( data.additional_fee, function( idx, add_data ) {
                                        let add_price = add_data.price;
                                        if( add_price > 0 ) {
                                            price = parseFloat( price ) + parseFloat( add_price );
                                            //total_price = parseFloat( total_price ) + parseFloat( price );
                                        }
                                    });
                                }
                                if( data.offer ) {
                                    total_discount = parseFloat( total_discount ) + parseFloat( data.offer );
                                }
                                let item_data = {
                                    "name": data.name,
                                    "description": data.name,
                                    "unit_amount": {
                                        "currency_code": ep_currency,
                                        "value": price
                                    },
                                    "discount": {
                                        "currency_code": ep_currency,
                                        "value": data.offer
                                    },
                                    "quantity": data.qty
                                }
                                items.push( item_data );
                            });
                            
                            if( ep_event_booking.booking_data.event.em_fixed_event_price && ep_event_booking.booking_data.event.em_fixed_event_price > 0 ) {
                                let item_data = {
                                    "name": "Event Fees",
                                    "description": "Event Fees",
                                    "unit_amount": {
                                        "currency_code": ep_currency,
                                        "value": ep_event_booking.booking_data.event.em_fixed_event_price
                                    },
                                    "quantity": 1
                                }
                                items.push( item_data );
                            }

                            if (sessionStorage.getItem("ep_booking_additional_price") !== null) {
                                const additional_price = sessionStorage.getItem('ep_booking_additional_price');
                                if( ep_event_booking.enabled_woocommerce_integration == 1 && additional_price != 'undefined' && ep_event_booking.booking_data.event.em_enable_product == 1 && ep_event_booking.booking_data.event.em_selectd_products.length > 0 ){
                                    let item_data = {
                                        "name": "Additional Prices",
                                        "description": "Aditional Prices",
                                        "unit_amount": {
                                            "currency_code": ep_currency,
                                            "value": additional_price
                                        },
                                        "quantity": 1
                                    }
                                    items.push( item_data );
                                }
                            }
                            
                            if (sessionStorage.getItem("ep_coupon") !== null) {
                                const coupon_data = sessionStorage.getItem('ep_coupon');
                                const couponArray = JSON.parse(coupon_data);
                                if(couponArray.code !== null){
                                    let ep_cc_dis = couponArray.discount_amount;
                                    if( ep_cc_dis ) {
                                        ep_cc_dis = atob( ep_cc_dis );
                                        if( ep_cc_dis > 0 ) {
                                            total_discount = parseFloat( total_discount ) + parseFloat( ep_cc_dis );
                                        }
                                    }
                                }
                            }
                            total_discount = total_discount.toFixed(2);
                            booking_price = parseFloat( booking_price ).toFixed(2);

                            var order_id = 0;
                            var checkout_form = jQuery( "#ep_event_checkout_form" );
                            jQuery('#ep-paypal-button-container').html('');
                            paypal.Buttons({
                                onClick: function() {
                                    let booking_data = { 
                                        action: 'ep_save_event_booking', 
                                        data  : checkout_form.serialize(),
                                        rid   : random_order_id,
                                        offer_data:JSON.stringify(ep_event_booking.booking_data.ep_event_offer_data)
                                    };
                                    //console.log( ep_event_booking.booking_data);
                                    jQuery.ajax({
                                        type    : "POST",
                                        url     : eventprime.ajaxurl,
                                        data    : booking_data,
                                        success : function( response ) {
                                            if( response.success == true ) {
                                                if( response.data.payment_method == "paypal" ) {
                                                    order_id      = response.data.order_id;
                                                    let booking_total = response.data.booking_total;
                                                    let item_total    = response.data.item_total;
                                                }
                                            }
                                        }
                                    });
                                },
                                // Set up the transaction
                                createOrder: function( data, actions ) {
                                    return actions.order.create({
                                        "purchase_units": [{
                                            "custom_id" : random_order_id,
                                            "amount": {
                                                "currency_code": ep_currency,
                                                "value": booking_price,
                                                "breakdown": {
                                                    "item_total": {
                                                        "currency_code": ep_currency,
                                                        "value": ( parseFloat( booking_price ) + parseFloat( total_discount ) )
                                                    },
                                                    "discount": {
                                                        "currency_code": ep_currency,
                                                        "value": total_discount
                                                    },
                                                }
                                            },
                                            "items": items
                                        }]
                                    });
                                },
                                // Finalize the transaction
                                onApprove: function( data, actions ) {
                                    actions.order.capture().then( function( orderData ) {
                                        jQuery('.ep-event-loader').show();
                                        paypalPaymentOnApprove( orderData, order_id );
                                    });
                                },
                                onError: function (err) {
                                    alert("There seems to be a problem. Please refresh the page and try again");
                                },
                            }).render('#ep-paypal-button-container');
                        }
                    }
                }
            }
            if( eventprime.global_settings.stripe_processor == 1 ) {
                if(sessionStorage.getItem( "allow_process_for_payment_step" )){
                    if(jQuery('#stripe_payment').is(':checked')){
                        initialize();
                    }
                }
                //initialize();
                /* jQuery('#stripe_payment').removeAttr("checked");
                jQuery('.ep-stripe-form').hide(); */
            }
        }
    }
}

function loadPaymentSection() {
    // console.log(ep_event_booking)
    var ep_currency = eventprime.global_settings.currency;
    if( !ep_currency ){
        ep_currency = 'USD';
    }
    var booking_price = jQuery( 'input[name=ep_event_booking_total_price]' ).val();
    
    if( booking_price > 0 ) {
        jQuery( '#ep_event_booking_payment_section' ).show( 500 );
        if( !ep_event_booking.is_payment_method_enabled && booking_price > 0 ) {
            jQuery( '#ep_event_booking_checkout_btn' ).remove();
        } else{
            if( eventprime.global_settings.paypal_processor == 1 ) {
                let paypal_client_id = eventprime.global_settings.paypal_client_id;
                //let default_payment_processor = eventprime.global_settings.default_payment_processor;
                let default_payment_processor = ep_event_booking.default_payment_processor;
                if( paypal_client_id ) {
                    if( ep_event_booking.booking_data.tickets ) {
                        if( !default_payment_processor || default_payment_processor == 'undefined' || default_payment_processor == 'paypal_processor' ){
                            jQuery( '#paypal_payment' ).prop( 'checked', true );
                            jQuery( '#ep-paypal-button-container' ).show(500);
                        }
                        let booking_tickets = ep_event_booking.booking_data.tickets;
                        if( booking_tickets ) {
                            var total_price = 0;var total_discount = 0;
                            var items = [];
                            var random_order_id = Math.random().toString( 36 ).substring( 2, 7 );
                            jQuery.each( booking_tickets, function(idx, data ) {
                                let price = data.price;
                                //total_price = parseFloat( total_price ) + parseFloat( price );
                                if( data.additional_fee && data.additional_fee.length > 0 ) {
                                    jQuery.each( data.additional_fee, function( idx, add_data ) {
                                        let add_price = add_data.price;
                                        if( add_price > 0 ) {
                                            price = parseFloat( price ) + parseFloat( add_price );
                                            //total_price = parseFloat( total_price ) + parseFloat( price );
                                        }
                                    });
                                }
                                if( data.offer ) {
                                    total_discount = parseFloat( total_discount ) + parseFloat( data.offer );
                                }
                                let item_data = {
                                    "name": data.name,
                                    "description": data.name,
                                    "unit_amount": {
                                        "currency_code": ep_currency,
                                        "value": price
                                    },
                                    "discount": {
                                        "currency_code": ep_currency,
                                        "value": data.offer
                                    },
                                    "quantity": data.qty
                                }
                                items.push( item_data );
                            });
                            
                            if( ep_event_booking.booking_data.event.em_fixed_event_price && ep_event_booking.booking_data.event.em_fixed_event_price > 0 ) {
                                let item_data = {
                                    "name": "Event Fees",
                                    "description": "Event Fees",
                                    "unit_amount": {
                                        "currency_code": ep_currency,
                                        "value": ep_event_booking.booking_data.event.em_fixed_event_price
                                    },
                                    "quantity": 1
                                }
                                items.push( item_data );
                            }

                            if (sessionStorage.getItem("ep_booking_additional_price") !== null) {
                                const additional_price = sessionStorage.getItem('ep_booking_additional_price');
                                if( ep_event_booking.enabled_woocommerce_integration == 1 && additional_price != 'undefined' && ep_event_booking.booking_data.event.em_enable_product == 1 && ep_event_booking.booking_data.event.em_selectd_products.length > 0 ){
                                    let item_data = {
                                        "name": "Additional Prices",
                                        "description": "Aditional Prices",
                                        "unit_amount": {
                                            "currency_code": ep_currency,
                                            "value": additional_price
                                        },
                                        "quantity": 1
                                    }
                                    items.push( item_data );
                                }
                            }
                            
                            if (sessionStorage.getItem("ep_coupon") !== null) {
                                const coupon_data = sessionStorage.getItem('ep_coupon');
                                const couponArray = JSON.parse(coupon_data);
                                if(couponArray.code !== null){
                                    let ep_cc_dis = couponArray.discount_amount;
                                    if( ep_cc_dis ) {
                                        ep_cc_dis = atob( ep_cc_dis );
                                        if( ep_cc_dis > 0 ) {
                                            total_discount = parseFloat( total_discount ) + parseFloat( ep_cc_dis );
                                        }
                                    }
                                }
                            }
                            total_discount = total_discount.toFixed(2);
                            booking_price = parseFloat( booking_price ).toFixed(2);

                            var order_id = 0;
                            var booking_total = 0;
                            var item_total = 0;
                            var discount_total = 0;
                            var checkout_form = jQuery( "#ep_event_checkout_form" );
                            jQuery('#ep-paypal-button-container').html('');
                            
                            paypal.Buttons({
                                
                                // Set up the transaction
                                createOrder: function (data, actions) {
                                    // Retrieve order details directly without relying on AJAX
                                    let booking_data = {
                                        action: 'ep_save_event_booking',
                                        data: checkout_form.serialize(),
                                        rid: random_order_id,
                                        offer_data: JSON.stringify(ep_event_booking.booking_data.ep_event_offer_data),
                                    };

                                    return jQuery.ajax({
                                        type: "POST",
                                        url: eventprime.ajaxurl,
                                        data: booking_data,
                                        success: function (response) {
                                            if (response.success == true && response.data.payment_method == "paypal") {
                                                order_id = response.data.order_id;
                                                booking_total = response.data.booking_total;
                                                item_total    = response.data.item_total;
                                                discount_total = response.data.discount_total;
                                            }
                                        },
                                    }).then(function () {
                                        // Return order details for PayPal
                                        return actions.order.create({
                                            purchase_units: [{
                                                custom_id: random_order_id,
                                                amount: {
                                                    currency_code: ep_currency,
                                                    value: booking_total,
                                                    breakdown: {
                                                        item_total: {
                                                            currency_code: ep_currency,
                                                            value: (parseFloat(booking_total) + parseFloat(discount_total)),
                                                        },
                                                        discount: {
                                                            currency_code: ep_currency,
                                                            value: discount_total,
                                                        },
                                                    },
                                                },
                                                items: items,
                                            }],
                                        });
                                    });
                                },
                                // Finalize the transaction
                                onApprove: function( data, actions ) {
                                    actions.order.capture().then( function( orderData ) {
                                        jQuery('.ep-event-loader').show();
                                        paypalPaymentOnApprove( orderData, order_id );
                                    });
                                },
                                onError: function (err) {
                                    alert("There seems to be a problem. Please refresh the page and try again");
                                },
                            }).render('#ep-paypal-button-container');
                        }
                    }
                }
            }
            if( eventprime.global_settings.stripe_processor == 1 ) {
                if(sessionStorage.getItem( "allow_process_for_payment_step" )){
                    if(jQuery('#stripe_payment').is(':checked')){
                        initialize();
                    }
                }
                //initialize();
                /* jQuery('#stripe_payment').removeAttr("checked");
                jQuery('.ep-stripe-form').hide(); */
            }
        }
    }
}

function loadPaymentSection_new() {
    console.log(ep_event_booking);
    var ep_currency = eventprime.global_settings.currency;

    if (!ep_currency) {
        ep_currency = 'USD';
    }

    var booking_price = jQuery('input[name=ep_event_booking_total_price]').val();

    if (booking_price > 0) {
        jQuery('#ep_event_booking_payment_section').show(500);

        if (!ep_event_booking.is_payment_method_enabled && booking_price > 0) {
            jQuery('#ep_event_booking_checkout_btn').remove();
        } else {
            if (eventprime.global_settings.paypal_processor == 1) {
                let paypal_client_id = eventprime.global_settings.paypal_client_id;
                let default_payment_processor = ep_event_booking.default_payment_processor;

                if (paypal_client_id) {
                    if (ep_event_booking.booking_data.tickets) {
                        if (!default_payment_processor || default_payment_processor == 'undefined' || default_payment_processor == 'paypal_processor') {
                            jQuery('#paypal_payment').prop('checked', true);
                            jQuery('#ep-paypal-button-container').show(500);
                        }

                        let booking_tickets = ep_event_booking.booking_data.tickets;

                        if (booking_tickets) {
                            var total_price = 0;
                            var total_discount = 0;
                            var items = [];
                            var random_order_id = Math.random().toString(36).substring(2, 7);

                            jQuery.each(booking_tickets, function (idx, data) {
                                let price = data.price;

                                if (data.additional_fee && data.additional_fee.length > 0) {
                                    jQuery.each(data.additional_fee, function (idx, add_data) {
                                        let add_price = add_data.price;

                                        if (add_price > 0) {
                                            price = parseFloat(price) + parseFloat(add_price);
                                        }
                                    });
                                }

                                if (data.offer) {
                                    total_discount = parseFloat(total_discount) + parseFloat(data.offer);
                                }

                                let item_data = {
                                    "name": data.name,
                                    "description": data.name,
                                    "unit_amount": {
                                        "currency_code": ep_currency,
                                        "value": price
                                    },
                                    "discount": {
                                        "currency_code": ep_currency,
                                        "value": data.offer
                                    },
                                    "quantity": data.qty
                                };

                                items.push(item_data);
                            });

                            if( ep_event_booking.booking_data.event.em_fixed_event_price && ep_event_booking.booking_data.event.em_fixed_event_price > 0 ) {
                                let item_data = {
                                    "name": "Event Fees",
                                    "description": "Event Fees",
                                    "unit_amount": {
                                        "currency_code": ep_currency,
                                        "value": ep_event_booking.booking_data.event.em_fixed_event_price
                                    },
                                    "quantity": 1
                                }
                                items.push( item_data );
                            }

                            if (sessionStorage.getItem("ep_booking_additional_price") !== null) {
                                const additional_price = sessionStorage.getItem('ep_booking_additional_price');
                                if( ep_event_booking.enabled_woocommerce_integration == 1 && additional_price != 'undefined' && ep_event_booking.booking_data.event.em_enable_product == 1 && ep_event_booking.booking_data.event.em_selectd_products.length > 0 ){
                                    let item_data = {
                                        "name": "Additional Prices",
                                        "description": "Aditional Prices",
                                        "unit_amount": {
                                            "currency_code": ep_currency,
                                            "value": additional_price
                                        },
                                        "quantity": 1
                                    }
                                    items.push( item_data );
                                }
                            }
                            
                            if (sessionStorage.getItem("ep_coupon") !== null) {
                                const coupon_data = sessionStorage.getItem('ep_coupon');
                                const couponArray = JSON.parse(coupon_data);
                                if(couponArray.code !== null){
                                    let ep_cc_dis = couponArray.discount_amount;
                                    if( ep_cc_dis ) {
                                        ep_cc_dis = atob( ep_cc_dis );
                                        if( ep_cc_dis > 0 ) {
                                            total_discount = parseFloat( total_discount ) + parseFloat( ep_cc_dis );
                                        }
                                    }
                                }
                            }
                            

                            total_discount = total_discount.toFixed(2);
                            booking_price = parseFloat(booking_price).toFixed(2);

                            var order_id = 0;
                            var checkout_form = jQuery("#ep_event_checkout_form");
                            jQuery('#ep-paypal-button-container').html('');
                            
                            // Function to create PayPal button after AJAX call
                            function createPayPalButton() {
                                paypal.Buttons({
                                    onClick: handlePayPalClick,
                                    createOrder: function (data, actions) {
                                        // Pass item_total and actions to createOrder function
                                        return createOrder(item_total, actions);
                                    },
                                    onApprove: handlePayPalApprove,
                                    onError: handlePayPalError
                                }).render('#ep-paypal-button-container');

                            }

                            // Function to handle the onClick event
                            function handlePayPalClick() {
                                let booking_data = {
                                    action: 'ep_save_event_booking',
                                    data: checkout_form.serialize(),
                                    rid: random_order_id,
                                    offer_data: JSON.stringify(ep_event_booking.booking_data.ep_event_offer_data)
                                };

                                jQuery.ajax({
                                    type: "POST",
                                    url: eventprime.ajaxurl,
                                    data: booking_data,
                                    success: function (response) {
                                        if (response.success == true) {
                                            if (response.data.payment_method == "paypal") {
                                                order_id = response.data.order_id;
                                                let booking_total = response.data.booking_total;
                                                let item_total = response.data.item_total;

                                                // Call createOrder function with the obtained data
                                                createOrder(item_total);
                                            }
                                        }
                                    }
                                });
                            }

                            // Function to set up the transaction with the obtained item_total
                            // Function to set up the transaction with the obtained item_total
                            function createOrder(item_total, actions) {
                                return actions.order.create({
                                    "purchase_units": [{
                                        "custom_id": random_order_id,
                                        "amount": {
                                            "currency_code": ep_currency,
                                            "value": booking_price,
                                            "breakdown": {
                                                "item_total": {
                                                    "currency_code": ep_currency,
                                                    "value": (parseFloat(item_total) + parseFloat(total_discount))
                                                },
                                                "discount": {
                                                    "currency_code": ep_currency,
                                                    "value": total_discount
                                                }
                                            }
                                        },
                                        "items": items
                                    }]
                                });
                            }

                            // Function to handle the onApprove event
                            function handlePayPalApprove(data, actions) {
                                actions.order.capture().then(function (orderData) {
                                    jQuery('.ep-event-loader').show();
                                    paypalPaymentOnApprove(orderData, order_id);
                                });
                            }

                            // Function to handle the onError event
                            function handlePayPalError(err) {
                                alert("There seems to be a problem. Please refresh the page and try again");
                            }

                            // Call the function to create the PayPal button
                            createPayPalButton();
                        }
                    }
                }
            }
            if( eventprime.global_settings.stripe_processor == 1 ) {
                if(sessionStorage.getItem( "allow_process_for_payment_step" )){
                    if(jQuery('#stripe_payment').is(':checked')){
                        initialize();
                    }
                }
                //initialize();
                /* jQuery('#stripe_payment').removeAttr("checked");
                jQuery('.ep-stripe-form').hide(); */
            }

            
        }
    }
}


function paypalPaymentOnApprove( orderData, order_id ) {
    let data = { 
        action   : 'ep_paypal_sbpr', 
        data     : orderData,
        order_id : order_id
    };
    jQuery.ajax({
        type    : "POST",
        url     : eventprime.ajaxurl,
        data    : data,
        success : function( response ) {
            if( response.data.redirect ) {
                location.href = response.data.redirect;
            }
        }
    });
}

// reload user section
function loadCheckoutUserSection( userId ) {
    jQuery( '.ep-event-loader' ).show();
    let user_data = { 
        action: 'ep_reload_checkout_user_section', 
        userId: userId,
    };
    jQuery.ajax({
        type    : "POST",
        url     : eventprime.ajaxurl,
        data    : user_data,
        success : function( response ) {
            jQuery( '.ep-event-loader' ).hide();
            if( response.success == true ) {
                jQuery( '#ep_event_booking_checkout_user_section' ).html( response.data.user_html );
                jQuery( '#ep_event_booking_checkout_user_section' ).show( 500 );
            }
        }
    });
}