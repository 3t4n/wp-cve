( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		/**
		 * Pin / Unpin Post.
		 */
		$( document ).on( 'click',  ".youzify-bookmark-tool", function ( e ) {

    		e.preventDefault();

    		// Disable Click On Processing Verification.
    		if ( $( this ).hasClass( 'loading' ) ) {
    			return false;
    		}

    		// Init Vars
    		var youzify_curent_btn, youzify_btn_title;
    		youzify_curent_btn = $( this );

    		// Add Loading Class.
    		youzify_curent_btn.addClass( 'loading' );

    		// Get Button Data.
			var data = {
				security: Youzify.security_nonce,
				action: 'youzify_handle_posts_bookmark',
				item_type: $( this ).data( 'item-type' ),
				operation: $( this ).attr( 'data-action' ),
				item_id: $( this ).closest( '.youzify-activity-tools' ).attr( 'data-activity-id' ),
			};

			// Process.
			$.post( ajaxurl, data, function( response ) {

            	// Get Response Data.
            	var res = $.parseJSON( response );

				if ( res.error ) {

		    		// Remove Loading Class.
		    		youzify_curent_btn.removeClass( 'loading' );

	            	// Show Error Message
	            	$.youzify_DialogMsg( 'error', res.error );

					return false;

				} else if ( res.action ) {

		    		// Remove Loading Class.
		    		youzify_curent_btn.removeClass( 'loading' );

		    		// Update Button Icon & Activity Class.
					if ( res.action == 'save' ) {
						youzify_curent_btn.find( '.youzify-tool-icon i' ).removeClass().addClass( 'fas fa-bookmark' );
					} else if ( res.action == 'unsave' ) {
						youzify_curent_btn.find( '.youzify-tool-icon i' ).removeClass().addClass( 'fas fa-times' );
					}

					// Get Button Title.
					youzify_btn_title = ( res.action == 'save' ) ? res.save_post : res.unsave_post;

					// Update Button title.
					youzify_curent_btn.find( '.youzify-tool-name' ).text( youzify_btn_title );

					// Update Button Action
					youzify_curent_btn.attr( 'data-action', res.action );

	            	// Show Error Message
	            	$.youzify_DialogMsg( 'success', res.msg );

					return false;
				}

			}).fail( function( xhr, textStatus, errorThrown ) {

				// Remove Loading Class.
	    		youzify_curent_btn.removeClass( 'loading' );

            	// Show Error Message
            	$.youzify_DialogMsg( 'error', Youzify.unknown_error );

				return false;

    		});

		});

	});

})( jQuery );