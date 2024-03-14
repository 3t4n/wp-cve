<?php

// add shortcode hooks
add_shortcode( 'tx_woo_compare_table', 'tx_woocompare_shortcode' );

/**
 * Renders compare list shortcode.
 *
 * @since 1.0.0
 * @shortcode tx_woo_compare_table
 */
function tx_woocompare_shortcode( $atts ) {

	wp_enqueue_style( 'tablesaw' );
	wp_enqueue_script( 'tablesaw-init' );

	return tx_woocompare_list_render();
}