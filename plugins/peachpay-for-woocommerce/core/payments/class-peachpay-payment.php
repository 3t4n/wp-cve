<?php
/**
 * PeachPay Payment util class.
 *
 * @package PeachPay/Payments
 */

if ( ! defined( 'PEACHPAY_ABSPATH' ) ) {
	exit;
}

/**
 * .
 */
final class PeachPay_Payment {

	/**
	 * .
	 */
	private function __construct() {
		add_action( 'woocommerce_cart_emptied', array( 'PeachPay_Payment', 'reset_session' ) );
	}

	/**
	 * For creating a PeachPay transaction, allowing us to create payment intents for subscription renewals.
	 *
	 * @param WC_Order $order Text.
	 * @param string   $session_id The.
	 * @param string   $transaction_location text.
	 * @param string   $peachpay_mode The mode to create the transaction.
	 */
	public static function create_order_transaction( $order, $session_id, $transaction_location, $peachpay_mode = 'detect' ) {
		$result = self::create_transaction( $session_id, $order->get_payment_method(), $transaction_location, $peachpay_mode );

		if ( true === $result['success'] ) {
			PeachPay_Stripe_Order_Data::set_peachpay_details(
				$order,
				array(
					'session_id'     => $session_id,
					'transaction_id' => $result['data']['transaction_id'],
				)
			);
		}

		return $result;
	}

	/**
	 * Updates a PeachPay transaction.
	 *
	 * @param WC_Order $order The order to update for.
	 * @param array    $options The order details to update the transaction.
	 */
	public static function update_order_transaction( $order, $options = array() ) {
		$transaction_id = PeachPay_Order_Data::get_peachpay( $order, 'transaction_id' );
		$session_id     = PeachPay_Order_Data::get_peachpay( $order, 'session_id' );
		$peachpay_mode  = PeachPay_Order_Data::get_peachpay( $order, 'peachpay_mode' );

		$options['order_status'] = $order->get_status();

		$result = self::update_transaction( $transaction_id, $session_id, $options, $peachpay_mode );

		return $result;
	}

	/**
	 * For creating a PeachPay transaction, allowing us to create payment intents for subscription renewals.
	 *
	 * @param string $session_id The session id for the order.
	 * @param string $gateway_id The gateway id for the order.
	 * @param string $checkout_location The checkout location for the order.
	 * @param string $peachpay_mode The mode to create the transaction.
	 */
	public static function create_transaction( $session_id, $gateway_id, $checkout_location, $peachpay_mode = 'detect' ) {
		$response = wp_remote_post(
			peachpay_api_url( $peachpay_mode ) . 'api/v1/transaction/create',
			array(
				'data_format' => 'body',
				'headers'     => array(
					'Content-Type'            => 'application/json; charset=utf-8',
					'PeachPay-Mode'           => $peachpay_mode,
					'PeachPay-Merchant-Id'    => peachpay_plugin_merchant_id(),
					'PeachPay-Session-Id'     => $session_id,
					'PeachPay-Plugin-Version' => PEACHPAY_VERSION,
				),
				'body'        => wp_json_encode(
					array(
						'session'     => array(
							'id'             => $session_id,
							'merchant_id'    => peachpay_plugin_merchant_id(),
							'merchant_url'   => home_url(),
							'merchant_name'  => get_bloginfo( 'name' ),
							'plugin_version' => PEACHPAY_VERSION,
							'platform'       => 'woocommerce',
						),
						'transaction' => array(
							'transaction_location'     => $checkout_location,
							'payment_method'           => $gateway_id,
							'payment_method_variation' => null,
						),
					)
				),
			)
		);

		$json = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( ! $json['success'] ) {
			return array(
				'success' => false,
				'message' => $json['message'],
			);
		}

		$transaction_id = $json['data']['transaction_id'];

		return array(
			'success' => true,
			'data'    => array(
				'transaction_id' => $transaction_id,
			),
		);
	}

	/**
	 * Updates a PeachPay transaction.
	 *
	 * @param string $transaction_id The transaction id for the order.
	 * @param string $session_id The session id for the order.
	 * @param array  $options The order details to update the transaction.
	 * @param string $peachpay_mode The mode the transaction was created in.
	 */
	public static function update_transaction( $transaction_id, $session_id, $options = array(), $peachpay_mode = 'detect' ) {
		$body = array(
			'session'     => array(
				'id'             => $session_id,
				'merchant_id'    => peachpay_plugin_merchant_id(),
				'merchant_url'   => home_url(),
				'merchant_name'  => get_bloginfo( 'name' ),
				'plugin_version' => PEACHPAY_VERSION,
			),
			'transaction' => array(
				'id' => $transaction_id,
			),
			'order'       => array(),
		);

		if ( isset( $options['order_status'] ) ) {
			$body['order']['order_status'] = $options['order_status'];

		}

		if ( isset( $options['payment_status'] ) ) {
			$body['order']['payment_status'] = $options['payment_status'];
		}

		if ( isset( $options['order_details'] ) ) {
			$body['order']['order_details'] = $options['order_details'];
		}

		if ( isset( $options['note'] ) ) {
			$body['transaction']['note'] = $options['note'];
		}

		$response = wp_remote_post(
			peachpay_api_url( $peachpay_mode ) . 'api/v1/transaction/update',
			array(
				'data_format' => 'body',
				'headers'     => array(
					'Content-Type'            => 'application/json; charset=utf-8',
					'PeachPay-Mode'           => $peachpay_mode,
					'PeachPay-Merchant-Id'    => peachpay_plugin_merchant_id(),
					'PeachPay-Transaction-Id' => $transaction_id,
					'PeachPay-Session-Id'     => $session_id,
					'PeachPay-Plugin-Version' => PEACHPAY_VERSION,
				),
				'body'        => wp_json_encode( $body ),
			)
		);

		$json = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( ! $json['success'] ) {
			return array(
				'success' => false,
				'message' => $json['message'],
			);
		}

		return array(
			'success' => true,
		);
	}

	/**
	 * Gets the PeachPay checkout session id. If it does not yet exists then it is created.
	 *
	 * @return string The session id.
	 */
	public static function get_session() {
		$session_id = WC()->session->get( 'peachpay_session_id' );

		if ( ! $session_id ) {
			$session_id = wp_generate_uuid4();
			WC()->session->set( 'peachpay_session_id', $session_id );
		}

		return $session_id;
	}

	/**
	 * Resets the PeachPay checkout session id.
	 */
	public static function reset_session() {
		unset( WC()->session['peachpay_session_id'] );

		WC()->session->save_data();
	}

	/**
	 * Updates a PeachPay transaction.
	 *
	 * TODO followup(refactor/cleanup) Move method into Purchase order folder
	 *
	 * @param WC_Order $order The order to update for.
	 * @param string   $session_id The session id for the order.
	 * @param string   $transaction_id The transaction id for the order.
	 * @param array    $purchase_order_number the information to update the transaction with.
	 */
	public static function update_transaction_purchase_order( $order, $session_id, $transaction_id, $purchase_order_number ) {
		$response = wp_remote_post(
			peachpay_api_url() . 'api/v1/transaction/update',
			array(
				'data_format' => 'body',
				'headers'     => array(
					'Content-Type' => 'application/json; charset=utf-8',
				),
				'body'        => wp_json_encode(
					array(
						'session'     => array(
							'id'             => $session_id,
							'merchant_id'    => peachpay_plugin_merchant_id(),
							'merchant_url'   => home_url(),
							'merchant_name'  => get_bloginfo( 'name' ),
							'plugin_version' => PEACHPAY_VERSION,
						),
						'transaction' => array(
							'id'             => $transaction_id,
							'purchase_order' => array(
								'purchase_order_number' => $purchase_order_number,
							),
						),
						'order'       => array(
							'payment_status' => $order->get_status(),
							'order_status'   => $order->get_status(),
							'data'           => array(
								'id'      => $order->get_id(),
								'result'  => 'success',
								'details' => $order->get_data(),
							),
						),
					)
				),
			)
		);

		$json = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( ! $json['success'] ) {
			return array(
				'success' => false,
				'message' => $json['message'],
			);
		}

		return array(
			'success' => true,
		);
	}

	/**
	 * Gets the available PeachPay gateways instances.
	 */
	public static function available_gateways() {
		$gateways = array();

		foreach ( WC()->payment_gateways->payment_gateways() as $gateway ) {
			if ( $gateway instanceof PeachPay_Payment_Gateway ) {
				$gateways[] = $gateway;
			}
		}

		return $gateways;
	}
}
