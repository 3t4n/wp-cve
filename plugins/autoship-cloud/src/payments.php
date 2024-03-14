<?php

/**
* Supported Payment Gateways include:
* === Standard Woocommerce Token Table Gateways ===
* Stripe,
* CyberSource ( v2.2.0+ ),
* Nmi,
* PayaV1,
* TrustCommerce,
* SagePay,
* Opayo (Formally SagePay),
* Braintree ( v2.5.0+ ),
* AuthorizeNet ( v3.3.0+ )
* Checkout.Com
* === Non-Standard ( Skyverge & Other Framework ) Gateways ===
* AuthorizeNet,
* PayPal,
* PayPal Payments,
* Braintree,
* Square,
* cybersource_credit_card
* === Plugins and Documentation ===
* @link https://github.com/jin0x/wc-cybersource/
* @link https://support.autoship.cloud/article/369-braintree-payments
* @link https://support.autoship.cloud/article/368-paypal-express-checkout-with-autoship-cloud
* @link https://support.autoship.cloud/article/366-authorize-net-cim-payments
* @link https://support.autoship.cloud/article/480-nmi-payments
* @link https://support.autoship.cloud/article/367-stripe-payments
* @link https://docs.google.com/document/d/1tlz1N1ajobkhAdE3J25kaWvWsFDzZDF3pDL3wFhwvRU/edit?usp=sharing
* @link https://woocommerce.com/products/sage-pay-form/
*/

/*

  Checkout & Add Payment Process / Non-Standard
  - 1. Catch the Call             // Hook into wc_payment_gateway_{Gateway-ID}_add_payment_method_transaction_result
  - 2. Start the Process          // autoship_add_non_wc_token_payment_method( $response, $order, 'Braintree' )
  - 3. Create a Token & Normalize // autoship_payment_method_tokenization filter to get token
  - 4. Generate the Data          // autoship_add_general_payment_method to get payment method data -- ** Same a
  - 5. Create the Payment         // autoship_add_payment_method( $autoship_method_data ) -- ** Same a

  Checkout & Add Payment Process / Standard
  - 1. Catch the Call             // Hook into woocommerce_new_payment_token
  - 2. Start the Process          // autoship_add_tokenized_payment_method( $token_id )
  - 3. Get the Token & Normalize  // WC_Payment_Tokens::get( $token_id );
  - 4. Generate the Data          // autoship_add_general_payment_method to get payment method data -- ** Same a
  - 5. Create the Payment         // autoship_add_payment_method( $autoship_method_data ) -- ** Same a

  Apply to All Scheduled Orders Btn / Non-Standard
  - 1. Hook into Actions          // Hook into wc_{Gateway-ID}_my_payment_methods_table_method_actions
  - 2. Generate the BTN Html      // autoship_display_apply_payment_method_to_all_scheduled_orders_skyverge_btn()
  - 3. Hook into WP & Handle post // autoship_update_payment_method_on_all_scheduled_orders() -- ** Same b
  - 4. Create a Token & Normalize // autoship_payment_method_tokenization filter to get token -- ** Same b
  - 5. Generate the Data          // autoship_add_general_payment_method to get payment method data -- ** Same b
  - 6. Set the Flag               // payment method data > ApplyToScheduledOrders = true; -- ** Same b
  - 5. Upsert The Method          // autoship_update_payment_method() -- ** Same b

  Apply to All Scheduled Orders Btn / Standard
  - 1. Hook into Actions          // Hook into woocommerce_payment_methods_list_item
  - 2. Generate the BTN Html      // autoship_display_apply_payment_method_to_all_scheduled_orders_btn()
  - 3. Hook into WP & Handle post // autoship_update_payment_method_on_all_scheduled_orders() -- ** Same b
  - 4. Create a Token & Normalize // autoship_payment_method_tokenization filter to get token -- ** Same b
  - 5. Generate the Data          // autoship_add_general_payment_method to get payment method data -- ** Same b
  - 6. Set the Flag               // payment method data > ApplyToScheduledOrders = true; -- ** Same b
  - 5. Upsert The Method          // autoship_update_payment_method() -- ** Same b

*/

require_once( 'QPilot/PaymentData.php' );

/**
 * Returns the Skyverge Gateways that create early tokens
 * missing customer id information
 *
 * @return array The gateways.
 */
function autoship_get_late_token_skyverge_gateways(){

  return array(
    'authorize_net_cim_credit_card'       => 'authorize_net',
    'braintree_credit_card'               => 'braintree',
    'braintree_paypal'                    => 'braintree_paypal',
    'cybersource_credit_card'             => 'cybersource_cc' );

}

/**
 * Returns the type of gateway it is ( tokenized or non-standard )
 * @param string The gateway
 * @return string standard for tokenized otherwise the gateway.
 */
function autoship_get_payment_method_gateway_type( $gateway ) {

  $types = apply_filters( 'autoship_payment_method_gateway_type', array_merge( array(
    'stripe'                              => 'standard',
    'stripe_sepa'                         => 'standard',
    'trustcommerce'                       => 'standard',
    'autoship-test-gateway'               => 'test_gateway',
    'ppec_paypal'                         => 'ppec',
    'nmi_gateway_woocommerce_credit_card' => 'standard',
    'sagepaymentsusaapi'                  => 'standard',
    'square_credit_card'                  => 'square',
    'sagepaydirect'                       => 'standard',
    'cybersource'                         => 'standard',
    'wc_checkout_com_cards'               => 'standard',
  ), autoship_get_late_token_skyverge_gateways() ) );

  return isset( $types[$gateway] ) ? $types[$gateway] : '';

}

/**
 * Returns an array of valid payment method ids.
 *
 * @return array Payment Method IDs and function names.
 */
function autoship_get_valid_payment_methods() {

  return apply_filters( 'autoship_valid_payment_method_ids', array(
    'authorize_net_cim_credit_card'       => 'authorize_net',
    'cybersource_credit_card'             => 'cybersource_cc',
    'stripe'                              => 'stripe',
    'stripe_sepa'                         => 'stripe_sepa',
    'trustcommerce'                       => 'trustcommerce',
    'autoship-test-gateway'               => 'test_gateway',
    'ppec_paypal'                         => 'ppec',
    'braintree_credit_card'               => 'braintree',
    'braintree_paypal'                    => 'braintree_paypal',
    'cybersource'                         => 'cybersource',
    'nmi_gateway_woocommerce_credit_card' => 'nmi',
    'sagepaymentsusaapi'                  => 'sagepaymentsusaapi',
    'square_credit_card'                  => 'square',
    'sagepaydirect'                       => 'sage',
    'wc_checkout_com_cards'               => 'checkout_com',
  ) );

}

/**
 * Returns the valid payment method QPilot Type.
 *
 * @return string Payment Method type.
 */
function autoship_get_valid_payment_method_type( $method_gateway_id ) {

  $types = apply_filters( 'autoship_valid_payment_method_types', array(
    'authorize_net_cim_credit_card'       => 'AuthorizeNet',
    'cybersource_credit_card'             => 'CyberSourceV2',
    'stripe'                              => 'Stripe',
    'stripe_sepa'                         => 'Stripe',
    'trustcommerce'                       => 'TrustCommerce',
    'autoship-test-gateway'               => 'Test',
    'ppec_paypal'                         => 'ppec',
    'braintree_credit_card'               => 'Braintree',
    'braintree_paypal'                    => 'Braintree',
    'cybersource'                         => 'CyberSource',
    'nmi_gateway_woocommerce_credit_card' => 'Nmi',
    'sagepaymentsusaapi'                  => 'PayaV1',
    'square_credit_card'                  => 'Square',
    'sagepaydirect'                       => 'Sage',
    'wc_checkout_com_cards'               => 'Checkout',
  ) );

  return isset( $types[$method_gateway_id] )? $types[$method_gateway_id] : '';

}

/**
 * Filters the Payment Method Types for Optional Gateways
 *
 * @param array $types
 * @return array The filtered Types
 */
function autoship_adjust_payment_method_gateway_type_for_optional_gateways( $types ){

 if ( 'yes' == autoship_get_support_paypal_payments_option() ){

   $types['ppcp-gateway']             = 'ppcp';
   $types['ppcp-credit-card-gateway'] = 'ppcp_cc';

 }

 return $types;

}
add_filter( 'autoship_payment_method_gateway_type', 'autoship_adjust_payment_method_gateway_type_for_optional_gateways', 10, 1);

/**
 * Filters the Payment Method Types for Optional Gateways
 *
 * @param array $types
 * @return array The filtered Types
 */
function autoship_enable_payment_method_ids_for_optional_gateways( $types ){

 if ( 'yes' == autoship_get_support_paypal_payments_option() ){

   $types['ppcp-gateway']             = 'ppcp';
   $types['ppcp-credit-card-gateway'] = 'ppcp_cc';

 }


 if ( 'yes' == autoship_get_support_cod_payments_option() ){
 
  $types['cod'] = 'payment_method_cod';

  }

 return $types;

}
add_filter( 'autoship_valid_payment_method_ids', 'autoship_enable_payment_method_ids_for_optional_gateways', 10, 1);

/**
 * Filters the Payment Method Types for Optional Gateways
 *
 * @param array $types
 * @return array The filtered Types
 */
function autoship_enable_payment_method_types_for_optional_gateways( $types ){

 if ( 'yes' == autoship_get_support_paypal_payments_option() ){

   $types['ppcp-gateway']             = 'Paypalv2';
   $types['ppcp-credit-card-gateway'] = 'Paypalv2';

 }

 return $types;

}
add_filter( 'autoship_valid_payment_method_types', 'autoship_enable_payment_method_types_for_optional_gateways', 10, 1);

/**
 * Returns the token id or token object based on the supplied token.
 * @param string $token The token to look up.
 * @param bool $tokenize If true return the WC Token else the id.
 * @return WC_Payment_token|int The WC Payment Token object or Token id.
 */
function autoship_get_related_tokenized_id( $token, $tokenize = true ) {
	global $wpdb;
  $token_id     = $wpdb->get_var( $wpdb->prepare(
    "SELECT token_id FROM {$wpdb->prefix}woocommerce_payment_tokens WHERE token = %s",
    $token
  ) );

  return $tokenize ? WC_Payment_Tokens::get( $token_id ) : $token_id;

}

/**
 * Returns the payment method general customer data.
 * @param int|WC_Customer $wc_customer The WC Customer ID or Object.
 * @param array $data Optional. Customer Data to use as an override.
 * @return array The Customer Data
 */
function autoship_get_general_payment_method_customer_data( $wc_customer, $data = array() ){

  if ( is_numeric( $wc_customer ) )
  $wc_customer = new WC_Customer( $wc_customer );

  $data = array_merge(
    array( 'customerId' => $wc_customer->get_id() ),
    $data
  );

  return autoship_generate_customer_billing_data ( $wc_customer, $data );

}

/**
 * Returns the payment method data.
 * @param int|WC_Customer $wc_customer The WC Customer ID or Object.
 * @param array $data Optional. Customer Data to use as an override.
 * @return array The Customer Data
 */
function autoship_get_general_payment_method_data( $token_id, $gateway ){

  // Get the Type of Gateway ( standard tokenized or non-standard )
  $gateway_type= autoship_get_payment_method_gateway_type( $gateway );

  // Retrieve the token and run it through our filter so that non-standard gateway
  // Payment methods can be retrieved.
  $token       = apply_filters( 'autoship_payment_method_tokenization', WC_Payment_Tokens::get( $token_id ), $token_id, $gateway_type );

  return !is_null( $token ) ?
  autoship_add_general_payment_method( $token, true ) : array();

}

/**
 * Returns the current sites valid payment gateways
 * @return array The valid supported payment gateways
 */
function autoship_get_available_valid_gateways(){

  // Get the currently enabled gateways
  $current_gateways = WC()->payment_gateways->get_available_payment_gateways();

  // Get the valid Autoship Payment Gateways ids
  $valid_gateways = autoship_get_valid_payment_methods();

  // Return only valid gateways
  return array_intersect_key( $current_gateways, $valid_gateways );

}

/**
 * Returns the current valid saved payment methods for a customer.
 * @param int   $customer_id The WC Customer ID.
 * @return array The valid supported saved methods
 */
function autoship_get_available_valid_customer_saved_methods( $customer_id ){

  // get the users current methods if methods aren't supplied
  $methods = wc_get_customer_saved_methods_list( $customer_id );

  // filter the methods for only valids.
  return autoship_filter_saved_methods( $methods );

}

/**
 * Returns the filtered version of the supplied saved payment methods.
 *
 * @param array $methods An array of saved customer payment methods
 * @return array The valid supported saved methods
 */
function autoship_filter_saved_methods( $methods ){

  // If there are no saved methods or no credit card saved methods
  if ( empty( $methods ) || !isset( $methods['cc'] ) )
  return array();

  $valid_methods = array();

  // Get the current valid gateways
  $valid_gateways = autoship_get_available_valid_gateways();

  foreach ($methods['cc'] as $key => $method) {

    if ( isset( $valid_gateways[$method['method']['gateway']] ) )
    $valid_methods['cc'][$key] = $method;

  }

  // Return only valid saved methods
  return $valid_methods;

}

// ========================================
// Order Payment Method Get functions
// Used on Checkout & Order Creation
// ========================================

/**
 * Main Function to Retrieve the Payment Gateway information for an order.
 * This information includes the Token, Customer ID, Description,
 * Last four of the card number, Card Type, and Gateway type.
 * @see autoship_get_valid_payment_methods()
 *
 * @param int $order_id The WC Order number.
 *
 * @return QPilotPaymentData|null The payment object or null if not supported.
 */
function autoship_get_order_payment_data( $order_id ) {
  
  // Get the Gateway ID from the order.
  $order = wc_get_order( $order_id );
	$payment_method_id = $order->get_payment_method();
  
  // Retrieve the array of valid Autoship Gateways.
  $valid_methods = autoship_get_valid_payment_methods();

	$payment_data = null;
  if ( isset( $valid_methods[$payment_method_id] ) ){

    $function = "autoship_get_{$valid_methods[$payment_method_id]}_order_payment_data";
    
    if ( function_exists( $function ) )
    $payment_data = $function( $order_id, $order );

  }

	return apply_filters( 'autoship_order_payment_data', $payment_data, $order_id );
}

/**
 * Retrieves the Payment information from the order for the Cash On Delivery Gateway
 *
 * @param int $order_id The WC Order number.
 * @return QPilotPaymentData
 */
function autoship_get_payment_method_cod_order_payment_data( $order_id, $order ) {
  $payment_data              = new QPilotPaymentData();
  $payment_data->description = 'Cash On Delivery';
  $payment_data->type        = 'Other';

  return $payment_data;
}

/**
 * Retrieves the Payment information from the order for the Braintree PayPal Gateway
 *
 * @param int $order_id The WC Order number.
 * @return QPilotPaymentData
 */
function autoship_get_braintree_paypal_order_payment_data( $order_id, $order ) {

	$token_string       = $order->get_meta('_wc_braintree_paypal_payment_token' );
	$paypal_customer_id = $order->get_meta( '_wc_braintree_paypal_customer_id' );
	if ( ! empty( $token_string ) ) {
		$payment_data                      = new QPilotPaymentData();
		$card_type                         = "PayPal";
		$last_four                         = substr( $token_string, - 4 );
		$payment_data->description         = sprintf( '%s ending in %s', ucfirst( $card_type ), $last_four );
		$payment_data->type                = 'Braintree';
		$payment_data->gateway_payment_id  = $token_string;
		$payment_data->gateway_customer_id = $paypal_customer_id;
		$payment_data->last_four           = $last_four;
		$payment_data->expiration          = NULL;

		return $payment_data;
	}

	return null;
}

/**
 * Retrieves the Payment information from the order for the Braintree Credit Card Gateway
 *
 * @param int $order_id The WC Order number.
 * @return QPilotPaymentData
 */
function autoship_get_braintree_order_payment_data( $order_id, $order ) {

	$token_string = $order->get_meta( '_wc_braintree_credit_card_payment_token' );
	$customer_id  = $order->get_meta( '_wc_braintree_credit_card_customer_id' );
	if ( ! empty( $token_string ) && ! empty( $customer_id ) ) {
		$payment_data                      = new QPilotPaymentData();
		$card_type                         = $order->get_meta( '_wc_braintree_credit_card_card_type' );
		$last_four                         = $order->get_meta( '_wc_braintree_credit_card_account_four' );

    // Expiration in 2025-02 format.
		$expiry_date                       = $order->get_meta( '_wc_braintree_credit_card_card_expiry_date' );
    $expiration                        = explode( '-', $expiry_date );
    $expiration[0]                     = substr( $expiration[0], -2 );
    $expiry_date                       = $expiration[1] . '/' . $expiration[0];
		$payment_data->description         = sprintf( '%s ending in %s (expires %s)', ucfirst( $card_type ), $last_four, $expiry_date );
		$payment_data->type                = 'Braintree';
		$payment_data->gateway_payment_id  = $token_string;
		$payment_data->gateway_customer_id = $customer_id;
		$payment_data->last_four           = $last_four;
		$payment_data->expiration          = $expiration[1] . $expiration[0];;

		return $payment_data;
	}

	return null;
}

/**
 * Retrieves the Payment information from the order for the Paypal EC Gateway
 *
 * @param int $order_id The WC Order number.
 * @return QPilotPaymentData
 */
function autoship_get_ppec_order_payment_data( $order_id, $order ) {

  $payment_agreement_id = $order->get_meta( '_ppec_billing_agreement_id' );
	if ( ! empty( $payment_agreement_id ) ) {
		$payment_data                     = new QPilotPaymentData();
		$card_type                        = 'PayPal';
		$last_four                        = substr( $payment_agreement_id, - 4 );
		$payment_data->description        = sprintf( '%s ending in %s', ucfirst( $card_type ), $last_four );
		$payment_data->type               = 'PayPal';
		$payment_data->gateway_payment_id = $payment_agreement_id;

		return $payment_data;
	}

	return null;
}

/**
 * Retrieves the Payment information from the order for the Paypal Payments Gateway
 *
 * @param int $order_id The WC Order number.
 * @return QPilotPaymentData
 */
function autoship_get_ppcp_order_payment_data( $order_id, $order ) {

	$payment_token = $order->get_meta( 'payment_token_id' );

	if ( ! empty( $payment_token ) ) {

		$payment_data                       = new QPilotPaymentData();
		$payment_data->description          = sprintf( '%s ending in %s', ucfirst( 'PayPal' ), substr( $payment_token, - 4 ) );
		$payment_data->type                 = 'Paypalv2';
		$payment_data->gateway_payment_id   = $payment_token;
		$payment_data->gateway_payment_type = 25;

		return $payment_data;

	}

	return null;
}

/**
 * Retrieves the Payment information from the order for the Paypal Payments Gateway
 *
 * @param int $order_id The WC Order number.
 * @return QPilotPaymentData
 */
function autoship_get_ppcp_cc_order_payment_data( $order_id, $order ) {

  $payment_token = $order->get_meta( 'payment_token_id' );

	if ( ! empty( $payment_token ) ) {

		$payment_data                     = new QPilotPaymentData();
		$payment_data->description        = sprintf( '%s ending in %s', ucfirst( 'PayPal' ), substr( $payment_token, - 4 ) );
		$payment_data->type               = 'PaypalV2';
		$payment_data->gateway_payment_id = $payment_token;
		$payment_data->gateway_payment_type = 26;

		return $payment_data;

	}

	return null;
}

/**
 * Retrieves the Payment information from the order for the Authorize.net Gateway
 *
 * @param int $order_id The WC Order number.
 * @return QPilotPaymentData
 */
function autoship_get_authorize_net_order_payment_data( $order_id, $order ) {

  $token_string = $order->get_meta( '_wc_authorize_net_cim_credit_card_payment_token' );
	$customer_id  = $order->get_meta( '_wc_authorize_net_cim_credit_card_customer_id' );

	if ( ! empty( $token_string ) && ! empty( $customer_id ) ) {

		$payment_data                      = new QPilotPaymentData();
		$card_type                         = $order->get_meta( '_wc_authorize_net_cim_credit_card_card_type' );
		$last_four                         = $order->get_meta( '_wc_authorize_net_cim_credit_card_account_four' );

    // Authnet Stores Expiration in YY-MM format so we need to adjust for Autoship
    // Expiration in format 23-02 should be 0223
		$expiry_date                       = $order->get_meta( '_wc_authorize_net_cim_credit_card_card_expiry_date' );
    $expiration                        = explode( '-', $expiry_date );

		$payment_data->description         = isset( $expiration[1] ) ?
                                         sprintf( '%s ending in %s (expires %s)', ucfirst( $card_type ), $last_four, $expiration[1] . '/' . $expiration[0] ) : sprintf( '%s ending in %s', ucfirst( $card_type ), $last_four );
		$payment_data->type                = 'AuthorizeNet';
		$payment_data->gateway_payment_id  = $token_string;
		$payment_data->gateway_customer_id = $customer_id;
		$payment_data->last_four           = $last_four;
		$payment_data->expiration          = isset( $expiration[1] ) ? $expiration[1] . $expiration[0] : NULL;

		return $payment_data;
	}

	return null;
}

/**
 * Retrieves the Payment information from the order for the Cybersource CC Gateway
 *
 * @param int $order_id The WC Order number.
 * @return QPilotPaymentData
 */
function autoship_get_cybersource_cc_order_payment_data( $order_id, $order ) {

  $token_string = $order->get_meta( '_wc_cybersource_credit_card_payment_token' );
	$customer_id  = $order->get_meta( '_wc_cybersource_credit_card_customer_id' );
	if ( ! empty( $token_string ) && ! empty( $customer_id ) ) {
		$payment_data                      = new QPilotPaymentData();
		$card_type                         = $order->get_meta( '_wc_cybersource_credit_card_card_type' );
		$last_four                         = $order->get_meta( '_wc_cybersource_credit_card_account_four' );

    // Cybersource Stores Expiration in YY-MM format so we need to adjust for Autoship
    // Expiration in format 23-02 should be 0223
		$expiry_date                       = $order->get_meta( '_wc_cybersource_credit_card_card_expiry_date' );
    $expiration                        = explode( '-', $expiry_date );

		$payment_data->description         = isset( $expiration[1] ) ?
                                         sprintf( '%s ending in %s (expires %s)', ucfirst( $card_type ), $last_four, $expiration[1] . '/' . $expiration[0] ) : sprintf( '%s ending in %s', ucfirst( $card_type ), $last_four );

		$payment_data->type                = 'CybersourceV2';
		$payment_data->gateway_payment_id  = $token_string;
		$payment_data->gateway_customer_id = $customer_id;
		$payment_data->last_four           = $last_four;
		$payment_data->expiration          = isset( $expiration[1] ) ? $expiration[1] . $expiration[0] : NULL;

		return $payment_data;
	}

	return null;
}

/**
 * Retrieves the Payment information from the order for the Stripe Gateway
 *
 * @param int $order_id The WC Order number.
 * @return QPilotPaymentData
 */
function autoship_get_stripe_order_payment_data( $order_id, $order ) {

  // Stripe version >= 4.0.0
	$token_id = $order->get_meta( '_stripe_source_id' );

	// Stripe version < 4.0.0
	if ( empty( $token_id ) ) {
		$token_id = $order->get_meta( '_stripe_card_id' );
	}

	$customer_id = $order->get_meta( '_stripe_customer_id' );
	if ( ! empty( $token_id ) && ! empty( $customer_id ) ) {

    $token = autoship_get_related_tokenized_id( $token_id );

    if ( ! empty( $token ) ) {
      $expiration   = $token->get_expiry_month() . substr( $token->get_expiry_year(), -2);
      $payment_data = new QPilotPaymentData();
      $payment_data->description         = $token->get_display_name();
      $payment_data->type                = 'Stripe';
      $payment_data->gateway_payment_id  = $token->get_token();
      $payment_data->gateway_customer_id = $customer_id;
  		$payment_data->last_four           = $token->get_last4();
  		$payment_data->expiration          = $expiration;
      return $payment_data;
    }

  }

	return null;
}

/**
 * Retrieves the charge info using the Transaction ID and Stipe API
 *
 * @param string $transaction_id The Transaction id
 * @return array|null The Stripe charge information
 */
function autoship_get_stripe_charge_by_transaction_id( $transaction_id ) {
	if ( class_exists( 'WC_Stripe_API' ) ) {
		$stripe   = new WC_Stripe_API();
		$response = $stripe::request( null, 'charges/' . $transaction_id, 'GET' );
		if ( ! is_wp_error( $response ) ) {
			return $response;
		}
	}

	return null;
}

/**
 * Retrieves the Payment information from the order for the Stripe Sepa Gateway
 *
 * @param int $order_id The WC Order number.
 * @return QPilotPaymentData
 */
function autoship_get_stripe_sepa_order_payment_data( $order_id, $order ) {

	// Get the Source ID and Customer ID
	$token_id = $order->get_meta( '_stripe_source_id' );
	$customer_id = $order->get_meta( '_stripe_customer_id' );

	if ( ! empty( $token_id ) && ! empty( $customer_id ) ) {

    $payment_data = new QPilotPaymentData();
    $payment_data->description         = 'SEPA IBAN';
    $payment_data->type                = 'Stripe';
    $payment_data->gateway_payment_id  = $token_id;
    $payment_data->gateway_customer_id = $customer_id;
		$payment_data->gateway_payment_type= 21;

    return $payment_data;

  }

	return null;
}

/**
 * Retrieves the Payment information from the order for the Checkout.com Gateway
 * NOTE The Checkout.com gateway plugin doesn't currently store the token on the WC Order
 * unless the order is a WC Subscription order
 *
 * @param int $order_id The WC Order number.
 * @return QPilotPaymentData
 */
function autoship_get_checkout_com_order_payment_data( $order_id, $order ) {

	// Retrieve the Payment ID ( i.e. transaction id )
	$transaction_id = $order->get_meta( '_cko_payment_id' );

  // Retrieve the Token Info from the API
  $data = autoship_get_checkout_com_charge_by_transaction_id( $transaction_id );

	if ( ! empty( $data ) && isset( $data['token'] ) ) {

    // Get the token from Woo Token Tables
    $token = autoship_get_related_tokenized_id( $data['token'] );

    if ( ! empty( $token ) ) {

      $expiration   = $token->get_expiry_month() . substr( $token->get_expiry_year(), -2);
      $payment_data = new QPilotPaymentData();
      $payment_data->description         = $token->get_display_name();
      $payment_data->type                = 'Checkout';
      $payment_data->gateway_payment_id  = $token->get_token();
  		$payment_data->last_four           = $token->get_last4();
  		$payment_data->expiration          = $expiration;

      return $payment_data;
    }

  }

	return null;
}

/**
 * Retrieves the Payment information from the order for the Test Gateway
 *
 * @param int $order_id The WC Order number.
 * @return QPilotPaymentData
 */
function autoship_get_test_gateway_order_payment_data( $order_id, $order ) {
	$gateway_customer_id = $order->get_meta( '_autoship_test_gateway_customer_id' );
	$gateway_payment_id  = $order->get_meta( '_autoship_test_gateway_payment_id' );
	if ( ! empty( $gateway_customer_id ) && ! empty( $gateway_payment_id ) ) {
		$payment_data                      = new QPilotPaymentData();
		$payment_data->type                = 'Test';
		$payment_data->gateway_payment_id  = $gateway_payment_id;
		$payment_data->gateway_customer_id = $gateway_customer_id;
		$payment_data->description         = 'Test Payment Method';

		return $payment_data;
	}

	return null;
}

/**
 * Retrieves the Payment information from the order for the Braintree Credit Card Gateway
 * NOTE Not Used
 * @param int $order_id The WC Order number.
 * @return QPilotPaymentData
 */
function autoship_get_braintree_gateway_order_payment_data( $order_id, $order ) {
	$payment_data = new QPilotPaymentData();

	$cc_token = $order->get_meta( '_wc_braintree_paypal_payment_token' );
	$pp_token = $order->get_meta( '_wc_braintree_paypal_payment_token' );

	// See if it's CC or PP
	if ( ! empty( $cc_token ) ) {
		$cc_card_type                      = $order->get_meta( '_wc_braintree_credit_card_card_type' );
		$cc_last_four                      = $order->get_meta( '_wc_braintree_credit_card_account_four' );
		$cc_expy                           = $order->get_meta( '_wc_braintree_credit_card_card_expiry_date' );
		$cc_customer_id                    = $order->get_meta( '_wc_braintree_credit_card_customer_id' );
		$payment_data->type                = 'Braintree';
		$payment_data->description         = $cc_card_type . " ending in " . $cc_last_four . ", expires: " . $cc_expy;
		$payment_data->gateway_payment_id  = $cc_token;
		$payment_data->gateway_customer_id = $cc_customer_id;
	} else if ( ! empty( $pp_token ) ) {
		$pp_last_four                      = substr( $pp_token, - 4 );
		$pp_expy                           = $order->get_meta( '_wc_braintree_credit_card_card_expiry_date' );
		$pp_customer_id                    = $order->get_meta( '_wc_braintree_paypal_customer_id' );
		$payment_data->type                = 'Braintree';
		$payment_data->description         = "Paypal ending in " . $pp_last_four . ", expires: " . $pp_expy;
		$payment_data->gateway_customer_id = $pp_customer_id;
		$payment_data->gateway_payment_id  = $pp_token;
	} else {
		$payment_data = null;
	}

	return $payment_data;
}

/**
 * Retrieves the Payment information from the order for the Cyber Source Gateway
 *
 * @param int $order_id The WC Order number.
 * @return QPilotPaymentData
 */
function autoship_get_cybersource_order_payment_data( $order_id, $order ) {
	$token_id = $order->get_meta( 'cybersource_token_id' );
	if ( ! empty( $token_id ) ) {
		$token = WC_Payment_Tokens::get( $token_id );
		if ( ! empty( $token ) ) {
			$payment_data                      = new QPilotPaymentData();
			$payment_data->description         = $token->get_display_name();
			$payment_data->type                = 'CyberSource';
			$payment_data->gateway_payment_id  = $token->get_token();
  		$payment_data->last_four           = $token->get_last4();

      // Get Expiration in MMYY format for Qpilot.
      $expiration   = $token->get_expiry_month() . substr( $token->get_expiry_year(), -2);
  		$payment_data->expiration          = $expiration;

			return $payment_data;
		}
	}

	return null;
}

/**
 * Retrieves the Payment information from the order for the NMI Gateway
 * Since NMI uses Customer Vault ID use tokenized data in the
 * gatewayCustomerId (since that's customer vault ID), and nothing in the gatewayPaymentId
 *
 * @param int $order_id The WC Order number.
 *
 * @return QPilotPaymentData
 */
function autoship_get_nmi_order_payment_data( $order_id, $order ) {

  $token_id      = $order->get_meta( '_wc_nmi_gateway_woocommerce_credit_card_payment_token' );
  $customer_id   = $order->get_meta( '_customer_user' );

  if ( ! empty( $token_id ) && ! empty( $customer_id ) ) {
    $token = autoship_get_related_tokenized_id( $token_id );

    if ( !empty( $token ) ){
  		$payment_data = new QPilotPaymentData();
  		$payment_data->description         = $token->get_display_name();
  		$payment_data->type                = 'Nmi';
  		$payment_data->gateway_customer_id = $token->get_token();
  		$payment_data->last_four           = $token->get_last4();

      // Get Expiration in MMYY format for Qpilot.
      $expiration   = $token->get_expiry_month() . substr( $token->get_expiry_year(), -2);
  		$payment_data->expiration          = $expiration;


      return $payment_data;
    }
	}
 	return null;
}

/**
 * Updates the NMI Payment gateway to Force Tokenization.
 * for non-logged in users.
 * attached to the {@see nmi_gateway_woocommerce_credit_card_should_force_tokenize} filter
 * @param bool $force. The current force tokenization flag.
 * @return bool True.
 */
function autoship_force_nmi_payment_tokenization( $force ){
  return true;
}

/**
 * Retrieves the Payment information from the order for the Sage Payment Gateway
 *
 * @param int $order_id The WC Order number.
 * @return QPilotPaymentData
 */
function autoship_get_sagepaymentsusaapi_order_payment_data( $order_id, $order ) {

  $token = null;

  // Get the Sage / Paya Token & Customer ID
  $order_token_id   = $order->get_meta( '_SageToken' );
  $customer_id      = $order->get_meta( '_customer_user' );
  $tokens           = WC_Payment_Tokens::get_customer_tokens( $customer_id, 'sagepaymentsusaapi' );

  // If the token is not empty get the other token payment info.
	if ( ! empty( $order_token_id ) ) {

    // Now loop through and grab the token object for this order
    foreach ($tokens as $wctoken) {

      if ( $order_token_id === $wctoken->get_token() ){

        $token = $wctoken;
        break;

      }

    }

		if ( ! empty( $token ) ) {
			$payment_data                      = new QPilotPaymentData();
			$payment_data->description         = $token->get_display_name();
			$payment_data->type                = 'PayaV1';
			$payment_data->gateway_customer_id = $token->get_token();
  		$payment_data->last_four           = $token->get_last4();

      // Get Expiration in MMYY format for Qpilot.
      $expiration   = $token->get_expiry_month() . substr( $token->get_expiry_year(), -2);
  		$payment_data->expiration          = $expiration;


			return $payment_data;
		}
	}

	return null;
}

/**
 * Retrieves the Payment information from the order for the Trustcommerce Gateway
 *
 * @param int $order_id The WC Order number.
 * @return QPilotPaymentData
 */
function autoship_get_trustcommerce_order_payment_data( $order_id, $order ) {

  // Grab the Token from the order.
 	$token_id  = $order->get_meta( '_trustcommerce_customer_id' );

	if ( ! empty( $token_id ) ) {

    $token = autoship_get_related_tokenized_id( $token_id );

		if ( ! empty( $token ) ) {
  		$payment_data = new QPilotPaymentData();
  		$payment_data->description         = $token->get_display_name();
  		$payment_data->type                = 'TrustCommerce';
  		$payment_data->gateway_customer_id = $token->get_token();
  		$payment_data->last_four           = $token->get_last4();

      // Get Expiration in MMYY format for Qpilot.
      $expiration   = $token->get_expiry_month() . substr( $token->get_expiry_year(), -2);
  		$payment_data->expiration          = $expiration;

			return $payment_data;
		}

	}

	return null;
}

/**
 * Retrieves the Payment information from the order for the Square Gateway
 *
 * @param int $order_id The WC Order number.
 * @return QPilotPaymentData
 */
function autoship_get_square_order_payment_data( $order_id, $order ) {

  // Grab the Customer ID and Token from the order.
 	$customer_id  = $order->get_meta( '_wc_square_credit_card_customer_id' );
 	$token_id     = $order->get_meta( '_wc_square_credit_card_payment_token' );


	if ( ! empty( $token_id ) ) {
    $payment_data                      = new QPilotPaymentData();
    $card_type                         = $order->get_meta( '_wc_square_credit_card_card_type' );
    $last_four                         = $order->get_meta( '_wc_square_credit_card_account_four' );
    $expiry_date                       = $order->get_meta( '_wc_square_credit_card_card_expiry_date' );

    $expiry_date                       = explode( '-', $expiry_date );
    $expiry_date[0]                    = strlen( $expiry_date[0] ) > 2 ? substr( $expiry_date[0], -2 ) : $expiry_date[0];
    $payment_data->description         = sprintf( '%s ending in %s (expires %s)', ucfirst ( $card_type ), $last_four, $expiry_date[1] . '/' . $expiry_date[0] );
    $payment_data->type                = 'Square';
    $payment_data->gateway_payment_id  = $token_id;
    $payment_data->gateway_customer_id = $customer_id;
    $payment_data->last_four           = $last_four;

    // Get Expiration in MMYY format for Qpilot.
    $expiration   = $expiry_date[1] . $expiry_date[0];
    $payment_data->expiration          = $expiration;

    return $payment_data;
	}

	return null;
}

/**
 * Attaches the SagePay Saved Payment method token to the WC Order when a
 * customer uses a saved payment method at checkout.
 *
 * @param int $order_id The WC Order ID.
 * @param array $posted_data The posted data
 * @param WC_Order $order The WC Order.
 */
function autoship_sagepaydirect_order_payment_data_saved_payment_patch( $order_id, $posted_data, $order ){

  // Only apply patch to Autoship Orders, orders that used the SagePay direct gateway, and those that don't have a token attached.
	$payment_method_id = isset( $posted_data['payment_method'] ) ? wc_clean( wp_unslash( $posted_data['payment_method'] ) ) : false;

  if ( 'sagepaydirect' !== $payment_method_id )
  return;

	// Process order items and remove non-autoship items.
	// If empty this order does not have scheduled items
  $order_items = $order->get_items();
	if ( empty( autoship_group_order_items( $order_items ) ) )
  return;

  // Check for the SagePay Posted info and see if this is a saved or new payment method.
  $sage_card_save_token	 = isset( $_POST['wc-sagepaydirect-new-payment-method'] ) && !empty( $_POST['wc-sagepaydirect-new-payment-method'] ) ?
  wc_clean( $_POST['wc-sagepaydirect-new-payment-method'] ) : false;

  $sage_card_token 		   = isset( $_POST['wc-sagepaydirect-payment-token'] ) && !empty( $_POST['wc-sagepaydirect-payment-token'] ) ?
  wc_clean( $_POST['wc-sagepaydirect-payment-token'] )      : false;

  if( $sage_card_token !== 'new' && $sage_card_token ) {

    $token = new WC_Payment_Token_CC();
    $token = WC_Payment_Tokens::get( $sage_card_token );
    $order->update_meta_data( '_SagePayDirectToken', $token->get_token() );
    $order->save();

  }

}

/**
 * Retrieves the Payment information from the order for the SagePay Gateway
 *
 * @param int $order_id The WC Order number.
 * @return QPilotPaymentData
 */
function autoship_get_sage_order_payment_data( $order_id, $order ) {

  // Grab the Customer ID and Token from the order.
 	$token_id = $order->get_meta( '_SagePayDirectToken' );

	if ( ! empty( $token_id ) ) {

    $token = autoship_get_related_tokenized_id( $token_id );

		if ( ! empty( $token ) ) {

  		$payment_data = new QPilotPaymentData();
  		$payment_data->description         = $token->get_display_name();
  		$payment_data->type                = 'Sage';
      $payment_data->gateway_payment_id  = $token_id;
  		$payment_data->last_four           = $token->get_last4();

      // Get Expiration in MMYY format for Qpilot.
      $expiration   = $token->get_expiry_month() . substr( $token->get_expiry_year(), -2);
  		$payment_data->expiration          = $expiration;

			return $payment_data;

		}

	}

	return null;
}

/**
 * Loads the Autoship Test Gateway file
 */
function autoship_init_test_gateway() {
	if ( function_exists( 'WC' ) ) {
		require_once( 'Payments/TestGateway.php' );
	}
}
//add_action( 'plugins_loaded', 'autoship_init_test_gateway', 100 );

/**
 * Adds the Autoship Test Gateway to WC
 *
 * @param array $methods The current available gateways
 * @return array The updated available gateways
 */
function autoship_add_test_gateway( $methods ) {
	require_once( 'Payments/TestGateway.php' );
	$methods[] = 'Autoship_Payments_TestGateway';

	return $methods;
}
//add_filter( 'woocommerce_payment_gateways', 'autoship_add_test_gateway' );

add_filter( 'woocommerce_available_payment_gateways', 'asc_remove_payment_gateway' );
function asc_remove_payment_gateway( $available_gateways ) {

	if ( is_account_page() ) {
		unset( $available_gateways['cybersource'] );
	}

	return $available_gateways;
}
remove_filter( 'woocommerce_available_payment_gateways', 'asc_remove_payment_gateway' );

// ========================================
// Payment Method Add and Remove functions
// Non Woocommerce Token Table Gateways
// Skyverge dependent
// ========================================

/**
* Specific Gateway Add & Delete wrappers
* i.  authorize.net
* ii. Braintree Credit Card
* iii.Braintree PayPal
* iv. Square
* v. Cybersource
* =============================*/

/* i. authorize.net Payment Method */

/**
 * Adds the authorize.net Payment Method to QPilot
 * Does not use the woocommerce_payment_tokens tables
 * @see wc_payment_gateway_' . $this->get_id() . '_add_payment_method_transaction_result hook.
 *
 * @param array $result {
 *   @type string $message notice message to render
 *   @type bool $success true to redirect to my account, false to stay on page
 * }
 * @param \SV_WC_Payment_Gateway_API_Create_Payment_Token_Response $response instance
 * @param \WC_Order $order order instance
 * @param \SV_WC_Payment_Gateway_Direct $this direct gateway instance
 *
 * @return mixed
 * @throws ReflectionException
 */
function autoship_add_authorize_net_payment_method( $result, $response, $order, $client ) {

  // Get the types to see if this is legacy or new Authnet
  $types = autoship_standard_gateway_id_types();

	// Check if the transaction has been approved
	if ( $response->transaction_approved() && !isset( $types['authorize_net_cim_credit_card'] ) )
  autoship_add_non_wc_token_payment_method( $response, $order, 'authorize_net_cim_credit_card' );

	// Return default
	return $result;
}

/**
 * Adds the Skyverge Payment Method to QPilot After Checkout
 * Fired when a payment is processed for an order.
 *
 * @param WC_Order $order The WC Order object
 * @param SV_WC_Payment_Gateway_Direct $gateway instance
 */
function autoship_add_skyverge_payment_method( $order, $gateway ){

  // Get the types to see if this is legacy or new
  $types = autoship_standard_gateway_id_types();

	if ( !isset( $types[$gateway->id] ) )
  return;

  // Retrieve the Token based off the token id & run it through the partial token filter.
  $token = autoship_get_related_tokenized_id( $order->payment->token, true );

  // Check for failed token retrieval
  if ( is_null( $token ) || empty( $token ) || !$token )
  return;

  $token = autoship_tokenize_non_fully_implemented_token_classes( $token );

  // Upsert the Token to the API
  autoship_add_general_payment_method( $token );

}

/**
 * Fires after a new Skyverge payment method is added by a customer.
 *
 * @param string $token new token
 * @param int $user_id user ID
 * @param SV_WC_Payment_Gateway_API_Response $response API response object
 */
function autoship_add_my_account_skyverge_payment_method( $token, $user_id, $response ){

  if ( is_checkout() )
  return;

  // Retrieve the Token based off the token id & run it through the partial token filter.
  $token = autoship_get_related_tokenized_id( $token, true );

  // Check for failed token retrieval
  if ( is_null( $token ) || empty( $token ) || !$token )
  return;

  $token = autoship_tokenize_non_fully_implemented_token_classes( $token );

  autoship_add_general_payment_method( $token );

}

/**
 * Modifies the Payment Info for a Customer
 * For authorize_net payment methods added to QPilot.
 * Hooked into the new {@see autoship_add_{$type}_payment_method} filter.
 *
 *
 * @param array  $payment_method_data Current payment Method Data
 * @param string $autoship_method_type     The QPilot Method Type
 * @param WC_Payment_Token_CC $token
 *
 * @return array The modified Payment Method Data to Send to QPilot
 */
function autoship_add_authorize_net_payment_method_data( $payment_method_data, $autoship_method_type, $token  ) {

  // Apply the test filters.
  $test_ext = apply_filters('autoship_payment_method_sandbox_metadata_field_test_ext', '_test', 'authorize_net_cim_credit_card' );
  // ex. _wc_authorize_net_cim_credit_card_payment_tokens_test or _wc_authorize_net_cim_credit_card_payment_tokens
  $meta_ext = apply_filters('autoship_payment_method_sandbox_metadata_field_ext', '', $test_ext, 'authorize_net_cim_credit_card' );

  // Authnet uses the customer id from user meta as Gateway Customer ID
  $user_id = $token->get_user_id();
  $payment_method_data['gatewayCustomerId'] = get_user_meta( $user_id,'wc_authorize_net_cim_customer_profile_id' . $meta_ext, true);

  return $payment_method_data;

}

/**
 * Removes the authorize.net Payment Method from QPilot
 * Does not use the woocommerce_payment_tokens tables
 * @see wc_payment_gateway_' . $this->get_id() . '_payment_method_deleted hook.
 *
 * @param string $token_id ID of the deleted token
 * @param int $user_id user ID
 * @return bool|WP_Error True on Success, False if not needed, WP_Error on failure.
 */
function autoship_delete_authorize_net_payment_method( $token_id, $user_id ) {

  // Get the types to see if this is legacy or new Authnet
  $types = autoship_standard_gateway_id_types();

	// Only faux tokenize if this is the legacy Authnet
	return !isset( $types['authorize_net_cim_credit_card'] ) ?
  autoship_delete_non_wc_token_payment_method( $token_id, null, 'AuthorizeNet', $user_id ) : false;

}

/* ii. Braintree Credit Card Payment Method */

/**
 * Adds the Brain Tree credit card Payment Method to QPilot
 * Does not use the woocommerce_payment_tokens tables
 * @see wc_payment_gateway_' . $this->get_id() . '_add_payment_method_transaction_result hook.
 *
 * @param array $result {
 *   @type string $message notice message to render
 *   @type bool $success true to redirect to my account, false to stay on page
 * }
 * @param \SV_WC_Payment_Gateway_API_Create_Payment_Token_Response $response instance
 * @param \WC_Order $order order instance
 * @param \SV_WC_Payment_Gateway_Direct $this direct gateway instance
 *
 * @return mixed
 */
function autoship_add_braintree_credit_card_payment_method( $result, $response, $order, $client ) {

  // Get the types to see if this is legacy or new Braintree Credit Card
  $types = autoship_standard_gateway_id_types();

	// Check if the transaction has been approved && This is the correct version
	if ( $response->transaction_approved() && !isset( $types['braintree_credit_card'] ) )
  autoship_add_non_wc_token_payment_method( $response, $order, 'braintree_credit_card' );

	// Return default
	return $result;

}

/**
 * Modifies the Payment Info for a Customer
 * For braintree_credit_card payment methods added to QPilot.
 * Hooked into the new {@see autoship_add_{$type}_payment_method} filter.
 *
 *
 * @param array  $payment_method_data Current payment Method Data
 * @param string $autoship_method_type     The QPilot Method Type
 * @param WC_Payment_Token_CC $token
 *
 * @return array The modified Payment Method Data to Send to QPilot
 */
function autoship_add_braintree_payment_method_data( $payment_method_data, $autoship_method_type, $token  ) {

  $type = $token->get_card_type();
  $ext_type = 'Paypal' == $type ? 'braintree_paypal': 'braintree_credit_card';

  // Get the types to see if this is legacy or new Braintree Paypal
  $types = autoship_standard_gateway_id_types();

  // Apply the test filters.
  $test_ext = apply_filters('autoship_payment_method_sandbox_metadata_field_test_ext', '_test', $ext_type );
  $meta_ext = apply_filters('autoship_payment_method_sandbox_metadata_field_ext', '', $test_ext, $ext_type );

  // braintree_credit_card uses the customer id from user meta as Gateway Customer ID
  $user_id = $token->get_user_id();
  $payment_method_data['gatewayCustomerId'] = get_user_meta( $user_id,'wc_braintree_customer_id' . $meta_ext, true);

  // Only use legacy methods & filters if this is a pre-token version
  if ( !isset( $types[$ext_type] ) && 'Paypal' == $type ){

    // Update the Description for paypal.
    $payment_tokens = get_user_meta( $user_id,'_wc_braintree_paypal_payment_tokens' . $meta_ext, true);
    $email = $payment_tokens[$token->get_token()]['payer_email'];
    $payment_method_data['description'] = apply_filters(
    'autoship_braintree_paypal_payment_method_description',
    sprintf('Paypal for %s', $email ) );

  } else if ( 'Paypal' == $type ){

    $email = $token->get_meta('payer_email');
    $payment_method_data['description'] = apply_filters(
    'autoship_braintree_paypal_payment_method_description',
    sprintf('Paypal for %s', $email ) );

  }

  return $payment_method_data;

}

/**
 * Removes the Brain Tree credit card Payment Method from QPilot
 * Does not use the woocommerce_payment_tokens tables
 * @see wc_payment_gateway_' . $this->get_id() . '_payment_method_deleted hook.
 *
 * @param string $token_id ID of the deleted token
 * @param int $user_id user ID
 * @return bool|WP_Error True on Success, False if not needed, WP_Error on failure.
 */
function autoship_delete_braintree_credit_card_payment_method( $token_id, $user_id ) {
  return autoship_delete_non_wc_token_payment_method( $token_id, null, 'Braintree', $user_id );
}

/* iii. Braintree PayPal Payment Method */

/**
 * Adds the Brain Tree Paypal Payment Method to QPilot
 * Does not use the woocommerce_payment_tokens tables
 * @see wc_payment_gateway_' . $this->get_id() . '_add_payment_method_transaction_result hook.
 *
 * @param array $result {
 *   @type string $message notice message to render
 *   @type bool $success true to redirect to my account, false to stay on page
 * }
 * @param \SV_WC_Payment_Gateway_API_Create_Payment_Token_Response $response instance
 * @param \WC_Order $order order instance
 * @param \SV_WC_Payment_Gateway_Direct $this direct gateway instance
 *
 * @return mixed
 */
function autoship_add_braintree_paypal_payment_method( $result, $response, $order, $client ) {

  // Get the types to see if this is legacy or new Braintree PayPal
  $types = autoship_standard_gateway_id_types();

	// Check if the transaction has been approved & This is the correct version
	if ( $response->transaction_approved() && !isset( $types['braintree_paypal'] ) )
  autoship_add_non_wc_token_payment_method( $response, $order, 'braintree_paypal' );

	// Return default
	return $result;

}

/**
 * Removes the Brain Tree Paypal Payment Method from QPilot
 * Does not use the woocommerce_payment_tokens tables
 * @see wc_payment_gateway_' . $this->get_id() . '_payment_method_deleted hook.
 *
 * @param string $token_id ID of the deleted token
 * @param int $user_id user ID
 * @return bool|WP_Error True on Success, False if not needed, WP_Error on failure.
 */
function autoship_delete_braintree_paypal_payment_method( $token_id, $user_id ) {
  return autoship_delete_non_wc_token_payment_method( $token_id, null, 'Braintree', $user_id );
}

/* iv. Square Payment Method */

/**
 * Adds the Square credit card Payment Method to QPilot
 * Does not use the woocommerce_payment_tokens tables
 * @see wc_payment_gateway_' . $this->get_id() . '_add_payment_method_transaction_result hook.
 *
 * @param array $result {
 *   @type string $message notice message to render
 *   @type bool $success true to redirect to my account, false to stay on page
 * }
 * @param \SV_WC_Payment_Gateway_API_Create_Payment_Token_Response $response instance
 * @param \WC_Order $order order instance
 * @param \SV_WC_Payment_Gateway_Direct $this direct gateway instance
 *
 * @return mixed
 */
function autoship_add_square_payment_method( $result, $response, $order, $client ) {

	// Check if the transaction has been approved
	if ( $response->transaction_approved() )
  autoship_add_non_wc_token_payment_method( $response, $order, 'square_credit_card' );

	// Return default
	return $result;

}

/**
 * Modifies the Payment Info for a Customer
 * For braintree_credit_card payment methods added to QPilot.
 * Hooked into the new {@see autoship_add_{$type}_payment_method} filter.
 *
 *
 * @param array  $payment_method_data Current payment Method Data
 * @param string $autoship_method_type     The QPilot Method Type
 * @param WC_Payment_Token_CC $token
 *
 * @return array The modified Payment Method Data to Send to QPilot
 */
function autoship_add_square_payment_method_data( $payment_method_data, $autoship_method_type, $token  ) {

  $type = $token->get_card_type();
  $ext_type = 'square_credit_card';

  // Apply the test filters.
  $test_ext = apply_filters('autoship_payment_method_sandbox_metadata_field_test_ext', '_test', $ext_type );
  // ex. _wc_authorize_net_cim_credit_card_payment_tokens_test or _wc_authorize_net_cim_credit_card_payment_tokens
  $meta_ext = apply_filters('autoship_payment_method_sandbox_metadata_field_ext', '', $test_ext, $ext_type );

  // square_credit_card uses the customer id from user meta as Gateway Customer ID
  $user_id = $token->get_user_id();
  $payment_method_data['gatewayCustomerId'] = get_user_meta( $user_id, 'wc_square_customer_id' . $meta_ext, true);

  return $payment_method_data;

}

/**
 * Removes the Square credit card Payment Method from QPilot
 * Does not use the woocommerce_payment_tokens tables
 * @see wc_payment_gateway_' . $this->get_id() . '_payment_method_deleted hook.
 *
 * @param string $token_id ID of the deleted token
 * @param int $user_id user ID
 * @return bool|WP_Error True on Success, False if not needed, WP_Error on failure.
 */
function autoship_delete_square_payment_method( $token_id, $user_id ) {
  return autoship_delete_non_wc_token_payment_method( $token_id, null, 'Square', $user_id );
}

/* v. Official Cybersource Payment Method */

/**
 * Modifies the Payment Info for a Customer
 * For cybersource_cc payment methods added to QPilot.
 * Hooked into the new {@see autoship_add_{$type}_payment_method} filter.
 *
 *
 * @param array  $payment_method_data Current payment Method Data
 * @param string $autoship_method_type     The QPilot Method Type
 * @param WC_Payment_Token_CC $token
 *
 * @return array The modified Payment Method Data to Send to QPilot
 */
function autoship_add_cybersource_cc_payment_method_data( $payment_method_data, $autoship_method_type, $token  ) {

  // Apply the test filters.
  $test_ext = apply_filters('cybersource_cc_payment_method_sandbox_metadata_field_test_ext', '_test', 'cybersource_credit_card' );
  $meta_ext = apply_filters('autoship_payment_method_sandbox_metadata_field_ext', '', $test_ext, 'cybersource_credit_card' );

  // braintree_credit_card uses the customer id from user meta as Gateway Customer ID
  $user_id = $token->get_user_id();
  $payment_method_data['gatewayCustomerId'] = get_user_meta( $user_id,'wc_cybersource_customer_id' . $meta_ext, true);

  return $payment_method_data;

}

// ========================================
// Filters for Deleted Payment methods
// ========================================

/**
 * Modifies the Payment Gateway ID for gateways treated like other gateways
 *
 * @param string $gateway_id     The current Gateway ID
 * @param WC_Payment_Token_CC $token The payment Token being deleted
 *
 * @return array The modified Payment Method Data to Send to QPilot
 */
function autoship_filter_deleted_payment_method_gateway_ids( $gateway_id, $token ){
  return 'stripe_sepa' == $gateway_id ? 'stripe' : $gateway_id;
}
add_filter( 'autoship_delete_tokenized_payment_method_gateway_id', 'autoship_filter_deleted_payment_method_gateway_ids', 10, 2 );

// ========================================
// Payment Method Add and Remove functions
// Woocommerce Token Table Gateways
// ========================================

/**
 * Modifies the Payment Info for a Customer before
 * For Stripe payment methods added to QPilot.
 * Hooked into the new {@see autoship_add_{$type}_payment_method} filter.
 *
 *
 * @param array  $payment_method_data Current payment Method Data
 * @param string $type     The QPilot Method Type
 * @param WC_Payment_Token_CC $token
 *
 * @return array The modified Payment Method Data to Send to QPilot
 */
function autoship_add_stripe_payment_method( $payment_method_data, $type, $token  ) {

  // Stripe uses the customer id from user meta as Gateway Customer ID
  $user_id = $token->get_user_id();
  $payment_method_data['gatewayCustomerId'] = get_user_option( '_stripe_customer_id', $user_id );

  if ( 'stripe_sepa' == $token->get_gateway_id() )
  $payment_method_data['gatewayPaymentType'] = 21;

  return $payment_method_data;

}

/**
 * Deletes the Stripe payment method from QPilot for a customer
 * Hooked into the new {@see autoship_delete_{$type}_payment_method_qpilot_match} filter.
 *
 * @param bool   $valid    Current match boolean
 * @param string $type     The QPilot Method Type
 * @param WC_Payment_Token_CC $token
 * @param object $method   a Qpilot Payment Method Type object
 *
 * @return bool True it's a match otherwise not.
 */
function autoship_delete_stripe_payment_method( $valid, $type, $token, $method ) {

  if ( 'Stripe' == $type ){

    $customer_id = get_user_option( '_stripe_customer_id', $token->get_user_id() );
    return ( $method->gatewayCustomerId == $customer_id && $method->gatewayPaymentId == $token->get_token() );

  }
  return $valid;

}

/**
 * Modifies the Payment Info for a Customer before
 * For PayaV1 payment methods added to QPilot.
 * Hooked into the new {@see autoship_add_{$type}_payment_method} filter.
 *
 *
 * @param array  $payment_method_data Current payment Method Data
 * @param string $type     The QPilot Method Type
 * @param WC_Payment_Token_CC $token
 *
 * @return array The modified Payment Method Data to Send to QPilot
 */
function autoship_add_payav1_payment_method( $payment_method_data, $type, $token  ) {

  // PayaV1 uses the token in place of the customer_id
  // And doesn't include the token.
  $payment_method_data['gatewayCustomerId'] = $payment_method_data['gatewayPaymentId'];
  $payment_method_data['gatewayPaymentId']  = null;

  return $payment_method_data;

}

/**
 * Deletes the Paya payment method from QPilot for a customer
 * Hooked into the new {@see autoship_delete_{$type}_payment_method_qpilot_match} filter.
 *
 * @param bool   $valid    Current match boolean
 * @param string $type     The QPilot Method Type
 * @param WC_Payment_Token_CC $token
 * @param object $method   a Qpilot Payment Method Type object
 *
 * @return bool True it's a match otherwise not.
 */
function autoship_delete_payav1_payment_method( $valid, $type, $token, $method ) {

  return  ( 'PayaV1' == $type ) ?
  ( $method->gatewayCustomerId == $token->get_token() ) : $valid;

}

/**
 * Modifies the Payment Info for a Customer before
 * For NMI payment methods added to QPilot.
 * Hooked into the new {@see autoship_add_{$type}_payment_method} filter.
 *
 * @param array  $payment_method_data Current payment Method Data
 * @param string $type     The QPilot Method Type
 * @param WC_Payment_Token_CC $token
 *
 * @return array The modified Payment Method Data to Send to QPilot
 */
function autoship_add_nmi_payment_method( $payment_method_data, $type, $token  ) {

  // NMI uses the token in place of the customer_id
  // And doesn't include the token.
  $payment_method_data['gatewayCustomerId'] = $payment_method_data['gatewayPaymentId'];
  unset($payment_method_data['gatewayPaymentId']);

  return $payment_method_data;

}

/**
 * Deletes the NMI payment method from QPilot for a customer
 * Hooked into the new {@see autoship_delete_{$type}_payment_method_qpilot_match} filter.
 *
 * @param bool   $valid    Current match boolean
 * @param string $type     The QPilot Method Type
 * @param WC_Payment_Token_CC $token
 * @param object $method   a Qpilot Payment Method Type object
 *
 * @return bool True it's a match otherwise not.
 */
function autoship_delete_nmi_payment_method( $valid, $type, $token, $method ) {

  return  ( 'Nmi' == $type ) ?
  ( $method->gatewayCustomerId == $token->get_token() ) : $valid;

}

/**
 * Modifies the Payment Info for a Customer before
 * For Cybersource payment methods added to QPilot.
 * Hooked into the new {@see autoship_add_{$type}_payment_method} filter.
 *
 * @param array  $payment_method_data Current payment Method Data
 * @param string $type     The QPilot Method Type
 * @param WC_Payment_Token_CC $token
 *
 * @return array The modified Payment Method Data to Send to QPilot
 */
function autoship_add_cybersource_payment_method( $payment_method_data, $type, $token  ) {

  // cybersource doesn't use the gatewayCustomerId
  $payment_method_data['gatewayCustomerId'] = null;
  return $payment_method_data;

}

/**
 * Modifies the Payment Info for a Customer before
 * For TrustCommerce payment methods added to QPilot.
 * Hooked into the new {@see autoship_add_{$type}_payment_method} filter.
 *
 * @param array  $payment_method_data Current payment Method Data
 * @param string $type     The QPilot Method Type
 * @param WC_Payment_Token_CC $token
 *
 * @return array The modified Payment Method Data to Send to QPilot
 */
function autoship_add_trustcommerce_payment_method( $payment_method_data, $type, $token  ) {

  // TrustCommerce doesn't use the gatewayPaymentId
  $payment_method_data['gatewayCustomerId'] = $payment_method_data['gatewayPaymentId'];
  $payment_method_data['gatewayPaymentId'] = null;
  return $payment_method_data;

}

/**
 * Deletes the Cybersource payment method from QPilot for a customer
 * Hooked into the new {@see autoship_delete_{$type}_payment_method_qpilot_match} filter.
 *
 * @param bool   $valid    Current match boolean
 * @param string $type     The QPilot Method Type
 * @param WC_Payment_Token_CC $token
 * @param object $method   a Qpilot Payment Method Type object
 *
 * @return bool True it's a match otherwise not.
 */
function autoship_delete_cybersource_payment_method( $valid, $type, $token, $method ) {

  // Get the method id from the token.
  $payment_method_id  = $token->get_token();

  // Cybersource doesn't use the Gateway Customer ID.
  return  ( 'CyberSource' == $type ) ?
  ( $method->gatewayPaymentId == $token->get_token() ) : $valid;

}

/**
 * Modifies the Payment Info for a Customer before
 * For SagePay Direct payment methods added to QPilot.
 * Hooked into the new {@see autoship_add_{$type}_payment_method} filter.
 *
 *
 * @param array  $payment_method_data Current payment Method Data
 * @param string $type     The QPilot Method Type
 * @param WC_Payment_Token_CC $token
 *
 * @return array The modified Payment Method Data to Send to QPilot
 */
function autoship_add_sagepaydirect_payment_method( $payment_method_data, $type, $token  ) {

  // SagePay Doesn't require a Gateway Customer ID
  $payment_method_data['gatewayCustomerId'] = null;
  return $payment_method_data;

}

/**
 * Deletes the Sage Direct payment method from QPilot for a customer
 * Hooked into the new {@see autoship_delete_{$type}_payment_method_qpilot_match} filter.
 *
 * @param bool   $valid    Current match boolean
 * @param string $type     The QPilot Method Type
 * @param WC_Payment_Token_CC $token
 * @param object $method   a Qpilot Payment Method Type object
 *
 * @return bool True it's a match otherwise not.
 */
function autoship_delete_sagepaydirect_payment_method( $valid, $type, $token, $method ) {

  return  ( 'Sage' == $type ) ?
  ( $method->gatewayPaymentId == $token->get_token() ) : $valid;

}

/**
 * Deletes the Authnet payment method from QPilot for a customer
 * Hooked into the new {@see autoship_delete_{$type}_payment_method_qpilot_match} filter.
 *
 * @param bool   $valid    Current match boolean
 * @param string $type     The QPilot Method Type
 * @param WC_Payment_Token_CC $token
 * @param object $method   a Qpilot Payment Method Type object
 *
 * @return bool True it's a match otherwise not.
 */
function autoship_delete_authorizenet_payment_method( $valid, $type, $token, $method ) {

  if ( 'AuthorizeNet' == $type ){

    // Apply the test filters.
    $test_ext = apply_filters('autoship_payment_method_sandbox_metadata_field_test_ext', '_test', 'authorize_net_cim_credit_card' );
    $meta_ext = apply_filters('autoship_payment_method_sandbox_metadata_field_ext', '', $test_ext, 'authorize_net_cim_credit_card' );

    $user_id = $token->get_user_id();
    $customer_id = get_user_meta( $user_id,'wc_authorize_net_cim_customer_profile_id' . $meta_ext, true);

    return ( $method->gatewayCustomerId == $customer_id && $method->gatewayPaymentId == $token->get_token() );

  }

  return $valid;

}

/**
 * Modifies the Payment Info for a Customer before
 * For Official Cybersource payment methods added to QPilot.
 * Hooked into the new {@see autoship_add_{$type}_payment_method} filter.
 *
 * @param array  $payment_method_data Current payment Method Data
 * @param string $type     The QPilot Method Type
 * @param WC_Payment_Token_CC $token
 *
 * @return array The modified Payment Method Data to Send to QPilot
 */
function autoship_add_cybersource_credit_card_payment_method( $payment_method_data, $type, $token  ) {

  // Apply the test filters.
  $test_ext = apply_filters('cybersource_cc_payment_method_sandbox_metadata_field_test_ext', '_test', 'cybersource_credit_card' );
  $meta_ext = apply_filters('autoship_payment_method_sandbox_metadata_field_ext', '', $test_ext, 'cybersource_credit_card' );

  // Cybersource uses the customer id from user meta as Gateway Customer ID
  $user_id = $token->get_user_id();
  $payment_method_data['gatewayCustomerId'] = get_user_meta( $user_id,'wc_cybersource_customer_id' . $meta_ext, true);

  return $payment_method_data;

}

/**
 * Modifies the Payment Info for a Customer before
 * For Stripe payment methods added to QPilot.
 * Hooked into the new {@see autoship_add_{$type}_payment_method} filter.
 *
 *
 * @param array  $payment_method_data Current payment Method Data
 * @param string $type     The QPilot Method Type
 * @param WC_Payment_Token_CC $token
 *
 * @return array The modified Payment Method Data to Send to QPilot
 */
function autoship_add_checkout_payment_method( $payment_method_data, $type, $token  ) {

  // Checkout.com plugin doesn't actually store the customer id but we do add it when
  // a customer checks out.  If it doesn't exist we'll try to grab it from the API
  $payment_method_data['gatewayCustomerId'] = NULL;
  return $payment_method_data;

}

/**
 * Deletes the Checkout.com payment method from QPilot for a customer
 * Hooked into the new {@see autoship_delete_{$type}_payment_method_qpilot_match} filter.
 *
 * @param bool   $valid    Current match boolean
 * @param string $type     The QPilot Method Type
 * @param WC_Payment_Token_CC $token
 * @param object $method   a Qpilot Payment Method Type object
 *
 * @return bool True it's a match otherwise not.
 */
function autoship_delete_checkout_payment_method( $valid, $type, $token, $method ) {

  return 'Checkout' == $type ?
  $method->gatewayPaymentId == $token->get_token() : $valid;

}

// =============================================
// Core Payment Method Client API functions
// =============================================

/**
 * Deletes a Payment Method for a Customer from QPilot
 *
 * @param int $method_id The QPilot Payment Method ID
 * @return bool|WP_Error True on Success, WP_Error on failure
 */
function autoship_delete_payment_method( $method_id ) {

  // Get Autoship Default client
  $client = autoship_get_default_client();

  try {

    // Try to delete payment method with method id
    $client->delete_payment_method( $method_id );

  } catch ( Exception $e ) {

    $notice = autoship_expand_http_code( $e->getCode() );
    autoship_log_entry( __( 'Autoship Payment Methods', 'autoship' ), sprintf( 'Error deleting QPilot Payment Method. Additional Details: %s - %s', $e->getCode(), $e->getMessage() ) );
    return new WP_Error( 'Deleting QPilot Payment Method Failed', __( $notice['desc'], "autoship" ) );

  }

  return true;

}

/**
 * Makes the QPilot Call to create the payment method.
 *
 * @param array $payment_method_data {
 *     Array of Payment Method Information
 *
 *     @type int        $customerId.        The WC Customer id,
 *     @type string     $type               The payment gateway type name ( stripe, NMI ),
 *     @type mixed      $gatewayCustomerId  The Payment Gateway Customer id
 *     @type mixed      $gatewayPaymentId   The payment gateway token.
 *     @type string     $description        The payment description
 *     @type string     $billingFirstName   The WC Customer's Billing First Name
 *     @type string     $billingLastName    The WC Customer's Billing last Name
 *     @type string     $billingStreet1     The WC Customer's Billing Street Address Line 1
 *     @type string     $billingStreet2     The WC Customer's Billing Street Address Line 2
 *     @type string     $billingCity        The WC Customer's Billing City
 *     @type string     $billingState       The WC Customer's Billing State
 *     @type string     $billingPostcode    The WC Customer's Billing Postcode
 *     @type string     $billingCountry     The WC Customer's Billing Country
 *     @type bool       $isDefault          True if the payment method is the default.
 *     @type bool       $applyToScheduledOrders True to apply this payment method to all
 *                                              Scheduled Orders
 *
 * }
 *
 * @return stdClass|WP_Error The resulting payment method object or WP_Error on failure.
 */
function autoship_add_payment_method( $payment_method_data ){

  $client = autoship_get_default_client();

	try {
		$method = $client->upsert_payment_method( $payment_method_data );
	} catch ( Exception $e ) {
    $notice = autoship_expand_http_code( $e->getCode() );
		autoship_log_entry( __( 'Autoship Payment Methods', 'autoship' ), sprintf( 'Error creating QPilot Payment Method. Additional Details: %s - %s', $e->getCode(), $e->getMessage() ) );
    return new WP_Error( 'Creating QPilot Payment Method Failed', __( $notice['desc'], "autoship" ) );
	}

  return $method;

}

/**
 * Makes the QPilot Call to create/update the payment method.
 *
 * @param array $payment_method_data {
 *     Array of Payment Method Information
 *
 *     @type int        $customerId.        The WC Customer id,
 *     @type string     $type               The payment gateway type name (stripe,NMI),
 *     @type mixed      $gatewayCustomerId  The Payment Gateway Customer id
 *     @type mixed      $gatewayPaymentId   The payment gateway token.
 *     @type string     $description        The payment description
 *     @type string     $billingFirstName   The WC Customer's Billing First Name
 *     @type string     $billingLastName    The WC Customer's Billing last Name
 *     @type string     $billingStreet1     The WC Customer's Billing Street Address Line 1
 *     @type string     $billingStreet2     The WC Customer's Billing Street Address Line 2
 *     @type string     $billingCity        The WC Customer's Billing City
 *     @type string     $billingState       The WC Customer's Billing State
 *     @type string     $billingPostcode    The WC Customer's Billing Postcode
 *     @type string     $billingCountry     The WC Customer's Billing Country
 *     @type bool       $isDefault          True if the payment method is the default.
 *     @type bool       $applyToScheduledOrders True to apply this payment method to all
 *                                              Scheduled Orders
 *
 * }
 *
 * @return stdClass The resulting payment method object.
 */
function autoship_update_payment_method( $payment_method_data ){

  $client = autoship_get_default_client();

	try {
		$method = $client->upsert_payment_method( $payment_method_data );
	} catch ( Exception $e ) {
    $notice = autoship_expand_http_code( $e->getCode() );
		autoship_log_entry( __( 'Autoship Payment Methods', 'autoship' ), sprintf( 'Error Updating QPilot Payment Method. Additional Details: %s - %s', $e->getCode(), $e->getMessage() ) );
    return new WP_Error( 'Updating QPilot Payment Method Failed', __( $notice['desc'], "autoship" ) );
	}

  return $method;

}

// =============================================
// Core Payment Method Client Wrapper functions
// =============================================

/**
 * returns a list of Gqteway id types for Woo Token Table Supported types.
 * Can be extended using {@see autoship_extend_gateway_id_types}
 * @return array of gateway_ids to QPilot Method Types.
 */
function autoship_standard_gateway_id_types(){

  return apply_filters( 'autoship_extend_gateway_id_types', array(
    'trustcommerce'                       => 'TrustCommerce',
    'cybersource_credit_card'             => 'CyberSource',
    'stripe'                              => 'Stripe',
    'stripe_sepa'                         => 'Stripe',
    'cybersource'                         => 'CyberSource',
    'nmi_gateway_woocommerce_credit_card' => 'Nmi',
    'sagepaymentsusaapi'                  => 'PayaV1',
    'sagepaydirect'                       => 'Sage',
    'wc_checkout_com_cards'               => 'Checkout',
  ) );

}

/**
 * Adds Support for the Authnet Token Version ( v3.3.0+ )
 * Adds Support for the Skyverge Framework Token Version ( v5.8.0+ )
 * @param array
 * @return array The filtered types
 */
function autoship_add_skyverge_token_support( $types ){

  /*
   * Check if the Skyverge Gateway is being used and if so check if it's the tokenized version
   */
  if ( class_exists( "WC_Braintree" ) && version_compare( WC_Braintree::VERSION , '2.5.0', '>=' ) ){
    $types['braintree_credit_card'] = 'Braintree';
    $types['braintree_paypal']      = 'Braintree';
  }

  // Check if the Authnet Gateway is being used and if so check if it's the tokenized version
  if ( class_exists( "WC_Authorize_Net_CIM" ) && version_compare( WC_Authorize_Net_CIM::VERSION , '3.3.0', '>=' ) )
  $types['authorize_net_cim_credit_card'] = 'AuthorizeNet';

  return $types;

}

/**
 * Adds the Standard types for the Skyverge Framework Token Version ( v5.8.0+ )
 * @param array
 * @return array The filtered types
 */
function autoship_add_skyverge_stanard_types( $types ){

  /*
   * Check if the Skyverge Gateway is being used and if so check if it's the tokenized version
   */
  if ( class_exists( "WC_Braintree" ) && version_compare( WC_Braintree::VERSION , '2.5.0', '>=' ) ){
    $types['braintree_credit_card'] = 'standard';
    $types['braintree_paypal']      = 'standard';
  }

  // Check if the Authnet Gateway is being used and if so check if it's the tokenized version
  if ( class_exists( "WC_Authorize_Net_CIM" ) && version_compare( WC_Authorize_Net_CIM::VERSION , '3.3.0', '>=' ) )
  $types['authorize_net_cim_credit_card'] = 'standard';

  // Check if the Cybersource Gateway is being used and if so check if it's the tokenized version
  if ( class_exists( "WC_Cybersource_Loader" ) && version_compare( WC_Cybersource_Loader::FRAMEWORK_VERSION , '5.8.0', '>=' ) )
  $types['cybersource_credit_card'] = 'standard';

  return $types;

}

/**
 * Checks if the gateway is a Skyverge Token and prevents
 * early upsert.
 *
 * @param bool $upsert. If the token should be upserted to QPilot
 * @param WC_Payment_Token_CC  $token. A WC_Payment_Token_CC token id.
 *
 * @return bool True if the token should be upserted else false
 */
function autoship_filter_skyverge_tokens( $upsert, $token ){

  // Retrieve the array of valid Autoship Gateways.
  $valid_methods    = autoship_get_valid_payment_methods();
  $skyverge_tokens  = autoship_get_late_token_skyverge_gateways();

  // Get the token id
  $gateway_id = $token->get_gateway_id();

  if ( isset( $valid_methods[$gateway_id] ) && isset( $skyverge_tokens[$gateway_id] ) )
  $upsert = true;

  return $upsert;

}

/**
 * Calls the function to add a payment method to QPilot
 * Based on Token.  Also can ve extended using {@see autoship_add_tokenized_payment_method_extend_gateway}
 *
 * @param int  $token_id. A WC_Payment_Token_CC token id.
 *
 * @return bool|stdClass|WP_Error The Payment Method object on success, WP_Error on failure, false on not needed.
 */
function autoship_add_tokenized_payment_method( $token_id ){

  // Get the token
  $token = WC_Payment_Tokens::get( $token_id );

  // Do not upsert payment methods at checkout
  if ( apply_filters( 'autoship_add_tokenized_payment_method', false, $token ) )
  return;

  // Get the Type of Gateway ( standard tokenized or non-standard )
  $gateway_type = autoship_get_payment_method_gateway_type( $token->get_gateway_id() );

  /**
  * Apply filters for non-standard gateways to tokenize the method data.
  * @hooked autoship_tokenize_non_standard_methods
  */
  $token      = apply_filters( 'autoship_payment_method_tokenization', $token, $token_id, $gateway_type );

  // Allow users to override the gateway id.
  $gateway_id = apply_filters('autoship_add_tokenized_payment_method_gateway_id', $token->get_gateway_id(), $token_id, $token );

  // Get the current gateway id types. & Allow users to extend based on id & token.
  $gateway_id_types = apply_filters('autoship_add_tokenized_payment_method_extend_gateway_types', autoship_standard_gateway_id_types(), $gateway_id, $token );

  if ( !array_key_exists( $gateway_id, $gateway_id_types ) )
  return false;

  /**
  * Add an action for Customers to call their own payment gateway add method.
  * @hooked autoship_add_tokenized_payment_method() - 11
  */
  do_action( 'autoship_add_tokenized_payment_method_extend_gateway', $gateway_id, $gateway_id_types, $token );

  // Call the Autoship general add payment function.
  return autoship_add_general_payment_method( $token );

}

/**
 * Calls the function to delete a payment method to QPilot
 * Based on Token.  Also can be extended using {@see autoship_add_tokenized_payment_method_extend_gateway}
 *
 * @param int $token_id. A WC_Payment_Token_CC token id.
 * @param object $response
 * @return bool|WP_Error True on success, false on not needed, WP_Error on failure
 */
function autoship_delete_tokenized_payment_method( $token_id, $token ){

  // The id for the token being removed.
  $gateway_id = apply_filters('autoship_delete_tokenized_payment_method_gateway_id', $token->get_gateway_id(), $token );

  // Get the current gateway id types. & Allow users to extend based on id & token.
  $gateway_id_types = apply_filters('autoship_delete_tokenized_payment_method_extend_gateway_types', autoship_standard_gateway_id_types(), $gateway_id, $token );

  if ( !array_key_exists( $gateway_id, $gateway_id_types ) )
  return false;

  // Add an action for Customers to call their own payment gateway deletion method.
  do_action( 'autoship_delete_tokenized_payment_method_extend_gateway', $gateway_id, $gateway_id_types, $token );

  // Call the Autoship general delete payment function.
  return autoship_delete_general_payment_method( $token_id, $token, $gateway_id_types[$gateway_id] );

}

/**
 * Adds a non-woocommerce token tables based Payment Method to QPilot
 * Does not use the woocommerce_payment_tokens tables
 *
 * @param Credit_Card_Payment $response The gateway response
 * @param WC_Order $order The wc order object.
 * @param string $type  The gateway type.
 *
 * @return stdClass|WP_Error The resulting payment method object or WP_Error on failure.
 */
function autoship_add_non_wc_token_payment_method( $response, $order, $autoship_method_type ){

  $token               = $response->get_payment_token();
  $autoship_method_id  = $token->get_id();

  /**
  * Apply filters for non-standard gateways to tokenize the method data.
  * @hooked autoship_tokenize_non_standard_methods
  */
  if ( empty( $token = apply_filters( 'autoship_payment_method_tokenization', WC_Payment_Tokens::get( $autoship_method_id ), $autoship_method_id, $autoship_method_type )) )
  return $token;

  $type     = autoship_get_valid_payment_method_type( $token->get_gateway_id() );

  // Get the payment method data
  $autoship_method_data = autoship_add_general_payment_method( $token, true );

  return autoship_add_payment_method( $autoship_method_data );

}

/**
 * Removes a non-woocommerce token tables based Payment Methods from QPilot
 * Does not use the woocommerce_payment_tokens tables
 *
 * @param string $gateway_payment_method_id ID of the deleted token
 * @param int $gateway_customer_id user ID
 * @param string $type  The gateway type.
 * @param int $wc_customer_id The WooCommerce user ID
 *
 * @return bool|WP_Error True on Success, False if not needed, WP_Error on failure.
 */
function autoship_delete_non_wc_token_payment_method( $gateway_payment_method_id, $gateway_customer_id, $type, $wc_customer_id = NULL ){

  if ( empty( $wc_customer_id ) || !isset( $wc_customer_id ) )
  $wc_customer_id = get_current_user_id();

  // Check if the customer exists and if not create it.
	$customer = autoship_check_autoship_customer( $wc_customer_id, 'autoship_delete_non_wc_token_payment' );

	if ( !$customer )
	return false;

  // Get this users payment methods from QPilot.
	$payment_methods = $customer->paymentMethods;

  $result = false;
	foreach ( $payment_methods as $method ) {

    // Valid Check Since some gateways don't have a user id at deletion.
    $valid = !empty( $gateway_customer_id ) ?
    ( $method->type == $type && $method->gatewayPaymentId == $gateway_customer_id && $method->gatewayPaymentId == $gateway_payment_method_id ):
    ( $method->type == $type && $method->gatewayPaymentId == $gateway_payment_method_id );

    if ( $valid ) {

      // Delete the Payment Method
    	$result = autoship_delete_payment_method( $method->id );
			break;

    }

	}

  return $result;

}

/**
 * Adds a payment method from QPilot for a customer
 *
 * @param WC_Payment_Token_CC $token
 * @param string $type The Type for the Payment Method from QPilot.
 * @param bool $return Should the payment method data be returned or added.
 *                     True to return, false to add to QPilot
 * @return stdClass|WP_Error|array The resulting payment method object, array of payment data,
 *                                 or WP_Error on failure.
 */
function autoship_add_general_payment_method( $token , $return = false ){

  if ( is_wp_error( $token ) || empty( $token ) || !$token )
  return;

  // Get the User associated with the token.
	$wc_customer_id = $token->get_user_id();

  // CHeck if the customer is already in Qpilot
  // This will upsert them if they aren't already there,
	$customer = autoship_check_autoship_customer( $wc_customer_id , 'autoship_add_general_payment' );

  // Don't process if not a Autoship
	if ( !$customer )
	return false;

 	// Create the payment method and gather the info.
	$payment_method_id        = $token->get_token();

  /**
  * Apply filters for non-standard gateways to tokenize the method data.
  * @hooked autoship_tokenize_non_standard_methods
  */
  $gateway                  = $token->get_gateway_id();
	$description              = apply_filters( 'autoship_add_general_payment_method_description', $token->get_display_name(), $token );
  $type                     = autoship_get_valid_payment_method_type( $gateway );
  $expiration               = NULL;

  // Catch Scenarios where the token class doesn't use expirations ( i.e. SEPA )
  if ( is_callable( [ $token, 'get_expiry_month' ] ) && is_callable( [ $token, 'get_expiry_year' ] ) )
  $expiration               = $token->get_expiry_month() . substr( $token->get_expiry_year(), -2);

  $payment_method_data      = autoship_get_general_payment_method_customer_data( $wc_customer_id, array(
    'type'              => $type,
    'expiration'        => $expiration,
    'lastFourDigits'    => $token->get_last4(),
		'gatewayCustomerId' => $wc_customer_id,
  	'gatewayPaymentId'  => $payment_method_id,
		'description'       => $description
  ) );

  // Apply the Payment method filter for customers to override.
  $payment_method_data = apply_filters( 'autoship_add_general_payment_method', $payment_method_data, $type, $token );
  $payment_method_data = apply_filters( "autoship_add_{$type}_payment_method", $payment_method_data, $type, $token );
  $payment_method_data = apply_filters( 'autoship_api_create_payment_method_data',$payment_method_data );

  // Add the Method
 	return $return ? $payment_method_data : autoship_add_payment_method( $payment_method_data );

}

/**
 * Deletes a payment method from QPilot for a customer
 *
 * @param string $token_id Payment Token ID for this payment method
 * @param WC_Payment_Token_CC $token
 * @param string $type The Type for the Payment Method from QPilot.
 * @return bool|WP_Error True on success, false on not needed, WP_Error on failure
 */
function autoship_delete_general_payment_method( $token_id, $token, $type = '' ) {

  // Get the WC Customer from the Token
 	$wc_customer_id = $token->get_user_id();

  // Get the Autoship Customer ID.
	$customer = autoship_check_autoship_customer( $wc_customer_id, 'autoship_delete_general_payment' );

  // If not autoship bypass
 	if ( !$customer || empty( $type ) )
	return false;

  // Get the method id from the token.
  $payment_method_id  = $token->get_token();

  // Get the customer's payment methods from QPilot
	$payment_methods = $customer->paymentMethods;

  // Check if there are any payment methods
  if ( empty( $payment_methods ) )
  return true;

  $result = false;

  // Iterate through the payment methods and delete the one that matches this type.
  foreach ( $payment_methods as $method ) {

    // If the token being deleted matches a token in QPilot Delete it.
    $valid = (
    $method->type == $type
		&& $method->gatewayCustomerId == $wc_customer_id
		&& $method->gatewayPaymentId  == $payment_method_id );

    // Added a filter to allow for custom matches between QPilot and Autoship.
    // Takes the current matching boolean, the supplied QPilot Method Type, The current Token and the QPilot Payment Method object.
    $valid = apply_filters( "autoship_delete_general_payment_method_qpilot_match", $valid, $type, $token, $method );
    $valid = apply_filters( "autoship_delete_{$type}_payment_method_qpilot_match", $valid, $type, $token, $method );

    if ( $valid ) {

      // Delete the Payment Method
    	$result = autoship_delete_payment_method( $method->id );
			break;

    }

	}

  return $result;

}

// ==========================================================
// My Account > Payment Methods Functions
// ==========================================================

/*  Action Processors
====================== */

/**
 * Generates a WC Payment Token for non-standard gateways.
 *
 * @param WC_Payment_token $token.  The current WC Payment Token
 * @param string $autoship_method_id.  The current Payment Method id.
 * @param string $autoship_method_type.  The current Payment Method type.
 *
 * @return WC_Payment_Token_CC
 */
function autoship_tokenize_non_standard_methods( $token, $autoship_method_id, $autoship_method_type  ){

  // If the token is invalid or it is valid and it's standard
  // no need to adjust it.
  
  if ( 'standard' == $autoship_method_type )
  return $token;

  $original_token = $token;
  $token = new WC_Payment_Token_CC();
  $token->set_user_id( get_current_user_id() );
  $token->set_token( $autoship_method_id );
  $token->set_gateway_id( $autoship_method_type );

  // Get the Skyverge Token Source Data
  // The following filter should be used to adjust the test mode extensions.
  // The reason is that the skyverge framework and others use a separate user metadata field
  // for sandbox mode vs live.
  $test_ext = apply_filters('autoship_payment_method_sandbox_metadata_field_test_ext', '_test', $autoship_method_type );

  // ex. _wc_authorize_net_cim_credit_card_payment_tokens_test or _wc_authorize_net_cim_credit_card_payment_tokens
  $meta_ext = apply_filters('autoship_payment_method_sandbox_metadata_field_ext', '', $test_ext, $autoship_method_type );

  $user_tokens = get_user_meta( get_current_user_id(), "_wc_{$autoship_method_type}_payment_tokens{$meta_ext}", true );

  // If the token data doesn't exist abort
  if ( empty( $user_tokens ) || !isset( $user_tokens[$autoship_method_id] ) )
  return $original_token;

  if ( 'braintree_paypal' != $autoship_method_type ){
    $token->set_last4( $user_tokens[$autoship_method_id]['last_four'] );
    // HACK: Most of these tokens are not giving four digit expiration years and
    // WC assumes they are so we assume they are in the 2000's
    $token->set_expiry_year( strlen( $user_tokens[$autoship_method_id]['exp_year'] ) < 4 ? 2000 + $user_tokens[$autoship_method_id]['exp_year']:$user_tokens[$autoship_method_id]['exp_year'] );
    $token->set_expiry_month( $user_tokens[$autoship_method_id]['exp_month'] );
    $token->set_card_type( $user_tokens[$autoship_method_id]['card_type'] );
  } else {
    $token->set_card_type('Paypal');
  }

  return $token;

}

/**
 * Generates a WC Payment Token for gateways that do not fully implement the
 * WC Payment Token Class.
 *
 * Gateways
 * =======================
 * braintree_paypal
 *
 * @param object $token.  The current WC Payment Token's Class.
 * @param string $autoship_method_id.  The current Payment Method id.
 * @param string $autoship_method_type.  The current Payment Method type.
 *
 * @return WC_Payment_Token_CC
 */
function autoship_tokenize_non_fully_implemented_token_classes( $token ){

  if ( empty( $token ) || "braintree_paypal" !== $token->get_gateway_id() )
  return $token;

  $original_token = $token;
  $token = new WC_Payment_Token_CC();
  $token->set_user_id( $original_token->get_user_id() );
  $token->set_token( $original_token->get_token() );
  $token->set_gateway_id( 'braintree_paypal' );
  $token->set_card_type('Paypal');
  $token->add_meta_data('payer_email', $original_token->get_meta('payer_email') );

  return $token;

}

/**
 * Main catch for the update url.
 * Hooks into the Autoship Update Scheduled Orders Payment Method endpoint
 * and updates the payment method on all orders info.
 */
function autoship_update_payment_method_on_all_scheduled_orders(){

  $autoship_method_id = get_query_var('autoship_method_id');
  $autoship_method_type = get_query_var('autoship_method_type');

  // skyverge_standard

  if ( !empty( $autoship_method_id ) && !empty( $autoship_method_type ) ) {

    wc_nocache_headers();

    /**
    * Apply filters for non-standard gateways to tokenize the method data.
    * @hooked autoship_tokenize_non_standard_methods
    */
    $token    = apply_filters( 'autoship_payment_method_tokenization', WC_Payment_Tokens::get( $autoship_method_id ), $autoship_method_id, $autoship_method_type );
    $type     = autoship_get_valid_payment_method_type( $token->get_gateway_id() );

    if ( empty( $type ) || is_null( $token ) || get_current_user_id() !== $token->get_user_id() || false === wp_verify_nonce( $_REQUEST['_wpnonce'], 'autoship-apply-payment-method-to-orders-' . $autoship_method_id ) ) {

      $message = __( 'Invalid payment method.', 'autoship' );
      $notice = apply_filters( 'autoship_update_payment_method_on_all_scheduled_orders_failure_invalid_notice', array( 'message' => $message, 'code' => 'error' ), $type );

    // Apply Security Measures.
    } else if ( !autoship_rights_checker( 'autoship_update_payment_info_on_all_scheduled_orders', array() ) ){

        $message = __( 'You have insufficient rights to make this change.', 'autoship' );
        $notice = apply_filters( 'autoship_update_payment_method_on_all_scheduled_orders_failure_rights_notice', array( 'message' => $message, 'code' => 'error' ), $type );

    } else {

      $schedule_label = autoship_translate_text( 'Scheduled Orders' );

      // Get the payment method data
      $autoship_method_data = autoship_add_general_payment_method( $token, true );

      // Add the apply to all scheduled orders flag
      $autoship_method_data['applyToScheduledOrders'] = true;

      // Now upsert the method.
      $result = autoship_update_payment_method( $autoship_method_data );

      if ( is_wp_error( $result ) ){
        $message = sprintf( __( "There was a problem updating the information on your %s. Additional details: %s", 'autoship' ), $schedule_label, $result->get_error_message() );
        $notice = apply_filters( 'autoship_update_payment_method_on_all_scheduled_orders_failure_notice', array( 'message' => $message, 'code' => 'error' ), $type );
      } else {
        $message = sprintf( __( "Your %s Have been successfully updated.", 'autoship' ), $schedule_label );
        $notice = apply_filters( 'autoship_update_payment_method_on_all_scheduled_orders_updated_notice', array( 'message' => $message, 'code' => 'success' ), $type );
      }


    }

    wc_add_notice( $notice['message'], $notice['code'] );

    wp_redirect( wc_get_account_endpoint_url( 'payment-methods' ) );
    exit();

  }

}

/*  Action Utilities
====================== */

/**
 * Generates a URL to update Schedueld Orders Payment Method.
 * Can be modified via {@see autoship_get_update_scheduled_orders_customer_payment_method_info_url}
 *
 * @param string     $method_id The Payment Method id.
 * @param string     $method_type Optional. The Payment Method gateway type.  Default 'standard'
 * @return string The action url.
 */
function autoship_get_scheduled_orders_update_customer_payment_method_info_url( $method_id, $method_type = 'standard' ) {
  return apply_filters(
  'autoship_get_update_scheduled_orders_customer_payment_method_info_url',
  add_query_arg( array(
  'autoship_method_id' => $method_id,
  'autoship_method_type' => $method_type,
  ), wc_get_page_permalink( 'myaccount' ) ),
  $method_id,
  $method_type
  );
}

/**
 * Retrieves the default setting for the apply to all scheduled orders.
 * @param int $user_id. The user being updated.
 * @return bool True to apply or false not to.
 */
function autoship_apply_customer_payment_method_info_to_all_scheduled_order_default( $user_id ){
  return apply_filters('autoship_apply_payment_method_to_scheduled_orders_by_default', false, $user_id );
}

/*  Action Hooks & Notices
====================== */

/**
 * Includes additional autoship actions notice after a payment method is saved updated.
 *
 * @param string $token_id   The new token ID
 * @param string $gateway_id The gateway for which a method was added.
 * @param string $name       The payment method name/description.
 */
function autoship_after_save_payment_method_autoship_action_notice( $token_id, $gateway_id = 'standard', $name = ''){

  $hide = apply_filters('autoship_display_save_payment_method_at_checkout', is_checkout() ) || !apply_filters('autoship_display_customer_update_actions', true, get_current_user_id() );

  if ( $hide )
  return;

  $schedule_label = autoship_translate_text( 'Scheduled Orders' );

  // Get the Set on all scheduled orders action url.
  $url      = autoship_get_scheduled_orders_update_customer_payment_method_info_url( $token_id, $gateway_id );
  $url      = wp_nonce_url( $url, 'autoship-apply-payment-method-to-orders-' . $token_id );

  $message = empty( $name ) ?
  sprintf( __( 'Use the newly Added Payment Method for your <strong>%s</strong> as well? <a href="%s" tabindex="1" class="button">%s</a>', 'autoship' ), $schedule_label, esc_url( $url ), esc_html__( 'Update', 'autoship' ) ) :
  sprintf( __( 'Use the new Payment Method ( %s ) for your <strong>%s</strong> as well? <a href="%s" tabindex="1" class="button">%s</a>', 'autoship' ), $name, $schedule_label, esc_url( $url ), esc_html__( 'Update', 'autoship' ) );
  $notice = apply_filters( 'autoship_apply_payment_method_to_all_scheduled_orders_default_action_notice', array( 'message' => $message, 'code' => 'success' ),$token_id, $gateway_id, $name );
  wc_add_notice( $notice['message'], $notice['code'] );

}

/**
 * Standard Gateways Only: Fires additional autoship actions after a payment method is saved updated.
 * @param int $token_id. A WC_Payment_Token_CC token id.
 */
function autoship_after_save_standard_payment_method_notice( $token_id ){

  $hide = apply_filters('autoship_display_save_payment_method_at_checkout', is_checkout() );

  if ( $hide )
  return;

  // Get the token
  $token = WC_Payment_Tokens::get( $token_id );

  // Allow users to override the gateway id.
  $gateway_id = apply_filters('autoship_add_tokenized_payment_method_gateway_id', $token->get_gateway_id(), $token_id, $token );
  $valid_gateways = autoship_get_valid_payment_methods();

  // Only include the notice for valid gateways.
  if ( !array_key_exists( $gateway_id, $valid_gateways ) )
  return;

  // Only include notice if from Payment Method form.
  if ( isset( $_POST['payment_method'] ) )
  autoship_after_save_payment_method_autoship_action_notice( $token->get_id(), 'standard', $token->get_display_name());

}

/**
 * Authorize.Net Gateway: Fires additional autoship actions after a payment method is saved.
 *
 * @param string $token_id new token ID
 * @param int $user_id user ID
 * @param \SV_WC_Payment_Gateway_API_Response $response API response object
 */
function autoship_after_save_authorize_net_payment_method_notice( $token_id, $user_id, $response ){

  // Get the types to see if this is legacy or new Authnet
  $types = autoship_standard_gateway_id_types();

	// Only Display Notice if this is the legacy Authnet Gateway
	if ( !isset( $types['authorize_net_cim_credit_card'] ) )
  autoship_after_save_payment_method_autoship_action_notice( $token_id, 'authorize_net_cim_credit_card', '' );

}

/**
 * Braintree_credit_card Gateway: Fires additional autoship actions after a payment method is saved.
 *
 * @param string $token_id new token ID
 * @param int $user_id user ID
 * @param \SV_WC_Payment_Gateway_API_Response $response API response object
 */
function autoship_after_save_braintree_credit_card_payment_method_notice( $token_id, $user_id, $response ){

  // Get the types to see if this is legacy or new Braintree
  $types = autoship_standard_gateway_id_types();

  // Only include this notice for non-token Braintree Credit Card Versions
	if ( !isset( $types['braintree_credit_card'] ) )
  autoship_after_save_payment_method_autoship_action_notice( $token_id, 'braintree_credit_card', '' );

}

/**
 * square_credit_card Gateway: Fires additional autoship actions after a payment method is saved.
 *
 * @param string $token_id new token ID
 * @param int $user_id user ID
 * @param \SV_WC_Payment_Gateway_API_Response $response API response object
 */
function autoship_after_save_square_credit_card_payment_method_notice( $token_id, $user_id, $response ){
  autoship_after_save_payment_method_autoship_action_notice( $token_id, 'square_credit_card', '' );
}

/**
 * Outputs the description on the Payment Methods Page
 * @param bool $has_methods If there are any methods yet.
 */
function autoship_display_apply_payment_method_to_all_scheduled_orders_note( $has_methods ){

  // Check if user has any SO's and skip if not.
  // Only display the notice if the user has scheduled orders.
  if ( !apply_filters('autoship_display_customer_update_actions', true, get_current_user_id() ) )
  return;

  $schedule_label = autoship_translate_text( 'Scheduled Orders' );

  echo apply_filters( 'autoship_display_apply_method_to_all_scheduled_orders_note' , sprintf( __('<p><strong><i class="autoship_update_all_orders"></i></strong> Select to apply the payment method to your %s.</p>', 'autoship' ), $schedule_label ) );

}

/*  Action Buttons
====================== */

/**
 * Non-Standard skyverge framework Gateways actions
 * Outputs the apply action button after each payment method
 * @param array $list_item {
 *     @type string $url action URL
 *     @type string $class action button class
 *     @type string $name action button name
 * }
 * @param \SV_WC_Payment_Gateway_Payment_Token $token
 * @param \SV_WC_Payment_Gateway_My_Payment_Methods $this instance
 * @param string The gateway id.
 */
function autoship_display_apply_payment_method_to_all_scheduled_orders_skyverge_btn( $list_item, $payment_token, $class, $gateway ){

  // Check if user has any SO's and skip if not.
  // Only display the notice if the user has scheduled orders.
  if ( !apply_filters('autoship_display_customer_update_actions', true, get_current_user_id() ) )
  return $list_item;

  $apply_to_all_orders_url      = autoship_get_scheduled_orders_update_customer_payment_method_info_url( $payment_token->get_id(), $gateway );
  $apply_to_all_orders_url      = wp_nonce_url( $apply_to_all_orders_url, 'autoship-apply-payment-method-to-orders-' . $payment_token->get_id() );

  $list_item['autoship_update_all_orders'] = apply_filters( 'autoship_apply_payment_method_to_all_scheduled_orders_row_action', array(
    'url'  => $apply_to_all_orders_url,
    'name' => apply_filters('autoship_update_orders_icon_action',''),
  	'class' => "autoship_update_all_orders",
  ), $apply_to_all_orders_url, $payment_token, $list_item );

  return  apply_filters( 'autoship_apply_payment_method_to_all_scheduled_orders_non_standard_row', $list_item, $payment_token, $class );

}

/**
 * Braintree
 * Non-Standard skyverge framework Gateways actions
 * Outputs the apply action button after each payment method
 * @param array $actions {
 *     @type string $url action URL
 *     @type string $class action button class
 *     @type string $name action button name
 * }
 * @param \SV_WC_Payment_Gateway_Payment_Token $token
 * @param \SV_WC_Payment_Gateway_My_Payment_Methods $this instance
 */
function autoship_display_apply_payment_method_to_all_scheduled_orders_braintree_btn( $list_item, $payment_token, $class ){
  $gateway = $payment_token->is_paypal_account() ? 'braintree_paypal': 'braintree_credit_card';
  return  autoship_display_apply_payment_method_to_all_scheduled_orders_skyverge_btn( $list_item, $payment_token, $class, $gateway );
}

/**
 * Authorize.Net
 * Non-Standard skyverge framework Gateways actions
 * Outputs the apply action button after each payment method
 * @param array $actions {
 *     @type string $url action URL
 *     @type string $class action button class
 *     @type string $name action button name
 * }
 * @param \SV_WC_Payment_Gateway_Payment_Token $token
 * @param \SV_WC_Payment_Gateway_My_Payment_Methods $this instance
 */
function autoship_display_apply_payment_method_to_all_scheduled_orders_authorize_btn( $list_item, $payment_token, $class ){
  return  autoship_display_apply_payment_method_to_all_scheduled_orders_skyverge_btn( $list_item, $payment_token, $class, 'authorize_net_cim_credit_card' );
}

/**
 * Cybersource CC
 * Non-Standard skyverge framework Gateways actions
 * Outputs the apply action button after each payment method
 * @param array $actions {
 *     @type string $url action URL
 *     @type string $class action button class
 *     @type string $name action button name
 * }
 * @param \SV_WC_Payment_Gateway_Payment_Token $token
 * @param \SV_WC_Payment_Gateway_My_Payment_Methods $this instance
 */
function autoship_display_apply_payment_method_to_all_scheduled_orders_cybersource_cc_btn( $list_item, $payment_token, $class ){
  return  autoship_display_apply_payment_method_to_all_scheduled_orders_skyverge_btn( $list_item, $payment_token, $class, 'cybersource_credit_card' );
}

/**
 * Square
 * Non-Standard skyverge framework Gateways actions
 * Outputs the apply action button after each payment method
 * @param array $actions {
 *     @type string $url action URL
 *     @type string $class action button class
 *     @type string $name action button name
 * }
 * @param \SV_WC_Payment_Gateway_Payment_Token $token
 * @param \SV_WC_Payment_Gateway_My_Payment_Methods $this instance
 */
function autoship_display_apply_payment_method_to_all_scheduled_orders_square_btn( $list_item, $payment_token, $class ){
  return  autoship_display_apply_payment_method_to_all_scheduled_orders_skyverge_btn( $list_item, $payment_token, $class, 'square_credit_card' );
}

/**
 * Standard Woocommerce Token Gateways actions
 * Outputs the apply action button after each payment method
 * @param  array $list         List of payment methods passed from wc_get_customer_saved_methods_list().
 * @param  int   $customer_id  The customer to fetch payment methods for.
 * @return array               Filtered list of customers payment methods.
 */
function autoship_display_apply_payment_method_to_all_scheduled_orders_btn( $list_item, $payment_token ){

  // Check if user has any SO's and skip if not.
  // Only display the notice if the user has scheduled orders.
  if ( !apply_filters('autoship_display_customer_update_actions', true, get_current_user_id() ) )
  return $list_item;

  $apply_to_all_orders_url      = autoship_get_scheduled_orders_update_customer_payment_method_info_url( $payment_token->get_id() );
  $apply_to_all_orders_url      = wp_nonce_url( $apply_to_all_orders_url, 'autoship-apply-payment-method-to-orders-' . $payment_token->get_id() );

  $list_item['actions']['autoship_update_all_orders'] = apply_filters( 'autoship_apply_payment_method_to_all_scheduled_orders_row_action', array(
    'url'  => $apply_to_all_orders_url,
    'name' => apply_filters('autoship_update_orders_icon_action',''),
  ), $apply_to_all_orders_url, $payment_token, $list_item );

  return  apply_filters( 'autoship_apply_payment_method_to_all_scheduled_orders_row', $list_item, $payment_token );

}

// ==========================================================
// Test & Sandbox Mode Filter for Non-Standard skyverge gateways.
// ==========================================================

/**
 * Filters for Gateway Test Modes and Adjusts the field extension
 * based on the test mode for the supplied gateway.
 *
 * @param  string $ext                   The non-test mode metadata field extension.
 * @param  string $alt_ext               The test mode metadata field extension.
 * @param  string $autoship_method_type  The gateway id.
 * @return string Filtered metadata field extension.
 */
function autoship_skyverge_payment_method_sandbox_metadata_field_ext_init( $ext, $alt_ext, $autoship_method_type ){

  $gateways = WC()->payment_gateways->payment_gateways();

  if ( !empty( $gateways ) && isset( $gateways[$autoship_method_type] ) )
  return $gateways[$autoship_method_type]->is_test_environment() ? $alt_ext : $ext;

  return $ext;

}

/**
 * Returns the field extension for test gateways.
 *
 * @param  string $ext                   The test mode metadata field extension.
 * @param  string $autoship_method_type  The gateway id.
 * @return string Filtered metadata field test extension.
 */
function autoship_payment_method_sandbox_metadata_field_ext_types( $ext, $autoship_method_type ){

  $types = array(
    'braintree_paypal'              => '_sandbox',
    'braintree_credit_card'         => '_sandbox',
    'authorize_net_cim_credit_card' => '_test',
    'cybersource_credit_card'       => '_test'
  );

  return isset($types[$autoship_method_type]) ? $types[$autoship_method_type] : $ext;

}

// ==========================================================
// Metadata Processing Functions
// ==========================================================

/**
 * Adds the refund metadata for PayPal
 * Adds a '_woo_pp_txnData' metadata record to allow for refunds.
 *
 * @param  array $payment_meta The gateway response data.
 * @param  WC_Order $order The wc order.
 * @param  array $payment_method The associated payment method data
 */
function autoship_add_adjusted_gateway_metadata_ppec_paypal( $payment_meta, $order, $payment_method ){

  // Set the status for the transaction
  $status = 'Completed' == $payment_meta['PAYMENTSTATUS'] ?
  $payment_meta['PAYMENTSTATUS'] : $payment_meta['PAYMENTSTATUS'] . '_' . $payment_meta['PENDINGREASON'];

  $refund_data = array(
    'txn_type' => 'sale',
    'refundable_txns' => array ( array(
    'txnID'           => $payment_meta['TRANSACTIONID'],
    'amount'          => $payment_meta['AMT'],
    'refunded_amount' => 0 )),
    'status' => $status
  );

  $order->update_meta_data( '_woo_pp_txnData', $refund_data );
  $order->save();
}

/**
 * Adds the refund metadata for PayPal v2
 *
 * @param  array $payment_meta The gateway response data.
 * @param  WC_Order $order The wc order.
 * @param  array $payment_method The associated payment method data
 */
function autoship_add_adjusted_gateway_metadata_ppep_paypal( $payment_meta, $order, $payment_method ){

  $order->update_meta_data( 'payment_token_id', $payment_method['gatewayPaymentId'] );
  $order->update_meta_data( '_ppcp_paypal_order_id', $payment_meta['id'] );
  $order->update_meta_data( '_ppcp_paypal_intent', 'CAPTURE' );
  $order->save();

  /**
   * Check if Test Mode or Live
   * @HACK Need better way to test if this is a live or test payment process
   */
  if ( isset( $payment_meta['links'] ) && !empty( $payment_meta['links'] ) && ( strpos( $payment_meta['links'][0]['href'], 'https://api.sandbox.paypal.com' ) !== FALSE ) ) {
    $order->update_meta_data( '_ppcp_paypal_payment_mode', 'sandbox' );
    $order->save();
  }
}

/**
 * Adds the refund metadata for Authorize.Net
 *
 * @param  array $payment_meta The gateway response data.
 * @param  WC_Order $order The wc order.
 * @param  array $payment_method The associated payment method data
 */
function autoship_add_adjusted_gateway_metadata_authnet( $payment_meta, $order, $payment_method ){

  $exp_year   = !empty( $payment_method['expiration'] ) && ( strlen( $payment_method['expiration'] ) > 2 ) ? substr( $payment_method['expiration'], -2 ) : $payment_method['expiration'];
  $exp_month  = !empty( $payment_method['expiration'] ) && ( strlen( $payment_method['expiration'] ) > 2 ) ? substr( $payment_method['expiration'], 0, 2 ) : $payment_method['expiration'];

  $metadata = array(
    '_wc_authorize_net_cim_credit_card_trans_id'            => $payment_meta['transId'],
    '_wc_authorize_net_cim_credit_card_authorization_code'  => $payment_meta['authCode'],
    '_wc_authorize_net_cim_credit_card_customer_id'         => $payment_method['gatewayCustomerId'],
    '_wc_authorize_net_cim_credit_card_account_four'        => $payment_method['lastFourDigits'],
    '_wc_authorize_net_cim_credit_card_card_expiry_date'    => $exp_year . '-' . $exp_month,
    '_wc_authorize_net_cim_credit_card_charge_captured'     => 'yes'
  );

  $order->set_transaction_id( $payment_meta['transId'] );
  
  foreach ( $metadata as $key => $value ) {
    $order->update_meta_data( $key, $value );
  }
  $order->save();

}

/**
 * Adds the refund metadata for Authorize.Net
 *
 * @param  array $payment_meta The gateway response data.
 * @param  WC_Order $order The wc order.
 * @param  array $payment_method The associated payment method data
 */
function autoship_add_adjusted_gateway_metadata_cybersource_cc( $payment_meta, $order, $payment_method ){

  $transaction_data = json_decode( $payment_meta['authorization'] );

  $exp_year   = !empty( $payment_method['expiration'] ) && ( strlen( $payment_method['expiration'] ) > 2 ) ? substr( $payment_method['expiration'], -2 ) : $payment_method['expiration'];
  $exp_month  = !empty( $payment_method['expiration'] ) && ( strlen( $payment_method['expiration'] ) > 2 ) ? substr( $payment_method['expiration'], 0, 2 ) : $payment_method['expiration'];
  $metadata = array(
    '_wc_cybersource_credit_card_trans_id'                  => $transaction_data->id,
    '_wc_cybersource_credit_card_processor_transaction_id'  => $transaction_data->processorInformation->transactionId,
    '_wc_cybersource_credit_card_authorization_code'        => $transaction_data->processorInformation->approvalCode,
    '_wc_cybersource_credit_card_reconciliation_id'         => $transaction_data->reconciliationId,
    '_wc_cybersource_credit_card_customer_id'               => $payment_method['gatewayCustomerId'],
    '_wc_cybersource_credit_card_account_four'              => $payment_method['lastFourDigits'],
    '_wc_cybersource_credit_card_card_expiry_date'          => $exp_year . '-' . $exp_month,
    '_wc_cybersource_credit_card_charge_captured'           => 'yes'
  );

  $order->set_transaction_id( $transaction_data->id );

  foreach ( $metadata as $key => $value ) {
    $order->update_meta_data( $key, $value );
  }
  $order->save();
}

/**
 * Adds the refund metadata for Stripe
 *
 * @param  array $payment_meta The gateway response data.
 * @param  WC_Order $order The wc order.
 * @param  array $payment_method The associated payment method data
 */
function autoship_add_adjusted_gateway_metadata_stripe( $payment_meta, $order, $payment_method ){

  if ( isset( $payment_meta['CustomerId'] ) ){
    $metadata = array(
      '_stripe_customer_id'     => $payment_meta['CustomerId'],
      '_stripe_source_id'       => $payment_meta['Source']['Id'],
      '_stripe_charge_captured' => 1 == $payment_meta['Captured'] ? 'yes' : 'no',
      '_stripe_currency'        => strtoupper( $payment_meta['Currency'] )
    );
    $order->set_transaction_id( $payment_meta['Id']);
  } else if ( isset( $payment_meta['charges'] ) && isset( $payment_meta['charges']['data'] ) ){

    $data = $payment_meta['charges']['data'][0];
    $metadata = array(
      '_stripe_customer_id'     => $data['customer'],
      '_stripe_source_id'       => $data['balance_transaction']['source'],
      '_stripe_charge_captured' => 1 == $data['captured'] ? 'yes' : 'no',
      '_stripe_currency'        => strtoupper( $data['balance_transaction']['currency'] )
    );
    $order->set_transaction_id( $data['id'] );
  }

  foreach ( $metadata as $key => $value ) {
    $order->update_meta_data( $key, $value );
  }
  $order->save();

}

/**
 * Adds the fee and net fee metadata for Stripe
 *
 * @param  array $payment_meta The gateway response data.
 * @param  WC_Order $order The wc order.
 * @param  array $payment_method The associated payment method data
 */
function autoship_add_adjusted_gateway_metadata_fees_stripe( $payment_meta, $order, $payment_method ){

  if ( !class_exists( 'WC_Stripe_Helper' ) )
  return;

  $fee_refund = $net_refund = NULL;
  if ( !isset( $payment_meta['BalanceTransaction'] ) && isset( $payment_meta['charges'] ) ){

    if ( isset( $payment_meta['charges']['data'] ) && !empty( $payment_meta['charges']['data'] ) ){

      $data = $payment_meta['charges']['data'][0];

      $fee_refund = isset( $data['balance_transaction']['fee'] ) ?
      $payment_meta['charges']['data'][0]['balance_transaction']['fee'] : 0;

      $net_refund = isset( $data['balance_transaction']['net'] ) ?
      $payment_meta['charges']['data'][0]['balance_transaction']['net'] : 0;

      // Use Stripes Helper Function to format currency
  		if ( !in_array( strtolower( $data['balance_transaction']['currency'] ), WC_Stripe_Helper::no_decimal_currencies() ) ) {
        $fee_refund = number_format( $fee_refund / 100, 2, '.', '' );
        $net_refund = number_format( $net_refund / 100, 2, '.', '' );
  		}

    }

  } else if ( isset( $payment_meta['BalanceTransaction'] ) && !empty( $payment_meta['BalanceTransaction'] ) ){

  	// Fees and Net needs to both come from Stripe to be accurate as the returned
  	// values are in the local currency of the Stripe account, not from WC.
    $fee_refund = isset( $payment_meta['BalanceTransaction']['Fee'] ) ? $payment_meta['BalanceTransaction']['Fee'] : 0;
    $net_refund = isset( $payment_meta['BalanceTransaction']['Net'] ) ? $payment_meta['BalanceTransaction']['Net'] : 0;

    // Use Stripes Helper Function to format currency
		if ( !in_array( strtolower( $payment_meta['BalanceTransaction']['Currency'] ), WC_Stripe_Helper::no_decimal_currencies() ) ) {
      $fee_refund = number_format( $fee_refund / 100, 2, '.', '' );
      $net_refund = number_format( $net_refund / 100, 2, '.', '' );
		}

  }

  if ( isset( $fee_refund ) && isset( $net_refund ) ){

  	// Current data fee & net.
		$fee_current = WC_Stripe_Helper::get_stripe_fee( $order );
		$net_current = WC_Stripe_Helper::get_stripe_net( $order );

  	// Calculation.
  	$fee = (float) $fee_current + (float) $fee_refund;
  	$net = (float) $net_current + (float) $net_refund;

    // Retrieve the current fees
    $current_fee = WC_Stripe_Helper::get_stripe_fee( $order );
    $current_net = WC_Stripe_Helper::get_stripe_net( $order );

    // if the fee or net doesn't exist update it
    if ( empty( $current_fee ) || empty( $current_net ) ){

      WC_Stripe_Helper::update_stripe_fee( $order, $fee );
  	  WC_Stripe_Helper::update_stripe_net( $order, $net );

			if ( is_callable( [ $order, 'save' ] ) ) {
				$order->save();
			}

    }

  }

}

/**
 * Adds the refund metadata for Square
 *
 * @param  array $payment_meta The gateway response data.
 * @param  WC_Order $order The wc order.
 * @param  array $payment_method The associated payment method data
 */
function autoship_add_adjusted_gateway_metadata_square( $payment_meta, $order, $payment_method ){

  // Set the status for the transaction
  $payment = $payment_meta['payment'];

  // Adjust Card Expiration
  $exp  = substr( $payment['card_details']['card']['exp_year'], -2);
  $exp .= '-' . $payment['card_details']['card']['exp_month'];

  // Format the date
  $date = autoship_get_datetime ( $payment['created_at'] );

  $metadata = array(
    '_wc_square_credit_card_square_order_id'      => $payment['order_id'],
    '_wc_square_credit_card_square_location_id'	  => $payment['location_id'],
    '_wc_square_credit_card_card_type'            => strtolower( $payment['card_details']['card']['card_brand'] ),
    '_wc_square_credit_card_card_expiry_date'     => $exp,
    '_wc_square_credit_card_charge_captured'      => 'yes',
    '_wc_square_credit_card_authorization_amount' => $payment['approved_money']['amount'],
    '_wc_square_credit_card_account_four'         => $payment['card_details']['card']['last_4'],
    '_wc_square_credit_card_customer_id'          => $payment['customer_id'],
    '_wc_square_credit_card_trans_date'           => $date->format('Y-m-d H:i:s'),
    '_wc_square_credit_card_trans_id'             => $payment['id'],
    '_wc_square_credit_card_authorization_code'   => $payment['id'],
    '_wc_square_credit_card_square_version'       => WC_SQUARE_PLUGIN_VERSION
  );

  $order->set_transaction_id( $payment['id'] );

  foreach ( $metadata as $key => $value ) {
    $order->update_meta_data( $key, $value );
  }
  $order->save();

}

/**
 * Adds the refund metadata for NMI
 *
 * @param  array $payment_meta The gateway response data.
 * @param  WC_Order $order The wc order.
 * @param  array $payment_method The associated payment method data
 */
function autoship_add_adjusted_gateway_metadata_nmi_gateway_woocommerce_credit_card( $payment_meta, $order, $payment_method ){

  $metadata = array(
    '_wc_nmi_gateway_woocommerce_credit_card_trans_id'        => $payment_meta['transactionid'],
    '_wc_nmi_gateway_woocommerce_credit_card_transaction_id'  => $payment_meta['transactionid']
  );

  $order->set_payment_method( 'nmi_gateway_woocommerce_credit_card' );

  foreach ( $metadata as $key => $value ) {
    $order->update_meta_data( $key, $value );
  }
  $order->save();

}

/**
 * Adds the refund metadata for Braintree Credit Card
 *
 * @param  array $payment_meta The gateway response data.
 * @param  WC_Order $order The wc order.
 * @param  array $payment_method The associated payment method data
 */
function autoship_add_adjusted_gateway_metadata_braintree_credit_card( $payment_meta, $order, $payment_method ){

  $metadata = array();

  if ( isset( $payment_meta['Target'] ) && isset( $payment_meta['Target']['Id'] ) ){
    $metadata['_wc_braintree_credit_card_trans_id'] = $payment_meta['Target']['Id'];
    $order->set_transaction_id( $payment_meta['Target']['Id'] );
  }

  $order->set_payment_method( 'braintree_credit_card' );
  $order->set_payment_method_title( 'Credit Card' );

  foreach ( $metadata as $key => $value ) {
    $order->update_meta_data( $key, $value );
  }
  $order->save();

}

/**
 * Adds the refund metadata for Braintree PayPal
 *
 * @param  array $payment_meta The gateway response data.
 * @param  WC_Order $order The wc order.
 * @param  array $payment_method The associated payment method data
 */
function autoship_add_adjusted_gateway_metadata_braintree_paypal( $payment_meta, $order, $payment_method ){

  $metadata = array(
    '_wc_braintree_paypal_trans_id'       => $payment_meta['Target']['Id']
  );

  $order->set_payment_method( 'braintree_paypal' );
  $order->set_payment_method_title( 'PayPal' );
  $order->set_transaction_id( $payment_meta['Target']['Id'] );

  foreach ( $metadata as $key => $value ) {
    $order->update_meta_data( $key, $value );
  }
  $order->save();

}

/**
 * Adds the refund metadata for Braintree PayPal or Credit Cart
 * Uses the Payment Method's description to look for paypal
 * @uses autoship_add_adjusted_gateway_metadata_braintree_paypal()
 * @uses autoship_add_adjusted_gateway_metadata_braintree_credit_card()
 *
 * @param  array $payment_meta The gateway response data.
 * @param  WC_Order $order The wc order.
 * @param  array $payment_method The associated payment method data
 */
function autoship_add_adjusted_gateway_metadata_braintree( $payment_meta, $order, $payment_method ){

  // Sets the flag to look for in the description
  $paypal_flag = apply_filters( 'autoship_braintree_paypal_descriptor_flag', 'PayPal', $payment_method );

  // Uses the flag to check if the payment method is a Braintree or not
  $is_paypal   = apply_filters( 'autoship_is_paypal_payment_method_scheduled_order', false !== strpos( $payment_method['description'], $paypal_flag ), $payment_meta, $order, $payment_method );

  if ( $is_paypal ){

    autoship_add_adjusted_gateway_metadata_braintree_paypal( $payment_meta, $order, $payment_method );

  } else {

    autoship_add_adjusted_gateway_metadata_braintree_credit_card( $payment_meta, $order, $payment_method );

  }

}

/**
 * Adds the refund metadata for Paya
 *
 * @param  array $payment_meta The gateway response data.
 * @param  WC_Order $order The wc order.
 * @param  array $payment_method The associated payment method data
 */
function autoship_add_adjusted_gateway_metadata_sagepaymentsusaapi( $payment_meta, $order, $payment_method ){

  $order->set_payment_method( 'sagepaymentsusaapi' );
  $order->set_payment_method_title( 'Credit Card via Paya' );

  $order->save();

}

/**
 * Adds the refund metadata for Trustcommerce
 *
 * @param  array $payment_meta The gateway response data.
 * @param  WC_Order $order The wc order.
 * @param  array $payment_method The associated payment method data
 */
function autoship_add_adjusted_gateway_metadata_trustcommerce( $payment_meta, $order, $payment_method ){

  $order->set_payment_method( 'trustcommerce' );
  $order->set_payment_method_title( 'Credit/Debit Card' );

  $order->save();

}

/**
 * Adds the refund metadata for Sage Direct
 * NOTE Not currently supported due to missing transaction data from PI
 *
 * @param  array $payment_meta The gateway response data.
 * @param  WC_Order $order The wc order.
 * @param  array $payment_method The associated payment method data
 */
function autoship_add_adjusted_gateway_metadata_sage( $payment_meta, $order, $payment_method ){

  $metadata = array(
    '_VendorTxCode'                       => $payment_meta['VendorTxCode']
  );

  $order->set_payment_method( 'sagepaydirect' );
  $order->set_payment_method_title( 'Credit Card via Sage' );

  foreach ( $metadata as $key => $value ) {
    $order->update_meta_data( $key, $value );
  }
  $order->save();

}

/**
 * Adds the refund metadata for Checkout.com
 *
 * @param  array $payment_meta The gateway response data.
 * @param  WC_Order $order The wc order.
 * @param  array $payment_method The associated payment method data
 */
function autoship_add_adjusted_gateway_metadata_checkout( $payment_meta, $order, $payment_method ){

  $metadata = array(
    '_cko_payment_id'         => $payment_meta['Id'],
    'cko_payment_authorized'  => true,
    'cko_payment_captured'    => true
  );

  $order->set_transaction_id( $payment_meta['action_id'] );
  $order->set_payment_method( 'wc_checkout_com_cards' );
  $order->set_payment_method_title( 'Pay by Card with Checkout.com' );
  
  foreach ( $metadata as $key => $value ) {
    $order->update_meta_data( $key, $value );
  }
  $order->save();

}

// ==========================================================
// CHECKOUT ADJUSTMENT FUNCTIONS
// ==========================================================

/**
 * Filter for only Valid Autoship Payment Gateways
 *
 * @param array $gateways The array of available payment gateways.
 * @return array The filtered $gateways
 */
function autoship_filter_checkout_supported_gateways( $gateways ){

    // Check if Cart has autoship products.
    if( !autoship_cart_has_valid_autoship_items() )
    return $gateways;

    // Get the valid Autoship Payment Gateways ids
    $valid_gateways = autoship_get_valid_payment_methods();

    //filter out any gateways that don't work.
    foreach ($gateways as $key => $gateway) {

      if ( !isset( $valid_gateways[$key] ) )
      unset( $gateways[$key] );

    }

    return $gateways;
}

/**
 * Runs a check to see if any valid Payment Gateways exist
 * and adds an Admin notice if not
 */
function autoship_confirm_valid_payment_gateways(){

	if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || false == DOING_AJAX ) ) {

    /**
     * Get Array of the Valid Autoship Gateways
     */
    $valid_gateways = autoship_get_valid_payment_methods();

    // Retrieve the WooCommerce Payment Gateways
    $current_gateways = WC()->payment_gateways->get_available_payment_gateways();

    // Find the available gateways
    $valid_gateways = array_intersect_key( $current_gateways, $valid_gateways );

    // If there are no valid gateways notify the admin
    if ( empty( $valid_gateways ) )
    add_action( 'admin_notices', 'autoship_payment_requirement_notice' );

  }

}

// ==========================================================
// GATEWAY SPECIFIC API UTILITY FUNCTIONS
// ==========================================================

/**
 * Retrieves the charge info using the Transaction ID and Checkout API
 *
 * @param string $transaction_id The Transaction id
 * @return array|null The Stripe charge information
 */
function autoship_get_checkout_com_charge_by_transaction_id( $transaction_id ) {

	if ( class_exists( 'Checkout\CheckoutDefaultSdk' ) ) {

		// Initialize the Checkout Api.
		$checkout = new Checkout_SDK();

    try {

      $details = $checkout->get_builder()->getPaymentsClient()->getPaymentDetails( $transaction_id );

      return array(
        'token' => $details['source']['id'],
        'customer_id' => $details['customer']['id']
      );


    } catch( CheckoutHttpException $ex ) {

      autoship_log_entry( __( 'Autoship Payment Methods Error', 'autoship' ), sprintf( 'Checkout.com Transaction #%s Retrieval failed.  Additional Details: %s', $transaction_id, $ex->getMessage() ) );

      return false;

    }

	}

	return null;
}

// ==========================================================
// PAYMENT GATEWAY OPTIONS SETTINGS TAB FUNCTIONS
// ==========================================================

/**
 * Filters the Payment Gateway table description in WP Admin > WooCommerce > Settings >> Payments ( tab )
 *
 * @param array $settings The current Tabs settings options
 * @return array The filtered Settings
 */
function autoship_adjust_payment_gateway_option_description( $settings ){

  if ( !isset( $settings[0] ) || !isset( $settings[0]['desc'] ) )
  return $settings;

  $settings[0]['desc'] .= sprintf( __('<br><hr><img class="autoship-payment-icon" width="24" height="24" src="%s"/><span class="autoship-payment-description">Autoship Cloud Supported Payment Gateways. <a href="%s">Learn More</a>'), Autoship_Plugin_Url . '/images/scheduled_order.svg', 'https://support.autoship.cloud/article/1002-payment-integrations' );

  return $settings;

}

// ==========================================================
// WP Admin FUNCTIONS & ADJUSTMENTS
// ==========================================================

/**
 * Enables CSS Support for Optional Gateways
 */
function autoship_include_css_support_for_optional_gateways(){

 if ( 'no' == autoship_get_support_paypal_payments_option() )
 return;

  ?>
  <style>
  .wc_gateways tr[data-gateway_id="ppcp-gateway"] td.name > a::after,
  .wc_gateways tr[data-gateway_id="ppcp-credit-card-gateway"] td.name > a::after {
    content: "";
    width: 24px;
    height: 24px;
    margin-left: 10px;
    display: inline-block;
    background-image: url( /wp-content/plugins/autoship-cloud/images/scheduled_order.svg );
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
    margin-bottom: -7px;
  }
  </style>
  <?php
}
add_action( 'admin_head', 'autoship_include_css_support_for_optional_gateways', 10 );

// ==========================================================
// DEFAULT HOOKED ACTIONS
// ==========================================================

/**
 * Forces Tokenization
 *
 * @see autoship_force_nmi_payment_tokenization()
 */
add_filter('nmi_gateway_woocommerce_credit_card_should_force_tokenize', 'autoship_force_nmi_payment_tokenization', 10, 1);

/**
 * Patch for getting payment method data after checkout for Autoship Orders for
 * the SagePay gateway
 *
 * @see autoship_sagepaydirect_order_payment_data_saved_payment_patch()
 */
add_action( 'woocommerce_checkout_order_processed', 'autoship_sagepaydirect_order_payment_data_saved_payment_patch', 10, 3 );


/**
 * Adds Support for the Authnet 3.0.0 Tokenized Version
 *
 * @see autoship_add_skyverge_token_support()
 */
add_filter( 'autoship_extend_gateway_id_types', 'autoship_add_skyverge_token_support', 11, 1 );

/**
 * Filters the Payment Method Types for Tokenized Version of Skyverge
 *
 * @see autoship_add_skyverge_stanard_types()
 */
add_filter( 'autoship_payment_method_gateway_type', 'autoship_add_skyverge_stanard_types', 10, 1);

/**
 * Add / remove function calls for Non WC Token Table Gateways
 * My Account > Payment Method > Add / Delete
 *
 * @see autoship_add_authorize_net_payment_method()
 * @see autoship_add_skyverge_payment_method()
 * @see autoship_add_my_account_skyverge_payment_method()
 * @see autoship_add_authorize_net_payment_method_data()
 * @see autoship_delete_authorize_net_payment_method()
 * @see autoship_add_cybersource_cc_payment_method_data()
 * @see autoship_add_braintree_credit_card_payment_method()
 * @see autoship_add_braintree_payment_method_data()
 * @see autoship_delete_braintree_credit_card_payment_method()
 * @see autoship_add_braintree_paypal_payment_method()
 * @see autoship_delete_braintree_paypal_payment_method()
 * @see autoship_add_square_payment_method()
 * @see autoship_add_square_payment_method_data()
 * @see autoship_delete_square_payment_method()
 */
add_filter( 'wc_payment_gateway_authorize_net_cim_credit_card_add_payment_method_transaction_result', 'autoship_add_authorize_net_payment_method', 10, 4 );

add_action( 'wc_payment_gateway_authorize_net_cim_credit_card_payment_processed', 'autoship_add_skyverge_payment_method', 10, 2 );
add_action( 'wc_payment_gateway_authorize_net_cim_credit_card_payment_method_added', 'autoship_add_my_account_skyverge_payment_method', 10, 3 );
add_action( 'wc_payment_gateway_cybersource_credit_card_payment_processed', 'autoship_add_skyverge_payment_method', 10, 2 );
add_action( 'wc_payment_gateway_cybersource_credit_card_payment_method_added', 'autoship_add_my_account_skyverge_payment_method', 10, 3 );
add_action( 'wc_payment_gateway_braintree_credit_card_payment_processed', 'autoship_add_skyverge_payment_method', 10, 2 );
add_action( 'wc_payment_gateway_braintree_credit_card_payment_method_added', 'autoship_add_my_account_skyverge_payment_method', 10, 3 );
add_action( 'wc_payment_gateway_braintree_paypal_payment_processed', 'autoship_add_skyverge_payment_method', 10, 2 );
add_action( 'wc_payment_gateway_braintree_paypal_payment_method_added', 'autoship_add_my_account_skyverge_payment_method', 10, 3 );


add_filter( 'autoship_add_AuthorizeNet_payment_method','autoship_add_authorize_net_payment_method_data', 10, 3 );
add_action( 'wc_payment_gateway_authorize_net_cim_credit_card_payment_method_deleted', 'autoship_delete_authorize_net_payment_method', 10, 2 );

add_filter( 'autoship_add_CyberSource_payment_method','autoship_add_cybersource_cc_payment_method_data', 10, 3 );

add_filter( 'wc_payment_gateway_braintree_credit_card_add_payment_method_transaction_result', 'autoship_add_braintree_credit_card_payment_method', 10, 4 );
add_filter( 'autoship_add_Braintree_payment_method','autoship_add_braintree_payment_method_data', 10, 3 );
add_action( 'wc_payment_gateway_braintree_credit_card_payment_method_deleted', 'autoship_delete_braintree_credit_card_payment_method', 10, 2 );
add_filter( 'wc_payment_gateway_braintree_paypal_add_payment_method_transaction_result', 'autoship_add_braintree_paypal_payment_method', 10, 4 );
add_action( 'wc_payment_gateway_braintree_paypal_payment_method_deleted', 'autoship_delete_braintree_paypal_payment_method', 10, 2 );
add_filter( 'wc_payment_gateway_square_credit_card_add_payment_method_transaction_result', 'autoship_add_square_payment_method', 10, 4 );
add_filter( 'autoship_add_Square_payment_method','autoship_add_square_payment_method_data', 10, 3 );
add_action( 'wc_payment_gateway_square_credit_card_payment_method_deleted', 'autoship_delete_square_payment_method', 10, 2 );

/**
 * Add / remove function calls for WC Token Table Gateways
 * My Account > Payment Method > Add / Delete
 *
 * @see autoship_add_stripe_payment_method()
 * @see autoship_delete_stripe_payment_method()
 * @see autoship_add_payav1_payment_method()
 * @see autoship_delete_payav1_payment_method()
 * @see autoship_add_nmi_payment_method()
 * @see autoship_delete_nmi_payment_method()
 * @see autoship_add_CyberSource_payment_method()
 * @see autoship_add_trustcommerce_payment_method()
 * @see autoship_delete_cybersource_payment_method()
 * @see autoship_add_sagepaydirect_payment_method()
 * @see autoship_add_cybersource_credit_card_payment_method()
 * @see autoship_add_checkout_payment_method()
 * @see autoship_delete_checkout_payment_method()
 */
add_filter('autoship_add_Stripe_payment_method', 'autoship_add_stripe_payment_method', 10 , 3 );
add_filter('autoship_delete_Stripe_payment_method_qpilot_match', 'autoship_delete_stripe_payment_method', 10 , 4 );
add_filter('autoship_add_PayaV1_payment_method', 'autoship_add_payav1_payment_method', 10 , 3 );
add_filter('autoship_delete_PayaV1_payment_method_qpilot_match', 'autoship_delete_payav1_payment_method', 10 , 4 );
add_filter('autoship_add_Nmi_payment_method', 'autoship_add_nmi_payment_method', 10 , 3 );
add_filter('autoship_delete_Nmi_payment_method_qpilot_match', 'autoship_delete_nmi_payment_method', 10 , 4 );
add_filter('autoship_add_CyberSource_payment_method', 'autoship_add_CyberSource_payment_method', 10 , 3 );
add_filter('autoship_add_TrustCommerce_payment_method', 'autoship_add_trustcommerce_payment_method', 10 , 3 );
add_filter('autoship_delete_CyberSource_payment_method_qpilot_match', 'autoship_delete_cybersource_payment_method', 10 , 4 );
add_filter('autoship_add_Sage_payment_method', 'autoship_add_sagepaydirect_payment_method', 10 , 3 );
add_filter('autoship_delete_Sage_payment_method_qpilot_match', 'autoship_delete_sagepaydirect_payment_method', 10 , 4 );
add_filter('autoship_add_CyberSourceV2_payment_method', 'autoship_add_cybersource_credit_card_payment_method', 10, 3 );
add_filter('autoship_add_Checkout_payment_method', 'autoship_add_checkout_payment_method', 10 , 3 );
add_filter('autoship_delete_Checkout_payment_method_qpilot_match', 'autoship_delete_checkout_payment_method', 10 , 4 );

// Add Delete Support for Authnet Token version 3.3.0+
add_filter('autoship_delete_AuthorizeNet_payment_method_qpilot_match', 'autoship_delete_authorizenet_payment_method', 10 , 4 );

/**
 * Adds / Removes payment methods from Qpilot based on My Account > Payment Methods Add or Delete actions
 *
 * @see autoship_add_tokenized_payment_method()
 * @see autoship_delete_tokenized_payment_method()
 */
add_action( 'woocommerce_new_payment_token', 'autoship_add_tokenized_payment_method', 10, 1 );
add_action( 'woocommerce_payment_token_deleted', 'autoship_delete_tokenized_payment_method', 10, 2 );

/**
 * Tokenizes non-standard payment gateway tokens
 *
 * @see autoship_tokenize_non_standard_methods()
 * @see autoship_tokenize_non_fully_implemented_token_classes()
 */
add_filter('autoship_payment_method_tokenization', 'autoship_tokenize_non_standard_methods', 10, 3 );
add_filter('autoship_payment_method_tokenization', 'autoship_tokenize_non_fully_implemented_token_classes', 11, 1 );

/**
 * Main Hook for catching the update payment method on all orders endpoint call
 *
 * @see autoship_update_payment_method_on_all_scheduled_orders()
 */
add_action( 'wp', 'autoship_update_payment_method_on_all_scheduled_orders', 20 );

/**
 * Displays the Apply to All Scheduled Orders Notice in My Account > Payment Methods
 *
 * @see autoship_after_save_standard_payment_method_notice()
 * @see autoship_after_save_authorize_net_payment_method_notice()
 * @see autoship_after_save_braintree_credit_card_payment_method_notice()
 * @see autoship_after_save_square_credit_card_payment_method_notice()
 * @see autoship_display_apply_payment_method_to_all_scheduled_orders_note()
 */
add_action( 'woocommerce_new_payment_token', 'autoship_after_save_standard_payment_method_notice', 10, 1 );
add_action( 'wc_payment_gateway_authorize_net_cim_credit_card_payment_method_added','autoship_after_save_authorize_net_payment_method_notice', 10, 3 );
add_action( 'wc_payment_gateway_braintree_credit_card_payment_method_added','autoship_after_save_braintree_credit_card_payment_method_notice', 10, 3 );
add_action( 'wc_payment_gateway_square_payment_method_added','autoship_after_save_square_credit_card_payment_method_notice', 10, 3 );
add_action( 'woocommerce_after_account_payment_methods', 'autoship_display_apply_payment_method_to_all_scheduled_orders_note', 99, 1 );

/**
 * Adds the Apply Payment Method to all Orders Button
 *
 * @see autoship_display_apply_payment_method_to_all_scheduled_orders_braintree_btn()
 * @see autoship_display_apply_payment_method_to_all_scheduled_orders_authorize_btn()
 * @see autoship_display_apply_payment_method_to_all_scheduled_orders_square_btn()
 * @see autoship_display_apply_payment_method_to_all_scheduled_orders_btn()
 * @see autoship_display_apply_payment_method_to_all_scheduled_orders_cybersource_cc_btn()
 */
add_filter( 'wc_braintree_my_payment_methods_table_method_actions', 'autoship_display_apply_payment_method_to_all_scheduled_orders_braintree_btn', 10, 3 );
add_filter( 'wc_authorize_net_cim_my_payment_methods_table_method_actions', 'autoship_display_apply_payment_method_to_all_scheduled_orders_authorize_btn', 10, 3 );
add_filter( 'wc_square_my_payment_methods_table_method_actions', 'autoship_display_apply_payment_method_to_all_scheduled_orders_square_btn', 10, 3 );
add_filter( 'woocommerce_payment_methods_list_item', 'autoship_display_apply_payment_method_to_all_scheduled_orders_btn', 99, 2 );
add_filter( 'wc_cybersource_credit_card_my_payment_methods_table_method_actions', 'autoship_display_apply_payment_method_to_all_scheduled_orders_cybersource_cc_btn', 10, 3 );


/**
 * Filters the Metadata field name based on test or live mode
 *
 * @see autoship_skyverge_payment_method_sandbox_metadata_field_ext_init()
 * @see autoship_payment_method_sandbox_metadata_field_ext_types()
 */
add_filter('autoship_payment_method_sandbox_metadata_field_ext', 'autoship_skyverge_payment_method_sandbox_metadata_field_ext_init', 10, 3);
add_filter('autoship_payment_method_sandbox_metadata_field_test_ext', 'autoship_payment_method_sandbox_metadata_field_ext_types', 9, 2);

/**
 * Adds Refund Metadata to WC Orders created by Processing Scheduled Orders
 *
 * @see autoship_add_adjusted_gateway_metadata_ppec_paypal()
 * @see autoship_add_adjusted_gateway_metadata_ppep_paypal()
 * @see autoship_add_adjusted_gateway_metadata_authnet()
 * @see autoship_add_adjusted_gateway_metadata_cybersource_cc()
 * @see autoship_add_adjusted_gateway_metadata_stripe()
 * @see autoship_add_adjusted_gateway_metadata_square()
 * @see autoship_add_adjusted_gateway_metadata_nmi_gateway_woocommerce_credit_card()
 * @see autoship_add_adjusted_gateway_metadata_braintree_credit_card()
 * @see autoship_add_adjusted_gateway_metadata_braintree_paypal()
 * @see autoship_add_adjusted_gateway_metadata_sagepaymentsusaapi()
 * @see autoship_add_adjusted_gateway_metadata_trustcommerce()
 *
 * The following are workarounds for incorrect Payment Method names returned by QPilot
 * @see autoship_add_adjusted_gateway_metadata_nmi_gateway_woocommerce_credit_card()
 * @see autoship_add_adjusted_gateway_metadata_braintree_credit_card()
 * @see autoship_add_adjusted_gateway_metadata_braintree_paypal()
 * @see autoship_add_adjusted_gateway_metadata_sagepaymentsusaapi()
 * @see autoship_add_adjusted_gateway_metadata_trustcommerce()
 */
add_action( 'autoship_update_scheduled_orders_on_processing_ppec_paypal_gateway', 'autoship_add_adjusted_gateway_metadata_ppec_paypal', 10, 3 );
add_action( 'autoship_update_scheduled_orders_on_processing_ppcp-gateway_gateway', 'autoship_add_adjusted_gateway_metadata_ppep_paypal', 10, 3 );
add_action( 'autoship_update_scheduled_orders_on_processing_authorize_net_cim_credit_card_gateway', 'autoship_add_adjusted_gateway_metadata_authnet', 10, 3 );
add_action( 'autoship_update_scheduled_orders_on_processing_cybersource_credit_card_gateway', 'autoship_add_adjusted_gateway_metadata_cybersource_cc', 10, 3 );
add_action( 'autoship_update_scheduled_orders_on_processing_stripe_gateway', 'autoship_add_adjusted_gateway_metadata_stripe', 10, 3 );
add_action( 'autoship_update_scheduled_orders_on_processing_stripe_gateway', 'autoship_add_adjusted_gateway_metadata_fees_stripe', 11, 3 );
add_action( 'autoship_update_scheduled_orders_on_processing_square_credit_card_gateway', 'autoship_add_adjusted_gateway_metadata_square', 10, 3 );
add_action( 'autoship_update_scheduled_orders_on_processing_nmi_gateway_woocommerce_credit_card_gateway', 'autoship_add_adjusted_gateway_metadata_nmi_gateway_woocommerce_credit_card', 10, 3 );
add_action( 'autoship_update_scheduled_orders_on_processing_sagepaymentsusaapi_gateway', 'autoship_add_adjusted_gateway_metadata_sagepaymentsusaapi', 10, 3 );
add_action( 'autoship_update_scheduled_orders_on_processing_trustcommerce_gateway', 'autoship_add_adjusted_gateway_metadata_trustcommerce', 10, 3 );
add_action( 'autoship_update_scheduled_orders_on_processing_wc_checkout_com_cards_gateway', 'autoship_add_adjusted_gateway_metadata_checkout', 10, 3 );

// Refund Metadata Hooked Functions for Working around QPilot Payment Method Names
add_action( 'autoship_update_scheduled_orders_on_processing_braintree_gateway', 'autoship_add_adjusted_gateway_metadata_braintree', 10, 3 );

// Refund Support not currently available for Sage Pay Direct
//add_action( 'autoship_update_scheduled_orders_on_processing_sage_gateway', 'autoship_add_adjusted_gateway_metadata_sage', 10, 3 );

/**
 * Filters the Payment Gateways for only those valid for Autoship
 *
 * @see autoship_filter_checkout_supported_gateways()
 */
add_filter( 'woocommerce_available_payment_gateways', 'autoship_filter_checkout_supported_gateways', 1 );

/**
 * Filters the Payment Gateway table description in WP Admin > WooCommerce > Settings >> Payments ( tab )
 *
 * @see autoship_adjust_payment_gateway_option_description()
 */
add_filter( 'woocommerce_payment_gateways_settings', 'autoship_adjust_payment_gateway_option_description', 10, 1 );

/**
 * Filters the Slyverge Token Gateways to prevent early upsert
 *
 * @see autoship_filter_skyverge_tokens()
 */
add_filter( 'autoship_add_tokenized_payment_method', 'autoship_filter_skyverge_tokens', 10, 3 );
