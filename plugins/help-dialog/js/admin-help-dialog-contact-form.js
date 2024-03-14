jQuery(document).ready(function($) {

	/*************************************************************************************************
	 *
	 *          Misc
	 *
	 ************************************************************************************************/

	// Remove tbody if there are no entries
	if ( ! $( '.ephd-admin__items-list__item tr' ).length ) {
		$( '.ephd-admin__items-list__item' ).remove();
	}

	/*************************************************************************************************
	 *
	 *          Submissions page
	 *
	 ************************************************************************************************/

	// Delete all submissions - only call delete dialog
	$( document ).on( 'click', '.ephd-admin__items-list__delete-all input', function( e ) {
		$( '#ephd-admin__items-list__delete-all_confirmation' ).addClass( 'ephd-dialog-box-form--active' );
	});

	/*************************************************************************************************
	 *
	 *         Contact Form Design page
	 *
	 ************************************************************************************************/

	// Load form to edit/create Contact Form Design
	$( document ).on( 'click', '.ephd-admin__item-preview .ephd_edit_item, .ephd-cf__create-new-design-btn', function( e ) {
		e.preventDefault();

		let contact_form_id = $( this ).data( 'id' );
		let postData = {
			action: 'ephd_load_contact_form',
			_wpnonce_ephd_ajax_action: ephd_help_dialog_vars.nonce,
			contact_form_id: typeof contact_form_id !== 'undefined' ? contact_form_id : 0
		};

		ephd_send_ajax( postData, function( response ){
			if ( ! response.error && typeof response.design_form != 'undefined' ) {

				// Hide elements before show the form
				$( '.ephd-cf__create-new-design-btn, .ephd__welcome-message' ).hide();

				// Update Design form
				$( '.ephd-cf__design-form' ).replaceWith( response.design_form );

				// Hide preview boxes
				$( '.ephd-admin__item-preview' ).removeClass( 'ephd-admin__item-preview--active' );
			}
		} );
	} );

	// Save Contact Form Design - save form
	$( document ).on( 'click', '.ephd-cf__design-form .ephd_save_contact_form', function() {

		let form = $( this ).closest( '.ephd-cf__design-form' );
		let contact_form_id = form.find( '[name="contact_form_id"]' ).val();
		let postData = {
			action: 'ephd_save_contact_form',
			_wpnonce_ephd_ajax_action: ephd_help_dialog_vars.nonce,
			contact_form_id: contact_form_id,
			contact_form_name: form.find( '[name="contact_form_name"]' ).val(),
			contact_welcome_title: form.find( '[name="contact_welcome_title"]' ).val(),
			contact_welcome_text: form.find( '[name="contact_welcome_text"]' ).val(),
			contact_name_text: form.find( '[name="contact_name_text"]' ).val(),
			contact_user_email_text: form.find( '[name="contact_user_email_text"]' ).val(),
			contact_subject_text: form.find( '[name="contact_subject_text"]' ).val(),
			contact_comment_text: form.find( '[name="contact_comment_text"]' ).val(),
			contact_acceptance_text: form.find( '[name="contact_acceptance_text"]' ).val(),
			contact_button_title: form.find( '[name="contact_button_title"]' ).val(),
			contact_success_message: form.find( '[name="contact_success_message"]' ).val()
		};

		ephd_send_ajax( postData, function( response ) {
			if ( ! response.error && typeof response.message != 'undefined' && typeof response.design_form != 'undefined' ) {
				ephd_show_success_notification( response.message );

				// Update Design form
				form.replaceWith( response.design_form );

				// Update Design preview
				if ( $( '.ephd-admin__item-preview--' + contact_form_id ).length ) {
					$( '.ephd-admin__item-preview--' + contact_form_id ).replaceWith( response.design_preview );

				// Add Design preview
				} else {
					$( response.design_preview ).insertBefore( '.ephd-cf__design-form' );
				}
			}
		} );
	});

	// Delete Contact Form Design - only call delete dialog
	$( document ).on( 'click', '.ephd-cf__delete-contact-form-wrap input.ephd_delete_contact_form', function( e ) {
		$( '#ephd-cf__delete-contact-form-confirmation' ).addClass( 'ephd-dialog-box-form--active' );
	});

	// Delete Contact Form Design by press on confirmation button
	$( '#ephd-cf__delete-contact-form-confirmation' ).on( 'submit', function( e ) {
		e.preventDefault();

		let confirmation_form = $( this ),
			form = $( '.ephd-cf__design-form' ),
			contact_form_id = form.find( '[name="contact_form_id"]' ).val(),
			postData = {
			action: 'ephd_delete_contact_form',
			_wpnonce_ephd_ajax_action: ephd_help_dialog_vars.nonce,
			contact_form_id: contact_form_id
		};

		ephd_send_ajax( postData, function( response ) {
			if ( ! response.error && typeof response.message != 'undefined' ) {

				// Show success message
				ephd_show_success_notification( response.message );

				// Hide forms
				confirmation_form.removeClass( 'ephd-dialog-box-form--active' );
				form.removeClass( 'ephd-cf__design-form--active' );

				// Show preview boxes
				$( '.ephd-admin__item-preview' ).addClass( 'ephd-admin__item-preview--active' );

				// Remove preview box for the deleted Contact Form Design
				$( '.ephd-admin__item-preview--' + contact_form_id ).fadeOut( function() {
					$( this ).remove();
				});

				// Show elements when hide the form
				$( '.ephd-cf__create-new-design-btn, .ephd__welcome-message' ).show();
			}
		} );
	});

	// Cancel form to edit/create Contact Form Design
	$( document ).on( 'click', '.ephd_cancel_contact_form', function( e ) {
		e.preventDefault();
		$( '.ephd-cf__design-form' ).removeClass( 'ephd-cf__design-form--active' );
		$( '.ephd-admin__item-preview' ).addClass( 'ephd-admin__item-preview--active' );
		$( '.ephd-cf__create-new-design-btn, .ephd__welcome-message' ).show();
		return false;
	} );

	// Contact Form Design Name
	$( document ).on( 'input', '.ephd-cf__feature-option-field__design_name input[type="text"]', function() {
		$( this ).closest( '.ephd-cf__design-form' ).find( '.ephd-cf__design-form__title-text' ).html( $( this ).val() );
	});

	// Save Contact Form Design Name
	$( document ).on( 'click', '.ephd-cf__feature-option-field__design_name input[type="button"]', function() {
		$( this ).closest( '.ephd-cf__design-form' ).find( '.ephd_save_contact_form' ).trigger( 'click' );
	});

	// Toggle input for edit Contact Form Design Name
	$( document ).on( 'click', '.ephd-cf__design-form__title-wrap .ephd-cf__edit-toggle', function() {
		$( this ).closest( '.ephd-cf__design-form' ).find( '.ephd-cf__feature-option-field__design_name' ).slideToggle( 200 );
	} );

	// Load full preview box by clicking on the View More link in preview boxes
	$( document ).on( 'click', '.ephd-admin__item-preview .ephd-admin__item-preview__sub-items-btn', function( e ) {
		e.preventDefault();

		let contact_form_id = $( this ).data( 'id' );
		let postData = {
			action: 'ephd_load_contact_form_preview',
			_wpnonce_ephd_ajax_action: ephd_help_dialog_vars.nonce,
			contact_form_id: typeof contact_form_id !== 'undefined' ? contact_form_id : 1
		};

		ephd_send_ajax( postData, function( response ){
			if ( ! response.error && typeof response.design_preview != 'undefined' ) {

				// Update preview box
				$( '.ephd-admin__item-preview--' + contact_form_id ).replaceWith( response.design_preview );
			}
		} );
	} );

	/*************************************************************************************************
	 *
	 *         Contact Form Editor page
	 *
	 ************************************************************************************************/

	// Save contact form settings
	$( document ).on( 'click', '.ephd-admin__list-actions-row .ephd_save_contact_form_settings', function( e ) {
		e.preventDefault();

		let form = $( '.ephd-cf__email-form' );

		let postData = {
			action: 'ephd_save_contact_form_settings',
			_wpnonce_ephd_ajax_action: ephd_help_dialog_vars.nonce,
			contact_submission_email: form.find( '[name="contact_submission_email"]' ).val(),
		};

		ephd_send_ajax( postData, function( response ){
			if ( ! response.error && typeof response.message != 'undefined' ) {
				ephd_show_success_notification( response.message );

				// Update test submission form email
				$( '.ephd-cf__test-email-form' ).find( '[name="email"]' ).val( postData.contact_submission_email );
			}
		} );

		return false;
	});


	// Test Contact Form Submission
	$( document ).on( 'click', '.ephd-cf__test-email-form .ephd_test_contact_form_submission', function( e ) {
		e.preventDefault();

		let form = $( '.ephd-cf__test-email-form' );
		let email = form.find( '[name="email"]' ).val();            // email storing in settings
		let unsaved_email = $( '#contact_submission_email' ).val(); // possibly unsaved email

		let postData = {
			action: 'ephd_help_dialog_contact',
			_wpnonce: ephd_help_dialog_vars.nonce,
			jsnonce: ephd_help_dialog_vars.nonce,

			widget_id: form.find( '[name="widget_id"]' ).val(),
			widget_name: form.find( '[name="widget_name"]' ).val(),
			contact_form_id: form.find( '[name="contact_form_id"]' ).val(),
			page_id: form.find( '[name="page_id"]' ).val(),
			page_name: form.find( '[name="page_name"]' ).val(),
			email:  unsaved_email,  // always test value that user has in the input
			user_first_name: form.find( '[name="user_first_name"]' ).val(),
			subject: form.find( '[name="subject"]' ).val(),
			comment: form.find( '[name="comment"]' ).val(),
			acceptance: form.find( '[name="acceptance"]' ).val(),
			submission_email_test: form.find( '[name="submission_email_test"]' ).val(),
			is_email_unsaved: email !== unsaved_email,
		};

		ephd_send_ajax( postData, function( response ) {
			if ( response.success === false && typeof response.message != 'undefined' ) {
				ephd_show_error_notification( response.message );
			} else if ( ! response.error&& typeof response.message != 'undefined' ) {
				ephd_show_success_notification( response.message );
			}
		} );

		return false;
	});

	/*************************************************************************************************
	 *
	 *          Generic
	 *
	 ************************************************************************************************/

	// Delete all items by press on confirmation button
	$( '#ephd-admin__items-list__delete-all_confirmation form' ).on( 'submit', function(e){
		e.preventDefault();

		let postData = {
			action: $( this ).find( '[name="action"]' ).val(),
			_wpnonce_ephd_ajax_action: ephd_help_dialog_vars.nonce
		};

		$( '#ephd-admin__items-list__delete-all_confirmation' ).removeClass( 'ephd-dialog-box-form--active' );

		ephd_send_ajax( postData, function( response ) {

			if ( ! response.error && typeof response.message != 'undefined' ) {

				let container = $( '#ephd-admin__items-list__delete-all_confirmation' ).parent().parent();

				// Show success message
				ephd_show_success_notification( response.message );

				// Delete items
				container.find( '.ephd-admin__items-list .ephd-admin__items-list__item' ).remove();

				// Delete 'Load more items' button, because we have no items in the table
				container.find( '.ephd-admin__items-list__more-items-message' ).remove();

				// Update total number of items
				container.find( '.ephd-admin__items-list__totally-found' ).html( '0' );

				// Hide 'Clear Table' button, because we have no items in the table
				container.find( '.ephd-admin__items-list__delete-all' ).hide();
			}
		} );

		return false;
	});

	// Handle delete action for single item in table
	let ephd_items_list_delete_item = function(e){
		e.preventDefault();

		let item_row = $( this ).closest( '.ephd-admin__items-list__item' );

		// check that we have filled input
		let item_id = item_row.find( '[name="item_id"]' ).val();

		if ( typeof item_id == 'undefined' || ! item_id ) {
			ephd_show_error_notification( ephd_vars.reload_try_again );
			return;
		}

		let postData = {
			action: item_row.find( '[name="action"]' ).val(),
			item_id: item_id,
			_wpnonce_ephd_ajax_action: ephd_help_dialog_vars.nonce
		};

		ephd_send_ajax( postData, function( response ) {
			if ( ! response.error && typeof response.message != 'undefined' ) {

				// Show success message
				ephd_show_success_notification( response.message );

				let container = item_row.closest( '.ephd-admin__items-list' ).parent().parent().parent();
				let page_number = parseInt( container.find( '[name="page_number"]' ).val() );

				// Update total number of items
				container.find( '.ephd-admin__items-list__totally-found' ).html( response.total_number );

				// Fadeout the removed item row and then delete it
				item_row.fadeOut( 500, function() {

					// Delete item
					item_row.remove();

					// Hide 'Clear Table' button if there is no items left
					if ( parseInt( response.total_number ) === 0 ) {
						container.find( '.ephd-admin__items-list__delete-all' ).hide();
					}
				} );
			}
		} );

		return false;
	};
	$( '.ephd-admin__items-list form.ephd-admin__items-list__field-actions__form' ).on( 'submit', ephd_items_list_delete_item );

	// Load more items
	$( '.ephd-admin__items-list__more-items-message form' ).on( 'submit', function(e){
		e.preventDefault();

		let container = $( this ).closest( '.ephd-admin__items-list__more-items-message' ).parent();
		let form = $( this );
		let insert_before = container.find( '.ephd-admin__items-list .ephd-admin__items-list__no-results' );

		let page_number = parseInt( form.find( '[name="page_number"]' ).val() );

		let postData = {
			action: form.find( '[name="action"]' ).val(),
			page_number: page_number + 1,
			_wpnonce_ephd_ajax_action: ephd_help_dialog_vars.nonce
		};

		ephd_send_ajax( postData, function( response ) {

			if ( ! response.error && typeof response.message != 'undefined' ) {

				let new_items = $( response.items );
				new_items.css( 'display', 'none' );

				page_number = page_number + 1;

				// Initialize submit handlers for each new items
				new_items.find( 'form.ephd-admin__items-list__field-actions__form' ).on( 'submit', ephd_items_list_delete_item );

				// Delete 'Load more items' button if there is no more items exist
				if ( response.total_number <= response.per_page * page_number ) {
					container.find( '.ephd-admin__items-list__more-items-message' ).remove();
				}

				// Insert new items
				$( insert_before ).before( new_items );
				new_items.fadeIn( 1000 );

				// Increase page number
				form.find( '[name="page_number"]' ).val( page_number );
			}
		} );

		return false;
	});


	/*************************************************************************************************
	 *
	 *          AJAX calls
	 *
	 ************************************************************************************************/
	
	// generic AJAX call handler
	function ephd_send_ajax( postData, refreshCallback, callbackParam, reload, alwaysCallback, $loader ) {

		let errorMsg;
		let theResponse;
		refreshCallback = (typeof refreshCallback === 'undefined') ? 'ephd_callback_noop' : refreshCallback;

		$.ajax({
			type: 'POST',
			dataType: 'json',
			data: postData,
			url: ajaxurl,
			beforeSend: function (xhr)
			{
				if ( typeof $loader == 'undefined' || $loader === false ) {
					ephd_loading_Dialog('show', '');
				} 
				
				if ( typeof $loader == 'object' ) {
					ephd_loading_Dialog('show', '', $loader);
				} 
				
			}
		} ).done( function ( response ) {

			theResponse = ( response ? response : '' );

			// Fix data structure to support hd front controller
			if ( typeof theResponse.data !== 'undefined' ) {
				theResponse.message = theResponse.data;
			}

			if ( theResponse.error || typeof theResponse.message === 'undefined' ) {
				//noinspection JSUnresolvedVariable,JSUnusedAssignment
				errorMsg = theResponse.message ? theResponse.message : ephd_admin_notification('', ephd_vars.reload_try_again, 'error');
			}

		} ).fail( function ( response, textStatus, error ) {
			//noinspection JSUnresolvedVariable
			errorMsg = ( error ? ' [' + error + ']' : ephd_vars.unknown_error );
			//noinspection JSUnresolvedVariable
			errorMsg = ephd_admin_notification(ephd_vars.error_occurred + '. ' + ephd_vars.msg_try_again, errorMsg, 'error');
		} ).always( function() {
			
			theResponse = (typeof theResponse === 'undefined') ? '' : theResponse;
			
			if ( typeof alwaysCallback == 'function' ) {
				alwaysCallback( theResponse );
			} 
			
			ephd_loading_Dialog('remove', '');

			if ( errorMsg ) {
				$('.ephd-bottom-notice-message').remove();
				$('body #ephd-admin-page-wrap').append(errorMsg).removeClass('fadeOutDown');
				
				setTimeout( function() {
					$('.ephd-bottom-notice-message').addClass( 'fadeOutDown' );
				}, 10000 );
				return;
			}

			if ( typeof refreshCallback === "function" ) {
				
				if ( callbackParam === 'undefined' ) {
					refreshCallback(theResponse);
				} else {
					refreshCallback(theResponse, callbackParam);
				}
			} else {
				if ( reload ) {
					location.reload();
				}
			}
		});
	}

	/**
	 * Displays a Center Dialog box with a loading icon and text.
	 *
	 * This should only be used for indicating users that loading or saving or processing is in progress, nothing else.
	 * This code is used in these files, any changes here must be done to the following files.
	 *   - admin-plugin-pages.js
	 *   - admin-kb-config-scripts.js
	 *
	 * @param  {string}    displayType     Show or hide Dialog initially. ( show, remove )
	 * @param  {string}    message         Optional    Message output from database or settings.
	 *
	 * @return {html}                      Removes old dialogs and adds the HTML to the end body tag with optional message.
	 *
	 */
	function ephd_loading_Dialog( displayType, message, $el ){

		if( displayType === 'show' ){
			
			let loadingClass = ( typeof $el == 'undefined' ) ? '' : 'ephd-admin-dialog-box-loading--relative';
			
			let output =
				'<div class="ephd-admin-dialog-box-loading ' + loadingClass + '">' +

				//<-- Header -->
				'<div class="ephd-admin-dbl__header">' +
				'<div class="ephd-admin-dbl-icon ephdfa ephdfa-hourglass-half"></div>'+
				(message ? '<div class="ephd-admin-text">' + message + '</div>' : '' ) +
				'</div>'+

				'</div>' +
				'<div class="ephd-admin-dialog-box-overlay ' + loadingClass + '"></div>';

			//Add message output at the end of Body Tag
			if ( typeof $el == 'undefined' ) {
				$( 'body' ).append( output );
			} else { 
				$el.append( output );
			}
			
		}else if( displayType === 'remove' ){

			// Remove loading dialogs.
			$( '.ephd-admin-dialog-box-loading' ).remove();
			$( '.ephd-admin-dialog-box-overlay' ).remove();
		}

	}

	/* Dialogs --------------------------------------------------------------------*/
	// SHOW INFO MESSAGES
	function ephd_admin_notification( $title, $message , $type ) {
		return '<div class="ephd-bottom-notice-message">' +
			'<div class="contents">' +
			'<span class="' + $type + '">' +
			($title ? '<h4>'+$title+'</h4>' : '' ) +
			($message ? '<p>' + $message + '</p>': '') +
			'</span>' +
			'</div>' +
			'<div class="ephd-close-notice ephdfa ephdfa-window-close"></div>' +
			'</div>';
	}

	let ephd_notification_timeout;

	function ephd_show_error_notification( $message, $title = '' ) {
		$( '.ephd-bottom-notice-message' ).remove();
		$( 'body #ephd-admin-page-wrap' ).append( ephd_admin_notification( $title, $message, 'error' ) );

		clearTimeout( ephd_notification_timeout );
		ephd_notification_timeout = setTimeout( function() {
			$('.ephd-bottom-notice-message').addClass( 'fadeOutDown' );
		}, 10000 );
	}

	function ephd_show_success_notification( $message, $title = '' ) {
		$( '.ephd-bottom-notice-message' ).remove();
		$( 'body #ephd-admin-page-wrap' ).append( ephd_admin_notification( $title, $message, 'success' ) );

		clearTimeout( ephd_notification_timeout );
		ephd_notification_timeout = setTimeout( function() {
			$( '.ephd-bottom-notice-message' ).addClass( 'fadeOutDown' );
		}, 10000 );
	}
	
	// scrool to element with animation 
	function ephd_scroll_to( $el ) {
		if ( ! $el.length ) {
			return;
		}
		
		$("html, body").animate({ scrollTop: $el.offset().top - 100 }, 300);
	}
});