jQuery(document).ready(function($) {

	/*************************************************************************************************
	 *
	 *          Save Settings
	 *
	 ************************************************************************************************/

	// Save Settings button - Submit
	$( document.body ).on( 'click', '#ephd_hd_save_settings_btn', function( e ) {
		e.preventDefault();

		// Prepare form data
		let postData = {
			action: 'ephd_save_analytics_settings',
			_wpnonce_ephd_ajax_action: ephd_help_dialog_vars.nonce
		};

		// Set form data: to include any field to the form, add 'ephd-input-group' CSS class to the field input
		$( '#ephd-admin__boxes-list__hd-stats-settings .ephd-input-group input' ).each( function() {

			let field_name = $( this ).attr( 'name' );
			let field_type = $( this ).attr( 'type' );

			// checkbox
			if ( 'checkbox' === field_type ) {

				// checkboxes multiselect
				if ( $( this ).closest( '.ephd-admin__checkboxes-multiselect' ).length ) {
					if ( $( this ).prop( 'checked' ) ) {
						if ( ! postData[field_name] ) {
							postData[field_name] = [];
						}
						postData[field_name].push( $(this).val() );
					}

				// single checkbox
				} else {
					postData[field_name] = ( $( this ).prop( 'checked' ) ) ? 'on' : 'off';
				}

			// other input field types
			} else {
				postData[field_name] = $( this ).val();
			}
		});
		$( '#ephd-admin__boxes-list__hd-stats-settings .ephd-input-group select' ).each( function() {
			let field_name = $( this ).attr( 'name' );
			postData[field_name] = $( this ).val();
		});

		// Send form
		ephd_send_ajax( postData, function( response ){
			if ( ! response.error && typeof response.message != 'undefined' ) {
				ephd_show_success_notification( response.message );
			}
		});
	});

	/*************************************************************************************************
	 *
	 *          Other events
	 *
	 ************************************************************************************************/

	// Submit analytics filters form after change date select field
	$( document.body ).on( 'change', '.ephd_admin_analytics_filters select', function() {
		ephd_loading_Dialog( 'show', '' );
		$( this ).closest( 'form' ).submit();
	});

	// Open popup box from admin widget (ephd-ap-widget)
	$( '.ephd-admin__open-details-popup' ).on( 'click', function() {
		$( this ).closest( '.ephd-ap-widget' ).find( '.ephd-ap-details-popup' ).addClass( 'ephd-ap-details-popup--active' );
		return false;
	} );

	// Popup accept / close button
	$( document.body ).on( 'click', '.ephd-ap-details-popup__overlay, .ephd-ap-details-popup__accept-btn', function() {
		$( '.ephd-ap-details-popup' ).removeClass( 'ephd-ap-details-popup--active' );
	});

	// Link to Per Page tab with selected filter page/post location filter
	$( document.body ).on( 'click', '.ephd-ap-cell-location-link', function() {

		let page_id = $( this ).data( 'obj_id' );

		if ( typeof page_id === 'undefined' ) {
			return;
		}

		// search option in filter drop-down select list by id
		let $location_option = $( '#ephd_admin_analytics_filters_location option[value="' + page_id + '"]' );

		// is option exist
		if ( $location_option.length > 0 ) {

			// show loading
			ephd_loading_Dialog( 'show', '' );

			// click on Per Page tab
			$( '.ephd-admin__top-panel__item--hd-stats-per-page' ).trigger( 'click' );

			// select option and trigger change to submit form
			$location_option.prop( 'selected', true ).trigger( 'change' )

		} else {
			// show bottom error notification
			ephd_show_error_notification( ephd_vars.msg_no_data );
		}

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
		}).done(function (response)        {
			theResponse = ( response ? response : '' );
			if ( theResponse.error || typeof theResponse.message === 'undefined' ) {
				//noinspection JSUnresolvedVariable,JSUnusedAssignment
				errorMsg = theResponse.message ? theResponse.message : ephd_admin_notification('', ephd_vars.reload_try_again, 'error');
			}

		}).fail( function ( response, textStatus, error )        {
			//noinspection JSUnresolvedVariable
			errorMsg = ( error ? ' [' + error + ']' : ephd_vars.unknown_error );
			//noinspection JSUnresolvedVariable
			errorMsg = ephd_admin_notification(ephd_vars.error_occurred + '. ' + ephd_vars.msg_try_again, errorMsg, 'error');
		}).always(function() {
			
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