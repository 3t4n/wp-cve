( function( $ ) {

	'use strict';

	$( document ).ready( function() {

    	$( '.youzify-verify-btn' ).on( 'click', function( e ) {

    		e.preventDefault();

    		// Disable Click On Processing Verification.
    		if ( $( this ).hasClass( 'loading' ) ) {
    			return false;
    		}

    		// Init Vars
    		var youzify_curent_verf_btn, youzify_verf_btn_icon, youzify_verf_btn_title;
    		youzify_curent_verf_btn = $( this );

    		// Add Loading Class.
    		youzify_curent_verf_btn.addClass( 'loading' );

    		// Get Button Data.
			var data = {
				verification_action: $( this ).attr( 'data-action' ),
				action: 'youzify_handle_account_verification',
				user_id: $( this ).data( 'user-id' ),
				security: $( this ).data( 'nonce')
			};

			// Process Verification.
			$.post( ajaxurl, data, function( response ) {

            	// Get Response Data.
            	var res = $.parseJSON( response );

				if ( res.error ) {

		    		// Remove Loading Class.
		    		youzify_curent_verf_btn.removeClass( 'loading' );

	            	// Show Error Message
	            	$.youzify_DialogMsg( 'error', res.error );

					return false;

				} else if ( res.action ) {

		    		// Remove Loading Class.
		    		youzify_curent_verf_btn.removeClass( 'loading' );

					// Get Button Icon.
					youzify_verf_btn_icon = ( res.action == 'verify' ) ? 'fas fa-check' : 'fas fa-times';

					// Update Button Icon.
					youzify_curent_verf_btn.find( '.youzify-tool-icon i' ).attr( 'class', youzify_verf_btn_icon );

					// Get Button Title.
					youzify_verf_btn_title = ( res.action == 'verify' ) ? res.verify_account : res.unverify_account;

					// Update Button title.
					youzify_curent_verf_btn.find( '.youzify-tool-name' ).text(  youzify_verf_btn_title );

					// Update Tooltip Title
					if ( youzify_curent_verf_btn.attr( 'data-youzify-tooltip' ) !== undefined ) {
						youzify_curent_verf_btn.attr( 'data-youzify-tooltip', youzify_verf_btn_title );
					}

					// Update Button Action
					youzify_curent_verf_btn.attr( 'data-action', res.action );

	            	// Show Error Message
	            	$.youzify_DialogMsg( 'success', res.msg );

					return false;
				}

			}).fail( function( xhr, textStatus, errorThrown ) {

				// Remove Loading Class.
	    		youzify_curent_verf_btn.removeClass( 'loading' );

            	// Show Error Message
            	$.youzify_DialogMsg( 'error', Youzify.unknown_error );

				return false;

    		});

		});

	});

})( jQuery );