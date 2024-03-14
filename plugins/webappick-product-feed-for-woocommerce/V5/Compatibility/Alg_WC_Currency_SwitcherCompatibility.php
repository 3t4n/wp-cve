<?php
/**
 * Compatibility class for Alg_WC_Currency_SwitcherCompatibility plugin
 *
 * @package CTXFeed\V5\Compatibility
 */

namespace CTXFeed\V5\Compatibility;

/**
 * Class Alg_WC_Currency_SwitcherCompatibility
 *
 * @package CTXFeed\V5\Compatibility
 */
class Alg_WC_Currency_SwitcherCompatibility {

	/**
	 * Alg_WC_Currency_SwitcherCompatibility Constructor.
	 */
	public function __construct() {
		add_filter( 'woo_feed_filter_product_regular_price', array( $this, 'get_converted_price' ), 10, 5 );
		add_filter( 'woo_feed_filter_product_price', array( $this, 'get_converted_price' ), 10, 5 );
		add_filter( 'woo_feed_filter_product_sale_price', array( $this, 'get_converted_price' ), 10, 5 );
		add_filter( 'woo_feed_filter_product_regular_price_with_tax', array( $this, 'get_converted_price' ), 10, 5 );
		add_filter( 'woo_feed_filter_product_price_with_tax', array( $this, 'get_converted_price' ), 10, 5 );
		add_filter( 'woo_feed_filter_product_sale_price_with_tax', array( $this, 'get_converted_price' ), 10, 5 );

		// Add currency suffix to product link.
		add_filter( 'woo_feed_filter_product_link', array( $this, 'get_product_link_with_suffix' ), 10, 3 );
	}

	/**
	 * Currency Convert for Currency Switcher
	 *
	 * @param int                        $price product price.
	 * @param \WC_Product         $product product object.
	 * @param \CTXFeed\V5\Utility\Config $config config object.
	 * @param bool                       $with_tax price with tax or without tax.
	 * @param string                     $price_type price type regular_price, price, sale_price.
     * @return float
	 */
	public function get_converted_price( $price, $product, $config, $with_tax, $price_type ) {// phpcs:ignore
		if ( $config->get_feed_currency() !== get_woocommerce_currency() ) {
			if ( ! empty( $price ) ) {
				$price = alg_get_product_price_by_currency( $price, $config->get_feed_currency() );
			}
		}

		return $price;
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
		$currency_suffix = $jointer . 'alg_currency=' . $config->get_feed_currency();

		$link .= $currency_suffix;

		return $link;
	}

}
