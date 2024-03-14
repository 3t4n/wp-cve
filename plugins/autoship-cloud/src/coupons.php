<?php

function autoship_coupon_usage_restriction_panels() {
	woocommerce_wp_checkbox( array(
		'id' => 'autoship_exclude_autoship',
		'label' => __( 'Exclude Autoship', 'autoship' ),
		'description' => __( 'Disable this coupon for carts containing Autoship enabled items.', 'autoship' ),
	) );
}
add_action( 'woocommerce_coupon_options_usage_restriction', 'autoship_coupon_usage_restriction_panels' );

function autoship_save_custom_coupon_fields( $post_id ) {
	$autoship_field_names = array(
		'autoship_exclude_autoship'
	);
	foreach ( $autoship_field_names as $name ) {
		$value = isset( $_POST[ $name ] ) ? sanitize_text_field( $_POST[ $name ] ) : '';
    update_post_meta( $post_id, $name, $value );
	}
}
add_action( 'woocommerce_process_shop_coupon_meta', 'autoship_save_custom_coupon_fields', 10, 1 );

/**
 * Checks if the WC Coupon is valid for the cart.
 *
 * @param bool $valid The current value
 * @param WC_Coupon $coupon The WC Coupon object.
 * @return bool True if valid else false.
 */
function autoship_coupon_is_valid( $valid, $coupon ) {

	if ( ! $valid )
	return $valid;

	if ( autoship_cart_has_valid_autoship_items() ) {

    $coupon_id = $coupon->get_id();
		$exclude_autoship = get_post_meta( $coupon_id, 'autoship_exclude_autoship', true );

		if ( 'yes' == $exclude_autoship )
		$valid = false;

	}

	return $valid;

}
add_action( 'woocommerce_coupon_is_valid', 'autoship_coupon_is_valid', 10, 2 );

/**
 * Checks if coupons are enabled
 *
 * @return bool True if enabled else false.
 */
function autoship_allow_coupons(){

  // Get the current site settings
  $settings = autoship_get_site_order_settings();
  return !is_wp_error( $settings ) ? (bool) $settings['isShowCoupons'] : false;

}

/**
 * Returns the currently applied Coupon Data from the supplied Scheduled order data
 *
 * @param array $autoship_order
 * @return array An array of currently applied coupons
 */
function autoship_get_valid_coupons_from_order( $autoship_order ){

  $coupons = array();
  foreach ( $autoship_order['couponsHistory'] as $coupon ) {

    if ( $coupon['valid'] )
    $coupons[$coupon['coupon']['code']] = $coupon['coupon'];

  }

  return $coupons;

}

/**
 * Checks if the supplied coupon is a valid format
 * Currently can't contain comma's
 * and be longer than 20 characters.
 *
 * @param string $coupon_code
 * @return bool True if valid else false.
 */
function autoship_valid_coupon_format( $autoship_coupon_code ){

  $valid = ( strlen( $autoship_coupon_code  ) <= 20 );
  $split = explode( ',', $autoship_coupon_code );
  $valid = ! ( count( $split ) > 1 );

  return apply_filters( 'autoship_valid_coupon_format', $valid, $autoship_coupon_code );

}

/**
 * Checks if the supplied coupon is applied to the order and
 * uses API to remove it from the schedule.
 *
 * @param int $order_id The scheduled order id.
 * @param string $coupon_code The coupon code to validate.
 * @param stdClass $order The Autoship Order.
 *
 * @return bool|WP_Error True if successful else false or WP_Error.
 */
function autoship_validate_and_remove_scheduled_order_coupon( $order_id, $coupon_code, $order = null ){

  // Get Current Scheduled Order if not supplied
  if ( !isset( $order ) || empty( $order ) ) {

    $order = autoship_get_scheduled_orders( $order_id );

    if ( is_wp_error( $order ) )
    return false;

    $order = autoship_convert_object_to_array( $order );

  }

  $original_order_coupons = empty( $order['coupons'] ) ? array() : $order['coupons'];

  // Check if its already applied and skip.
  $original_order_coupons = array_flip( $original_order_coupons );

  if ( !array_key_exists( $coupon_code, $original_order_coupons ) ){
    wc_add_notice( __( 'The supplied coupon does not exist on this order.', 'autoship' ), 'notice' );
    return false;
  }

  // Remove the coupon & flip back
  unset( $original_order_coupons[$coupon_code] );
  $original_order_coupons = array_keys( $original_order_coupons );

  // Attach the new coupon to the order.
  $order['coupons'] = !empty( $original_order_coupons ) ? $original_order_coupons: NULL;

  $client = autoship_get_default_client();

  // Now update the coupons for the schedule.
  try {

    $result = $client->update_scheduled_order( $order_id, $order );

  } catch ( Exception $e ) {

    if ( '404' == $e->getCode() ){
      $notice = new WP_Error( 'Order Not Found', __( "The supplied order can not be found in QPilot", "autoship" ) );
      autoship_log_entry( __( 'Autoship Scheduled Order Coupons', 'autoship' ), sprintf( 'The supplied order #%d can not be found in QPilot. Additional Details: Error Code %s - %s', $order_id, $e->getCode(), $e->getMessage() ) );
    } else {
      $notice = autoship_expand_http_code( $e->getCode() );
      $notice = new WP_Error( 'Coupon Removal Failed', __( $notice['desc'], "autoship" ) );
      autoship_log_entry( __( 'Autoship Scheduled Order Coupons', 'autoship' ), sprintf( 'Order #%d Coupon Removal Failed. Additional Details: Error Code %s - %s', $order_id, $e->getCode(), $e->getMessage() ) );
    }

    return $notice;

  }

  return true;

}

/**
* Search for coupon(s)
* @param int $page The results page to return.
* @param string $search The search string.
* @param bool $all When True return all results starting with the supplied page
*
* @return array|WP_Error Array of stdClass coupon objects
*/
function autoship_search_scheduled_order_coupons( $page = 1, $search = NULL, $all = true ){

  $client = autoship_get_default_client();

  try {

    // Search for the coupon.
    $coupons = $client->get_coupons( $page, $search );

  } catch ( Exception $e ) {

    $notice = autoship_expand_http_code( $e->getCode() );
    $notice = new WP_Error( 'Coupon Search Failed', __( $notice['desc'], "autoship" ) );
    autoship_log_entry( __( 'Autoship Scheduled Order Coupons', 'autoship' ), sprintf( 'The Coupon Search Failed. Additional Details: Error Code %s - %s', $e->getCode(), $e->getMessage() ) );

  }

  if ( is_wp_error( $coupons ) )
  return $coupons;

  // Continue the search if there are more results.
  if ( $all && ( $coupons->totalPages > $page ) ){

    $page++;
    $new_coupons = autoship_search_scheduled_order_coupons( $page, $search, $all );
    return !is_wp_error( $new_coupons ) ? array_merge( $coupons->items, $new_coupons ) : $new_coupons;

  }

  return $coupons->items;

}

/**
 * Checks if the supplied coupon is a valid format and
 * uses API to validate against schedule.
 *
 * @param int $order_id The scheduled order id.
 * @param string $coupon_code The coupon code to validate.
 * @param stdClass|array $order The Autoship Order.
 *
 * @return bool|WP_Error True if valid, false if not else WP_Error on failure.
 */
function autoship_validate_and_apply_scheduled_order_coupon( $order_id, $coupon_code, $order = NULL ){

  // Validate Coupon format
  if ( !autoship_valid_coupon_format( $coupon_code ) )
  return new WP_Error( 'Coupon Validation Failed',__('The supplied coupon format is invalid.  Please enter a valid code.', 'autoship' ), 'error' );

  // Get Current Scheduled Order object if not supplied.
  if ( !isset( $order ) || empty( $order ) ) {

    $order = autoship_get_scheduled_order( $order_id );

    if ( is_wp_error( $order ) )
    return $order;

  }

  // HACK: Remove spaces Incorrectly added by QPilot
  $order_coupons          = is_array( $order ) ? $order['coupons'] : $order->coupons;
  $original_order_coupons = preg_replace('/\s+/', '', $order_coupons );

  // Check if its already applied and skip.
  if ( array_key_exists( $coupon_code, array_flip( $original_order_coupons ) ) )
  return new WP_Error( 'Coupon Validation Failed',__( 'The supplied coupon has already been applied.', 'autoship' ), 'error' );

  // Attach the new coupon to the order.
  $applied_coupons = empty( $original_order_coupons ) ? $coupon_code : implode( ',' , $original_order_coupons ) . ',' . $coupon_code;

  $client = autoship_get_default_client();

  // Now update the payment method for the schedule.
  try {

    // Validate Coupons returns coupon objects.
    $result = $client->validate_coupons( $order_id, $applied_coupons );

  } catch ( Exception $e ) {

    if ( '404' == $e->getCode() ){
      $notice = new WP_Error( 'Order Not Found', sprintf( __( "Order #%d can not be found in QPilot", "autoship" ), $order_id ) );
      autoship_log_entry( __( 'Autoship Scheduled Order Coupons', 'autoship' ), sprintf( 'The supplied order #%d can not be found in QPilot. Additional Details: Error Code %s - %s', $order_id, $e->getCode(), $e->getMessage() ) );
    } else {
      $notice = autoship_expand_http_code( $e->getCode() );
      $notice = new WP_Error( 'Coupon Validation Failed', __( $notice['desc'], "autoship" ) );
      autoship_log_entry( __( 'Autoship Scheduled Order Coupons', 'autoship' ), sprintf( 'Order #%d Coupons Validation Failed. Additional Details: Error Code %s - %s', $order_id, $e->getCode(), $e->getMessage() ) );
    }

    return $notice;

  }

  $valid = false;
  foreach ( $result as $key => $coupon ) {
    if ( $coupon_code == $coupon->code )
    $valid = true;
  }

  // Check if the coupon is invalid.
  if ( !$valid )
  return false;

  $result = autoship_apply_scheduled_order_coupon( $order_id, $coupon_code, $order );

  return is_wp_error( $result ) ? false : true;

}

/**
 * Applies a Coupon Code to a Scheduled Order
 * @param int $order_id The Scheduled Order ID.
 * @param string $coupon_code The coupon code to apply.
 *
 * @return stdClass The resulting Scheduled Order.
 */
function autoship_apply_scheduled_order_coupon( $order_id, $coupon_code, $order = NULL ){

  // Get Current Scheduled Order object if not supplied.
  if ( !isset( $order ) || empty( $order ) ) {

    $order = autoship_get_scheduled_order( $order_id, true );

    if ( is_wp_error( $order ) )
    return $order;

  }

  // Attach the coupon to the existing order.
  // Orders come as array's or stdClass objests
  if ( is_array( $order ) ){
    $order['coupons'][] = $coupon_code;
  } else {
    $order->coupons[] = $coupon_code;
  }

  $client = autoship_get_default_client();

  // Since it's valid apply it Now update the coupons for the schedule.
  try {

    $result = $client->update_scheduled_order( $order_id,  $order );

  } catch ( Exception $e ) {

    if ( '404' == $e->getCode() ){
      $notice = new WP_Error( 'Order Not Found', __( "The supplied order can not be found in QPilot", "autoship" ) );
      autoship_log_entry( __( 'Autoship Scheduled Order Coupons', 'autoship' ), sprintf( 'The supplied order #%d can not be found in QPilot. Additional Details: Error Code %s - %s', $order_id, $e->getCode(), $e->getMessage() ) );
    } else {
      $notice = autoship_expand_http_code( $e->getCode() );
      $notice = new WP_Error( 'Coupon Update Failed', __( $notice['desc'], "autoship" ) );
      autoship_log_entry( __( 'Autoship Scheduled Order Coupons', 'autoship' ), sprintf( 'Order #%d Coupons Update Failed. Additional Details: Error Code %s - %s', $order_id, $e->getCode(), $e->getMessage() ) );
    }

    return $notice;

  }

  return $result;

}

/**
 * Returns the WC Coupon Type based on the supplied QPilot Coupon Type.
 *
 * @param string $type The QPilot coupon type to lookup.
 * @param bool $val True return key's value if exists. False return false for non-strings.
 * @return NULL|string|bool The WC Coupon type else false if it doesn't exist.
 */
function autoship_get_coupon_type_by_code( $type, $val = false ){

  $codes = apply_filters( 'autoship_coupon_types_by_code', array(
    'ReduceSubtotalByPercentage'  => 'percent',
    'ReduceSubtotalByAmount'      => 'fixed_cart',
    'ReduceShippingByAmount'      => 'fixed_shipping',
    'SetShippingToAmount'         => 'set_shipping',
    'ReduceProductPriceByPercentage' => 'percent', //percent
    'ReduceProductPriceByAmount'  => 'fixed_product',
    'ReduceShippingByPercentage'  => 'percent_shipping',
  ) );

  if ( $val )
  return array_key_exists( $type, $codes ) ? $codes[$type] : false;

  return isset( $codes[$type] ) ? $codes[$type] : false;

}

/**
 * Generates a virtual WC Coupon using the supplied data.
 *
 * @param array $coupon_data The data to use for creating the coupon
 * @return WC_Coupon The virtual coupon.
 */
function autoship_generate_virtual_wc_coupon( $coupon_data ){

  $args = wp_parse_args( $coupon_data, array(
    'code'          => '',
    'description'   => '',
    'discount_type' => '',
    'exclude_sale_items' => false,
    'amount'        => 0,
    'product_ids'   => [],
    'excluded_product_ids' => [],
    'virtual' => true
  ) );

  $data = [
    'code' => $args['code'],
    'description'          => $args['description'],
    'discount_type'        => $args['discount_type'],
    'exclude_sale_items'   => $args['exclude_sale_items'],
    'amount'               => $args['amount'],
    'product_ids'          => $args['product_ids'],
    'excluded_product_ids' => $args['excluded_product_ids'],
    'virtual'              => $args['virtual'],
  ];
  // Run filter so that WC uses virtual coupon instead of trying to get one from DB
  add_filter( 'woocommerce_get_shop_coupon_data', function() use($data) {
    return $data;
  } );

  $coupon = new WC_Coupon($args['code']);
 
  return apply_filters( 'autoship_generated_virtual_wc_coupon', $coupon, $coupon_data );
}

/**
 * Register Shipping coupons used by Autoship
 * 
 * @param array $types  Array of WC coupon types
 * @return array $types Array of WC coupon types
 */
function autoship_register_shipping_wc_coupons( $types ) {

  // Skip if WP admin pages so that they cannot be manualy created in administration
  if(!is_admin()) {
    $types['fixed_shipping'] = __( 'Fixed Shipping', 'autoship' );
    $types['set_shipping'] = __( 'Set Shipping', 'autoship' );
    $types['percent_shipping'] = __( 'Percent Shipping', 'autoship' );
  }
  return $types;
}

add_filter( 'woocommerce_coupon_discount_types', 'autoship_register_shipping_wc_coupons', 20 );


/**
 * Create a Coupon in Qpilot
 *
 * @param string $code The coupon code to use
 * @param array $coupon {
 *      @type string $code                          The coupon code
 *      @type string $name                          The coupon name
 *      @type string $country                       Required Shipping Country for this coupon to be valid
 *      @type string $postcode                      Required Shipping Postcode for this coupon to be valid
 *      @type string $state                         Required Shipping State for this coupon to be valid
 *      @type string $city                          Required Shipping City for this coupon to be valid
 *      @type int $minUnits                         Min # units required for this coupon to be valid
 *      @type int $maxUnits                         Max # units required for this coupon to be valid
 *      @type int $minWeight                        Min weight required for this coupon to be valid
 *      @type int $maxWeight                        Max weight required for this coupon to be valid
 *      @type string $weightUnitType                The required WeightUnitType for this coupon to be valid
 *                                                  (i.e.'Pound', 'Ounce', 'Kilogram', 'Gram' )
 *      @type int $cycles                           Total Cycles this coupon will apply
 *      @type int $maxCyclesPerSite                 Max Scheduled Order cycles this coupon can be used across a site
 *      @type float $minSubtotal                    Min subtotal required for this coupon
 *      @type float $maxSubtotal                    Max subtotal required for this coupon
 *      @type int $maxCyclesPerCustomer             Max Scheduled Order cycles this coupon can be used by a customer
 *      @type int $maxAssignmentsPerCustomer        Max number of times this coupon can be used by a customer
 *      @type int $maxAssignmentsPerSite            Max number of times this coupon can be used across a site
 *      @type int $minScheduledOrderCycles          Required min cycles for a Scheduled Order for coupon to be valid
 *      @type int $maxScheduledOrderCycles          Required max cycles for a Scheduled Order for coupon to be valid
 *      @type int $maxCyclesPerScheduledOrder       Max Scheduled Order cycles a coupon will be valid
 *      @type float $maxDiscountPerCustomer         Max discount $ allowed for a single customer
 *      @type float $amount                         The discount $ amount
 *      @type string $discountType                  The Coupon Type ( i.e. 'None', 'ReduceSubtotalByPercentage',
 *                                                  'ReduceSubtotalByAmount', 'ReduceShippingByPercentage',
 *                                                  'ReduceShippingByAmount', 'SetShippingToAmount' )
 *      @type float $maxPercentageDiscount          Sets the maximum amount that can be discounted by the percentage discount
 *      @type string $expirationDate                The coupon expiration date ( i.e. 2019-12-10T13:06:10.929Z )
 *      @type bool $isStackable                     True if the coupon can be used with other discounts.
 *      @type bool $active                          True if the coupon is active
 * }
 * @return WP_Error|stdClass The created coupon object or WP_Error on failure.
 */
function autoship_create_coupon( $code, $coupon = array() ){

 $coupon_data['code'] = $code;
 $coupon_data = apply_filters("autoship_create_coupon_default_data", $coupon_data );

 $client = autoship_get_default_client();

 // Since it's valid apply it Now update the coupons for the schedule.
 try {

   $result = $client->create_coupon(  $coupon_data );

 } catch ( Exception $e ) {

   if ( '404' == $e->getCode() ){
     $notice = false;
   } else {
     $notice = autoship_expand_http_code( $e->getCode() );
     $notice = new WP_Error( 'Coupon Creation Failed', __( $notice['desc'], "autoship" ) );
     autoship_log_entry( __( 'Autoship Scheduled Order Coupons', 'autoship' ), sprintf( 'Creating the Coupon %s Failed. Additional Details: Error Code %s - %s', $code, $e->getCode(), $e->getMessage() ) );
   }

   return $notice;

 }

 return $result;

}

/**
 * Deletes a Scheduled Order from QPilot.
 * @uses QPilotClient::delete_order()
 *
 * @param int $coupon_id The Qpilot Coupon ID.
 * @return bool|WP_Error true on success or false|WP_Error on failure.
 */
function autoship_delete_coupon ( $coupon_id ){

    $client = autoship_get_default_client();

    try {

      $client->delete_coupon( $coupon_id );

    } catch ( Exception $e ) {

      if ( '404' == $e->getCode() ){
        $notice = new WP_Error( 'Coupon Not Found', __( "The supplied coupon can not be found in QPilot", "autoship" ) );
    		autoship_log_entry( __( 'Autoship Orders', 'autoship' ), sprintf( '%d The supplied coupon #%d can not be found in QPilot. Additional Details: %s', $e->getCode(), $coupon_id, $e->getMessage() ) );
      } else {
        $notice = autoship_expand_http_code( $e->getCode() );

        $notice = autoship_is_user_http_message( $e->getCode() ) ?
        new WP_Error( $notice['msg'], $notice['desc'] ) : new WP_Error( 'Coupon Delete Failed', __( $notice['desc'], "autoship" ) );
    		autoship_log_entry( __( 'Autoship Orders', 'autoship' ), sprintf( '%d Coupon #%d Delete Failed. Additional Details: %s', $e->getCode(), $coupon_id, $e->getMessage() ) );
      }

      return apply_filters('autoship_scheduled_order_api_action_excemption_handler', $notice, $e, $order_id );

    }

    return true;

}

/**
 * Retrieves the QPilot Coupon by Coupon code
 *
 * @param string $code The QPilot coupon code
 * @return stdClass|false The QPilot Coupon Object else false if it doesn't exist.
 */
function autoship_get_coupon_by_code( $code ){

  $client = autoship_get_default_client();

  // Since it's valid apply it Now update the coupons for the schedule.
  try {

    $result = $client->get_coupon_by_code( $code );

  } catch ( Exception $e ) {

    if ( '404' == $e->getCode() ){
      $notice = false;
    } else {
      $notice = autoship_expand_http_code( $e->getCode() );
      $notice = new WP_Error( 'Coupon Retrieval Failed', __( $notice['desc'], "autoship" ) );
      autoship_log_entry( __( 'Autoship Scheduled Order Coupons', 'autoship' ), sprintf( 'Retrieving Coupon by Code Failed. Additional Details: Error Code %s - %s', $order_id, $e->getCode(), $e->getMessage() ) );
    }

    return $notice;

  }

  return $result;

}

/**
 * Runs QPilot Orders through a pre-processor to remove fee lines
 * which will later be added as a virtual coupon so that taxes and totals
 * are calculated correctly.
 *
 * @param  WP_REST_Request $request The current Request data.
 * @param  WP_REST_Request $original_request The original Request data.
 * @return WP_Error|WP_REST_Response
 */
function autoship_qpilot_orders_update_via_rest_convert_fees_to_coupons( $request, $original_request ) {

  // allow devs to flip the path from the new pre-process route to the legacy direct
  if ( apply_filters('autoship_qpilot_orders_via_rest_enable_fee_lines', autoship_rest_order_fee_lines_enabled() , $request ) )
  return $request;
  
  // Check for Coupon Metadata
  $coupons = array();
  if ( isset( $request['meta_data'] ) && !empty( $request['meta_data'] ) ){
    foreach ($request['meta_data'] as $key => $meta ) {
      if ( '_qpilot_coupon_data' == $meta['key'] ){
        $coupons = $meta['value'];
        break;
      }
    }
  }
  
  $new_coupons = $new_codes = array();
  // Loop through coupon objects and generate virtual coupon.
  foreach ( $coupons as $key => $coupon ) {

    $type = autoship_get_coupon_type_by_code( $coupon['discountType'] );

  if ( !empty( $type ) ){

      $coupon_data = array(
        'code'          => 'Coupon: ' . $coupon['code'],
        'description'   => sprintf( __( 'QPilot Dynamic Coupon generated from Coupon ID #%d.', 'autoship' ), $coupon['id'] ),
        'discount_type' => $type,
        'amount'        => $coupon['amount'],
        'product_ids'   => []
      );

      $excluded_ids = [];
      if( ( $coupon['productDiscountNotAppliesWhenItHasSalePrice'] ) ) {
        // Check if theres item with salePrice and exclude it
        foreach ( $request['line_items'] as $item ) {
            if ( isset( $item['meta_data'] ) && is_array( $item['meta_data'] ) ) {
                foreach ( $item['meta_data'] as $meta ) {
                    if ( ( isset($meta['key'] ) && $meta['key'] === '_qpilot_scheduled_order_item' ) && isset( $meta['value']['salePrice'] ) ) {
                        $excluded_ids[] = $item['product_id'];
                    }
                }
            }
        }
      }
      if( $excluded_ids ) {
        $coupon_data['excluded_product_ids'] = $excluded_ids;
      }

      if ( isset( $coupon['product'] ) && isset( $coupon['product']['externalId'] ) && !in_array( $coupon['product']['externalId'] , $excluded_ids ) ){
        $coupon_data['product_ids'][] = $coupon['product']['externalId'];
        
      }
      if ( isset( $coupon['productGroupId'] ) ){
        // get products from group
        $product_group = autoship_get_product_group_by_id( $coupon['productGroupId'] );
 
        if ( !is_wp_error( $product_group ) ){
          if ( isset( $product_group->products ) && is_array( $product_group->products ) ) {
            foreach ( $product_group->products as $prod ) {
              if ( !in_array( $prod->id, $excluded_ids ) ) {
                $coupon_data['product_ids'][] = $prod->id;
              }
            }
          }
        }
      }
      $new_coupons['Coupon: ' . $coupon['code']] = $coupon_data;
      
    }
  }

  // Allow devs to hook in so that these could be converted to real coupons or modify on the fly
  $new_coupons = apply_filters( 'autoship_rest_generated_virtual_wc_coupons', $new_coupons, $coupons, $request );

  // Grab the request metadata
  $meta_data = $request['meta_data'];

  // Add the virtual coupons data to the request for later use.
  if ( !empty( $new_coupons ) ){
    $meta_data[] = array(
      'key'   => '_qpilot_dynamic_coupons',
      'value' => $new_coupons
    );
  }

  // Check for Fee lines and if a coupon has been generated for it remove the line.
  if ( isset( $request['fee_lines'] ) && !empty( $request['fee_lines'] ) ){

    /**
    * Loop through the fee lines and remove any we have coupons for.
    * Any that get removed add to the legacy fee metadata.
    */
    $fee_lines = $request['fee_lines'];
    $legacy_fee_lines = array();

    foreach ($fee_lines as $key => $fee) {
      if ( isset( $new_coupons[$fee['name']] ) ){
        $legacy_fee_lines[] = $fee;
        unset( $fee_lines[$key] );
      }
    }

  }

  // Set the new fee line values.
  if ( empty( $fee_lines ) ){
    $request->offsetUnset( 'fee_lines' );
  } else {
    $request->set_param(
      'fee_lines',
      $fee_lines
    );
  }

  // Attach any legacy fee lines
  if ( !empty( $legacy_fee_lines ) ){
    $meta_data[] = array(
      'key'   => '_qpilot_legacy_fee_lines',
      'value' => $legacy_fee_lines
    );
  }

  // Re-attach the metadata
  $request->set_param(
    'meta_data',
    $meta_data
  );

  return $request;

}
add_filter( 'autoship_qpilot_orders_via_rest', 'autoship_qpilot_orders_update_via_rest_convert_fees_to_coupons', 10, 2 );


 /**
 * Hook into the Order Save and Applies any QPilot Virtual Coupons
 * @param bool $and_taxes True if Taxes should be calculated.
 * @param WC_Abstract_Order $order The current order.
 */
 function autoship_qpilot_orders_update_via_rest_apply_coupons( $and_taxes, $order ){

  // Only continue if the order is via the rest api and if it's a
  if ( !( $order instanceof WC_Order ) || ( 'rest-api' != $order->get_created_via() ) )
 	return;

  // We Use the Processing ID to ensure even though the order came through rest-api it's
  // a QPilot generated order.
  $id = autoship_get_scheduled_order_processing_id( $order );

  if ( apply_filters( 'autoship_qpilot_orders_update_via_rest_apply_coupons', empty( $id ), $id, $order ) )
 	return;

  // Get the virtual coupons for the order and if they exist apply them.
  $coupons = autoship_get_order_associated_virtual_wc_coupons( $order );

  if ( !empty( $coupons ) ){

    /**
    * Iterate through associated coupons and apply them.
    * NOTE No need to check if they already exist since apply_coupon
    * does that and ignores if they do.
    */
    foreach ($coupons as $code => $coupon){
      // Here we turn saved coupon data into WC_Coupon object
      $wc_coupon = autoship_generate_virtual_wc_coupon( $coupon );
      $order->apply_coupon( $wc_coupon );
    }
  }

 }
 add_action( 'woocommerce_order_after_calculate_totals', 'autoship_qpilot_orders_update_via_rest_apply_coupons', 10, 2 );
