( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		/**
		 * Pin / Unpin Post.
		 */
		$( document ).on( 'click',  '.youzify-pin-tool', function ( e ) {

    		e.preventDefault();

    		// Disable Click On Processing Verification.
    		if ( $( this ).hasClass( 'loading' ) ) {
    			return false;
    		}

    		// Init Vars
    		var youzify_curent_pin_btn, youzify_pin_btn_title;
    		youzify_curent_pin_btn = $( this );

    		// Add Loading Class.
    		youzify_curent_pin_btn.addClass( 'loading' );

    		// Get Button Data.
			var data = {
				action: 'youzify_handle_sticky_posts',
				security: Youzify.security_nonce,
				operation: $( this ).attr( 'data-action' ),
				component: $( this ).closest( '.activity' ).hasClass( 'single-group' ) ? 'groups' : 'activity',
				post_id: $( this ).closest( '.youzify-activity-tools' ).attr( 'data-activity-id' ),
			};

			// Process Verification.
			$.post( ajaxurl, data, function( response ) {

            	// Get Response Data.
            	var res = $.parseJSON( response );

				if ( res.error ) {

		    		// Remove Loading Class.
		    		youzify_curent_pin_btn.removeClass( 'loading' );

	            	// Show Error Message
	            	$.youzify_DialogMsg( 'error', res.error );

					return false;

				} else if ( res.action ) {

		    		// Remove Loading Class.
		    		youzify_curent_pin_btn.removeClass( 'loading' );

		    		// Update Button Icon & Activity Class.
					if ( res.action == 'pin' ) {
						youzify_curent_pin_btn.find( '.youzify-tool-icon i' ).removeClass( 'fa-flip-vertical');
						youzify_curent_pin_btn.closest( '.activity-item' ).removeClass( 'youzify-pinned-post' );
					} else if ( res.action == 'unpin' ) {
						youzify_curent_pin_btn.find( '.youzify-tool-icon i' ).addClass( 'fa-flip-vertical');
						youzify_curent_pin_btn.closest( '.activity-item' ).addClass( 'youzify-pinned-post' );
					}

					// Get Button Title.
					youzify_pin_btn_title = ( res.action == 'pin' ) ? res.pin : res.unpin;

					// Update Button title.
					youzify_curent_pin_btn.find( '.youzify-tool-name' ).text(  youzify_pin_btn_title );

					// Update Button Action
					youzify_curent_pin_btn.attr( 'data-action', res.action );

	            	// Show Error Message
	            	$.youzify_DialogMsg( 'success', res.msg );

					return false;
				}

			}).fail( function( xhr, textStatus, errorThrown ) {

				// Remove Loading Class.
	    		youzify_curent_pin_btn.removeClass( 'loading' );

            	// Show Error Message
            	$.youzify_DialogMsg( 'error', Youzify.unknown_error );

				return false;

    		});

		});

	});

})( jQuery );