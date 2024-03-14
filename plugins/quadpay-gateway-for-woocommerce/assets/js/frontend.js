// Zip price update for variable product
jQuery( function() {
	jQuery( '.variations_form' ).each( function() {
		jQuery(this).on( 'found_variation', function( event, variation ) {
			jQuery( 'quadpay-widget-v3,quadpay-widget').attr( 'amount', variation.display_price );
		});
	});
});
