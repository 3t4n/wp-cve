<?php
/**
 *
 * This template can be overridden by copying it to yourtheme/templates/waitlist-woocommerce/xoo-wl-button.php.
 *
 * HOWEVER, on occasion we will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen.
 * @see     https://docs.xootix.com/waitlist-for-woocommerce/
 * @version 2.4
 */

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}


echo apply_filters(
	'xoo_wl_waitlist_button',
	sprintf(
		'<button type="button" data-product_id="%s" class="%s" %s>%s</button>',
		esc_attr( isset( $args['id'] ) ? $args['id'] : 0 ),
		'xoo-wl-action-btn xoo-wl-open-form-btn '.esc_attr( isset( $args['class'] ) ? $args['class'] : 'button xoo-wl-open-popup' ),
		isset( $attributes ) ? $attributes : '',
		esc_attr( isset( $args['text'] ) ? $args['text'] : xoo_wl_helper()->get_general_option( 'txt-btn' ) )
	),
	$args
);
