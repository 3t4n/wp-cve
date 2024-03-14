<?php
/**
 * Plugin Name: WooCommerce Mini Cart Block
 * Plugin URI: https://github.com/WordPress/blockons
 * Description: An WooCommerce Mini Cart Block.
 * Version: 1.1.0
 * Author: Kaira
 *
 * @package blockons
 */
defined( 'ABSPATH' ) || exit;

/**
 * Register Block Assets
 */
function blockons_wc_mini_cart_register_block() {
	// Register the block by passing the location of block.json.
	register_block_type( __DIR__ );

	if ( function_exists( 'wp_set_script_translations' ) ) {
		wp_set_script_translations( 'blockons-wc-mini-cart-editor-script', 'blockons', BLOCKONS_PLUGIN_DIR . 'lang' );
	}
}
add_action( 'init', 'blockons_wc_mini_cart_register_block' );

/**
 * Add Cart Items and Mini Cart to footer (hidden)
 */
// add_filter('safe_style_css', function( $styles ) {
//     $styles[] = 'display';
// 	return $styles;
// });
function blockons_add_footer_wc_minicart() {
	if (has_block('blockons/wc-mini-cart')) {
		$allowed_html = array(
			'div' => array('class' => array(), 'id' => array(), 'style' => array()),
			'a' => array('class' => array(), 'href' => array()),
			'span' => array('class' => array()),
		);
		$html = '<div class="blockons-hidden" style="width: 0; height: 0; overflow: hidden;">' . blockons_wc_minicart_item() . '</div>';

		// Add Cart & Mini Cart to site footer
		echo wp_kses($html ,$allowed_html);

		$html2 = '<div class="blockons-hidden" style="width: 0; height: 0; overflow: hidden;">' . blockons_wc_cart_amount() . '<div class="blockons-mini-crt"><div class="widget_shopping_cart_content">';
		$html3 = '</div></div></div>';

		echo wp_kses($html2 ,$allowed_html);
			woocommerce_mini_cart();
		echo wp_kses($html3 ,$allowed_html);
	}
	
	// the_widget( 'WC_Widget_Cart', array( 'title' => ''), array( 'before_widget' => '<div class="blockons-hidden" style="width: 0; height: 0; overflow: hidden;"><div class="blockons-mini-crt">', 'after_widget' => '</div></div>' ) );
}
add_action('wp_footer', 'blockons_add_footer_wc_minicart' );

/**
 * Update Cart Item AJAX
 */
function blockons_woocommerce_cart_fragments( $fragments ) {
	$fragments['div.blockons-cart-amnt'] = blockons_wc_minicart_item();
	$fragments['span.blockons-crtamnt'] = blockons_wc_cart_amount();
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'blockons_woocommerce_cart_fragments' );

/**
 * Create Cart Item html
 */
function blockons_wc_minicart_item() {
	$cart_itemno = WC()->cart->get_cart_contents_count();
	$item_count_text = sprintf(
		/* translators: number of items in the mini cart. */
		_n( '%d item', '%d items', $cart_itemno, 'blockons' ), $cart_itemno
	);
	$has_items = $cart_itemno > 0 ? 'has-items' : 'no-items';

	$mini_cart = '<div class="blockons-cart-amnt ' . sanitize_html_class( $has_items ) . '">
						<span class="amount">' . wp_kses_data( WC()->cart->get_cart_subtotal() ) . '</span>
						<span class="count">' . esc_html( '(' . $item_count_text . ')' ) . '</span>
					</div>';
	return $mini_cart;
}
function blockons_wc_cart_amount() {
	$cart_itemno = WC()->cart->get_cart_contents_count();
	$cart_amount = '<span class="blockons-crtamnt">' . esc_html($cart_itemno) . '</span>';
	return $cart_amount;
}
