<?php

// ==========================================================
// Native UI Scheduled Order Functions
// ==========================================================

/**
 * Returns the formatted address based on WC Settings.
 * @param array $address The address to format.
 * @return string The formatted address.
 */
function autoship_formatted_scheduled_order_addresses ( $address = array(), $type ="" ) {
  $address = apply_filters( 'autoship_formatted_scheduled_order_addresses', $address, $type  );
  $address = WC()->countries->get_formatted_address( $address );
  return $address ? $address : '';
}

/**
 * Returns the Shipping address information for a scheduled order.
 *
 * @param array $autoship_order The scheduled order.
 * @return array The address values normalized to WooCommerce
 */
function autoship_order_get_woocommerce_shipping_address_values( $autoship_order ){

  $address = array();
  $address['first_name'] = $autoship_order['shippingFirstName'];
  $address['last_name']  = $autoship_order['shippingLastName'];
  $address['address_1']  = $autoship_order['shippingStreet1'];
  $address['address_2']  = $autoship_order['shippingStreet2'];
  $address['city']       = $autoship_order['shippingCity'];
  $address['state']      = $autoship_order['shippingState'];
  $address['postcode']   = $autoship_order['shippingPostcode'];
  $address['country']    = $autoship_order['shippingCountry'];
  $address['phone']      = $autoship_order['phoneNumber'];
  $address['company']    = $autoship_order['company'];

  return $address;

}

/**
 * Returns the billing address information for a scheduled order.
 *
 * @param array $autoship_order The scheduled order.
 * @return array The address values normalized to WooCommerce
 */
function autoship_order_get_woocommerce_billing_address_values( $autoship_order ){

  $address = array();
  if ( isset( $autoship_order['paymentMethod'] ) ){

    $address['first_name'] = $autoship_order['paymentMethod']['billingFirstName'];
    $address['last_name']  = $autoship_order['paymentMethod']['billingLastName'];
    $address['address_1']  = $autoship_order['paymentMethod']['billingStreet1'];
    $address['address_2']  = $autoship_order['paymentMethod']['billingStreet2'];
    $address['city']       = $autoship_order['paymentMethod']['billingCity'];
    $address['state']      = $autoship_order['paymentMethod']['billingState'];
    $address['postcode']   = $autoship_order['paymentMethod']['billingPostcode'];
    $address['country']    = $autoship_order['paymentMethod']['billingCountry'];
    $address['phone']      = $autoship_order['phoneNumber'];

  }

  return $address;

}

/**
 * Returns the Shipping or billing address information for a scheduled order.
 *
 * @param array $autoship_order The scheduled order.
 * @param string $type The type of address to retrieve
 * @return array The shipping or billing address values
 */
function autoship_order_address_values( $autoship_order, $type = 'shipping' ){

  $address = 'shipping' == $type ?
  autoship_order_get_woocommerce_shipping_address_values( $autoship_order ) :
  autoship_order_get_woocommerce_billing_address_values( $autoship_order );

  return apply_filters( 'autoship_order_address_values', $address, $autoship_order, $type );

}

/**
 * Checks if an item is within a cycle window
 *
 * @param int $cycles      The current cycle count for this item on the order.
 * @param int $min         The min cycle restriction for this item
 * @param int $max         The max cycle restriction for this item
 *
 * @return bool True when within the cycle window else false.
 */
function autoship_valid_cycle_for_item ( $cycles, $min, $max ){

  // default $cycles to 0 for NULL - order hasn't run yet.
  if ( !isset( $cycles ) )
  $cycles = 0;

  // If there are no min's or max's then this is a valid item.
  if ( !isset( $min ) && !isset( $max ) )
  return true;

  // Start with the min's
  if ( isset( $min ) && ( $cycles < $min ) )
  return false;

  if ( isset( $max ) && ( $cycles >= $max ) )
  return false;

  return true;

}

/**
 * Returns the Duration Lock information for an order
 *
 * @param array|stdClass $autoship_order The scheduled order.
 * @param int $customer_id Optional. The wc customer id to retrieve the orders for.
 *
 * @return array The Lock Information for an order.
 */
function autoship_check_lock_status_info ( $autoship_order, $customer_id = NULL, $settings = array() ){

  // Convert stdClass objects to array if needed.
  if ( $autoship_order instanceof stdClass )
  $autoship_order = autoship_convert_object_to_array( $autoship_order );

  // Grab the customer id
  if ( !isset( $customer_id ) )
  $customer_id = $autoship_order['customerId'];

  // Get the Site Settings and Allow for Customized Lock Durations by Product, Order, and/or Customer.
  $settings = empty( $settings ) ? autoship_get_site_order_settings() : $settings;

  // Set up the current settings
  $lock_data = array(

    'locked'           => $autoship_order['locked'],
    'notice'           => '',
    'duration'         => $settings['lockDurationDays'],
    'processing_start' => autoship_get_formatted_local_date ( $autoship_order['lastEditableDate'] )

  );

  // If no Lock Duration or if filtered to not enable return the default.
  if ( empty( $lock_data['duration'] ) || !apply_filters('autoship_enable_lock_duration_restriction', true ) ){
  $lock_data['locked'] = false;
  return $lock_data; }

  // Allow merchants to filter the locked status based on order status and order details.
  $lockable_status  = apply_filters('autoship_order_is_lockable_status', $lock_data['locked'], $autoship_order['status'], $autoship_order, $customer_id );

  if ( $lockable_status ){

    // Add the default notice
    $lock_data['notice'] = apply_filters( 'autoship_lock_duration_restriction_notification', sprintf( __( "Your order started processing on <strong>%s</strong>.  Only the Payment Method can be changed on this order.", 'autoship' ), $lock_data['processing_start'] ), $lock_data );

  }

  return $lock_data;

}

/**
 * Returns if products can be added to scheduled orders.
 *
 * @param bool True if locked else false.
 * @param string $status The current Scheduled Order status
 * @param array $autoship_order The scheduled order.
 * @param int $customer_id The wc_customer id to retrieve the orders for.
 *
 * @return bool True if locked else false.
 */
function autoship_set_locked_status( $locked, $status, $autoship_order, $customer_id ){
  return 'Processing' == $status ? true : $locked;
}
add_filter( 'autoship_order_is_lockable_status', 'autoship_set_locked_status', 10, 4 );

/**
 * Returns if products can be added to scheduled orders.
 *
 * @param array $autoship_order The scheduled order.
 * @param int $customer_id The wc_customer id to retrieve the orders for.
 * @param int $autoship_customer_id The wc_customer id to retrieve the orders for.
 *
 * @return bool True if allowed else false.
 */
function autoship_allow_added_products( $autoship_order, $customer_id, $autoship_customer_id ){

  return apply_filters( 'autoship_allow_added_products', true , $autoship_order, $customer_id, $autoship_customer_id  );

}

/**
 * Checks if a use has one or more scheduled orders.
 *
 * @param int $customer_id The wc_customer id to retrieve the orders for.
 * @return bool True they have scheduled orders else false.
 */
function autoship_customer_has_scheduled_orders ( $customer_id ){

  // Check if
  $autoship_orders = autoship_get_customer_scheduled_orders ( $customer_id );

  // Add check in case API error occurs
  return !empty( $autoship_orders ) && !is_wp_error( $autoship_orders  );

}

/**
 * Returns the total count of items in the supplied order.
 *
 * @param int $customer_id The wc_customer id to retrieve the orders for.
 * @param int $index The pagination to return.
 * @param int $pagesize The total quantity to return.
 *
 * @return array An array of scheduled orders.
 */
function autoship_get_customer_scheduled_orders ( $customer_id, $index = 0, $pagesize = -1 ){

  // Lgacy call - when customer id wasn't WC ID
  if ( false ){
    $customerid = autoship_get_autoship_customer_id( $customer_id, 'autoship_get_customer_scheduled_orders' );
    $orders = !is_wp_error( $customerid ) ? autoship_get_scheduled_orders( 0 , $customerid ) : array();
  }

  $orders = autoship_get_scheduled_orders( 0 , $customer_id );

  /**
  * @hooked autoship_filter_customer_scheduled_orders_exclude_deleted - 10
  */
  return apply_filters( 'autoship_get_customer_scheduled_orders' , $orders, $customer_id );

}

/**
 * Excludes Deleted Orders.
 *
 * @param array $orders An array of stdClass scheduled order objects.
 * @param int $customer_id The wc_customer id to retrieve the orders for.
 *
 * @return array The filtered orders array of scheduled orders.
 */
function autoship_filter_customer_scheduled_orders_exclude_deleted ( $orders, $customer_id ){

  // If no orders don't bother.
  if ( empty( $orders ) || is_wp_error( $orders ) )
  return array();

  // Iterate through orders and remove deleted.
  foreach ( $orders as $key => $order) {

    $status = is_array( $order ) ?
    $order['status'] : $order->status;

    if ( 'Deleted' == $status )
    unset( $orders[$key] );

  }

  return $orders;

}
add_filter( 'autoship_get_customer_scheduled_orders', 'autoship_filter_customer_scheduled_orders_exclude_deleted', 10 , 2 );

/**
 * Returns an array of all valid system Scheduled Order Statuses
 *
 * @return array
 */
function autoship_get_scheduled_order_statuses (){

  return array (
    'Active'      => 'Active',
    'Locked'      => 'Processing',
    'Processing'  => 'Processing',
    'Pending'     => 'Pending',
    'Failed'      => 'Failed',
    'Paused'      => 'Paused',
    'Deleted'     => 'Deleted',
  );

}

/**
 * Get the nice name for an scheduled order status.
 * Can be modified using {@see }
 *
 * @param  string $autoship_status
 * @return string
 */
function autoship_get_scheduled_order_status_nicename( $autoship_status ){

  // Display the Admin variation for those with rights.
  $nicenames = autoship_rights_checker( 'autoship_admin_account_scheduled_orders_nicenames', array('administrator') ) ?
  array (
    'Active'  => 'Active',
    'Locked'  => 'Processing',
    'Pending' => 'Pending',
    'Failed'  => 'Failed',
    'Paused'  => 'Paused',
    'Deleted' => 'Deleted',
  ) :
  array (
    'Active'  => 'Active',
    'Locked'  => 'Processing',
    'Pending' => 'Pending',
    'Failed'  => 'Paused',
    'Paused'  => 'Paused',
    'Deleted' => 'Deleted',
  );

  $statuses = apply_filters( 'autoship_get_scheduled_order_status_nicenames', $nicenames );

  return isset( $statuses[$autoship_status] ) ? $statuses[$autoship_status] : $autoship_status;

}

/**
 * Returns the next possible date for a scheduled order's Next Occurrence
 *
 * @param array|stdClass $autoship_order The autoship order
 * @return string formatted a date based on the offset timestamp
 */
function autoship_get_next_available_nextoccurrence( $autoship_order, $format = "" ){

  $lock_data = autoship_check_lock_status_info ( $autoship_order );
  // Add default +1 day from today
  $now = '+1 day';
  $occurrence_offset = false;
  // If order doesn't have occurrence offset set, use site settings
  if ( isset( $autoship_order['nextOccurrenceOffset'] ) && $autoship_order['nextOccurrenceOffset'] !== null ) {
    $occurrence_offset = $autoship_order['nextOccurrenceOffset'];
  } else {
    $settings = autoship_get_remote_site_settings();
    if ( !empty( $settings ) && isset( $settings['orderProcessingOffset'] ) && !empty( $settings['orderProcessingOffset'] ) ) {
      $occurrence_offset = $settings['orderProcessingOffset'];
    }
  }
  
  if ( isset( $lock_data['duration'] ) && $lock_data['duration'] > 0 ) {
    $now = $lock_data['duration'];
  }

  if ( $occurrence_offset && $occurrence_offset > 0 ) {
    $now = is_numeric( $now ) ? $now + $occurrence_offset : $occurrence_offset;
  }

  // If lock duration or occurrence offset available, increase date by number of days
  if ( is_numeric( $now ) ) {
    $now += 1;
    $now = '+' . $now  . ' day' . ( $now  == 1 ? '' : 's' );
  }

  $date = new DateTime( $now, new DateTimeZone( "UTC") );

  return apply_filters( 'autoship_get_next_available_nextoccurrence', autoship_get_formatted_local_date( $date, $format ), $autoship_order );
  
}

/**
 * Returns the total count of items in the supplied order.
 *
 * @param array|stdClass $autoship_order The autoship order object.
 * @param bool $return_ids false the total count of items will be returned only else the an array of line item ids and counts.
 *
 * @return int|array The total count of items in the order.
 */
function autoship_get_item_count( $autoship_order, $return_ids = false ){

  $total_items = 0;
  $items = array();
  if ( $autoship_order instanceof stdClass ){
    foreach ($autoship_order->scheduledOrderItems as $item ){

      $cycle_item      =  isset( $item->minCycles ) || isset( $item->maxCycles );
      $valid_for_cycle = $cycle_item ? autoship_valid_cycle_for_item( $item->cycles, $item->minCycles, $item->maxCycles ) : true;
      if ( apply_filters( 'autoship_scheduled_order_form_item_exclude_out_of_cycle', true, $autoship_order ) && !$valid_for_cycle ){
        continue;
      }

      $total_items += $item->quantity;
      $items[] = $item->id;
    }
  } else {
    foreach ($autoship_order['scheduledOrderItems'] as $item ){

      $cycle_item      =  isset( $item['minCycles'] ) || isset( $item['maxCycles'] );
      $valid_for_cycle = $cycle_item ? autoship_valid_cycle_for_item( $item['cycles'], $item['minCycles'], $item['maxCycles'] ) : true;
      if ( apply_filters( 'autoship_scheduled_order_form_item_exclude_out_of_cycle', true, $autoship_order ) && !$valid_for_cycle ){
        continue;
      }

      $total_items += $item['quantity'];
      $items[] = $item['id'];

    }
  }

  return !$return_ids ? $total_items : array( 'count' => $total_items, 'ids' => $items );

}

/**
 * Enables/Disables the use of WC Tax Settings to Display Prices in the Native UI.
 * @return bool True if WC Tax settings should be used else false.
 */
function autoship_enable_wc_tax_settings_for_prices_display(){
  return apply_filters('autoship_enable_wc_tax_settings_for_prices_display_default', true );
}

/**
 * Filters to Show $0 Total in the Scheduled Orders template view
 *
 * @param bool  $exclude True to exclude $0 total in Scheduled Orders screen calcs
 * @return bool false so $0 orders totals show in the Scheduled Orders screen
 */
function autoship_include_scheduled_order_total_zero_total( $exclude ){
  return false;
}

/**
 * Filters to Show $0 Total in the Scheduled Orders template view
 *
 * @param bool  $exclude True to exclude $0 total in Scheduled Orders screen calcs
 * @return bool false so $0 orders totals show in the Scheduled Orders screen
 */
function autoship_include_scheduled_order_shipping_total_zero_total( $exclude ){
  return false;
}


/**
 * Gets scheduled order formatted totals - formatted for display with html labels.
 * @see autoship_get_formatted_price()
 *
 * @param array  $totals An array of total amounts.
 * @return array An array of formatted totals with labels
 */
function autoship_get_formatted_order_display_totals( $totals = array(), $currency = 'USD' ){

  $defaults = apply_filters( 'autoship_formatted_order_display_totals_default_values',
  array(
    'subtotal'       => array( 'label'=> __('Subtotal', 'autoship'),        'value' => 0 ),
    'shipping_total' => array( 'label'=> __('Shipping Total', 'autoship'),  'value' => 0 ),
    'tax_total'      => array( 'label'=> __('Tax', 'autoship'),             'value' => 0 ),
    'discount_total' => array( 'label'=> __('Discount', 'autoship'),        'value' => 0 ),
    'total'          => array( 'label'=> __('Total', 'autoship'),           'value' => 0 ),
  ));

  $new_totals = array();
  foreach ( $defaults as $key => $values) {

    // Check if the total exists and then apply the display zero amounts filter.
    if ( array_key_exists( $key, $totals ) && ( empty( $totals[$key] ) || !$totals[$key] ) && apply_filters("autoship_exclude_scheduled_order_{$key}_zero_total", true ) ){
      continue;
    }

    if ( isset( $totals[$key] ) ){
      $new_totals[$key]['value'] = $key == 'shipping_total' && $totals[$key] == 0 ? __('Free', 'autoship') : autoship_get_formatted_price( $totals[$key], array( 'currency' => $currency ) );
      $new_totals[$key]['label'] = $defaults[$key]['label'];
    }

  }

  return apply_filters( 'autoship_formatted_order_display_totals', $new_totals );

}

/**
 * Filters the Formatted Order Totals for Free Shipping.
 * @see autoship_get_formatted_order_display_totals()
 *
 * @param array An array of the current formatted totals with labels
 * @return array The new filtered array of formatted totals with labels
 */
function autoship_format_free_shipping_order_display_total( $totals ){

  if ( !array_key_exists( 'shipping_total', $totals ) && !empty( $totals ) ){

    $totals = autoship_array_insert_before( 'total', $totals, 'shipping_total', array('label'=>__('Shipping Total', 'autoship'), 'value'=>__('No Shipping Options Found', 'autoship') ) );

  } else if ( array_key_exists( 'shipping_total', $totals ) && !$totals['shipping_total']['value'] ){
    $totals['shipping_total']['value'] = 'Free';
  }

  return $totals;

}
add_filter( 'autoship_formatted_order_display_totals','autoship_format_free_shipping_order_display_total', 10, 1 );

/**
 * Display TBD when Tax is 0
 *
 * @param array  $totals An array of total amounts.
 * @return array An array of formatted totals with labels
 */
function autoship_add_tax_tbd_formatted_order_display_totals( $totals ){

  if ( !isset( $totals['tax_total'] ) && !empty( $totals ) )
  $totals = isset( $totals['discount_total'] ) ? autoship_array_insert_before('discount_total', $totals, 'tax_total',
  array( 'label'=> __('Tax', 'autoship'), 'value' => 'TBD' ) ) : autoship_array_insert_before('total', $totals, 'tax_total',
  array( 'label'=> __('Tax', 'autoship'), 'value' => 'TBD' ) );

  return $totals;

}
add_filter( 'autoship_formatted_order_display_totals', 'autoship_add_tax_tbd_formatted_order_display_totals', 11, 1 );

/**
 * Adjusts the Totals Labels if Needed based on Tax Display in Native UI.
 * @see autoship_get_formatted_order_display_totals()
 *
 * @param array An array of the current formatted totals with labels
 * @return array The new filtered array of formatted totals with labels
 */
function autoship_adjust_total_format_for_tax_display( $totals ){

  if ( isset( $totals['tax_total'] ) && !empty( $totals ) && autoship_enable_wc_tax_settings_for_prices_display() && ( 'incl' === get_option( 'woocommerce_tax_display_shop' ) ) )
  $totals['tax_total']['label'] = __('Addt\'l Tax & Fees', 'autoship');

  return $totals;

}
add_filter( 'autoship_formatted_order_display_totals', 'autoship_adjust_total_format_for_tax_display', 12, 1 );


/**
 * Outputs the currently assigned Shipping Rate & select option
 *
 * @param array $totals the current totals data
 * @param array $autoship_order The current autoship order
 * @param int   $customer_id The customer id.
 *
 * @return string The additional row
 */
function autoship_output_shipping_rate_total_row( $totals, $autoship_order, $customer_id ){

  $rates = autoship_get_available_scheduled_order_shipping_rates( $autoship_order );

  if ( !apply_filters( 'autoship_include_shipping_rate_in_total_summary', 'yes' == autoship_get_editable_shipping_rate_option(), $autoship_order, $customer_id ) || empty( $rates['available_rates'] ) )
  return;

  $rate = autoship_is_valid_shipping_rate( $rates['preferred_rate'], $autoship_order ) ?
  $rates['available_rates'][$rates['preferred_rate']] : $rates['available_rates'][$rates['default_rate']];

  ob_start();
  if ( apply_filters( 'autoship_disable_shipping_rate_selection_action', !isset( $autoship_order['lock_data'] ) || empty( $autoship_order['lock_data'] ) || $autoship_order['lock_data']['locked'], $rate, $autoship_order, $customer_id ) ){

    ?>

    <tr class="cart-shipping-rate">
			<th class="selected-shipping-rate" scope="row"><?php echo __('Shipping', 'autoship');?></th>
			<td><?php echo $rate['label']; ?></td>
		</tr>

    <?php

  } else if ( ( count( $rates['available_rates'] ) <= 1 ) && apply_filters( 'autoship_include_shipping_rate_refresh_option', true, $rates, $autoship_order, $customer_id ) ){

    $refresh_url = autoship_refresh_scheduled_order_url( $autoship_order['id'] );

    ?>

    <tr class="cart-shipping-rate">
			<th class="selected-shipping-rate" scope="row"><?php echo __('Shipping', 'autoship');?> <a href="<?php echo $refresh_url;?>" class="small-autoship-action dismissible-action" data-feature-id="autoship-refresh-shipping-rates-<?php echo $autoship_order['id']; ?>"><?php esc_html_e( 'Refresh Rates', 'autoship' ); ?></a></th>
			<td><?php echo $rate['label']; ?></td>
		</tr>

    <?php

  } else {

    ?>

    <tr class="cart-shipping-rate">
			<th class="selected-shipping-rate" scope="row"><?php echo __('Shipping', 'autoship');?> <a href="#" class="activate-action small-autoship-action autoship-modal-trigger" data-modal-toggle="#autoship_shipping_rate_option_modal"><?php esc_html_e( 'Edit', 'autoship' ); ?></a></th>
			<td><a href="#" class="shipping-rate-name autoship-modal-trigger" data-modal-toggle="#autoship_shipping_rate_option_modal"><?php echo $rate['label']; ?></a></td>
		</tr>

    <?php

  }

  echo ob_get_clean();

}

/**
 * Get account schedule orders actions.
 * Values can be modified through {@see autoship_get_account_scheduled_orders_actions}
 * filter
 * @param  int $order_number The Autoship Schedule Order Number.
 * @param  string $status Scheduled Order instance Status.
 * @param  string $context Context in which the actions are being displayed.
 *                         Default 'orders'.
 * @return array
 */
function autoship_get_account_scheduled_orders_actions( $order_number, $status, $context = 'orders', $customer_id = NULL ) {

  $schedule_label = autoship_translate_text( 'Scheduled Order' );

	$actions = array(
		'Paused'    => array(
			'url'  => autoship_get_scheduled_order_status_change_url( $order_number, 'Paused' ),
      'title'=> sprintf( __( "Pause %s", 'autoship' ), $schedule_label ),
			'name' => __( 'Pause', 'autoship' ),
		),
		'Active'   => array(
			'url'  => autoship_get_scheduled_order_status_change_url( $order_number, 'Active' ),
      'title'=> sprintf( __( "Resume %s", 'autoship' ), $schedule_label ),
			'name' => __( 'Resume', 'autoship' ),
		),
		'Failed'   => array(
			'url'  => autoship_get_scheduled_order_status_change_url( $order_number, 'Failed' ),
      'title'=> sprintf( __( "Fail %s", 'autoship' ), $schedule_label ),
			'name' => __( 'Mark as Failed', 'autoship' ),
		),
		'Pending'   => array(
			'url'  => autoship_get_scheduled_order_status_change_url( $order_number, 'Pending' ),
      'title'=> sprintf( __( "Mark %s as Pending", 'autoship' ), $schedule_label ),
			'name' => __( 'Mark as Pending', 'autoship' ),
		),
		'Deleted'   => array(
			'url'  => autoship_get_scheduled_order_delete_url( $order_number ),
      'title'=> sprintf( __( "Delete %s", 'autoship' ), $schedule_label ),
			'name' => __( 'Delete', 'autoship' ),
		),
		'Edit'   => array(
			'url'  => autoship_get_scheduled_order_url( $order_number ),
      'title'=> sprintf( __( "Edit / View %s", 'autoship' ), $schedule_label ),
			'name' => __( 'Edit', 'autoship' ),
		),
		'View'   => array(
			'url'  => autoship_get_view_scheduled_order_url( $order_number ),
      'title'=> sprintf( __( "View %s", 'autoship' ), $schedule_label ),
			'name' => __( 'View', 'autoship' ),
		),
		'Create'   => array(
			'url'  => autoship_get_scheduled_order_create_url( array( 'customer'=> $customer_id ) ),
      'title'=> sprintf( __( "Create %s", 'autoship' ), $schedule_label ),
			'name' => sprintf( __( "Create %s", 'autoship' ), $schedule_label )
		),
	);

  // Remove actions for non-admins.
  // By default only admins can see other users' accounts
  if ( !autoship_rights_checker( 'autoship_admin_account_scheduled_orders_actions' ) ) {
    unset( $actions['Failed'] );
    unset( $actions['Pending'] );
  }

  // Based on current status remove non-relevant actions
  if ( isset( $actions[$status] ) )
  unset( $actions[$status] );

  if ( 'Deleted' == $status ){

    $actions = array(
      'View' => $actions['View'],
    );

  } else if ( 'Failed' == $status ) {
    unset( $actions['Paused'] );
  } else if ( 'Pending' == $status ){
    unset( $actions['Edit'] );
  } else {
    unset( $actions['View'] );
  }

	return apply_filters( 'autoship_get_account_scheduled_orders_actions', $actions, $status, $context );
}

/**
 * Strips unsupported Actions
 *
 * @param array $actions The current actions
 * @param string $statuc The current order's status.
 * @param string $context Context in which the actions are being displayed.
 *
 * @return array The resulting actions.
 */
function autoship_strip_actions( $actions, $status, $context ){

  // Remove Unsupported Actions.
  unset( $actions['Failed'] );
  unset( $actions['Pending'] );

  // Remove Non-Singular View Actions.
  if ( 'order' == $context || 'empty-order' == $context ){
    unset( $actions['Edit'] );
    unset( $actions['View'] );
  } else {
    unset( $actions['Create'] );
  }

  return $actions;
}
add_filter( 'autoship_get_account_scheduled_orders_actions', 'autoship_strip_actions', 1, 3 );


/**
 * Adjusts actions displayed in the No Order Items scheduled orders
 *
 * @param array $actions The current actions
 * @param array $autoship_order The current selected autoship order.
 * @param int $customer_id The WC Customer ID
 * @param int $autoship_customer_id The QPilot Customer ID
 *
 * @return array The resulting actions.
 */
function autoship_adjust_no_item_actions( $actions, $autoship_order, $customer_id, $autoship_customer_id ){

  unset( $actions['Create'] );
  return $actions;

}
add_filter('autoship_no_order_items_form_actions', 'autoship_adjust_no_item_actions', 10, 4 );

/**
 * Get My Account > Scheduled Orders columns.
 * Can be modified via {@see autoship_get_my_account_scheduled_orders_columns}
 * @return array
 */
function autoship_get_my_account_scheduled_orders_columns() {

  $schedule_label = autoship_translate_text( 'Scheduled Order' );

  if ( 'Scheduled Order' == $schedule_label )
  $schedule_label = 'Order';

	$columns = apply_filters(
		'autoship_my_account_my_scheduled_orders_columns', array(
			'order-number'  => __( $schedule_label, 'autoship' ),
			'order-date'    => __( 'Next Date', 'autoship' ),
			'order-status'  => __( 'Status', 'autoship' ),
			'order-total'   => __( 'Total', 'autoship' ),
			'order-actions' => __( 'Actions', 'autoship' ),
		)
	);
	return $columns;
}

/**
 * Strips Order Columns
 *
 * @param array $columns The current column array
 * @return array The filtered columns
 */
function autoship_strip_scheduled_orders_column( $columns ){

  unset( $columns['order-number'] );

  return $columns;
}
add_filter( 'autoship_my_account_my_scheduled_orders_columns', 'autoship_strip_scheduled_orders_column', 1, 1 );

/**
 * Verifies the WC corresponding product exists and is the same ( uses id and SKU )
 * @param int $wc_product_id the woocommerce product id
 * @param string $sku        the woocommerce sku
 * @return WC_Product|null   The woocommerce product object or NULL if not found
 */
function autoship_get_original_wc_product ( $wc_product_id , $sku ){

  $og_product = wc_get_product( $wc_product_id );

  if ( $og_product ){
  $wc_sku = $og_product->get_sku();
  $sku_true = ( empty( $sku ) && empty( $wc_sku ) ) || ( $wc_sku == $sku ); }

  $og = ( false != $og_product ) && ( null != $og_product ) && ( $sku_true ) ? $og_product : null;

  return apply_filters( 'autoship_get_original_wc_product_validation', $og , $wc_product_id , $sku , $og_product );

}

/**
 * retrieves the product name and variation from the autoship so name.
 * The Variation label and value are stored as a string in the last part of the product name.
 *
 * @param string $name       The name to split.
 * @return array             An array containing the name and the variation info.
 */
function autoship_get_scheduled_order_variation_item_name_parts ( $name ){

  $split = explode('(', $name );
  $size = count( $split );

  $variation = $new_name = '';
  if ( $size > 1 ){

    $variation = rtrim( $split[$size -1] , ')');
    unset( $split[$size -1] );

    $new_name = implode( ')', $split );

  } else {
    $new_name = $name;
  }

  return apply_filters( 'autoship_get_scheduled_order_variation_item_name_parts', array( 'name' => $new_name, 'meta' => $variation ), $name );

}

/**
 * Retrieves the available frequencies Scheduled Orders can be changed to.
 *
 * @param stdClass  $autoship_order  The Autoship Scheduled Order Class object
 * @return array                      An array of frequencies
 */
function autoship_get_all_valid_order_change_frequencies( $autoship_order ){
  $frequencies = apply_filters( 'autoship_get_all_valid_order_change_frequencies', autoship_default_frequency_options(), $autoship_order );
  return $frequencies;
}

/**
 * Retrieves the available frequencies Scheduled Orders can be created with.
 *
 * @param int  $customer_id The WC Customer ID
 * @param array $order_data The new order data.
 * @return array            An array of frequencies
 */
function autoship_get_all_valid_order_create_frequencies( $customer_id, $order_data = array() ){
  $frequencies = apply_filters( 'autoship_get_all_valid_order_create_frequencies', autoship_default_frequency_options(), $customer_id, $order_data );
  return $frequencies;
}

/**
 * Retrieves the available shipping rates for a Scheduled Order
 *
 * @param array  $autoship_order  The Autoship Scheduled Order data
 * @return array An array of rates
 */
function autoship_get_available_scheduled_order_shipping_rates( $autoship_order ){

  // Pull out the available rates
  $rates = $default = array();
  foreach ($autoship_order['shippingRateOptions'] as $key => $rate ) {

    $total = 0; $package = array();
    foreach ( $rate['shippingLines'] as $shipping_line ) {
      $package[ $shipping_line['name'] ] = $shipping_line['sourceShippingRate'];
      $total += $shipping_line['total'];
    }

    $total_html = autoship_get_formatted_price( $total, array( 'currency' => $autoship_order['currencyIso'] ) );

    $rates[$rate['optionValue']] = array(
      'default'    => $rate['isDefault'],
      'value'      => $rate['optionValue'],
      'total'      => $total,
      'total_html' => $total_html,
      'label'      => $rate['optionValue'],
      'label_html' => sprintf( $rate['optionValue'] . ' ( %s )', $total_html ),
      'details'    => $package,
    );

    if ( $rate['isDefault'] )
    $default = $rate['optionValue'];

  }

  return apply_filters( 'autoship_get_available_scheduled_order_shipping_rates',
  array( 'available_rates' => $rates, 'default_rate' => $default, 'preferred_rate' => $autoship_order['preferredShippingRateOption'] ), $autoship_order );

}

/**
 * Retrieves the current default shipping rate for a scheduled order
 *
 * @param array  $autoship_order  The Autoship Scheduled Order data
 * @return false|array The default rate else false if none exist
 */
function autoship_get_available_scheduled_order_default_shipping_rate( $autoship_order ){

  $all_rates = autoship_get_available_scheduled_order_shipping_rates( $autoship_order );
  return !empty( $all_rates['default_rate'] ) ? $all_rates['available_rates'][$all_rates['default_rate']] : false;

}

/**
 * Retrieves the current preferred shipping rate for a scheduled order
 *
 * @param stdClass|array  $autoship_order  The Autoship Scheduled Order data
 * @return string The shipping rate name
 */
function autoship_get_scheduled_order_preferred_shipping_rate( $autoship_order ){
  return $autoship_order['preferredShippingRateOption'];
}

/**
 * Confirms that the supplied Shipping rate is still valid for the Scheduled Order
 *
 * @param string $shipping_rate The shipping rate to check
 * @param array  $autoship_order  The Autoship Scheduled Order data
 * @return bool True if valid else false
 */
function autoship_is_valid_shipping_rate( $shipping_rate, $autoship_order ){

  $all_rates = autoship_get_available_scheduled_order_shipping_rates( $autoship_order );
  return !empty( $all_rates ) && isset( $all_rates['available_rates'] ) && isset( $all_rates['available_rates'][$shipping_rate] );

}

/**
 * Output the quantity input for Autoship Scheduled Order Edit form.
 * Mimics the WC {@see woocommerce_quantity_input} function but deals with autoship specific.
 *
 * @param  array           $args Args for the input.
 * @param  stdClass        $product Autoship Product Object.
 * @param  WC_Product|null $wc_product Product.
 * @param  boolean         $echo Whether to return or echo|string.
 *
 * @return string
 */
function autoship_scheduled_order_quantity_input( $args, $product, $wc_product, $echo = true ){

  if ( null !== $wc_product ){

    $defaults = array(
        'input_id'    => uniqid( 'quantity_' ),
        'input_name'  => 'quantity',
        'input_value' => '1',
        'max_value'   => apply_filters( 'woocommerce_quantity_input_max', -1, $wc_product ),
        'min_value'   => apply_filters( 'woocommerce_quantity_input_min', 0, $wc_product ),
        'step'        => apply_filters( 'woocommerce_quantity_input_step', 1, $wc_product ),
        'pattern'     => apply_filters( 'woocommerce_quantity_input_pattern', has_filter( 'woocommerce_stock_amount', 'intval' ) ? '[0-9]*' : '' ),
        'inputmode'   => apply_filters( 'woocommerce_quantity_input_inputmode', has_filter( 'woocommerce_stock_amount', 'intval' ) ? 'numeric' : '' ),
    );

    $args = apply_filters( 'woocommerce_quantity_input_args', wp_parse_args( $args, $defaults ), $wc_product );

  } else {

    $defaults = array(
        'input_id'    => uniqid( 'quantity_' ),
        'input_name'  => 'quantity',
        'input_value' => '1',
        'max_value'   => apply_filters( 'autopship_scheduled_order_quantity_input_max', -1, $product ),
        'min_value'   => apply_filters( 'autopship_scheduled_order_quantity_input_min', 0, $product ),
        'step'        => apply_filters( 'autopship_scheduled_order_quantity_input_step', 1, $product ),
        'pattern'     => apply_filters( 'autopship_scheduled_order_quantity_input_pattern', '[0-9]*' ),
        'inputmode'   => apply_filters( 'autopship_scheduled_order_quantity_input_inputmode', 'intval' ),
    );

    $args = apply_filters( 'autoship_scheduled_order_quantity_input_args', wp_parse_args( $args, $defaults ), $product );

  }

  // Apply sanity to min/max args - min cannot be lower than 0.
  $args['min_value'] = max( $args['min_value'], 0 );
  $args['max_value'] = 0 < $args['max_value'] ? $args['max_value'] : '';

  // Max cannot be lower than min if defined.
  if ( '' !== $args['max_value'] && $args['max_value'] < $args['min_value'] ) {
      $args['max_value'] = $args['min_value'];
  }

  ob_start();

  if ( $args['max_value'] && $args['min_value'] === $args['max_value'] ) {
  	?>
  	<div class="quantity hidden">
  		<input type="hidden" id="<?php echo esc_attr( $args['input_id'] ); ?>" class="qty" name="<?php echo esc_attr( $args['input_name'] ); ?>" value="<?php echo esc_attr( $args['min_value'] ); ?>" />
  	</div>
  	<?php
  } else {
  	/* translators: %s: Quantity. */
  	$labelledby = ! empty( $args['product_name'] ) ? sprintf( __( '%s quantity', 'autoship' ), strip_tags( $args['product_name'] ) ) : '';
  	?>
  	<div class="quantity">
  		<label class="screen-reader-text" for="<?php echo esc_attr( $args['input_id'] ); ?>"><?php esc_html_e( 'Quantity', 'autoship' ); ?></label>
  		<input
  			type="number"
  			id="<?php echo esc_attr( $args['input_id'] ); ?>"
  			class="input-text qty text"
  			step="<?php echo esc_attr( $args['step'] ); ?>"
  			min="<?php echo esc_attr( $args['min_value'] ); ?>"
  			max="<?php echo esc_attr( 0 < $args['max_value'] ? $args['max_value'] : '' ); ?>"
  			name="<?php echo esc_attr( $args['input_name'] ); ?>"
  			value="<?php echo esc_attr( $args['input_value'] ); ?>"
  			title="<?php echo esc_attr_x( 'Qty', 'Product quantity input tooltip', 'autoship' ); ?>"
  			size="4"
  			pattern="<?php echo esc_attr( $args['pattern'] ); ?>"
  			inputmode="<?php echo esc_attr( $args['inputmode'] ); ?>"
  			aria-labelledby="<?php echo esc_attr( $labelledby ); ?>" />
  	</div>
  	<?php
  }

  $input_display = apply_filters( 'autoship_scheduled_order_quantity_input_display', ob_get_clean() , $args, $product, $wc_product, $echo );

  if ( $echo ) {
    echo $input_display;
  } else {
    return $input_display;
  }

}

/**
 * Retrieves or Calculates the supplied Scheduled Orders totals.
 * @uses wc_get_price_to_display()
 *
 * @param float $price The current price to adjust
 * @param WC_Product|int The current wc product object or id associated with the Price.
 * @return float The filtered Price.
 */
function autoship_get_adjusted_product_item_price( $price, $product ){

  if ( is_numeric( $product ) )
  $product = wc_get_product( $product );

  if ( !isset( $product ) || !$product || !autoship_enable_wc_tax_settings_for_prices_display() )
  return $price;

  return wc_get_price_to_display( $product, array( 'price' => $price ) );

}

/**
 * Retrieves or Calculates the supplied Scheduled Order Objects totals.
 * @param stdClass $scheduled_order The Scheduled Order object
 * @param array $scheduled_items Optional. An Array of Scheduled Order Item objects.
 * @param array $filter_ids Optional. An Array of Scheduled Order Item Ids to filter for.
 * @return array The totals.
 */
function autoship_get_calculated_scheduled_order_object_totals( $scheduled_order, $scheduled_items = array(), $filter_ids = array() ){

  // Convert the object
  $scheduled_order = autoship_convert_object_to_array( $scheduled_order );

  // Convert the items
  $scheduled_items = empty( $scheduled_items ) ?
  $scheduled_order['scheduledOrderItems'] : autoship_convert_object_to_array( $scheduled_items );

  // Gather the processed data
  $scheduled_items = autoship_get_scheduled_order_form_items_data( $scheduled_order['id'], $scheduled_items, '', $filter_ids = array() );

  return autoship_get_calculated_scheduled_order_totals( $scheduled_order, $scheduled_items );

}

/**
 * Retrieves or Calculates the supplied Scheduled Order items sale price including coupon discounts.
 *
 * @param array $scheduled_item An Array of Scheduled Order Item data.
 * @param array $scheduled_order The Scheduled Order data
 * @return float the price
 */
function autoship_get_calculated_scheduled_order_item_sale_price( $scheduled_item, $scheduled_order ){

  // Get any Discounts that need to be re-applied
  $discounts = autoship_get_valid_coupons_from_order( $scheduled_order );

  // Get the price to use
  $price = !empty( $scheduled_item['salePrice'] ) ? $scheduled_item['salePrice'] : $scheduled_item['price'];

  if ( apply_filters( 'autoship_exclude_product_coupon_discounts_in_saleprice', false ) )
  return $price;

  /**
  *  Apply the discounts
  */
  foreach ( $discounts as $code => $coupon ) {

    // Confirm the product for this coupon is still on the order
    if ( $coupon['productDiscountNotAppliesWhenItHasSalePrice'] && !empty( $scheduled_item['salePrice'] ) )
    continue;

    if (
      'ReduceProductPriceByPercentage' == $coupon['discountType'] &&
      !empty( $coupon['productGroupId'] ) &&
      !empty( $scheduled_item['product']['productGroupIds'] ) &&
      in_array( $coupon['productGroupId'], $scheduled_item['product']['productGroupIds'] )
    ){

      // Calculate the new sale price
      $price = $price - ( $price * ( $coupon['amount'] / 100 ) );

    } else if (
      'ReduceProductPriceByPercentage' == $coupon['discountType'] &&
      isset( $coupon['product'] ) &&
      ( $coupon['product']['id'] == $scheduled_item['productId'] )
    ){

      // Calculate the new sale price
      $price = $price - ( $price * ( $coupon['amount'] / 100 ) );

    } else if (
      'ReduceProductPriceByAmount' == $coupon['discountType'] &&
      !empty( $coupon['productGroupId'] ) &&
      !empty( $scheduled_item['product']['productGroupIds'] ) &&
      in_array( $coupon['productGroupId'], $scheduled_item['product']['productGroupIds'] )
    ){

      // Calculate the new sale price
      $discount_price = $price - $coupon['amount'];
      $price = $discount_price >= 0 ? $discount_price : 0;

    } else if (
      'ReduceProductPriceByAmount' == $coupon['discountType'] &&
      isset( $coupon['product'] ) &&
      ( $coupon['product']['id'] == $scheduled_item['productId'] )
    ){

      // Calculate the new sale price
      $discount_price = $price - $coupon['amount'];
      $price = $discount_price >= 0 ? $discount_price : 0;

    } else if ('ReduceSubtotalByPercentage' == $coupon['discountType'] && $coupon['subtotalDiscountAppliesToRegularPrice'] ) {
      $price = $scheduled_item['price'];
    }

    // Check if the price is reduced to Free & stop discounts
    if ( !$price )
    return 0;

  }

  return $price;

}

/**
 * Retrieves or Calculates the supplied Scheduled Orders totals.
 * @param array $scheduled_order The Scheduled Order data
 * @param array $scheduled_items Optional. An Array of Scheduled Order Items.
 * @return array The totals.
 */
function autoship_get_calculated_scheduled_order_totals( $scheduled_order, $scheduled_items = array() ){

  $totals = array(
      'subtotal'       => $scheduled_order['subtotal'],
      'shipping_total' => $scheduled_order['shippingRateOptions'] ? $scheduled_order['shippingTotal'] : null,
      'tax_total'      => $scheduled_order['taxTotal'],
      'discount_total' => 0,
      'total'          => $scheduled_order['total'],
  );

  if ( autoship_enable_wc_tax_settings_for_prices_display() ){

    // Get any Discounts that need to be re-applied
    $discounts = autoship_get_valid_coupons_from_order( $scheduled_order );

    if ( empty( $scheduled_items ) )
    $scheduled_items = autoship_get_scheduled_order_form_items_data( $scheduled_order['id'], $scheduled_order['scheduledOrderItems'] );

    // Calculate the new Subtotal using the adjusted price(s)
    $totals['subtotal'] = 0;
    $totals['discount'] = 0;
    $product_discounts  = $group_ids = array();
    foreach ( $scheduled_items as $item ) {

      $totals['subtotal'] += $item['sale_subtotal'];
      $sale_price = $item['price'] === $item['sale_price'] ? '' : $item['sale_price'];

      $product_discounts[ $item['product_id'] ] = array(
        'qty'           => $item['qty'],
        'price'         => $item['price'],
        'sale_price'    => $sale_price,
        'subtotal'      => $item['sale_subtotal']
      );

      foreach ( $item['product']['productGroupIds'] as $groud_id ) {

        isset( $group_ids[$groud_id] ) ?
        $group_ids[$groud_id][] = $item['product_id'] : $group_ids[$groud_id] = array( $item['product_id'] );

      }

    }

    /**
    *  Re-apply the discounts
    */
    foreach ( $discounts as $code => $coupon ) {

      if ( 'ReduceSubtotalByPercentage' == $coupon['discountType'] && $coupon['subtotalDiscountAppliesToRegularPrice'] ){

        // We are using SO subtotal that already has discounted price 
        $totals['subtotal'] = $scheduled_order['subtotal'];

      } else if ( 'ReduceSubtotalByPercentage' == $coupon['discountType'] ) {

        // Add the new discount amount to the total discounts
        $totals['discount'] += $totals['subtotal'] * ( $coupon['amount'] / 100 );

      } else if ( 'ReduceSubtotalByAmount' == $coupon['discountType'] ){

        // Add the new discount amount to the total discounts
        $totals['discount'] += $coupon['amount'];

      } else if ( 'ReduceProductPriceByPercentage' == $coupon['discountType'] && !empty( $coupon['productGroupId'] ) && isset( $group_ids[ $coupon['productGroupId'] ] ) ){

        foreach ( $group_ids[ $coupon['productGroupId'] ] as $product_id ) {

          // Confirm the product for this coupon is still on the order
          if ( !isset( $product_discounts[ $product_id ] ) || ( $coupon['productDiscountNotAppliesWhenItHasSalePrice'] && !empty( $product_discounts[ $product_id ]['sale_price'] ) ) )
          continue;

          // Get the Price to use in the calc.
          $price = empty( $product_discounts[ $product_id ]['sale_price'] ) ? $product_discounts[ $product_id ]['price'] : $product_discounts[ $product_id ]['sale_price'];

          // Add the new discount amount to the total discounts
          $totals['discount'] += $price * $product_discounts[ $product_id ]['qty'] * ( $coupon['amount'] / 100 );

        }

      } else if ( 'ReduceProductPriceByPercentage' == $coupon['discountType'] && isset( $coupon['product'] ) ){
        // Get the Product ID
        $product_id = $coupon['product']['id'];
        
        // Confirm the product for this coupon is still on the order
        if ( !isset( $product_discounts[ $product_id ] ) || ( $coupon['productDiscountNotAppliesWhenItHasSalePrice'] && !empty( $product_discounts[ $product_id ]['sale_price'] ) ) )
        continue;

        // Get the Price to use in the calc.
        $price = empty( $product_discounts[ $product_id ]['sale_price'] ) ? $product_discounts[ $product_id ]['price'] : $product_discounts[ $product_id ]['sale_price'];

        // Add the new discount amount to the total discounts
        $totals['discount'] += $price * $product_discounts[ $product_id ]['qty'] * ( $coupon['amount'] / 100 );


      } else if ( 'ReduceProductPriceByAmount' == $coupon['discountType'] && !empty( $coupon['productGroupId'] ) && isset( $group_ids[ $coupon['productGroupId'] ] ) ){

        foreach ( $group_ids[ $coupon['productGroupId'] ] as $product_id ) {

          // Confirm the product for this coupon is still on the order
          if ( !isset( $product_discounts[ $product_id ] ) || ( $coupon['productDiscountNotAppliesWhenItHasSalePrice'] && !empty( $product_discounts[ $product_id ]['sale_price'] ) ) )
          continue;

          // Get the Price to use in the calc.
          $price = empty( $product_discounts[ $product_id ]['sale_price'] ) ? $product_discounts[ $product_id ]['price'] : $product_discounts[ $product_id ]['sale_price'];

          // Add the new discount amount to the total discounts
          $discount_price = $price - $coupon['amount'];
          $discount_amount = $discount_price > 0 ? $coupon['amount'] * $product_discounts[ $product_id ]['qty'] : $price * $product_discounts[ $product_id ]['qty'];
          $totals['discount'] += $discount_amount;

        }

      } else if ( 'ReduceProductPriceByAmount' == $coupon['discountType'] && isset( $coupon['product'] ) ){

        // Get the Product ID
        $product_id = $coupon['product']['id'];

        // Confirm the product for this coupon is still on the order
        if ( !isset( $product_discounts[ $product_id ] ) || ( $coupon['productDiscountNotAppliesWhenItHasSalePrice'] && !empty( $product_discounts[ $product_id ]['sale_price'] ) ) )
        continue;

        // Get the Price to use in the calc.
        $price = empty( $product_discounts[ $product_id ]['sale_price'] ) ? $product_discounts[ $product_id ]['price'] : $product_discounts[ $product_id ]['sale_price'];

        // Add the new discount amount to the total discounts
        $discount_price = $price - $coupon['amount'];
        $discount_amount = $discount_price > 0 ? $coupon['amount'] * $product_discounts[ $product_id ]['qty'] : $price * $product_discounts[ $product_id ]['qty'];
        $totals['discount'] += $discount_amount;

      }

    }

    // Calculate the New Subtotal and Total
    $totals['subtotal'] -= $totals['discount'];

    if($totals['shipping_total']) {
      $totals['total']   = $totals['shipping_total'] + $totals['subtotal'];
      $totals['total']   += $totals['tax_total'];
    }

  }

  return apply_filters('autoship_get_calculated_scheduled_order_totals', $totals, $scheduled_order, $scheduled_items );

}

/**
 * Generates the Scheduled Item Data for a group of items for Form Displays.
 * @param int $order_id The Scheduled Order ID.
 * @param array $scheduled_items An Array of Scheduled Order Items arrays or objects.
 * @param string $action The action being fired.
 * @param array $filter_ids Optional. An Array of Scheduled Order Item Ids to filter for.
 *
 * @return array The generated form data.
 */
function autoship_get_scheduled_order_form_items_data( $order_id, $scheduled_items, $action = 'form_item', $filter_ids = array() ){

  $items = array();
  foreach ( $scheduled_items as $scheduled_item_key => $scheduled_item ){

    if ( !empty( $filter_ids ) && !in_array( $scheduled_item['id'], $filter_ids ) )
    continue;

    $scheduled_item['scheduled_item_key'] = $scheduled_item_key;
    $item = autoship_get_scheduled_order_form_item_data( $order_id, $scheduled_item, $action );

    if ( !empty( $item ) )
    $items[$item['id']] = $item;

  }

  return apply_filters( "autoship_scheduled_order_{$action}s", $items, $scheduled_items );

}

/**
 * Generates the Scheduled Item Data for Form Displays.
 * @param int $order_id The Scheduled Order ID.
 * @param array $scheduled_item An Array of Scheduled Order Item data.
 * @param string $action The action being fired.
 * @return array The generated form data.
 */
function autoship_get_scheduled_order_form_item_data( $order_id, $scheduled_item, $action = 'form_item' ){

  // Gather the data clean & neat.
  // All values are Autoship specific unless with wc_ prefix.
  $item = array();
  $item['item_key']       = isset( $scheduled_item['scheduled_item_key'] ) ? $scheduled_item['scheduled_item_key'] : NULL;
  $item['id']             = $scheduled_item['id'];
  $item['order_id']       = $order_id;
  $item['wc_product_id']  = $scheduled_item['product']['id'];
  $item['cycles']         = $scheduled_item['cycles'];
  $item['min_cycles']     = $scheduled_item['minCycles'];
  $item['max_cycles']     = $scheduled_item['maxCycles'];

  $item['key']            = 'item-' . $item['id'];

  $item['sku']            = $scheduled_item['product']['sku'];

  $item['wc_product']     = autoship_get_original_wc_product ( $item['wc_product_id'] , $item['sku'] );

  $item['imageurl']       = isset( $scheduled_item['product']['metadata']['imageUrl'] ) ?
  $scheduled_item['product']['metadata']['imageUrl'] : '';

  $item['imagethumburl']  = isset( $scheduled_item['product']['metadata']['imageThumbUrl'] ) ? $scheduled_item['product']['metadata']['imageThumbUrl'] : '';

  // Retrieve the data from the Original WC Product if it exists.
  if ( null != $item['wc_product'] ){
    $item['wc_visible']           = $item['wc_product']->is_visible();
    $item['wc_permallink']        = $item['wc_product']->get_permalink();
    $item['thumbnail']            = $item['wc_product']->get_image();
    $item['is_sold_individually'] = $item['wc_product']->is_sold_individually();
    $item['max_input']            = $item['wc_product']->get_max_purchase_quantity();
  } else {
    $item['thumbnail']            = autoship_get_product_thumbnail_html ( $item['imagethumburl'] );
    $item['wc_permallink']        = '';
    $item['wc_visible']           = false;
    $item['is_sold_individually'] = false;
    $item['max_input']            = '1';
  }


  $item['min_input']      = apply_filters( "autoship_scheduled_order_{$action}_min_qty_required", '0', $item, $scheduled_item );
  $item['max_input']      = apply_filters( "autoship_scheduled_order_{$action}_max_qty_allowed", $item['max_input'], $item, $scheduled_item );

  $item['thumbnail']      = apply_filters( "autoship_scheduled_order_{$action}_thumbnail", $item['thumbnail'], $item, $scheduled_item );
  $item['wc_permallink']  = apply_filters( "autoship_scheduled_order_{$action}_wc_permalink", $item['wc_permallink'], $item, $scheduled_item );

  $item['is_sold_individually']  = apply_filters( "autoship_scheduled_order_{$action}_sold_individually", $item['is_sold_individually'], $item, $scheduled_item );

  $item['product_id']     = $scheduled_item['productId'];
  $item['product']        = $scheduled_item['product'];

  $name_components = autoship_get_scheduled_order_variation_item_name_parts(  $item['product']['title'] );

  $item['name'] = apply_filters( "autoship_scheduled_order_{$action}_name", $name_components['name'], $item, $scheduled_item );
  $item['meta'] = apply_filters( "autoship_scheduled_order_{$action}_meta", $name_components['meta'], $item, $scheduled_item );

  $item['qty']            = $scheduled_item['quantity'];
  $item['price']          = autoship_get_adjusted_product_item_price( $scheduled_item['price'], $item['wc_product'] );
  $item['sale_price']     = autoship_get_adjusted_product_item_price( ! empty( $scheduled_item['salePrice'] ) ? $scheduled_item['salePrice'] : $scheduled_item['price'], $item['wc_product'] );

  $item['subtotal']       = $item['qty'] * $item['price'];
  $item['sale_subtotal']  = $item['qty'] * $item['sale_price'];

  $item['remove_url']     = autoship_get_scheduled_order_item_remove_url( $item['id'] , $item['order_id'] );

  $item['key']            = apply_filters ( "autoship_scheduled_order_{$action}_key", $item['key'], $item, $scheduled_item );
  $item['visible']        = apply_filters ( "autoship_scheduled_order_{$action}_visible", true, $item, $scheduled_item );

  // Check Stock Status and Processing Status
  $item['availability']   = $item['product']['processScheduledOrder'] !== false ? 'available-to-process' : 'unavailable-to-process';
  $item['stock_status']   = ( 'InStock' == $item['product']['availability'] ) && ( 'available-to-process' == $item['availability'] ) ? 'instock' : 'outofstock';

  // Check for Cycle validity
  $item['cycle_item']      =  isset( $item['min_cycles'] ) || isset( $item['max_cycles'] );
  $item['valid_for_cycle'] = $item['cycle_item'] ? autoship_valid_cycle_for_item( $item['cycles'], $item['min_cycles'], $item['max_cycles'] ) : true;
  if ( apply_filters( "autoship_scheduled_order_{$action}_exclude_out_of_cycle", true, $order_id ) && !$item['valid_for_cycle'] ){
    $autoship_order['future_products'][$item['id']] = $item;
    $item = array();
  }

  return apply_filters ( "autoship_scheduled_order_{$action}", $item, $scheduled_item );

}

// ==========================================================
// URL FUNCTIONS
// ==========================================================

/**
 * Returns the Create Scheduled Order url.
 * @param array $args {
 *    Optional. The parameters to generate the url.
 *
 *    @type int $freq              The frequency.
 *    @type string $freqtype       The frequency Type.
 *    @type string $nextoccurrence The Next Occurrence Date string.
 *    @type int $customer          The WC Customer id.
 *    @type int|array $mincycle    The min cycle for the product(s). Single value or array of product id => cycle value
 *    @type int|array $maxcycle    The max cycle for the product(s). Single value or array of product id => cycle value
 *    @type int|array $products    The product(s) & qty to add to the order. Single value or array of product id => qty values
 *
 * }
 *
 * @return string Output of the url.
 */
function autoship_get_scheduled_order_create_url( $args = array() ){

  $defaults = array(
    'freq'           => NULL,
    'freqtype'       => NULL,
    'customer'       => NULL,
    'nextoccurrence' => NULL,
    'mincycle'       => NULL,
    'maxcycle'       => NULL,
    'products'       => NULL
  );

  $args = wp_parse_args( $args, $defaults );

  // Sanitize and Strip any non-populated args.
  $params = array();

  // Attach the frequency
  if ( isset( $args['freq'] ) )
  $params['freq'] = absint( $args['freq'] );

  // Attach the frequency type
  if ( isset( $args['freqtype'] ) )
  $params['freqtype'] = urlencode( sanitize_text_field( $args['freqtype'] ) );

  // Attach the next occurrence
  if ( isset( $args['nextoccurrence'] ) )
  $params['nextoccurrence'] = urlencode( sanitize_text_field( $args['nextoccurrence'] ) );

  // Now attach any products
  if ( isset( $args['products']) && is_array( $args['products'] ) ){
    foreach ( $args['products'] as $product => $qty )
    $params['items[' . absint( $product ) . ']'] = absint( $qty );
  } else if ( isset( $args['products']) ){
    $params['items'] = absint( $args['products'] );
  }

  // Min/Max Cycles only matter if a product(s) are supplied
  if ( isset( $args['products']) ){

    // Now attach any min cycles
    if ( isset( $args['mincycle']) && is_array( $args['mincycle'] ) ){
      foreach ( $args['mincycle'] as $product => $min )
      $params['min[' . absint( $product ) . ']'] = absint( $min );
    } else if ( isset( $args['mincycle']) ){
      $params['min'] = absint( $args['mincycle'] );
    }

    // Now attach any min cycles
    if ( isset( $args['maxcycle']) && is_array( $args['maxcycle'] ) ){
      foreach ( $args['maxcycle'] as $product => $max )
      $params['max[' . absint( $product ) . ']'] = absint( $max );
    } else if ( isset( $args['maxcycle']) ){
      $params['max'] = absint( $args['maxcycle'] );
    }

  }

  $params['action'] = 'create-scheduled-order';

  // If a customer id is supplied we hash it so it locks the url
  // To this customer specifically.
  if ( isset( $args['customer'] ) )
  $params['customer'] = absint( $args['customer'] );

  // Allow developers to hook in and add / sanitize their own values
  $params = apply_filters( 'autoship_get_create_scheduled_order_url_params' , $params, $args );

  // Grab the needed endpoint url as the base for the query
  $base = autoship_get_endpoint_url ( 'scheduled-orders', '', wc_get_page_permalink( 'myaccount' ) );

  return !empty( $params ) ? add_query_arg( $params, $base ) : $base;

}

/**
 * Gets the url to delete an order.
 *
 * @param int     $order_id The autoship order id.
 * @param string  $confirm  If a confirm action notice should show.
 * @return string url for the action.
 */
function autoship_get_scheduled_order_delete_url( $order_id, $confirm = 'confirm' ) {

    $keys = array(
      'delete-scheduled-order-confirm' => $confirm,
      'scheduled-order'  => $order_id,
    );

    $autoship_page_url = autoship_get_scheduled_orders_url();
    return apply_filters( 'autoship_scheduled_order_delete_url', $autoship_page_url ?
    wp_nonce_url( add_query_arg( $keys , $autoship_page_url ), 'autoship-delete-scheduled-order', 'autoship-delete-scheduled-order-nonce' ) : '' );
}

/**
 * Gets the url to change the status of an order.
 *
 * @param int     $order_id The autoship order id.
 * @param string  $status   The status to change it to.
 * @return string url for the action.
 */
function autoship_get_scheduled_order_status_change_url( $order_id, $status ) {

    $keys = array(
      'update-scheduled-order-status' => $status,
      'scheduled-order'  => $order_id,
    );

    $autoship_page_url = autoship_get_scheduled_orders_url();
    return apply_filters( 'autoship_scheduled_order_status_change_url', $autoship_page_url ?
    wp_nonce_url( add_query_arg( $keys , $autoship_page_url ), 'autoship-update-scheduled-order', 'autoship-update-scheduled-order-nonce' ) : '' );
}

/**
 * Returns the Remove Scheduled Order Item url.
 *
 * @param int $autoship_order_item_id The Autoship order item id.
 * @param int $autoship_order_id The Autoship order id..
 *
 * @return string Output of the url.
 */
function autoship_get_scheduled_order_item_remove_url( $autoship_order_item_id, $autoship_order_id ){

  $keys = array(
    'remove-scheduled-order-item' => $autoship_order_item_id,
    'scheduled-order'  => $autoship_order_id,
  );

  $autoship_page_url = autoship_get_scheduled_order_url( $autoship_order_id );
  return apply_filters( 'autoship_scheduled_order_item_remove_url', $autoship_page_url ?
  wp_nonce_url( add_query_arg( $keys , $autoship_page_url ), 'autoship-remove-scheduled-order-item', 'autoship-remove-scheduled-order-item-nonce' ) : '' );

}

/**
 * Returns the Add Scheduled Order Item url.
 * @param array $args {
 *    The parameters to generate the url.
 *
 *    @type int $item              The WC Product id.
 *    @type int $qty               The quantity.
 *    @type int $min               The Min number of cycles required before adding it.
 *    @type int $max               The Max number of Cycles it should be included for.
 *    @type int $freq              The frequency.
 *    @type string $freqtype       The frequency Type.
 *    @type int $order             The Autoship order id.
 *    @type int $customer          The WC Customer id.
 *    @type string $coupon         The Qpilot Coupon Code
 *
 * }
 *
 * @return string Output of the url.
 */
function autoship_get_scheduled_order_item_add_url( $args ){

  $defaults = array(
    'item'       => NULL,
    'qty'        => NULL,
    'min'        => NULL,
    'max'        => NULL,
    'freq'       => NULL,
    'freqtype'   => NULL,
    'order'      => NULL,
    'customer'   => NULL,
    'coupon'     => NULL,
  );
  $args = wp_parse_args( $args, $defaults );

  // Sanitize and Strip any non-populated args.
  $params = array();
  if ( isset( $args['item'] ) )
  $params['item'] = absint( $args['item'] );

  if ( isset( $args['qty'] ) )
  $params['qty'] = absint( $args['qty'] );

  if ( isset( $args['min'] ) )
  $params['min'] = absint( $args['min'] );

  if ( isset( $args['max'] ) )
  $params['max'] = absint( $args['max'] );

  if ( isset( $args['order'] ) )
  $params['order'] = absint( $args['order'] );

  if ( isset( $args['freq'] ) )
  $params['freq'] = absint( $args['freq'] );

  if ( isset( $args['freqtype'] ) )
  $params['freqtype'] = urlencode( sanitize_text_field( $args['freqtype'] ) );

  if ( isset( $args['coupon'] ) )
  $params['coupon'] = urlencode( sanitize_text_field( $args['coupon'] ) );

  $params['action'] = 'add-to-scheduled-order';

  // If a customer id is supplied we hash it so it locks the url
  // To this customer specifically.
  if ( isset( $args['customer'] ) )
  $params['customer'] = wp_hash( absint( $args['customer'] ) );

  // Allow developers to hook in and add / sanitize their own values
  $params = apply_filters( 'autoship_get_add_to_scheduled_order_url_params' , $params, $args );

  // Grab the needed endpoint url as the base for the query
  $base = autoship_get_endpoint_url ( 'scheduled-orders', '', wc_get_page_permalink( 'myaccount' ) );

  return !empty( $params ) ? add_query_arg( $params, $base ) : $base;

}

/**
 * Returns the Remove Scheduled Order Item url.
 *
 * @param int $autoship_order_item_id The Autoship order item id.
 * @param int $autoship_order_id The Autoship order id..
 *
 * @return string Output of the url.
 */
function autoship_get_scheduled_order_coupon_remove_url( $autoship_order_coupon_code, $autoship_order_id ){

  $keys = array(
    'remove-scheduled-order-coupon' => urlencode( $autoship_order_coupon_code ),
    'scheduled-order'  => $autoship_order_id,
  );

  $autoship_page_url = autoship_get_scheduled_order_url( $autoship_order_id );
  return apply_filters( 'autoship_scheduled_order_coupon_remove_url', $autoship_page_url ?
  wp_nonce_url( add_query_arg( $keys , $autoship_page_url ), 'autoship-remove-scheduled-order-coupon', 'autoship-remove-scheduled-order-coupon-nonce' ) : '' );

}

/**
 * Generates a URL to view Scheduled Orders from the my account page.
 * Can be modified via {@see autoship_get_scheduled_orders_url}
 *
 * @param int     $customer_id Optional. The WC Customer ID to view the orders for.
 *                             Only available to admins.
 * @return string
 */
function autoship_get_scheduled_orders_url( $customer_id = '') {
  return apply_filters( 'autoship_get_scheduled_orders_url', autoship_get_endpoint_url ( 'scheduled-orders', $customer_id, wc_get_page_permalink( 'myaccount' ) ), $customer_id );
}

/**
 * Generates a URL to view / edit a Scheduled Order from the my account page.
 * Can be modified via {@see autoship_get_scheduled_order_url}
 * @return string
 */
function autoship_get_scheduled_order_url( $scheduled_order_id ) {
  return apply_filters( 'autoship_get_scheduled_order_url', autoship_get_endpoint_url ( 'scheduled-order', $scheduled_order_id, wc_get_page_permalink( 'myaccount' ) ), $scheduled_order_id );
}

/**
 * Generates a URL to view a Scheduled Order from the my account page.
 * Can be modified via {@see autoship_get_view_scheduled_order_url}
 * @return string
 */
function autoship_get_view_scheduled_order_url( $scheduled_order_id ) {
  return apply_filters( 'autoship_get_view_scheduled_order_url', autoship_get_endpoint_url ( 'view-scheduled-order', $scheduled_order_id, wc_get_page_permalink( 'myaccount' ) ), $scheduled_order_id );
}

/**
 * Generates a URL to view or edit a Scheduled Order from the my account page.
 * Modified/controlled via a filter
 *
 * Can be modified via {@see autoship_get_view_edit_scheduled_order_url}
 * @param int $scheduled_order_id The scheduled order id.
 * @param string $status The scheduled order status
 * @return string
 */
function autoship_get_view_edit_scheduled_order_url( $scheduled_order_id , $status ) {

  if ( in_array( $status, apply_filters( 'autoship_editable_order_statuses', array( 'Active', 'Paused' ) ) ) ){

    $url = autoship_get_endpoint_url ( 'view-scheduled-order', $scheduled_order_id, wc_get_page_permalink( 'myaccount' ) );

  } else {

    $url = autoship_get_endpoint_url ( 'scheduled-order', $scheduled_order_id, wc_get_page_permalink( 'myaccount' ) );

  }

  return apply_filters( 'autoship_get_view_edit_scheduled_order_url', $url , $scheduled_order_id , $status );
}

/**
 * Generates a URL to fresh the Shipping Rates for a Scheduled Order
 * NOTE: Qpilot updates shipping rates on a Scheduled Order when it's updated
 * even if no changes are made.
 *
 * @param int $scheduled_order_id The scheduled order id.
 * @return string
 */
function autoship_refresh_scheduled_order_url( $scheduled_order_id ) {

  $keys = array(
    'refresh-scheduled-order' => 'refresh-scheduled-order',
    'scheduled-order'  => $scheduled_order_id,
  );

  $autoship_page_url = autoship_get_scheduled_order_url( $scheduled_order_id );
  return apply_filters( 'autoship_refresh_scheduled_order_url', $autoship_page_url ?
  wp_nonce_url( add_query_arg( $keys , $autoship_page_url ), 'autoship-refresh-scheduled-order-data', 'autoship-refresh-scheduled-order-data-nonce' ) : '' );

}

// ==========================================================
// CORE CACHE MANAGEMENT FUNCTIONS
// ==========================================================

/**
* CORE ORDER CACHE
* Current Order Cache not set but should / could be updated to
* use transients, session, cookies etc.
**/

if ( !function_exists( 'autoship_get_order_cache_data' ) ){

  /**
   * Retrieves the Session / Cache / Transient option
   * Can be overwritten to use other caching, session, transient.
   */
  function autoship_get_order_cache_data( $key ){
    global $autoship_order_cache;
    return apply_filters( 'autoship_get_order_cache_handler_object' , isset( $autoship_order_cache[$key] ) ? $autoship_order_cache[$key]  : '', $autoship_order_cache, $key );
  }

}

if ( !function_exists( 'autoship_save_order_cache_data' ) ){

  /**
   * Saves the Session / Cache / Transient option
   * Can be overwritten to use other caching, session, transient.
   */
  function autoship_save_order_cache_data( $key, $data ){
    global $autoship_order_cache;
    $autoship_order_cache[$key] = apply_filters( 'autoship_save_order_cache_handler_object' , $data, $key, $autoship_order_cache );
  }

}

if ( !function_exists( 'autoship_clear_order_cache_data' ) ){

  /**
  * Clears the Session / Cache / Transient option
  * Can be overwritten to use other caching, session, transient.
  */
  function autoship_clear_order_cache_data(){
    global $autoship_order_cache;
    $autoship_order_cache = apply_filters( "autoship_clear_orders_cache_handler_object" , array(), $autoship_order_cache );
  }

}

/**
* CORE PRODUCT CACHE
* Current Product Cache uses the WP transients
**/

if ( !function_exists( 'autoship_get_product_cache_data' ) ){

  /**
   * Retrieves the Session / Cache / Transient option
   * Can be overwritten to use other caching, session, transient.
   * @see get_transient()
   */
  function autoship_get_product_cache_data(){
    $autoship_product_cache = get_transient( apply_filters( "autoship_product_cache_object_name" , 'autoship_product_cache' ) );
    return apply_filters( 'autoship_get_product_cache_handler_object' , ( false === $autoship_product_cache ) ? '' : $autoship_product_cache, $autoship_product_cache );
  }

}

if ( !function_exists( 'autoship_save_product_cache_data' ) ){

  /**
   * Saves the Session / Cache / Transient option
   * Can be overwritten to use other caching, session, transient.
   * @see set_transient()
   */
  function autoship_save_product_cache_data( $key, $data ){
    $bool_response = set_transient( apply_filters( "autoship_product_cache_object_name" , 'autoship_product_cache' ) , array( $key => $data ), apply_filters( 'autoship_product_cache_max_life', 720 ) );
  }

}

if ( !function_exists( 'autoship_clear_product_cache_data' ) ){

  /**
   * Clears the Session / Cache / Transient option
   * Can be overwritten to use other caching, session, transient.
   * @see delete_transient()
   */
  function autoship_clear_product_cache_data(){
    delete_transient( apply_filters( "autoship_product_cache_object_name" , 'autoship_product_cache' ) );
  }

}

// ==========================================================
// ORDER CACHE UTILITY FUNCTIONS
// ==========================================================

/**
 * Returns the Scheduled Order, either from cache else pulls it.
 *
 * @param int       $order_id   The Scheduled Order ID
 * @param string    $key        The session / cache key
 * @return array    $order      The Autoship Scheduled Order Data
 */
function autoship_maybe_retrieve_order_from_cache( $order_id, $key = NULL ){

  $key = apply_filters( 'autoship_order_from_cache_key', $key, $order_id );

  if ( !isset( $key ) || empty( $key ) )
  $key = $order_id;

  $order = autoship_pull_scheduled_order_session( $key, $order_id );

  // If no order in cache
  // pull it and add it to cache
  if( empty( $order ) ){
  $order = autoship_get_scheduled_order( $order_id, true );
  autoship_load_scheduled_order_into_session( $key, $order );
  }

  return $order;

}

// ==========================================================
// ORDER CACHE WRAPPER MANAGEMENT FUNCTIONS
// ==========================================================

/**
 * Clears the Session data for the supplied key
 * @param string     $key  The session key.
 */
function autoship_clear_scheduled_order_sessions(){

  autoship_clear_order_cache_data();

}

/**
 * Adds the supplied Autoship Schedule Data to the Session
 * @param string   $key  The session key to add it for.
 * @param stdClass $data The Autoship Schedule Data
 */
function autoship_load_scheduled_order_into_session( $key, $data ){

  autoship_save_order_cache_data( $key, $data );

}

/**
 * Gst the Session data for the supplied key
 * @param string           $key  The session key
 * @return stdClass|array  $data The Autoship Schedule Data
 */
function autoship_pull_scheduled_order_session( $key, $order_id = 0, $decoded = true, $clean_reset = true ){

  $order = autoship_get_order_cache_data( $key );

  // if clean reset clear session data.
  if ( $clean_reset )
  autoship_clear_scheduled_order_sessions();

  // Convert stdClass objects to array if needed.
  if ( $decoded ) {

   $order = autoship_convert_object_to_array( $order );

  }

  // If the order id was supplied verify it.
  if ( !empty( $order ) && $order_id ) {

    if ( $order['id'] != $order_id )
    $order = '';

  }

  return $order;

}

// ==========================================================
// PRODUCTS CACHE WRAPPER MANAGEMENT FUNCTIONS
// ==========================================================

/**
 * Adds the supplied Autoship Schedule Data to the Session
 * @param string   $key  a UNIX timestamp.
 * @param bool           False if stale or True if valid
 */
function autoship_check_schedulable_products_session_freshness( $key ){


    // Get current UTC date time
    // Specified date/time in the specified time zone.
    $date = new DateTime();
    $date->setTimezone(new DateTimeZone('UTC'));
    $now = $date->getTimestamp();

    // Determine the diff of the two UNIX timestamps in minutes
    $dteDiff  = abs( $now - $key ) / 60;

    // The default threshold is to expire the session every 12 min.
    $threshold = apply_filters( 'autoship_schedulable_products_session_freshness_threshold', 12 );
    return apply_filters( 'autoship_schedulable_products_session_freshness', $dteDiff > $threshold, $dteDiff, $threshold, $key, $now );

}

/**
 * Adds the supplied Autoship Schedule Data to the Session
 * @param string   $key  The session key to add it for.
 * @param stdClass $data The Autoship Schedule Data
 */
function autoship_load_schedulable_products_into_session( $data ){

  // Get current UTC date time
  // Specified date/time in the specified time zone.
  $date = new DateTime();
  $date->setTimezone(new DateTimeZone('UTC'));
  $now = $date->getTimestamp();

  autoship_clear_product_cache_data();
  autoship_save_product_cache_data( $now, $data );

}

/**
 * Gst the Session data for the supplied key
 *
 * @param int              $product_id              Optional. The product_id.
 * @param bool             $decoded                 Optional. True convert any objects to array.
 * @param bool             $force_reset_on_pull     Optional. Clear Session on Data Pull.
 *
 * @return array|stdClass  $data        The Autoship Schedule Data
 */
function autoship_pull_schedulable_products_session( $product_id = 0, $decoded = false, $force_reset_on_pull = false ){

  $product_set = autoship_get_product_cache_data();

  if ( empty( $product_set ) )
  return '';

  // Get the key and check the freshness of the session
  // if stale clear it and return empty
  $values = reset($product_set);
  $key = key($product_set);
  if ( !autoship_check_schedulable_products_session_freshness( $key ) || $force_reset_on_pull ){

    autoship_clear_schedulable_products_sessions();
    return '';

  }

  // Check and Grab just the product if supplied.
  // Product is missing so clear session in case it's not update.
  if ( $product_id && !isset( $values[$product_id] ) ){

    autoship_clear_schedulable_products_sessions();
    return '';

  } else if ( $product_id ){

    $values = $values[$product_id];

  }

  // Check for the force reset on pull flag.
  if ( $force_reset_on_pull )
  autoship_clear_schedulable_products_sessions();

  // Convert stdClass objects to array if needed.
  return $decoded ? autoship_convert_object_to_array( $values ) : $values;

}

/**
 * Clears the Session data for the supplied key
 * @param string     $key  The session key.
 */
function autoship_clear_schedulable_products_sessions(){

  autoship_clear_product_cache_data();

}

// ==========================================================
// MAIN TEMPLATE FORM UPDATE FUNCTIONS
// ==========================================================

/**
 * The Ajax Callback to remove an item from an Order
 * NOTE Currently Not Used.
 */
function autoship_remove_scheduled_order_item_ajax_wrapper () {

    ob_start();

    // Validate Nonce and get order id.
    $nonce = $_REQUEST['autoship-remove-scheduled-order-item-nonce'];
    $autoship_order_id = autoship_update_scheduled_order_post_valid_nonce_id( $nonce, 'autoship-remove-scheduled-order-item' );
    $item_id = absint( $_POST['remove-scheduled-order-item'] );

    if ( empty( $item_id ) ){

      wc_add_notice( __( 'Invalid or Missing %s Item Information.', 'autoship' ), 'error' );
      $success = false;

    } else {

      // Pre-flight & get Scheduled Order from cache otherwise pull it for processing
      $original_autoship_order = autoship_maybe_retrieve_order_from_cache( $autoship_order_id );

      $success = autoship_update_scheduled_order_handler(
        $autoship_order_id,
        'autoship_remove_order_item',
        array( 'autoship_scheduled_order_item_id' => $item_id,
               'original_autoship_order' => $original_autoship_order )
      );

    }

    // if Successful
    $success_action = $success ? 'success' : 'failure';
    do_action( "autoship_ajax_remove_scheduled_order_item_{$success_action}", $autoship_order_id, $item_id );

    $notices = wc_get_notices();
    wc_clear_notices();

		// If there was an error adding to the cart, redirect to the product page to show any errors
		$data = array(
      'notices'     => $notices,
			'error'       => $success,
			'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ),
		);

    if ( !$success ){
      wp_send_json_error( $data );
    } else {
      wp_send_json( $data );
    }

    die();
}

/**
 * Validates the POST call includes the autoship order id and valid nonce.
 *
 * @param string $nonce          The WP Nonce to check.
 * @param string $action         The action to validate.
 *
 * @return int|bool The Autoship Order of false if invalid.
 */
function autoship_update_scheduled_order_post_valid_nonce_id( $nonce, $action ){

  // Nonce and hook for custom verification.
  // Add custom user security checks here if custom rights are added.
  if( ! apply_filters( 'autoship_update_scheduled_order_action_nonce_verification', wp_verify_nonce( $nonce, $action ) ) ){

    wc_add_notice( __( 'Invalid or Expired Call.', 'autoship' ), 'error' );
    return false;

  }

  // All Updates require the order id.
  if ( !isset( $_POST['autoship_scheduled_order_id'] ) || empty( $_POST['autoship_scheduled_order_id'] ) ){

    wc_add_notice( __( sprintf( 'Invalid %s ID.', autoship_translate_text( 'Scheduled Order' ) ), 'autoship' ), 'error' );
    return false;

  }

  return apply_filters( 'autoship_update_scheduled_order_order_id_verification',
                        absint( $_POST['autoship_scheduled_order_id'] ), $action );

}

/* Endpoint Handler Wrappers
====================================== */

/**
 * Deletes a Scheduled Order or Displays confirmation using the Query Vars
 * @see autoship_update_scheduled_order_handler
 */
function autoship_delete_scheduled_order_endpoint_wrapper (){
	global $wp;

	if ( isset( $wp->query_vars['delete-scheduled-order-confirm'] ) && isset( $wp->query_vars['scheduled-order'] )  ) {

    $nonce = $_REQUEST['autoship-delete-scheduled-order-nonce'];

    // Nonce and hook for custom verification.
    // Add custom user security checks here if custom rights are added.
  	if( ! apply_filters( 'autoship_delete_scheduled_order_action_nonce_verification', wp_verify_nonce( $nonce, 'autoship-delete-scheduled-order' ) ) ){

      wc_add_notice( __( 'Invalid or Expired Call.', 'autoship' ), 'error' );

    } else if ( 'confirm' == $wp->query_vars['delete-scheduled-order-confirm'] ) {

      $scheduled_order_label = autoship_translate_text( 'scheduled order' );

      $url = autoship_get_scheduled_order_delete_url( $wp->query_vars['scheduled-order'], 'confirmed' );
      $cancel = autoship_get_scheduled_orders_url();
      $notice = apply_filters( 'autoship_confirm_delete_schedule_link_label',
      __( sprintf( '<span class="action-request">Are you sure you want to Delete %s #%d?</span> <a class="confirm-action" href="%s">Confirm</a> or <a class="cancel-action" href="%s">Cancel</a>', $scheduled_order_label, $url, $wp->query_vars['scheduled-order'] ), 'autoship'), $scheduled_order_label, $url, $cancel, $wp->query_vars['scheduled-order'], $wp->query_vars['delete-scheduled-order-confirm'] );

      wc_add_notice( $notice , 'notice' );

    } else if ( 'confirmed' == $wp->query_vars['delete-scheduled-order-confirm'] )  {

      // Pre-flight & get Scheduled Order from cache otherwise pull it for processing
      $original_autoship_order = autoship_maybe_retrieve_order_from_cache( $wp->query_vars['scheduled-order'] );

      $success = autoship_update_scheduled_order_handler(
        $wp->query_vars['scheduled-order'],
        'autoship_delete_order',
        array( 'original_autoship_order' => $original_autoship_order )
      );

    }

		wp_redirect( autoship_get_scheduled_orders_url() );
		exit();

  }

}
add_action( 'wp', 'autoship_delete_scheduled_order_endpoint_wrapper', 20 );

/**
 * Updates a Scheduled Order Status using the Query Vars
 * @see autoship_update_scheduled_order_handler
 */
function autoship_update_scheduled_order_status_endpoint_wrapper (){

  global $wp;

	if ( isset( $wp->query_vars['update-scheduled-order-status'] ) && isset( $wp->query_vars['scheduled-order'] )  ) {

    $nonce = $_REQUEST['autoship-update-scheduled-order-nonce'];

    // Nonce and hook for custom verification.
    // Add custom user security checks here if custom rights are added.
  	if( ! apply_filters( 'autoship_update_scheduled_order_action_nonce_verification', wp_verify_nonce( $nonce, 'autoship-update-scheduled-order' ) ) ){

      wc_add_notice( __( 'Invalid or Expired Call.', 'autoship' ), 'error' );

    } else {

      // Pre-flight & get Scheduled Order from cache otherwise pull it for processing
      $original_autoship_order = autoship_maybe_retrieve_order_from_cache( $wp->query_vars['scheduled-order'] );

      $success = autoship_update_scheduled_order_handler(
        $wp->query_vars['scheduled-order'],
        'autoship_update_order_status',
        array( 'status' => $wp->query_vars['update-scheduled-order-status'],
               'original_autoship_order' => $original_autoship_order )
      );

    }

		wp_redirect( autoship_get_scheduled_orders_url() );
		exit();

  }

}
add_action( 'wp', 'autoship_update_scheduled_order_status_endpoint_wrapper', 20 );

/**
 * Removes a Scheduled Order item using the Query Vars
 * @see autoship_update_scheduled_order_handler
 */
function autoship_remove_scheduled_order_item_endpoint_wrapper (){
	global $wp;

	if ( isset( $wp->query_vars['remove-scheduled-order-item'] ) && isset( $wp->query_vars['scheduled-order'] )  ) {

    $nonce = $_REQUEST['autoship-remove-scheduled-order-item-nonce'];

    // Nonce and hook for custom verification.
    // Add custom user security checks here if custom rights are added.
  	if( ! apply_filters( 'autoship_remove_scheduled_order_item_action_nonce_verification', wp_verify_nonce( $_REQUEST['autoship-remove-scheduled-order-item-nonce'], 'autoship-remove-scheduled-order-item' ) ) ){

      wc_add_notice( __( 'Invalid or Expired Call.', 'autoship' ), 'error' );

    } else {

      // Pre-flight & get Scheduled Order from cache otherwise pull it for processing
      $original_autoship_order = autoship_maybe_retrieve_order_from_cache( $wp->query_vars['scheduled-order'] );

      $success = autoship_update_scheduled_order_handler(
        $wp->query_vars['scheduled-order'],
        'autoship_remove_order_item',
        array( 'autoship_scheduled_order_item_id' => $wp->query_vars['remove-scheduled-order-item'],
               'original_autoship_order' => $original_autoship_order )
      );

      wp_redirect( autoship_get_scheduled_order_url( $wp->query_vars['scheduled-order'] ) );
      exit();

    }

		wp_redirect( autoship_get_scheduled_orders_url() );
		exit();

  }

}
add_action( 'wp', 'autoship_remove_scheduled_order_item_endpoint_wrapper', 20 );

/**
 * Adds a WooCommerce item to a Scheduled Order using the Query Vars.
 * NOTE If a Frequency & Frequency Type is not supplied then the item will be
 * added to the next scheduled order.
 */
function autoship_add_to_scheduled_order_endpoint_wrapper ( $action ){

  // A product is required for this endpoint.
	if (  apply_filters( 'autoship_add_to_scheduled_order_endpoint', $action == 'add-to-scheduled-order', $action )  ) {

    // Default possible query vars which allows devs to set
    $link = autoship_get_scheduled_order_link_params( 'add_scheduled_order_item' );

    if ( !isset( $link['products'] ) )
    return false;

    $customer_id = 0;
    $notice = array( 'notice' => '', 'notice_type' => '' );

    if ( !isset( $link['customer_id'] ) )
    $notice = array( 'notice' => __( sprintf( 'A problem was encountered while attempting to add this item to the %s.  Please try again.', autoship_translate_text( 'scheduled order' ) ), 'autoship' ), 'notice_type' => 'error' );

    if ( empty( $notice['notice'] ) ){

      // Process the link data and add the item to the next order(s)
      $result = autoship_add_to_scheduled_order_link_handler( $link );

      if ( !$result )
      do_action( 'autoship_add_to_next_order_no_order_found', $link, $customer_id );

      if ( is_wp_error( $result ) )
      $notice = array( 'notice' => $result->get_error_message(), 'notice_type' => 'error' );

      // Check if no future orders exist
      if ( false != $result && !is_wp_error( $result ) ){

        $scheduled_orders_label = autoship_translate_text( 'scheduled orders' );
        $scheduled_order_label = autoship_translate_text( 'scheduled order' );

        // Multiple cycles or just one terminology
        $qty_string = $link['max'] > 1 ?
        __( sprintf( '%d item(s) were successfully added to your next %d %s.', $link['qty'], $link['max'], $scheduled_orders_label ), 'autoship' ) :
        __( sprintf( '%d item(s) were successfully added to your next %s.',  $link['qty'], $scheduled_order_label ), 'autoship' );

        $notice = array(
          'notice'      => $qty_string,
          'notice_type' => 'success' );

      }

    }

    // Allow filtering of redirect
    $redirect = apply_filters('autoship_add_to_next_order_notice_url', '', $notice['notice_type'], $link, $customer_id );

    // Let devs adjust notices and redirect.
    $notice = apply_filters( 'autoship_add_to_next_order_notice', $notice, $link, $customer_id );

    if ( !empty( $notice['notice'] ) )
    wc_add_notice( $notice['notice'] , $notice['notice_type'] );

    if ( !empty( $redirect ) ){
      wp_redirect( $redirect );
      exit();
    }

    return true;

  }

}

/**
 * Creates a Scheduled Order using the Query Vars.
 * @param string $action The current action
 */
function autoship_create_scheduled_order_endpoint_wrapper ( $action ){

  // Only process the create action in this endpoint
	if (  apply_filters( 'autoship_create_scheduled_order_endpoint', $action == 'create-scheduled-order', $action )  ) {

    // retrieve the query vars
    $link = autoship_get_scheduled_order_link_params( 'create_scheduled_order' );

    $customer_id = 0;
    $notice = $redirect = '';

    $scheduled_order_label = autoship_translate_text( 'scheduled order' );

    if ( !isset( $link['customer_id'] ) )
    $notice = array( 'notice' => __( sprintf( 'A problem was encountered while attempting to add this item to the %s.  Please try again.', $scheduled_order_label ), 'autoship' ), 'notice_type' => 'error' );

    if ( empty( $notice ) ){

      // Process the link data and add the item to the next order(s)
      $result = autoship_create_scheduled_order_link_handler( $link );

      if ( is_wp_error( $result ) ){

        do_action( 'autoship_create_scheduled_order_failed', $link, $customer_id );

        $notice = array( 'notice' => $result->get_error_message(), 'notice_type' => 'error' );

      } else {

        $redirect = autoship_get_scheduled_order_url( $result->id );

        $notice = array( 'notice' => sprintf( __( '%s #%d was successfully created.', 'autoship' ), $scheduled_order_label, $result->id ), 'notice_type' => 'success' );

        do_action( 'autoship_create_scheduled_order_by_link_success', $result, $link, $customer_id );

      }

    }

    // Allow filtering of redirect
    $redirect = apply_filters('autoship_create_scheduled_order_url_redirect', $redirect, $notice['notice_type'], $link, $customer_id );

    // Let devs adjust notices and redirect.
    $notice = apply_filters( 'autoship_create_scheduled_order_url_notice', $notice, $link, $customer_id );

    if ( !empty( $notice['notice'] ) )
    wc_add_notice( $notice['notice'] , $notice['notice_type'] );

    if ( !empty( $redirect ) ){
      wp_redirect( $redirect );
      exit();
    }

    return true;

  }

}

/**
 * Removes a Scheduled Order coupon using the Query Vars
 * @see autoship_update_scheduled_order_handler
 */
function autoship_remove_scheduled_order_coupon_endpoint_wrapper (){
	global $wp;

	if ( isset( $wp->query_vars['remove-scheduled-order-coupon'] ) && isset( $wp->query_vars['scheduled-order'] )  ) {

    $nonce = $_REQUEST['autoship-remove-scheduled-order-coupon-nonce'];

    // Nonce and hook for custom verification verification.
    // Add custom user security checks here if custom rights are added.
  	if( ! apply_filters( 'autoship_remove_scheduled_order_coupon_action_nonce_verification', wp_verify_nonce( $nonce, 'autoship-remove-scheduled-order-coupon' ) ) ){

      wc_add_notice( __( 'Invalid or Expired Call.', 'autoship' ), 'error' );

    } else {

      // Pre-flight & get Scheduled Order from cache otherwise pull it for processing
      $original_autoship_order = autoship_maybe_retrieve_order_from_cache( $wp->query_vars['scheduled-order'] );

      $success = autoship_update_scheduled_order_handler(
        $wp->query_vars['scheduled-order'],
        'autoship_remove_order_coupon',
        array(
          'autoship_coupon_code'    => $wp->query_vars['remove-scheduled-order-coupon'],
          'original_autoship_order' => $original_autoship_order
        )
      );

      wp_redirect( autoship_get_scheduled_order_url( $wp->query_vars['scheduled-order'] ) );
      exit();

    }

		wp_redirect( autoship_get_scheduled_orders_url() );
		exit();

  }

}
add_action( 'wp', 'autoship_remove_scheduled_order_coupon_endpoint_wrapper', 20 );

/**
 * Refreshes a Scheduled Orders Data using the Query Vars
 * @see autoship_update_scheduled_order_handler
 */
function autoship_refresh_scheduled_order_data_endpoint_wrapper(){
	global $wp;

	if ( isset( $wp->query_vars['refresh-scheduled-order'] ) && isset( $wp->query_vars['scheduled-order'] )  ) {

    $nonce = $_REQUEST['autoship-refresh-scheduled-order-data-nonce'];

    // Nonce and hook for custom verification verification.
    // Add custom user security checks here if custom rights are added.
  	if( ! apply_filters( 'autoship_refresh_scheduled_order_data_nonce_verification', wp_verify_nonce( $nonce, 'autoship-refresh-scheduled-order-data' ) ) ){

      wc_add_notice( __( 'Invalid or Expired Call.', 'autoship' ), 'error' );

    } else {

      // Pre-flight & get Scheduled Order from cache otherwise pull it for processing
      $original_autoship_order = autoship_maybe_retrieve_order_from_cache( $wp->query_vars['scheduled-order'] );

      $success = autoship_update_scheduled_order_handler(
        $wp->query_vars['scheduled-order'],
        'autoship_refresh_scheduled_order',
        array(
          'original_autoship_order' => $original_autoship_order
        )
      );

      wp_redirect( autoship_get_scheduled_order_url( $wp->query_vars['scheduled-order'] ) );
      exit();

    }

		wp_redirect( autoship_get_scheduled_orders_url() );
		exit();

  }

}
add_action( 'wp', 'autoship_refresh_scheduled_order_data_endpoint_wrapper', 20 );

/* Endpoint Handler Utility Functions
====================================== */

/**
 * Retrieve the link components from the $_GET for the Scheduled Order link actions
 * @param string $action The link action being performed.
 * @return array|WP_Error  Array of Link Params on Success or WP_Error on failure
 */
function autoship_get_scheduled_order_link_params( $action ){

  $link = array();
  $qty  = 1;

  if ( isset( $_GET['qty'] ) && !empty( $_GET['qty'] ) ){
    $link['qty'] = $qty  = absint( $_GET['qty'] );
  }

  // Allow for item or items as a param
  if ( ( isset( $_GET['items'] ) && !empty( $_GET['items'] ) ) ||
       ( isset( $_GET['item'] ) && !empty( $_GET['item'] ) ) ){

    $key = isset( $_GET['items'] ) && !empty( $_GET['items'] ) ? 'items' : 'item';
    $link['products'] = !is_array( $_GET[$key] ) ?
    array( absint( $_GET[$key] ) => $qty ) : array_filter( $_GET[$key], 'absint' );
  }

  if ( isset( $_GET['min'] ) && !empty( $_GET['min'] ) ){
    $link['min'] = !is_array( $_GET['min'] ) ?
    absint( $_GET['min'] ) : array_filter( $_GET['min'], 'absint' );
  }

  if ( isset( $_GET['max'] ) && !empty( $_GET['max'] ) ){
    $link['max'] = !is_array( $_GET['max'] ) ?
    absint( $_GET['max'] ) : array_filter( $_GET['max'], 'absint' );
  }


  $raw_next_occurrence = isset( $_GET['nextoccurrence'] ) && !empty( $_GET['nextoccurrence'] ) ? sanitize_text_field( $_GET['nextoccurrence'] ) : NULL;
  if( isset( $raw_next_occurrence ) ){

    $formats_to_check = array(

      // 12 Hour Clock variations
      'Y-m-d g:i A',    // 2021-12-22 8:35 PM
      'Y-m-d h:i A',    // 2021-12-22 08:35 PM
      'Y-m-d g:i:s A',  // 2021-12-22 8:35:23 PM
      'Y-m-d h:i:s A',  // 2021-12-22 08:35:23 PM
      'Y-m-d g:i a',    // 2021-12-22 8:35 pm
      'Y-m-d h:i a',    // 2021-12-22 08:35 pm
      'Y-m-d g:i:s a',  // 2021-12-22 8:35:23 pm
      'Y-m-d h:i:s a',  // 2021-12-22 08:35:23 pm

      // 24 Hour Clock variations
      'Y-m-d G:i',   // 2021-12-22 16:35
      'Y-m-d H:i',   // 2021-12-22 08:35
      'Y-m-d G:i:s', // 2021-12-22 16:35:23
      'Y-m-d H:i:s', // 2021-12-22 08:35:23

    );

    foreach ( $formats_to_check as $format ) {

      if ( $next_occurrence = autoship_get_api_formatted_date ( $raw_next_occurrence, $format, true ) ){
        $link['next_occurrence'] = $next_occurrence;
        break;
      }

    }

  }

  $link['frequency']     = isset( $_GET['freq'] ) && !empty( $_GET['freq'] )         ? absint( $_GET['freq'] ) :  NULL;
  $link['frequency_type'] = isset( $_GET['freqtype'] ) && !empty( $_GET['freqtype'] ) ? sanitize_text_field( $_GET['freqtype'] ) : NULL;

  $link['order'] = isset( $_GET['order'] ) && !empty( $_GET['order'] )? absint( $_GET['order'] ) : NULL;

  $link['coupon']= isset( $_GET['coupon'] ) && !empty( $_GET['coupon'] )? sanitize_text_field( $_GET['coupon'] ) : NULL;

  // Grab the current user id
  $user = get_current_user_id();

  // Only take the value of the customer field if the current user is an admin.
  // Or if the current customer matches the hash
  $valid = ( autoship_rights_checker( "autoship_filter_allow_customer_var_on_add_to_order", array('administrator') ) && isset( $_GET['customer'] ) ) || ( isset( $_GET['customer'] ) && hash_equals( wp_hash( $user ) ,  $_GET['customer'] ) );

  if ( $valid )
  $link['customer_id'] = hash_equals( wp_hash( $user ) ,  $_GET['customer'] ) ? $user : $_GET['customer'];

  $link_args = wp_parse_args( $link,
    array(
      'products'            => NULL,
      'qty'                 => NULL,
      'frequency'           => NULL,
      'frequency_type'      => NULL,
      'max'                 => NULL,
      'mix'                 => NULL,
      'customer_id'         => get_current_user_id(),
      'order'               => NULL,
      'coupon'              => NULL,
      'discount'            => NULL
  ) );

  // Default possible query vars which allows devs to set
  return apply_filters( "autoship_{$action}_endpoint_quary_vars_defaults", $link_args );

}

/**
 * Validate Coupon Against the supplied wc product id and current user. Only
 * coupon discounts of percent or fixed_product are allowed.
 *
 * This Coupon Validations included
 *   - Expiration Date : Checks if Coupon is expired.
 *   - Type : Checks that the coupon type is either percent or fixed_product
 *   - User Usage Limit : Checks if the coupon has hit it's limit for a user.
 *   - Valid Products : Checks if this product is included for the coupon.
 *   - Excluded Products : Checks if this product is excluded for the coupon.
 *   - Valid Categories : Checks if this product's categories are included for the coupon.
 *   - Excluded Categories : Checks if this product's categories are excluded for the coupon.
 *   - User Email : Checks if this user's email is valid for the coupon.
 *
 * @param string $coupon_code The coupon code to validate.
 * @param int $product_id     The WC_Product id
 * @param float $price        The Product Price
 * @param int $qty            The Product Quantity
 * @param int $min            The Min Cycles
 * @param int $max            The Max Cycles
 * @param int $user_id        The WC User ID.
 *
 * @return float|WP_Error The discount or an error.
 */
function autoship_validate_and_get_autoship_coupon_discount( $coupon_code, $product_id, $price, $qty, $min, $max, $user_id ){

  // Get the coupon object from QPilot.
  $valid_coupon = autoship_get_coupon_by_code( $coupon_code );

  if ( is_wp_error( $valid_coupon ) || !$valid_coupon )
  return is_wp_error( $valid_coupon ) ? $valid_coupon : new WP_Error( 'invalid_coupon', __('The supplied coupon is invalid or does not exist.', 'autoship') );

  // Check expiration
  if ( isset( $valid_coupon->expirationDate ) && !empty( $valid_coupon->expirationDate ) ){

    // Get the current DateTime object and Expiration DateTime object
    $now              = autoship_get_datetime ();
    $next             = autoship_get_datetime ( $valid_coupon->expirationDate );

    // Expired?
    if ( $next < $now )
    return new WP_Error( 'expired_coupon', __('The supplied coupon is expired or does not exist.', 'autoship') );

  }

  // Check minCycles
  if ( isset( $valid_coupon->minCycles ) && !empty( $valid_coupon->minCycles ) && ( $min < $valid_coupon->minCycles ) )
  return new WP_Error( 'invalid_coupon_mincycles', __('Invalid Minimum Cycles for this coupon.', 'autoship') );

  // Check maxCycles
  if ( isset( $valid_coupon->maxCycles ) && !empty( $valid_coupon->maxCycles ) && ( $max > $valid_coupon->maxCycles ))
  return new WP_Error( 'invalid_coupon_maxcycles', __('Invalid Maxium Cycles for this coupon.', 'autoship') );

  // Check minSubtotal
  if ( isset( $valid_coupon->minSubtotal ) && !empty( $valid_coupon->minSubtotal ) && ( ( $price * $qty ) < $valid_coupon->minSubtotal ) )
  return new WP_Error( 'invalid_coupon_minsubtotal', __('Invalid Minimum Amount for this coupon.', 'autoship') );

  // Wait to pull the customer from the API until needed.
  $customer = array();

  // Check Shipping Country
  if ( isset( $valid_coupon->country ) && !empty( $valid_coupon->country ) ){

    if ( empty( $customer ) )
    $customer = autoship_get_autoship_customer( $user_id );

    if ( strtolower( $valid_coupon->country ) != strtolower( $customer->shippingCountry ) )
    return new WP_Error( 'invalid_country', __('The supplied coupon is not valid for your country.', 'autoship') );

  }

  // Check Shipping Postcode
  if ( isset( $valid_coupon->postcode ) && !empty( $valid_coupon->postcode ) ){

    if ( empty( $customer ) )
    $customer = autoship_get_autoship_customer( $user_id );

    if ( strtolower( $valid_coupon->postcode ) != strtolower( $customer->shippingPostcode ) )
    return new WP_Error( 'invalid_postcode', __('The supplied coupon is not valid for your Postcode.', 'autoship') );

  }

  // Check Shipping State
  if ( isset( $valid_coupon->state ) && !empty( $valid_coupon->state ) ){

    if ( empty( $customer ) )
    $customer = autoship_get_autoship_customer( $user_id );

    if ( strtolower( $valid_coupon->state ) != strtolower( $customer->shippingState ) )
    return new WP_Error( 'invalid_state', __('The supplied coupon is not valid for your State.', 'autoship') );

  }

  // Check Shipping City
  if ( isset( $valid_coupon->city ) && !empty( $valid_coupon->city ) ){

    if ( empty( $customer ) )
    $customer = autoship_get_autoship_customer( $user_id );

    if ( strtolower( $valid_coupon->city ) != strtolower( $customer->shippingCity ) )
    return new WP_Error( 'invalid_city', __('The supplied coupon is not valid for your City.', 'autoship') );

  }


  $amount = $valid_coupon->amount;
  $type = $valid_coupon->discountType;

  if ( 'ReduceSubtotalByPercentage' == $type ){

    $discount = max( round( $price - ( $price * ( $amount / 100 ) ), 2 ) , 0);

  } else if ( 'ReduceSubtotalByAmount' == $type ) {

    $discount = max( $price - $amount , 0);

  } else {

    // Bail Since the discount type isn't valid
    return new WP_Error( 'invalid_coupon_type', __('The supplied coupon discount type is invalid.', 'autoship') );

  }

  return apply_filters( 'autoship_validate_and_get_autoship_coupon_discount_amount', $discount, $valid_coupon, $product_id, $price, $qty, $min, $max, $user_id );

}

/**
 * Validate Coupon Against the supplied wc product id and current user. Only
 * coupon discounts of percent or fixed_product are allowed.
 *
 * This Coupon Validations included
 *   - Expiration Date : Checks if Coupon is expired.
 *   - Amount : Checks that the amount of coupon is not empty
 *   - Type : Checks that the coupon type is either percent or fixed_product
 *   - Usage Limit : Checks if the coupon has hit it's limit.
 *   - User Usage Limit : Checks if the coupon has hit it's limit for a user.
 *   - Valid Products : Checks if this product is included for the coupon.
 *   - Excluded Products : Checks if this product is excluded for the coupon.
 *   - Valid Categories : Checks if this product's categories are included for the coupon.
 *   - Excluded Categories : Checks if this product's categories are excluded for the coupon.
 *   - User Email : Checks if this user's email is valid for the coupon.
 *
 * @param string $coupon_code The coupon code to validate.
 * @param int $product_id     The WC_Product id
 * @param float $price        The Product Price
 * @param int $qty            The Product Quantity
 *
 * @return float|WP_Error The discount or an error.
 */
function autoship_validate_and_get_woocommerce_coupon_discount( $coupon_code, $product_id, $price, $qty ){

    $coupon = array();

    // Grab the current user id
    $user = get_current_user_id();

    $wc_coupon = new WC_Coupon( $coupon_code );
    $coupon['amount'] = $wc_coupon->get_amount();

    // Not a real coupon bail.
    if ( empty( $coupon['amount'] ) )
    return new WP_Error( 'invalid_coupon', __('The supplied coupon is invalid or does not exist.', 'autoship') );

    // Get Discount Type
    $coupon['type']               = $wc_coupon->get_discount_type();

    // Calculate Coupon Discount
    // only support percent and fixed product
    if ( 'percent' == $coupon['type'] ){

      $coupon['discount'] = max( round( $price - ( $price * $coupon['amount'] ), 2 ) , 0);

    } else if ( 'fixed_product' == $coupon['type'] ) {

      $coupon['discount'] = max( $price - $coupon['amount'] , 0);

    } else {

      // Bail Since the discount type isn't valid
      return new WP_Error( 'invalid_coupon_type', __('The supplied coupon discount type is invalid.', 'autoship') );

    }

    // Check usage limit
    $coupon['usage_limit']        = $wc_coupon->get_usage_limit();
    $coupon['usage_count']        = $wc_coupon->get_usage_count();

    if ( ( $coupon['usage_count'] > 0 ) && ( $coupon['usage_limit'] > 0 ) && ( $coupon['usage_count'] >= $coupon['usage_limit'] ) )
    return new WP_Error( 'invalid_coupon', __('The supplied coupon is no longer valid.', 'autoship') );

    // Get who's used this coupon
    $coupon['used_by']            = $wc_coupon->get_used_by();

    // Validate Usage Limit per user
    $coupon['usage_user_limit']   = $wc_coupon->get_usage_limit_per_user();
    if ( ( $coupon['usage_user_limit'] > 0 ) && $wc_coupon->get_data_store() ){

      $date_store  = $coupon->get_data_store();
      $usage_count = $date_store->get_usage_by_user_id( $wc_coupon, $user );

      // Bail if limit is hit
      if ( $usage_count >= $coupon['usage_user_limit'] )
      return new WP_Error( 'invalid_use', sprintf( __('This coupon can not be used more than %d times.', 'autoship'), $coupon['usage_user_limit'] ) );

    }


    // Get Expired Date
    $coupon['expired_date']       = $wc_coupon->get_date_expires();

    // First check if this coupon is expired
    if ( isset( $coupon['expired_date'] ) ){

      $coupon['expired_timestamp']  = $coupon['expired_date']->getTimestamp();
      $coupon['current_timestamp']  = current_time( 'timestamp', true );
      $coupon['expired']            = $coupon['current_timestamp'] > $coupon['expired_timestamp'];

      if ( $coupon['expired'] )
      return new WP_Error( 'expired_coupon', __('The supplied coupon is expired.', 'autoship') );

    }

    // Validate Product Ids
    $coupon['valid_products']     = $wc_coupon->get_product_ids();

    // First check if this product is valid for this coupon
    if ( !empty( $coupon['valid_products'] ) && !in_array ( $product_id , $coupon['valid_products'] ) )
    return new WP_Error( 'invalid_coupon_product', __('This product is not valid for the supplied coupon.', 'autoship') );

    // Validate Excluded Products
    $coupon['excluded_products']  = $wc_coupon->get_excluded_product_ids();

    // First check if this product is valid for this coupon
    if ( !empty( $coupon['excluded_products'] ) && in_array ( $product_id , $coupon['excluded_products'] ) )
    return new WP_Error( 'invalid_coupon_product', __('This product is not valid for the supplied coupon.', 'autoship') );

    // Exclude Products on Sale
    $product = wc_get_product( $product_id );

    if ( !$product )
    return new WP_Error( 'invalid_coupon_product', __('The supplied product is not valid or no longer exists.', 'autoship') );

    $coupon['onsale_limit']       = $wc_coupon->get_exclude_sale_items();

    if ( $coupon['onsale_limit'] && $product->is_on_sale() )
    return new WP_Error( 'invalid_coupon_product', __('The supplied coupon is not valid for products on sale.', 'autoship') );

    // Check for Category Restrictions
    $product_cats = wc_get_product_cat_ids( $product_id );
    $coupon['categories']         = $wc_coupon->get_product_categories();
    $coupon['excluded_categories']= $wc_coupon->get_excluded_product_categories();

    if ( $product->get_parent_id() )
    $product_cats = array_merge( $product_cats, wc_get_product_cat_ids( $product->get_parent_id() ) );

    // If we find an item with a cat in our allowed cat list, the coupon is valid.
    if ( ( !empty( $coupon['categories'] ) && ( count( array_intersect( $product_cats, $coupon['categories'] ) ) <= 0 ) ) ||
         ( !empty( $coupon['excluded_categories'] ) && ( count( array_intersect( $product_cats, $coupon['excluded_categories'] ) ) > 0 ) ) )
    return new WP_Error( 'invalid_coupon_product', __('The supplied coupon is not valid for this products categories.', 'autoship') );

    // Check and Validate any email restrictions
    // Limit to defined email addresses.
    // Get user and posted emails to compare.
    $coupon['emails']         = $wc_coupon->get_email_restrictions();

    // Go through allowed emails and validate.
    if ( is_array( $coupon['emails'] ) && 0 < count( $coupon['emails'] ) ) {

      $current_user       = wp_get_current_user();
      $user_bill_email    = get_user_meta( $user, 'billing_email', true );
      $user_account_email = $current_user->user_email;

      // Combine to get emails actually to check
      $check_emails  = array_unique(
        array_filter(
          array_map(
            'strtolower',
            array_map( 'sanitize_email', array( $user_bill_email, $user_account_email ) )
          )
        )
      );

      $found = false;
      foreach ( $check_emails as $check_email ) {

        // With a direct match we return true.
        $found = in_array( $check_email, $coupon['emails'], true );

        // If still not found then look to see if it matches a wildcard
        if ( !$found ){

          // Go through the allowed emails and return true if the email matches a wildcard.
          foreach ( $coupon['emails'] as $restriction ) {
            // Convert to PHP-regex syntax.
            $regex = '/^' . str_replace( '*', '(.+)?', $restriction ) . '$/';
            preg_match( $regex, $check_email, $match );
            if ( ! empty( $match ) ) {
              $found = true;
            }
          }

        }

      }

      if ( !$found )
      return new WP_Error( 'invalid_coupon_email', __('The supplied coupon is not valid.', 'autoship') );

    }

    return apply_filters( 'autoship_validate_and_get_coupon_discount_amount', $coupon['discount'], $coupon, $product_id, $price, $qty );

}

/**
 * Adjust the redirect url for when a scheduled order is created via a link.
 * Accounts for Non-Native UI use of create order link.
 *
 * @param string $redirect The current URL the user will be redirected to.
 * @param string $notice_type The type of result ( error or success )
 * @param array $link The link parameters
 * @param int $customer_id The current customers WC Id
 *
 * @return string The redirect url
 */
function autoship_adjust_create_scheduled_order_url_redirect( $redirect, $notice_type, $link, $customer_id ){

  // Get the UI version and adjust the redirect to the Scheduled Orders tab rather than the detail for non-Native UIs
  return ( 'template' != autoship_get_scheduled_orders_display_version() ) ?
  autoship_get_scheduled_orders_url( $customer_id ) : $redirect;

}
add_filter( 'autoship_create_scheduled_order_url_redirect', 'autoship_adjust_create_scheduled_order_url_redirect', 10, 4 );

/* Endpoint Link Handler Functions
====================================== */

/**
 * Processes the Create Scheduled Order Link Data
 * @param array $link {
 *          The link param in an array
 *          @type int|array $products       Array of woocommerce simple or variation ids or single id
 *          @type int       $qty            The qty to add
 *          @type int       $min            The min cycles
 *          @type int       $max            The max cycles
 *          @type int       $frequency      The frequency
 *          @type string    $frequency_type The frequency type
 *          @type int       $order          The Autoship Scheduled Order ID
 *          @type string    $coupon         The coupon code
 *          @type string    $customer_id    The WC Customer ID hashed
 *          @type float     $discount       The discount amount ( SUPPLIED AS NULL )
 * }
 *
 * @return bool|WP_Error  True on Success, False if no scheduled order exists,
 *                        WP_Error on failure
 */
function autoship_create_scheduled_order_link_handler( $link ){

  // The General notice is used for any link failures not related to coupons
  $general_notice = apply_filters( 'autoship_create_scheduled_order_link_invalid',
  __( 'The supplied link is no longer valid or has expired.', "autoship" ),
  $link );

  do_action( 'autoship_initiate_create_scheduled_order_link_handler', $link );

  if ( !isset( $link['customer_id'] ) )
  return new WP_Error( 'invalid_or_expired_link', $general_notice );

  $customer_id = $link['customer_id'];
  $args = array();

  // Check for frequency
  if ( isset( $link['frequency'] ) )
  $args['frequency'] = $link['frequency'];

  // Check for frequency type
  if ( isset( $link['frequency_type'] ) )
  $args['frequencyType'] = $link['frequency_type'];

  // Check for frequency type
  if ( isset( $link['next_occurrence'] ) )
  $args['nextOccurrenceUtc'] = $link['next_occurrence'];

  // Check for Products / Line Items
  if ( isset( $link['products'] ) && !empty( $link['products'] ) ){

    // Setup the Query Params for the QPilot call
    // By default we search for only those ids that are enabled to be added to an order.
    $valid_products = apply_filters( 'autoship_create_scheduled_order_endpoint_valid_products_params',
    array( 'productIds' => array_keys( $link['products'] ) ), $link );

    // Make the Search call
    $available_products = autoship_search_available_products( $valid_products );

    if ( !is_wp_error( $available_products ) && !empty( $available_products ) ){

      // Loop through the QPilot products and attach any needed info.
      $args['scheduledOrderItems'] = array();
      foreach ($available_products as $key => $value) {

        if ( !isset( $link['products'][$value->id] ) )
        continue;

        // Grab the prices - runs through the custom filter
        $prices = autoship_get_product_prices( $value->id );

        // Create the min-needed for a line item
        // Devs can adjust on the fly using URL params
        $line = apply_filters('autoship_create_scheduled_order_endpoint_item', array(
          'productId' => $value->id,
          'price'     => $prices['regular_price'],
          'salePrice' => $prices['autoship_recurring_price'],
          'quantity'  => $link['products'][$value->id]
        ), $link );

        // Check for Min Cycle Data
        if ( isset( $link['min'] ) && is_array( $link['min'] ) ){
          $line['minCycles'] = isset( $link['min'][$value->id] ) ? $link['min'][$value->id] : NULL;
        } else if ( isset( $link['min'] ) ){
          $line['minCycles'] = $link['min'];
        }

        // Check for Max Cycle Data
        if (  isset( $link['max'] ) && is_array( $link['max'] ) ){
          $line['maxCycles'] = isset( $link['max'][$value->id] ) ? $link['max'][$value->id] : NULL;
        } else if ( isset( $link['max'] ) ){
          $line['maxCycles'] = $link['max'];
        }

        $args['scheduledOrderItems'][] = $line;

      }

    }

  }

  // Allow a final filter for last adjustments & customizations
  $args = apply_filters( 'autoship_create_scheduled_order_endpoint_order_args', $args, $link );

  // Make the call to create the order.
  $result = autoship_create_scheduled_order( $customer_id, $args );

  return $result;
}

/**
 * Processes the Add to Scheduled Order Link Data
 * @param array $link {
 *          The link param in an array
 *          @type int|array $products       Array of woocommerce simple or variation ids or single id
 *          @type int       $qty            The qty to add
 *          @type int       $min            The min cycles
 *          @type int       $max            The max cycles
 *          @type int       $frequency      The frequency
 *          @type string    $frequency_type The frequency type
 *          @type int       $order          The Autoship Scheduled Order ID
 *          @type string    $coupon         The coupon code
 *          @type string    $customer_id    The WC Customer ID hashed
 *          @type float     $discount       The discount amount ( SUPPLIED AS NULL )
 * }
 *
 * @return bool|WP_Error  True on Success, False if no scheduled order exists,
 *                        WP_Error on failure
 */
function autoship_add_to_scheduled_order_link_handler( $link ){

  // The General notice is used for any link failures not related to coupons
  $general_notice = apply_filters( 'autoship_add_to_scheduled_order_link_invalid',
  __( 'The supplied link is no longer valid or has expired.', "autoship" ),
  $link );

  do_action( 'autoship_initiate_add_to_scheduled_order_link_handler', $link );

  // Check for Products / Line Items & validate them
  if ( isset( $link['products'] ) && !empty( $link['products'] ) ){

    $products = is_array( $link['products'] ) ? array_keys( $link['products'] ) : array( $link['products'] );

    // Setup the Query Params for the QPilot call
    // By default we search for only those ids that are enabled to be added to an order.
    $valid_products = apply_filters( 'autoship_add_to_scheduled_order_endpoint_valid_products_params',
    array( 'productIds' => $products ), $link );

    // Make the Search call
    $available_products = autoship_search_available_products( $valid_products );

    if ( !is_wp_error( $available_products ) && !empty( $available_products ) ){

      // Loop through the QPilot products and attach any needed info.
      $link['items'] = array();
      foreach ($available_products as $key => $value) {

        if ( ( is_array( $link['products'] ) && !isset( $link['products'][$value->id] ) ) || ( !is_array( $link['products'] ) && $link['products'] != $value->id ) )
        continue;

        // Grab the prices - runs through the custom filter
        $prices = autoship_get_product_prices( $value->id );

        // Create the min-needed for a line item
        // Devs can adjust on the fly using URL params
        $line = apply_filters('autoship_create_scheduled_order_endpoint_item', array(
          'productId' => $value->id,
          'price'     => $prices['regular_price'],
          'salePrice' => $prices['autoship_recurring_price'],
          'quantity'  => is_array( $link['products'] ) ? $link['products'][$value->id] : $link['qty']
        ), $link );

        // Check for Min Cycle Data
        if ( isset( $link['min'] ) && is_array( $link['min'] ) ){
          $line['minCycles'] = isset( $link['min'][$value->id] ) ? $link['min'][$value->id] : NULL;
        } else if ( isset( $link['min'] ) ){
          $line['minCycles'] = $link['min'];
        }

        // Check for Max Cycle Data
        if (  isset( $link['max'] ) && is_array( $link['max'] ) ){
          $line['maxCycles'] = isset( $link['max'][$value->id] ) ? $link['max'][$value->id] : NULL;
        } else if ( isset( $link['max'] ) ){
          $line['maxCycles'] = $link['max'];
        }

        // Validate Coupon and get discount
        if ( isset( $link['coupon'] ) && !empty( $link['coupon'] ) ){

          $coupon_notice = apply_filters( 'autoship_add_to_scheduled_order_link_invalid_coupon',
          __( 'The supplied discount link is no longer valid or has expired.', "autoship" ),
          $link );

          // Grab the new discount price since there's a coupon
          $link['discount'] = autoship_validate_and_get_autoship_coupon_discount( $link['coupon'], $line['productId'], apply_filters( 'autoship_add_to_scheduled_order_link_coupon_base_price', $line['price'], $line['salePrice'], $line, $link ), $line['quantity'], $line['minCycles'], $line['maxCycles'], $link['customer_id'] );

          if ( is_wp_error( $link['discount'] ) )
          return new WP_Error( 'invalid_or_expired_discount', $coupon_notice );

          // Set the sale price to the new discount price if it exists
          $line['salePrice'] = $link['discount'];

        }

        $link['items'][] = $line;

      }

    }

  }

  // If there are no valid items to add bail.
  if ( !isset( $link['items'] ) || empty( $link['items'] ) )
  return new WP_Error( 'invalid_or_expired_link', $general_notice );

  // Allow Filters to adjust link data on the fly
  $link = apply_filters( 'autoship_add_to_scheduled_order_link_values', $link, $products );

  // Check if a specific order number is specified or a freq and frequency type.
  // Else we add to the next order.
  if ( ( isset( $link['order'] ) && !empty( $link['order'] ) ) ||
       ( isset( $link['frequency'] ) || isset( $link['frequency_type'] ) ) ) {

    // Use specific order if specified.
    if ( isset( $link['order'] ) && !empty( $link['order'] ) ){

      // Since a specific id is supplied we need to validate it belongs to this user.
      $order = autoship_get_scheduled_order( $link['order'] );

      // Bail if no next order exists
      // Also Check the customer info if an order is found.
      if ( is_wp_error( $order ) || ( $order->customerId != $link['customer_id'] ) )
      return false;

    } else {

      // Check the Frequency and Frequency Type and if populated we need to get the next order that matches.
      $type_id = autoship_get_frequencytype_int ( $link['frequency_type'] );

      // Bail if the frequency type doesn't exist
      if ( false === $type_id )
      return new WP_Error( 'invalid_or_expired_link', $general_notice );

      // Get the next scheduled order
      $order = autoship_get_next_scheduled_order( $link['customer_id'], $link['frequency'], $type_id );

      // Bail if no next order exists
      if ( is_wp_error( $order ) )
      return false;

    }

    // Now add the items via the api
    $updated = autoship_create_scheduled_order_items( $order->id, $link['items'] );

    // Since the function returns true on success or WP_Error on false
    // Check it.
    $updated = is_wp_error( $updated ) ? $updated : $order->id;

  } else {

    // Call Autoships add to next scheduled order.
    $updated = autoship_update_next_scheduled_order_with_items ( $link['customer_id'], $link['items'] );

    // Special catch in case next order doesn't exist.
    if ( is_wp_error( $updated ) )
    return false;

  }

  return $updated;
}

/* Post Validation Filters
====================================== */

/**
 * Validates the Required Shipping Address fields.
 * @param WP_Error|bool The current validation status.
 * @param array $valids The current valid fields being submitted.
 * @param array $fields The address field list
 */
function autoship_validate_scheduled_order_shipping_address_required_fields ( $validation, $valids, $fields ){

  if ( !is_wp_error( $validation ) ){

    $missing = array();
    foreach ($fields as $key => $value) {
      if ( $value['required'] && ( !isset( $valids[$key] ) || empty( $valids[$key] ) ) )
      $missing[$key] = $value['label'];
    }

    if ( !empty( $missing ) ){
      $validation = new WP_Error( 'missing_shipping_address_fields', sprintf( __( "The following Shipping Address fields are required and missing or are invalid: %s.", "autoship" ), implode(', ', $missing ) ) );
    }

  }

  return $validation;

}
add_filter('autoship_scheduled_order_validate_shipping_address_required_posted_fields', 'autoship_validate_scheduled_order_shipping_address_required_fields', 10, 3 );

/* Post Handler Wrappers
====================================== */

/**
 * Updates a Scheduled Order Shipping Address via POST
 * @see autoship_update_scheduled_order_handler
 */
function autoship_update_scheduled_order_shipping_address_post_wrapper (){

  // Verify action
  if ( ! isset( $_REQUEST['autoship_update_order_shipping_address'] ) ) {
    return;
  }

	wc_nocache_headers();

  // Validate Nonce and get order id.
  $nonce = $_REQUEST['autoship-update-scheduled-order-shipping-address-nonce'];
  $autoship_order_id = autoship_update_scheduled_order_post_valid_nonce_id( $nonce, 'autoship-update-scheduled-order-shipping-address' );

  if ( !$autoship_order_id )
  return;

  // Check for the country
  $country = isset( $_POST['country'] ) ? sanitize_text_field( $_POST['country'] ) : NULL;
  $base = !empty( $country ) ? array( 'country' => $country ) : array();

  // If the current country isn't valid add an error and bail.
  $validation = $success = true;
	if ( ! array_key_exists( $country, WC()->countries->get_countries() ) ){

    $validation = new WP_Error( 'invalid_shipping_address_values', __( "The selected Shipping Address country field is invalid.", "autoship" ) );

    $messages = apply_filters( 'autoship_update_scheduled_order_shipping_address_invalid_country_notice', $validation->get_error_messages(), array( 'country' => $country ) );

    // Add our general error notice
    foreach ( $messages as $code => $message)
    wc_add_notice( $message, 'error' );

    // Allow others to add on additional invalid notices
    do_action( 'autoship_update_scheduled_order_shipping_address_invalid_country_field', array( 'country' => $country ) );

    $success = false;

  }

  if ( $success ){

    // Retrieve the Form Fields to Check
    // Country must be used from the submitted address
    // Else Base Country will be used.
    $fields = autoship_shipping_address_form_fields( $base );
    $values = $valids = $invalids = array();

    // Get posted values
    foreach ( $fields as $key => $data ) {

      if ( isset( $_POST[$key] ) ){

  			// Get Value & clean based on type.
  			$value = 'checkbox' === $data['type'] ?
  		  (int) isset( $_POST[ $key ] ) : wc_clean( wp_unslash( $_POST[ $key ] ) );

        // format the special fields ( i.e. postcode )
        switch ($key) {

          case 'postcode':

            // Get the formatted postal code
            $value = wc_format_postcode( $value, $country );

            // If empty string or valid then add it to our values
            if ( '' === $value || WC_Validation::is_postcode( $value, $country ) )
            $values[$key] = $value;

            break;
          case 'phone':

            // If empty string or valid then add it to our values
            if ( '' === $value || WC_Validation::is_phone( $value ) )
            $values[$key] = $value;

            break;
          case 'email':

            $value = strtolower( $value );

            // If valid then add it to our values
            if ( is_email( $value ) )
            $values[$key] = $value;

            break;

          default:
            $values[$key] = $value;
            break;

        }

      }

    }

    // Allow posted values to be sanitized & formatted
    $values = apply_filters( 'autoship_scheduled_order_sanitize_shipping_address_post_fields', $values, $fields );

    // Validate posted field values
    $valids = apply_filters( 'autoship_scheduled_order_validate_shipping_address_post_field', $values, $fields );

    //Get Invalids
    foreach ($values as $key => $value){
      if ( !isset( $valids[$key] ) )
      $invalids[$key] = $value;
    }

    // Check for Invalid
    $validation = true;
    if ( !empty( $invalids ) )
    $validation = new WP_Error( 'invalid_shipping_address_values', __( "One or more of the entered Shipping Address fields are invalid.", "autoship" ) );

    $validation = apply_filters( 'autoship_scheduled_order_validate_shipping_address_required_posted_fields', $validation, $valids, $fields );

    if ( is_wp_error( $validation ) ){

      $messages = apply_filters( 'autoship_update_scheduled_order_shipping_address_invalid_notices', $validation->get_error_messages(), $invalids, $fields );

      // Add our general error notice
      foreach ( $messages as $code => $message)
      wc_add_notice( $message, 'error' );

      // Allow others to add on additional invalid notices
      do_action( 'autoship_update_scheduled_order_shipping_address_invalid_fields', $invalids, $fields );

      $success = false;

    } else {

      // Pre-flight & get Scheduled Order from cache otherwise pull it for processing
      $original_autoship_order = autoship_maybe_retrieve_order_from_cache( $autoship_order_id );

      $success = autoship_update_scheduled_order_handler(
        $autoship_order_id,
        'autoship_update_order_shipping_address',
        array(  'shipping_address' => $valids,
                'original_autoship_order' => $original_autoship_order )
      );

    }

  }

  // If update failed include url action so form can be displayed.
  $url = is_wp_error( $success ) || !$success ?
  add_query_arg( 'action', 'edit-shipping-address', autoship_get_scheduled_order_url( $autoship_order_id ) ) : autoship_get_scheduled_order_url( $autoship_order_id );

  wp_redirect( $url );
  exit();

}

/**
 * Updates a Scheduled Order Schedule ( Frequency & Next Occurrence ) via POST
 * @see autoship_update_scheduled_order_handler
 */
function autoship_update_scheduled_order_schedule_post_wrapper (){

  // Verify action
  if ( ! isset( $_REQUEST['autoship_update_order_schedule'] ) ) {
    return;
  }

	wc_nocache_headers();

  // Validate Nonce and get order id.
  $nonce = $_REQUEST['autoship-update-scheduled-order-schedule-nonce'];
  $autoship_order_id = autoship_update_scheduled_order_post_valid_nonce_id( $nonce, 'autoship-update-scheduled-order-schedule' );

  if ( !$autoship_order_id )
  return;

  if ( !isset( $_POST['autoship_order_frequency'] ) || empty( $_POST['autoship_order_frequency'] ) || !isset( $_POST['autoship_next_occurrence'] ) || empty( $_POST['autoship_next_occurrence'] )){

    wc_add_notice( __( sprintf( 'Invalid or Missing %s information.', autoship_translate_text( 'Scheduled Order' ) ), 'autoship' ), 'error' );
    return;

  } else {


    // By default Frequency is submitted as frequency_type:frequency but this can be modifeid.
    // Expects the filter return value to be array with frequnecy type as first key pair value and frequency as second pair value.
    // key is ignored.
    $frequency = apply_filters(    'autoship_update_order_frequency_value_formatted' ,
                                    explode( ':' , $_POST['autoship_order_frequency'] ),
                                    $_POST['autoship_order_frequency'],
                                    $autoship_order_id );

    // Take the input value of Y-m-d, convert it to UTC in the API format and adjust the time to fit in the
    // processing window if it exists.
    $next_occurrence = apply_filters('autoship_update_order_next_occurrence_value_formatted' ,
                                    autoship_get_api_formatted_date ( $_POST['autoship_next_occurrence'], 'Y-m-d', true ),
                                    $_POST['autoship_next_occurrence'],
                                    $autoship_order_id );

    // Pre-flight & get Scheduled Order from cache otherwise pull it for processing
    $original_autoship_order = autoship_maybe_retrieve_order_from_cache( $autoship_order_id );

    $success = autoship_update_scheduled_order_handler(
      $autoship_order_id,
      'autoship_update_order_schedule',
      array(  'frequency_type' => $frequency[0],
              'frequency' => $frequency[1],
              'next_occurrence' => $next_occurrence,
              'original_autoship_order' => $original_autoship_order )
    );

  }

  return;

}

/**
 * Updates a Scheduled Order Items via POST
 * @see autoship_update_scheduled_order_handler
 */
function autoship_update_scheduled_order_items_post_wrapper (){

  // Verify action
  if ( !isset( $_REQUEST['autoship_update_schedule_items'] ) ) {
    return;
  }

	wc_nocache_headers();

  // Validate Nonce and get order id.
  $nonce = $_REQUEST['autoship-update-scheduled-order-items-nonce'];
  $autoship_order_id = autoship_update_scheduled_order_post_valid_nonce_id( $nonce, 'autoship-update-scheduled-order-items' );

  if ( !$autoship_order_id )
  return;

  // Pre-flight & get Scheduled Order from cache otherwise pull it for processing
  $original_autoship_order = autoship_maybe_retrieve_order_from_cache( $autoship_order_id );

  $success = autoship_update_scheduled_order_handler(
    $autoship_order_id,
    'autoship_update_schedule_items',
    array( 'order_items' => isset( $_POST['autoship_scheduled_order_items'] ) ? $_POST['autoship_scheduled_order_items'] : array(),
           'new_order_items' => isset( $_POST['autoship_scheduled_order_add_items'] ) ? $_POST['autoship_scheduled_order_add_items'] : array(),
           'original_autoship_order' => $original_autoship_order )
  );

  return;

}

/**
 * Removes a Scheduled Order Item via POST
 * @see autoship_update_scheduled_order_handler
 */
function autoship_remove_scheduled_order_item_post_wrapper (){

  // Verify action
  if ( !isset( $_REQUEST['autoship_remove_order_item'] ) ) {
    return;
  }

	wc_nocache_headers();

  // Validate Nonce and get order id.
  $nonce = $_REQUEST['autoship-remove-scheduled-order-item-nonce'];
  $autoship_order_id = autoship_update_scheduled_order_post_valid_nonce_id( $nonce, 'autoship-remove-scheduled-order-item-nonce' );

  if ( !$autoship_order_id )
  return;

  $item_id = absint( $_POST['remove-scheduled-order-item'] );

  if ( empty( $item_id ) ){

    wc_add_notice( __( sprintf( 'Invalid or Missing %s Item Information.', autoship_translate_text( 'Scheduled Order' ) ), 'autoship' ), 'error' );
    return;

  }

  // Pre-flight & get Scheduled Order from cache otherwise pull it for processing
  $original_autoship_order = autoship_maybe_retrieve_order_from_cache( $autoship_order_id );

  $success = autoship_update_scheduled_order_handler(
    $autoship_order_id,
    'autoship_remove_order_item',
    array( 'autoship_scheduled_order_item_id' => $item_id,
           'original_autoship_order' => $original_autoship_order )
  );

  return;

}

/**
 * Updates a Scheduled Order Payment Method via POST
 * Calls {@see autoship_update_scheduled_order_handler}
 */
function autoship_update_scheduled_order_payment_method_post_wrapper (){

  // Verify action
  if ( !isset( $_REQUEST['autoship_update_order_payment_method'] ) ) {
    return;
  }

	wc_nocache_headers();

  // Validate Nonce and get order id.
  $nonce = $_REQUEST['autoship-update-scheduled-order-payment-nonce'];
  $autoship_order_id = autoship_update_scheduled_order_post_valid_nonce_id( $nonce, 'autoship-update-scheduled-order-payment' );

  if ( !$autoship_order_id )
  return;

  if ( !isset( $_POST['autoship_order_payment_method'] ) ){

    wc_add_notice( __( sprintf( 'Invalid or Missing %s Payment Method information.', autoship_translate_text( 'Scheduled Order' ) ), 'autoship' ), 'error' );
    return;

  } else if ( isset( $_POST['autoship_update_order_payment_method'] ) ){

    // Pre-flight & get Scheduled Order from cache otherwise pull it for processing
    $original_autoship_order = autoship_maybe_retrieve_order_from_cache( $autoship_order_id );

    $success = autoship_update_scheduled_order_handler(
      $autoship_order_id,
      'autoship_update_order_payment_method',
      array(  'autoship_order_payment_method' => $_POST['autoship_order_payment_method'],
              'original_autoship_order' => $original_autoship_order )
    );

  }

  return;

}

/**
 * Updates a Scheduled Order Coupon via POST
 * @see autoship_update_scheduled_order_handler
 */
function autoship_update_scheduled_order_coupon_post_wrapper (){

  // Verify action
  if ( !isset( $_REQUEST['autoship_update_order_coupon'] ) ) {
    return;
  }

	wc_nocache_headers();

  // Validate Nonce and get order id. Uses same nonce as update order items.
  $nonce = $_REQUEST['autoship-update-scheduled-order-items-nonce'];
  $autoship_order_id = autoship_update_scheduled_order_post_valid_nonce_id( $nonce, 'autoship-update-scheduled-order-items' );

  if ( !$autoship_order_id )
  return;

  // Pre-flight & get Scheduled Order from cache otherwise pull it for processing
  $original_autoship_order = autoship_maybe_retrieve_order_from_cache( $autoship_order_id );

  $success = autoship_update_scheduled_order_handler(
    $autoship_order_id,
    'autoship_add_order_coupon',
    array( 'autoship_coupon_code' => $_POST['autoship_coupon_code'],
           'original_autoship_order' => $original_autoship_order )
  );

  return;

}

/**
 * Updates a Scheduled Order Shipping Rate via POST
 * Calls {@see autoship_update_scheduled_order_handler}
 */
function autoship_update_scheduled_order_preferred_shipping_rate_post_wrapper (){

  // Verify action
  if ( !isset( $_REQUEST['autoship_order_preferred_shipping_rate'] ) ) {
    return;
  }

	wc_nocache_headers();

  // Validate Nonce and get order id.
  $nonce = $_REQUEST['autoship-update-scheduled-order-preferred-shipping-rate-nonce'];
  $autoship_order_id = autoship_update_scheduled_order_post_valid_nonce_id( $nonce, 'autoship-update-scheduled-order-preferred-shipping-rate' );

  if ( !$autoship_order_id )
  return;

  if ( !isset( $_POST['autoship_order_preferred_shipping_rate'] ) ){

    wc_add_notice( __( sprintf( 'Invalid or Missing %s Shipping Rate information.', autoship_translate_text( 'Scheduled Order' ) ), 'autoship' ), 'error' );
    return;

  } else if ( isset( $_POST['autoship_order_preferred_shipping_rate'] ) ){

    // Pre-flight & get Scheduled Order from cache otherwise pull it for processing
    $original_autoship_order = autoship_maybe_retrieve_order_from_cache( $autoship_order_id );

    $success = autoship_update_scheduled_order_handler(
      $autoship_order_id,
      'autoship_update_order_preferred_shipping_rate',
      array(  'autoship_order_preferred_shipping_rate' => $_POST['autoship_order_preferred_shipping_rate'],
              'original_autoship_order' => $original_autoship_order )
    );

  }

  return;

}

/**
 * Add Update Scheduled Order Post functions
 *
 * @see autoship_update_scheduled_order_shipping_address_wrapper()
 * @see autoship_update_scheduled_order_schedule_post_wrapper()
 * @see autoship_update_scheduled_order_items_post_wrapper()
 * @see autoship_update_scheduled_order_payment_method_post_wrapper()
 * @see autoship_update_scheduled_order_shipping_rate_post_wrapper()
 * @see autoship_update_scheduled_order_coupon_post_wrapper()
 */
add_action( 'wp_loaded', 'autoship_update_scheduled_order_shipping_address_post_wrapper', 20 );
add_action( 'wp_loaded', 'autoship_update_scheduled_order_schedule_post_wrapper', 20 );
add_action( 'wp_loaded', 'autoship_update_scheduled_order_items_post_wrapper', 20 );
add_action( 'wp_loaded', 'autoship_update_scheduled_order_payment_method_post_wrapper', 20 );
add_action( 'wp_loaded', 'autoship_update_scheduled_order_preferred_shipping_rate_post_wrapper', 20 );
add_action( 'wp_loaded', 'autoship_update_scheduled_order_coupon_post_wrapper', 20 );

/* Main Scheduled Order Update Handler
====================================== */

/**
 * The main handler for form and endpoint Scheduled Order Updates.
 *
 * @param int $order_id       The Autoship Scheduled Order ID
 * @param string $action      A valid action to perform.
 * @param mixed $data         The Data necessary to perform that action.
 * @return int|bool|WP_Error  True if successful or WR_Error|false on failure.
 */
function autoship_update_scheduled_order_handler( $order_id, $action , $data = array() ) {

  $valid_actions = apply_filters( 'autoship_update_scheduled_order_handler_valid_actions', array(
    'autoship_add_order_coupon'                       => true,
    'autoship_remove_order_coupon'                    => true,
    'autoship_add_order_item'                         => true,
    'autoship_remove_order_item'                      => true,
    'autoship_update_order'                           => true,
    'autoship_delete_order'                           => true,
    'autoship_update_order_status'                    => true,
    'autoship_update_order_schedule'                  => true,
    'autoship_update_order_shipping_address'          => true,
    'autoship_update_schedule_items'                  => true,
    'autoship_update_order_payment_method'            => true,
    'autoship_update_order_preferred_shipping_rate'   => true,
    'autoship_refresh_scheduled_order'                => true,
  ), $order_id, $action , $data );

  // Retrieve the Customer ID for the Scheduled Order so we can pass it to securirty checks
  $customer_id =  isset( $data['original_autoship_order'] ) &&
                 !empty( $data['original_autoship_order'] ) &&
                  isset( $data['original_autoship_order']['customerId'] ) &&
                 !empty( $data['original_autoship_order']['customerId'] ) ?
                 $data['original_autoship_order']['customerId'] : 0;

  // Rights for use to make the update.
  // Defaults to everyone can modify.
  if ( !autoship_rights_checker( 'autoship_update_scheduled_order_rights' , array(), $customer_id ) ||
       !autoship_rights_checker( "autoship_update_scheduled_order_{$action}_rights" , array(), $customer_id )  ){
    wc_add_notice( __( 'Insufficient rights to perform this action.', 'autoship' ), 'error' );
    return false;
  }

  // Check the supplied action is valid and enabled
  if ( !isset( $valid_actions[$action] ) || !$valid_actions[$action] ){
   wc_add_notice( __( 'The supplied Action is invalid.', 'autoship' ), 'error' );
   return false;
  }

  $scheduled_order_label = autoship_translate_text( 'Scheduled Order' );

  $default_msgs = apply_filters("default_{$action}_action_messages", array(
    'error' => sprintf( __( "A problem was encountered while trying to update the %s.", 'autoship' ), $scheduled_order_label ),
    'success' => sprintf(  __("The %s was successfully updated.", 'autoship'), $scheduled_order_label . ' #%s' )
  ) );

  // Allow adjustments to the data parameter to be made.
  $data = apply_filters( 'autoship_update_scheduled_order_handler_call_back_data', $data, $order_id, $action );

  do_action( 'autoship_before_update_scheduled_order_handler', $order_id, $action , $data );

  // run the actions specific handler
  $method = apply_filters( 'autoship_update_scheduled_order_' . $action . '_handler_call_back', $action . '_action_handler', $order_id, $action , $data );
  $result = function_exists( $method ) ? $method( $order_id, $data ) : new WP_Error( 'autoship_update_scheduled_order_handler_error', sprintf( __("The %s could not be updated since the supplied action could not be processed.", 'autoship' ), $scheduled_order_label ) );

  // Check if error
  if ( is_wp_error( $result ) ){
    do_action( "autoship_after_{$action}_handler_failure", $order_id, $action , $data, $result );
    wc_add_notice( __( $default_msgs['error'] . '<br/>' . $result->get_error_message(), 'autoship' ),  'error' );
    return false;
  }

  if ( !$result ){
    do_action( "autoship_after_{$action}_handler_failure", $order_id, $action , $data, $result );
    return false;
  }

  do_action( "autoship_after_{$action}_handler_success", $order_id, $action , $data );

  wc_add_notice( __( sprintf( $default_msgs['success'], $order_id ) , 'autoship' ), 'success' );

  do_action( "autoship_after_handler_success", $order_id, $action , $data );

  return true;

}

/* INDIVIDUAL SCHEDULED ORDER ACTION FUNCTIONS
/ ==========================================================

/**
 * The main handler for the autoship_delete_order action
 * Deletes the order associated with the supplied order ID
 *
 * @param int $order_id       The Autoship Scheduled Order ID
 * @param mixed $data         The Data necessary to perform this action.
 * @return int|bool|WP_Error  True if successful or WR_Error|false on failure.
 */
function autoship_delete_order_action_handler( $order_id, $data ){

  // Run the delete method
  // Method returns WP_Error on failure.
  return autoship_delete_scheduled_order( $order_id );

}

/**
 * Returns the filtered messages for the autoship_delete_order action messages
 *
 * @param array $messages The current error and success messages for the action.
 * @return array The filtered error and success messages for the action.
 */
function autoship_delete_order_action_handler_messages( $messages ){

  $scheduled_order_label = autoship_translate_text( 'Scheduled Order' );

  return array(
    'error'   => sprintf( __("A problem was encountered deleting the %s.", 'autoship'), $scheduled_order_label ),
    'success' => sprintf( __("The %s was successfully deleted.", 'autoship'), $scheduled_order_label . ' #%s' )
  );
}
add_filter( 'default_autoship_delete_order_action_messages', 'autoship_delete_order_action_handler_messages', 10, 1 );

/**
 * The main handler for the autoship_update_order_status action
 * Updates the Status for the supplied order ID
 *
 * @param int $order_id       The Autoship Scheduled Order ID
 * @param mixed $data         The Data necessary to perform this action.
 * @return int|bool|WP_Error  True if successful or WR_Error|false on failure.
 */
function autoship_update_order_status_action_handler( $order_id, $data ){

  //Validate the status being applied
  $valid_statuses = autoship_get_scheduled_order_statuses ();

  if ( !isset( $valid_statuses[$data['status']] ) ) {

    wc_add_notice( __( sprintf( 'Invalid %s Status.', autoship_translate_text( 'Scheduled Order' ) ), 'autoship' ), 'error' );
    return false;

  }

  // Run the Set method
  // Method returns WP_Error on failure.
  return autoship_set_scheduled_order_status ( $order_id, $data['status'] );

}

/**
 * Returns the filtered messages for the autoship_update_order_status action messages
 *
 * @param array $messages The current error and success messages for the action.
 * @return array The filtered error and success messages for the action.
 */
function autoship_update_order_status_action_handler_messages( $messages ){

  $scheduled_order_label = autoship_translate_text( 'Scheduled Order' );

  return array(
    'error'   => sprintf( __( "A problem was encountered updating the %s's Status.", 'autoship'), $scheduled_order_label ),
    'success' => sprintf( __( "The status for %s was successfully updated.", 'autoship'), $scheduled_order_label . ' #%s' )
  );

}
add_filter( 'default_autoship_update_order_status_action_messages', 'autoship_update_order_status_action_handler_messages', 10, 1 );

/**
 * The main handler for the autoship_update_order_shipping_address action
 * Updates the Shipping Address for the supplied order ID
 *
 * @param int $order_id       The Autoship Scheduled Order ID
 * @param mixed $data         The Data necessary to perform this action.
 * @return int|bool|WP_Error  True if successful or WR_Error|false on failure.
 */
function autoship_update_order_shipping_address_action_handler( $order_id, $data ){

  //Verify it needs to be updated if possible
  if ( isset( $data['original_autoship_order'] ) && !empty( $data['original_autoship_order'] ) ) {

    // Check to see if the address should be updated.
    $current_address = autoship_order_address_values( $data['original_autoship_order'], 'shipping' );

    $changed = false;
    // Run through the current Scheduled Order Address Fields and look for changes.
    foreach ( $current_address as $key => $value) {

      if ( isset( $data['shipping_address'][$key] ) && ( $value != $data['shipping_address'][$key] ) ){
        $changed = true;
        break;
      }

    }

    // If none of the existing values changed and no new fields were added then no-change.
    if ( !$changed && ( array_merge( $current_address, $data['shipping_address'] ) == count( $current_address ) ) ){

      do_action( 'autoship_after_update_scheduled_order_shipping_address_handler_nochange', $order_id, $action , $data );
      wc_add_notice( __( sprintf( 'No changes updated for %s #%s.', autoship_translate_text( 'Scheduled Order' ), $order_id ), 'autoship' ), 'notice' );
      return true;

    }

  }

  if ( !isset( $data['original_autoship_order'] ) || empty( $data['original_autoship_order'] ) ){
  $data['original_autoship_order'] = autoship_maybe_retrieve_order_from_cache( $order_id ); }

  // Run the update method
  // Method returns WP_Error on failure.
  return autoship_set_scheduled_order_shipping_address ( $data['original_autoship_order'], $data['shipping_address'] );

}

/**
 * Returns the filtered messages for the autoship_update_order_shipping_address action messages
 *
 * @param array $messages The current error and success messages for the action.
 * @return array The filtered error and success messages for the action.
 */
function autoship_update_order_shipping_address_action_handler_messages( $messages ){

  $scheduled_order_label = autoship_translate_text( 'Scheduled Order' );

  return array(
      'error'   => sprintf( __( "A problem was encountered updating the %s's Shipping Address.", 'autoship'), $scheduled_order_label ),
      'success' => sprintf( __( "The shipping address for %s was successfully updated.", 'autoship'), $scheduled_order_label . ' #%s' )
  );
}
add_filter( 'default_autoship_update_order_shipping_address_action_messages', 'autoship_update_order_shipping_address_action_handler_messages', 10, 1 );

/**
 * The main handler for the autoship_update_order_schedule action
 * Updates the Schedule ( Frequency, Frequency Type, and/or Next Occurrence ) for the supplied order ID
 *
 * @param int $order_id       The Autoship Scheduled Order ID
 * @param mixed $data         The Data necessary to perform this action.
 * @return int|bool|WP_Error  True if successful or WR_Error|false on failure.
 */
function autoship_update_order_schedule_action_handler( $order_id, $data ){

  $scheduled_order_label = autoship_translate_text( 'Scheduled Order' );

  //Verify it needs to be updated if possible
  $frequency_unchanged = $frequency_type_unchanged = $next_occurrence_unchanged = false;
  if ( isset( $data['original_autoship_order'] ) && !empty( $data['original_autoship_order'] ) ) {

    // Check to see if the frequency should be updated.
    $frequency_unchanged       = $data['frequency']       == $data['original_autoship_order']['frequency'];
    $frequency_type_unchanged  = $data['frequency_type']  == $data['original_autoship_order']['frequencyType'];
    $next_occurrence_unchanged = $data['next_occurrence'] == $data['original_autoship_order']['nextOccurrenceUtc'];

    if ( $frequency_unchanged && $frequency_type_unchanged && $next_occurrence_unchanged ){

      do_action( 'autoship_after_update_scheduled_order_schedule_handler_nochange', $order_id, $action , $data );
      wc_add_notice( __( sprintf( 'No changes updated for %s #%s.', $scheduled_order_label, $order_id ), 'autoship' ), 'notice' );
      return true;

    }

  }

  $error = '';
  $updated = $result = false;
  if ( !$frequency_unchanged || !$frequency_type_unchanged ){

    // Run the Set method
    // Method returns WP_Error on failure.
    $result   = autoship_set_scheduled_order_frequency ( $order_id, $data['frequency_type'], $data['frequency'] );
    $error    = __( sprintf( "A problem was encountered updating the %s's Frequency.", $scheduled_order_label ), 'autoship');
    $updated  = !is_wp_error( $result );

  }

  if ( !$next_occurrence_unchanged && !is_wp_error( $result ) ){

    // Run the Set method
    // Method returns WP_Error on failure.
    $result = update_scheduled_order_next_occurrence ( $order_id, $data['next_occurrence'] );
    $error = $updated ? sprintf( __( "The %s's Frequency was updated successfully, however, there was a problem updating the Next Occurrence Date.", 'autoship'), $scheduled_order_label ) :
    sprintf( __("A problem was encountered updating the %s's Next occurrence Date.", 'autoship'), $scheduled_order_label );

  }

  // Dynamically the error message to the actions message filter.
  if ( !empty( $error ) )
  add_filter( 'default_autoship_update_order_schedule_action_messages', function( $messages ) { return array_merge( $messages, array(
    'error' => $error,
  )); }, 11, 1 );

  return $result;

}

/**
 * Returns the filtered messages for the autoship_update_order_schedule action messages
 *
 * @param array $messages The current error and success messages for the action.
 * @return array The filtered error and success messages for the action.
 */
function autoship_update_order_schedule_action_handler_messages( $messages ){

  $scheduled_order_label = autoship_translate_text( 'Scheduled Order' );

  return array(
    'error'   => sprintf( __( "A problem was encountered updating the %s's schedule.",'autoship'), $scheduled_order_label ),
    'success' => sprintf( __( "The schedule for %s was successfully updated.",'autoship'), $scheduled_order_label . ' #%s' )
  );
}
add_filter( 'default_autoship_update_order_schedule_action_messages', 'autoship_update_order_schedule_action_handler_messages', 10, 1 );

/**
 * The main handler for the autoship_update_schedule_items action
 * Updates the Scheduled Items for the supplied order ID
 *
 * @param int $order_id       The Autoship Scheduled Order ID
 * @param mixed $data         The Data necessary to perform this action.
 * @return int|bool|WP_Error  True if successful or WR_Error|false on failure.
 */
function autoship_update_schedule_items_action_handler( $order_id, $data ){

  // Get Current Scheduled Order items &
  // Verify it needs to be updated if possible
  $original_autoship_order_items = $updated_autoship_order_items = array();
  if ( isset( $data['original_autoship_order'] ) && !empty( $data['original_autoship_order'] ) ) {

    $original_order_items = $data['original_autoship_order']['scheduledOrderItems'];

    // Flip supplied ids for better lookup.
    $item_ids     = array_flip( array_keys( $data['order_items'] ) );

    // Iterate through the scheduled order items and unset any
    // not requested.
    foreach ($original_order_items as $key => $item) {

      if ( empty( $item_ids ) || isset( $item_ids[$item['id']] ) )
      $original_autoship_order_items[$item['id']] = $original_order_items[$key];

    }

  } else {

    $original_autoship_order_items = autoship_get_scheduled_order_items( $order_id, array_keys( $data['order_items'] ) );
    $original_autoship_order_items = autoship_convert_object_to_array( $original_autoship_order_items );

  }

  // Check changes are actual changes and if so init updates.
  foreach ( $data['order_items'] as $item_id => $item) {

    $change = false;
    $temp = $original_autoship_order_items[$item_id];

    // Insert item value update code ( need to make extendable )
    if ( isset($item['qty']) && $original_autoship_order_items[$item_id]['quantity'] != $item['qty'] ){

      $change = true;
      $temp['quantity'] = $item['qty'];

    }

    if ( isset( $item['price'] ) && ( $original_autoship_order_items[$item_id]['price'] != $item['price'] ) ){

      $change = true;
      $temp['price'] = $item['price'];

    }

    if ( isset( $item['sale_price'] ) && ( $original_autoship_order_items[$item_id]['salePrice'] != $item['sale_price'] ) ){

      $change = true;
      $temp['salePrice'] = $item['sale_price'];

    }

    // If there was indeed a change then add it to updated items.
    if ( apply_filters( 'autoship_update_schedule_items_changed_made',
    $change,
    $original_autoship_order_items[$item_id],
    $item ) )
    $updated_autoship_order_items[$item_id] = $temp;

  }

  /**
  * Allow devs to modify item changes on the fly.
  */
  $updated_autoship_order_items = apply_filters(
    'autoship_update_schedule_items_changes',
    $updated_autoship_order_items,
    $original_autoship_order_items,
    $order_id, 'autoship_update_schedule_items' , $data
  );

  /**
  * Now update any existing order items if necessary.
  */
  if ( !empty( $updated_autoship_order_items ) ){

    // Run the Set method
    // Method returns WP_Error on failure.
    $result = autoship_set_scheduled_order_items ( $order_id, $updated_autoship_order_items );

  }

  // Check for new items
  $new_autoship_order_items = array();
  if ( ( empty( $updated_autoship_order_items ) || ( !empty( $updated_autoship_order_items ) && !is_wp_error( $result ) ) ) &&
        !empty( $data['new_order_items'] ) ){

    // Get Current Scheduled Order Frequency and FrequencyType needed for new items
    $original_frequency = $original_type = '';
    if ( !isset( $data['original_autoship_order'] ) || empty( $data['original_autoship_order'] ) ){
    $data['original_autoship_order'] = autoship_maybe_retrieve_order_from_cache( $order_id ); }

    // No problems getting the original order
    if ( !is_wp_error( $data['original_autoship_order'] ) ){

      foreach ( $data['new_order_items'] as $item_ids => $item_data) {

        // Item ids are QPilot id - External ID
        $ids = explode( '-', $item_ids );

        // Get the Product Data associated with the item.
        $new_autoship_order_items[] = autoship_create_scheduled_order_item_data (
          $order_id,
          $ids[0],
          $ids[1],
          $item_data['qty'],
          $data['original_autoship_order']['frequencyType'],
          $data['original_autoship_order']['frequency'] );

      }

      /**
      * Allow devs to modify item changes on the fly.
      */
      $new_autoship_order_items = apply_filters(
        'autoship_update_schedule_new_items_changes',
        $new_autoship_order_items,
        $order_id, 'autoship_update_schedule_items' , $data
      );

      // Now add the items via the api
      $result = autoship_create_scheduled_order_items( $order_id, $new_autoship_order_items );

    }

  }

  $scheduled_order_label = autoship_translate_text( 'Scheduled Order' );

  // Check if no change is being made
  if ( empty( $updated_autoship_order_items ) && empty( $new_autoship_order_items ) ){

    do_action( 'autoship_after_update_schedule_items_handler_nochange', $order_id, 'autoship_update_schedule_items' , $data );
    wc_add_notice( __( sprintf( 'No changes updated for %s #%s.', $scheduled_order_label, $order_id ) , 'autoship' ), 'notice' );
    return true;

  }

  // Dynamically adjust notice based on what action was being performed.
  $new_messages = '';
  if ( !empty( $updated_autoship_order_items ) && !empty( $new_autoship_order_items ) ){

    $new_messages = array(
      'error'   => sprintf( __( "A problem was encountered updating the %s's items.", 'autoship'), $scheduled_order_label ),
      'success' => sprintf( __( "The items for %s were successfully updated.", 'autoship'), $scheduled_order_label . ' #%s' )
    );

  } else if (  !empty( $new_autoship_order_items ) ){

    $new_messages = array(
      'error'   => sprintf( __( "A problem was encountered Adding the new %s's items.", 'autoship'), $scheduled_order_label ),
      'success' => sprintf( __( "The items for %s were successfully added.", 'autoship'), $scheduled_order_label . ' #%s' )
    );

  }

  if ( !empty( $new_messages ) )
  add_filter( 'default_autoship_update_schedule_items_action_messages', function( $messages ) { return array_merge( $messages, $new_messages ); }, 11, 1 );

  return $result;

}

/**
 * Returns the filtered messages for the autoship_update_schedule_items action messages
 *
 * @param array $messages The current error and success messages for the action.
 * @return array The filtered error and success messages for the action.
 */
function autoship_update_schedule_items_action_handler_messages( $messages ){

  $scheduled_order_label = autoship_translate_text( 'Scheduled Order' );

  return array(
    'error'   => sprintf( __( "A problem was encountered updating the %s's items.",'autoship'), $scheduled_order_label ),
    'success' => sprintf( __( "The items for %s were successfully updated.",'autoship'), $scheduled_order_label . ' #%s' )
  );
}
add_filter( 'default_autoship_update_schedule_items_action_messages', 'autoship_update_schedule_items_action_handler_messages', 10, 1 );

/**
 * The main handler for the autoship_update_order_payment_method action
 * Updates the Payment Method for the supplied order ID
 *
 * @param int $order_id       The Autoship Scheduled Order ID
 * @param mixed $data         The Data necessary to perform this action.
 * @return int|bool|WP_Error  True if successful or WR_Error|false on failure.
 */
function autoship_update_order_payment_method_action_handler( $order_id, $data ){

  //Verify it needs to be updated if possible
  if ( isset( $data['original_autoship_order'] ) && !empty( $data['original_autoship_order'] ) ) {

    // Check to see if the payment method should be updated.
    if ( $data['autoship_order_payment_method'] == $data['original_autoship_order']['paymentMethodId'] ){

      do_action( 'autoship_after_update_scheduled_order_payment_method_handler_nochange', $order_id, $action , $data );
      wc_add_notice( __( sprintf( 'No changes needed for the Payment Method for %s #%s.', autoship_translate_text( 'Scheduled Order' ), $order_id ), 'autoship' ), 'notice' );
      return true;

    }

  }

  // Run the Set method
  // Method returns WP_Error on failure.
  return autoship_set_scheduled_order_payment_method ( $order_id, $data['autoship_order_payment_method'] );

}

/**
 * Returns the filtered messages for the autoship_update_order_payment_method action messages
 *
 * @param array $messages The current error and success messages for the action.
 * @return array The filtered error and success messages for the action.
 */
function autoship_update_order_payment_method_action_handler_messages( $messages ){

  $scheduled_order_label = autoship_translate_text( 'Scheduled Order' );

  return array(
    'error'   => sprintf( __( "A problem was encountered updating the %s's Payment Method.",'autoship'), $scheduled_order_label ),
    'success' => sprintf( __( "The payment method for %s was successfully updated.",'autoship'), $scheduled_order_label . ' #%s' )
  );
}
add_filter( 'default_autoship_update_order_payment_method_action_messages', 'autoship_update_order_payment_method_action_handler_messages', 10, 1 );

/**
 * The main handler for the autoship_update_order_preferred_shipping_method action
 * Updates the Shipping Rate for the supplied order ID
 *
 * @param int $order_id       The Autoship Scheduled Order ID
 * @param mixed $data         The Data necessary to perform this action.
 * @return int|bool|WP_Error  True if successful or WR_Error|false on failure.
 */
function autoship_update_order_preferred_shipping_rate_action_handler( $order_id, $data ){

  $autoship_order = autoship_get_scheduled_order( $order_id, true );
  if ( !autoship_is_valid_shipping_rate( $data['autoship_order_preferred_shipping_rate'], $autoship_order ) ){
    wc_add_notice( __( sprintf( 'The Selected Rate is no longer available for %s #%s.', autoship_translate_text( 'Scheduled Order' ), $order_id ), 'autoship' ), 'error' );
    return false;
  }

  // Run the Set method
  // Method returns WP_Error on failure.
  if ( is_wp_error( $result = autoship_set_scheduled_order_preferred_shipping_rate ( $autoship_order, $data['autoship_order_preferred_shipping_rate'] ) ) )
  return $result;

  // Since it wasn't a WP_Error it could stil have been an invalid shipping rate.
  if ( !$result ){
    wc_add_notice( __( sprintf( 'The Selected Rate is no longer available for %s #%s. The default shipping rate has been selected instead.', autoship_translate_text( 'Scheduled Order' ), $order_id ), 'autoship' ), 'error' );
    return false;
  }

  return true;

}

/**
 * Returns the filtered messages for the autoship_update_order_preferred_shipping_method action messages
 *
 * @param array $messages The current error and success messages for the action.
 * @return array The filtered error and success messages for the action.
 */
function autoship_update_order_preferred_shipping_rate_action_handler_messages( $messages ){

  $scheduled_order_label = autoship_translate_text( 'Scheduled Order' );

  return array(
    'error'   => sprintf( __( "A problem was encountered updating the %s's preferred Shipping Rate.",'autoship'), $scheduled_order_label ),
    'success' => sprintf( __( "The preferred shipping rate for %s was successfully updated.",'autoship'), $scheduled_order_label . ' #%s' )
  );
}
add_filter( 'default_autoship_update_order_preferred_shipping_rate_action_messages', 'autoship_update_order_preferred_shipping_rate_action_handler_messages', 10, 1 );

/**
 * The main handler for the autoship_remove_order_item action
 * Removes a scheduled item from the Schedule associated with the Order ID
 *
 * @param int $order_id       The Autoship Scheduled Order ID
 * @param mixed $data         The Data necessary to perform this action.
 * @return int|bool|WP_Error  True if successful or WR_Error|false on failure.
 */
function autoship_remove_order_item_action_handler( $order_id, $data ){

  // Run the Set method
  // Method returns WP_Error on failure.
  return autoship_remove_scheduled_order_item ( $order_id, $data['autoship_scheduled_order_item_id'] );

}

/**
 * Returns the filtered messages for the autoship_remove_order_item action messages
 *
 * @param array $messages The current error and success messages for the action.
 * @return array The filtered error and success messages for the action.
 */
function autoship_remove_order_item_action_handler_messages( $messages ){

  $scheduled_order_label = autoship_translate_text( 'Scheduled Order' );

  return array(
    'error'   => sprintf( __( "A problem was encountered removing the %s item.",'autoship'), $scheduled_order_label ),
    'success' => sprintf( __( "The item was successfully removed from %s.",'autoship'), $scheduled_order_label . ' #%s' )
  );
}
add_filter( 'default_autoship_remove_order_item_action_messages', 'autoship_remove_order_item_action_handler_messages', 10, 1 );

/**
 * The main handler for the autoship_add_order_coupon action
 * Adds a coupon to the Scheduled Order associated with the supplied Order ID
 *
 * @param int $order_id       The Autoship Scheduled Order ID
 * @param mixed $data         The Data necessary to perform this action.
 * @return int|bool|WP_Error  True if successful or WR_Error|false on failure.
 */
function autoship_add_order_coupon_action_handler( $order_id, $data ){

  // Check if coupon is allowed
  if ( !apply_filters( 'autoship_allow_coupon_on_order', autoship_allow_coupons(), $order_id, $data ) ){

    wc_add_notice( __( 'Coupons are currently not Allowed.', 'autoship' ), 'error' );
    return false;

  }

  $order = isset( $data['original_autoship_order'] ) && !empty( $data['original_autoship_order'] ) ? $data['original_autoship_order'] : null;

  // Run QPilot validation for the scheduled order and code then apply.
  $valid = autoship_validate_and_apply_scheduled_order_coupon( $order_id, $data['autoship_coupon_code'], $order );

  if ( !$valid ){
    wc_add_notice( __( 'The Supplied Coupon is not Valid.', 'autoship' ), 'error' );
    return false;
  }

  return $valid;

}

/**
 * Returns the filtered messages for the autoship_add_order_coupon action messages
 *
 * @param array $messages The current error and success messages for the action.
 * @return array The filtered error and success messages for the action.
 */
function autoship_add_order_coupon_action_handler_messages( $messages ){

  $scheduled_order_label = autoship_translate_text( 'Scheduled Order' );

  return array(
    'error'   => __("A problem was encountered validating and applying the coupon.",'autoship'),
    'success' => sprintf( __("The coupon was successfully added to %s.",'autoship'), $scheduled_order_label . ' #%s' ),
  );
}
add_filter( 'default_autoship_add_order_coupon_action_messages', 'autoship_add_order_coupon_action_handler_messages', 10, 1 );

/**
 * The main handler for the autoship_remove_order_coupon action
 * Removes a coupon from the Scheduled Order associated with the supplied Order ID
 *
 * @param int $order_id       The Autoship Scheduled Order ID
 * @param mixed $data         The Data necessary to perform this action.
 * @return int|bool|WP_Error  True if successful or WR_Error|false on failure.
 */
function autoship_remove_order_coupon_action_handler( $order_id, $data ){

  $order = isset( $data['original_autoship_order'] ) && !empty( $data['original_autoship_order'] ) ? $data['original_autoship_order'] : null;

  // Run QPilot validation for the scheduled order and code then apply.
  return autoship_validate_and_remove_scheduled_order_coupon( $order_id, $data['autoship_coupon_code'], $order );

}

/**
 * Returns the filtered messages for the autoship_remove_order_coupon action messages
 *
 * @param array $messages The current error and success messages for the action.
 * @return array The filtered error and success messages for the action.
 */
function autoship_remove_order_coupon_action_handler_messages( $messages ){

  $scheduled_order_label = autoship_translate_text( 'Scheduled Order' );

  return array(
    'error'   => __("A problem was encountered removing the coupon.",'autoship'),
    'success' => sprintf( __("The coupon was successfully removed from %s.",'autoship'), $scheduled_order_label . ' #%s' ),
  );
}
add_filter( 'default_autoship_remove_order_coupon_action_messages', 'autoship_remove_order_coupon_action_handler_messages', 10, 1 );

/**
 * The main handler for the autoship_refresh_scheduled_order action
 * Refreshes th data on a Scheduled Order
 *
 * @param int $order_id       The Autoship Scheduled Order ID
 * @param mixed $data         The Data necessary to perform this action.
 * @return int|bool|WP_Error  True if successful or WR_Error|false on failure.
 */
function autoship_refresh_scheduled_order_action_handler( $order_id, $data ){

  // Run the upsert method with no changes to the order
  // this refreshes the Scheduled Order data.
  $autoship_order = autoship_get_scheduled_order( $order_id, true );
  return autoship_upsert_scheduled_order( $autoship_order['customerId'], $autoship_order );

}

/**
 * Returns the filtered messages for the autoship_refresh_scheduled_order action messages
 *
 * @param array $messages The current error and success messages for the action.
 * @return array The filtered error and success messages for the action.
 */
function autoship_refresh_scheduled_order_action_handler_messages( $messages ){

  $scheduled_order_label = autoship_translate_text( 'Scheduled Order' );

  return array(
    'error'   => sprintf( __( "A problem was encountered refreshing the %s.", 'autoship'), $scheduled_order_label ),
    'success' => sprintf( __( "The Scheduled Order data for %s was successfully refreshed.", 'autoship'), $scheduled_order_label . ' #%s' ),
  );

}
add_filter( 'default_autoship_refresh_scheduled_order_action_messages', 'autoship_refresh_scheduled_order_action_handler_messages', 10, 1 );

// LOCKED ORDER SUPPORT
// ==========================================================

// Additional Check for Locked orders.
function autoship_scheduled_locked_order_api_action_excemption_handler ( $notice, $e, $order_id ){

  if ( ( '400' == $e->getCode() ) && ( strpos( $e->getMessage(), "Cannot change a locked scheduled order" ) !== false ) ){

    $notice = new WP_Error( 'User Message', __( sprintf( 'Your %s #%d has started processing. No changes can be made at this time.', autoship_translate_text( 'scheduled order' ), $order_id ), "autoship" ) );
    autoship_log_entry( __( 'Autoship Payment Methods', 'autoship' ), sprintf( 'Order #%d Payment Method Update Failed. Additional Details: Error Code %s - %s', $order_id, $e->getCode(), $e->getMessage() ) );

  }

  return $notice;

}
add_filter('autoship_scheduled_order_api_action_excemption_handler', 'autoship_scheduled_locked_order_api_action_excemption_handler', 10, 3);

// ==========================================================
// SITE ORDER SETTINGS CACHE FUNCTIONS
// ==========================================================

if ( !function_exists( 'autoship_get_site_order_settings_cache' ) ){

  /**
   * Retrieves the Transient option
   * Can be overwritten to use other caching, session, transient.
   * @see get_transient()
   */
  function autoship_get_site_order_settings_cache(){
    return get_transient( apply_filters( 'autoship_site_order_settings_cache_object_name' , 'autoship_site_order_settings_cache' ) );
  }

}

if ( !function_exists( 'autoship_save_site_order_settings_cache' ) ){

  /**
   * Saves the Transient option
   * Can be overwritten to use other caching, session, transient.
   * @see set_transient()
   */
  function autoship_save_site_order_settings_cache( $data ){
    $bool_response = set_transient( apply_filters( 'autoship_site_order_settings_cache_object_name' , 'autoship_site_order_settings_cache' ) , $data, apply_filters( 'autoship_site_order_settings_cache_max_life', 900 ) );
  }

}

// ==========================================================
// UTILILITY SCHEDULED ORDER FUNCTIONS
// ==========================================================

/**
 * Retrieves the current Autoship Site Settings
 * Checks for the cache/transient before fetching data from API
 * Cache/transient default expiration time 900 seconds
 */
function autoship_get_site_order_settings(){

  $client = autoship_get_default_client();

  // Check if data exists in the cache before making API call
  $has_data = autoship_get_site_order_settings_cache();
  if(false !== $has_data) {
    return $has_data;
  }

  try {

    $result = $client->get_settings();

  } catch ( Exception $e ) {

    if ( '404' == $e->getCode() ){
      $result = new WP_Error( 'Site Settings Not Found', __( "The current site settings can not be found in QPilot", "autoship" ) );
      autoship_log_entry( __( 'Autoship Orders', 'autoship' ), sprintf( '%d The current site order settings can not be found in QPilot. Additional Details: %s', $e->getCode(), $e->getMessage() ) );
    } else {
      $notice = autoship_expand_http_code( $e->getCode() );

      $result  = autoship_is_user_http_message( $e->getCode() ) ?
      new WP_Error( $notice['msg'], $notice['desc'] ) : new WP_Error( 'Site Settings Retrieval Failed', __( $notice['desc'], "autoship" ) );
      autoship_log_entry( __( 'Autoship Orders', 'autoship' ), sprintf( '%d Site Settings Retrieval Failed. Additional Details: %s', $e->getCode(), $e->getMessage() ) );
    }
    return $result;

  }

  $settings = array(

    'lockDurationDays'            => $result->lockDurationDays,
    'lockNotificationOffsetDays'  => $result->lockNotificationOffsetDays,
    'isShowingNotes'              => $result->isShowingNotes,
    'isShowCoupons'               => $result->isShowCoupons,
    'isProcessingPaused'          => $result->isProcessingPaused,
    'hasActiveInvalidProducts'    => $result->hasActiveInvalidProducts,
    'orderProcessingStartTime'    => $result->orderProcessingStartTime,
    'orderProcessingEndTime'      => $result->orderProcessingEndTime,
    'supportsProcessingWindow'    => $result->supportsProcessingWindow,
    'orderProcessingOffset'       => $result->orderProcessingOffset,

  );

  // Save to the cache
  autoship_save_site_order_settings_cache( $settings );

  return $settings;

}

/**
 * Updates a Scheduled Order's Next occurrence.
 * @uses QPilotClient::get_next_occurrence_utc()
 *
 * @param string       $frequency_type. The frequency type assigned to this product
 * @param int          $frequency.      The actual frequency duration.
 * @param string       $from_date       The date from which the next occurrence should be calculated.
 *                                      Date should be in Y-m-d H:i:s format ( 2019-07-19 15:21:39 )
 * @return string|WP_Error The calculate next Occurrence Date in DateTime UTC Y-m-d\TH:i:s format ( 2019-07-19T15:21:39 ).
 */
function autoship_calculate_scheduled_order_next_occurrence ( $frequency_type, $frequency, $from_date ){

  $client = autoship_get_default_client();

  try {
    $next_occurrence = $client->get_next_occurrence_utc( $frequency_type,$frequency, $from_date );
  } catch ( Exception $e ) {
    $notice = autoship_expand_http_code( $e->getCode() );

    $next_occurrence  = autoship_is_user_http_message( $e->getCode() ) ?
    new WP_Error( $notice['msg'], $notice['desc'] ) : new WP_Error( 'Next Occurrence Calculation Failed', __( $notice['desc'], "autoship" ) );

    autoship_log_entry( __( 'Autoship Orders', 'autoship' ), sprintf( '%d Next Occurrence Calculation Failed with Frequency %d Type %s and From Date %s. Additional Details: %s', $e->getCode(), $frequency, $frequency_type, $from_date, $e->getMessage() ) );
  }

  return $next_occurrence;

}

// ==========================================================
// GET & RETRIEVAL SCHEDULED ORDER FUNCTIONS
// ==========================================================

/**
 * Retrieves a Scheduled Order's Items.
 * @uses QPilotClient::get_scheduled_order() to retrieve the Scheduled Order and then iterate through it
 * to return the item(s) requested
 *
 * @param int $order_id        A Scheduled Order id to update.
 * @param array $item_ids      Optional. An array of Scheduled Order item ids to retrieve.
 *                             If no items numbers are supplied all items are returned.
 *                             numeric keys are expected.
 * @return array|WP_Error array of items on success or WP_Error on failure.
 */
function autoship_get_scheduled_order_items ( $order_id, $item_ids = array() ){

    if ( empty( $order_id ) ){

      $notice = new WP_Error( 'Invalid Call', __( "The Set Scheduled Order items was called Incorrectly", "autoship" ) );
      return $notice ;

    }

    $client = autoship_get_default_client();

    try {

      $order = $client->get_order( $order_id );

    } catch ( Exception $e ) {

      if ( '404' == $e->getCode() ){
        $notice = new WP_Error( 'Order Not Found', __( "The supplied order can not be found in QPilot", "autoship" ) );
    		autoship_log_entry( __( 'Autoship Orders', 'autoship' ), sprintf( '%d The supplied scheduled order #%d can not be found in QPilot. Additional Details: %s', $e->getCode(), $order_id, $e->getMessage() ) );
      } else {
        $notice = autoship_expand_http_code( $e->getCode() );

        $notice  = autoship_is_user_http_message( $e->getCode() ) ?
        new WP_Error( $notice['msg'], $notice['desc'] ) : new WP_Error( 'Order Scheduled Item retrieval Failed', __( $notice['desc'], "autoship" ) );
    		autoship_log_entry( __( 'Autoship Orders', 'autoship' ), sprintf( '%d Scheduled Order #%d retrieval Failed. Additional Details: %s', $e->getCode(), $order_id, $e->getMessage() ) );
      }

      return $notice;

    }

    $return_items = array();

    // Flip supplied ids for better lookup.
    if ( ! empty( $item_ids ) )
    $item_ids     = array_flip( $item_ids );

    // Iterate through the scheduled order items and unset any
    // not requested.
    foreach ($order->scheduledOrderItems as $key => $item) {

      if ( empty( $item_ids ) || isset( $item_ids[$item->id] ) )
      $return_items[$item->id] = $order->scheduledOrderItems[$key];

    }

    return $return_items;

}

/**
 * Retrieves a customer's available payment methods from QPilot
 * @uses QPilotClient::get_customer_payment_methods()
 *
 * @param int        $customer_id The autoship customer id.
 * @return stdClass  The payment method objects
 */
function autoship_get_available_scheduled_order_payment_methods( $customer_id ){

  $client = autoship_get_default_client();

  try {

    $methods = $client->get_payment_methods( $customer_id );

  } catch ( Exception $e ) {

    if ( '404' == $e->getCode() ){
      $notice = new WP_Error( 'Payment Methods Not Found', __( "The supplied customer can not be found in QPilot", "autoship" ) );
      autoship_log_entry( __( 'Autoship Customers', 'autoship' ), sprintf( '%d The supplied Customer #%d can not be found in QPilot. Additional Details: %s', $e->getCode(), $customer_id, $e->getMessage() ) );
    } else {
      $notice = autoship_expand_http_code( $e->getCode() );

      $notice  = autoship_is_user_http_message( $e->getCode() ) ?
      new WP_Error( $notice['msg'], $notice['desc'] ) : new WP_Error( 'Retrieve Payment Methods Failed', __( $notice['desc'], "autoship" ) );
      autoship_log_entry( __( 'Autoship Customers', 'autoship' ), sprintf( '%d Retrieve Customer #%d Payment Methods Failed. Additional Details: %s', $e->getCode(), $customer_id, $e->getMessage() ) );
    }

    return $notice;

  }

  return $methods;

}

/**
 * Retrieves the next scheduled order from QPilot
 * @uses QPilotClient::get_next_scheduled_order()
 *
 * @param int       $customer_id     An Autoship customer id.
 * @param int       $frequency       Optional. The Frequency to Match
 * @param int       $frequency_type  Optional. The Frequency Type to Match
 * @param string    $status          Optional. The Status to Match
 *
 * @return stdClass|WP_Error A Scheduled order object or WP_Error on failure.
 */
function autoship_get_next_scheduled_order( $customer_id, $frequency = NULL, $frequency_type = NULL, $status = NULL ){

  $client = autoship_get_default_client();

  try {

    $result = $client->get_next_scheduled_order( $customer_id, $frequency, $frequency_type, $status );

  } catch ( Exception $e ) {

    if ( '404' == $e->getCode() ){
      $notice = new WP_Error( 'No Next Schedule Order Found', __( "There is no next schedule order in QPilot matching the search.", "autoship" ) );
  		autoship_log_entry( __( 'Autoship Orders', 'autoship' ), sprintf( '%d No matching next schedule order in QPilot for customer #%d found with Frequency %d and Type %s. Additional Details: %s', $e->getCode(), $customer_id, $frequency, $frequency_type, $e->getMessage() ) );
    } else {
      $notice = autoship_expand_http_code( $e->getCode() );

      $notice  = autoship_is_user_http_message( $e->getCode() ) ?
      new WP_Error( $notice['msg'], $notice['desc'] ) : new WP_Error( 'Get Next Scheduled Order Failed', __( $notice['desc'], "autoship" ) );
  		autoship_log_entry( __( 'Autoship Orders', 'autoship' ), sprintf( '%d Get Next Scheduled Order for customer #%d found with Frequency %d and Type %s Failed. Additional Details: %s', $e->getCode(), $customer_id, $frequency, $frequency_type, $e->getMessage() ) );
    }

    return apply_filters('autoship_scheduled_order_api_action_excemption_handler', $notice, $e, 0 );

  }

  return $result;

}

/**
 * Retrieves a scheduled order from QPilot
 * @uses QPilotClient::get_order()
 *
 * @param int $order_id            An Autoship order id.
 * @return stdClass|WP_Error       A Scheduled order object or
 *                                 WP_Error on failure.
 */
function autoship_get_scheduled_order( $order_id, $filter = false ){

  $client = autoship_get_default_client();

  try {

    $order = $client->get_order( $order_id );

  } catch ( Exception $e ) {

    $notice = autoship_expand_http_code( $e->getCode() );

    if ( '404' == $e->getCode() ){
      $order = new WP_Error( 'Order Not Found', sprintf( __( "Order #%d could not be found in QPilot", "autoship" ), $order_id ) );
  		autoship_log_entry( __( 'Autoship Order', 'autoship' ), sprintf( '%d Order #%d Not Found. Additional Details: %s', $e->getCode(), $order_id, $e->getMessage() ) );
    } else {

      $order  = autoship_is_user_http_message( $e->getCode() ) ?
      new WP_Error( $notice['msg'], $notice['desc'] ) : new WP_Error( 'Order Retrieval Failed', __( $notice['desc'], "autoship" ) );

  		autoship_log_entry( __( 'Autoship Order', 'autoship' ), sprintf( '%d Order Retrieval Failed for Order #%d. Additional Details: %s', $e->getCode(), $order_id, $e->getMessage() ) );
    }

  }

  return $filter ? autoship_convert_object_to_array( $order ): $order;

}

/**
 * Retrieves a scheduled order(s) from QPilot
 * @uses QPilotClient::get_scheduled_order()
 * @uses QPilotClient::get_scheduled_orders()
 *
 * @param array|int $order_ids     An Autoship order id or array of ids.
 * @param int       $customer_id   Optional. An Autoship customer id.
 * @return stdClass|array|WP_Error A Scheduled order object or array of scheduled orders.
 *                                 WP_Error on failure.
 */
function autoship_get_scheduled_orders( $order_ids = array(), $customer_id = 0 ){

  if ( empty( $order_ids ) && !$customer_id )
  return array();

  // Check if this is a specific order(s) to retrieve or
  // all orders for a customer.
  if ( !empty( $order_ids ) ){
    $all_orders = is_array( $order_ids ) ?
    autoship_get_scheduled_orders_by_ids( $order_ids ) : autoship_get_scheduled_order( $order_ids );
  } else {
    $all_orders = autoship_search_all_scheduled_orders( $customer_id );
  }

  return apply_filters( 'autoship_filter_retrieved_scheduled_orders', $all_orders, $order_ids, $customer_id );

}

/**
 * Retrieves all scheduled order from QPilot for a Customer
 * @uses QPilotClient::get_scheduled_orders()
 *
 * @param int       $customer_id   An Autoship customer id.
 * @return stdClass|array|WP_Error A Scheduled order object or array of scheduled orders.
 *                                 WP_Error on failure.
 */
function autoship_get_all_customer_scheduled_orders( $customer_id ){

  $client = autoship_get_default_client();

  try {

    $orders = $client->get_customer_orders( $customer_id );

  } catch ( Exception $e ) {

    $notice = autoship_expand_http_code( $e->getCode() );

    if ( '404' == $e->getCode() ){
      $orders = new WP_Error( 'Order(s) Not Found', sprintf( __( "Orders could not be found in QPilot", "autoship" ), $order_id ) );
  		autoship_log_entry( __( 'Autoship Orders', 'autoship' ), sprintf( '%d Order(s) Not Found for Customer %d. Additional Details: %s', $e->getCode(), $customer_id, $e->getMessage() ) );
    } else {

      $orders  = autoship_is_user_http_message( $e->getCode() ) ?
      new WP_Error( $notice['msg'], $notice['desc'] ) : new WP_Error( 'Order Retrieval Failed', __( $notice['desc'], "autoship" ) );
  		autoship_log_entry( __( 'Autoship Orders', 'autoship' ), sprintf( '%d Order Retrieval Failed for Customer %d. Additional Details: %s', $e->getCode(), $customer_id, $e->getMessage() ) );
    }

  }

  return $orders;

}

/**
  * Searches for scheduled order(s) in QPilot
  * @uses QPilotClient::get_orders()
  * If Page is not supplied it's assumed retrieve all orders.
  *
  * @param int       $customer_id   Optional. An Autoship customer id.
  * @param array     $params {
  *     Optional. An array of search parameters.
  *
  *     @type int     $pageSize             The default page size.  Default 100
  *     @type string  $orderBy              A product property to sort the results by
  *     @type string  $order                The Sort Direction the results should be returned ( DESC vs ASC )
  *     @type array   $statusNames          Array of Status names to search for.
  *     @type array   $metadataKey	         Array of Order Metadata keys to search for.
  *     @type array   $metadataValue	       Array of Order Metadata Values to search for.
  *     @type string  $search               A query string to search for.
  * }
  * @param bool      $recursive     Optional. True recursively call self starting with supplied page until all
  *                                 results are retrieved. False only retrieve requested page.
  * @return array|WP_Error          Array of Scheduled order objects
  *                                 WP_Error on failure.
  */
function autoship_search_all_scheduled_orders( $customer_id = NULL, $index = 1, $params = array(), $recursive = true ){

  $params = wp_parse_args( $params, array( 'pageSize' => 100 ) );
  $params['page'] = $index;

  $client = autoship_get_default_client();

  try {

    $orders = $client->get_orders( $customer_id, $params );

  } catch ( Exception $e ) {

    $notice = autoship_expand_http_code( $e->getCode() );

    if ( '404' == $e->getCode() ){
      $orders = new WP_Error( 'Order(s) Not Found', __( "Orders could not be found in QPilot", "autoship" ) );
      $message = isset( $customer_id ) ?
      sprintf( '%d Order(s) Not Found for Customer %d. Additional Details: %s', $e->getCode(), $customer_id, $e->getMessage() ) : sprintf( '%d Order(s) Not Found matching the search criteria. Additional Details: %s', $e->getCode(), $e->getMessage() );
  		autoship_log_entry( __( 'Autoship Orders', 'autoship' ), $message );
    } else {

      $orders  = autoship_is_user_http_message( $e->getCode() ) ?
      new WP_Error( $notice['msg'], $notice['desc'] ) : new WP_Error( 'Order Retrieval Failed', __( $notice['desc'], "autoship" ) );
      $message = isset( $customer_id ) ?
      sprintf( '%d Order Retrieval Failed for Customer %d. Additional Details: %s', $e->getCode(), $customer_id, $e->getMessage() ) : sprintf( '%d Order Retrieval Failed for the search criteria. Additional Details: %s', $e->getCode(), $e->getMessage() );
  		autoship_log_entry( __( 'Autoship Orders', 'autoship' ), $message );
    }

  }

  if ( is_wp_error( $orders ) )
  return $orders;

  if ( $recursive && ( $orders->totalPages > $index ) ){

    $index++;
    $new_orders = autoship_search_all_scheduled_orders( $customer_id, $index, $params, $recursive );
    return !is_wp_error( $new_orders ) ? array_merge( $orders->items, $new_orders ) : $new_orders;

  }

  return $orders->items;

}

/**
  * Searches for scheduled order(s) in QPilot
  * @uses QPilotClient::get_order()
  * If Page is not supplied it's assumed retrieve all orders.
  *
  * @param array $order_ids     An Autoship order id or array of ids.
  * @return array|WP_Error          Array of Scheduled order objects
  *                                 WP_Error on failure.
  */
function autoship_get_scheduled_orders_by_ids( $order_ids ){

  $client = autoship_get_default_client();

  $orders = array();
  foreach ( $order_ids as $id ) {

    $order = autoship_get_scheduled_order( $id );

    if ( is_wp_error( $order ) )
    continue;

    $orders[] = $order;

  }

  return $orders;

}

/**
 * Gets Scheduable products from Qpilot API
 * @return array Array with stdClass objects of schedulable products
 */
function autoship_get_schedulable_products_from_qpilot() {
  // Pull Products from Session if available else use api for fresh pull.
  $all_products = autoship_pull_schedulable_products_session();
  $all_products = empty( $all_products ) ?
  autoship_search_available_products( array( 'availability' => 1, 'addToScheduledOrder' => 'true', 'active' => 'true' ) ) : $all_products;

  return $all_products;
}

/**
 * Gets Scheduable products from WooCommerce
 * @return array Array of WC_Product objects
 */
function autoship_get_schedulable_products_from_wc() {
  // Pull Products 
  $products_args = [
    'status' => 'publish',
    'limit' => '-1',
    'stock_status' => 'instock',
    '_autoship_schedule_order_enabled' => 'yes',
    '_autoship_sync_active_enabled' => 'yes',
    'type' => [
      'simple',
      'grouped',
    ]
  ];

  $products_args = apply_filters('autoship_filter_schedulable_products_query_args', $products_args );

  $all_products = wc_get_products( $products_args );

  // Pull variations
  if( !is_wp_error( $all_products ) ) {
    $variations = autoship_get_available_schedulable_variations();
    if( !is_wp_error( $variations ) ) {
      $all_products = array_merge( $all_products, $variations );
    }
  }

  return $all_products;
}


// ==========================================================
// CREATE NEW & UPSERT SCHEDULED ORDER FUNCTIONS
// ==========================================================

/**
* Retrieves the Customer Data for creating a scheduled order.
* If the customer doesn't exist it attempts to create it in QPilot
*
* @param int          $customer_id The WC Customer ID
* @return WP_Error|stdClass The customer object from QPilot
*/
function autoship_retrieve_scheduled_order_customer_object( $customer_id ){

  // Pull the Customer object from QPilot
  $customer = autoship_get_autoship_customer( $customer_id );

  // Check if this customer exists already in QPilot & If not lets assume
  // they need to be added
  if ( is_wp_error( $customer ) || empty( $customer ) ){

    // Try to create the customer in QPilot
    autoship_maybe_create_update_autoship_customer( $customer_id );

    // Now try once more to get the customer object.
    $customer = autoship_get_autoship_customer( $customer_id );

  }

  return $customer;

}

/**
 * Retrieves the Customer Data for creating a scheduled order.
 *
 * @param int          $customer_id The WC Customer ID
 * @param array        $defaults {
 *     Optional. An array of default data to use instead of retrieved data.
 *
 *     @type stdClass $customer_object    The QPilot Customer Object to use
 *     @type int      $customerId         The QPilot Customer ID,
 *     @type string   $shippingFirstName,
 *     @type string   $shippingLastName,
 *     @type string   $shippingStreet1,
 *     @type string   $shippingStreet2,
 *     @type string   $shippingCity,
 *     @type string   $shippingState,
 *     @type string   $shippingPostcode,
 *     @type string   $shippingCountry,
 *     @type string   $Company,
 *     @type string   $PhoneNumber,
 * }
 * @return array       The customer data for the order.
 */
function autoship_create_scheduled_order_assign_customer_info( $customer_id, $defaults = array() ) {

  // Check if the QPilot user object was supplied.
  if ( !isset( $defaults['customer_object'] ) || empty( $defaults['customer_object'] ) ){

    // Pull the Customer object from QPilot
    $defaults['customer_object'] = autoship_retrieve_scheduled_order_customer_object( $customer_id );

    // Bail if Customer Doesn Exist in QPilot and could not be created.
    if ( is_wp_error( $defaults['customer_object'] ) || empty( $defaults['customer_object'] ) )
    return $defaults['customer_object'];

  }

  $customer_data = array(
    "customerId"        => $defaults['customer_object']->id,
    "shippingFirstName" => $defaults['customer_object']->shippingFirstName,
    "shippingLastName"  => $defaults['customer_object']->shippingLastName,
    "shippingStreet1"   => $defaults['customer_object']->shippingStreet1,
    "shippingStreet2"   => $defaults['customer_object']->shippingStreet2,
    "shippingCity"      => $defaults['customer_object']->shippingCity,
    "shippingState"     => $defaults['customer_object']->shippingState,
    "shippingPostcode"  => $defaults['customer_object']->shippingPostcode,
    "shippingCountry"   => $defaults['customer_object']->shippingCountry,
    "company"           => $defaults['customer_object']->company,
    "phoneNumber"       => $defaults['customer_object']->phoneNumber,
  );

  unset( $defaults['customer_object'] );

  // Get the customer info for the order but
  // let the defaults be used if supplied as overrides.
  return wp_parse_args(
    apply_filters('autoship_create_scheduled_order_assign_customer_info', $customer_data, $defaults, $customer_id ),
    $defaults
  );

}

/**
 * Retrieves the Customer Payment Method Data for creating a scheduled order.
 *
 * @param int          $customer_id The WC Customer ID
 * @param array        $defaults {
 *     Optional. An array of default data to use instead of retrieved data.
 *
 *     @type stdClass $customer_object    The QPilot Customer Object to use
 *     @type stdClass $payment_object     The QPilot Customer Payment Method object to use
 *     @type int      $customerId         The QPilot Customer ID,
 *     @type string   $description,
 *     @type string   $type,
 *     @type string   $expiration,
 *     @type int      $lastFourDigits,
 *     @type string   $gatewayCustomerId,
 *     @type string   $gatewayPaymentId,
 *     @type string   $billingFirstName,
 *     @type string   $billingLastName,
 *     @type string   $billingStreet1,
 *     @type string   $billingStreet2,
 *     @type string   $billingCity,
 *     @type string   $billingState,
 *     @type string   $billingPostcode,
 *     @type string   $billingCountry
 * }
 * @return array       The customer payment method data for the order.
 */
function autoship_create_scheduled_order_assign_payment_info( $customer_id, $defaults = array() ) {

  if ( !isset( $defaults['customer_object'] ) || empty( $defaults['customer_object'] ) ){

    // Pull the Customer object from QPilot
    $defaults['customer_object'] = autoship_retrieve_scheduled_order_customer_object( $customer_id );

    // Bail if Customer Doesn Exist in QPilot and could not be created.
    if ( is_wp_error( $defaults['customer_object'] ) || empty( $defaults['customer_object'] ) )
    return $defaults['customer_object'];

  }

  if ( !isset( $defaults['payment_object'] ) || empty( $defaults['payment_object'] ) ){

    // Get the current Payment Method
    $payment_methods = autoship_get_available_scheduled_order_payment_methods( $defaults['customer_object']->id );

    // Grab the first method in the group but let this be filtered
    $defaults['payment_object'] = apply_filters( 'autoship_create_scheduled_order_assign_payment_method_default',
    empty( $payment_methods ) ? NULL : $payment_methods[0], $payment_methods, $customer_id, $defaults );

  }

  $payment_data = isset( $defaults['payment_object'] ) ? array(
    "description"       => $defaults['payment_object']->description,
    "type"              => $defaults['payment_object']->type,
    "expiration"        => $defaults['payment_object']->expiration,
    "lastFourDigits"    => $defaults['payment_object']->lastFourDigits,
    "gatewayCustomerId" => $defaults['payment_object']->gatewayCustomerId,
    "gatewayPaymentId"  => $defaults['payment_object']->gatewayPaymentId,
    "billingFirstName"  => $defaults['payment_object']->billingFirstName,
    "billingLastName"   => $defaults['payment_object']->billingLastName,
    "billingStreet1"    => $defaults['payment_object']->billingStreet1,
    "billingStreet2"    => $defaults['payment_object']->billingStreet2,
    "billingCity"       => $defaults['payment_object']->billingCity,
    "billingState"      => $defaults['payment_object']->billingState,
    "billingPostcode"   => $defaults['payment_object']->billingPostcode,
    "billingCountry"    => $defaults['payment_object']->billingCountry,
  ) : array();

  unset( $defaults['customer_object'] );
  unset( $defaults['payment_object'] );

  // Get the payment info for the order but
  // let the defaults be used if supplied as overrides.
  return wp_parse_args(
    apply_filters('autoship_create_scheduled_order_assign_payment_info', $payment_data, $defaults, $customer_id ),
    $defaults
  );

}

/**
 * Creates Line Item Data to add to an order.
 *
 * @param int          $customer_id The WC Customer ID
 * @param array        $items An Array of QPilot Product Ids to Qty
 * @return array|WP_Error An array of Scheduled Line Item Records or WP_Error on failure.
 */
function autoship_create_scheduled_order_assign_scheduled_items( $customer_id, $items ) {

  if ( empty( $items ) )
  return array();

  $scheduled_order_items = array();

  // Retrieve the items for this site from QPilot
  $available_products = autoship_get_products_by_ids( array_keys( $items ), 5, array( 'availability' => 'InStock', 'addToScheduledOrder' => 'true' ) );

  // Check for API Error(s)
  if ( is_wp_error( $available_products ) ){
    $scheduled_order_items = $available_products;
    $products    = array();
  } else {
    $products    = $available_products;
  }

  // Generate the line items.
  foreach ($products as $key => $value) {
    $scheduled_order_items[] = array(
      'productId' => $value->id,
      'price'     => $value->price,
      'salePrice' => $value->salePrice,
      'quantity'  => $items[$value->id]
    );
  }

  return apply_filters('autoship_create_scheduled_orders_assign_scheduled_items', $scheduled_order_items, $items, $customer_id );

}

/**
 * Creates a new QPilot Scheduled Order
 *
 * @param int          $customer_id The WC Customer ID error
 * @param array        Optional. $order_data The array of default order data to use for creating the scheduled order.
 *                     Default empty array.
 * @return WP_Error|stdClass The resulting Scheduled Order or an error.
 */
function autoship_create_scheduled_order( $customer_id, $order_data = array() ) {

  $order_data['customerId'] = $customer_id;
  $order_data = apply_filters("autoship_create_scheduled_order_default_order_data", $order_data, $customer_id );

  // Check for any supplied line items
  $items_data = isset( $order_data['scheduledOrderItems'] ) && !empty( $order_data['scheduledOrderItems'] ) ?
  $order_data['scheduledOrderItems'] : NULL;

  // Grab the Scheduled Item data for the order if items were supplied.
  if ( isset( $order_data['scheduledOrderItemIds'] ) && !empty( $order_data['scheduledOrderItemIds'] ) && empty( $items_data ) )
  $items_data = autoship_create_scheduled_order_assign_scheduled_items( $customer_id, $order_data['scheduledOrderItemIds'] );

  // Assign Items if not error or empty
  $order_data['scheduledOrderItems'] = ( !is_wp_error( $items_data ) && isset( $items_data ) && !empty( $items_data ) ) ?
  $items_data : NULL;

  // Grab the Frequency & FrequencyType
  $frequency_options = autoship_get_all_valid_order_create_frequencies( $customer_id, $order_data );
  $default_frequency = reset( $frequency_options );
  $order_data['frequency']      = isset( $order_data['frequency'] ) ? $order_data['frequency'] : $default_frequency['frequency'];
  $order_data['frequencyType']  = isset( $order_data['frequencyType'] ) ? $order_data['frequencyType'] : $default_frequency['frequency_type'];

  // Assign the status
  $order_data['status'] = isset( $order_data['status'] ) ?
  $order_data['status'] : apply_filters( 'autoship_create_scheduled_orders_default_status', 'Active', $order_data, $customer_id );

  // Add the Origin to the Order data if doesn't exist.
  $order_data["origin"] = isset( $order_data["origin"] ) && !empty( $order_data["origin"] ) ? $order_data["origin"] : 'CustomerApi';

  // Add the Origin to the Order data if doesn't exist.
  $order_data["utcOffset"] = isset( $order_data["utcOffset"] ) && !empty( $order_data["utcOffset"] ) ? $order_data["utcOffset"] : autoship_get_local_timezone_offset();

  // Add the Default Currency to the order
  $order_data["currencyIso"] = get_woocommerce_currency();

  // Allow devs to filter data.
  $scheduled_order_data = apply_filters('autoship_create_scheduled_orders_data', $order_data, $customer_id );

  // Create the order in QPilot.
  $scheduled_order = autoship_upsert_scheduled_order( $scheduled_order_data['customerId'], $scheduled_order_data );

  if ( is_wp_error( $scheduled_order ) ){

    // Adjust Code to Note where the error bubbled up through
    return new WP_Error( 'Create Scheduled Orders Failed', $scheduled_order->get_error_message() );

  }

  return $scheduled_order;

}

/**
 * Updates or Creates a scheduled order in QPilot
 * @uses QPilotClient::upsert_order()
 *
 * @param stdClass|array $order The QPilot Order data
 * @return stdClass The resulting/updated order object
 */
function autoship_upsert_scheduled_order( $customer_id, $order ){

  $client = autoship_get_default_client();

  try {

    // Create/update the order in QPilot.
    $scheduled_order = $client->upsert_order( $customer_id, $order );

  } catch ( Exception $e ) {

    // Attach the orders key, save the error and add an error note.
    $notice = autoship_expand_http_code( $e->getCode() );

    $error = autoship_is_user_http_message( $e->getCode() ) ?
    new WP_Error( $notice['msg'], $notice['desc'] ) : new WP_Error( 'Creating/Updating Scheduled Orders Failed', sprintf( __('%d Creating/Updating Scheduled Order #%s for Customer #%d Failed.  Additional Details: %s'), $e->getCode(), $order['originalExternalId'], $customer_id, $notice['desc'] ) );

    autoship_log_entry( __( 'Autoship Orders', 'autoship' ), sprintf( '%d Upsert Scheduled Order #%s for Customer #%d Failed.  Additional Details: %s', $e->getCode(), $order['originalExternalId'], $customer_id, $e->getMessage() ) );

    return $error;

  }

  return $scheduled_order;

}

/**
 * Adds new Scheduled Order Scheduled Order Items.
 * @uses QPilotClient::create_scheduled_order_item()
 *
 * @param int $order_id  A Scheduled Order id to update.
 * @param array $scheduled_order_items {
 *    Optional. An array of item arrays .
 *    $item {
 *      @type int     $productId            The WC Product ID.
 *      @type float   $price                The item price. Default if not supplied pulled from Product
 *      @type float   $salePrice            The item sale price. Default if not supplied pulled from Product
 *      @type float   $originalSalePrice    The original price stored for historical purposes
 *      @type int     $quantity             The quantity for the line item. Default 1
 *      @type int     $cycles               The current cycle count for the item. Default 0
 *      @type int     $minCycles            The min Cycle value for the item. Default 0
 *      @type int     $manCycles            The max Cycle value for the item. Default 0
 *      @type array   $metadata             An array of metadata key => value pairs to attach to the item
 *    }
 * }
 * @return bool|WP_Error true on success or false|WP_Error on failure.
 */
function autoship_create_scheduled_order_items ( $order_id, $scheduled_order_items ){

  if ( empty( $order_id ) || empty( $scheduled_order_items ) ){

    $notice = new WP_Error( 'Invalid Call', __( "The Add Scheduled Order items was called Incorrectly", "autoship" ) );
    return $notice ;

  }

  $client = autoship_get_default_client();

  try {

    $client->create_scheduled_order_items ( $order_id, $scheduled_order_items );

  } catch ( Exception $e ) {

    if ( '404' == $e->getCode() ){
      $notice = new WP_Error( 'Order Not Found', __( "The supplied order can not be found in QPilot", "autoship" ) );
  		autoship_log_entry( __( 'Autoship Orders', 'autoship' ), sprintf( '%d The supplied order #%d can not be found in QPilot. Additional Details: %s', $e->getCode(), $order_id, $e->getMessage() ) );
    } else {
      $notice = autoship_expand_http_code( $e->getCode() );

      $notice = autoship_is_user_http_message( $e->getCode() ) ?
      new WP_Error( $notice['msg'], $notice['desc'] ) : new WP_Error( 'Order Update Failed', __( $notice['desc'], "autoship" ) );
  		autoship_log_entry( __( 'Autoship Orders', 'autoship' ), sprintf( '%d Create Scheduled Order Item for Order #%d Failed. Additional Details: %s', $e->getCode(), $order_id, $e->getMessage() ) );
    }

    return apply_filters('autoship_scheduled_order_api_action_excemption_handler', $notice, $e, $order_id );

  }

  return true;

}

// ==========================================================
// UPDATE & SET SCHEDULED ORDER FUNCTIONS
// ==========================================================

/**
 * Updates an existing scheduled order in QPilot
 * @uses QPilotClient::update_scheduled_order()
 *
 * @param stdClass|array $order The QPilot Order data
 * @return stdClass The resulting/updated order object
 */
function autoship_update_scheduled_order( $order_id, $order ){

  $client = autoship_get_default_client();

  try {

    // Create/update the order in QPilot.
    $scheduled_order = $client->update_scheduled_order( $order_id, $order );

  } catch ( Exception $e ) {

    if ( '404' == $e->getCode() ){

      $error = new WP_Error( 'Order Not Found', __( "The supplied order can not be found in QPilot", "autoship" ) );
      autoship_log_entry( __( 'Autoship Orders', 'autoship' ), sprintf( '%d The supplied order #%d can not be found in QPilot. Additional Details: %s', $e->getCode(), $order_id, $e->getMessage() ) );

    } else {

      // Attach the orders key, save the error and add an error note.
      $notice = autoship_expand_http_code( $e->getCode() );

      $error  = autoship_is_user_http_message( $e->getCode() ) ?
      new WP_Error( $notice['msg'], $notice['desc'] ) : new WP_Error( 'Updating Scheduled Orders Failed', sprintf( __('Updating Scheduled Order #%d Failed.  Additional Details: %s'), $order_id, $notice['desc'] ) );

      autoship_log_entry( __( 'Autoship Orders', 'autoship' ), sprintf( '%d Update Scheduled order #%d Failed. Additional Details: %s', $e->getCode(), $order_id, $e->getMessage() ) );

    }

    return $error;

  }

  return $scheduled_order;

}

/**
 * Updates a Scheduled Order's preferred Shipping Method.
 *
 * @param int|array $autoship_order  A Scheduled Order or id to update.
 * @param string $shipping_rate The Shipping Rate Name
 * @return bool|WP_Error true on success or false|WP_Error on failure.
 */
function autoship_set_scheduled_order_preferred_shipping_rate ( $autoship_order, $shipping_rate ){

  if ( !is_array( $autoship_order ) )
  $autoship_order = autoship_get_scheduled_orders( absint( $autoship_order ), true );

  $autoship_order['preferredShippingRateOption'] = $shipping_rate;

  $client = autoship_get_default_client();

  try {

    $result = $client->update_scheduled_order( $autoship_order['id'], $autoship_order );

  } catch ( Exception $e ) {

    if ( '404' == $e->getCode() ){
      $notice = new WP_Error( 'Order Not Found', __( "The supplied order can not be found in QPilot", "autoship" ) );
      autoship_log_entry( __( 'Autoship Orders', 'autoship' ), sprintf( '%d The supplied order #%d can not be found in QPilot. Additional Details: %s', $e->getCode(), $autoship_order['id'], $e->getMessage() ) );
    } else {
      $notice = autoship_expand_http_code( $e->getCode() );

      $notice = autoship_is_user_http_message( $e->getCode() ) ?
      new WP_Error( $notice['msg'], $notice['desc'] ) : new WP_Error( 'Order Shipping Rate Update Failed', __( $notice['desc'], "autoship" ) );
      autoship_log_entry( __( 'Autoship Orders', 'autoship' ), sprintf( '%d Order #%d Shipping Rate Update Failed. Additional Details: %s', $e->getCode(), $autoship_order['id'], $e->getMessage() ) );
    }

    return apply_filters('autoship_scheduled_order_api_action_excemption_handler', $notice, $e, $autoship_order['id'] );

  }

  // Validate that the Shipping rate was applied
  // invalid shipping rates don't apply
  // Convert the object to array
  $preferred = autoship_get_scheduled_order_preferred_shipping_rate( autoship_convert_object_to_array( $result ) );
  return $shipping_rate == $preferred;

}

/**
 * Updates a Scheduled Order's Payment Method.
 * @uses QPilotClient::update_scheduled_order_payment_method() to assign the payment method
 *
 * @param int $order_id          A Scheduled Order id to update.
 * @param int $payment_method_id The payment method id to update to.
 * @return bool|WP_Error true on success or false|WP_Error on failure.
 */
function autoship_set_scheduled_order_payment_method ( $order_id, $payment_method_id ){

    if ( empty( $order_id ) || empty( $payment_method_id ) ){

      $notice = new WP_Error( 'Invalid Call', __( "The Set Scheduled Order Payment Method was called Incorrectly", "autoship" ) );
      return $notice ;

    }

    $client = autoship_get_default_client();

    // Get Payment Method Object
    try {

      $payment_method = $client->get_payment_method( $payment_method_id );

    } catch ( Exception $e ) {

      if ( '404' == $e->getCode() ){
        $notice = new WP_Error( 'Payment Method Not Found', __( "The supplied payment method can not be found in QPilot", "autoship" ) );
    		autoship_log_entry( __( 'Autoship Payment Methods', 'autoship' ), sprintf( '%d The supplied payment method #%d can not be found in QPilot. Additional Details: %s', $e->getCode(), $payment_method_id, $e->getMessage() ) );
      } else {
        $notice = autoship_expand_http_code( $e->getCode() );

        $notice = autoship_is_user_http_message( $e->getCode() ) ?
        new WP_Error( $notice['msg'], $notice['desc'] ) : new WP_Error( 'Payment Method Update Failed', __( $notice['desc'], "autoship" ) );
    		autoship_log_entry( __( 'Autoship Payment Methods', 'autoship' ), sprintf( '%d Payment Method #%d Retrieval Failed. Additional Details: %s', $e->getCode(), $payment_method_id, $e->getMessage() ) );
      }

      return $notice;

    }

    // Now update the payment method for the schedule.
    try {

      $result = $client->update_scheduled_order_payment_method( $order_id, $payment_method_id );

    } catch ( Exception $e ) {

      if ( '404' == $e->getCode() ){
        $notice = new WP_Error( 'Order Not Found', __( "The supplied order can not be found in QPilot", "autoship" ) );
    		autoship_log_entry( __( 'Autoship Orders', 'autoship' ), sprintf( '%d The supplied order #%d can not be found in QPilot. Additional Details: %s', $e->getCode(), $order_id, $e->getMessage() ) );
      } else {
        $notice = autoship_expand_http_code( $e->getCode() );

        $notice = autoship_is_user_http_message( $e->getCode() ) ?
        new WP_Error( $notice['msg'], $notice['desc'] ) : new WP_Error( 'Payment Method Update Failed', __( $notice['desc'], "autoship" ) );
    		autoship_log_entry( __( 'Autoship Orders', 'autoship' ), sprintf( '%d Update Order #%d Payment Method #%d Failed. Additional Details: %s', $e->getCode(), $order_id, $payment_method_id, $e->getMessage() ) );
      }

      return apply_filters('autoship_scheduled_order_api_action_excemption_handler', $notice, $e, $order_id );

    }

    return true;

}

/**
 * Updates a Scheduled Order's frequency.
 * @uses QPilotClient::update_scheduled_order_frequency() to assign the frequency type and frequency
 *
 * @param int $order_id          A Scheduled Order id to update.
 * @param string $frequency_type A Scheduled Order frequency type.
 * @param int    $frequency      A Scheduled Order frequency.
 * @return bool|WP_Error true on success or false|WP_Error on failure.
 */
function autoship_set_scheduled_order_frequency ( $order_id, $frequency_type, $frequency ){

    if ( empty( $order_id ) || empty( $frequency_type ) || empty( $frequency ) ){

      $notice = new WP_Error( 'Invalid Call', __( "The Set Scheduled Order Frequency was called Incorrectly", "autoship" ) );
      return $notice ;

    }

    $client = autoship_get_default_client();

    try {

      $result = $client->update_scheduled_order_frequency( $order_id, $frequency_type, $frequency );

    } catch ( Exception $e ) {

      if ( '404' == $e->getCode() ){
        $notice = new WP_Error( 'Order Not Found', __( "The supplied order can not be found in QPilot", "autoship" ) );
    		autoship_log_entry( __( 'Autoship Orders', 'autoship' ), sprintf( '%d The supplied order #%d can not be found in QPilot. Additional Details: %s', $e->getCode(), $order_id, $e->getMessage() ) );
      } else {
        $notice = autoship_expand_http_code( $e->getCode() );

        $notice = autoship_is_user_http_message( $e->getCode() ) ?
        new WP_Error( $notice['msg'], $notice['desc'] ) : new WP_Error( 'Order Frequency Update Failed', __( $notice['desc'], "autoship" ) );
    		autoship_log_entry( __( 'Autoship Orders', 'autoship' ), sprintf( '%d Order #%d Frequency ( %d - %s ) Update Failed. Additional Details: %s', $e->getCode(), $order_id, $frequency, $frequency_type, $e->getMessage() ) );
      }

      return apply_filters('autoship_scheduled_order_api_action_excemption_handler', $notice, $e, $order_id );

    }

    return true;

}

/**
 * Updates a Scheduled Order's Shipping Address
 * @uses QPilotClient::update_scheduled_order() to assign the shipping address
 *
 * @param int|array $autoship_order A Scheduled Order id or Scheduled Order to update.
 * @param array $address         The Shipping address data to update.
 * @return bool|WP_Error true on success or false|WP_Error on failure.
 */
function autoship_set_scheduled_order_shipping_address ( $autoship_order, $address = array() ){

    if ( empty( $address ) ){

      $notice = new WP_Error( 'Invalid Call', __( "The Set Scheduled Order Shipping Address was called Incorrectly", "autoship" ) );
      return $notice ;

    }

    if ( !is_array( $autoship_order ) ){

      $autoship_order = autoship_get_scheduled_orders( absint( $autoship_order ) );
      $autoship_order = autoship_convert_object_to_array( $autoship_order );

    }

    $fields_map = autoship_shipping_address_form_fields();

    foreach ( $fields_map as $key => $value) {
      if ( isset( $address[$key] ) )
      $autoship_order[$value['api_key']] = $address[$key];
    }

    $autoship_order = array_merge(
      $autoship_order,
      apply_filters( 'autoship_generated_scheduled_order_updated_shipping_data', array(
      'shippingFirstName' => $autoship_order['shippingFirstName'],
      'shippingLastName'  => $autoship_order['shippingLastName'],
      'shippingStreet1'   => $autoship_order['shippingStreet1'],
      'shippingStreet2'   => $autoship_order['shippingStreet2'],
      'shippingCity'      => $autoship_order['shippingCity'],
      'shippingPostcode'  => $autoship_order['shippingPostcode'],
      'shippingState'     => $autoship_order['shippingState'],
      'shippingCountry'   => $autoship_order['shippingCountry']
      ), $autoship_order )
    );

    $client = autoship_get_default_client();

    try {

      $result = $client->update_scheduled_order( $autoship_order['id'], $autoship_order );

    } catch ( Exception $e ) {

      if ( '404' == $e->getCode() ){
        $notice = new WP_Error( 'Order Not Found', __( "The supplied order can not be found in QPilot", "autoship" ) );
    		autoship_log_entry( __( 'Autoship Orders', 'autoship' ), sprintf( '%d The supplied order #%d can not be found in QPilot. Additional Details: %s', $e->getCode(), $autoship_order['id'], $e->getMessage() ) );
      } else {
        $notice = autoship_expand_http_code( $e->getCode() );

        $notice = autoship_is_user_http_message( $e->getCode() ) ?
        new WP_Error( $notice['msg'], $notice['desc'] ) : new WP_Error( 'Order Shipping Address Update Failed', __( $notice['desc'], "autoship" ) );
    		autoship_log_entry( __( 'Autoship Orders', 'autoship' ), sprintf( '%d Order #%d Shipping Address Update Failed. Additional Details: %s', $e->getCode(), $autoship_order['id'], $e->getMessage() ) );
      }

      return apply_filters('autoship_scheduled_order_api_action_excemption_handler', $notice, $e, $autoship_order['id'] );

    }

    return true;

}

/**
 * Updates a Scheduled Order's Next occurrence.
 * @uses QPilotClient::update_scheduled_order_next_occurrence()
 *
 * @param int    $order_id          A Scheduled Order id to update.
 * @param string $next_occurrence     A Scheduled Order next occurrence DateTime in UTC.
 * @return bool|WP_Error true on success or false|WP_Error on failure.
 */
function update_scheduled_order_next_occurrence ( $order_id, $next_occurrence ){

    if ( empty( $order_id ) || empty( $next_occurrence ) ){

      $notice = new WP_Error( 'Invalid Call', __( "The Set Scheduled Order Next occurrence was called Incorrectly", "autoship" ) );
      return $notice ;

    }

    $client = autoship_get_default_client();

    try {

      $result = $client->update_scheduled_order_next_occurrence( $order_id, $next_occurrence  );

    } catch ( Exception $e ) {

      if ( '404' == $e->getCode() ){

        $notice = new WP_Error( 'Order Not Found', __( "The supplied order can not be found in QPilot", "autoship" ) );
    		autoship_log_entry( __( 'Autoship Orders', 'autoship' ), sprintf( '%d The supplied order #%d can not be found in QPilot. Additional Details: %s', $e->getCode(), $order_id, $e->getMessage() ) );

      } else if ( '400' == $e->getCode() ){

        $notice = new WP_Error( 'Order Next Occurrence Update Failed', $e->getMessage() );
        autoship_log_entry( __( 'Autoship Orders', 'autoship' ), sprintf( '%d Order #%d Next Occurrence Update to %s Failed. Additional Details: %s', $e->getCode(), $order_id, $next_occurrence, $e->getMessage() ) );

      } else {

        $notice = autoship_expand_http_code( $e->getCode(), '', $e->getMessage() );
        $notice = autoship_is_user_http_message( $e->getCode() ) ?
        new WP_Error( $notice['msg'], $notice['desc'] ) : new WP_Error( 'Order Next Occurrence Update Failed', __( $notice['desc'], "autoship" ) );
    		autoship_log_entry( __( 'Autoship Orders', 'autoship' ), sprintf( '%d Order #%d Next Occurrence Update to %s Failed. Additional Details: %s', $e->getCode(), $order_id, $next_occurrence, $e->getMessage() ) );

      }

      return apply_filters('autoship_scheduled_order_api_action_excemption_handler', $notice, $e, $order_id );

    }

    return true;

}

/**
 * Updates the next Scheduled Order with the supplied items.
 * @uses QPilotClient::add_items_to_next_scheduled_order()
 *
 * @param int    $customer_id    The wc customer id.
 * @param int    $product_id     The wc product id.
 * @param int    $qty            The quantity to add
 * @param float  $price          Optional. The Price.
 *                               If not supplied uses the WC Product Price.
 *                               Default NULL
 * @param float  $sale_price     Optional. The Sale Price to use.
 *                                If not supplied uses the WC Product Autoship Recurring Price.
 *                               or sale price.
 *                               Default NULL
 * @param int    $min_cycles     The first time this item should be processed.
 *                               Default NULL
 * @param int    $max_cycles     The number of times this item should be processed.
 *                               Default NULL
 * @return int|WP_Error The order id it was added to on success or false|WP_Error on failure.
 */
function autoship_update_next_scheduled_order_with_item ( $customer_id, $product_id, $qty = 1, $price = NULL, $sale_price = NULL, $min_cycles = NULL , $max_cycles = NULL ){

  if ( empty( $customer_id ) || empty( $product_id ) )
  return new WP_Error( 'Invalid Call', __( "The Customer ID and Product ID are both required.", "autoship" ) );

  $result = autoship_update_next_scheduled_order_with_items ( $customer_id, array( array(
  	'productId' => $product_id,
    'quantity'  => $qty,
    'price'     => $price,
    'salePrice' => $sale_price,
    'minCycles' => $min_cycles,
    'maxCycles' => $max_cycles
  ) ) );

  return $result;

}

/**
 * Updates the next Scheduled Order with the supplied items.
 * @uses QPilotClient::add_items_to_next_scheduled_order()
 *
 * @param int   $customer_id    The wc customer id.
 * @param array $scheduled_order_items {
 *    $item {
 *      @type int     $productId            The WC Product ID.
 *      @type float   $price                The item price. Default if not supplied pulled from Product
 *      @type float   $salePrice            The item sale price. Default if not supplied pulled from Product
 *      @type float   $originalSalePrice    The original price stored for historical purposes
 *      @type int     $quantity             The quantity for the line item. Default 1
 *      @type int     $cycles               The current cycle count for the item. Default 0
 *      @type int     $minCycles            The min Cycle value for the item. Default 0
 *      @type int     $manCycles            The max Cycle value for the item. Default 0
 *      @type array   $metadata             An array of metadata key => value pairs to attach to the item
 *    }
 * }
 * @return int|WP_Error The order id it was added to on success or false|WP_Error on failure.
 */
function autoship_update_next_scheduled_order_with_items ( $customer_id, $scheduled_order_items ){

    $client = autoship_get_default_client();

    try {

      $result = $client->add_items_to_next_scheduled_order( $customer_id, $scheduled_order_items );

    } catch ( Exception $e ) {

      if ( '404' == $e->getCode() ){
        $notice = new WP_Error( 'Order Not Found', __( "An order to add these items to was not found in QPilot", "autoship" ) );
    		autoship_log_entry( __( 'Autoship Orders', 'autoship' ), sprintf( '%d An order to add item to was not found in QPilot for customer #%d. Additional Details: %s', $e->getCode(), $customer_id, $e->getMessage() ) );
      } else {
        $notice = autoship_expand_http_code( $e->getCode() );

        $notice = autoship_is_user_http_message( $e->getCode() ) ?
        new WP_Error( $notice['msg'], $notice['desc'] ) : new WP_Error( 'Add Item(s) to Next Scheduled Order Failed', __( $notice['desc'], "autoship" ) );
    		autoship_log_entry( __( 'Autoship Orders', 'autoship' ), sprintf( '%d Add Item(s) to Next Scheduled Order Failed for customer #%d. Additional Details: %s', $e->getCode(), $customer_id, $e->getMessage() ) );
      }

      return apply_filters('autoship_scheduled_order_api_action_excemption_handler', $notice, $e, 0 );

    }

    return $result->id;

}

/**
 * Updates a Scheduled Order's status.
 * @uses QPilotClient::update_scheduled_order_status()
 *
 * @param int $order_id  A Scheduled Order id to update.
 * @param string $status A Scheduled Order status to set.
 * @return bool|WP_Error true on success or false|WP_Error on failure.
 */
function autoship_set_scheduled_order_status ( $order_id, $status ){

    if ( empty( $order_id ) || empty( $status ) ){

      $notice = new WP_Error( 'Invalid Call', __( "The Set Scheduled Order Status was called Incorrectly", "autoship" ) );
      return $notice ;

    }

    $client = autoship_get_default_client();

    try {

      $client->update_scheduled_order_status( $order_id, $status );

    } catch ( Exception $e ) {

      if ( '404' == $e->getCode() ){
        $notice = new WP_Error( 'Order Not Found', __( "The supplied order can not be found in QPilot", "autoship" ) );
    		autoship_log_entry( __( 'Autoship Orders', 'autoship' ), sprintf( '%d The supplied order #%d can not be found in QPilot. Additional Details: %s', $e->getCode(), $order_id, $e->getMessage() ) );
      } else {

        $notice = autoship_expand_http_code( $e->getCode() );

        $notice = autoship_is_user_http_message( $e->getCode() ) ?
        new WP_Error( $notice['msg'], $notice['desc'] ) : new WP_Error( 'Order Status Update Failed', __( $notice['desc'], "autoship" ) );
    		autoship_log_entry( __( 'Autoship Orders', 'autoship' ), sprintf( '%d Order #%d Status Update to %s Failed. Additional Details: %s', $e->getCode(), $order_id, $status, $e->getMessage() ) );
      }

      return apply_filters('autoship_scheduled_order_api_action_excemption_handler', $notice, $e, $order_id );

    }

    return true;

}

/**
 * Updates a Scheduled Order's Scheduled Order Items.
 * @uses QPilotClient::update_scheduled_order_item()
 *
 * @param int $order_id  A Scheduled Order id to update.
 * @param array $scheduled_order_items An array of scheduled order items to update.
 * @return bool|WP_Error true on success or false|WP_Error on failure.
 */
function autoship_set_scheduled_order_items ( $order_id, $scheduled_order_items ){

    if ( empty( $order_id ) || empty( $scheduled_order_items ) ){

      $notice = new WP_Error( 'Invalid Call', __( "The Set Scheduled Order items was called Incorrectly", "autoship" ) );
      return $notice ;

    }

    $client = autoship_get_default_client();

    try {

      foreach ($scheduled_order_items as $scheduled_order_item)
      $client->update_scheduled_order_item ( $scheduled_order_item['id'], $scheduled_order_item );

    } catch ( Exception $e ) {

      if ( '404' == $e->getCode() ){
        $notice = new WP_Error( 'Order Not Found', __( "The supplied order can not be found in QPilot", "autoship" ) );
    		autoship_log_entry( __( 'Autoship Orders', 'autoship' ), sprintf( '%d The supplied order #%d or order item #%d can not be found in QPilot. Additional Details: %s', $e->getCode(), $order_id, $scheduled_order_item['id'], $e->getMessage() ) );
      } else {
        $notice = autoship_expand_http_code( $e->getCode() );

        $notice = autoship_is_user_http_message( $e->getCode() ) ?
        new WP_Error( $notice['msg'], $notice['desc'] ) : new WP_Error( 'Order Update Failed', __( $notice['desc'], "autoship" ) );
    		autoship_log_entry( __( 'Autoship Orders', 'autoship' ), sprintf( '%d Order #%d Scheduled Items Update Failed for item #%d. Additional Details: %s', $e->getCode(), $order_id, $scheduled_order_item['id'], $e->getMessage() ) );
      }

      return apply_filters('autoship_scheduled_order_api_action_excemption_handler', $notice, $e, $order_id );

    }

    return true;

}

// ==========================================================
// DELETE & REMOVE SCHEDULED ORDER FUNCTIONS
// ==========================================================

/**
 * Deletes a Scheduled Order from QPilot.
 * @uses QPilotClient::delete_order()
 *
 * @param int $order_id  A Scheduled Order id to delete.
 * @return bool|WP_Error true on success or false|WP_Error on failure.
 */
function autoship_delete_scheduled_order ( $order_id ){

    $client = autoship_get_default_client();

    try {

      $client->delete_order( $order_id );

    } catch ( Exception $e ) {

      if ( '404' == $e->getCode() ){
        $notice = new WP_Error( 'Order Not Found', __( "The supplied order can not be found in QPilot", "autoship" ) );
    		autoship_log_entry( __( 'Autoship Orders', 'autoship' ), sprintf( '%d The supplied order #%d can not be found in QPilot. Additional Details: %s', $e->getCode(), $order_id, $e->getMessage() ) );
      } else {
        $notice = autoship_expand_http_code( $e->getCode() );

        $notice = autoship_is_user_http_message( $e->getCode() ) ?
        new WP_Error( $notice['msg'], $notice['desc'] ) : new WP_Error( 'Order Delete Failed', __( $notice['desc'], "autoship" ) );
    		autoship_log_entry( __( 'Autoship Orders', 'autoship' ), sprintf( '%d Order #%d Delete Failed. Additional Details: %s', $e->getCode(), $order_id, $e->getMessage() ) );
      }

      return apply_filters('autoship_scheduled_order_api_action_excemption_handler', $notice, $e, $order_id );

    }

    return true;

}

/**
 * Deletes Scheduled Orders from QPilot.
 * @uses autoship_delete_scheduled_order()
 *
 * @param array $order_ids  The Scheduled Order ids to delete.
 * @return bool|WP_Error true on success or false|WP_Error on failure.
 */
function autoship_delete_scheduled_orders ( $order_ids ){

  $client = autoship_get_default_client();

  foreach ($order_ids as $order_id) {

    $success = autoship_delete_scheduled_order( $order_id );
    if ( !$success || is_wp_error( $success ) )
    return $success;

  }

  return true;

}

/**
 * Deletes a Scheduled Order Item from a Scheduled Order.
 * @uses QPilotClient::delete_scheduled_order_item()
 *
 * @param int $order_id  A Scheduled Order id to update.
 * @param int $scheduled_order_item_id A Scheduled Order item id to remove.
 * @return bool|WP_Error true on success or false|WP_Error on failure.
 */
function autoship_remove_scheduled_order_item ( $order_id, $scheduled_order_item_id ){

  $client = autoship_get_default_client();

  try {

    $client->delete_scheduled_order_item( $scheduled_order_item_id );

  } catch ( Exception $e ) {

    if ( '404' == $e->getCode() ){
      $notice = new WP_Error( 'Order Not Found', __( "The supplied order can not be found in QPilot", "autoship" ) );
  		autoship_log_entry( __( 'Autoship Orders', 'autoship' ), sprintf( '%d The supplied order #%d or order item #%d can not be found in QPilot. Additional Details: %s', $e->getCode(), $order_id, $scheduled_order_item_id, $e->getMessage() ) );
    } else {
      $notice = autoship_expand_http_code( $e->getCode() );

      $notice = autoship_is_user_http_message( $e->getCode() ) ?
      new WP_Error( $notice['msg'], $notice['desc'] ) : new WP_Error( 'Remove Order item Failed', __( $notice['desc'], "autoship" ) );
  		autoship_log_entry( __( 'Autoship Orders', 'autoship' ), sprintf( '%d Order item #%d for Order #%d Removal Failed. Additional Details: %s', $e->getCode(), $scheduled_order_item_id, $order_id, $e->getMessage() ) );
    }

    return apply_filters('autoship_scheduled_order_api_action_excemption_handler', $notice, $e, $order_id );

  }

  return true;

}

// ==========================================================
// MAIN TEMPLATE DISPLAY FUNCTIONS
// ==========================================================

/**
 * Display Scheduled Orders App.
 *
 * Includes the following filters to adjust settings
 * {@see autoship_scheduled_orders_app_url} can be used to change the url
 * parameters used in the app.
 * {@see autoship_scheduled_orders_app_display_template} can be used to
 * override the php template file name.
 * {@see autoship_scheduled_orders_app_path} can be used to filter the app
 * path url.
 *
 * @param int $customer_id The wc_customer id.
 * @param int $autoship_customer_id The Autoship customer id.
 * @return string Output of the template file.
 */
function autoship_scheduled_orders_app_display ( $customer_id, $autoship_customer_id, $template = 'scheduled-orders' ) {

  // Get the template to use if not supplied
  $template = empty( $template ) ? 'scheduled-orders' : $template;

  // Load the embedded scheduled orders app
  // Set the default app_path
  $app_path = plugin_dir_url( Autoship_Plugin_File ) . 'scheduled-orders/dist/';

  // Check for an override app_path
  $override_app_dir = get_stylesheet_directory() . '/'.Autoship_Plugin_Folder_Name.'/scheduled-orders/dist/';
  if ( file_exists( $override_app_dir ) ) {

    // Override app_path
    $override_app_path = get_stylesheet_directory_uri() . '/'.Autoship_Plugin_Folder_Name.'/scheduled-orders/dist/';
    $app_path = $override_app_path;

  }

  // Filter the app_path
  $app_path = apply_filters( 'autoship_scheduled_orders_app_path', $app_path );

  // Load the app
  $app_params = array(
    'settings_url' => autoship_get_relative_uri(
      admin_url( '/admin-ajax.php?action=autoship_get_scheduled_orders_settings' )
    ),
    'autoship_version' => Autoship_Version
  );

  $app_url = apply_filters(
    'autoship_scheduled_orders_app_url',
    $app_path . '?' . http_build_query( $app_params ),
    $app_path,
    $app_params
  );

  return autoship_render_template(
    apply_filters(
      'autoship_scheduled_orders_app_display_template',
      $template,
      $customer_id,
      $autoship_customer_id ),
    array(
    'app_url'              => $app_url,
    'customer_id'          => $customer_id,
    'autoship_customer_id' => $autoship_customer_id
    )
  );

}

/**
 * Display Scheduled Orders Hosted iframe.
 *
 * Includes the following filters to adjust settings
 * {@see autoship_scheduled_orders_hosted_url_params} can be used to
 * change the url parameters used in the iframe url.
 * {@see autoship_scheduled_orders_hosted_display_template} can be used to
 * override the php template file name.
 *
 * @param int $customer_id The wc_customer id.
 * @param int $autoship_customer_id The Autoship customer id.
 * @return string Output of the template file.
 */
function autoship_scheduled_orders_hosted_display ( $customer_id, $autoship_customer_id, $template = 'scheduled-orders' ) {

  // Get the template to use if not supplied
  $template = empty( $template ) ? 'scheduled-orders' : $template;

  $autoship_orders = autoship_get_customer_scheduled_orders ( $customer_id );

  // Add check in case API error occurs
  if ( empty( $autoship_orders ) || is_wp_error( $autoship_orders  ) ){

    $template = 'scheduled-orders/orders-error-template';

    $args = array(
      'page'                      => 1,
      'paginate'                  => false,
      'autoship_orders'           => $autoship_orders,
      'customer_id'               => $customer_id,
      'autoship_customer_id'      => $autoship_customer_id
    );

  } else {

    // Get an instance of the QPilot Client
    $client = autoship_get_default_client();

    // Get the access token from the client.
    try {

      $accessToken = $client->generate_customer_access_token( $autoship_customer_id, autoship_get_client_secret() );

    } catch ( Exception $e ) {

      if ( 401 == $e->getCode() ) {
        try {
          autoship_refresh_token_auth();
          $url = $_SERVER['REQUEST_URI'];
          return sprintf( '<script>window.location = %s;</script>', json_encode( $url ) );
        } catch (Exception $e) {
          return sprintf( __( 'Error %1$s: %2$s', 'autoship' ), strval( $e->getCode() ), $e->getMessage() );
        }
      } elseif ( 403 == $e->getCode() ) {
        delete_user_meta( $customer_id, '_autoship_customer_id' );
      } else {
        return sprintf( __( 'Error %1$s: %2$s', 'autoship' ), strval( $e->getCode() ), $e->getMessage() );
      }

    }

    // Gather the iframe url.
    // Filter allows for adding additional query strings to iframe passed url.
    $app_params = apply_filters( 'autoship_scheduled_orders_hosted_url_params', array(
      'tokenBearerAuth' => $accessToken->tokenBearerAuth
    ), $customer_id, $autoship_customer_id );

    $app_url = sprintf( '%s/widgets/scheduled-orders/%d/%d?%s',
      autoship_get_merchants_url(),
      $client->get_site_id(),
      $autoship_customer_id,
      http_build_query( $app_params )
    );

    $args = array(
      'app_url'              => $app_url,
      'customer_id'          => $customer_id,
      'autoship_customer_id' => $autoship_customer_id
    );

  }

  // return the template contents.
  return autoship_render_template(
    apply_filters(
      'autoship_scheduled_orders_hosted_display_template',
      $template,
      $customer_id,
      $autoship_customer_id ),
    $args
  );

}

/**
 * Display Scheduled Orders Hosted iframe.
 *
 * Includes the following filters to adjust settings
 * {@see autoship_scheduled_orders_hosted_url_params} can be used to
 * change the url parameters used in the iframe url.
 * {@see autoship_scheduled_orders_hosted_display_template} can be used to
 * override the php template file name.
 *
 * @param int $customer_id The wc_customer id.
 * @param int $autoship_customer_id The Autoship customer id.
 * @param string $template Optional. The Autoship Scheduled Orders page template.
 * @return string Output of the template file.
 */
function autoship_scheduled_orders_template_display ( $customer_id, $autoship_customer_id, $template = 'scheduled-orders/orders-template' , $page = 1, $paginate = false ){

    // Get the template to use if not supplied
    $template = empty( $template ) ? 'scheduled-orders/orders-template' : $template;

    $autoship_orders = array();

    // Check rights and bail if the customer doesn't have rights to this view nor order
    if ( !autoship_rights_checker( 'autoship_scheduled_orders_template_display', array(), $customer_id )  ){

      $template = apply_filters(
        'autoship_scheduled_orders_template_display_no_rights_redirect_template',
        'scheduled-orders/order-error-template', $customer_id );

      $autoship_orders = new WP_Error(
        sprintf( __( 'Invalid %s Display', 'autoship' ), autoship_translate_text( 'Scheduled Orders' ) ),
        sprintf( __( 'A problem was encountered while trying to display the %s. Please refresh the page and try again.', 'autoship' ),
        autoship_translate_text( 'Scheduled Orders' )
        )
      );

    } else {

      $autoship_orders = autoship_get_customer_scheduled_orders ( $customer_id );

      // Add check in case API error occurs
      if ( empty( $autoship_orders ) || is_wp_error( $autoship_orders  ) )
      $template = 'scheduled-orders/orders-error-template';

      // Add check for single or multiple scheduled orders and redirect to single if only one.
      if ( apply_filters( 'autoship_scheduled_orders_template_display_single_order_redirect_template', !is_wp_error( $autoship_orders ) && ( count( $autoship_orders ) == 1 ), $autoship_orders, $customer_id, $autoship_customer_id ) ){
        autoship_scheduled_order_template_display ( $customer_id, $autoship_customer_id, current( $autoship_orders )->id );
        return;
      }

    }

    // Allow for the filtering of the orders.
    $autoship_orders = apply_filters( 'autoship_scheduled_orders_template_display_orders', $autoship_orders, $customer_id, $autoship_customer_id );

    return autoship_render_template(
      apply_filters(
        'autoship_scheduled_orders_template_display',
        $template,
        $customer_id,
        $autoship_customer_id ),
      array(
        'page'                      => absint( $page ),
        'paginate'                  => (bool) $paginate,
        'autoship_orders'           => $autoship_orders,
        'customer_id'               => $customer_id,
        'autoship_customer_id'      => $autoship_customer_id,

        // Only used for the order error template display
        'autoship_order'            => !empty( $autoship_orders ) && !is_wp_error( $autoship_orders ) ? reset( $autoship_orders ) : $autoship_orders
    ) );

}

/**
 * Display the View/Edit Scheduled Order native template.
 *
 * @param int $customer_id The wc_customer id.
 * @param int $autoship_customer_id The Autoship customer id.
 * @param int $autoship_order_id The Autoship order id.
 * @param string $template Optional. The Autoship Scheduled Orders page template.
 *
 * @return string Output of the template file.
 */
function autoship_scheduled_order_template_display ( $customer_id, $autoship_customer_id, $autoship_order_id, $template = 'scheduled-orders/order-template' ) {

    // Get the template to use if not supplied
    $template = empty( $template ) ? 'scheduled-orders/order-template' : $template;

    $autoship_order = $settings = array();
    $autoship_order = autoship_get_scheduled_orders( $autoship_order_id );
    
    $autoship_order = autoship_filter_non_customer_visible_orders ( array( $autoship_order ) );
    $autoship_order = !empty( $autoship_order ) ? autoship_convert_object_to_array( $autoship_order[0] ) : array();
    
    // Add Security - double check this user is owner of order.
    // Check rights and bail if the customer doesn't have rights to view this order
    if ( autoship_rights_checker( 'autoship_scheduled_order_template_display', array('administrator'), $customer_id ) || 
          ($autoship_order['customerId'] == $autoship_customer_id )
    ){      
      // Get the Site Settings that include Lock Duration etc.
      $settings = autoship_get_site_order_settings();

      // Add check in case API error occurs
      if ( empty( $autoship_order ) || is_wp_error( $autoship_order  ) ){

        $template = 'scheduled-orders/order-error-template';

      } else {
        // Check for Duration Lock and swap template if exists.
        $lock_data = autoship_check_lock_status_info ( $autoship_order, $customer_id, $settings );

        // Attach the Lock Data so we don't need to call it again.
        $autoship_order['lock_data'] = $lock_data;

        if ( $lock_data['locked'] )
        $template = 'scheduled-orders/order-lock-template';

      }

    } else {
      $template = apply_filters(
        'autoship_scheduled_orders_template_display_no_rights_redirect_template',
        'scheduled-orders/order-error-template', $customer_id );
  
      $autoship_order = new WP_Error(
        sprintf( __( '404 %s Not Found', 'autoship' ), autoship_translate_text( 'Scheduled Order' ) ),
        sprintf( __( '%s #%s could not be found or is invalid. Please check the %s number and try again.', 'autoship' ),
          autoship_translate_text( 'Scheduled Order' ) ,
          '<mark class="order-number">' . $autoship_order_id . '</mark>',
          autoship_translate_text( 'Scheduled Order' )
          )
        );
    }

    return autoship_render_template(
      apply_filters(
        'autoship_scheduled_order_display_template',
        $template,
        $customer_id,
        $autoship_customer_id,
        $autoship_order_id ),
      array(
        'autoship_order'            => $autoship_order,
        'customer_id'               => $customer_id,
        'autoship_customer_id'      => $autoship_customer_id,
        'autoship_order_id'         => $autoship_order_id,
        'settings'                  => $settings
    ) );


}

/**
 * Display the View Scheduled Order native template.
 *
 * @param int $customer_id The wc_customer id.
 * @param int $autoship_customer_id The Autoship customer id.
 * @param int $autoship_order_id The Autoship order id.
 * @param string $template Optional. The Autoship Scheduled Orders page template.
 *
 * @return string Output of the template file.
 */
function autoship_view_scheduled_order_template_display ( $customer_id, $autoship_customer_id, $autoship_order_id, $template = 'scheduled-orders/order-view-template' ) {

    // Get the template to use if not supplied
    $template = empty( $template ) ? 'scheduled-orders/order-view-template' : $template;
    $autoship_order = array();

    // Add Security - double check this user is owner of order.
    if ( !autoship_rights_checker( 'autoship_scheduled_order_template_display', array(), $customer_id )  ){

      $template = apply_filters( 'autoship_scheduled_order_template_display_no_rights_redirect_template', 'scheduled-orders/order-error-template', $customer_id );

      $autoship_orders = new WP_Error(
        sprintf( __( '404 %s Not Found', 'autoship' ), autoship_translate_text( 'Scheduled Order' ) ),
        sprintf( __( '%s #%s could not be found or is invalid. Please check the %s number and try again.', 'autoship' ),
          autoship_translate_text( 'Scheduled Order' ) ,
          '<mark class="order-number">' . $autoship_order_id . '</mark>',
          autoship_translate_text( 'Scheduled Order' )
          )
        );

    } else {

      // Add Security - double check this user is owner of order.
      $autoship_order = autoship_get_scheduled_orders( $autoship_order_id );
      $autoship_order = autoship_filter_non_customer_visible_orders ( array( $autoship_order ) );
      $autoship_order = !empty( $autoship_order ) ? autoship_convert_object_to_array( $autoship_order[0] ) : array();

      // Add check in case API error occurs
      if ( empty( $autoship_order ) || is_wp_error( $autoship_order  ) )
      $template = 'scheduled-orders/order-error-template';

    }

    return autoship_render_template(
      apply_filters(
        'autoship_scheduled_order_display_template',
        $template,
        $customer_id,
        $autoship_customer_id,
        $autoship_order_id ),
      array(
        'autoship_order'            => $autoship_order,
        'customer_id'               => $customer_id,
        'autoship_customer_id'      => $autoship_customer_id,
        'autoship_order_id'         => $autoship_order_id,
    ) );

}


// ==========================================================
// HOOKED TEMPLATE DISPLAY FUNCTIONS
// ==========================================================

/**
* Displays Any WP Notices not yet displayed.
*
* @param int    $customer_id The woocommerce customer id.
* @param int    $autoship_customer_id The autoship customer id.
*
* @return string The html output
*/
function autoship_scheduled_orders_header_wp_notices_display ( $customer_id, $autoship_customer_id ){

  wc_print_notices();
  wc_clear_notices();

}

/**
* Displays the Scheduled Orders Custom HTML Header
* @return string The html output
*/
function autoship_scheduled_orders_custom_html_header_display (){

  ?>

  <div class="autoship-scheduled-orders-custom-header">

  <?php

  // Get the Text from Admin Settings
  echo apply_filters( "the_content", autoship_get_scheduled_orders_html() );?>

  </div>

  <?php

}


/**
* Displays Any WP Notices not yet displayed.
*
* @param int    $customer_id The woocommerce customer id.
* @param int    $autoship_customer_id The autoship customer id.
*
* @return string The html output
*/
function autoship_scheduled_orders_header_actions ( $customer_id, $autoship_customer_id, $autoship_orders ){

  if ( apply_filters( 'autoship_hide_scheduled_orders_header_actions_on_no_orders', empty( $autoship_orders ), $customer_id, $autoship_orders ) )
  return;

  $label = autoship_translate_text( 'Scheduled Order' );

  $actions = apply_filters( 'autoship_scheduled_orders_header_actions', array(
    'autoship-create-new-order' => sprintf( '<a href="%s" class="button autoship-action-btn">%s</a>', autoship_get_scheduled_order_create_url( array('customer' => $customer_id ) ), __("Create New {$label}", 'autoship') )
  ), $customer_id, $autoship_customer_id, $autoship_orders );

  ob_start();?>

  <div class="autoship-scheduled-orders-header-actions">

    <?php foreach ($actions as $action => $html ) echo $html; ?>

  </div>

  <?php
  echo ob_get_clean();

}

/**
* Displays the Scheduled Order Row Notice
*
* @param array  $autoship_order The autoship order array.
* @param int    $customer_id The woocommerce customer id.
* @param int    $autoship_customer_id The autoship customer id.
*
* @return string The html output
*/
function autoship_scheduled_orders_row_notice_template_display ( $autoship_order, $customer_id, $autoship_customer_id ){

  return autoship_render_template(
    apply_filters(
      'autoship_scheduled_orders_display_template',
      'scheduled-orders/orders-row-notice-template',
      $customer_id,
      $autoship_customer_id ),
    array(
      'autoship_order'            => $autoship_order,
      'customer_id'               => $customer_id,
      'autoship_customer_id'      => $autoship_customer_id
  ) );

}

/**
* Displays the Scheduled Orders Error template Custom HTML Body if needed
* for the embedded and iframe versions
*
* @param int    $customer_id The woocommerce customer id.
* @param int    $autoship_customer_id The autoship customer id.
*
* @return string The html output
*/
function autoship_non_hosted_scheduled_order_error_display(  $customer_id, $autoship_customer_id ){

  // Since the Embedded and Hosted Apps don't load the orders directly we will
  // If no scheduled orders include the custom html template.
  $autoship_orders = autoship_get_customer_scheduled_orders ( $customer_id );
  if ( empty( $autoship_orders ) && !is_wp_error( $autoship_orders ) )
  autoship_scheduled_order_error_template_custom_html_display ( $autoship_orders, $customer_id, $autoship_customer_id );

}

/**
* Displays the Scheduled Orders Error template Custom HTML Body
*
* @param int    $customer_id The woocommerce customer id.
* @param int    $autoship_customer_id The autoship customer id.
* @param array  $autoship_order The autoship orders array.
*
* @return string The html output
*/
function autoship_scheduled_order_error_template_custom_html_display ( $autoship_orders, $customer_id, $autoship_customer_id ){

  if ( is_wp_error( $autoship_orders ) )
  return;

  ?>

  <div class="autoship-scheduled-order-custom-content">

  <?php

  // Get the Text from Admin Settings
  echo apply_filters( "the_content", autoship_get_scheduled_orders_body_html() );?>

  </div>

  <?php

}

/**
* Displays the Scheduled Orders Error template Notice
*
* @param int    $customer_id The woocommerce customer id.
* @param int    $autoship_customer_id The autoship customer id.
* @param array  $autoship_order The autoship orders array.
*
* @return string The html output
*/
function autoship_scheduled_order_error_template_notice_display ( $customer_id, $autoship_customer_id, $autoship_orders ){

  if ( ! is_wp_error( $autoship_orders ) )
  return;

  $notice = apply_filters( 'autoship_scheduled_orders_error_notice',
  $autoship_orders->get_error_code(),
  $autoship_orders, $customer_id, $autoship_customer_id );

  $notice_details = apply_filters( 'autoship_scheduled_orders_error_notice_details',
  $details  = $autoship_orders->get_error_message(),
  $autoship_orders, $customer_id, $autoship_customer_id );

  if ( empty( $notice ) && empty( $notice_details ) )
  return;

  ?>

  <h3 class="autoship-error-notice"><span><?php echo $notice;?></span></h3>
  <p class="autoship-error-notice-details"><?php echo $notice_details;?></p>

  <?php

}

/**
* Displays the Scheduled Order Header
*
* @param array  $autoship_order The autoship order array.
* @param int    $customer_id The woocommerce customer id.
* @param int    $autoship_customer_id The autoship customer id.
*
* @return string The html output
*/
function autoship_scheduled_order_header_template_display ( $autoship_order, $customer_id, $autoship_customer_id ){

  return autoship_render_template(
    apply_filters(
      'autoship_scheduled_order_display_template',
      'scheduled-orders/order-header-template',
      $customer_id,
      $autoship_customer_id ),
    array(
      'autoship_order'            => $autoship_order,
      'customer_id'               => $customer_id,
      'autoship_customer_id'      => $autoship_customer_id
  ) );

}

/**
* Displays the Scheduled Order Header Notice for Duration Locked Orders
*
* @param array  $autoship_order The autoship order array.
* @param int    $customer_id The woocommerce customer id.
* @param int    $autoship_customer_id The autoship customer id.
*
* @return string The html output
*/
function autoship_scheduled_order_header_locked_notice_display ( $autoship_order, $customer_id, $autoship_customer_id ){

  // Check for Duration Lock and swap template if exists.
  if ( !isset( $autoship_order['lock_data'] ) || empty( $autoship_order['lock_data'] ) )
  $autoship_order['lock_data'] = autoship_check_lock_status_info ( $autoship_order, $customer_id );

  if ( $autoship_order['lock_data']['locked'] )
  echo apply_filters( 'autoship_scheduled_order_header_locked_notice_display', '<p class="autoship_order_notice woocommerce-message woocommerce-notice woocommerce-info" role="alert">' . $autoship_order['lock_data']['notice'] . '</p>' , $autoship_order, $customer_id );

}

/**
* Displays the Scheduled Order Schedule Summary Section
*
* @param array  $autoship_order The autoship order array.
* @param int    $customer_id The woocommerce customer id.
* @param int    $autoship_customer_id The autoship customer id.
*
* @return string The html output
*/
function autoship_scheduled_order_schedule_summary_template_display ( $autoship_order, $customer_id, $autoship_customer_id ){

  return autoship_render_template(
    apply_filters(
      'autoship_scheduled_order_display_template',
      'scheduled-orders/order-schedule-summary-template',
      $customer_id,
      $autoship_customer_id ),
    array(
      'autoship_order'            => $autoship_order,
      'customer_id'               => $customer_id,
      'autoship_customer_id'      => $autoship_customer_id
  ) );

}

/**
* Displays the Main Scheduled Order items Template ( Non-editable)
*
* @param array  $autoship_order The autoship order array.
* @param int    $customer_id The woocommerce customer id.
* @param int    $autoship_customer_id The autoship customer id.
*
* @return string The html output
*/
function autoship_scheduled_order_items_template_display ( $autoship_order, $customer_id, $autoship_customer_id ){

  return autoship_render_template(
    apply_filters(
      'autoship_scheduled_order_display_template',
      'scheduled-orders/order-items-template',
      $customer_id,
      $autoship_customer_id ),
    array(
      'autoship_order'            => $autoship_order,
      'customer_id'               => $customer_id,
      'autoship_customer_id'      => $autoship_customer_id
  ) );

}

/**
* Hides the Scheduled Order Item Remove link for Cycle Items
*
* @param string $link The html remove item string
* @param string $key  The order item unique key.
* @param array  $item The scheduled order item data.
*
* @return string The html output
*/
function autoship_scheduled_order_item_remove_modify_for_cycle_item( $link, $key, $item ){

  return $item['cycle_item'] && !apply_filters( 'autoship_scheduled_order_allow_removal_of_cycle_item', true, $item['cycle_item'], $item ) ? '<i class="dashicons dashicons-lock"></i>' : $link;

}

/**
* Adjusts the Scheduled Order Item Qty field for Cycle items
*
* @param int    $product_quantity The quantity value for this item.
* @param array  $item The scheduled order item data.
* @param array  $scheduled_item The scheduled order raw item data.
*
* @return string The html output
*/
function autoship_scheduled_order_item_qty_modify_for_cycle_item( $product_quantity, $item, $scheduled_item ){

  return !$item['cycle_item'] ? $product_quantity : sprintf( '<span>%d<span/>', $item['qty'] );

}

/**
* Displays the Scheduled Order Item Meta
*
* @param array  $meta_data The scheduled order item meta data.
* @param array  $scheduled_item The scheduled order raw item data.
* @param array  $autoship_order The autoship order.
* @param int    $customer_id The woocommerce customer id.
* @param int    $autoship_customer_id The autoship customer id.
*
* @return string The html output
*/
function autoship_scheduled_order_item_cycle_notice ( $item, $scheduled_item, $autoship_order, $customer_id, $autoship_customer_id ){

  if ( $item['cycle_item'] && $item['valid_for_cycle'] && isset( $item['max_cycles'] ) ) {

    $single_notice = apply_filters( 'autoship_scheduled_order_item_single_cycle_notice',
    __( '1 time only.', 'autoship' ), $item );

    $multiple_notice = apply_filters( 'autoship_scheduled_order_item_multiple_cycle_notice',
    sprintf( __( '%d times only.', 'autoship' ), $item['max_cycles'] - $item['cycles'] ),
    __( '%d Times Only.' ),
    $item['max_cycles'] - $item['cycles'],
    $item );

    $notice = ( $item['max_cycles'] - $item['cycles'] ) > 1 ?
    $multiple_notice : $single_notice;

    if ( ( $item['max_cycles'] != $item['cycles'] ) && ( $item['max_cycles'] > $item['cycles'] ) && ( $item['max_cycles'] >= $item['min_cycles'] ) )
    echo apply_filters( 'autoship_scheduled_order_item_cycle_notice',
    sprintf( '%s %s %s' , '<span class="autoship-item-notice"><i class="dashicons dashicons-calendar-alt"></i>', $notice, '</span>' ),
    $notice, $item );

  }

}

/**
* Displays the Scheduled Order Item Stock Notices
*
* @param array  $item The scheduled order item data.
* @param array  $scheduled_item The scheduled order raw item data.
* @param array  $autoship_order The autoship order.
* @param int    $customer_id The woocommerce customer id.
* @param int    $autoship_customer_id The autoship customer id.
*
* @return string The html output
*/
function autoship_scheduled_order_item_stock_notice_display ( $item, $scheduled_item, $autoship_order, $customer_id, $autoship_customer_id ){

  if ( 'instock' != $item['stock_status'] )
  echo apply_filters( 'autoship_scheduled_order_item_stock_notice', sprintf( '<span class="autoship-item-notice"><i class="dashicons dashicons-warning"></i>%s</span>',  __( 'Out of Stock', 'autoship' ) ), $item, $scheduled_item, $autoship_order, $customer_id, $autoship_customer_id );

}

/**
* Displays the Scheduled Order Item Meta
*
* @param array  $item The scheduled order item data.
* @param array  $scheduled_item The scheduled order raw item data.
* @param array  $autoship_order The autoship order.
* @param int    $customer_id The woocommerce customer id.
* @param int    $autoship_customer_id The autoship customer id.
*
* @return string The html output
*/
function autoship_scheduled_order_item_meta_template_display ( $item, $scheduled_item, $autoship_order, $customer_id, $autoship_customer_id ){

  $meta_data = array( $item['sku'], $item['meta'] );

  return autoship_render_template(
    apply_filters(
      'autoship_scheduled_order_display_template',
      'scheduled-orders/order-item-meta-template',
      $customer_id,
      $autoship_customer_id ),
    array(
      'meta_data'                 => $meta_data,
      'scheduled_item'            => $scheduled_item,
      'autoship_order'            => $autoship_order,
      'customer_id'               => $customer_id,
      'autoship_customer_id'      => $autoship_customer_id
  ) );

}

/**
* Displays the Scheduled Order Item Meta for Newly Added Items via Ajax
*
* @param array  $meta_data  The scheduled order item meta data.
* @param array  $product    The Autoship Product data.
*
* @return string The html output
*/
function autoship_scheduled_order_add_item_meta_template_display ( $meta_data, $product ){

  return autoship_render_template(
    apply_filters(
      'autoship_scheduled_order_display_template',
      'scheduled-orders/order-add-item-meta-template',
      0,
      0 ),
    array(
      'meta_data'                 => $meta_data,
      'product'                   => $product
  ) );

}

/**
* Displays the Scheduled Order No Items template
*
* @param array  $autoship_order The autoship order.
* @param int    $customer_id The woocommerce customer id.
* @param int    $autoship_customer_id The autoship customer id.
*
* @return string The html output
*/
function autoship_scheduled_order_no_items_template_display ( $autoship_order, $customer_id, $autoship_customer_id ){

  return autoship_render_template(
    apply_filters(
      'autoship_scheduled_order_display_template',
      'scheduled-orders/order-no-items-template',
      $customer_id,
      $autoship_customer_id ),
    array(
      'autoship_order'            => $autoship_order,
      'customer_id'               => $customer_id,
      'autoship_customer_id'      => $autoship_customer_id
  ) );

}


/**
* Displays the Scheduled Order Totals Summary
*
* @param array  $autoship_order The autoship order array.
* @param int    $customer_id The woocommerce customer id.
* @param int    $autoship_customer_id The autoship customer id.
*
* @return string The html output
*/
function autoship_scheduled_order_totals_summary_template_display ( $autoship_order, $customer_id, $autoship_customer_id, $autoship_order_items ){

  return autoship_render_template(
    apply_filters(
      'autoship_scheduled_order_display_template',
      'scheduled-orders/order-totals-template',
      $customer_id,
      $autoship_customer_id ),
    array(
      'autoship_order'            => $autoship_order,
      'customer_id'               => $customer_id,
      'autoship_customer_id'      => $autoship_customer_id,
      'autoship_order_items'      => $autoship_order_items
    ) );

}

/**
* Displays the Scheduled Order Payment Method Summary
*
* @param array  $autoship_order The autoship order array.
* @param int    $customer_id The woocommerce customer id.
* @param int    $autoship_customer_id The autoship customer id.
*
* @return string The html output
*/
function autoship_scheduled_order_payment_method_summary_template_display ( $autoship_order, $customer_id, $autoship_customer_id ){

  if ( apply_filters( 'autoship_exclude_payment_method_summary_on_empty_order', empty( $autoship_order['scheduledOrderItems'] ) , $autoship_order, $customer_id, $autoship_customer_id ) )
  return;

  return autoship_render_template(
    apply_filters(
      'autoship_scheduled_order_display_template',
      'scheduled-orders/order-payment-method-summary-template',
      $customer_id,
      $autoship_customer_id ),
    array(
      'autoship_order'            => $autoship_order,
      'customer_id'               => $customer_id,
      'autoship_customer_id'      => $autoship_customer_id
    ) );

}

/**
* Displays the Scheduled Order Address Info
*
* @param array  $autoship_order The autoship order array.
* @param int    $customer_id The woocommerce customer id.
* @param int    $autoship_customer_id The autoship customer id.
* @param string $context The current context ( view or edit ).
*
* @return string The html output
*/
function autoship_scheduled_order_address_template_display ( $autoship_order, $customer_id, $autoship_customer_id, $context = 'view' ){
  return autoship_render_template(
    apply_filters(
      'autoship_scheduled_order_display_template',
      'scheduled-orders/order-address-template',
      $customer_id,
      $autoship_customer_id ),
    array(
      'autoship_order'            => $autoship_order,
      'customer_id'               => $customer_id,
      'autoship_customer_id'      => $autoship_customer_id,
      'context'                   => $context
    ) );

}

/**
* Displays the Scheduled Order Address Info in View Context
*
* @param array  $autoship_order The autoship order array.
* @param int    $customer_id The woocommerce customer id.
* @param int    $autoship_customer_id The autoship customer id.
*
* @return string The html output
*/
function autoship_scheduled_order_address_view_template_display ( $autoship_order, $customer_id, $autoship_customer_id ){
  autoship_scheduled_order_address_template_display ( $autoship_order, $customer_id, $autoship_customer_id, 'view' );
}

/**
* Displays the Scheduled Order Address Info in Edit Context
*
* @param array  $autoship_order The autoship order array.
* @param int    $customer_id The woocommerce customer id.
* @param int    $autoship_customer_id The autoship customer id.
*
* @return string The html output
*/
function autoship_scheduled_order_address_edit_template_display ( $autoship_order, $customer_id, $autoship_customer_id ){
  autoship_scheduled_order_address_template_display ( $autoship_order, $customer_id, $autoship_customer_id, 'edit' );
}

/**
* Displays the Scheduled Order Shipping Address Form
*
* @param array  $autoship_order The autoship order array.
* @param int    $customer_id The woocommerce customer id.
* @param int    $autoship_customer_id The autoship customer id.
*
* @return string The html output
*/
function autoship_scheduled_order_address_form_display ( $autoship_order, $customer_id, $autoship_customer_id ){

  // Retrieve the Scheduled Orders Address values.
	$autoship_order_values = autoship_order_address_values( $autoship_order, 'shipping' );

  // Retrieve the Address fields.
	$address_fields = autoship_shipping_address_form_fields( $autoship_order_values );

	// Enqueue scripts.
	wp_deregister_script( 'wc-address-i18n' );
	wp_dequeue_script( 'wc-address-i18n' );

	// denqueue scripts.
  wp_deregister_script( 'wc-country-select' );
  wp_dequeue_script( 'wc-country-select' );

  // Load the country data for our Edit Scheduled Order Form in the Native UI
  $data = array(
  	'i18n_select_state_text' => esc_attr__( 'Select an option&hellip;', 'woocommerce' ),
    'countries' => wp_json_encode( array_merge( WC()->countries->get_allowed_country_states(), WC()->countries->get_shipping_country_states() ) )
  );
	wp_localize_script( 'autoship-scheduled-orders', 'wc_country_select_params', apply_filters( 'wc_country_select_params', $data ) );

  $data = array(
    'locale'             => wp_json_encode( WC()->countries->get_country_locale() ),
    'locale_fields'      => wp_json_encode( WC()->countries->get_country_locale_field_selectors() ),
    'i18n_required_text' => esc_attr__( 'required', 'woocommerce' ),
    'i18n_optional_text' => esc_html__( 'optional', 'woocommerce' ),
  );
	wp_localize_script( 'autoship-scheduled-orders', 'wc_address_i18n_params', apply_filters( 'wc_address_i18n_params', $data ) );

  return autoship_render_template(
    apply_filters(
      'autoship_scheduled_order_display_template',
      'scheduled-orders/order-address-form',
      $customer_id,
      $autoship_customer_id ),
    array(
      'address'                   => $address_fields,
      'autoship_order'            => $autoship_order,
      'customer_id'               => $customer_id,
      'autoship_customer_id'      => $autoship_customer_id
    ) );

}

// ==========================================================
// HOOKED FORM DISPLAY FUNCTIONS
// ==========================================================

/**
* Displays the Scheduled Order Schedule Form
* to edit Frequency and Next Occurrence
*
* @param array  $autoship_order The autoship order array.
* @param int    $customer_id The woocommerce customer id.
* @param int    $autoship_customer_id The autoship customer id.
*
* @return string The html output
*/
function autoship_scheduled_order_schedule_form_display ( $autoship_order, $customer_id, $autoship_customer_id ){

  return autoship_render_template(
    apply_filters(
      'autoship_scheduled_order_display_template',
      'scheduled-orders/order-schedule-form',
      $customer_id,
      $autoship_customer_id ),
    array(
      'autoship_order'            => $autoship_order,
      'customer_id'               => $customer_id,
      'autoship_customer_id'      => $autoship_customer_id
  ) );

}

/**
* Displays the Main Scheduled Order items Form
* to edit scheduled order line items
*
* @param array  $autoship_order The autoship order array.
* @param int    $customer_id The woocommerce customer id.
* @param int    $autoship_customer_id The autoship customer id.
*
* @return string The html output
*/
function autoship_scheduled_order_items_form_display ( $autoship_order, $customer_id, $autoship_customer_id ){

  return autoship_render_template(
    apply_filters(
      'autoship_scheduled_order_display_template',
      'scheduled-orders/order-items-form',
      $customer_id,
      $autoship_customer_id ),
    array(
      'autoship_order'            => $autoship_order,
      'customer_id'               => $customer_id,
      'autoship_customer_id'      => $autoship_customer_id
  ) );

}


/**
* Displays the Main Scheduled Order items Update Action Section Form
*
* @param array  $autoship_order The autoship order array.
* @param int    $customer_id The woocommerce customer id.
* @param int    $autoship_customer_id The autoship customer id.
*
* @return string The html output
*/
function autoship_scheduled_order_items_action_form_display ( $autoship_order, $customer_id, $autoship_customer_id ){

  return autoship_render_template(
    apply_filters(
      'autoship_scheduled_order_display_template',
      'scheduled-orders/order-items-action-form',
      $customer_id,
      $autoship_customer_id ),
    array(
      'autoship_order'            => $autoship_order,
      'customer_id'               => $customer_id,
      'autoship_customer_id'      => $autoship_customer_id
  ) );

}


/**
* Displays the Scheduled Order items Update Coupon Action Section Form
*
* @param array  $autoship_order The autoship order array.
* @param int    $customer_id The woocommerce customer id.
* @param int    $autoship_customer_id The autoship customer id.
*
* @return string The html output
*/
function autoship_scheduled_order_items_coupon_action_form_display ( $autoship_order, $customer_id, $autoship_customer_id ){

  return autoship_render_template(
    apply_filters(
      'autoship_scheduled_order_display_template',
      'scheduled-orders/order-items-coupon-action-form',
      $customer_id,
      $autoship_customer_id ),
    array(
      'autoship_order'            => $autoship_order,
      'customer_id'               => $customer_id,
      'autoship_customer_id'      => $autoship_customer_id
  ) );

}

/**
* Displays the Scheduled Order Shipping Rate Form
* to edit a preffered shipping rate associated with an order
*
* @param array  $autoship_order The autoship order array.
* @param int    $customer_id The woocommerce customer id
* @param int    $autoship_customer_id The Autoship customer id
*
* @return string The html output
*/
function autoship_scheduled_order_shipping_rate_form_display ( $autoship_order, $customer_id, $autoship_customer_id ){

  ob_start();
  ?>

  <div class="autoship-shipping-rate-modal">

    <?php
    echo autoship_render_template(
      apply_filters(
        'autoship_scheduled_order_display_template',
        'scheduled-orders/order-shipping-rate-form',
        $customer_id,
        $autoship_customer_id ),
      array(
        'autoship_order'            => $autoship_order,
        'customer_id'               => $customer_id,
        'autoship_customer_id'      => $autoship_customer_id,
    ) ); ?>

  </div>

  <?php
  $modal_content = ob_get_clean();
  autoship_generate_modal( 'autoship_shipping_rate_option_modal', $modal_content, 'autoship-form-modal medium',"", false );

}

/**
* Displays the Scheduled Order Payment Form
* to edit a payment method associated with an order
*
* @param array  $autoship_order The autoship order array.
* @param int    $customer_id The woocommerce customer id.
* @param int    $autoship_customer_id The autoship customer id.
*
* @return string The html output
*/
function autoship_scheduled_order_payment_form_display ( $autoship_order, $customer_id, $autoship_customer_id ){

  if ( apply_filters( 'autoship_exclude_payment_method_form_on_empty_order', empty( $autoship_order['scheduledOrderItems'] ) , $autoship_order, $customer_id, $autoship_customer_id ) )
  return;

  return autoship_render_template(
    apply_filters(
      'autoship_scheduled_order_display_template',
      'scheduled-orders/order-payment-form',
      $customer_id,
      $autoship_customer_id ),
    array(
      'autoship_order'            => $autoship_order,
      'customer_id'               => $customer_id,
      'autoship_customer_id'      => $autoship_customer_id
  ) );

}

/**
* Displays the Add Payment Method link
* @see wc_get_account_endpoint_url()
*
*/
function autoship_scheduled_order_payment_form_add_method_link( $autoship_order, $customer_id, $autoship_customer_id ){

  // Add Payment Methods endpoint link.
  $url = wc_get_account_endpoint_url( 'payment-methods' );

  echo apply_filters(
    'autoship_scheduled_order_payment_form_add_method_link',
    sprintf( '<div class="add-payment-method-link"><a href="%1$s">%2$s</a></div>', $url, __( 'Add New Payment Method', 'autoship' )),
    $autoship_order, $customer_id, $autoship_customer_id );

}

//button autoship-action-btn activate-action

/**
* Displays the Scheduled Order Display Payment Method form
*
* @return string The html output
*/
function autoship_edit_scheduled_order_payment_form_display_action() {

    /*
    * The Skins Filter Allows Devs to customize the action buttons.
    */
    $skin = apply_filters( 'autoship_edit_scheduled_order_action_button_skin', array(
      'action_btn' => 'button autoship-action-btn',
    ));

  ?>

    <div class="action-info"><a href="#" class="<?php echo $skin['action_btn']; ?> activate-action" data-toggle-text="<?php esc_html_e( 'Cancel Edit Payment Method', 'autoship' ); ?>" data-target=".autoship-edit-scheduled-order-payment-form"><?php esc_html_e( 'Edit Payment Method', 'autoship' ); ?></a>	</div>

  <?php
}

/**
* Displays the Scheduled Order Display Schedule form
*
* @param array  $autoship_order The autoship order array.
* @param int    $customer_id The woocommerce customer id.
* @param int    $autoship_customer_id The autoship customer id.
*
* @return string The html output
*/
function autoship_edit_scheduled_order_schedule_form_display_action( $autoship_order, $customer_id, $autoship_customer_id ) {

    if ( apply_filters( 'autoship_display_edit_schedule_form_actions_on_empty_order', false , $autoship_order, $customer_id, $autoship_customer_id ) )
    return;

    /*
    * The Skins Filter Allows Devs to customize the action buttons.
    */
    $skin = apply_filters( 'autoship_edit_scheduled_order_action_button_skin', array(
      'action_btn' => 'button autoship-action-btn',
      'action_span'=> '',
    ));

    $actions = autoship_get_account_scheduled_orders_actions( $autoship_order['id'], $autoship_order['status'], empty( $autoship_order['scheduledOrderItems'] ) ? 'empty-order' : 'order', $customer_id );

  ?>

    <div class="action-info main-actions">

      <a href="#" class="<?php echo $skin['action_btn']; ?> activate-action" data-toggle-text="<?php esc_html_e( 'Cancel Edit Schedule', 'autoship' ); ?>" data-target=".autoship-edit-scheduled-order-schedule-form"><span class="<?php echo $skin['action_span'];?>"><?php esc_html_e( 'Edit Schedule', 'autoship' ); ?></span></a>

      <?php
      if ( ! empty( $actions ) ) {
      foreach ( $actions as $key => $action ) { ?>

      <a title="<?php echo esc_html( $action['title'] ); ?>" href="<?php echo esc_url( $action['url'] );?>" class="<?php echo $skin['action_btn']; ?> <?php echo sanitize_html_class( strtolower( $key ) ); ?>" data-autoship-order="<?php echo $autoship_order['id']; ?>" data-autoship-action="<?php echo $key; ?>" data-autoship-view="order">
        <span class="<?php echo $skin['action_span'];?>"><?php esc_html_e( $action['name'] ); ?></span>
      </a>

      <?php }
      } ?>

  	</div>

    <?php
}

/**
* Displays the Close Button for the Shipping Rate Modal
*
* @param array  $autoship_order The autoship order array.
* @param int    $customer_id The woocommerce customer id.
* @param int    $autoship_customer_id The autoship customer id.
*
* @return string The html output
*/
function autoship_shipping_rate_form_close_modal_display_action( $autoship_order, $customer_id, $autoship_customer_id ){

  ?>

  <button class="button autoship-action-btn cancel"><?php echo apply_filters( 'autoship_scheduled_order_edit_preferred_shipping_rate_form_action_cancel_label', __('Cancel', 'autoship' )); ?></button>

  <?php

}

/**
* Displays the Refresh Shipping Rate link
*
* @param array  $autoship_order The autoship order array.
* @param int    $customer_id The woocommerce customer id.
* @param int    $autoship_customer_id The autoship customer id.
*
* @return string The html output
*/
function autoship_shipping_rate_form_refresh_rates_action( $autoship_order, $customer_id, $autoship_customer_id ){

  ?>

  <div class="refresh-rates-link"><a href="<?php echo autoship_refresh_scheduled_order_url( $autoship_order['id'] );?>"><?php echo __('Refresh Rates','autoship');?></a></div>

  <?php

}

// ==========================================================
// DEFAULT FILTERS APPLIED
// ==========================================================

/**
 * Filters out Deleted Orders for Customers..
 *
 * @param array $orders Array of Autoship Order stdClass objects
 * @return array The filtered array.
 */
function autoship_filter_non_customer_visible_orders ( $orders ){

  if ( empty( $orders ) )
  return $orders;

  if ( ! autoship_rights_checker( 'autoship_filter_non_customer_visible_orders', array('administrator') ) ){

    foreach ($orders as $key => $order) {

      if ( 'Deleted' == $order->status )
      unset( $orders[$key] );

    }

  }

  return $orders;

}

/**
* Hide ScheduledOrderFailureReason For Active Orders
* @NOTE The ScheduledOrderFailureReason clears on each successfull processing.
*
* @param string $notice The current notice or failure reason.
* @param array|stdClass $autoship_order The Autoship Scheduled Order Object or array.
*
* @return string The filtered Notice.
*/
function autoship_filter_notices_for_all_orders ( $notice, $autoship_order ){

  $status = is_array( $autoship_order )? $autoship_order['status'] : $autoship_order->status;

  return apply_filters( 'autoship_filter_notices_for_all_orders',
  ( 'Active' == $status || 'Pending' == $status ) ? '' : $notice, $status, $notice );

}

/**
* Displays the Scheduled Order Address Info in-page form Edit link.
*
* @param string $link The current edit link.
* @param array  $autoship_order The autoship order array.
* @param string $context The current context ( view or edit ).
*
* @return string The filtered html output
*/
function autoship_scheduled_order_address_edit_link_adjustment_display ( $link, $autoship_order, $context = 'view' ){

  return 'edit' == $context ? sprintf( __( '<a href="#" class="activate-action slim-autoship-action" data-toggle-text="%s" data-target=".autoship-edit-scheduled-order-shipping-address-form">%s</a>', 'autoship'), esc_html__( 'Cancel Edit Shipping Address', 'autoship' ), esc_html__( 'Update your shipping address.', 'autoship' ) ) : $link;

}

/**
* Hide Address Change Notices For Locked Orders
*
* @param string $subnote The current Address Subnote.
* @param array|stdClass $autoship_order The Autoship Scheduled Order Object or array.
*
* @return string The filtered Subnote.
*/
function autoship_filter_out_scheduled_order_address_subnotes ( $subnote, $autoship_order ){

  // Check for Duration Lock and swap template if exists.
  if ( !isset( $autoship_order['lock_data'] ) || empty( $autoship_order['lock_data'] ) )
  $autoship_order['lock_data'] = autoship_check_lock_status_info ( $autoship_order, $autoship_order['customer']['id'] );

  return $autoship_order['lock_data']['locked'] ? '' : $subnote;

}

/**
* Filters the Actions for the Main Scheduled Orders template
*
* @param array $actions The Autoship Scheduled Orders actions for the current row.
* @param array|stdClass $autoship_order The Autoship Scheduled Order Object or array.
* @param array $settings Optional. The Autoship Site Settings array.
*
* @return array The filtered actions.
*/
function autoship_filter_scheduled_orders_template_actions ( $actions, $autoship_order, $customer_id, $settings = array() ){

  // Convert stdClass objects to array if needed.
  if ( $autoship_order instanceof stdClass )
  $autoship_order = autoship_convert_object_to_array( $autoship_order );

  // Check for Duration Lock and swap template if exists.
  if ( !isset( $autoship_order['lock_data'] ) || empty( $autoship_order['lock_data'] ) )
  $autoship_order['lock_data'] = autoship_check_lock_status_info ( $autoship_order, $customer_id, $settings );

  if ( $autoship_order['lock_data']['locked'] ){

    foreach ($actions as $action => $values) {

      if ( 'Edit' != $action && 'View' != $action )
      unset( $actions[$action] );

    }

  }

  return $actions;

}

/**
* Should the product data in "Add" dropdown be coming from WC or Qpilot API(legacy).
*
* @return bool The filtered boolean.
*/
function autoship_filter_schedulable_products_use_wc_data() {
  $use_wc_data = autoship_get_legacy_support_qpilot_products_data() == 'yes' ? false : true;
  return apply_filters( 'autoship_filter_schedulable_products_use_wc_data', $use_wc_data );
}


// ==========================================================
// ADD NEW ITEM SCRIPT & HTML AJAX FUNCTIONS
// ==========================================================

/**
 * Outputs the Product Infor for Adding to Order
 *
 * @return string The html output
 */
function autoship_get_schedulable_products_script_data( $reply_type = 'output_return' ){
  $uses_wc_data = autoship_filter_schedulable_products_use_wc_data();

  $all_products = $uses_wc_data ? autoship_get_schedulable_products_from_wc() :  autoship_get_schedulable_products_from_qpilot();

  // Check if error
  if ( is_wp_error( $all_products ) ){

    do_action( "autoship_after_{$action}_get_autoship_get_available_products_failure", $all_products );
    wc_add_notice( __( 'An issue was encountered while trying to retrieve the available products. <br/>Additional Details: ' . $all_products->get_error_message(), 'autoship' ),  'error' );
    $all_products = '';

  }

  $all_products = apply_filters('autoship_filter_schedulable_products_data', $all_products );

  // Store the pulled products from Qpilot API into the session if not empty,
  // If empty clear session since there are no available products.
  if ( !$uses_wc_data ) {
    if ( !empty( $all_products ) ){
      autoship_load_schedulable_products_into_session( $all_products );
    } else {
      autoship_clear_schedulable_products_sessions();
    }
  }

  if ( 'output_return' == $reply_type ){

    autoship_print_scripts_data( array(
      'autoship_available_products_data' => $all_products
    ) );

    return $all_products;

  } else if ( 'output' == $reply_type ) {

    autoship_print_scripts_data( array(
      'autoship_available_products_data' => $all_products
    ) );

  } else if ( 'json' == $reply_type ) {

    return json_encode( array( 'autoship_available_products_data' => $all_products ) );

  }

  return $all_products;

}

/**
 * Returns an array of display labels to use in Select drop down.
 * @param array $products The currently available products
 * @return array The labels & values to use in the select drop down.
 */
function autoship_get_schedulable_products_display_labels( $available_products, $products ){
  $uses_wc_data = autoship_filter_schedulable_products_use_wc_data();

  // Now gather the product display data
  foreach ( $products as $product ){

    // Get the Product for the suffix
    $wc_product = $uses_wc_data ? $product : wc_get_product( $product['id'] );

    // Check for invalid products - they break the UI.
    if ( !$wc_product ){

      autoship_log_entry( __( 'Autoship Native UI Missing or Invalid Product', 'autoship' ), sprintf( 'Product #%d can not be found in WooCommerce or is invalid.', $product['id'] ) );
      continue;

    }

    // Get the original price
    $available_products[$wc_product->get_id()]['item_original_price'] = autoship_get_adjusted_product_item_price( $wc_product->get_price(), $wc_product->get_id() );

    // Get the sale / current price
    $available_products[$wc_product->get_id()]['item_price'] = $wc_product->get_sale_price() ?
    autoship_get_adjusted_product_item_price( $wc_product->get_sale_price(), $wc_product->get_id() ) : $available_products[$wc_product->get_id()]['item_original_price'];

    $overrides = autoship_get_product_custom_data_values( $wc_product );
    $available_products[$wc_product->get_id()]['label'] = apply_filters( 'autoship_scheduled_order_form_item_add_display_name',
    $overrides['title']. ' ' . autoship_get_formatted_price( $available_products[$wc_product->get_id()]['item_price'], array(
      'suffix'    => $wc_product->get_price_suffix(),
    ) ),
    $wc_product->get_title(),
    $wc_product->get_price(),
    $wc_product->get_sale_price(),
    $uses_wc_data ? $wc_product : $product
    );

  }

  return $available_products;

}
add_filter('autoship_filter_schedulable_products_display_labels', 'autoship_get_schedulable_products_display_labels', 10, 2 );

// Wrap in function_exists so this function is pluggable.
if ( !function_exists( 'autoship_filter_schedulable_products_data' ) ){

  /**
   * Filters the Schedulable Product Data returned from QPilot
   * @param array  An Array of stdClass objects or WC_Product objects.
   * @return array The filtered array
   */
  function autoship_filter_schedulable_products_data ( $products ){

    $uses_wc_data = autoship_filter_schedulable_products_use_wc_data();
    $filtered = array();
    foreach ( $products as $product ) {

      $product = $uses_wc_data ? $product : autoship_convert_object_to_array( $product );
      // Skip variable products
      $skip = apply_filters( 'autoship_filter_schedulable_products_data_should_skip', false, $product );
      $product_id = $uses_wc_data ? $product->get_id() : $product['id'];

      if ( $skip )
      continue;
 
      $filtered[$product_id] = $product;

    }

    return $filtered;

  }

}
add_filter('autoship_filter_schedulable_products_data', 'autoship_filter_schedulable_products_data', 10, 1 );


/**
 * Filters the available product data by skipping Variable Products since they aren't used in UI.
 * @param bool $skip True Skip the product else false.
 * @param array $product The product currently being filtered.
 * @return bool The filtered value.
 */
function autoship_filter_schedulable_products_data_skip_variable( $skip, $product ){
  $uses_wc_data = autoship_filter_schedulable_products_use_wc_data();
  if($uses_wc_data) {
    return $product->is_type( 'variable' ) ? true : $skip;
  }
  return isset( $product['metadata']['type'] ) && 'variable' == $product['metadata']['type'] ? true : $skip;
}
add_filter('autoship_filter_schedulable_products_data_should_skip', 'autoship_filter_schedulable_products_data_skip_variable', 10, 2 );

/**
 * Outputs the HTML for when adding an item to an Order
 *
 * @return string The html output
 */
function autoship_get_add_item_html(){

  $product    = !isset( $_POST['product'] ) || empty( $_POST['product'] ) ? NULL : $_POST['product'];
  $product_id = !isset( $_POST['product_id'] ) || empty( $_POST['product_id'] ) ? NULL : absint( $_POST['product_id'] );

  if ( !isset( $product ) && !isset( $product_id ) ){

    $notice_content = apply_filters( 'autoship_add_item_via_ajax_failure_msg', __('Error Adding Product Due to Invalid Call.', 'autoship'), 400, '' );

    autoship_ajax_result( 400, array(
     'success'=> 'error',
     'notice'        => apply_filters( 'autoship_add_item_via_ajax_msg_html',
                                        sprintf( '<div class="autoship-msg woocommerce-%1s">%2s</div>', 'error', $notice_content ),
                                        'error',
                                        $notice_content ),
     'notice_target' => apply_filters( 'autoship_add_item_via_ajax_msg_html_target',
                                       '.scheduled-order-add-ons-msg', 'error' ))
    );
  }

  // Check if Product Object Supplied or just id.
  // If just id grab the product from Session or API.
  if ( !isset( $product ) ){

    $product = autoship_pull_schedulable_products_session( $product_id );

    if ( empty( $product ) )
    $product = autoship_get_available_product( $product_id );

    if ( is_wp_error( $product ) ){

       $notice_content = apply_filters( 'autoship_add_item_via_ajax_failure_msg',
         __('Error Adding Product Due to Invalid Call. <br/>Additional Details: ' . $product->get_error_message(), 'autoship'),
         404,
         $product
       );

       autoship_ajax_result( 404, array(
         'success'=> 'error',
         'notice'        => apply_filters( 'autoship_add_item_via_ajax_msg_html', sprintf( '<div class="autoship-msg woocommerce-%1s">%2s</div>', 'error', $notice_content ), 'error', $notice_content ),
         'notice_target' => apply_filters( 'autoship_add_item_via_ajax_msg_html_target', '.scheduled-order-add-ons-msg', 'success' ))
       );

    }

    $product = autoship_convert_object_to_array( $product );

  }

  ob_start();

  autoship_render_template(
    apply_filters(
      'autoship_scheduled_order_display_template',
      'scheduled-orders/order-ajax-add-item-form',
      $product['id'],
      $product ),
    array(
      'product_id' => $product['id'],
      'product'=> $product
  ) );

  $template = ob_get_clean();

  $label = autoship_translate_text( 'Scheduled Order' );

  $notice_content = apply_filters(
    'autoship_add_item_via_ajax_success_msg',
    sprintf( __( "Click Update Items to Save your %s and see your new total amount.", 'autoship'), $label ),
    200,
    $product
  );

  autoship_ajax_result( 200, array(
    'success'=> 'success',
    'notice'        => apply_filters( 'autoship_add_item_via_ajax_msg_html', sprintf( '<div class="autoship-msg woocommerce-%1s">%2s</div>', 'message', $notice_content ), 'success', $notice_content ),
    'notice_target' => apply_filters( 'autoship_add_item_via_ajax_msg_html_target', '.scheduled-order-add-ons-msg', 'success' ), 'content'=> $template )
  );

}
add_action( 'wp_ajax_autoship_get_add_item_html', 'autoship_get_add_item_html' );

/**
 * Outputs the HTML for when adding an item to an Order
 *
 * @return string The html output
 */
function autoship_confirm_delete_schedule_notice_html(){

  if ( ( !isset( $_POST['order_id'] ) || empty( $_POST['order_id'] ) ) &&
       ( !isset( $_POST['order_view'] ) || empty( $_POST['order_view'] ) ) &&
       ( !isset( $_POST['confirm'] ) || empty( $_POST['confirm'] ) ) ){

     $notice_content = apply_filters( 'autoship_delete_scheduled_order_via_ajax_failure_msg',
       __( sprintf( 'Error %s Due to Invalid Call.', autoship_translate_text( 'Scheduled Order' ) ), 'autoship'),
       400,
       ''
     );

     autoship_ajax_result( 400, array(
       'success'=> 'error',
       'notice'        => apply_filters( 'autoship_delete_scheduled_order_via_ajax_msg_html',
                                         sprintf( '<div class="autoship-msg woocommerce-%1s">%2s</div>', 'error', $notice_content ),
                                         'error',
                                         $notice_content ),
       'notice_target' => apply_filters( 'autoship_delete_scheduled_order_via_ajax_msg_html_target',
                                         '.scheduled-order-add-ons-msg', 'error' ))
      );
   }

  $scheduled_order = $_POST['order_id'];
  $view = $_POST['order_view'];

  $url    = autoship_get_scheduled_order_delete_url( $scheduled_order, 'confirmed' );
  $cancel = autoship_get_scheduled_orders_url();

  // Figure out where the notice will be added in UI.
  $notice_target = apply_filters('autoship_confirm_delete_schedule_notice_html_target',
  'orders' == $view ? '#row-' . $scheduled_order : '.schedule-summary', $view, $scheduled_order );

  // Figure out where the notice will be added in UI.
  $notice_wrapper_target = apply_filters('autoship_confirm_delete_schedule_notice_html_wrapper_target',
  'orders' == $view ? 'tr.autoship-notice-row' : '.autoship-notice-block', $view, $scheduled_order );

  $label = autoship_translate_text( 'Scheduled Order' );

  // Format Notice Content
  $notice = apply_filters( 'autoship_confirm_delete_schedule_link_label', sprintf( __('<span class="action-request">Are you sure you want to Delete %s #%d?</span> <a class="confirm-action" data-notice-wrapper="%s" href="%s">Confirm</a> or <a class="cancel-action" data-notice-wrapper="%s" href="%s">Cancel</a>', 'autoship'), $label, $scheduled_order, $notice_wrapper_target, $url, $notice_wrapper_target, $url, $cancel ), $url, $cancel, $notice_wrapper_target, $scheduled_order, 'confirm' );

  // Grab the notice wrapper.
  $notice_content = apply_filters("autoship_confirm_delete_schedule_notice_{$view}_html_wrapper", $notice , $scheduled_order );

  autoship_ajax_result( 200, array(
    'success'       => 'success',
    'notice'        => apply_filters( 'autoship_confirm_delete_via_ajax_msg_html',
                                      $notice_content,
                                      'alert',
                                      $notice,
                                      $view,
                                      $scheduled_order ),
    'notice_target' => apply_filters( 'autoship_confirm_delete_via_ajax_msg_html_target',
                                      $notice_target,
                                      $notice,
                                      $view,
                                      $scheduled_order ),
   ));


 	die();

}
add_action( 'wp_ajax_autoship_Deleted_ajax_action', 'autoship_confirm_delete_schedule_notice_html' );

/**
 * Outputs the HTML wrapper for when displaying the delete notice on Scheduled Orders
 * @param string $notice The current Notice content
 * @param int #scheduled_order The scheduled order id.
 *
 * @return string The html output
 */
function autoship_confirm_delete_schedule_notice_orders_html_wrapper ( $notice , $scheduled_order ){

  $columns = autoship_get_my_account_scheduled_orders_columns();
  ob_start();?>

    <tr class="autoship-notice-row woocommerce-orders-table__row">

      <td class="autoship-order-notice action-notice confirm-delete" data-title="<?php echo __( 'Notice', 'autoship' ); ?>" colspan="<?php echo count( $columns ) - 1;?>"><p role="alert"><?php echo $notice; ?></p></td>

    </tr>

  <?php
  return ob_get_clean();

}
add_filter( 'autoship_confirm_delete_schedule_notice_orders_html_wrapper', 'autoship_confirm_delete_schedule_notice_orders_html_wrapper', 10, 2 );


/**
 * Outputs the HTML wrapper for when displaying the delete notice on Scheduled Order
 * @param string $notice The current Notice content
 * @param int #scheduled_order The scheduled order id.
 *
 * @return string The html output
 */
function autoship_confirm_delete_schedule_notice_order_html_wrapper ( $notice , $scheduled_order ){

  ob_start();?>

  <div class="autoship-notice-block">

    <div class="autoship-order-notice action-notice confirm-delete" data-title="<?php echo __( 'Notice', 'autoship' ); ?>"><p role="alert"><?php echo $notice; ?></p></div>

  </div>

  <?php
  return ob_get_clean();

}
add_filter( 'autoship_confirm_delete_schedule_notice_order_html_wrapper', 'autoship_confirm_delete_schedule_notice_order_html_wrapper', 10, 2 );


// ==========================================================
// DEFAULT HOOKED ACTIONS
// ==========================================================

/**
 * View Scheduled Orders Template.
 *
 * @see autoship_filter_non_customer_visible_orders()
 * @see autoship_scheduled_orders_header_wp_notices_display()
 * @see autoship_scheduled_orders_custom_html_header_display()
 * @see autoship_scheduled_orders_header_actions()
 * @see autoship_scheduled_orders_row_notice_template_display()
 * @see autoship_include_scheduled_order_total_zero_total()
 */
add_filter('autoship_before_autoship_scheduled_orders', 'autoship_scheduled_orders_header_wp_notices_display', 9, 2 );
add_filter('autoship_before_autoship_scheduled_orders', 'autoship_scheduled_orders_custom_html_header_display', 10 );
add_filter('autoship_scheduled_orders_template_display_orders', 'autoship_filter_non_customer_visible_orders', 10, 3 );
add_filter('autoship_before_autoship_scheduled_orders_template', 'autoship_scheduled_orders_header_wp_notices_display', 9, 2 );
add_filter('autoship_before_autoship_scheduled_orders_template', 'autoship_scheduled_orders_custom_html_header_display', 10 );
add_action('autoship_before_autoship_scheduled_orders_template', 'autoship_scheduled_orders_header_actions', 10, 3 );
add_filter('autoship_after_autoship_scheduled_orders_template_row', 'autoship_scheduled_orders_row_notice_template_display', 10, 3 );
add_filter('autoship_exclude_scheduled_order_total_zero_total', 'autoship_include_scheduled_order_total_zero_total', 19, 1 );
add_filter('autoship_exclude_scheduled_order_shipping_total_zero_total', 'autoship_include_scheduled_order_shipping_total_zero_total', 19, 1 );

/**
 * Scheduled Orders Row Notice Template.
 *
 * @see autoship_scheduled_orders_row_notice_template_display()
 */
add_filter('autoship_orders_notice_message', 'autoship_filter_notices_for_all_orders', 10, 2 );

/**
 * Filters out the Scheduled Orders Address Subnotes for Non-Editable Orders.
 *
 * @see autoship_filter_out_scheduled_order_address_subnotes()
 */
add_filter('autoship_scheduled_order_address_shipping_subnote', 'autoship_scheduled_order_address_edit_link_adjustment_display', 9, 3 );
add_filter('autoship_scheduled_order_address_shipping_subnote', 'autoship_filter_out_scheduled_order_address_subnotes', 10, 2 );
add_filter('autoship_scheduled_order_address_billing_subnote', 'autoship_filter_out_scheduled_order_address_subnotes', 10, 2 );

/**
 * Filters Scheduled Orders Actions Displayed
 *
 * @see autoship_filter_scheduled_orders_template_actions()
 */
add_filter('autoship_scheduled_orders_display_actions_filter', 'autoship_filter_scheduled_orders_template_actions', 10, 4 );

/**
 * Scheduled Order Error Template.
 *
 * @see autoship_non_hosted_scheduled_order_error_display()
 * @see autoship_scheduled_orders_custom_html_header_display()
 * @see autoship_scheduled_order_error_template_custom_html_display()
 * @see autoship_scheduled_order_error_template_notice_display()
 */
add_action( 'autoship_before_autoship_scheduled_orders', 'autoship_non_hosted_scheduled_order_error_display', 11, 2 );
add_action( 'autoship_before_schedule_orders_error', 'autoship_scheduled_orders_custom_html_header_display', 9 );
add_action( 'autoship_before_schedule_orders_error', 'autoship_scheduled_order_error_template_custom_html_display', 10, 3 );
add_action( 'autoship_scheduled_orders_error_content', 'autoship_scheduled_order_error_template_notice_display', 10, 3 );

/**
 * Lock Scheduled Order Template.
 *
 * @see autoship_scheduled_order_header_locked_notice_display()
 * @see autoship_scheduled_order_header_template_display()
 * @see autoship_scheduled_order_schedule_summary_template_display()
 * @see autoship_scheduled_order_items_template_display()
 * @see autoship_scheduled_order_payment_method_summary_template_display()
 * @see autoship_scheduled_order_payment_form_display()
 * @see autoship_scheduled_order_address_template_display()
 */
add_action( 'autoship_after_schedule_order_header', 'autoship_scheduled_order_header_locked_notice_display', 10, 3 );
add_action( 'autoship_before_scheduled_order_lock', 'autoship_scheduled_order_header_template_display', 9, 3 );
add_action( 'autoship_before_scheduled_order_lock', 'autoship_scheduled_order_schedule_summary_template_display', 10, 3 );
add_action( 'autoship_scheduled_order_lock',        'autoship_scheduled_order_items_template_display', 10, 3 );
add_action( 'autoship_after_scheduled_order_lock',  'autoship_scheduled_order_payment_method_summary_template_display', 9, 3 );
add_action( 'autoship_after_scheduled_order_lock',  'autoship_edit_scheduled_order_payment_form_display_action', 10, 3 );
add_action( 'autoship_after_scheduled_order_lock',  'autoship_scheduled_order_payment_form_display', 10, 3 );
add_action( 'autoship_after_scheduled_order_lock',  'autoship_scheduled_order_address_view_template_display', 11, 3 );

/**
 * View Scheduled Order Template.
 *
 * @see autoship_scheduled_order_header_template_display()
 * @see autoship_scheduled_order_schedule_summary_template_display()
 * @see autoship_scheduled_order_items_template_display()
 * @see autoship_scheduled_order_payment_method_summary_template_display()
 * @see autoship_scheduled_order_address_template_display()
 */
add_action( 'autoship_before_scheduled_order_view', 'autoship_scheduled_order_header_template_display', 9, 3 );
add_action( 'autoship_before_scheduled_order_view', 'autoship_scheduled_order_schedule_summary_template_display', 10, 3 );
add_action( 'autoship_scheduled_order_view',        'autoship_scheduled_order_items_template_display', 10, 3 );
add_action( 'autoship_after_scheduled_order_view',  'autoship_scheduled_order_payment_method_summary_template_display', 9, 3 );
add_action( 'autoship_after_scheduled_order_view',  'autoship_scheduled_order_address_view_template_display', 11, 3 );

/**
 * scheduled-order-items-template.php
 *
 * @see autoship_scheduled_order_item_meta_template_display()
 * @see autoship_scheduled_order_item_cycle_notice()
 * @see autoship_scheduled_order_no_items_template_display()
 * @see autoship_scheduled_order_totals_summary_template_display()
 */
add_action( 'autoship_before_scheduled_order_view_item_name', 'autoship_scheduled_order_item_stock_notice_display', 10, 5 );
add_action( 'autoship_after_scheduled_order_view_item_name', 'autoship_scheduled_order_item_meta_template_display', 10, 5 );
add_action( 'autoship_after_scheduled_order_view_item_name', 'autoship_scheduled_order_item_cycle_notice', 10, 5 );
add_action( 'autoship_scheduled_order_view_no_items', 'autoship_scheduled_order_no_items_template_display', 10, 3 );
add_action( 'autoship_after_scheduled_order_view_table', 'autoship_scheduled_order_totals_summary_template_display', 10, 4 );

/**
 * Scheduled Order Template.
 *
 * @see autoship_get_schedulable_products_script_data()
 * @see autoship_scheduled_order_header_template_display()
 * @see autoship_scheduled_order_schedule_summary_template_display()
 * @see autoship_scheduled_order_schedule_form_display()
 * @see autoship_scheduled_order_items_form_display()
 * @see autoship_scheduled_order_payment_method_summary_template_display()
 * @see autoship_scheduled_order_payment_form_display()
 * @see autoship_scheduled_order_address_template_display()
 * @see autoship_scheduled_order_shipping_rate_form_display()
 */
//add_action( 'autoship_before_scheduled_order_edit', 'autoship_get_schedulable_products_script_data' );
add_action( 'autoship_before_scheduled_order_edit', 'autoship_scheduled_order_header_template_display', 9, 3 );
add_action( 'autoship_before_scheduled_order_edit', 'autoship_scheduled_order_schedule_summary_template_display', 10, 3 );
add_action( 'autoship_before_scheduled_order_edit', 'autoship_scheduled_order_schedule_form_display', 11, 3 );
add_action( 'autoship_scheduled_order_edit',        'autoship_scheduled_order_items_form_display', 10, 3 );
add_action( 'autoship_after_scheduled_order_edit',  'autoship_scheduled_order_shipping_rate_form_display', 8, 3 );
add_action( 'autoship_after_scheduled_order_edit',  'autoship_scheduled_order_payment_method_summary_template_display', 9, 3 );
add_action( 'autoship_after_scheduled_order_edit',  'autoship_edit_scheduled_order_payment_form_display_action', 10, 3 );
add_action( 'autoship_after_scheduled_order_edit',  'autoship_scheduled_order_payment_form_display', 10, 3 );
add_action( 'autoship_after_scheduled_order_edit',  'autoship_scheduled_order_address_edit_template_display', 11, 3 );
add_action( 'autoship_after_scheduled_order_edit',  'autoship_scheduled_order_address_form_display', 12, 3 );

/**
 * scheduled-order-items-form.php
 *
 * @see autoship_scheduled_order_item_remove_modify_for_cycle_item()
 * @see autoship_scheduled_order_item_qty_modify_for_cycle_item()
 * @see autoship_scheduled_order_item_meta_template_display()
 * @see autoship_scheduled_order_items_action_form_display()
 * @see autoship_scheduled_order_items_coupon_action_form_display()
 * @see autoship_scheduled_order_no_items_template_display()
 * @see autoship_scheduled_order_totals_summary_template_display()
 * @see autoship_scheduled_order_item_cycle_notice()
 * @see autoship_output_shipping_rate_total_row()
 */
add_action( 'autoship_scheduled_order_form_item_remove_link', 'autoship_scheduled_order_item_remove_modify_for_cycle_item', 10, 3 );
add_filter( 'autoship_scheduled_order_form_item_quantity_field', 'autoship_scheduled_order_item_qty_modify_for_cycle_item', 10, 3 );
add_action( 'autoship_before_scheduled_order_form_item_name', 'autoship_scheduled_order_item_stock_notice_display', 10, 5 );
add_action( 'autoship_after_scheduled_order_form_item_name', 'autoship_scheduled_order_item_meta_template_display', 10, 5 );
add_action( 'autoship_after_scheduled_order_form_item_name', 'autoship_scheduled_order_item_cycle_notice', 10, 5 );
add_action( 'autoship_after_scheduled_order_form_items', 'autoship_scheduled_order_items_action_form_display', 10, 3 );
add_action( 'autoship_after_scheduled_order_form_add_ons', 'autoship_scheduled_order_items_coupon_action_form_display', 10, 3 );
add_action( 'autoship_update_scheduled_order_form_no_items', 'autoship_scheduled_order_no_items_template_display', 10, 3 );
add_action( 'autoship_after_scheduled_order_form_table', 'autoship_scheduled_order_totals_summary_template_display', 10, 4 );
add_action( 'autoship_before_scheduled_order_total_shipping_total_row', 'autoship_output_shipping_rate_total_row', 10, 3 );

/**
 * order-ajax-add-item-form.php
 *
 * @see autoship_scheduled_order_add_item_meta_template_display()
 */
add_action( 'autoship_after_scheduled_order_form_add_item_name', 'autoship_scheduled_order_add_item_meta_template_display', 10, 2 );


/**
 * scheduled-order-payment-form.php
 *
 * @see autoship_scheduled_order_payment_form_add_method_link()
 */
add_action( 'autoship_update_scheduled_order_payment_after_actions',  'autoship_scheduled_order_payment_form_add_method_link', 10, 3 );

/**
 * order-schedule-form.php
 *
 * @see autoship_edit_scheduled_order_schedule_display_action()
 */
add_action( 'autoship_before_update_scheduled_order_schedule_form', 'autoship_edit_scheduled_order_schedule_form_display_action', 10, 3 );

/**
 * order-shipping-rate-form.php
 *
 * @see autoship_shipping_rate_form_refresh_rates_action()
 */
add_action( 'autoship_before_scheduled_order_edit_preferred_shipping_rate_form_action', 'autoship_shipping_rate_form_refresh_rates_action', 9, 3 );

/**
 * order-shipping-rate-form.php
 *
 * @see autoship_shipping_rate_form_close_modal_display_action()
 */
add_action( 'autoship_before_scheduled_order_edit_preferred_shipping_rate_form_action', 'autoship_shipping_rate_form_close_modal_display_action', 10, 3 );
