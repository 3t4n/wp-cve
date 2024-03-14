<?php
/**
 * Addon for Ultimate WooCommerce Auction Pro
 *
 * @author   Codection
 * @category Addons
 * @package  Products Restricted Users from WooCommerce
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPRU_Ultimate_WooCommerce_Auction_Pro {
	/**
	 * Constructor
	 **/
	public function __construct() {
	}

	/**
	 * Hooks declaration
	 **/
	public function hooks() {
		add_action( 'wp_head', array( $this, 'maybe_remove_add_to_cart_actions_single' ), PHP_INT_MAX );
		add_action( 'woocommerce_shop_loop', array( $this, 'maybe_remove_add_to_cart_actions_archive' ), PHP_INT_MAX );
	}

	/**
	 * Maybe remove hooks if is single-product view
	 **/
	public function maybe_remove_add_to_cart_actions_single() {
		if ( ! is_product() ) {
			return;
		}

		$product = wc_get_product( get_the_ID() );
		if ( $product->get_type() !== 'auction' ) {
			return;
		}

		$wpru_filters = new WPRU_Filters();
		if ( $wpru_filters->excluded_roles() ) {
			return;
		}

		$restricted_product = new WPRU_Restricted_Product( $product->get_id() );
		if ( $restricted_product->is_purchasable() ) {
			return;
		}

		remove_action( 'woocommerce_auction_add_to_cart', array( UWA_Front::get_instance(), 'woocommerce_uwa_auction_bid' ), 25 );
		remove_action( 'woocommerce_single_product_summary', array( UWA_Front::get_instance(), 'woocommerce_uwa_auction_bid' ), 25 );
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
	}

	/**
	 * Maybe remove hooks if is archive-product view
	 **/
	public function maybe_remove_add_to_cart_actions_archive() {
		global $product;

		if ( $product->get_type() !== 'auction' ) {
			return;
		}

		$wpru_filters = new WPRU_Filters();
		if ( $wpru_filters->excluded_roles() ) {
			return;
		}

		$restricted_product = new WPRU_Restricted_Product( $product->get_id() );
		if ( $restricted_product->is_purchasable() ) {
			return;
		}

		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
	}
}

add_action(
	'before_woocommerce_init',
	function() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		if ( ! is_plugin_active( 'ultimate-woocommerce-auction-pro/ultimate-woocommerce-auction-pro.php' ) ) {
			return;
		}

		$wpru_uwap = new WPRU_Ultimate_WooCommerce_Auction_Pro();
		$wpru_uwap->hooks();
	}
);
