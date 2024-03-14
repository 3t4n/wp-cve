<?php
/**
 * Compatibility class for WooCommerce Multi Currency plugin
 *
 * @package CTXFeed\V5\Compatibility
 */

namespace CTXFeed\V5\Compatibility;

/**
 * Class WOOCSCompatibility
 *
 * @package CTXFeed\V5\Compatibility
 */
class WOOCSCompatibility {

	/**
	 * PolylangCompatibility Constructor.
	 */
	public function __construct() {
		add_action( 'before_woo_feed_generate_batch_data', array( $this, 'switch_currency' ), 10, 1 );
		add_action( 'after_woo_feed_generate_batch_data', array( $this, 'restore_currency' ), 10, 1 );

		// Add currency suffix to product link.
		add_filter( 'woo_feed_filter_product_link', array( $this, 'get_product_link_with_suffix' ), 10, 3 );
	}

	/**
	 * Switch currency before feed generation
	 *
	 * @param \CTXFeed\V5\Utility\Config $config feed config array.
	 */
	public function switch_currency( $config ) {
		if ( class_exists( 'woocommerce_wpml' ) && wcml_is_multi_currency_on() ) {
			// when wpml and woocs both is installed and wpml is enabled, woocs change currency by it's own filter, filter is removed here
			global $WOOCS;// phpcs:ignore
			remove_filter( 'woocommerce_currency', array( $WOOCS, 'get_woocommerce_currency' ), 9999 );// phpcs:ignore
		}

		global $WOOCS;// phpcs:ignore
		$currency_code = $WOOCS->default_currency;// phpcs:ignore

		if ( $currency_code !== $config->get_feed_currency() ) {
			$WOOCS->set_currency( $config->get_feed_currency() );// phpcs:ignore
		}

		// WooCommerce Out of Stock visibility override
		if ( !$config->get_outofstock_visibility() ) {
            return;
        }

        // just return false as wc expect the value should be 'yes' with eqeqeq (===) operator.
        add_filter( 'pre_option_woocommerce_hide_out_of_stock_items', '__return_false', 999 );
	}

	/**
	 * Restore currency after feed generation
	 *
	 * @param \CTXFeed\V5\Utility\Config $config feed config array.
	 */
	public function restore_currency( $config ) {
		global $WOOCS;// phpcs:ignore
		$currency_code = $WOOCS->default_currency;// phpcs:ignore

		if ( $currency_code !== $config->get_feed_currency() ) {
			$WOOCS->set_currency( $currency_code );// phpcs:ignore
		}

		// WooCommerce Out of Stock visibility override
		if ( !$config->get_outofstock_visibility() ) {
            return;
        }

        remove_filter( 'pre_option_woocommerce_hide_out_of_stock_items', '__return_false', 999 );
	}

	/**
	 * Get product link with currency suffix.
	 *
	 * @param string                     $link product link.
	 * @param \WC_Product                $product product object.
	 * @param \CTXFeed\V5\Utility\Config $config config object.
	 * @return string
	 */
	public function get_product_link_with_suffix( $link, $product, $config ) { // phpcs:ignore
		$jointer         = substr( $link,  - 1 ) == '/' ? '?' : '&';
		$currency_suffix = $jointer . 'currency=' . $config->get_feed_currency();

		$link .= $currency_suffix;

		return $link;
	}


}
