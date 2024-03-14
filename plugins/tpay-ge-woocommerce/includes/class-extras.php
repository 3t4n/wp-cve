<?php
/**
 * Intellectual Property rights, and copyright, reserved by Plug and Pay, Ltd. as allowed by law include,
 * but are not limited to, the working concept, function, and behavior of this software,
 * the logical code structure and expression as written.
 *
 * @package     TBC Checkout for WooCommerce
 * @author      Plug and Pay Ltd. http://plugandpay.ge/
 * @copyright   Copyright (c) Plug and Pay Ltd. (support@plugandpay.ge)
 * @since       1.0.0
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

namespace PlugandPay\TBC_Checkout;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * TBC Checkout extras class.
 */
class Extras {

	/**
	 * __FILE__ from the root plugin file.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $file;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * @param string $file Must be __FILE__ from the root plugin file.
	 */
	public function __construct( $file ) {
		$this->file = $file;

		add_filter( 'woocommerce_gateway_icon', [ $this, 'add_gateway_icons' ], 10, 2 );
		add_action( 'run_check_order_status_in_background', [ $this, 'check_order_status_in_background' ], 10, 2 );
	}

	/**
	 * Add TBC Checkout logo to the gateway.
	 *
	 * @since 1.0.0
	 * @param string $icons Html image tags.
	 * @param string $gateway_id Gateway id.
	 * @return string
	 */
	public function add_gateway_icons( $icons, $gateway_id ) {
		if ( 'tpay_gateway' === $gateway_id ) {

			$method_to_logo = [
				'card_payments'    => 'Cards',
				'qr_payments'      => 'QR',
				'ertguli_payments' => 'Ertguli',
				'apple_payments'   => 'ApplePay',
			];

			foreach ( $method_to_logo as $method => $logo ) {
				$option = $this->get_option( 'tpay_gateway', $method, 'yes' );

				if ( 'yes' === $option ) {

					$icons .= sprintf(
						'<img height="22" src="%1$sassets/%2$s.png" alt="TBC %2$s" />',
						plugin_dir_url( $this->file ),
						$logo
					);

				}
			}

			$icons .= sprintf(
				'<img height="22" src="%1$sassets/TBC.png" alt="TBC Checkout" />',
				plugin_dir_url( $this->file )
			);

		}

		return $icons;
	}

	/**
	 * Check order status in background periodically.
	 *
	 * @since 1.1.0
	 * @param int $order_id Order id.
	 * @param int $retry_index Retry counter index.
	 * @return bool
	 */
	public function check_order_status_in_background( $order_id, $retry_index ) {

		$order   = wc_get_order( $order_id );
		$gateway = new Gateway( $this->file );

		$trans_id = $order->get_transaction_id();
		$response = $gateway->get_transaction_status_from_api( $trans_id, $order->get_currency() );

		$gateway->log( sprintf( 'TBC Checkout reply on status check in the background: %s, order_id: %d', wp_json_encode( $response, JSON_PRETTY_PRINT ), $order->get_id() ), 'info' );

		if ( $response && isset( $response['status'] ) ) {

			switch ( $response['status'] ) {

				case 'WaitingConfirm':
				case 'Succeeded':
					$gateway->payment_complete( $order, $trans_id );
					$gateway->log( 'TBC Checkout: payment successful.', 'notice' );
					return true;

				case 'Failed':
					$gateway->payment_failed( $order, $trans_id );
					$gateway->log( 'TBC Checkout: payment failed.', 'notice' );
					return true;
			}

			$gateway->log( 'API did not return status Succeeded or Failed.', 'error' );
		} else {
			$gateway->log( 'API did not return anything.', 'error' );
		}

		$retries = [
			1 => MINUTE_IN_SECONDS * 3,
			2 => MINUTE_IN_SECONDS * 5,
			3 => MINUTE_IN_SECONDS * 10,
		];

		if ( isset( $retries[ $retry_index + 1 ] ) ) {
			$gateway->log( 'Reschedule background check, retry in (m): ' . $retries[ $retry_index + 1 ] / MINUTE_IN_SECONDS, 'alert' );

			WC()->queue()->schedule_single(
				time() + $retries[ $retry_index + 1 ],
				'run_check_order_status_in_background',
				[
					'order_id'    => $order->get_id(),
					'retry_index' => $retry_index + 1,
				]
			);
		}

		return false;
	}

	/**
	 * Get gateway setting.
	 *
	 * @since 2.0.0
	 * @param string $id Gateway id.
	 * @param string $key Setting key.
	 * @param mixed  $default Default value.
	 * @return mixed|null
	 */
	public function get_option( $id, $key, $default = null ) {
		$settings = get_option( sprintf( 'woocommerce_%s_settings', $id ) );
		return $settings[ $key ] ?? $default;
	}

}

