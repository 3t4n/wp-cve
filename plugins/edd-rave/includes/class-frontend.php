<?php

namespace Tubiz\EDD_Rave;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Frontend {

	public function __construct() {
		$this->hooks();
	}

	public function hooks() {
		add_filter( 'edd_payment_gateways', array( $this, 'register_rave_gateway' ) );
		add_action( 'edd_rave_cc_form', '__return_false' );
		add_action( 'edd_gateway_rave', array( $this, 'process_payment' ) );
		add_action( 'edd_pre_process_purchase', array( $this, 'is_flutterwave_configured' ), 1 );
		add_action( 'init', array( $this, 'process_redirect' ) );
		add_action( 'tbz_edd_rave_redirect_verify', array( $this, 'process_redirect_payment' ) );
		add_action( 'tbz_edd_rave_ipn_verify', array( $this, 'process_ipn' ) );
		add_filter( 'edd_currencies', array( $this, 'add_currencies' ) );
		add_filter( 'edd_accepted_payment_icons', array( $this, 'payment_icons' ) );
		add_filter( 'edd_currency_symbol', array( $this, 'extra_currency_symbol' ), 10, 2 );
		add_filter( 'edd_ngn_currency_filter_before', array( $this, 'format_ngn_currency_before' ), 10, 3 );
		add_filter( 'edd_ngn_currency_filter_after', array( $this, 'format_ngn_currency_after' ), 10, 3 );
		add_filter( 'edd_ghs_currency_filter_before', array( $this, 'format_ghs_currency_before' ), 10, 3 );
		add_filter( 'edd_ghs_currency_filter_after', array( $this, 'format_ghs_currency_after' ), 10, 3 );
		add_filter( 'edd_zar_currency_filter_before', array( $this, 'format_zar_currency_before' ), 10, 3 );
		add_filter( 'edd_zar_currency_filter_after', array( $this, 'format_zar_currency_after' ), 10, 3 );
	}

	public function register_rave_gateway( $gateways ) {

		$gateways['rave'] = array(
			'admin_label'    => __( 'Flutterwave', 'edd-rave' ),
			'checkout_label' => __( 'Flutterwave', 'edd-rave' ),
		);

		return $gateways;
	}

	public function is_flutterwave_configured() {

		$is_enabled = edd_is_gateway_active( 'rave' );

		if ( ( ! $is_enabled || false === $this->is_flutterwave_setup() ) && 'rave' === edd_get_chosen_gateway() ) {
			edd_set_error( 'rave_gateway_not_configured', __( 'Flutterwave payment gateway is not setup.', 'edd-rave' ) );
		}
	}

	public function process_payment( $purchase_data ) {

		$payment_data = array(
			'price'        => $purchase_data['price'],
			'date'         => $purchase_data['date'],
			'user_email'   => $purchase_data['user_email'],
			'purchase_key' => $purchase_data['purchase_key'],
			'currency'     => edd_get_currency(),
			'downloads'    => $purchase_data['downloads'],
			'cart_details' => $purchase_data['cart_details'],
			'user_info'    => $purchase_data['user_info'],
			'status'       => 'pending',
			'gateway'      => 'rave',
		);

		$payment = edd_insert_payment( $payment_data );

		if ( $payment ) {

			$rave_data = array();

			$rave_data['amount']     = $purchase_data['price'];
			$rave_data['email']      = $purchase_data['user_email'];
			$rave_data['first_name'] = $purchase_data['user_info']['first_name'];
			$rave_data['last_name']  = $purchase_data['user_info']['last_name'];
			$rave_data['reference']  = 'EDD-' . $payment . '-' . uniqid();

			edd_set_payment_transaction_id( $payment, $rave_data['reference'] );

			$get_payment_url = $this->get_payment_link( $rave_data );

			if ( isset( $get_payment_url->status ) && 'success' === $get_payment_url->status ) {
				// phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect
				wp_redirect( $get_payment_url->data->link );

				exit;

			}

			edd_set_error( 'rave_error', __( 'Can&#8217;t connect to the gateway, please try again.', 'edd-rave' ) );

		}

		edd_send_back_to_checkout( '?payment-mode=rave' );
	}

	public function process_redirect() {

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! isset( $_GET['edd-listener'] ) ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( 'rave' === $_GET['edd-listener'] ) {
			do_action( 'tbz_edd_rave_redirect_verify' );
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( 'raveipn' === $_GET['edd-listener'] ) {
			do_action( 'tbz_edd_rave_ipn_verify' );
		}
	}

	public function process_redirect_payment() {

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! empty( $_REQUEST['transaction_id'] ) && ! empty( $_REQUEST['tx_ref'] ) ) {

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$transaction_id = sanitize_text_field( $_REQUEST['tx_ref'] );

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$rave_txn_id = sanitize_text_field( $_REQUEST['transaction_id'] );

			$payment_id = edd_get_purchase_id_by_transaction_id( $transaction_id );

			$payment_status = edd_get_payment_status( $payment_id );

			if ( $payment_id && in_array( $payment_status, array( 'publish', 'complete' ), true ) ) {

				edd_empty_cart();

				edd_send_to_success_page();
			}

			$transaction = $this->verify_transaction( $rave_txn_id );

			if ( isset( $transaction->status ) && 'success' === $transaction->status ) {

				$payment              = new \EDD_Payment( $payment_id );
				$currency_symbol      = edd_currency_symbol( $payment->currency );
				$order_total          = edd_get_payment_amount( $payment_id );
				$rave_currency_symbol = edd_currency_symbol( $transaction->data->currency );
				$amount_paid          = $transaction->data->amount;
				$txn_ref              = $transaction->data->tx_ref;
				$payment_ref          = $transaction->data->flw_ref;

				if ( $amount_paid < $order_total ) {

					$formatted_amount_paid = $rave_currency_symbol . $amount_paid;
					$formatted_order_total = $currency_symbol . $order_total;

					/* Translators: 1: Amount paid 2: Order total 3: Rave transaction ID 4: Rave payment reference. */
					$note = sprintf( __( 'Look into this purchase. This order is currently revoked. Reason: Amount paid is less than the total order amount. Amount Paid was %1$s while the total order amount is %2$s. Flutterwave Transaction ID: %3$s. Flutterwave Payment Reference: %4$s', 'edd-rave' ), $formatted_amount_paid, $formatted_order_total, $txn_ref, $payment_ref );

					$payment->status = 'revoked';

				} else {

					/* Translators: 1: Rave transaction ID 2: Rave payment reference. */
					$note = sprintf( __( 'Payment transaction was successful. Flutterwave Transaction ID: %1$s. Rave Payment Reference: %2$s', 'edd-rave' ), $txn_ref, $payment_ref );

					$payment->status = 'publish';

				}

				$payment->add_note( $note );

				$payment->save();

				edd_empty_cart();

				edd_send_to_success_page();

			} else {

				edd_set_error( 'failed_payment', __( 'Payment failed. Please try again.', 'edd-rave' ) );

				edd_send_back_to_checkout( '?payment-mode=rave' );

			}
		} else {

			edd_send_back_to_checkout( '?payment-mode=rave' );

		}

	}

	public function process_ipn() {

		if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) ) {
			exit;
		}

		$body = file_get_contents( 'php://input' );

		if ( $this->is_json( $body ) ) {
			$webhook_body = (array) json_decode( $body, true );
		} else {
			// phpcs:ignore WordPress.Security.NonceVerification.Missing
			$webhook_body = $_POST;
		}

		if ( isset( $webhook_body['data']['id'] ) ) {
			$transaction_id = $webhook_body['data']['id'];
		} else {
			$transaction_id = $webhook_body['id'];
		}

		if ( empty( $transaction_id ) ) {
			exit;
		}

		$transaction = $this->verify_transaction( $transaction_id );

		if ( isset( $transaction->status ) && 'success' === $transaction->status ) {

			$txn_ref = $transaction->data->tx_ref;

			$payment_id = edd_get_purchase_id_by_transaction_id( $txn_ref );

			if ( empty( $payment_id ) ) {
				exit;
			}

			if ( $payment_id ) {
				http_response_code( 200 );
			}

			$payment_status = edd_get_payment_status( $payment_id );

			if ( in_array( strtolower( $payment_status ), array( 'publish', 'complete' ), true ) ) {
				exit;
			}

			$payment = new \EDD_Payment( $payment_id );

			if ( ! $payment ) {
				exit;
			}

			$currency_symbol      = edd_currency_symbol( $payment->currency );
			$order_total          = edd_get_payment_amount( $payment_id );
			$rave_currency_symbol = edd_currency_symbol( $transaction->data->currency );
			$amount_paid          = $transaction->data->amount;
			$payment_ref          = $transaction->data->flw_ref;

			if ( $amount_paid < $order_total ) {

				$formatted_amount_paid = $rave_currency_symbol . $amount_paid;
				$formatted_order_total = $currency_symbol . $order_total;

				/* Translators: 1: Amount paid 2: Order total 3: Rave transaction ID 4: Rave payment reference. */
				$note = sprintf( __( 'Look into this purchase. This order is currently revoked. Reason: Amount paid is less than the total order amount. Amount Paid was %1$s while the total order amount is %2$s. Flutterwave Transaction ID: %3$s. Flutterwave Payment Reference: %4$s', 'edd-rave' ), $formatted_amount_paid, $formatted_order_total, $txn_ref, $payment_ref );

				$payment->status = 'revoked';

			} else {

				/* Translators: 1: Rave transaction ID 2: Rave payment reference. */
				$note = sprintf( __( 'Payment transaction was successful. Flutterwave Transaction ID: %1$s. Flutterwave Payment Reference: %2$s', 'edd-rave' ), $txn_ref, $payment_ref );

				$payment->status = 'publish';

			}

			$payment->add_note( $note );

			$payment->save();

			edd_empty_cart();
		}

		exit;
	}

	public function payment_icons( $icons ) {

		$icons[ TBZ_EDD_RAVE_URL . 'assets/images/powered-by-rave.png' ] = __( 'Flutterwave', 'edd-rave' );

		return $icons;
	}

	public function add_currencies( $currencies ) {

		$currencies['NGN'] = 'Nigerian Naira (&#8358;)';
		$currencies['KES'] = 'Kenyan Shilling (KSh)';
		$currencies['GHS'] = 'Ghanaian Cedi (&#x20b5;)';
		$currencies['ZAR'] = 'South African Rand (&#82;)';
		$currencies['UGX'] = 'Ugandan Shilling (UGX)';
		$currencies['RWF'] = 'Rwandan Franc (Fr)';
		$currencies['TZS'] = 'Tanzanian Shilling (Sh)';
		$currencies['SLL'] = 'Sierra Leonean Leone (Le)';
		$currencies['XAF'] = 'Central African CFA franc (CFA)';
		$currencies['ZMW'] = 'Zambian Kwacha (ZK)';

		return $currencies;
	}

	public function extra_currency_symbol( $symbol, $currency ) {

		switch ( $currency ) {

			case 'NGN':
				$symbol = '&#8358;';
				break;

			case 'KES':
				$symbol = 'KSh';
				break;

			case 'GHS':
				$symbol = '&#x20b5;';
				break;

			case 'ZAR':
				$symbol = '&#82;';
				break;

			case 'UGX':
				$symbol = 'UGX';
				break;

			case 'RWF':
				$symbol = 'Fr';
				break;

			case 'TZS':
				$symbol = 'Sh';
				break;

			case 'SLL':
				$symbol = 'Le';
				break;

			case 'XAF':
				$symbol = 'CFA';
				break;

			case 'ZMW':
				$symbol = 'ZK';
				break;

		}

		return $symbol;
	}

	/**
	 * @param $formatted
	 * @param $currency
	 * @param $price
	 *
	 * @return string
	 */
	public function format_ngn_currency_before( $formatted, $currency, $price ) {
		$symbol = edd_currency_symbol( $currency );

		return $symbol . $price;
	}

	/**
	 * @param $formatted
	 * @param $currency
	 * @param $price
	 *
	 * @return string
	 */
	public function format_ngn_currency_after( $formatted, $currency, $price ) {
		$symbol = edd_currency_symbol( $currency );

		return $price . $symbol;
	}

	/**
	 * @param $formatted
	 * @param $currency
	 * @param $price
	 *
	 * @return string
	 */
	public function format_ghs_currency_before( $formatted, $currency, $price ) {
		$symbol = edd_currency_symbol( $currency );

		return $symbol . ' ' . $price;
	}

	/**
	 * @param $formatted
	 * @param $currency
	 * @param $price
	 *
	 * @return string
	 */
	public function format_ghs_currency_after( $formatted, $currency, $price ) {
		$symbol = edd_currency_symbol( $currency );

		return $price . ' ' . $symbol;
	}

	/**
	 * @param $formatted
	 * @param $currency
	 * @param $price
	 *
	 * @return string
	 */
	public function format_zar_currency_before( $formatted, $currency, $price ) {
		$symbol = edd_currency_symbol( $currency );

		return $symbol . ' ' . $price;
	}

	/**
	 * @param $formatted
	 * @param $currency
	 * @param $price
	 *
	 * @return string
	 */
	public function format_zar_currency_after( $formatted, $currency, $price ) {
		$symbol = edd_currency_symbol( $currency );

		return $price . ' ' . $symbol;
	}

	private function get_payment_link( $payment_data ) {

		$api_url = 'https://api.flutterwave.com/v3/payments';

		$headers = array(
			'Content-Type'  => 'application/json',
			'Authorization' => 'Bearer ' . $this->get_secret_key(),
		);

		$callback_url = add_query_arg( 'edd-listener', 'rave', home_url( 'index.php' ) );

		$body = array(
			'tx_ref'         => $payment_data['reference'],
			'amount'         => $payment_data['amount'],
			'currency'       => edd_get_currency(),
			'customer'       => array(
				'name'  => $payment_data['first_name'] . ' ' . $payment_data['last_name'],
				'email' => $payment_data['email'],
			),
			'customizations' => array(
				'title'       => edd_get_option( 'edd_rave_title', '' ),
				'description' => edd_get_option( 'edd_rave_description', '' ),
				'logo'        => edd_get_option( 'edd_rave_checkout_image', '' ),
			),
			'redirect_url'   => $callback_url,
		);

		$args = array(
			'body'    => wp_json_encode( $body ),
			'headers' => $headers,
			'timeout' => 60,
		);

		$request = wp_remote_post( $api_url, $args );

		return json_decode( wp_remote_retrieve_body( $request ) );
	}

	private function verify_transaction( $txn_id ) {

		$api_url = "https://api.flutterwave.com/v3/transactions/$txn_id/verify";

		$headers = array(
			'Content-Type'  => 'application/json',
			'Authorization' => 'Bearer ' . $this->get_secret_key(),
		);

		$args = array(
			'headers' => $headers,
			'timeout' => 60,
		);

		$request = wp_remote_get( $api_url, $args );

		return json_decode( wp_remote_retrieve_body( $request ) );
	}

	private function get_secret_key() {

		if ( edd_get_option( 'edd_rave_test_mode' ) ) {
			$secret_key = edd_get_option( 'edd_rave_test_secret_key' );
		} else {
			$secret_key = edd_get_option( 'edd_rave_live_secret_key' );
		}

		return $secret_key;
	}

	private function is_json( $string ) {
		return is_string( $string ) && is_array( json_decode( $string, true ) ) ? true : false;
	}

	private function is_flutterwave_setup() {

		if ( edd_get_option( 'edd_rave_test_mode' ) ) {

			$secret_key = edd_get_option( 'edd_rave_test_secret_key', '' );
			$public_key = edd_get_option( 'edd_rave_test_public_key', '' );

		} else {

			$secret_key = edd_get_option( 'edd_rave_live_secret_key', '' );
			$public_key = edd_get_option( 'edd_rave_live_public_key', '' );

		}

		return ! ( empty( $public_key ) || empty( $secret_key ) );
	}
}
new namespace\Frontend();
