<?php
/**
 * Handles and process WC payment tokens API.
 * Seen in checkout page and my account->add payment method page.
 *
 * @package    WooCommerce
 * @category   Payment Gateways
 * @author     Revolut
 * @since      2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_Revolut_Payment_Tokens class.
 */
class WC_Revolut_Payment_Tokens {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'woocommerce_payment_token_deleted', array( $this, 'woocommerce_payment_token_deleted' ), 10, 2 );
		add_filter( 'woocommerce_payment_methods_list_item', array( $this, 'get_account_saved_payment_methods_list_item' ), 10, 2 );
	}

	/**
	 * Get saved payment method item.
	 *
	 * @param  array            $item          list item.
	 * @param  WC_Payment_Token $payment_token payment token.
	 */
	public function get_account_saved_payment_methods_list_item( $item, $payment_token ) {
		return $item;
	}

	/**
	 * Delete payment token on API
	 *
	 * @param  int              $token_id id token.
	 * @param  WC_Payment_Token $token payment token.
	 */
	public function woocommerce_payment_token_deleted( $token_id, $token ) {
		$gateway_revolut = new WC_Gateway_Revolut_CC();

		if ( $token->get_gateway_id() === $gateway_revolut->id ) {
			$revolut_customer_id = $gateway_revolut->get_revolut_customer_id( get_current_user_id() );
			if ( empty( $revolut_customer_id ) ) {
				if ( is_account_page() ) {
					wc_add_notice( 'Can not find customer ID', 'error' );
				}
			}

			$payment_method_id = $token->get_token();
			try {
				$gateway_revolut->api_client->delete( "/customers/$revolut_customer_id/payment-methods/$payment_method_id" );
			} catch ( Exception $e ) {
				if ( is_account_page() ) {
					wc_add_notice( $e->getMessage(), 'error' );
				}
			}
		}
	}
}

new WC_Revolut_Payment_Tokens();
