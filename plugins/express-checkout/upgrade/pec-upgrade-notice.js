;(function ( $, window, document ) {
	'use strict';

	// Plugin rows.
	let $notice_row = $( 'tr#pec-migrate-notice' );
	let $pec_row   = $notice_row.prev();
	let $ppcp_row   = $( 'tr[data-slug="smart-paypal-checkout-for-woocommerce"]' );

	$pec_row.toggleClass( 'hide-border', true );

	// Check whether PayPal Payments is installed.
	let is_paypal_payments_installed = $ppcp_row.length > 0;
	let is_paypal_payments_active    = is_paypal_payments_installed && $ppcp_row.hasClass( 'active' );

	let updateUI = function() {
		// Dynamically update plugin activation link to handle plugin folder renames.
		if ( is_paypal_payments_installed > 0 ) {
			$notice_row.find( 'a#pec-activate-paypal-payments' ).attr( 'href', $ppcp_row.find( 'span.activate a' ).attr( 'href' ) );
		}

		// Hide notice/buttons conditionally.
		$notice_row.find( 'a#pec-install-paypal-payments' ).toggle( ! is_paypal_payments_installed );
		$notice_row.find( 'a#pec-activate-paypal-payments' ).toggle( is_paypal_payments_installed && ! is_paypal_payments_active );

		// Display buttons area.
		$notice_row.find( '.pec-notice-buttons' ).removeClass( 'hidden' );
	};

	// Handle delete event for PayPal Payments.
	$( document ).on( 'wp-plugin-delete-success', function( event, response ) {
		if ( 'smart-paypal-checkout-for-woocommerce' === response.slug ) {
			is_paypal_payments_installed = false;
			is_paypal_payments_active    = false;
			updateUI();
		}
	} );

	// Change button text when install link is clicked.
	$notice_row.find( '#pec-install-paypal-payments' ).click( function( e ) {
		e.preventDefault();
		$( this ).addClass( 'updating-message' ).text( 'Installing...' );
		const install_link = $( this ).attr('href');
		setTimeout( function(){
			window.location = install_link;
		}, 50 );
	} );

	// Dismiss button.
	$( document).on( 'click', '#pec-migrate-notice button.notice-dismiss', function( e ) {
		$.ajax(
			{
				url: ajaxurl,
				method: 'POST',
				data: {
					action: 'pec_dismiss_pec_upgrade_notice',
					_ajax_nonce: $notice_row.attr( 'data-dismiss-nonce' )
				},
				dataType: 'json',
				success: function( res ) {
					$pec_row.removeClass( 'hide-border' );
				}
			}
		);
	} );

	updateUI();

})( jQuery, window, document );
