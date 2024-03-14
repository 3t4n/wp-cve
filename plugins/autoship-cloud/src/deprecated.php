<?php
/**
 * Deprecated functions from past Autoship versions. You shouldn't use these
 * functions and look for the alternatives instead. The functions will be
 * removed in a later version.
 *
 */

/*
 * Deprecated functions come here to die.
 */

 /**
  * Retrieves Checkout Price - Either Autoship or Product price
  *
  * @deprecated 2.0 Use autoship_checkout_price()
  * @see autoship_checkout_price()
  * @param WC_Product|int $product WC_Product, WC_Product_Variation or Id
  * @return float  The checkout price.
  */
 function autoship_discounted_price( $product ) {
    _deprecated_function( __FUNCTION__, '2.0', 'autoship_checkout_price()' );
    return autoship_checkout_price( $product );
 }

 /**
  * Gets formatted amount for display.
  * @deprecated 2.0 Use autoship_get_formatted_price()
  *
  * @param float  $amount The total amount to format.
  * @param  array $args  Arguments to format a price {
  *     Array of arguments.
  *     Defaults to empty array.
  *
  *     @type bool   $ex_tax_label       Adds exclude tax label.
  *                                      Defaults to false.
  *     @type string $currency           Currency code.
  *                                      Defaults to empty string (Use the result from get_woocommerce_currency()).
  *     @type string $decimal_separator  Decimal separator.
  *                                      Defaults the result of wc_get_price_decimal_separator().
  *     @type string $thousand_separator Thousand separator.
  *                                      Defaults the result of wc_get_price_thousand_separator().
  *     @type string $decimals           Number of decimals.
  *                                      Defaults the result of wc_get_price_decimals().
  *     @type string $price_format       Price format depending on the currency position.
  *                                      Defaults the result of get_woocommerce_price_format().
  * }
  *
  * @return string
 */
 function autoship_get_formatted_amount( $amount, $args = array() ){
    _deprecated_function( __FUNCTION__, '2.0.2', 'autoship_get_formatted_price()' );
   return apply_filters( 'autoship_get_formatted_amount', autoship_get_formatted_price( $amount, $args ), $amount, $args );
 }

 /**
 * Outputs the Autoship Schedule Options Template to the frontend Cart
 * Directly below the cart item name.
 * @deprecated 2.0.5 Use autoship_display_cart_item_options()
 *
 * @param string $product_link The current Product Name or Anchor Link.
 * @param array $cart_item The current cart item data.
 * @param string $cart_item_key The current cart item key
 *
 */
 function autoship_cart_item_name( $product_link, $cart_item, $cart_item_key ) {
  _deprecated_function( __FUNCTION__, '2.0.5', 'autoship_display_cart_item_data()' );
  return autoship_display_cart_item_options( $cart_item, $cart_item_key );
 }

 /**
 * Adds formatted Autoship Data to the cart item data + variations for display on the frontend.
 * @deprecated 2.0.5 Use autoship_display_cart_item_data()
 *
 * @param array $data The key to value array of cart item data to use for display ( Label => Value ).
 * @param array $item The current cart item's data array.
 *
 * @return array $data The updated cart item's display data array.
 */
 function autoship_get_item_data( $data, $item ) {
    _deprecated_function( __FUNCTION__, '2.0.5', 'autoship_display_cart_item_options()' );

   // If not an autoship item bail.
 	if ( !isset( $item['autoship_frequency_type'] ) || empty( $item['autoship_frequency_type'] ) || !isset( $item['autoship_frequency'] ))
 	return $data;

   // Get the autoship values for this item.
 	$frequency       = intval( $item['autoship_frequency'] );
 	$frequency_type  = $item['autoship_frequency_type'];
 	$product_id      = ! empty( $item['variation_id'] ) ? $item['variation_id'] : $item['product_id'];

   // Get the formatting and display name for the schedule - first check if there is a custom name else get default.
   $options = autoship_product_frequency_options( $product_id );
 	$frequency_display_name         = autoship_search_for_frequency_display_name( $frequency_type, $frequency, $options );
 	$product_frequency_display_name = apply_filters( 'autoship_product_frequency_display_name', $frequency_display_name, $product_id );

   $data[] = array(
 		'name'  => apply_filters( 'autoship_frequency_cart_order_item_schedule_display_label', __( 'Schedule', 'autoship' ) ),
 		'value' => $product_frequency_display_name
 	);

 	if ( ! empty( $item['autoship_next_occurrence'] ) ) {

 		$formatted_date = autoship_format_next_occurrence_for_display( $item['autoship_next_occurrence'] );

 		$data[] = array(
 			'name'   => apply_filters( 'autoship_frequency_cart_order_item_next_occurence_display_label', __( 'Next Order', 'autoship' ), $formatted_date, $product_id ),
 			'value'  => apply_filters( 'autoship_frequency_cart_order_item_next_occurence_display_date_value', __( $formatted_date, 'autoship' ), $product_id ),
 		);

   }

 	return $data;
 }

 /**
  * Adds order notes after order insertion/update.
  * @deprecated 2.0.7 Use autoship_woocommerce_rest_insert_shop_object_add_note()
  *
  * @param int $order          The created/inserted order
  * @param string $request      The request.
  * @param int    $creating      If the order is creating.
  */
function autoship_woocommerce_rest_insert_shop_order_object( $order, $request, $creating ) {
  _deprecated_function( __FUNCTION__, '2.0.7', 'autoship_woocommerce_rest_insert_shop_object_add_note()' );
  autoship_woocommerce_rest_insert_shop_object_add_note( $order, $request, $creating );
}

 /**
  * Handle custom qpilot query vars to get orders with qpilot meta keys.
  * @deprecated 2.0.7 Use autoship_handle_meta_query_by_scheduled_order_processing_id()
  *
  * @param array $query - Args for WP_Query.
  * @param array $query_vars - Query vars from WC_Order_Query.
  * @return array modified $query
  */
function handle_custom_qpilot_meta_query( $query, $query_vars ) {
  _deprecated_function( __FUNCTION__, '2.0.7', 'autoship_handle_meta_query_by_scheduled_order_processing_id()' );
  return autoship_handle_meta_query_by_scheduled_order_processing_id( $query, $query_vars );
}

/**
 * Outputs the Dynamic Schedule Cart Widgets iframe
 * @deprecated 2.0.9
 *
 * @param string $path The iframe url.
 */
function autoship_render_widget( $path ) {
  _deprecated_function( __FUNCTION__, '2.0.9' );
	$url = autoship_get_merchants_url() . '/widgets/' . $path;
	return autoship_render_template( 'widget', array( 'url' => $url ) );
}

/**
* Returns the Duration Lock information for an order
* @deprecated 2.1.1
*
* @param array|stdClass $autoship_order The scheduled order.*
* @return array The Lock Information for an order.
*/
function autoship_check_is_order_locked ( $autoship_order ){
  _deprecated_function( __FUNCTION__, '2.1.1', 'autoship_check_lock_status_info()' );

  // Convert stdClass objects to array if needed.
  if ( $autoship_order instanceof stdClass )
  $autoship_order = autoship_convert_object_to_array( $autoship_order );

  // Get the Site Settings that include Lock Duration etc.
  $settings = autoship_get_site_order_settings();

  return autoship_check_lock_status_info ( $autoship_order, $autoship_order['customerId'], $settings );

}


/**
 * Retrieves Autoship Recurring Price - Either Autoship recurring or checkout price
 * @deprecated 2.2.0
 *
 * @param WC_Product|int $product WC_Product, WC_Product_Variation or Id
 * @param array $prices Optional. The Current Checkout Price and Autoship Checkout Price.
 * @return float  The recurring price.
 */
function autoship_recurring_price( $product, $prices = array() ) {

  _deprecated_function( __FUNCTION__, '2.2.0', 'autoship_get_product_recurring_price()' );

  if ( is_numeric( $product ) ){
    $product = wc_get_product( $product );
  }

  $prices = wp_parse_args(
    $prices,
    array(
      'price'    => !isset( $prices['price'] ) ? $product->get_price() : $prices['price'],
      'discount' => !isset( $prices['discount'] ) ? autoship_get_product_recurring_price( $product->get_id() ) : $prices['discount']
    )
  );

  // Recurring may be 0 but if not set we use the checkout price.
  $autoship_price = empty( $prices['discount'] ) ?
	autoship_checkout_price( $product, array( 'price' => $prices['price'] ) ) : $prices['discount'];

  return apply_filters( 'autoship_filter_recurring_price', $autoship_price, $product, $prices );

}

/**
 * Returns the formatted date from the Autoship formatted date.
 * @deprecated 2.2.5
 *
 * @param string|DateTime $date The date string to convert or DateTime object to use.
 * @return string formatted a date based on the offset timestamp
 */
function autoship_get_formatted_date ( $date, $input = false, $format = 'Y-m-d' ){

  _deprecated_function( __FUNCTION__, '2.2.5', 'autoship_get_formatted_local_date()' );

  $display_format = $input ? $format : "";

  // Now return the value based on if it's for a form input or display
  return autoship_get_formatted_local_date( $date, $display_format );

}

/**
 * Returns the next possible date for a scheduled order.
 * @deprecated 2.2.5
 *
 * @param string $date The date string to convert.
 * @return string formatted a date based on the offset timestamp
*/
function autoship_get_next_available_date ( $input = false ){

  _deprecated_function( __FUNCTION__, '2.2.5', 'autoship_get_next_available_nextoccurrence()' );

  $format = $input ? 'Y-m-d' : "";
  $date = new DateTime();

  return apply_filters( 'autoship_get_next_available_date', autoship_get_formatted_local_date( $date, $format ) );

}


/**
 * Returns the gtm offset timestamp
 * @deprecated 2.2.5
 * @return string
 */
function autoship_get_site_zero_time_string() {

  _deprecated_function( __FUNCTION__, '2.2.5' );
	return sprintf( '00:00%+03d:00', get_option( 'gmt_offset' ) );
}

/**
 * Returns the Next Occurrence Date Time Unix Time Stamp
 * @deprecated 2.2.5
 *
 * @param string Next Occurrence Date string.
 * @return string Next Occurrence Date Time Unix Time Stamp
 */
function autoship_format_next_occurrence_timestamp( $next_occurrence ) {

  _deprecated_function( __FUNCTION__, '2.2.5' );
	if ( empty( $next_occurrence ) ) {
		return $next_occurrence;
	}

	if ( is_integer( $next_occurrence ) || preg_match( '/^\d+$/', $next_occurrence ) ) {
		return $next_occurrence;
	}

	$next_occurrence_formatted = $next_occurrence;

	if ( preg_match( '/^\d{4}-\d{2}-\d{2}$/', $next_occurrence_formatted ) ) {
		$next_occurrence_formatted = sprintf( '%sT%s',
			$next_occurrence,
			autoship_get_site_zero_time_string()
		);
	}

	$next_occurrence_timestamp = strtotime( $next_occurrence_formatted );

	return $next_occurrence_timestamp;
}
