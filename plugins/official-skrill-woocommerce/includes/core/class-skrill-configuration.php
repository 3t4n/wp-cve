<?php
/**
 * Skrill Configuration
 *
 * This class helper for get configuration.
 * Copyright (c) Skrill
 *
 * @class   Skrill_Configuration
 * @package Skrill/Classes
 * @located at  /includes/core/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Skrill_Configuration
 */
class Skrill_Configuration {


	/**
	 * Get WordPress language
	 *
	 * @return string
	 */
	public static function get_language() {
		$ietf_language = get_bloginfo( 'language' );
		$language      = substr( $ietf_language, 0, 2 );
		return strtoupper( $language );
	}

	/**
	 * Validate administrator user
	 *
	 * @return bool
	 */
	public function is_site_admin() {
		return in_array( 'administrator', wp_get_current_user()->roles, true );
	}

	/**
	 * Set number format
	 *
	 * @param  string|float $number - number.
	 * @return string
	 */
	public static function set_number_format( $number ) {
		$number = (float) str_replace( ',', '.', $number );
		return number_format( $number, 2, '.', '' );
	}

	/**
	 * Validate skrill payment
	 *
	 * @param  string $payment_method_id - payment method id.
	 * @return bool
	 */
	public static function is_skrill_payment( $payment_method_id ) {
		if ( strpos( $payment_method_id, 'skrill' ) !== false ) {
			return true;
		}
		return false;
	}

	/**
	 * Translate error identifier.
	 *
	 * @param  string $error_identifier - error identifier.
	 * @return string
	 */
	public static function translate_error_identifier( $error_identifier ) {
		switch ( $error_identifier ) {
			case 'Please come back again after a few minutes and check your order histoy.':
				return __( 'Please come back again after a few minutes and check your order histoy.', 'wc-skrill' );
			case 'Unfortunately, the confirmation of your payment failed. Please contact your merchant for clarification.':
				return __( 'Unfortunately, the confirmation of your payment failed. Please contact your merchant for clarification.', 'wc-skrill' );
			case 'Error before redirect':
				return __( 'Error before redirect', 'wc-skrill' );
			case 'Unfortunately, there was an error while processing your order. In case a payment has been made, it will be automatically refunded.':
				return __( 'Unfortunately, there was an error while processing your order. In case a payment has been made, it will be automatically refunded.', 'wc-skrill' );
			case 'You cancelled the payment prior to its execution. Please try again.':
				return __( 'You cancelled the payment prior to its execution. Please try again.', 'wc-skrill' );
			case 'Referred by card issuer':
				return __( 'Referred by card issuer', 'wc-skrill' );
			case 'Invalid Merchant':
				return __( 'Invalid Merchant', 'wc-skrill' );
			case 'Stolen card':
				return __( 'Stolen card', 'wc-skrill' );
			case "Declined by customer's Card Issuer":
				return __( "Declined by customer's Card Issuer", 'wc-skrill' );
			case 'Insufficient funds':
				return __( 'Insufficient funds', 'wc-skrill' );
			case 'PIN tries exceeded - card blocked':
				return __( 'PIN tries exceeded - card blocked', 'wc-skrill' );
			case 'Invalid Transaction':
				return __( 'Invalid Transaction', 'wc-skrill' );
			case 'Transaction frequency limit exceeded':
				return __( 'Transaction frequency limit exceeded', 'wc-skrill' );
			case 'Invalid credit card or bank account':
				return __( 'Invalid credit card or bank account', 'wc-skrill' );
			case 'Duplicate transaction':
				return __( 'Duplicate transaction', 'wc-skrill' );
			case 'Unknown failure reason. Try again':
				return __( 'Unknown failure reason. Try again', 'wc-skrill' );
			case 'Card expired':
				return __( 'Card expired', 'wc-skrill' );
			case 'Lost/Stolen card':
				return __( 'Lost/Stolen card', 'wc-skrill' );
			case 'Card Security Code check failed':
				return __( 'Card Security Code check failed', 'wc-skrill' );
			case 'Card restricted by card issuer':
				return __( 'Card restricted by card issuer', 'wc-skrill' );
			case 'Security violation':
				return __( 'Security violation', 'wc-skrill' );
			case 'Card blocked by card issuer':
				return __( 'Card blocked by card issuer', 'wc-skrill' );
			case "Customer's issuing bank not available":
				return __( "Customer's issuing bank not available", 'wc-skrill' );
			case 'Processing system error':
				return __( 'Processing system error', 'wc-skrill' );
			case 'Transaction not permitted to cardholder':
				return __( 'Transaction not permitted to cardholder', 'wc-skrill' );
			case 'Customer failed to complete 3DS':
				return __( 'Customer failed to complete 3DS', 'wc-skrill' );
			case 'Customer failed SMS verification':
				return __( 'Customer failed SMS verification', 'wc-skrill' );
			case 'Fraud engine declined':
				return __( 'Fraud engine declined', 'wc-skrill' );
			case 'Error in communication with provider':
				return __( 'Error in communication with provider', 'wc-skrill' );
			case 'Failure reason not specified':
				return __( 'Failure reason not specified', 'wc-skrill' );
			case 'An error occurred while processing your transaction. Please contact our support.':
				return __( 'An error occurred while processing your transaction. Please contact our support.', 'wc-skrill' );
			default:
				return __( 'Failure reason not specified', 'wc-skrill' );
		}
	}

	/**
	 * Translate Order Information Identifier.
	 *
	 * @param  string $status_identifier - status identifier.
	 * @return string
	 */
	public static function translate_transaction_status_identifier( $status_identifier ) {
		switch ( $status_identifier ) {
			case 'Processed':
				return __( 'Processed', 'wc-skrill' );
			case 'Pending':
				return __( 'Pending', 'wc-skrill' );
			case 'Cancelled':
				return __( 'Cancelled', 'wc-skrill' );
			case 'Failed':
				return __( 'Failed', 'wc-skrill' );
			case 'Chargeback':
				return __( 'Chargeback', 'wc-skrill' );
			case 'Refund successfull':
				return __( 'Refund successfull', 'wc-skrill' );
			case 'Refund failed':
				return __( 'Refund failed', 'wc-skrill' );
			case 'Refund pending':
				return __( 'Refund pending', 'wc-skrill' );
			case 'was considered fraudulent':
				return __( 'was considered fraudulent', 'wc-skrill' );
			case 'Not Verified':
				return __( 'Not Verified', 'wc-skrill' );
			case 'Abandoned by user':
				return __( 'Abandoned by user', 'wc-skrill' );
			default:
				return __( 'Abandoned by user', 'wc-skrill' );
		}
	}
}
