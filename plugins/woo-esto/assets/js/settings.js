jQuery( function( $ ) {
	$( '#woocommerce_esto_use_secondary_endpoint_ee' ).on( 'click', function() {
		$( '#woocommerce_esto_shop_id_ee, #woocommerce_esto_secret_key_ee' ).closest( 'tr' ).toggleClass( 'hidden' );
	} );

	$( '#woocommerce_esto_use_secondary_endpoint_lv' ).on( 'click', function() {
		$( '#woocommerce_esto_shop_id_lv, #woocommerce_esto_secret_key_lv' ).closest( 'tr' ).toggleClass( 'hidden' );
	} );

	$( '#woocommerce_esto_use_secondary_endpoint_lt' ).on( 'click', function() {
		$( '#woocommerce_esto_shop_id_lt, #woocommerce_esto_secret_key_lt' ).closest( 'tr' ).toggleClass( 'hidden' );
	} );
} );
