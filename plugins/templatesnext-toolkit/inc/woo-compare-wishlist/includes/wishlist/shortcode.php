<?php

// add shortcode hooks
add_shortcode( 'tx_woo_wishlist_table', 'tx_woowishlist_shortcode' );

/**
 * Renders wishlist shortcode.
 *
 * @since 1.0.0
 *
 * @param array $atts The array of shortcode attributes.
 */
function tx_woowishlist_shortcode( $atts ) {

	$atts = apply_filters( 'shortcode_atts_tx_woo_wishlist_table', $atts );

	return tx_woowishlist_render( $atts );
}