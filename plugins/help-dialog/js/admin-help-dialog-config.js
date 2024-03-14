jQuery(document).ready(function($) {

	/*************************************************************************************************
	 *
	 *          Misc
	 *
	 ************************************************************************************************/

	// Disable PRO inputs for Advanced Settings page
	$( '#ephd-admin__boxes-list__settings .ephd-admin__input-disabled' ).each( function(){
		$( this ).find( 'input, select, textarea' ).prop( 'disabled', true );
	});

	// Toggle the PRO Setting Tooltip
	$( document ).on( 'click', '.ephd-admin__input-disabled, .ephd__option-pro-tag', function (){
		let $tooltip = $( this ).closest( '.ephd-input-group' ).find( '.ephd__option-pro-tooltip' );
		let is_visible = $tooltip.is(':visible');

		// hide all pro tooltip
		$( '.ephd__option-pro-tooltip' ).hide();

		// toggle current pro tooltip
		if ( is_visible ) {
			$tooltip.hide();
		} else {
			$tooltip.show();
		}
	});

	// Hide PRO Setting Tooltip if click outside the tooltip
	$( document ).on( 'click', function (e){
		let target = $( e.target );
		if ( ! target.closest( '.ephd__option-pro-tooltip' ).length && ! target.closest( '.ephd-admin__input-disabled' ).length && ! target.closest( '.ephd__option-pro-tag' ).length  ) {
			$( '.ephd__option-pro-tooltip' ).hide();
		}
	});

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
			action: 'ephd_save_global_settings',
			_wpnonce_ephd_ajax_action: ephd_help_dialog_vars.nonce
		};

		// Set form data: to include any field to the form, add 'ephd-input-group' CSS class to the field input
		$( '#ephd-admin__boxes-list__settings .ephd-input-group input, #ephd-admin__boxes-list__settings .ephd-input-group textarea' ).each( function() {

			let field_name = $( this ).attr( 'name' );
			let field_type = $( this ).attr( 'type' );

			// Checkbox
			if ( field_type === 'checkbox' ) {
				if ( $( this ).prop( 'checked' ) ) {
					if ( typeof postData[field_name] === 'undefined' ) {
						postData[field_name] = [];
					}
					postData[field_name].push( $( this ).val() );
				}
			// Radio
			} else if ( field_type === 'radio' ) {
				if ( $( this ).prop( 'checked' ) ) {
					postData[field_name] = $( this ).val();
				}
			// Other inputs
			} else {
				postData[field_name] = $( this ).val();
			}
		});

		// Send form
		ephd_send_ajax( postData, function( response ){
			if ( ! response.error && typeof response.message != 'undefined' ) {
				ephd_show_success_notification( response.message );

				//$( '.ephd-wp__widget-form' ).replaceWith( response.widget_form );
			}
		});
	});

	/*************************************************************************************************
	 *
	 *          Test Analytics
	 *
	 ************************************************************************************************/
	// Add test records
	$( document.body ).on( 'click', '#ephd_add_test_analytics_records', function( e ) {
		e.preventDefault();

		let postData = {
			action: 'ephd_add_test_analytics_records',
			_wpnonce_ephd_ajax_action: ephd_help_dialog_vars.nonce
		};
		ephd_send_ajax( postData, function( response ){
			if ( ! response.error && typeof response.message != 'undefined' ) {
				ephd_show_success_notification( response.message );
			}
		});
	});
	// Delete test records
	$( document.body ).on( 'click', '#ephd_delete_test_analytics_records', function( e ) {
		e.preventDefault();

		let postData = {
			action: 'ephd_delete_test_analytics_records',
			_wpnonce_ephd_ajax_action: ephd_help_dialog_vars.nonce
		};
		ephd_send_ajax( postData, function( response ){
			if ( ! response.error && typeof response.message != 'undefined' ) {
				ephd_show_success_notification( response.message );
			}
		});
	});

	/*************************************************************************************************
	 *
	 *          Tools Tab: Export Import settings
	 *
	 ************************************************************************************************/

	// Export Import settings
	$( document ).on( 'click', '#ephd-admin__boxes-list__tools .ephd-kbnh__feature-links .ephd-primary-btn', function( e ) {
		e.preventDefault();

		let id = $( this ).prop( 'id' );

		if ( id === 'ephd_core_export' ) {
			$( '#ephd-admin__boxes-list__tools form.ephd-export-settings' ).submit();
			return false;
		}

		if ( $( '.ephd-kbnh__feature-panel-container--' + id ).length === 0 ) {
			return false;
		}

		$( this ).closest( '.ephd-setting-box__list' ).find( '.ephd-kbnh__feature-container').css( {'display' : 'none'} );
		$( this ).closest( '.ephd-setting-box__list' ).find( '.ephd-kbnh__feature-panel-container--' + id ).css( {'display' : 'block'} );

		return false;
	});

	// Back button
	$( '#ephd-admin__boxes-list__tools .ephd-kbnh-back-btn' ).on('click', function( e ){
		e.preventDefault();
		$( '#ephd-admin__boxes-list__tools .ephd-setting-box__list>.ephd-kbnh__feature-container').css( {'display' : 'flex'} );
		$( '#ephd-admin__boxes-list__tools .ephd-setting-box__list>.ephd-kbnh__feature-panel-container').css( {'display' : 'none'} );
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