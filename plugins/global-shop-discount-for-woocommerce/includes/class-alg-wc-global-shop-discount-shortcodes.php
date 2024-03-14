<?php
/**
 * Global Shop Discount for WooCommerce - Shortcodes Class
 *
 * @version 1.7.0
 * @since   1.7.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Global_Shop_Discount_Shortcodes' ) ) :

class Alg_WC_Global_Shop_Discount_Shortcodes {

	/**
	 * Constructor.
	 *
	 * @version 1.7.0
	 * @since   1.7.0
	 */
	function __construct() {
		add_shortcode( 'alg_wc_gsd_products', array( $this, 'products_shortcode' ) );
	}

	/**
	 * `[alg_wc_gsd_products]` shortcode.
	 *
	 * @version 1.7.0
	 * @since   1.5.1
	 *
	 * @todo    (dev) use `get_gsd_product_ids()`
	 * @todo    (dev) `$atts`: `block_size`?
	 * @todo    (dev) `$atts`: `transient_expiration`?
	 * @todo    (dev) use `wc_get_products()`
	 */
	function products_shortcode( $atts ) {

		$product_ids_on_sale = false;
		$do_use_transient    = ( isset( $atts['use_transient'] ) && filter_var( $atts['use_transient'], FILTER_VALIDATE_BOOLEAN ) );

		// Try cache
		if ( $do_use_transient ) {
			$product_ids_on_sale = get_transient( 'alg_wc_gsd_products_onsale' );
		}

		// Get on-sale products
		if ( false === $product_ids_on_sale ) {

			$product_ids_on_sale = array();
			$offset              = 0;
			$block_size          = 1024;
			while ( true ) {
				$query_args = array(
					'post_type'      => 'product',
					'fields'         => 'ids',
					'offset'         => $offset,
					'posts_per_page' => $block_size,
				);
				$query = new WP_Query( $query_args );
				if ( ! $query->have_posts() ) {
					break;
				}
				foreach ( $query->posts as $product_id ) {
					if ( ( $product = wc_get_product( $product_id ) ) && $product->is_on_sale() ) {
						$product_ids_on_sale[] = $product_id;
					}
				}
				$offset += $block_size;
			}

			// Save cache
			if ( $do_use_transient ) {
				set_transient( 'alg_wc_gsd_products_onsale', $product_ids_on_sale, DAY_IN_SECONDS );
			}

		}

		// Pass additional atts
		$_atts = '';
		if  ( ! empty( $atts ) ) {
			$_atts = ' ' . implode( ' ', array_map(
				function ( $v, $k ) {
					return sprintf( '%s="%s"', $k, $v );
				},
				$atts,
				array_keys( $atts )
			) );
		}

		// Run [products] shortcode
		return ( ! empty( $product_ids_on_sale ) ?
			do_shortcode( '[products' . $_atts . ' ids="' . implode( ',', $product_ids_on_sale ) . '"]' ) :
			( isset( $atts['on_empty'] ) ? $atts['on_empty'] : '' )
		);

	}

}

endif;

return new Alg_WC_Global_Shop_Discount_Shortcodes();
