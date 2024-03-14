jQuery( function( $ ) {
    
    $( document ).ready( function() {
        // validate addtag before save
        var addform = $( '#addtag' );
        $( addform ).find( "input[type='submit']" ).click( function( e ) {
            if( $( "input[name='taxonomy']" ).val() != 'em_event_organizer') return true;
            
            e.preventDefault();
            let formError = 0;
            
            // check for valid phone no.
            $( '.ep-organizers-phone input[type="text"]' ).each(function() {
                let phoneValue = $( this ).val();
                if( phoneValue && !is_valid_phone(phoneValue ) ) {
                    let invalidPhone = get_translation_string( 'invalid_phone' );
                    if( $( this ).closest( '.ep-org-phone' ).find( '.ep-error-message' ).length < 1 ){
                        $( this ).closest( '.ep-org-phone' ).append('<div class="ep-error-message">'+invalidPhone+'</div>');
                    }
                    $( this ).focus();
                    formError = 1;
                    return false;
                } else{
                    $( this ).closest( '.ep-org-phone' ).find( '.ep-error-message' ).remove();
                }
            });

            // check for valid email
            $( '.ep-organizers-email input[type="email"]' ).each(function() {
                let emailValue = $( this ).val();
                if( emailValue && !is_valid_email( emailValue ) ) {
                    let invalidEmail = get_translation_string( 'invalid_email' );
                    if( $( this ).closest( '.ep-org-email' ).find( '.ep-error-message' ).length < 1 ){
                        $( this ).closest( '.ep-org-email' ).append('<div class="ep-error-message">'+invalidEmail+'</div>');
                    }
                    $( this ).focus();
                    formError = 1;
                    return false;
                } else{
                    $( this ).closest( '.ep-org-email' ).find( '.ep-error-message' ).remove();
                }
            });

            // check for valid url
            $( '.ep-organizers-website input[type="text"]' ).each(function() {
                let urlValue = $( this ).val();
                if( urlValue && !is_valid_url(urlValue ) ) {
                    let invalidUrl = get_translation_string( 'invalid_url' );
                    if( $( this ).closest( '.ep-org-website' ).find( '.ep-error-message' ).length < 1 ){
                        $( this ).closest( '.ep-org-website' ).append('<div class="ep-error-message">'+invalidUrl+'</div>');
                    }
                    $( this ).focus();
                    formError = 1;
                    return false;
                } else{
                    $( this ).closest( '.ep-org-website' ).find( '.ep-error-message' ).remove();
                }
            });

            if( formError == 1 ){
                return false;
            }
            $( addform ).submit();
        });

        // validate addtag before save
        var editform = $("#edittag");
        $(editform).find("input[type='submit']").click(function(e){
            if($("input[name='taxonomy']").val() != 'em_event_organizer') return true;
            
            e.preventDefault();
            let formError = 0;
            
            // check for valid phone no.
            $( '.ep-organizers-phone input[type="text"]' ).each(function() {
                let phoneValue = $( this ).val();
                if( phoneValue && !is_valid_phone(phoneValue ) ) {
                    let invalidPhone = get_translation_string( 'invalid_phone' );
                    if( $( this ).closest( '.ep-org-phone' ).find( '.ep-error-message' ).length < 1 ){
                        $( this ).closest( '.ep-org-phone' ).append('<div class="ep-error-message">'+invalidPhone+'</div>');
                    }
                    $( this ).focus();
                    formError = 1;
                    return false;
                } else{
                    $( this ).closest( '.ep-org-phone' ).find( '.ep-error-message' ).remove();
                }
            });

            // check for valid email
            $( '.ep-organizers-email input[type="email"]' ).each(function() {
                let emailValue = $( this ).val();
                if( emailValue && !is_valid_email(emailValue ) ) {
                    let invalidEmail = get_translation_string( 'invalid_email' );
                    if( $( this ).closest( '.ep-org-email' ).find( '.ep-error-message' ).length < 1 ){
                        $( this ).closest( '.ep-org-email' ).append('<div class="ep-error-message">'+invalidEmail+'</div>');
                    }
                    $( this ).focus();
                    formError = 1;
                    return false;
                } else{
                    $( this ).closest( '.ep-org-email' ).find( '.ep-error-message' ).remove();
                }
            });

            // check for valid url
            $( '.ep-organizers-website input[type="text"]' ).each(function() {
                let urlValue = $( this ).val();
                if( urlValue && !is_valid_url(urlValue ) ) {
                    let invalidUrl = get_translation_string( 'invalid_url' );
                    if( $( this ).closest( '.ep-org-website' ).find( '.ep-error-message' ).length < 1 ){
                        $( this ).closest( '.ep-org-website' ).append('<div class="ep-error-message">'+invalidUrl+'</div>');
                    }
                    $( this ).focus();
                    formError = 1;
                    return false;
                } else{
                    $( this ).closest( '.ep-org-website' ).find( '.ep-error-message' ).remove();
                }
            });

            if( formError == 1 ){
                return false;
            }
            $( editform ).submit();
        });
    });

    $( document ).on( 'click', '.ep-org-add-more', function() {
        let data_input = $( this ).data( 'input' );
        let max_field_count = 5;
        if( data_input == 'phone' ) {
            let phone_count = $( '.ep-organizers-phone .ep-org-phone' ).length;
            if( phone_count < max_field_count ) { 
                let removeTitle = $( this ).data( 'remove_title' );
                let fieldHtml = '<span class="ep-org-phone ep-org-data-field">';
                fieldHtml += '<input type="text" class="ep-org-data-input" name="em_organizer_phones[]" placeholder="Phone">';
                fieldHtml += '<button type="button" class="ep-org-remove button button-primary" data-input="phone" title="'+removeTitle+'">-</button>';
                fieldHtml += '</span>';
                $( fieldHtml ).insertBefore( $( '.ep-organizers-phone p.emnote' ) );
            } else{
                show_toast( 'warning', em_organizer_object.max_field_warning + ' ' + data_input );
            }
        }

        if( data_input == 'email' ) {
            let email_count = $( '.ep-organizers-email .ep-org-email' ).length;
            if ( email_count < max_field_count ) {
                let removeTitle = $( this ).data( 'remove_title' );
                let fieldHtml = '<span class="ep-org-email ep-org-data-field">';
                fieldHtml += '<input type="email" class="ep-org-data-input" name="em_organizer_emails[]" placeholder="Email">';
                fieldHtml += '<button type="button" class="ep-org-remove button button-primary" data-input="email" title="'+removeTitle+'">-</button>';
                fieldHtml += '</span>';
                $( fieldHtml ).insertBefore( $( '.ep-organizers-email p.emnote' ) );
            } else{
                show_toast( 'warning', em_organizer_object.max_field_warning + ' ' + data_input );
            }
        }

        if( data_input == 'website' ) {
            let website_count = $( '.ep-organizers-website .ep-org-website' ).length;
            if ( website_count < max_field_count ) {
                let removeTitle = $( this ).data( 'remove_title' );
                let fieldHtml = '<span class="ep-org-website ep-org-data-field">';
                fieldHtml += '<input type="text" class="ep-org-data-input" name="em_organizer_websites[]" placeholder="Website">';
                fieldHtml += '<button type="button" class="ep-org-remove button button-primary" data-input="website" title="'+removeTitle+'">-</button>';
                fieldHtml += '</span>';
                $( fieldHtml ).insertBefore( $( '.ep-organizers-website p.emnote' ) );
            } else{
                show_toast( 'warning', em_organizer_object.max_field_warning + ' ' + data_input );
            }
        }
    });

    $( document ).on( 'click', '.ep-org-remove', function() {
        $( this ).closest( '.ep-org-data-field' ).remove();
    });

    // fire on upload image button
    var file_frame;
    jQuery( document ).on( 'click', '.upload_image_button', function( event ) {
        event.preventDefault();
        // If the media frame already exists, reopen it.
        if ( file_frame ) {
            file_frame.open();
            return;
        }
        // Create the media frame.
        file_frame = wp.media.frames.downloadable_file = wp.media({
            title: em_organizer_object.media_title,
            button: {
                text: em_organizer_object.media_button
            },
            multiple: false
        });

        // When an image is selected, run a callback.
        file_frame.on( 'select', function() {
            var attachment           = file_frame.state().get( 'selection' ).first().toJSON();
            var attachment_thumbnail = attachment.sizes.thumbnail || attachment.sizes.full;

            jQuery( '#ep_organizer_image_id' ).val( attachment.id );
            let imageHtml = '<span class="ep-organizer-image">';
                imageHtml += '<i class="remove_image_button dashicons dashicons-trash ep-text-danger"></i>';
                imageHtml += '<img src="'+attachment_thumbnail.url+'" data-image_id="'+attachment.id+'" width="60">';
            imageHtml += '</span>';
            jQuery( '#ep-organizer-admin-image' ).html( imageHtml );
        });

        // Finally, open the modal.
        file_frame.open();
    });

    // remove image
    jQuery( document ).on( 'click', '.remove_image_button', function(){
        jQuery( '#ep-organizer-admin-image' ).html('');
        jQuery( '#ep_organizer_image_id' ).val( '' );
    });

    // fire event on ajax complete
    jQuery( document ).ajaxComplete( function( event, request, options ) {
        if ( request && 4 === request.readyState && 200 === request.status
            && options.data && 0 <= options.data.indexOf( 'action=add-tag' ) ) {

            var res = wpAjax.parseAjaxResponse( request.responseXML, 'ajax-response' );
            if ( ! res || res.errors ) {
                return;
            }
            // Clear Thumbnail fields on submit
            jQuery( '#ep-organizer-admin-image' ).html('');
            jQuery( '#ep_organizer_image_id' ).val( '' );
            jQuery( '#is_featured' ).prop( 'checked', false );
            return;
        }
    } );

});