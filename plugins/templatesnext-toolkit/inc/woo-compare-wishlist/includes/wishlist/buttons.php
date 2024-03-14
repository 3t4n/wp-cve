<?php

// register action hooks

add_action( 'woocommerce_after_shop_loop_item', 'tx_woowishlist_add_button_loop', 12 );

add_action( 'woocommerce_single_product_summary', 'tx_woowishlist_add_button_single', 35 );

/**
 * Renders appropriate button for a loop product.
 *
 * @since 1.0.0
 * @action woocommerce_after_shop_loop_item
 */
function tx_woowishlist_add_button_loop( $args ) {

	if ( 'yes' === get_option( 'tx_woowishlist_show_in_catalog' ) ) {

		tx_woowishlist_add_button( $args );
	}
}

/**
 * Renders appropriate button for a product.
 *
 * @since 1.0.0
 */
function tx_woowishlist_add_button( $args ) {

	$id      = get_the_ID();
	$id      = tx_wc_compare_wishlist()->get_original_product_id( $id );
	$classes = array( 'button', 'tm-woowishlist-button', 'btn', 'btn-default' );
	$nonce   = wp_create_nonce( 'tx_woowishlist' . $id );

	if ( in_array( $id, tx_woowishlist_get_list() ) ) {

		$text      = get_option( 'tx_woowishlist_added_text', __( 'Added to Wishlist', 'tx' ) );
		$classes[] = ' in_wishlist';

	} else {

		$text = get_option( 'tx_woowishlist_add_text', __( 'Add to wishlist', 'tx' ) );
	}
	$text      = '<span class="tx_woowishlist_product_actions_tip"><span class="text">' . esc_html( $text ) . '</span></span>';
	$preloader = apply_filters( 'tx_wc_compare_wishlist_button_preloader', '' );

	if( $single = ( is_array( $args ) && isset( $args['single'] ) && $args['single'] ) ) {

		$classes[] = 'tm-woowishlist-button-single';
	}
	$html = sprintf( '<button type="button" class="%s" data-id="%s" data-nonce="%s" title="'. esc_attr(get_option( 'tx_woowishlist_add_text', __( 'Add to wishlist', 'tx' ) )) .'">%s</button>', implode( ' ', $classes ), $id, $nonce, $text . $preloader );

	echo apply_filters( 'tx_woowishlist_button', $html, $classes, $id, $nonce, $text, $preloader );

	if ( in_array( $id, tx_woowishlist_get_list() ) && $single ) {

		echo tx_woowishlist_page_button( array( 'btn-primary', 'alt' ) );
	}
}

/**
 * Renders appropriate button for a single product.
 *
 * @since 1.0.0
 * @action woocommerce_single_product_summary
 */
function tx_woowishlist_add_button_single( $args ) {

	if ( 'yes' === get_option( 'tx_woowishlist_show_in_single' ) ) {

		if( empty( $args ) ) {

			$args = array();
		}
		$args['single'] = true;

		tx_woowishlist_add_button( $args );
	}
}

/**
 * Renders wishlist page button for a product.
 *
 * @since 1.0.0
 */
function tx_woowishlist_page_button( $classes = array() ) {

	$link = tx_woowishlist_get_page_link();

	if( ! $link ) {

		return;
	}
	$classes = array_merge( $classes,  array( 'button', 'tm-woowishlist-page-button', 'btn' ) );
	$text    = get_option( 'tx_woowishlist_page_btn_text', __( 'Go to my wishlist', 'tx' ) );
	$html    = sprintf( '<a class="%s" href="%s">%s</a>', implode( ' ', $classes ), $link, $text );

	return apply_filters( 'tx_woowishlist_page_button', $html, $classes, $link, $text );
}