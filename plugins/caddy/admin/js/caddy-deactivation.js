(function( $ ) {
	'use strict';

	// Put original Caddy deactivation URL to the Skip & deactivate button
	var CaddyUrlRedirect = document.querySelector( '[data-slug="caddy"] .deactivate a' ).getAttribute( 'href' );
	$( '.cc-skip-deactivate-button' ).attr( 'href', CaddyUrlRedirect );

	// Replace the thickbox url to the Caddy deactivation link
	var caddyDeactivateButton = $( '[data-slug="caddy"] .deactivate a' );
	// caddyDeactivateButton.attr( 'href', '#TB_inline?&width=600&height=550&inlineId=caddy-deactivation-survey-wrap' );
	// caddyDeactivateButton.addClass( 'thickbox' );

	$( document ).on( 'click', '#deactivate-caddy', function( event ) {
		event.preventDefault();
		caddyDeactivateButton.attr( 'href', '#TB_inline?&width=600&height=550&inlineId=caddy-deactivation-survey-wrap' );
		caddyDeactivateButton.addClass( 'thickbox' );
		caddyDeactivateButton.trigger( 'click' );
		$( '#TB_window' ).addClass( 'cc-deactivation' );
	} );

	$( 'body' ).on( '.cc-deactivation thickbox:removed', function( event ) {
		event.preventDefault();
		caddyDeactivateButton.removeClass( 'thickbox' );
		caddyDeactivateButton.attr( 'href', CaddyUrlRedirect );
	} );

	// Deactivation form survey radio button value changes
	$( document ).on( 'change', 'input[name="caddy-survey-radios"]', function( event ) {
		$( '.caddy-survey-extra-field' ).show();
	} );

	// Deactivation form survey submit
	$( '.deactivation-survey-form' ).submit( function( e ) {

		//prevent Default functionality
		e.preventDefault();

		var popUpSelectedReason = $( 'input[name="caddy-survey-radios"]:checked' ).closest( '.caddy-field-description' ).find( 'span' ).text(),
			deactivationReason = $( '.caddy-survey-extra-field .user-reason' ).val(),
			contactMeCheckbox = '';

		if ( $( '.caddy-contact-for-issue' ).prop( 'checked' ) === true ) {
			contactMeCheckbox = 'yes';
		}

		// AJAX Request to submit deactivation form data
		var data = {
			action: 'cc_submit_deactivation_form_data',
			nonce: caddyAjaxObject.nonce,
			popUpSelectedReason: popUpSelectedReason,
			deactivationReason: deactivationReason,
			contactMeCheckbox: contactMeCheckbox
		};

		$.ajax( {
			type: 'post',
			url: caddyAjaxObject.ajaxurl,
			data: data,
			success: function( response ) {
				window.location.href = CaddyUrlRedirect;
			}
		} );

	} );

	// Cancel the deactivation form survey
	$( document ).on( 'click', '.cc-cancel-survey', function( event ) {
		$( '#TB_window .tb-close-icon' ).trigger( 'click' );
	} );

})( jQuery );
