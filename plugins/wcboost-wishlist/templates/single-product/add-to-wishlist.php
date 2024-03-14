<?php
/**
 * Template for displaying the add-to-wishlist button on the single product page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-wishlist.php.
 *
 * @author  WCBoost
 * @package WCBoost\Wishlist\Templates
 * @version 1.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

echo apply_filters(
	'wcboost_wishlist_single_add_to_wishlist_link', // WPCS: XSS ok.
	sprintf(
		'<a href="%s" data-quantity="%s" data-product_id="%d" data-variations="%s" class="%s" aria-label="%s">
			%s
			<span class="wcboost-wishlist-button__text">%s</span>
		</a>',
		esc_url( isset( $args['url'] ) ? $args['url'] : add_query_arg( [ 'add-to-wishlist' => $product->get_id() ] ) ),
		esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
		esc_attr( $product->get_id() ),
		esc_attr( isset( $args['variations_data'] ) ? json_encode( $args['variations_data'] ) : '' ),
		esc_attr( isset( $args['class'] ) ? $args['class'] : 'wcboost-wishlist-single-button wcboost-wishlist-button button' ),
		esc_attr( isset( $args['aria-label'] ) ? $args['aria-label'] : sprintf( __( 'Add %s to the wishlist', 'wcboost-wishlist' ), '&ldquo;' . $product->get_title() . '&rdquo;' ) ),
		empty( $icon ) ? '' : '<span class="wcboost-wishlist-button__icon">' . $icon . '</span>',
		esc_html( isset( $args['label'] ) ? $args['label'] : __( 'Add to wishlist', 'wcboost-wishlist' ) )
	),
	$args
);
