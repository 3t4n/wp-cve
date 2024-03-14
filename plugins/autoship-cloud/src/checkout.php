<?php

/**
 * Adds the Autoship Scheduled Order Data to the Order Line item.
 *
 * @param WC_Order_Item_Product $item The WC Order Item Object
 * @param string $cart_item_key The cart item key
 * @param array $values The Cart Item values
 * @param WC_Order $order The WooCommerce Order.
 */
function autoship_checkout_create_order_line_item( WC_Order_Item_Product $item, $cart_item_key, $values, $order ) {

	if ( empty( $order ) )
	return;

	// Check if this is a checkout order
	// and if not bail.
	if ( $order->get_created_via() != 'checkout' )
	return;


	// No autoship schedule bail
	$schedule_values = autoship_get_item_data_schedule_values( $values );
	if ( !autoship_item_data_has_valid_schedule( $schedule_values ) )
	return;

	// Add the order metadata
	foreach ( $schedule_values as $key => $value )
	$item->add_meta_data( '_' . $key, $value );

	extract( $schedule_values );

	// Finally attach the display name.
  $options = autoship_product_frequency_options( $item->get_product_id() );
  $frequency_display_name = autoship_search_for_frequency_display_name( $autoship_frequency_type, $autoship_frequency, $options );
  $label = apply_filters( 'autoship_frequency_cart_order_item_schedule_display_label', __( 'Schedule', 'autoship' ) );
  $item->add_meta_data( $label , $frequency_display_name );

}

/**
 * Filters the Disables the Guest Checkout Flag based on if the Cart has Autoship Items
 *
 * @param string $enable_guest_checkout The Current Guest Checkout option
 * @return string The filtered value.
 */
function autoship_get_option_enable_guest_checkout( $enable_guest_checkout ) {

  if ( is_admin() && ! wp_doing_ajax() ) {
		return $enable_guest_checkout;
	}

	if ( autoship_cart_has_valid_autoship_items() ) {
		return 'no';
	}

	return $enable_guest_checkout;
}

/**
 * Includes the Force Save Card JS based on if the cart has Autoship Items
 */
function autoship_force_save_card() {

	if ( autoship_cart_has_valid_autoship_items() ) {

		$gateway_element_ids = apply_filters( 'autoship_checkout_supported_payment_gateway_save_method_ids', array(
			'wc-stripe-new-payment-method',
			'wc-authorize-net-cim-credit-card-tokenize-payment-method',
			'wc-braintree-credit-card-tokenize-payment-method',
			'wc-braintree-paypal-tokenize-payment-method',
			'wc-cyber-source-tokenize-payment-method',
			'wc-nmi-gateway-woocommerce-credit-card-new-payment-method',
			'wc-sagepaymentsusaapi-new-payment-method',
			'trustcommerce-save-card',
			'wc-square-credit-card-tokenize-payment-method',
			'wc-sagepaydirect-new-payment-method',
			'wc-cybersource-credit-card-tokenize-payment-method',
			'wc-wc_checkout_com_cards-new-payment-method',
			'wc-stripe_sepa-new-payment-method'
		) );

		autoship_print_scripts_data( array(
			'AUTOSHIP_SAVE_PAYMENT_ELEMENT_IDS' => $gateway_element_ids
		));

		printf( "\n<script src=\"%s\"></script>\n",
			plugin_dir_url( Autoship_Plugin_File ) . 'js/force-save-cards.js'
		);

	}
}

/**
 * Enables JS force Save for Optional gateways
 *
 * @param array $gateway_element_ids
 * @return array Adjusted IDs
 */
function autoship_enable_force_save_for_optional_gateways( $gateway_element_ids ){

	if ( 'yes' == autoship_get_support_paypal_payments_option() )
	$gateway_element_ids[] = 'ppcp-credit-card-vault';

	return $gateway_element_ids;

}

// ==========================================================
// SUBSCRIPTION HOOKED FUNCTIONS
// ==========================================================

/**
 * Used to Force Paypal Payments to retrieve and assign
 * the payment token to the WC Order
 *
 * @param WC_Order|int $order A WC Order object or order id.
 * @return void
 */
function autoship_woocommerce_subscription_patch( $order ){

 if ( 'no' == autoship_get_support_paypal_payments_option() )
 return;

	if( !class_exists( 'WC_Subscription' ) ){

		/**
		 * Faux Subscription Object Class
		 * NOTE Only used for integrating WooCommerce PayPal Payments Plugin
		 */
		class WC_Subscription extends WC_Order {

			/** @public WC_Order Stores order data for the order in which the subscription was purchased (if any) */
			protected $order = null;

			/**
			* Initialize the subscription object.
			*
			* @param int|WC_Order $subscription
			*/
			public function __construct( $subscription ) {
				$this->order = $subscription;
				parent::__construct( $subscription );
			}


			/**
			 * __get function.
			 *
			 * @param mixed $key
			 * @return mixed
			 */
			public function __get( $key ) {

				if ( 'order' == $key ) {

					$value = $this->order;

				} else {

					$value = parent::__get( $key );

				}

				return $value;

			}


		}

	}

  do_action( 'woocommerce_subscription_payment_complete', new WC_Subscription( $order ) );

}

/**
 * Add Faux Subscription Class and Contains Renewal Function so Autoship works with WooCommerce PayPal
 * Checkout Gateway > Smart Button. Only defines the function & class if the cart has autoship items
 */
function autoship_function_checkout_paypal_billing_agreement_adjustment() {

  // Check if the current cart has autoship items and only then use Faux functions
  return autoship_cart_has_valid_autoship_items();

}

// ==========================================================
// CREATE SCHEDULED ORDER CHECKOUT FUNCTIONS
// ==========================================================

/**
* Wrapper for creating scheduled orders at checkout only.
* @uses autoship_create_scheduled_orders()
*
* @param WC_Order|int $order A WC Order object or order id.
* @param string       Optional. $creation_date The date the order should be
*                     created based on should be 'Y-m-d H:i:s' format.
*                     Default NULL.
* @return void
*/
function autoship_create_scheduled_orders_on_checkout ( $order_id, $creation_date = null ) {

  // Get order
  $order = wc_get_order( $order_id );
  
  // Check if this is a checkout order
  // This should be updated at some point when we adjust
  // QPilot to update the status only vs. run payment_complete.
  $created_via = $order->get_created_via();
  if ( ( ! $order ) || ( $created_via != 'checkout' ) ) {
    // This is not a checkout order
    return;
  }

  $scheduled_order_ids = autoship_create_scheduled_orders( $order_id, $creation_date );

  do_action( 'autoship_post_create_scheduled_orders_on_checkout', $scheduled_order_ids, $order_id );

}

/**
 * Wrapper specifically for creating scheduled orders from WC Checkout orders
 * That don't fire the payment complete hook but instead change the Order Status
 * from Pending to Processing via a different channel
 * NOTE This is the route Checkout.com goes.
 *
 * @uses autoship_create_scheduled_orders()
 *
 * @param int 				 $order_id The WC Order ID
 * @param WC_Order     $order The WC Order Object
 * @return void
 */
function autoship_create_scheduled_orders_on_payment_status_changed ( $order_id, $order ) {

	// Check if this is a checkout order
	// This should be updated at some point when we adjust
	// QPilot to update the status only vs. run payment_complete.
	$created_via = $order->get_created_via();
	if ( ( ! $order ) || ( $created_via != 'checkout' ) ) {
		// This is not a checkout order
		return;
	}

	// Now check if the order is one that should be processed this channel and not
	// via checkout
	if ( apply_filters( 'autoship_maybe_create_scheduled_orders_on_payment_status_change', false, $order_id ) ){

	  $scheduled_order_ids = autoship_create_scheduled_orders( $order_id, NULL );

	  do_action( 'autoship_post_create_scheduled_orders_on_payment_status_changed', $scheduled_order_ids, $order_id );

	}

}

/**
 * Wrapper specifically for creating scheduled orders from WC Checkout orders
 * That don't fire the payment complete hook in checkout but instead fire a custom webhook callback action
 *
 * @uses autoship_create_scheduled_orders()
 *
 * @param int 				 $order_id The WC Order ID
 * @param WC_Order     $order The WC Order Object
 * @param array 			 $data  Optional. Additional data from callback
 * @return void
 */
function autoship_create_scheduled_orders_on_payment_captured_hook ( $order_id, $order, $data = NULL ) {

	// Check if this is a checkout order
	// This should be updated at some point when we adjust
	// QPilot to update the status only vs. run payment_complete.
	$created_via = $order->get_created_via();
	if ( ( ! $order ) || ( $created_via != 'checkout' ) ) {
		// This is not a checkout order
		return;
	}

	$scheduled_order_ids = autoship_create_scheduled_orders( $order_id, NULL );
	do_action( 'autoship_post_create_scheduled_orders_on_payment_captured_hook', $scheduled_order_ids, $order_id );

}

/**
 * Checks if the order is a Checkout.com order and thus should be handled
 * via the status change filter
 *
 * @uses autoship_create_scheduled_orders()
 *
 * @param bool    $maybe_create True if we should create on status change else false
 * @param int 		$order_id The WC Order ID
 * @return bool		True to Maybe create Scheduled Order
 */
function autoship_create_checkout_com_scheduled_orders_on_processing ( $maybe_create, $order_id ) {

	// Get the Order
	$order = wc_get_order( $order_id );

	// Bail if order is invalid OR if the status is not processing
	if ( ! $order || ( 'processing' != $order->get_status() ) )
	return $maybe_create;

	// We want to maybe create the Scheduled Order(s) if a) this is a Checkout.com order
	// and b) the order hasn't already generated Scheduled Orders.
	$existing_scheduled_order_keys = autoship_get_order_created_scheduled_orders_key( $order );
	return ( 'wc_checkout_com_cards' != $order->get_payment_method() ) || !empty( $existing_scheduled_order_keys ) ? $maybe_create : true;

}

// ==========================================================
// DEFAULT HOOKED ACTIONS & FILTERS
// ==========================================================

/**
 * WooSubscription Workarounds for Gateways that rely on Billing Agreements
 *
 * @see autoship_woocommerce_subscription_patch()
 * @see autoship_function_checkout_paypal_billing_agreement_adjustment()
 */
add_action( 'woocommerce_payment_complete', 'autoship_woocommerce_subscription_patch', 9, 1 );
add_filter( 'woocommerce_paypal_express_checkout_needs_billing_agreement', 'autoship_function_checkout_paypal_billing_agreement_adjustment', 10, 1 );

/**
 * Enables optional supported gateways like PayPal Payments
 *
 * @see autoship_enable_force_save_for_optional_gateways()
 */
add_filter( 'autoship_checkout_supported_payment_gateway_save_method_ids', 'autoship_enable_force_save_for_optional_gateways', 10, 1 );

/**
 * Hook used to create Scheduled Orders on non-payment complete actions
 * specifically on WC Order status change
 *
 * @see autoship_create_scheduled_orders_on_payment_status_changed()
 */
add_action( 'woocommerce_order_payment_status_changed', 'autoship_create_scheduled_orders_on_payment_status_changed', 10, 2 );

/**
 * Alternative Action Captures for non-standard gateways
 *
 * @see autoship_create_scheduled_orders_on_payment_captured_hook()
 */
add_action( 'checkout_com_payment_captured', 'autoship_create_scheduled_orders_on_payment_captured_hook', 10, 3 );


/**
 * Adds the Scheduled Order Data to the Order Line Item
 *
 * @see autoship_checkout_create_order_line_item()
 */
add_action( 'woocommerce_checkout_create_order_line_item', 'autoship_checkout_create_order_line_item', 10, 4 );

/**
 * Filters the Enable Guest Checkout Option based on Autoship items in the cart
 *
 * @see autoship_get_option_enable_guest_checkout()
 */
add_filter( 'pre_option_woocommerce_enable_guest_checkout', 'autoship_get_option_enable_guest_checkout', 10, 1 );

/**
 * Includes the Force Save Payment method checkout JS based on Autoship items in the cart
 *
 * @see autoship_force_save_card()
 */
add_action( 'woocommerce_review_order_after_submit', 'autoship_force_save_card', 10, 0 );

/**
 * WooCommerce Payment Complete
 * @see autoship_create_scheduled_orders_on_checkout()
 */
add_action( 'woocommerce_payment_complete', 'autoship_create_scheduled_orders_on_checkout', 11, 1 );
add_action( 'woocommerce_thankyou_cod', 'autoship_create_scheduled_orders_on_checkout', 11, 1 );

