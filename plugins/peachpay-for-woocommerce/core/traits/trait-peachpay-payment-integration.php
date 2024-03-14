<?php
/**
 * PeachPay Extension Trait.
 *
 * @package PeachPay
 */

defined( 'ABSPATH' ) || exit;

require_once PEACHPAY_ABSPATH . 'core/traits/trait-peachpay-extension.php';

trait PeachPay_Payment_Integration {
	use PeachPay_Extension {
		internal_hooks as private parent_internal_hooks;
	}

	/**
	 * Gateway class list.
	 *
	 * @var array $payment_gateways
	 */
	private $payment_gateways = array();

	/**
	 * .
	 *
	 * @param boolean $enabled If the extension is enabled.
	 */
	private function internal_hooks( $enabled ) {
		$this->parent_internal_hooks( $enabled );

		$extension = $this;
		add_filter(
			'woocommerce_payment_gateways',
			function ( $gateways ) use ( $extension ) {
				return array_merge( $gateways, $extension->payment_gateways );
			}
		);

		add_action(
			'woocommerce_blocks_loaded',
			function () use ( $extension ) {
				if ( class_exists( 'Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType' ) ) {
					$extension->woocommerce_blocks_loaded();
				}
			}
		);
	}

	/**
	 * Determines if a gateway belongs to this integration.
	 *
	 * @param string $gateway_id The gateway to test for.
	 */
	abstract public static function is_payment_gateway( $gateway_id );

	/**
	 * Gets a array of this integrations payment gateway instances. If WC has not loaded the classes
	 * yet this function will return an empty array.
	 */
	public static function get_payment_gateways() {
		$gateways = array();

		foreach ( WC()->payment_gateways->payment_gateways() as $gateway ) {
			if ( self::is_payment_gateway( $gateway->id ) ) {
				$gateways[] = $gateway;
			}
		}

		usort(
			$gateways,
			function ( $a, $b ) {
				return $a->settings_priority - $b->settings_priority;
			}
		);

		return $gateways;
	}

	/**
	 * Takes all payment gateways for the provider and will check if at least one is true.
	 *
	 * @return bool provider_enabled
	 */
	public static function has_gateway_enabled() {
		// Note: I've decided to copy this snippet from the above function
		// instead of using the function as there is no reason for a sort to run here,
		// so this will avoid that and do a single loop.
		foreach ( WC()->payment_gateways->payment_gateways() as $gateway ) {
			if ( self::is_payment_gateway( $gateway->id ) && $gateway->get_option( 'enabled' ) === 'yes' ) {
				return true;
			}
		}

		// If no active payment gateway found, return false.
		return false;
	}

	/**
	 * This method is called when the "woocommerce_blocks_loaded" action is trigged.
	 */
	protected static function woocommerce_blocks_loaded() {
	}
}
