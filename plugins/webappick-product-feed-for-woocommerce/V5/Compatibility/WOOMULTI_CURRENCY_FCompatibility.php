<?php
/**
 * Compatibility class for WOOMULTI_CURRENCY_F plugin
 *
 * @package CTXFeed\V5\Compatibility
 */

namespace CTXFeed\V5\Compatibility;

/**
 * Class WOOMULTI_CURRENCY_FCompatibility
 *
 * MultiCurrency by VillaTheme free version support.
 *
 * @package CTXFeed\V5\Compatibility
 */
class WOOMULTI_CURRENCY_FCompatibility {

	/**
	 * WOOMULTI_CURRENCY_FCompatibility Constructor.
	 */
	public function __construct() {
		// Switch currency before feed generation.
		add_action( 'before_woo_feed_generate_batch_data', array( $this, 'switch_currency' ), 10, 1 );
		// Restore currency after feed generation.
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
		$data             = \WOOMULTI_CURRENCY_F_Data::get_ins();
		$default_currency = $data->get_default_currency();

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
		$data             = \WOOMULTI_CURRENCY_F_Data::get_ins();
		$default_currency = $data->get_default_currency();

		$data->set_current_currency( $default_currency );
	}

	/**
	 * Get product link with currency suffix.
	 *
	 * @param string $link product link.
	 * @param \WC_Product $product product object.
	 * @param \CTXFeed\V5\Utility\Config $config config object.
	 *
	 * @return string
	 */
	public function get_product_link_with_suffix( $link, $product, $config ) { // phpcs:ignore
		$jointer         = substr( $link,  - 1 ) == '/' ? '?' : '&';
		$currency_suffix = $jointer . 'wmc-currency=' . $config->get_feed_currency();

		$link .= $currency_suffix;

		return $link;
	}

}
