<?php
/**
 * WPML Multi currency.
 *
 * @package CTXFeed\V5\Price
 */

namespace CTXFeed\V5\Compatibility;

use WCML\MultiCurrency\Geolocation;

/**
 * Class woocommerce_wpmlCompatibility
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Compatibility
 * @author     Ohidul Islam <wahid0003@gmail.com>
 * @link       https://webappick.com
 * @license    https://opensource.org/licenses/gpl-license.php GNU Public License
 * @category   MyCategory
 */
class woocommerce_wpmlCompatibility {

	/**
	 * WCMLCurrency constructor.
	 */
	public function __construct() {

		add_filter( 'woo_feed_filter_product_regular_price', array( $this, 'wcml_currency_convert' ), 99, 5 );
		add_filter( 'woo_feed_filter_product_price', array( $this, 'wcml_currency_convert' ), 99, 5 );
		add_filter( 'woo_feed_filter_product_sale_price', array( $this, 'wcml_currency_convert' ), 99, 5 );
		add_filter( 'woo_feed_filter_product_regular_price_with_tax', array(
			$this,
			'wcml_currency_convert'
		), 99, 5 );
		add_filter( 'woo_feed_filter_product_price_with_tax', array( $this, 'wcml_currency_convert' ), 99, 5 );
		add_filter( 'woo_feed_filter_product_sale_price_with_tax', array( $this, 'wcml_currency_convert' ), 99, 5 );
	}

	/**
	 * Convert the price to the feed currency.
	 *
	 * @param float $price Price.
	 * @param object $product Product.
	 * @param \CTXFeed\V5\Utility\Config $config Config.
	 *
	 * @return float
	 */
	public function wcml_currency_convert( $price, $product, $config, $with_tax, $price_type ) {//phpcs:ignore

		$original_price = $price;
		$currency       = $config->get_feed_currency(); //phpcs:ignore
		// Use WPML's function to convert the price
		$converted_price = apply_filters( 'wcml_raw_price_amount', $price, $currency );
		if ( empty( $converted_price ) ) {
			$converted_price = $original_price;
		}

		// If product has custom price, use that instead.
		if ( get_post_meta( $product->get_id(), '_wcml_custom_prices_status', true ) ) {
			$custom_price = get_post_meta( $product->get_id(), '_' . $price_type . '_' . $currency , true);
			if ( ! empty( $custom_price ) ) {
				$converted_price = $custom_price;
			}
		}

		return $converted_price;
	}

}
