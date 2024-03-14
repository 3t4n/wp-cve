<?php
/**
 * Class NoShippingAvailableMessage
 */

namespace Octolize\Shipping\Notices\ShippingNotice;

use Exception;
use Octolize\Shipping\Notices\PluginSettings;
use OctolizeShippingNoticesVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WC_Customer;

/**
 * Modify message.
 */
class NoShippingAvailableMessage implements Hookable {

	/**
	 * @var WC_Customer
	 */
	private $customer;

	/**
	 * @var ShippingNoticeFinder
	 */
	private $finder;

	/**
	 * @var PluginSettings
	 */
	private $plugin_settings;

	/**
	 * @param WC_Customer          $customer .
	 * @param ShippingNoticeFinder $finder   .
	 */
	public function __construct( WC_Customer $customer, ShippingNoticeFinder $finder, PluginSettings $plugin_settings ) {
		$this->finder          = $finder;
		$this->customer        = $customer;
		$this->plugin_settings = $plugin_settings;
	}

	/**
	 * @return void
	 */
	public function hooks(): void {
		add_filter( 'woocommerce_no_shipping_available_html', [ $this, 'modify_message' ] );
		add_filter( 'woocommerce_cart_no_shipping_available_html', [ $this, 'modify_message' ] );
	}

	/**
	 * @param mixed $message .
	 *
	 * @return string
	 */
	public function modify_message( $message ): string {
		if ( ! $this->plugin_settings->is_enabled() ) {
			// @phpstan-ignore-next-line
			return $message;
		}

		try {
			return nl2br(
				$this->finder->find_message(
					$this->customer->get_shipping_country(),
					$this->customer->get_shipping_state(),
					$this->customer->get_shipping_postcode(),
					$this->get_location()
				)->get_message()
			);
		} catch ( Exception $e ) { //phpcs:ignore
			// Do nothing.
		}

		// @phpstan-ignore-next-line
		return $message;
	}

	/**
	 * @return string
	 * @throws Exception
	 */
	private function get_location(): string {
		$custom = apply_filters( 'shipping-notices/location', null );

		if ( is_string( $custom ) ) {
			return $custom;
		}

		if ( is_checkout() ) {
			return 'checkout';
		}

		if ( is_cart() ) {
			return 'cart';
		}

		throw new Exception( __( 'Unknown Notice display page', 'octolize-shipping-notices' ) );
	}
}
