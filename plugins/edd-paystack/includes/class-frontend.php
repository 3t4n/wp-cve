<?php

namespace Tubiz\EDD_Paystack;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Frontend {

	public function __construct() {
		$this->hooks();
	}

	public function hooks() {
		add_filter( 'edd_payment_gateways', array( $this, 'register_paystack_gateway' ) );
		add_action( 'edd_paystack_cc_form', '__return_false' );
		add_action( 'edd_gateway_paystack', array( $this, 'process_payment' ) );
		add_action( 'edd_pre_process_purchase', array( $this, 'is_paystack_configured' ), 1 );
		add_action( 'init', array( $this, 'process_redirect' ) );
		add_action( 'tbz_edd_paystack_redirect_verify', array( $this, 'process_redirect_payment' ) );
		add_action( 'tbz_edd_paystack_ipn_verify', array( $this, 'process_ipn' ) );
		add_filter( 'edd_currencies', array( $this, 'add_currencies' ) );
		add_filter( 'edd_accepted_payment_icons', array( $this, 'payment_icons' ) );
		add_filter( 'edd_currency_symbol', array( $this, 'extra_currency_symbol' ), 10, 2 );
		add_filter( 'edd_ngn_currency_filter_before', array( $this, 'format_ngn_currency_before' ), 10, 3 );
		add_filter( 'edd_ngn_currency_filter_after', array( $this, 'format_ngn_currency_after' ), 10, 3 );
		add_filter( 'edd_ghs_currency_filter_before', array( $this, 'format_ghs_currency_before' ), 10, 3 );
		add_filter( 'edd_ghs_currency_filter_after', array( $this, 'format_ghs_currency_after' ), 10, 3 );
		add_filter( 'edd_zar_currency_filter_before', array( $this, 'format_zar_currency_before' ), 10, 3 );
		add_filter( 'edd_zar_currency_filter_after', array( $this, 'format_zar_currency_after' ), 10, 3 );
		add_filter( 'edd_kes_currency_filter_before', array( $this, 'format_kes_currency_before' ), 10, 3 );
		add_filter( 'edd_kes_currency_filter_after', array( $this, 'format_kes_currency_after' ), 10, 3 );
		add_filter( 'edd_kes_currency_filter_before', array( $this, 'format_egp_currency_before' ), 10, 3 );
		add_filter( 'edd_kes_currency_filter_after', array( $this, 'format_egp_currency_after' ), 10, 3 );
		add_filter( 'edd_kes_currency_filter_before', array( $this, 'format_xof_currency_before' ), 10, 3 );
		add_filter( 'edd_kes_currency_filter_after', array( $this, 'format_xof_currency_after' ), 10, 3 );
	}

	/**
	 * @param $gateways
	 *
	 * @return mixed
	 */
	public function register_paystack_gateway( $gateways ) {

		$gateways['paystack'] = array(
			'admin_label'    => __( 'Paystack', 'edd-paystack' ),
			'checkout_label' => __( 'Paystack', 'edd-paystack' ),
		);

		return $gateways;
	}

	/**
	 *
	 */
	public function is_paystack_configured() {

		$is_enabled     = edd_is_gateway_active( 'paystack' );
		$chosen_gateway = edd_get_chosen_gateway();

		if ( 'paystack' === $chosen_gateway && ( ! $is_enabled || false === tbz_paystack_edd_is_setup() ) ) {
			edd_set_error( 'paystack_gateway_not_configured', __( 'Paystack payment gateway is not setup.', 'edd-paystack' ) );
		}

		if ( 'paystack' === $chosen_gateway && ! in_array( strtoupper( edd_get_currency() ), $this->get_supported_currencies(), true ) ) {
			edd_set_error( 'paystack_gateway_invalid_currency', __( 'Currency not supported by Paystack. Set the store currency to either EGP (EGP), GHS (GH&#x20b5;), KES (Ksh), NGN (&#8358), USD (&#36;), XOF (CFA) or ZAR (R)', 'edd-paystack' ) );
		}
	}

	/**
	 * @param $paystack_data
	 *
	 * @return mixed
	 */
	public function get_payment_link( $paystack_data ) {

		$paystack_url = 'https://api.paystack.co/transaction/initialize/';

		if ( edd_get_option( 'edd_paystack_test_mode' ) ) {
			$secret_key = trim( edd_get_option( 'edd_paystack_test_secret_key' ) );
		} else {
			$secret_key = trim( edd_get_option( 'edd_paystack_live_secret_key' ) );
		}

		$headers = array(
			'Authorization' => 'Bearer ' . $secret_key,
		);

		$callback_url = add_query_arg( 'edd-listener', 'paystack', home_url( 'index.php' ) );

		$body = array(
			'amount'       => $paystack_data['amount'],
			'email'        => $paystack_data['email'],
			'reference'    => $paystack_data['reference'],
			'currency'     => edd_get_currency(),
			'callback_url' => $callback_url,
		);

		$args = array(
			'body'    => $body,
			'headers' => $headers,
			'timeout' => 60,
		);

		$request = wp_remote_post( $paystack_url, $args );

		if ( ! is_wp_error( $request ) && 200 === (int) wp_remote_retrieve_response_code( $request ) ) {

			$paystack_response = json_decode( wp_remote_retrieve_body( $request ) );

		} else {

			$paystack_response = json_decode( wp_remote_retrieve_body( $request ) );

		}

		return $paystack_response;
	}

	/**
	 * @param $purchase_data
	 */
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
			'gateway'      => 'paystack',
		);

		$payment_id = edd_insert_payment( $payment_data );

		if ( false === $payment_id ) {

			edd_record_gateway_error( 'Payment Error', sprintf( 'Payment creation failed before sending buyer to Paystack. Payment data: %s', wp_json_encode( $payment_data ) ), $payment_id );

			edd_send_back_to_checkout( '?payment-mode=paystack' );

		} else {

			$paystack_data  = array();
			$transaction_id = 'EDD-' . $payment_id . '-' . uniqid();

			$paystack_data['amount']    = $purchase_data['price'] * 100;
			$paystack_data['email']     = $purchase_data['user_email'];
			$paystack_data['reference'] = $transaction_id;

			edd_set_payment_transaction_id( $payment_id, $transaction_id );

			$get_payment_url = $this->get_payment_link( $paystack_data );

			if ( isset( $get_payment_url->status ) && $get_payment_url->status ) {
				// phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect
				wp_redirect( $get_payment_url->data->authorization_url );
				exit;
			}

			$default_error_message = __( 'Unable to connect to the payment gateway, try again.', 'edd-paystack' );

			$error_message = $get_payment_url->message ?? $default_error_message;

			edd_set_error( 'paystack_error', $error_message );

			edd_send_back_to_checkout( '?payment-mode=paystack' );
		}

	}

	/**
	 *
	 */
	public function process_redirect() {

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! isset( $_GET['edd-listener'] ) ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( 'paystack' === sanitize_text_field( $_GET['edd-listener'] ) ) {
			do_action( 'tbz_edd_paystack_redirect_verify' );
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( 'paystackipn' === sanitize_text_field( $_GET['edd-listener'] ) ) {
			do_action( 'tbz_edd_paystack_ipn_verify' );
		}
	}

	/**
	 *
	 */
	public function process_redirect_payment() {

		if ( isset( $_REQUEST['trxref'] ) ) {

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$transaction_id = sanitize_text_field( $_REQUEST['trxref'] );

			$the_payment_id = edd_get_purchase_id_by_transaction_id( $transaction_id );

			$payment_status = edd_get_payment_status( $the_payment_id );

			if ( $the_payment_id && in_array( $payment_status, array( 'publish', 'complete' ), true ) ) {

				edd_empty_cart();

				edd_send_to_success_page();
			}

			$paystack_txn = $this->verify_transaction( $transaction_id );

			$order_info = explode( '-', $transaction_id );

			$payment_id = $order_info[1];

			if ( $payment_id && ! empty( $paystack_txn->data ) && ( 'success' === $paystack_txn->data->status ) ) {

				$payment          = new \EDD_Payment( $payment_id );
				$order_total      = edd_get_payment_amount( $payment_id );
				$currency_symbol  = edd_currency_symbol( $payment->currency );
				$amount_paid      = $paystack_txn->data->amount / 100;
				$paystack_txn_ref = $paystack_txn->data->reference;

				if ( $amount_paid < $order_total ) {

					$formatted_amount_paid = $currency_symbol . $amount_paid;
					$formatted_order_total = $currency_symbol . $order_total;

					/* Translators: 1: Amount paid 2: Order total 3: Paystack transaction reference. */
					$note = sprintf( __( 'Look into this purchase. This order is currently revoked. Reason: Amount paid is less than the total order amount. Amount Paid was %1$s while the total order amount is %2$s. Paystack Transaction Reference: %3$s', 'edd-paystack' ), $formatted_amount_paid, $formatted_order_total, $paystack_txn_ref  );

					$payment->status = 'revoked';

				} else {

					/* Translators: 1: Paystack transaction reference. */
					$note = sprintf( __( 'Payment transaction was successful. Paystack Transaction Reference: %s', 'edd-paystack' ), $paystack_txn_ref );

					$payment->status = 'publish';

				}

				$payment->add_note( $note );
				$payment->transaction_id = $paystack_txn_ref;

				$payment->save();

				edd_empty_cart();

				edd_send_to_success_page();

			} else {

				edd_set_error( 'failed_payment', __( 'Payment failed. Please try again.', 'edd-paystack' ) );

				edd_send_back_to_checkout( '?payment-mode=paystack' );

			}
		}
	}

	/**
	 *
	 */
	public function process_ipn() {

		if ( ( strtoupper( $_SERVER['REQUEST_METHOD'] ) !== 'POST' ) || ! array_key_exists( 'HTTP_X_PAYSTACK_SIGNATURE', $_SERVER ) ) {
			exit;
		}

		$json = file_get_contents( 'php://input' );

		if ( edd_get_option( 'edd_paystack_test_mode' ) ) {
			$secret_key = trim( edd_get_option( 'edd_paystack_test_secret_key' ) );
		} else {
			$secret_key = trim( edd_get_option( 'edd_paystack_live_secret_key' ) );
		}

		// validate event do all at once to avoid timing attack
		if ( hash_hmac( 'sha512', $json, $secret_key ) !== $_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] ) {
			exit;
		}

		$event = json_decode( $json );

		if ( 'charge.success' === $event->event ) {

			http_response_code( 200 );

			$transaction_id = $event->data->reference;
			$the_payment_id = edd_get_purchase_id_by_transaction_id( $transaction_id );
			$payment_status = edd_get_payment_status( $the_payment_id );

			if ( $the_payment_id && in_array( $payment_status, array( 'publish', 'complete' ), true ) ) {
				exit;
			}

			$order_info = explode( '-', $transaction_id );

			$payment_id = $order_info[1];

			$saved_txn_ref = edd_get_payment_transaction_id( $payment_id );

			if ( $event->data->reference !== $saved_txn_ref ) {
				exit;
			}

			$payment = new \EDD_Payment( $payment_id );

			if ( ! $payment ) {
				exit;
			}

			$order_total = edd_get_payment_amount( $payment_id );

			$currency_symbol = edd_currency_symbol( $payment->currency );

			$amount_paid = $event->data->amount / 100;

			$paystack_txn_ref = $event->data->reference;

			if ( $amount_paid < $order_total ) {

				$formatted_amount_paid = $currency_symbol . $amount_paid;
				$formatted_order_total = $currency_symbol . $order_total;

				/* Translators: 1: Amount paid 2: Order total 3: Paystack transaction reference. */
				$note = sprintf( __( 'Look into this purchase. This order is currently revoked. Reason: Amount paid is less than the total order amount. Amount Paid was %1$s while the total order amount is %2$s. Paystack Transaction Reference: %3$s', 'edd-paystack' ), $formatted_amount_paid, $formatted_order_total, $paystack_txn_ref  );

				$payment->status = 'revoked';

				$payment->add_note( $note );

				$payment->transaction_id = $paystack_txn_ref;

			} else {

				/* Translators: 1: Paystack transaction reference. */
				$note = sprintf( __( 'Payment transaction was successful. Paystack Transaction Reference: %s', 'edd-paystack' ), $paystack_txn_ref );

				$payment->status = 'publish';

				$payment->add_note( $note );

				$payment->transaction_id = $paystack_txn_ref;

			}

			$payment->save();

			exit;
		}

		exit;
	}

	/**
	 * @param $payment_token
	 *
	 * @return mixed
	 */
	public function verify_transaction( $payment_token ) {

		$paystack_url = 'https://api.paystack.co/transaction/verify/' . $payment_token;

		if ( edd_get_option( 'edd_paystack_test_mode' ) ) {

			$secret_key = trim( edd_get_option( 'edd_paystack_test_secret_key' ) );

		} else {

			$secret_key = trim( edd_get_option( 'edd_paystack_live_secret_key' ) );

		}

		$headers = array(
			'Authorization' => 'Bearer ' . $secret_key,
		);

		$args = array(
			'headers' => $headers,
			'timeout' => 60,
		);

		$request = wp_remote_get( $paystack_url, $args );

		if ( ! is_wp_error( $request ) && 200 === (int) wp_remote_retrieve_response_code( $request ) ) {

			$paystack_response = json_decode( wp_remote_retrieve_body( $request ) );

		} else {

			$paystack_response = json_decode( wp_remote_retrieve_body( $request ) );

		}

		return $paystack_response;

	}

	/**
	 * @param $currencies
	 *
	 * @return array
	 */
	public function add_currencies( $currencies ) {

		$currencies['EGP'] = 'Egyptian Pound (EGP)';
		$currencies['GHS'] = 'Ghanaian Cedi (GH&#x20b5;)';
		$currencies['KES'] = 'Kenyan Shilling (Ksh)';
		$currencies['NGN'] = 'Nigerian Naira (&#8358;)';
		$currencies['ZAR'] = 'South African Rand (R)';
		$currencies['XOF'] = 'West African CFA franc (CFA)';

		return $currencies;
	}

	/**
	 * @param $icons
	 *
	 * @return mixed
	 */
	public function payment_icons( $icons ) {

		$icons[ TBZ_EDD_PAYSTACK_URL . 'assets/images/paystack-cards.png' ] = __( 'Paystack (Cards)', 'edd-paystack' );
		$icons[ TBZ_EDD_PAYSTACK_URL . 'assets/images/paystack-gh.png' ]    = __( 'Paystack (Ghana)', 'edd-paystack' );
		$icons[ TBZ_EDD_PAYSTACK_URL . 'assets/images/paystack-ke.png' ]    = __( 'Paystack (Kenya)', 'edd-paystack' );
		$icons[ TBZ_EDD_PAYSTACK_URL . 'assets/images/paystack.png' ]       = __( 'Paystack (Nigeria)', 'edd-paystack' );
		$icons[ TBZ_EDD_PAYSTACK_URL . 'assets/images/paystack-za.png' ]    = __( 'Paystack (South Africa)', 'edd-paystack' );

		return $icons;
	}

	/**
	 * @param $symbol
	 * @param $currency
	 *
	 * @return mixed|string
	 */
	public function extra_currency_symbol( $symbol, $currency ) {

		switch ( $currency ) {
			case 'GHS':
				$symbol = 'GH&#x20b5;';
				break;

			case 'NGN':
				$symbol = '&#8358;';
				break;

			case 'KES':
				$symbol = 'Ksh';
				break;

			case 'ZAR':
				$symbol = 'R';
				break;

			case 'XOF':
				$symbol = 'CFA';
				break;

			case 'EGP':
				$symbol = 'EGP';
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

	/**
	 * @param $formatted
	 * @param $currency
	 * @param $price
	 *
	 * @return string
	 */
	public function format_kes_currency_before( $formatted, $currency, $price ) {
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
	public function format_kes_currency_after( $formatted, $currency, $price ) {
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
	public function format_egp_currency_before( $formatted, $currency, $price ) {
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
	public function format_egp_currency_after( $formatted, $currency, $price ) {
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
	public function format_xof_currency_before( $formatted, $currency, $price ) {
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
	public function format_xof_currency_after( $formatted, $currency, $price ) {
		$symbol = edd_currency_symbol( $currency );

		return $price . ' ' . $symbol;
	}

	private function get_supported_currencies() {
		$supported_currencies = array( 'GHS', 'NGN', 'USD', 'ZAR', 'KES', 'XOF', 'EGP' );

		/**
		 * Filters the currencies supported by Paystack.
		 *
		 * @param array  $supported_currencies The array of currencies supported by Paystack.
		 */
		return apply_filters( 'edd_paystack_supported_currencies', $supported_currencies );
	}
}

new namespace\Frontend();
