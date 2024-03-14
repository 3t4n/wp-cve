<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$uws_extension = fw()->extensions->get( 'shortcodes' )->get_shortcode( 'uws_add_to_cart' );

wp_enqueue_style(
	'uws-add-to-cart-style',
	$uws_extension->locate_URI( '/static/css/uws-add-to-cart.css' ),
	array()
);

