jQuery(document).ready(function($) {

	var ephd = $( '#ephd-admin-page-wrap' );

	// Set special CSS class to #wpwrap for only HD admin pages
	if ( $( ephd ).find( '.ephd-admin__content' ).length > 0 ) {
		$( '#wpwrap' ).addClass( 'ephd-admin__wpwrap' );
	}

	/*************************************************************************************************
	 *
	 *          HD CONFIGURATION PAGE
	 *
	 ************************************************************************************************/

	// Save contact form submission email from popup
	$( '#ephd-nh_contact_submission_email_popup' ).addClass( 'ephd-dialog-box-form--active' );
	$( document ).on( 'submit', '#ephd-nh_contact_submission_email_popup', function( e ) {
		e.preventDefault();

		let form = $( this ),
			contact_submission_email = form.find( '[name="contact_submission_email"]' ).val(),
			postData = {
				action: 'ephd_save_global_settings',
				_wpnonce_ephd_ajax_action: ephd_help_dialog_vars.nonce,
				contact_submission_email: contact_submission_email
			};

		ephd_send_ajax( postData, function( response ){
			if ( ! response.error && typeof response.message != 'undefined' ) {

				// Show success message
				ephd_show_success_notification( response.message );

				// Hide forms
				form.removeClass( 'ephd-dialog-box-form--active' );
			}
		} );

		return false;
	});

	function ephd_show_success_notification( $message, $title = '' ) {
		$( '.ephd-bottom-notice-message' ).remove();
		$( 'body #ephd-admin-page-wrap' ).append( ephd_admin_notification( $title, $message, 'success' ) );

		setTimeout( function() {
			$( '.ephd-bottom-notice-message' ).addClass( 'fadeOutDown' );
		}, 10000 );
	}

	/*************************************************************************************************
	 *
	 *          ADMIN PAGES
	 *
	 ************************************************************************************************/

	/* Admin Top Panel Items -----------------------------------------------------*/
	$( '.ephd-admin__top-panel__item' ).on( 'click', function() {

		// Warning for visual Editor
		if ( $( this ).hasClass( 'ephd-article-structure-dialog' ) ) {
			return;
		}

		let active_top_panel_item_class = 'ephd-admin__top-panel__item--active';
		let active_boxes_list_class = 'ephd-admin__boxes-list--active';
		let active_secondary_panel_class = 'ephd-admin__secondary-panel--active';

		// Do nothing for already active item
		if ( $( this ).hasClass( active_top_panel_item_class ) ) {
			return;
		}

		let list_key = $( this ).attr( 'data-target' );

		// Change class for active Top Panel item
		$( '.ephd-admin__top-panel__item' ).removeClass( active_top_panel_item_class );
		$( this ).addClass( active_top_panel_item_class );

		// Change class for active Boxes List
		$( '.ephd-admin__boxes-list' ).removeClass( active_boxes_list_class );
		$( '#ephd-admin__boxes-list__' + list_key ).addClass( active_boxes_list_class );

		// Change class for active Secondary Panel
		$( '.ephd-admin__secondary-panel' ).removeClass( active_secondary_panel_class );
		$( '#ephd-admin__secondary-panel__' + list_key ).addClass( active_secondary_panel_class );

		// Licenses tab on Add-ons page - support for existing add-ons JS handlers
		let active_top_panel_item = this;
		setTimeout( function () {
			if ( $( active_top_panel_item ).attr( 'id' ) === 'echd_license_tab' ) {
				$( '#echd_license_tab').trigger( 'click' );
			}
		}, 100);

		// Update anchor
		window.location.hash = '#' + list_key;
	});

	// Set correct active tab after the page reloading
	(function(){
		let url_parts = window.location.href.split( '#' );

		// Set first item as active if there is no any anchor
		if ( url_parts.length === 1 ) {
			$( $( '.ephd-admin__top-panel__item' )[0] ).trigger( 'click' );
			return;
		}

		let target_kyes = url_parts[1].split( '__' );

		let target_main_items = $( '.ephd-admin__top-panel__item[data-target="' + target_kyes[0] + '"]' );

		// If no target items was found, then set the first item as active
		if ( target_main_items.length === 0 ) {
			$( $( '.ephd-admin__top-panel__item' )[0] ).trigger( 'click' );
			return;
		}

		// Change class for active item
		$( target_main_items[0] ).trigger( 'click' );

		// Key for Secondary item was specified and it is not empty
		if ( target_kyes.length > 1 && target_kyes[1].length > 0 ) {
			setTimeout( function() {

				let target_secondary_items = $( '.ephd-admin__secondary-panel__item[data-target="' + url_parts[1] + '"]' );

				// If no target items was found, then set the first item as active
				if ( target_secondary_items.length === 0 ) {
					$( $( '.ephd-admin__secondary-panel__item' )[0] ).trigger( 'click' );
					return;
				}

				// Change class for active item
				$( target_secondary_items[0] ).trigger( 'click' );
			}, 100 );
		}
	})();

	/* Admin Secondary Panel Items -----------------------------------------------*/
	$( '.ephd-admin__secondary-panel__item' ).on( 'click', function() {

		// Warning for visual Editor
		if ( $( this ).hasClass( 'ephd-article-structure-dialog' ) ) {
			return;
		}

		let active_secondary_panel_item_class = 'ephd-admin__secondary-panel__item--active';
		let active_secondary_boxes_list_class = 'ephd-setting-box__list--active';

		// Do nothing for already active item
		if ( $( this ).hasClass( active_secondary_panel_item_class ) ) {
			return;
		}

		let list_key = $( this ).attr( 'data-target' );
		let parent_list_key = list_key.split( '__' )[0];

		// Change class for active Top Panel item
		$( '#ephd-admin__secondary-panel__' + parent_list_key ).find( '.ephd-admin__secondary-panel__item' ).removeClass( active_secondary_panel_item_class );
		$( this ).addClass( active_secondary_panel_item_class );

		// Change class for active Boxes List
		$( '#ephd-admin__boxes-list__' + parent_list_key ).find( '.ephd-setting-box__list' ).removeClass( active_secondary_boxes_list_class );
		$( '#ephd-setting-box__list-' + list_key ).addClass( active_secondary_boxes_list_class );

		// Update anchor
		window.location.hash = '#' + list_key;
	});

	/* Misc ----------------------------------------------------------------------*/
	(function(){

		// TOGGLE DEBUG
		ephd.find( '#ephd_toggle_debug' ).on( 'click', function() {

			// Remove old messages
			$( '.ephd-top-notice-message' ).html( '' );
			let parent = $( this ).parent();

			let postData = {
				action: parent.find( 'input[name="action"]' ).val(),
				_wpnonce_ephd_ajax_action: parent.find( 'input[name="_wpnonce_ephd_ajax_action"]' ).val()
			};

			ephd_send_ajax( postData, function() {
				location.reload();
			} );
		});

		// ADD-ON PLUGINS + OUR OTHER PLUGINS - PREVIEW POPUP
		 (function(){
			//Open Popup larger Image
			ephd.find( '.featured_img' ).on( 'click', function( e ){

				e.preventDefault();
				e.stopPropagation();

				ephd.find( '.ephd-image-zoom' ).remove();

				var img_src;
				var img_tag = $( this ).find( 'img' );
				if ( img_tag.length > 1 ) {
					img_src = $(img_tag[0]).is(':visible') ? $(img_tag[0]).attr('src') :
							( $(img_tag[1]).is(':visible') ? $(img_tag[1]).attr('src') : $(img_tag[2]).attr('src') );

				} else {
					img_src = $( this ).find( 'img' ).attr( 'src' );
				}

				$( this ).after('' +
					'<div id="ephd_image_zoom" class="ephd-image-zoom">' +
					'<img src="' + img_src + '" class="ephd-image-zoom">' +
					'<span class="close icon_close"></span>'+
					'</div>' + '');

				//Close Plugin Preview Popup
				$('html, body').on('click.ephd', function(){
					$( '#ephd_image_zoom' ).remove();
					$('html, body').off('click.ephd');
				});
			});
		})();

		//Info Icon for Licenses
		$( '#add_on_panels' ).on( 'click', '.ep_font_icon_info', function(){

			$( this ).parent().find( '.ep_font_icon_info_content').toggle();

		});
	})();

	// When clicking on a link with the following class it will show message with target class only
	$( '.ephd-nh__dynamic-notice__toggle' ).on( 'click', function ( e ) {
		e.stopPropagation();
		$( this ).parent().find( '.ephd-nh__dynamic-notice__target' ).show();
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

				if ( typeof callbackParam === 'undefined' ) {
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


	/*************************************************************************************************
	 *
	 *          DIALOGS
	 *
	 ************************************************************************************************/

	/**
	  * Displays a Center Dialog box with a loading icon and text.
	  *
	  * This should only be used for indicating users that loading or saving or processing is in progress, nothing else.
	  * This code is used in these files, any changes here must be done to the following files.
	  *   - admin-plugin-pages.js
	  *   - admin-help-dialog-analytics.js
	  *   - admin-help-dialog-config.js
	  *   - admin-help-dialog-contact-form.js
	  *   - admin-help-dialog-faqs.js
	  *   - admin-help-dialog-widgets.js
	  *
	  * @param  {string}    displayType     Show or hide Dialog initially. ( show, remove )
	  * @param  {string}    message         Optional    Message output from database or settings.
	  *
	  * @return {html}                      Removes old dialogs and adds the HTML to the end body tag with optional message.
	  *
	  */
	function ephd_loading_Dialog( displayType, message ){

		if( displayType === 'show' ){

			let output =
				'<div class="ephd-admin-dialog-box-loading">' +

				//<-- Header -->
				'<div class="ephd-admin-dbl__header">' +
				'<div class="ephd-admin-dbl-icon ephdfa ephdfa-hourglass-half"></div>'+
				(message ? '<div class="ephd-admin-text">' + message + '</div>' : '' ) +
				'</div>'+

				'</div>' +
				'<div class="ephd-admin-dialog-box-overlay"></div>';

			//Add message output at the end of Body Tag
			$( 'body' ).append( output );
		}else if( displayType === 'remove' ){

			// Remove loading dialogs.
			$( '.ephd-admin-dialog-box-loading' ).remove();
			$( '.ephd-admin-dialog-box-overlay' ).remove();
		}

	}

	// Close Button Message if Close Icon clicked
	$( document ).on( 'click', '.ephd-bottom-notice-message .ephdfa-window-close', function() {
		let bottom_message = $( this ).closest( '.ephd-bottom-notice-message' );
		bottom_message.addClass( 'fadeOutDown' );
		setTimeout( function() {
			bottom_message.html( '' );
		}, 1000);
	} );

	// HELP ICON DIALOG
	// open dialog but re-center when loading finished so that it stays in the center of the screen
	var ephd_help_dialog = $("#ephd-dialog-info-icon").dialog(
		{
			resizable: false,
			autoOpen: false,
			modal: true,
			buttons: {
				Ok: function ()
				{
					$( this ).dialog( "close" );
				}
			},
			close: function ()
			{
				$('#ephd-dialog-info-icon-msg').html();
			}
		}
	);

	// AJAX DIALOG USED BY KB CONFIGURATION AND SETTINGS PAGES
	$('#ephd-ajax-in-progress').dialog({
		resizable: false,
		height: 70,
		width: 200,
		modal: false,
		autoOpen: false
	}).hide();

	/*
	// ToolTip
	ephd.on( 'click', '.ephd__option-tooltip__button', function(){
		let tooltip_on = $( this ).parent().find( '.ephd__option-tooltip__contents' ).css('display') == 'block';

		$( '.ephd__option-tooltip .ephd__option-tooltip__contents' ).fadeOut();

		if ( ! tooltip_on ) {
			$( this ).parent().find( '.ephd__option-tooltip__contents' ).fadeIn();
		}
	});

	// ToolTip for PRO content
	ephd.on( 'click', '.ephd-wp__feature-option-field--pro-disabled', function(){
		let tooltip_on = $( this ).find( '.ephd__option-tooltip__contents' ).css('display') == 'block';

		$( '.ephd__option-tooltip .ephd__option-tooltip__contents' ).fadeOut();

		if ( ! tooltip_on ) {
			$( this ).find( '.ephd__option-tooltip__contents' ).fadeIn();
		}
	});
	*/

	// SHOW INFO MESSAGES
	function ephd_admin_notification( $title, $message , $type ) {
		return '<div class="ephd-bottom-notice-message">' +
			'<div class="contents">' +
			'<span class="' + $type + '">' +
			($title ? '<h4>' + $title + '</h4>' : '' ) +
			($message ? '<p>' + $message + '</p>': '') +
			'</span>' +
			'</div>' +
			'<div class="ephd-close-notice ephdfa ephdfa-window-close"></div>' +
			'</div>';
	}

	//Admin Notice
	$('.ephd-notice-remind').on('click',function(e){
		e.preventDefault();
		$(this).parent().parent().remove();
	});

	//Dismiss ongoing notice
	$(document).on( 'click', '.ephd-notice-dismiss', function( event ) {
		event.preventDefault();
		$('.notice-'+$(this).data('notice-id')).slideUp();
		var postData = {
			action: 'ephd_dismiss_ongoing_notice',
			ephd_dismiss_id: $(this).data('notice-id')
		};
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: ajaxurl,
			data: postData
		});
	} );


	// Shared handlers for close buttons of Dialog Box Form
	$( document ).on( 'click', '.ephd-dialog-box-form .ephd-dbf__close, .ephd-dialog-box-form .ephd-dbf__footer__cancel', function() {
		$( this ).closest( '.ephd-dialog-box-form' ).toggleClass( 'ephd-dialog-box-form--active' );
	});
	$( document ).on( 'click', '.ephd-dialog-box-form .ephd-dbf__footer__accept__btn', function() {
		$( this ).closest( '.ephd-dialog-box-form' ).find( 'form' ).trigger( 'submit' );
	});

	// Confirm button for popup notification
	$( '.ephd-notification-box-basic__button-confirm' ).on( 'click', function () {
		if ( $( this ).attr( 'data-target' ).length > 0 ) {
			$( this ).closest( $( this ).attr( 'data-target' ) ).remove();
		}
	});

	// 'Choose Features' button on 'Need Help?' => 'Getting Started' page (possibly other similar links)
	$( '.ephd-admin__step-cta-box__link[data-target]' ).on( 'click', function () {

		// Get target keys
		let target_keys = $( this ).attr( 'data-target' );
		if ( typeof target_keys === 'undefined' || target_keys.length === 0 ) {
			return;
		}
		target_keys = target_keys.split( '__' );

		// Top panel item
		$( '.ephd-admin__top-panel__item[data-target="' + target_keys[0] + '"]' ).trigger( 'click' );

		// Secondary panel item
		if ( target_keys.length > 1 ) {
			setTimeout( function () {
				$( '.ephd-admin__secondary-panel__item[data-target="' + target_keys[1] + '"]' ).trigger( 'click' );
			}, 100 );
		}
	});

	function clear_bottom_notifications() {
		var bottom_message = $('body').find('.ephd-bottom-notice-message');
		if ( bottom_message.length ) {
			bottom_message.addClass( 'fadeOutDown' );
			setTimeout( function() {
				bottom_message.html( '' );
			}, 1000);
		}
	}

	function clear_message_after_set_time(){

		var ephd_timeout;
		if( $('.ephd-bottom-notice-message .contents' ).length > 0 ) {
			clearTimeout(ephd_timeout);

			//Add fadeout class to notice after set amount of time has passed.
			ephd_timeout = setTimeout(function () {
				clear_bottom_notifications();
			} , 10000);
		}
	}
	clear_message_after_set_time();


	$('body').on('click', '.ephd-wp__editor-link a', function(){
		$('body').append(`
			<div class="ephd-editor-popup" id="ephd-editor-popup">
				<iframe src="${$(this).prop('href')}" ></iframe>
			</div>
		`);

		return false;
	});

	$('body').on('click', '.ephd-editor-popup', function(){
		$(this).remove();
	});

	// Show/Hide Widget details
	$( document ).on( 'click', '.ephd-admin__widget-preview__view-details', function() {
		$( this ).closest( '.ephd-admin__widget-preview' ).find( '.ephd-admin__widget-preview__content').toggle();
	} );
});