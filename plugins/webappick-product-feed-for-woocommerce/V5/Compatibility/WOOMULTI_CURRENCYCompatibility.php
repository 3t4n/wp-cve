<?php
/**
 * Compatibility class for WOOMULTI_CURRENCYCompatibility plugin
 *
 * @package CTXFeed\V5\Compatibility
 */

namespace CTXFeed\V5\Compatibility;

use Automattic\Jetpack\Status\Cache;

/**
 * Class WOOMULTI_CURRENCYCompatibility
 *
 * MultiCurrency by VillaTheme pro version support.
 *
 * @package CTXFeed\V5\Compatibility
 */
class WOOMULTI_CURRENCYCompatibility {
	/**
	 * WOOMULTI_CURRENCYCompatibility Constructor.
	 */
	public function __construct() {
		// Switch currency before feed generation.
		add_action( 'before_woo_feed_get_product_information', array( $this, 'switch_currency' ), 999, 1 );
		// Switch currency before feed generation.
		add_action( 'before_woo_feed_generate_batch_data', array( $this, 'switch_currency' ), 10, 1 );
		// Restore currency after feed generation.
		add_action( 'after_woo_feed_generate_batch_data', array( $this, 'restore_currency' ), 10, 1 );

		// Get price with currency conversion.
		add_filter( 'woo_feed_filter_product_regular_price', array( $this, 'get_converted_price' ), 10, 5 );
		add_filter( 'woo_feed_filter_product_price', array( $this, 'get_converted_price' ), 10, 5 );
		add_filter( 'woo_feed_filter_product_sale_price', array( $this, 'get_converted_price' ), 10, 5 );
		add_filter( 'woo_feed_filter_product_regular_price_with_tax', array( $this, 'get_converted_price' ), 10, 5 );
		add_filter( 'woo_feed_filter_product_price_with_tax', array( $this, 'get_converted_price' ), 10, 5 );
		add_filter( 'woo_feed_filter_product_sale_price_with_tax', array( $this, 'get_converted_price' ), 10, 5 );
	}

	/**
	 * Switch currency before feed generation
	 *
	 * @param \CTXFeed\V5\Utility\Config $config feed config array.
	 */
	public function switch_currency( $config ) {
		add_filter( 'wmc_get_default_currency', function ( $currency ) use ( $config ) {
			Cache::set( 'woo_feed_currency', $currency );

			return $config->get_feed_currency();
		} );
		$woo_multi_currency = new \WOOMULTI_CURRENCY_Data();
		$data               = $woo_multi_currency::get_ins();
		$default_currency   = $data->get_default_currency();

		if ( $default_currency !== $config->get_feed_currency() ) {
			$data->set_current_currency( $config->get_feed_currency() );
		} else {
			$data->set_current_currency( $default_currency );
		}
	}

	/**
	 * Restore currency after feed generation
	 *
	 * @param \CTXFeed\V5\Utility\Config $config feed config array.
	 */
	public function restore_currency( $config ) { // phpcs:ignore

		add_filter( 'wmc_get_default_currency', function ( $currency ) {
			return Cache::get( 'woo_feed_currency' );
		} );

		$woo_multi_currency = new \WOOMULTI_CURRENCY_Data();
		$data               = $woo_multi_currency::get_ins();
		$default_currency   = Cache::get( 'woo_feed_currency' );
		if ( ! $default_currency ) {
			$default_currency = $data->get_default_currency();
		}

		$data->set_current_currency( $default_currency );
	}

	/**
	 * Currency Convert for Currency Switcher
	 *
	 * @param int $price product price.
	 * @param \WC_Product $product product object.
	 * @param \CTXFeed\V5\Utility\Config $config config object.
	 * @param bool $with_tax price with tax or without tax.
	 * @param string $price_type price type regular_price, price, sale_price.
	 *
	 * @return int
	 */
	public function get_converted_price( $price, $product, $config, $with_tax, $price_type ) {// phpcs:ignore

		$original_price = $price;
		if ( $config->get_feed_currency() !== Cache::get( 'woo_feed_currency' ) ) {
			$price               = $main_price = wmc_get_price( $price, $config->get_feed_currency() );
			$wmc_currency_params = get_option( 'woo_multi_currency_params' );

			$regular_price = wmc_adjust_fixed_price( json_decode( get_post_meta( $product->get_id(), '_regular_price_wmcp', true ), true ) );
			$sale_price    = wmc_adjust_fixed_price( json_decode( get_post_meta( $product->get_id(), '_sale_price_wmcp', true ), true ) );

			if (
				isset( $wmc_currency_params['enable_fixed_price'] )
				&& $wmc_currency_params['enable_fixed_price'] === 1
			) {
				$price = $this->get_curreny_fixed_price( $price, $product, $config, $regular_price, $sale_price, $price_type );

				if ( ! $price ) {
					$price = $main_price;
				}
			}
		}

		if ( empty( $price ) ) {
			$price = $original_price;
		}


		return $price;
	}

	/**
	 * Get currency fixed price
	 *
	 * @param int $price product price.
	 * @param \WC_Product $product product object.
	 * @param \CTXFeed\V5\Utility\Config $config config object.
	 * @param array $regular_price product regular price.
	 * @param array $sale_price product sale price.
	 * @param string $price_type price type regular_price, price, sale_price.
	 *
	 * @return int
	 */
	public function get_curreny_fixed_price( $price, $product, $config, $regular_price, $sale_price, $price_type ) { // phpcs:ignore
		if ( $price_type === 'price' && ! empty( $regular_price ) ) {
			$price = $regular_price[ $config->get_feed_currency() ];
		} elseif ( $price_type === 'sale_price' && ! empty( $sale_price ) ) {
			$price = $sale_price[ $config->get_feed_currency() ];
		}

		return $price;
	}

}
