jQuery( function( $ ) {

    $( document ).ready( function() {
        // fes user role restriction
        $( '#frontend_submission_roles' ).select2({
            theme: "classic"
        });

        $( '#front_switch_view_option' ).select2({
            theme: "classic"
        });

        $( "#default_calendar_date" ).datepicker({
            changeYear: true,
            changeMonth: true,
            dateFormat: 'yy-mm-dd',
            gotoCurrent: true,
            showButtonPanel: true,
        });
    });

    // open new checkout fields dialog
    $( document ).on( 'click', '#em_add_new_checkout_field', function() {
        $( '#ep_event_settings_checkout_fields_container #em_checkout_field_label' ).val('');
        $( '#ep_event_settings_checkout_fields_container #em_checkout_field_type' ).val('');
        $( '#ep_event_settings_checkout_fields_container #em_checkout_field_id' ).val('');
        if( $( '.ep-checkout-fields-option-wrapper' ).length > 0 ) {
            $( '.ep-checkout-fields-option-wrapper' ).remove();
        }
    });
    // save checkout field
    $( document ).on( 'click', '#ep_save_checkout_field', function() {
        $( '.ep-error-message' ).text( '' );
        $( '#em_checkout_field_label_error' ).text( '' );
        $( '#em_checkout_field_type_error' ).text( '' );
        let requireString = get_translation_string( 'required' );
        let field_id = $( '#em_checkout_field_id').val();
        let label = $( '#em_checkout_field_label').val();
        if( !label ) {
            $( '#em_checkout_field_label_error' ).text( requireString );
            return false;
        }
        let type = $( '#em_checkout_field_type').val();
        if( !type ) {
            $( '#em_checkout_field_type_error' ).text( requireString );
            return false;
        }
        // check for option data
        let isEmptyOPtions = false;
        if( $( '.ep-checkout-fields-option-container' ).length > 0 ) {
            $('.ep-checkout-fields-option-container input[type=text]:required' ).each(function() {
                if( $( this ).val() === '' ) {
                    let option_field_id = this.id;
                    $( '#' + option_field_id + '_error' ).text( requireString );
                    isEmptyOPtions = true;
                    return false;
                }
            });
        }
        if( isEmptyOPtions ) return false;

        $( '#ep_event_settings_checkout_fields_container #ep_modal_buttonset .ep-checkout-field-modal-close' ).before('<span class="spinner is-active"></span>');
        $( '#ep_event_settings_checkout_fields_container #ep_modal_buttonset button' ).attr( 'disabled', 'disabled');
        
        var original_data = $( '#ep_event_settings_checkout_fields_container' ).find(
			'input, select, textarea'
		);
        let data = { 
            action: 'ep_save_checkout_field', 
            security: ep_admin_settings.save_checkout_fields_nonce, 
            data: original_data.serialize(),
        };
        $.ajax({
            type: 'POST', 
            url :  get_ajax_url(),
            data: data,
            success: function(data, textStatus, XMLHttpRequest){
                let field_data_message = data.data.message;
                $( '#ep_event_settings_checkout_fields_container #ep_modal_buttonset .spinner' ).remove();
                $( '#ep_event_settings_checkout_fields_container #ep_modal_buttonset .ep-checkout-field-modal-close' ).before('<span class="ep-success-message">'+field_data_message+'</span>');
                setTimeout( function() {
                    $( '#ep_event_settings_checkout_fields_container #ep_modal_buttonset .ep-success-message' ).remove();
                    $( '#ep_event_settings_checkout_fields_container' ).closePopup({
                        anim: (!$(this).attr('data-animation') || $(this).data('animation') == null) ? 'ep-modal-' : $(this).data('animation')
                    });
                    let field_data = data.data.field_data;
                    if( field_data && !field_id ){
                        let newTr = '<tr id="ep-checkout-field-'+field_data.field_id+'">';
                            newTr += '<td class="em-checkout-field-label">' + field_data.label + '</td>';
                            newTr += '<td class="em-checkout-field-type">' + field_data.type + '</td>';
                            newTr += '<td>' + field_data.created_at + '</td>';
                            newTr += '<td><div class="ep-checkout-field-action">';
                                newTr += '<a href="javascript:void(0);" class="ep-edit-checkout-field" data-field_id="'+field_data.field_id+'" data-field_label="'+field_data.label+'" data-field_type="'+field_data.type+'">'+ep_admin_settings.edit_text+'</a>';
                                newTr += '<a href="javascript:void(0);" class="ep-delete-checkout-field ep-open-modal" data-id="ep_event_settings_delete_checkout_field" data-field_id="'+field_data.field_id+'" data-field_label="'+field_data.label+'" data-field_type="'+field_data.type+'"><span class="ep-text-danger ep-cursor ep-ml-3">'+ep_admin_settings.delete_text+'</span></a>';
                            newTr += '</div></td>';
                        newTr += '</tr>';
                        $( '#ep_settings_checkout_field_lists tbody' ).prepend( newTr );
                        $( '#ep_settings_checkout_field_lists tbody tr:nth-child(1)' ).effect( "highlight", {}, 3000 );
                    } else{
                        $( '#ep-checkout-field-'+field_data.field_id+' .em-checkout-field-label' ).html( field_data.label );
                        $( '#ep-checkout-field-'+field_data.field_id+' .em-checkout-field-type' ).html( field_data.type );
                        // update the attribute
                        $( '#ep_edit_checkout_field_' + field_data.field_id ).attr( 'data-field_label', field_data.label );
                        $( '#ep_edit_checkout_field_' + field_data.field_id ).attr( 'data-field_type', field_data.type );

                        $( '#ep-checkout-field-'+field_data.field_id ).effect( "highlight", {}, 3000 );
                    }

                    // initialize the fields
                    $( '#em_checkout_field_label').val( '' );
                    $( '#em_checkout_field_type').val( '' );
                    $( '#ep_event_settings_checkout_fields_container #ep_modal_buttonset button' ).removeAttr( 'disabled' );
                }, 2000);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){ 
                $( '#ep_event_settings_checkout_fields_container #ep_modal_buttonset .spinner' ).remove();
                $( '#ep_event_settings_checkout_fields_container #ep_modal_buttonset button' ).removeAttr( 'disabled');
                $( '#ep_event_settings_checkout_fields_container #ep_modal_buttonset .ep-checkout-field-modal-close' ).before('<span class="ep-error-message">'+errorThrown+'</span>');
            }
        });
    });

    // edit checkout field modal
    $( document ).on( 'click', '.ep-edit-checkout-field', function() {
        let field_id = $( this ).data( 'field_id' );
        if( field_id ) {
            // remove the error message before open the modal
            $( '#em_checkout_field_label_error, #em_checkout_field_type_error' ).text( '' );

            let field_label = $( this ).attr( 'data-field_label' );
            let field_type = $( this ).attr( 'data-field_type' );
            $( '#ep_event_settings_checkout_fields_container' ).openPopup({
                anim: (!$(this).attr('data-animation') || $(this).data('animation') == null) ? 'ep-modal-' : $(this).data('animation')
            });
            $( '#ep_event_settings_checkout_fields_container .ep-modal-title' ).html( ep_admin_settings.edit_checkout_field_title );
            $( '#ep_event_settings_checkout_fields_container #em_checkout_field_label' ).val( field_label );
            $( '#ep_event_settings_checkout_fields_container #em_checkout_field_type' ).val( field_type );
            $( '#ep_event_settings_checkout_fields_container #em_checkout_field_id' ).val( field_id );
            $( '#em_checkout_field_type' ).trigger('change');
        }
    });

    // delete checkout field modal
    $( document ).on( 'click', '.ep-delete-checkout-field', function() {
        let field_id = $( this ).data( 'field_id' );
        if( field_id ) {
            $( '#em_checkout_field_id_delete' ).val( field_id );
        }
    });

    // delete action
    $( document ).on( 'click', '#em_delete_checkout_fields', function() {
        let field_id = $( '#em_checkout_field_id_delete' ).val();
        if( field_id ) {
            $( '#ep_event_settings_delete_checkout_field #em_delete_modal_cancel_button' ).before('<span class="spinner is-active"></span>');
            $( '#ep_event_settings_delete_checkout_field #em_delete_modal_cancel_button' ).attr( 'disabled', 'disabled');
            $( '#ep_event_settings_delete_checkout_field #em_delete_checkout_fields' ).attr( 'disabled', 'disabled');
            deleteCheckoutField( field_id );
        }
    });

    $( document ).on( 'click', '.ep-checkout-field-modal-close', function() {
        let title = $( '#ep_event_settings_checkout_fields_container' ).attr( 'title' );
        setTimeout( function() {
            $( '#ep_event_settings_checkout_fields_container .ep-modal-title' ).html( title );
            $( '#ep_event_settings_checkout_fields_container #em_checkout_field_label' ).val('');
            $( '#ep_event_settings_checkout_fields_container #em_checkout_field_type' ).val('');
            $( '#ep_event_settings_checkout_fields_container #em_checkout_field_id' ).val('');
            if( $( '.ep-checkout-fields-option-wrapper' ).length > 0 ) {
                $( '.ep-checkout-fields-option-wrapper' ).remove();
            }
        }, 100);
        
        $( '#ep_event_settings_checkout_fields_container' ).closePopup({
            anim: (!$(this).attr('data-animation') || $(this).data('animation') == null) ? 'ep-modal-' : $(this).data('animation')
        });
    });

    jQuery('.ep-payment-toggle').click(function(e){
        ep_submit_payment_ajax(this);
    });
    
    // fes allow submission by anonymous users
    $( document ).on( 'click', '#allow_submission_by_anonymous_user', function() {
        if( $( this ).prop( 'checked' ) == true ) {
            $( '#ep-use-login-message' ).hide();
        } else{
            $( '#ep-use-login-message' ).show();
        }
    });

    // front box view show/hide
    $( document ).on( 'change', '#ep_settings_front_display_view', function() {
        if( $( this ).val() == 'colored_grid' ) {
            $( '#ep_settings_card_background' ).show();
        } else{
            $( '#ep_settings_card_background' ).hide();
        }
    });

    // login form Id field option
    $( document ).on( 'change', '#login_id_field', function() {
        if( $( this ).val() ) {
            $( '#login_id_field_label_setting' ).show();    
        } else{
            $( '#login_id_field_label_setting' ).hide();
        }
    });

    // show remember me
    $( document ).on( 'click', '#login_show_rememberme', function() {
        if( $( this ).prop( 'checked' ) == true ) {
            $( '#login_rememberme_setting' ).show();
        } else{
            $( '#login_rememberme_setting' ).hide();
        }
    });

    // show forgot password
    $( document ).on( 'click', '#login_show_forgotpassword', function() {
        if( $( this ).prop( 'checked' ) == true ) {
            $( '#login_forgotpassword_setting' ).show();
        } else{
            $( '#login_forgotpassword_setting' ).hide();
        }
    });

    // show forgot password
    $( document ).on( 'click', '#login_google_recaptcha', function() {
        if( $( this ).prop( 'checked' ) == true ) {
            $( '#login_google_recaptcha_setting' ).show();
        } else{
            $( '#login_google_recaptcha_setting' ).hide();
        }
    });
    // show register link
    $( document ).on( 'click', '#login_show_registerlink', function() {
        if( $( this ).prop( 'checked' ) == true ) {
            $( '#login_registerlink_setting' ).show();
            $( '#login_registerform_setting' ).show();
        } else{
            $( '#login_registerlink_setting' ).hide();
            $( '#login_registerform_setting' ).hide();
        }
    });

    // show RM form options
    $( document ).on( 'change', '#login_registration_form', function() {
        $( '#ep_user_registration_form_settings' ).hide();
        $( '#login_rm_registerform_setting' ).hide();
        if( $( this ).val() == 'rm' ) {
            $( '#login_rm_registerform_setting' ).show();
        }
        if( $( this ).val() == 'ep' ) {
            $( '#ep_user_registration_form_settings' ).show();
        }
    });

    // register forms checkout fields
    $( document ).on( 'click', '#ep_settings_register_form_fields input[type=checkbox]', function() {
        let field_name = $( this ).data( 'field' );
        let property_name = $( this ).data( 'property' );
        if( $( this ).prop( 'checked' ) == true ) {
            // if click on the madatory then the field should be visible
            if( property_name == 'mandatory' ) {
                $( '#' + field_name + '_show' ).prop( 'checked', 'checked' );
            }
        } else{
            // if mandatory propery checked and show property unchecked, then mandatory should be unchecked
            if( property_name == 'show' ) {
                if( $( '#' + field_name + '_mandatory' ).prop( 'checked' ) == true ) {
                    $( '#' + field_name + '_mandatory' ).prop( 'checked', false );
                }
            }
        }
    });

    // show/hide paypal options
    $( document ).on( 'change', '#ep_paypal_processor_settings', function() {
        if( $( this ).prop( 'checked' ) == false ) {
            $( '.ep-enable-paypal-service' ).hide( 500 );
        } else{
            $( '.ep-enable-paypal-service' ).show( 500 );
        }
    });

    // show/hide no of events custom value input field
    $( document ).on( 'change', '#show_no_of_events_card', function() {
        var default_cal_view = $( '#default_cal_view' ).val();
        var show_no_of_event = $( this ).val();
        if(  ( default_cal_view == 'card' || default_cal_view == 'masonry' || default_cal_view == 'list' || default_cal_view == 'square_grid' || default_cal_view == 'staggered_grid' || default_cal_view == 'rows' ) && show_no_of_event == 'custom' ) {
            $('.ep_enable_card_view_custom_value_child').show();
        } else{
            $('.ep_enable_card_view_custom_value_child').hide();
        }
    });

    // show/hide timezone settings
    $( document ).on( 'click', '#enable_event_time_to_user_timezone', function() {
        if( $( this ).prop( 'checked' ) == true ) {
            $( '#show_timezone_message_on_event_page_wrap' ).show();
            if( $( '#show_timezone_message_on_event_page' ).prop( 'checked' ) == true ) {
                $( '#timezone_related_message_wrap' ).show();
            } else{
                $( '#timezone_related_message_wrap' ).hide();
            }
        } else{
            $( '#show_timezone_message_on_event_page_wrap' ).hide();
            $( '#timezone_related_message_wrap' ).hide();
        }
    });

    $( document ).on( 'click', '#show_timezone_message_on_event_page', function() {
        if( $( this ).prop( 'checked' ) == true ) {
            $( '#timezone_related_message_wrap' ).show();
        } else{
            $( '#timezone_related_message_wrap' ).hide();
        }
    });
    
    $( document ).on( 'change', '#ep_populate_email', function() {
        var selectedVal = $(this).val();
        $('#ep-email-not-found').hide();
        if( selectedVal !== null){
            var formData = new FormData(document.getElementById('ep-email-attendies'));
            formData.append('action', 'ep_get_attendees_email_by_event_id');
            jQuery.ajax({
                type : "POST",
                url : get_ajax_url(),
                data: formData,
                contentType: false,
                processData: false,       
                success: function(response) {
                    if(response.data.status === true){
                        $('#ep-email-address-lists').val(response.data.emails);
                    }else{
                        $('#ep-email-not-found').html(response.data.errors).show();
                        $('#ep-email-address-lists').val('');
                    }
                }
            });
        }
    });
    
    $( document ).on( 'click', '#ep_send_email_attendies', function() {
        var validated = false;
        $('.ep-attendies-error').hide();
        var emails = $('#ep-email-address-lists').val();
        var email_cc = $('#ep_email_cc').val();
        var subject = $('#ep_email_subject').val();
        var content = '';
        let editor_id = textarea_id = 'content';
        if ( jQuery('#wp-'+editor_id+'-wrap').hasClass('tmce-active') && tinyMCE.get(editor_id) ) {
            content =  tinyMCE.get(editor_id).getContent();
        }else{
           content = jQuery('#'+textarea_id).val();
        } 
        if(emails == ''){
            $('.ep-attendies-error').html('Emails is required');
        }else if(subject == ''){
            $('.ep-attendies-error').html('Subject is required'); 
        }else if(content == ''){
            $('.ep-attendies-error').html('Content is required'); 
        }else{
            validated = true;
        }
        $('.ep-attendies-error').show();
        if( validated == true){
            var formData = new FormData(document.getElementById('ep-email-attendies'));
            formData.append('action', 'ep_send_attendees_email');
            formData.append('content_html', content);
            jQuery.ajax({
                type : "POST",
                url : get_ajax_url(),
                data: formData,
                contentType: false,
                processData: false,       
                success: function(response) {
                    alert(response.data.message);
                    if(response.data.success == true){
                        location.reload();
                    }
                }
            });
        }
    });

    // delete checkout field callback
    function deleteCheckoutField( field_id ) {
        if( field_id ) {
            let data = { 
                action: 'ep_delete_checkout_field', 
                security: ep_admin_settings.delete_checkout_fields_nonce, 
                field_id: field_id,
            };
            $.ajax({
                type: 'POST', 
                url :  get_ajax_url(),
                data: data,
                success: function(data, textStatus, XMLHttpRequest){
                    let field_data_message = data.data.message;
                    $( '#ep_event_settings_delete_checkout_field .spinner' ).remove();
                    $( '#ep_event_settings_delete_checkout_field #em_delete_modal_cancel_button' ).before('<span class="ep-success-message">'+field_data_message+'</span>');
                    setTimeout( function() {
                        $( '#ep_event_settings_delete_checkout_field .ep-success-message' ).remove();
                        $( '#ep_event_settings_delete_checkout_field' ).closePopup({
                            anim: (!$(this).attr('data-animation') || $(this).data('animation') == null) ? 'ep-modal-' : $(this).data('animation')
                        });
                        $( '#ep_event_settings_delete_checkout_field #em_delete_modal_cancel_button' ).removeAttr( 'disabled' );
                        $( '#ep_event_settings_delete_checkout_field #em_delete_checkout_fields' ).removeAttr( 'disabled' );
                        if( data.success == true ){
                            $( '#ep-checkout-field-'+field_id ).remove();
                        }
                    }, 2000);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown){ 
                    $( '#ep_event_settings_delete_checkout_field .spinner' ).remove();
                    $( '#ep_event_settings_delete_checkout_field button' ).removeAttr( 'disabled');
                    $( '#ep_event_settings_delete_checkout_field #em_delete_modal_cancel_button' ).before('<span class="ep-error-message">'+errorThrown+'</span>');
                }
            });
        }
    }

    function ep_submit_payment_ajax( ele ) {
        var loader = $( '<span class="spinner is-active" style="float: none;"></span>' );
        $( loader ).insertAfter( $( ele ).parent() );
        let method = ele.getAttribute('name');
        let method_status = 0;
        if( ele.checked ) {
            method_status = 1;
        }
        var formData = new FormData( document.getElementById('ep_setting_form') );
        formData.append( 'action', 'ep_submit_payment_setting' );
        formData.append( 'payment_method', method );
        formData.append( 'method_status', method_status );
        jQuery.ajax({
            type : "POST",
            url : get_ajax_url(),
            data: formData,
            contentType: false,
            processData: false,       
            success: function( response ) {
                if( response.success ) {
                    if( response.data.url != '' ) {
                        window.location.replace( response.data.url );
                    }
                    if( method_status == 0 ) {
                        $('input:checkbox[name='+method+']').val(0);
                    } else{
                        $('input:checkbox[name='+method+']').val(1);
                    }
                }
                loader.remove();
                show_toast( 'success', response.data.message );
            }
        });
    }
    // handle the default payment request
    $( document ).on( 'click', '.ep-default-payment-processor', function() {
        ep_submit_default_payment_processor_ajax( this );
    })

    // set default payment option
    function ep_submit_default_payment_processor_ajax( ele ){
        var loader = $( '<span class="spinner is-active" style="float: none;"></span>' );
        $( loader ).insertAfter( $( ele ).parent() );
        let ep_default_payment_processor = ele.value;
        // first check if payment processor is activate
        let check_payment_active = $('input:checkbox[name='+ep_default_payment_processor+']').val();
        if( check_payment_active == 1 ) {
            let data = {
                'action': 'ep_set_default_payment_processor',
                'security': ep_admin_settings.default_payment_processor_nonce,
                'ep_default_payment_processor': ep_default_payment_processor
            }
            $.ajax({
                type : "POST",
                url : get_ajax_url(),
                data: data,
                success: function( response ) {
                    loader.remove();
                    if( response.success ) {
                        window.location.reload();
                    } else{
                        show_toast( 'error', response.error );
                        $( '#ep_default_payment_' + ep_default_payment_processor ).prop( 'checked', false );
                        return false;
                    }
                }
            });
        } else{
            loader.remove();
            let payment_name = ep_default_payment_processor.split( '_' )[0];
            show_toast( 'error', ep_admin_settings.activate_payment + ' ' + payment_name + ' ' + ep_admin_settings.payment_text);
            $( '#ep_default_payment_' + ep_default_payment_processor ).prop( 'checked', false );
            return false;
        }
    }

    // show/hide custom height
    $( document ).on( 'change', '#event_detail_image_height', function() {
        $( '#event_detail_image_height_custom_data' ).hide();
        let image_height_val = $( this ).val();
        if( image_height_val == 'custom' ) {
            $( '#event_detail_image_height_custom_data' ).show();
        }
    });

    // show/hide the trending event type options
    $( document ).on( 'change', '#ep_show_trending_event_types', function() {
        if( $( this ).prop( 'checked' ) == true ) {
            $( '.ep-settings-trending-event-type-option' ).show();
        } else{
            $( '.ep-settings-trending-event-type-option' ).hide();
        }
    });
});
