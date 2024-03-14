( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		var youzify_review_button;

		/**
		 * Add Review
		 */
		$( document ).on( 'click', '#youzify-add-review' , function( e ) {

    		e.preventDefault();

			var data = $( '#youzify-review-form' ).serialize() +
			"&action=youzify_handle_user_reviews" +
			"&operation=" + $( this ).attr( 'data-action' ) +
			"&security=" + Youzify.security_nonce;

    		var submit_button = $( this );

    		var button_title = submit_button.text();

    		// Disable Submit Button.
    		submit_button.attr( 'disabled', 'disabled' );

		    // Show Loader.
		    submit_button.addClass( 'loading' );

			// Process.
			$.post( ajaxurl, data, function( response ) {

				// Remove loading spinner.
		    	submit_button.removeClass( 'loading' );

            	// Get Response Data.
            	var res = $.parseJSON( response );

				if ( res.error ) {

	            	// Show Error Message
	            	$.youzify_DialogMsg( 'error', res.error );

		    		// Disable Submit Button.
		    		submit_button.attr( 'disabled', false );

					return false;

				} else {

		    		submit_button.closest( '#youzify-modal' ).find( '.youzify-modal-close-icon' ).trigger( 'click' );

		    		// Change Button Title.
					if ( youzify_review_button.parent().attr( 'class' ) != 'youzify-item-tools' ) {
	    				youzify_review_button.find( '.youzify-tool-name' ).text( res.edit_review );
					}

					// Update Button Action
					if ( res.action == 'edit' ) {
						yyouzify-modalz_review_button.attr( 'data-review-id', res.review_id );
						youzify_review_button.attr( 'data-action', 'edit' );

						youzify_review_button.find( '.youzify-tool-name' ).text( res.button_title );
						youzify_review_button.find( '.youzify-tool-icon i' ).removeClass().addClass( 'fas fa-edit' );
						if ( youzify_review_button.attr( 'data-youzify-tooltip' ) !== undefined ) {
							youzify_review_button.attr( 'data-youzify-tooltip', res.button_title );
						}
					} else if ( res.action == 'delete_button' ) {
		    			youzify_review_button.remove();
					}

	            	// Show Error Message
	            	$.youzify_DialogMsg( 'success', res.msg );

					return false;
				}

			}).fail( function( xhr, textStatus, errorThrown ) {

	    		// Enable Submit Button.
	    		submit_button.attr( 'disabled', 'disabled' );

            	// Show Error Message
            	$.youzify_DialogMsg( 'error', Youzify.unknown_error );

				return false;

    		});

    	});

    	/**
    	 * Display User Review Form
    	 */
		$( document ).on( 'click', '.youzify-review-btn, .youzify-review-tool.youzify-edit-tool' , function( e ) {

    		e.preventDefault();

    		// Set Global
    		youzify_review_button = $( this );

    		// Init Vars
    		var youzify_curent_btn = $( this ), user_id, review_id = null, button_icon = null;

    		// Get User ID.
    		if ( youzify_review_button.hasClass( 'youzify-review-btn' ) ) {
    			review_id = $( this ).attr( 'data-review-id' );
    			user_id = $( this ).parent( '.youzify-tools' ).data( 'user-id' );
    		} else {
    			review_id = $( this ).parent( '.youzify-item-tools' ).data( 'review-id' );
    			user_id = $( this ).parent( '.youzify-item-tools' ).data( 'user-id' );
    		}

    		// Disable Click On Displaying Share Box.
    		if ( $( this ).hasClass( 'loading' ) ) {
    			return false;
    		}

    		// Add Loading Class.
    		youzify_curent_btn.addClass( 'loading' );

    		// Get Button Data.
			var data = {
				user_id: user_id,
				review_id: review_id,
				security: Youzify.security_nonce,
				action : 'youzify_get_user_review_form',
				operation: $( this ).attr( 'data-action' ),
			};

			// Process Verification.
			$.post( Youzify.ajax_url, data, function( response ) {

            	// Get Response Data.
				if ( $.youzify_isJSON( response ) ) {

            		var res = $.parseJSON( response );

		    		// Remove Loading Class.
		    		youzify_curent_btn.removeClass( 'loading' );

	            	// Show Error Message
	            	$.youzify_DialogMsg( 'error', res.error );

					return false;

				}

				// Mark Button As laoded.
				youzify_curent_btn.attr( 'data-loaded', 'true' );

	    		// Remove Loading Class.
	    		youzify_curent_btn.removeClass( 'loading' );

	    		var $form = $( response );

				// Append Content.
				$.youzify_show_modal( $form );

			}).fail( function( xhr, textStatus, errorThrown ) {

				// Remove Loading Class.
	    		youzify_curent_btn.removeClass( 'loading' );

            	// Show Error Message
            	$.youzify_DialogMsg( 'error', Youzify.unknown_error );

				return false;

    		});

		});


    	/**
    	 * Delete User Review.
    	 */
		$( document ).on( 'click', '#youzify-delete-review, .youzify-review-tool.youzify-delete-tool' , function( e ) {

    		e.preventDefault();

    		var submit_button = $( this ),
    			button_title = submit_button.text(),
    			review_id;

    		// Disable Submit Button.
    		submit_button.attr( 'disabled', 'disabled' );

		    // Show Loader.
		    submit_button.addClass( 'loading' );

			// Create New Form Data.
		    var formData = new FormData();

		    if ( submit_button.hasClass( 'youzify-delete-tool' ) ) {
		    	review_id = $( this ).parent( '.youzify-item-tools' ).data( 'review-id' );
		    } else {
		    	review_id = submit_button.closest( '.youzify-modal' ).find( 'input[name="review_id"]' ).val()
		    }

		    // Fill Form with Data.
		    formData.append( 'review_id', review_id );
		    formData.append( 'action', 'youzify_delete_user_review' );
			formData.append( 'security', Youzify.security_nonce );

			$.ajax({
                type: "POST",
                data: formData,
                url: Youzify.ajax_url,
		        contentType: false,
		        processData: false,
		        success: function( response ) {

					// Remove Loading Spinner.
			    	submit_button.removeClass( 'loading' );

					// Disable Delete Button.
					submit_button.attr( 'disabled', false );

	            	// Get Response Data.
	            	var res = $.parseJSON( response );

					if ( res.error ) {

		            	// Show Error Message
		            	$.youzify_DialogMsg( 'error', res.error );

			    		// Disable Submit Button.
			    		submit_button.attr( 'disabled', false );

						return false;

					} else {

		    			if ( submit_button.hasClass( 'youzify-delete-tool' ) ) {
		    				submit_button.closest( '.youzify-review-item' ).fadeOut( 300, function() {
				    			$( this ).remove();
		    				});
		    			} else {

		    				submit_button.closest( '#youzify-modal' ).find( '.youzify-modal-close-icon' ).trigger( 'click' );

				    		// Change Button Title.
				    		youzify_review_button.find( '.youzify-tool-name' ).text( res.edit_review );

			    			youzify_review_button.remove();
		    			}

		            	// Show Error Message
		            	$.youzify_DialogMsg( 'success', res.msg );

						return false;
					}

		        }


				});


		});

	});

})( jQuery );