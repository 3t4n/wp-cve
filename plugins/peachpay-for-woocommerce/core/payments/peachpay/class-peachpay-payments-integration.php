<?php
/**
 * PeachPay Payments extension.
 *
 * @package PeachPay
 */

if ( ! defined( 'PEACHPAY_ABSPATH' ) ) {
	exit;
}

require_once PEACHPAY_ABSPATH . 'core/traits/trait-peachpay-payment-integration.php';

/**
 * .
 */
final class PeachPay_Payments_Integration {
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
	protected function includes( $enabled ) {
		require_once PEACHPAY_ABSPATH . 'core/payments/class-peachpay-payment.php';

		if ( is_admin() ) {
			require_once PEACHPAY_ABSPATH . 'core/payments/peachpay/admin/class-peachpay-payments-admin-integration.php';
		}
	}

	/**
	 * .
	 *
	 * @param boolean $enabled If the extension is enabled.
	 */
	protected function woocommerce_init( $enabled ) {
		require_once PEACHPAY_ABSPATH . 'core/payments/peachpay/gateways/class-peachpay-purchase-order-gateway.php';

		$this->payment_gateways[] = 'PeachPay_Purchase_Order_Gateway';
	}

	/**
	 * Used to detect if a gateway is a PeachPay gateway.
	 *
	 * @param string $id Payment gateway id.
	 */
	public static function is_payment_gateway( $id ) {
		if ( 'peachpay_purchase_order' === $id ) {
			return true;
		}

		return false;
	}
}
PeachPay_Payments_Integration::instance();
