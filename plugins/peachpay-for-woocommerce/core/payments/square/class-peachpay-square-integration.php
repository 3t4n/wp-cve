<?php
/**
 * PeachPay Square payment integration.
 *
 * @package PeachPay
 */

if ( ! defined( 'PEACHPAY_ABSPATH' ) ) {
	exit;
}

/**
 * .
 */
final class PeachPay_Square_Integration {
	use PeachPay_Payment_Integration;

	/**
	 * Should the extension load?
	 */
	public static function should_load() {
		return true;
	}

	/**
	 * Is the extension enabled?
	 */
	public static function enabled() {
		return true;
	}

	/**
	 * .
	 *
	 * @param boolean $enabled If the extension is enabled.
	 */
	private function includes( $enabled ) {
		require_once PEACHPAY_ABSPATH . 'core/payments/square/hooks.php';
		require_once PEACHPAY_ABSPATH . 'core/payments/square/functions.php';

		if ( is_admin() ) {
			require_once PEACHPAY_ABSPATH . 'core/payments/square/admin/class-peachpay-admin-square-integration.php';
		}
	}

	/**
	 * Runs code after all plugins are loaded. Before WC init.
	 *
	 * @param boolean $enabled If the extension is enabled.
	 */
	private function plugins_loaded( $enabled ) {
		// https://github.com/woocommerce/woocommerce/wiki/Payment-Token-API
		require_once PEACHPAY_ABSPATH . 'core/payments/square/tokens/class-wc-payment-token-peachpay-square-card.php';
		require_once PEACHPAY_ABSPATH . 'core/payments/square/utils/class-peachpay-square.php';
		require_once PEACHPAY_ABSPATH . 'core/payments/square/utils/class-peachpay-square-order-data.php';
	}

	/**
	 * .
	 *
	 * @param boolean $enabled If the extension is enabled.
	 */
	protected function woocommerce_init( $enabled ) {
		require_once PEACHPAY_ABSPATH . 'core/payments/square/abstract/class-peachpay-square-payment-gateway.php';

		require_once PEACHPAY_ABSPATH . 'core/payments/square/gateways/class-peachpay-square-card-gateway.php';
		require_once PEACHPAY_ABSPATH . 'core/payments/square/gateways/class-peachpay-square-applepay-gateway.php';
		require_once PEACHPAY_ABSPATH . 'core/payments/square/gateways/class-peachpay-square-googlepay-gateway.php';
		require_once PEACHPAY_ABSPATH . 'core/payments/square/gateways/class-peachpay-square-ach-gateway.php';
		require_once PEACHPAY_ABSPATH . 'core/payments/square/gateways/class-peachpay-square-afterpay-gateway.php';
		require_once PEACHPAY_ABSPATH . 'core/payments/square/gateways/class-peachpay-square-cashapp-gateway.php';

		$this->payment_gateways[] = 'PeachPay_Square_Card_Gateway';
		$this->payment_gateways[] = 'PeachPay_Square_ApplePay_Gateway';
		$this->payment_gateways[] = 'PeachPay_Square_GooglePay_Gateway';
		$this->payment_gateways[] = 'PeachPay_Square_ACH_Gateway';
		$this->payment_gateways[] = 'PeachPay_Square_Afterpay_Gateway';
		$this->payment_gateways[] = 'PeachPay_Square_Cashapp_Gateway';
	}

	/**
	 * Callback for registering PeachPay Square payment method support for WooCommerce blocks.
	 */
	protected function woocommerce_blocks_loaded() {
		require_once PEACHPAY_ABSPATH . 'core/payments/square/blocks/class-peachpay-square-card-gateway-blocks-support.php';
		require_once PEACHPAY_ABSPATH . 'core/payments/square/blocks/class-peachpay-square-googlepay-gateway-blocks-support.php';
		require_once PEACHPAY_ABSPATH . 'core/payments/square/blocks/class-peachpay-square-ach-gateway-blocks-support.php';

		add_action(
			'woocommerce_blocks_payment_method_type_registration',
			function ( Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry $payment_method_registry ) {
				$payment_method_registry->register( new PeachPay_Square_Card_Gateway_Blocks_Support() );
				$payment_method_registry->register( new PeachPay_Square_GooglePay_Gateway_Blocks_Support() );
				$payment_method_registry->register( new PeachPay_Square_ACH_Gateway_Blocks_Support() );
			}
		);
	}

	/**
	 * Used to detect if a gateway is a PeachPay Square gateway.
	 *
	 * @param string $id Payment gateway id.
	 */
	public static function is_payment_gateway( $id ) {
		return peachpay_starts_with( $id, 'peachpay_square_' );
	}

	/**
	 * Gets the PeachPay square test or live mode status.
	 *
	 * @param boolean|null $mode If the mode should override the global test mode. If not null a truthy value will indicate live mode where a falsy value will indicate test mode.
	 */
	public static function mode( $mode = 'detect' ) {
		if ( 'detect' === $mode ) {
			return ( peachpay_is_test_mode() || peachpay_is_local_development_site() || peachpay_is_staging_site() ) ? 'test' : 'live';
		}

		if ( 'live' === $mode ) {
			return 'live';
		} else {
			return 'test';
		}
	}
}
PeachPay_Square_Integration::instance();
