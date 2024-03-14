<?php
/**
 * Global Shop Discount for WooCommerce - Functions
 *
 * @version 1.9.2
 * @since   1.6.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'alg_wc_gsd_get_product_discount_groups' ) ) {
	/**
	 * alg_wc_gsd_get_product_discount_groups.
	 *
	 * @version 1.9.2
	 * @since   1.9.2
	 */
	function alg_wc_gsd_get_product_discount_groups( $product ) {
		return ( function_exists( 'alg_wc_global_shop_discount' ) ?
			alg_wc_global_shop_discount()->core->get_product_discount_groups( $product ) :
			false );
	}
}

if ( ! function_exists( 'alg_wc_gsd_is_discount_product' ) ) {
	/**
	 * alg_wc_gsd_is_discount_product.
	 *
	 * @version 1.9.2
	 * @since   1.9.2
	 */
	function alg_wc_gsd_is_discount_product( $product ) {
		return ( function_exists( 'alg_wc_global_shop_discount' ) ?
			alg_wc_global_shop_discount()->core->is_gsd_product( $product ) :
			false );
	}
}

if ( ! function_exists( 'alg_wc_gsd_get_product_ids' ) ) {
	/**
	 * alg_wc_gsd_get_product_ids.
	 *
	 * @version 1.6.0
	 * @since   1.6.0
	 */
	function alg_wc_gsd_get_product_ids( $product_query_args = array( 'limit' => -1 ), $incl_on_sale = true, $use_transient = false ) {
		return ( function_exists( 'alg_wc_global_shop_discount' ) ?
			alg_wc_global_shop_discount()->core->get_gsd_product_ids( $product_query_args, $incl_on_sale, $use_transient ) :
			false );
	}
}
