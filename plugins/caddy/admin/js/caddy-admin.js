(function( $ ) {
	'use strict';

	// Dismiss the welcome notice
	$( document ).on( 'click', '.cc-welcome-notice .notice-dismiss', function() {
		cc_dismiss_welcome_notice();
	} );

	// Dismiss the opt-in notice
	$( document ).on( 'click', '.cc-optin-notice .notice-dismiss', function() {
		cc_dismiss_optin_notice();
	} );

	// Dismiss the opt-in notice when form submitted
	$( '#caddy-email-signup' ).submit( function( e ) {
		cc_dismiss_optin_notice();
	} );

	/* Dismiss welcome notice screen function */
	function cc_dismiss_welcome_notice() {
		// AJAX Request to dismiss the welcome notice
		var data = {
			action: 'dismiss_welcome_notice',
			nonce: caddyAjaxObject.nonce,
		};

		$.ajax( {
			type: 'post',
			url: caddyAjaxObject.ajaxurl,
			data: data,
			success: function( response ) {
			}
		} );
	}

	/* Dismiss the opt-in notice */
	function cc_dismiss_optin_notice() {
		// AJAX Request to dismiss the welcome notice
		var data = {
			action: 'dismiss_optin_notice',
			nonce: caddyAjaxObject.nonce,
		};
		$.ajax( {
			type: 'post',
			url: caddyAjaxObject.ajaxurl,
			data: data,
			success: function( response ) {
			}
		} );
	}
	
	$(document).ready(function() {
		$('.copy-shortcode-button').click(function() {
			var $button = $(this);
			var $temp = $("<input>");
			$("body").append($temp);
			$temp.val($('#cc_cart_widget_shortcode').val()).select();
			document.execCommand("copy");
			$temp.remove();
	
			// Change button text to "Copied" and revert back after 2 seconds
			$button.text('Copied!').addClass('button-copied');
			setTimeout(function() {
				$button.html('<span class="dashicons dashicons-admin-page"></span>').removeClass('button-copied');
			}, 2000);
		});
	});

})( jQuery );
