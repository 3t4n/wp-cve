<?php

// ==========================================================
// Utility Functions
// ==========================================================

/**
 * Gets the Autoship Related Cart Item Keys
 *
 * @param bool   $as_array_keys Optional. True return an
 *                              array with keys and null values.
 * @return array The key names
 */
function autoship_cart_item_keys( $as_array_keys = false ) {
	$values = apply_filters('autoship_cart_item_keys', array(
		'autoship_frequency',
		'autoship_frequency_type',
		'autoship_next_occurrence',
		'autoship_preferred_shipping',
	));

	if( $as_array_keys ){

		$array_values = array();
		foreach ( $values as $value )
		$array_values[$value] = NULL;

		return $array_values;

	}

	return $values;
}

/**
 * Get Item Schedule Hash
 *
 * @param array $data      The values to check
 * @param bool  $hashed 	 Optional. True to implode
 * @param bool  $filtered  Optional. True to filter out NULL / Empty vals
 *
 * @return string|array The hash string or array based off the Schedule Data
 */
function autoship_get_item_data_schedule_hash( $data, $hashed = true, $filtered = true ){

	$hash = array();
	foreach ( autoship_cart_item_keys() as $key )
	$hash[$key] = isset( $data[$key] ) ? $data[$key] : NULL;

	if (  $filtered )
	$hash = array_filter( $hash );

	return $hashed ? implode( ';', $hash ) : $hash;

}

/**
 * Get Item Schedule Values
 *
 * @param array $data      The array of data to retrieve the values from
 *
 * @return array The Schedule Data
 */
function autoship_get_item_data_schedule_values( $data ){

	$values = autoship_cart_item_keys( true );
	foreach ( $values as $key => $value )
	$values[$key] = isset( $data[$key] ) ? $data[$key] : $value;

	return $values;

}

/**
 * Checks if the supplied array of Data
 * has valid autoship values
 *
 * @param array $data          The values to check
 * @param int $rfreq Optional. A required frequency for
 *													   the item to be valid
 * @param string $rfreq_type   Optional. A required frequency type for
 *													   the item to be valid
 * @return bool True if it does else false
 */
function autoship_item_data_has_valid_schedule( $data, $rfreq = NULL, $rfreq_type = NULL ){

	$valid = false;
	$schedule_values = autoship_get_item_data_schedule_values( $data );
	if ( !empty( $schedule_values['autoship_frequency'] ) && !empty( $schedule_values['autoship_frequency_type'] ) ){

		$valid = true;

		if ( isset( $rfreq ) )
		$valid = $schedule_values['autoship_frequency'] >= $rfreq;

		if ( isset( $rfreq_type ) && !empty( $rfreq_type ) )
		$valid = $schedule_values['autoship_frequency_type'] == $rfreq_type;

	}

	return apply_filters( 'autoship_item_data_has_valid_schedule', $valid, $data, $rfreq, $rfreq_type );

}

/**
 * Gets the value for the Global 'autoship_cart_schedule_options_enabled' Settings field
 *
 * @return string 'yes' if option enabled else 'no'
 */
function autoship_cart_schedule_options_enabled() {

	$val = apply_filters( 'autoship_override_cart_schedule_options_enabled_default',
  get_option( 'autoship_cart_schedule_options_enabled' ) );

  return empty( $val ) ? 'no' : $val;

}

/**
 * Checks the cart to see if it's a valid
 * Autoship cart.  By default just one Autoship item
 * Validates the cart.
 *
 * NOTE Developers can override the required number of Autoship items
 * to make a cart valid using {@see autoship_valid_cart_item_count}
 * NOTE Developers can override the required Autoship frequency type
 * required for an item to be counted using {@see autoship_valid_cart_item_frequency}
 * NOTE Developers can override the required minimum total number of
 * Valid Autoship items in cart to be valid using {@see autoship_valid_cart_total_min_required}
 *
 * @return bool True if the requirement is met. False if not.
 */
function autoship_cart_has_valid_autoship_items(){

  $cart = WC()->cart;

  // Don't bother if empty or if being called pre-wp loaded
	if ( empty( $cart ) || !did_action( 'wp_loaded' ) )
	return false;

  // Developers can override the required frequency to be valid.
  $min_required_frequency = apply_filters( 'autoship_valid_cart_item_min_frequency', 1 );

  // Developers can override the required frequency type to be valid.
  // By default any type is ok.
  $required_frequency_type = apply_filters( 'autoship_valid_cart_item_frequency', '' );

  // Loop through the cart checking for Autoship frequency.
	$autoship_items_count = 0;
	foreach ( $cart->get_cart() as $item ) {

		if ( autoship_item_data_has_valid_schedule( $item, $min_required_frequency, $required_frequency_type ) )
		$autoship_items_count++;

  }

	return (bool) apply_filters( 'autoship_valid_cart_total_min_required', $autoship_items_count );

}


/**
 * Groups order or cart items by frequency type, frequency, and
 * Next occurrence.
 *
 *
 * @param array $cart_items The cart or order items to group.
 * @return array Cart Items grouped by Frequency and Frequency Type.
 */
function autoship_group_cart_items( $cart_items ) {

	// Group frequencies
	$frequencies_hash = array();
	foreach ( $cart_items as $item_key => $item ) {

		// If the item doesn't have valid scheduled data skip it.
		if ( !autoship_item_data_has_valid_schedule( $item ) )
		continue;

		// Get the item's hash values & key
		$item_hash_values = autoship_get_item_data_schedule_hash( $item, false );
		$item_hash_key 		= implode( ';', $item_hash_values );

		// Check if the hash already exists
		// if not then create the initial values
		if ( !isset( $frequencies_hash[$item_hash_key] ) )
		$frequencies_hash[ $item_hash_key ] = array_merge(
			autoship_cart_item_keys( true ),
			$item_hash_values + [ 'items' => [] ],
		);

		// Now add the item
		$frequencies_hash[ $item_hash_key ]['items'][$item_key] = $item;

	}

  ksort($frequencies_hash);
  return $frequencies_hash;

}

/**
 * Returns the Cart Items from the supplied items that match the
 * supplied Frequency, Frequency Type, and Next Occurrence
 *
 * NOTE If NULL supplied for Frequency, Frequency Type, and Next Occurrence
 * all non-autoship items are returned.
 *
 * @param array $cart_items The cart or order items to group.
 * @return array Cart Items grouped by Frequency and Frequency Type.
 */
function autoship_select_cart_items( $cart_items, $frequency_type, $frequency, $next_occurrence ) {
	$selected_cart_items = array();
	if ( $frequency_type == null && $frequency == null) {
		foreach ( $cart_items as $cart_key => $item ) {
			if ( empty( $item['autoship_frequency_type'] ) && empty( $item['autoship_frequency'] ) ) {
				$selected_cart_items[$cart_key] = $item;
			}
		}
	} else {
		foreach ( $cart_items as $cart_key => $item ) {
			if ( isset( $item['autoship_frequency_type'] ) && isset( $item['autoship_frequency'] ) ) {
				$item_next_occurrence = ( ! empty( $item['autoship_next_occurrence'] ) ) ? $item['autoship_next_occurrence'] : null;
				if ( $item['autoship_frequency_type'] == $frequency_type
						&& $item['autoship_frequency'] == $frequency
						&& $item_next_occurrence == $next_occurrence ) {
					$selected_cart_items[$cart_key] = $item;
				}
			}
		}
	}
	return $selected_cart_items;
}

/**
 * Retrieves the Autoship Data related to a product and it's variations
 * Returns the values in a format currently used in schedule-options.js.
 * @param WC_Product|WC_Product_Variable A woocommerce product or variable product.
 * @return array Of autoship schedule data for each product variation.
 */
function autoship_get_all_variation_cart_options( $variable_product ){

  $product = new WC_Product_Variable( $variable_product );
  $variations = $product->get_available_variations();

  $autoship_variation_data = array();
  foreach ($variations as $variation ) {
    $new_product = wc_get_product( $variation['variation_id'] );
    $data = autoship_product_discount_data( $new_product );
    $autoship_variation_data[$variation['variation_id']] = apply_filters( 'autoship_get_all_variation_cart_options', $data, $variation['variation_id'] , $new_product );
  }

  return $autoship_variation_data;
}

/**
 * Checks if a Next Occurrence should be updated in case the item has been in the cart for
 * a while and is now expired.
 *
 * @param int     $product_id. The WC Product or variation id.
 * @param string  $next_occurrence The next occurrence string.
 * @param string  $frequency_type Optional. The Scheduled Frequency Type. Default Empty String
 * @param int     $frequency Optional. The Scheduled Frequency. Default 0
 *
 * @return string|NULL The next occurrence string or NULL if doesn't exist.
 */
function autoship_maybe_update_cart_item_relative_next_occurrence( $product_id, $next_occurrence, $frequency_type = '', $frequency = 0 ){

	// Clone the original for inclusion in the filter
  $original_next_occurrence = $next_occurrence;

  // Re-Calculate the Relative Next Occurrence for the cart item if it's stale.
  if ( apply_filters('autoship_cart_item_relative_next_occurrence_is_stale', true, $product_id, $next_occurrence ) )
  $next_occurrence = autoship_get_product_default_relative_next_occurrence( $product_id, $frequency_type, $frequency );

  return apply_filters( 'autoship_maybe_update_item_relative_next_occurrence_date', $next_occurrence, $product_id, $original_next_occurrence, $frequency_type, $frequency );

}

// ==========================================================
// Cart Update & Validation Functions
// ==========================================================

/**
 * Validates the Schedule Values are still valid for the product
 *
 * @param array $schedule_data The Schedule Data for the item
 * @param array $original_data The original Schedule Data to be assigned
 * @param array $item_data cart item data being updated
 * @param int   $product_id ID of the product associated with the cart item data.
 * @param int   $variation_id ID of the variation associated with the cart item data.
 *
 * @return array The validated cart item's data array.
 */
function autoship_validate_cart_item_schedule_data( $schedule_data, $original_data, $item_data, $product_id, $variation_id ) {

	// If the frequency or frequency type is no longer valid reset the values
	return !autoship_is_valid_product_frequency_option( $variation_id ? $variation_id : $product_id, $schedule_data['autoship_frequency'], $schedule_data['autoship_frequency_type'] ) ? array_merge( $schedule_data, autoship_cart_item_keys( true ) ) : $schedule_data;

}

/**
 * Adds / updates the Autoship Schedule Data in the Cart Item being added / updated.
 *
 * @param array $cart_item_data cart item data we want to update.
 * @param int   $product_id ID of the product associated with the cart item data.
 * @param int   $variation_id ID of the variation associated with the cart item data.
 * @param int   $quantity The quantity being added
 *
 * @return array The updated cart item's data array.
 */
function autoship_update_cart_item_data( $cart_item_data, $product_id, $variation_id, $quantity, $schedule_data ) {

	// Default the Autoship Values for the supplied data
	// allow devs to adjust before we run our cart operations.
	$default_schedule_values = autoship_cart_item_keys( true );
	$schedule_data = apply_filters( 'autoship_update_cart_item_data_schedule_values',
	array_merge( $default_schedule_values, $schedule_data ),
	$schedule_data, $cart_item_data, $product_id, $variation_id, $quantity );

	// No valid autoship schedule so we don't continue
	// however, we still update the item's data with the re-freshed data
	$schedule_values = autoship_get_item_data_schedule_values( $schedule_data );

	if ( !autoship_item_data_has_valid_schedule( $schedule_values ) )
	return array_merge( $cart_item_data, $schedule_values );

	extract( $schedule_values );

	// Adjust the Relative Next Occurrence if needed
	$schedule_values['autoship_next_occurrence'] = isset( $autoship_next_occurrence ) && !empty( $autoship_next_occurrence ) ? autoship_maybe_update_cart_item_relative_next_occurrence( $product_id, $autoship_next_occurrence, $autoship_frequency_type, $autoship_frequency  ) : autoship_get_product_default_relative_next_occurrence( $product_id, $autoship_frequency_type, $autoship_frequency );

	// Now attach the Schedule Values to the cart item
	foreach ( $schedule_values as $key => $value )
	$cart_item_data[$key] = $value;

	return $cart_item_data;

}

/**
 * Adds the Autoship Schedule Data to Cart Item being added / updated.
 * These values are also used to find a matching cart item in the cart.
 * NOTE This is the first function to filter data being posted to add to cart.
 *
 * @param array $cart_item_data extra cart item data we want to pass into the item.
 * @param int   $product_id ID of the product to add to the cart.
 * @param int   $variation_id ID of the variation to add to the cart.
 * @param int   $quantity The quantity being added
 *
 * @return array The updated cart item's data array.
 */
function autoship_add_cart_item_data( $cart_item_data, $product_id, $variation_id, $quantity ) {

	// Determine which global variable contains our data
	$values = !empty( autoship_get_item_data_schedule_hash( $_POST ) ) ? $_POST : $_REQUEST;
	return autoship_update_cart_item_data( $cart_item_data, $product_id, $variation_id, $quantity, $values );

}

/**
 * Adjust the price of new items being added to the cart based on their schedule
 * so it only fires if the item being aded is NEW and doesn't exist already.
 *
 * NOTE This filters data after @see autoship_add_cart_item_data() so Schedule
 * data has already been attached / defaulted.
 *
 * @param array  $cart_item_data The current cart item's data array.
 * @param string $cart_item_key The current cart item's hash key
 * @param array  $schedule_data Optional. Optional Schedule Data to re-assign to the item
 *
 * @return array The updated Cart item Data
 */
function autoship_add_cart_item( $cart_item_data, $cart_item_key, $schedule_data = NULL ) {

	$product_id    = $cart_item_data['product_id'];
	$variation_id  = $cart_item_data['variation_id'];
	$quantity      = $cart_item_data['quantity'];

	// Check if Schedule Data was supplied and if so refresh the current items data
	if ( isset( $schedule_data ) && is_array( $schedule_data ) )
	$cart_item_data = autoship_update_cart_item_data( $cart_item_data, $product_id, $variation_id, $quantity, $schedule_data );

	// No autoship schedule bail
	$schedule_values = autoship_get_item_data_schedule_values( $cart_item_data );
	if ( !autoship_item_data_has_valid_schedule( $schedule_values ) )
	return $cart_item_data;

	// Otherwise let's get the schedule data so we can calculate the price in case
	// there's a checkout price
	extract( $schedule_values );

	// Set / adjust for the scheduled item if we need to
	$checkout_price = autoship_get_product_checkout_price( $variation_id ? $variation_id : $product_id, $autoship_frequency_type, $autoship_frequency );

	if ( !empty( $checkout_price ) )
	$cart_item_data['data']->set_price( $checkout_price );

	return $cart_item_data;

}

/**
 * Adds the Autoship Schedule Data to Cart Items from Session.
 * Ensures any price changes or filters are re-applied to the session retrieved
 * data by running the data back through our add cart item function.
 *
 * @see autoship_add_cart_item()
 *
 * @param array $session_data   The current cart item's session data.
 *														  This is current cart item data + product obj
 * @param array $values         The current cart item data
 * @param string $cart_item_key The current cart item's hash key
 *
 * @return array $data The updated cart item's data array.
 */
function autoship_get_cart_item_from_session( $session_data, $values, $cart_item_key ) {

	// Pull any schedule values out of the session cart item ( or init any new ones )
	$schedule_data = autoship_get_item_data_schedule_values( $session_data );

	// Now re-process the cart item as if it was just added so that the price, next occurrence, etc
	// is refreshed and values validated.
	return autoship_add_cart_item( $session_data, $cart_item_key, $schedule_data );

}

/**
 * Adds formatted Autoship Data to the cart item data + variations for display on the frontend.
 *
 * @param string $name The current cart item name.
 * @param array $item The current cart item's data array.
 * @param string $item_key The cart item's key
 *
 * @return string The updated name.
 */
function autoship_display_cart_item_data( $name, $item, $item_key  ) {

  // Get the autoship values for this item.
  // If not an autoship item bail.
	$schedule_data = autoship_get_item_data_schedule_values( $item );
	if ( !autoship_item_data_has_valid_schedule( $schedule_data ) )
	return $name;

	extract( $schedule_data );

	$product_id = ! empty( $item['variation_id'] ) ? $item['variation_id'] : $item['product_id'];

  // Get the formatting and display name for the schedule - first check if there is a custom name else get default.
  $options = autoship_product_frequency_options( $product_id );
	$frequency_display_name         = autoship_search_for_frequency_display_name( $autoship_frequency_type, $autoship_frequency, $options );
	$product_frequency_display_name = apply_filters( 'autoship_product_frequency_display_name', $frequency_display_name, $product_id );

  $label = apply_filters( 'autoship_frequency_cart_order_item_schedule_display_label', __( 'Schedule', 'autoship' ), $name, $item );
  $name .= apply_filters( 'autoship_frequency_cart_order_item_schedule_display', "<p>{$label}: {$product_frequency_display_name}</p>", $label, $product_frequency_display_name, $name, $item );

	if ( ! empty( $autoship_next_occurrence ) ) {

		$formatted_date = autoship_format_next_occurrence_for_display( $autoship_next_occurrence );

    $datelabel = apply_filters('autoship_frequency_cart_order_item_next_occurence_display_label', __( 'Next Order', 'autoship' ), $formatted_date, $product_id );
    $date = apply_filters( 'autoship_frequency_cart_order_item_next_occurence_display_date_value', __( $formatted_date, 'autoship' ), $product_id );

    $name .= apply_filters( 'autoship_frequency_cart_order_item_schedule_next_occurrence_display', "<p>{$datelabel}: {$date}</p>", $datelabel, $date, $product_frequency_display_name, $name, $item );

  }

	return $name;

}

/**
 * Updates the Cart Items Frequency, Frequency Type, and Next Occurrence
 */
function autoship_ajax_change_cart_frequency() {
	$cart_items = WC()->cart->get_cart();
	foreach ( $_POST['cart_item_keys'] as $key ) {
		if ( isset( $cart_items[$key] ) ) {
			WC()->cart->cart_contents[$key]['autoship_frequency_type'] = $_POST['frequency_type'];
			WC()->cart->cart_contents[$key]['autoship_frequency'] = $_POST['frequency'];
			$next_occurrence = ! empty( $_POST['next_occurrence'] ) ? $_POST['next_occurrence'] : autoship_get_product_default_relative_next_occurrence( $cart_items[$key]['product_id'],  $_POST['frequency_type'], $_POST['frequency']  );
			WC()->cart->cart_contents[$key]['autoship_next_occurrence'] = $next_occurrence;
		}
	}
	WC()->cart->set_session();

	// Redirect
	if ( ! empty( $_POST['redirect'] ) ) {
		wp_redirect( $_POST['redirect'] );
	} else {
		wp_redirect( get_permalink( wc_get_page_id( 'shop' ) ) );
	}
	die();
}

/**
 * Updates the Autoship Frequency and Type when cart is updated.
 *
 * @param bool $cart_updated True if the cart was updated.
 * @return bool The adjusted cart updated flag.
 */
function autoship_update_cart_action( $cart_updated ) {

  // If the cart's empty return.
  if ( WC()->cart->is_empty() || ( 'no' == autoship_cart_schedule_options_enabled() ) )
  return $cart_updated;

  // Go through the cart items and check for Autoship Flags.
  foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {

    // Check for any updates to the schedule data
		$schedule_data = autoship_get_item_data_schedule_values( $_POST['cart'][ $cart_item_key ] );

		// Add the Schedule Data to the Cart Item Data
		$updated_item_data = autoship_update_cart_item_data( $values, $values['product_id'], $values['variation_id'], $_POST['cart'][ $cart_item_key ]['qty'], $schedule_data );

		// Now Re-process the cart item to refresh checkout price, etc if needed.
		WC()->cart->cart_contents[ $cart_item_key ] = autoship_add_cart_item( $updated_item_data, $cart_item_key );
    $cart_updated = true;

  }

  return $cart_updated;

}

/**
 * Applies a Schedule to the entire cart
 * @param int $frequency The Schedule Interval
 * @param string $frequency_type The Schedule Interval Type ( i.e. Months, Weeks, Days)
 */
function autoship_set_full_cart_schedule( $frequency = 1, $frequency_type = 'Months' ){

  // Retrieve the cart contents
  $cart = WC()->cart->cart_contents;

  // Itterate through the cart and apply the schedule
  foreach( $cart as $cart_item_id => $cart_item ) {

    // Attach the Schedule
    $cart_item['autoship_frequency']      = $frequency;
    $cart_item['autoship_frequency_type'] = $frequency_type;

    // Update the Item in the cart
    WC()->cart->cart_contents[$cart_item_id] = $cart_item;

  }

  // Update the Cart Session data.
  WC()->cart->set_session();

}

/**
 * Gets the current Schedules assigned to the cart
 *
 * @return array The current Schedules assigned to the cart.
 */
function autoship_get_full_cart_schedule(){

  $schedule = array();

  foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {

    if ( isset( $values['autoship_frequency_type'] ) && !empty( $values['autoship_frequency_type'] )
      && isset( $values['autoship_frequency'] ) && !empty( $values['autoship_frequency'] ) ){

        // Attach Frequency & Frequency Type to cart items
        $schedule[$values['autoship_frequency_type'] .'-'. $values['autoship_frequency']] = array();
        $schedule[$values['autoship_frequency_type'] .'-'. $values['autoship_frequency']]['frequency'] = $values['autoship_frequency'];
        $schedule[$values['autoship_frequency_type'] .'-'. $values['autoship_frequency']]['frequency_type'] = $values['autoship_frequency_type'];
        $schedule[$values['autoship_frequency_type'] .'-'. $values['autoship_frequency']]['display_name'] = autoship_get_frequency_display_name($values['autoship_frequency_type'], $values['autoship_frequency'] );

        if ( !isset( $schedule[$values['autoship_frequency_type'] .'-'. $values['autoship_frequency']]['count'] ) )
        $schedule[$values['autoship_frequency_type'] .'-'. $values['autoship_frequency']]['count'] = 0;

        $schedule[$values['autoship_frequency_type'] .'-'. $values['autoship_frequency']]['count']++;
    }

  }

  return $schedule;

}

// ==========================================================
// Cart UI Functions
// ==========================================================

/**
 * Outputs the Autoship Schedule Options Template to the frontend Cart
 * Directly below the cart item name.
 *
 * @param array $cart_item The current cart item data.
 * @param string $cart_item_key The current cart item key
 *
 */
function autoship_display_cart_item_options( $cart_item, $cart_item_key ) {

  // Is the cart ( how could it not be? ) Check if the Options Setting is enabled && Only Active and Enabled Products should include the template.
  if ( ! is_cart() || ( autoship_cart_schedule_options_enabled() !== 'yes' ) || ! autoship_is_visible_active_shop_product( $cart_item['product_id'] ) )
	return;

	$product_id = ( isset( $cart_item['variation_id'] ) && $cart_item['variation_id'] != 0 ) ?
	$cart_item['variation_id'] : $cart_item['product_id'];

  if ( apply_filters( 'autoship_disable_schedule_order_options_in_cart', autoship_disable_schedule_order_options( $product_id ), $cart_item, $cart_item_key ) === 'yes' )
	return;

	$product = wc_get_product( $product_id );

  // Allow Devs to set the default value for Frequency, Frequency Type, and Next Occurrence
  $defaults = apply_filters( 'autoship_cart_item_scheduled_options_defaults', array(
    'frequency'       => '',
    'frequency_type'  => '',
    'next_occurrence' => '',
  ), $cart_item, $product );

  // Get the Frequency, Frequency Type, and Next Occurrence to set in the template
	$frequency       = isset( $cart_item['autoship_frequency'] ) ?       $cart_item['autoship_frequency']        : $defaults['frequency'];
	$frequency_type  = isset( $cart_item['autoship_frequency_type'] ) ?  $cart_item['autoship_frequency_type']   : $defaults['frequency_type'];
	$next_occurrence = isset( $cart_item['autoship_next_occurrence'] ) ? $cart_item['autoship_next_occurrence']  : $defaults['next_occurrence'];


  // Get the Scheduled Options Template HTML
	$cart_schedule_options = autoship_render_template( 'cart/schedule-options', array(
		'product' 				=> $product,
		'frequency_type' 	=> $frequency_type,
		'frequency' 			=> $frequency,
		'next_occurrence' => $next_occurrence,
		'cart_item' 			=> $cart_item,
		'cart_item_key' 	=> $cart_item_key
	) );

  // Add the Scheduled Options Template HTML to end of name/link
	echo $cart_schedule_options;

}

/**
 * Adds the Autoship Template to the frontend for simple products.
 *
 * @param WC_Product $for_product The product the template is being displayed for.
 */
function autoship_print_cart_autoship_options( $for_product = null ) {
	global $product;
	if ( empty( $for_product ) ) {
		$for_product = $product;
	}

  // Not used for Variable and Only Active and Enabled Show.
	if ( $for_product->is_type( 'variable' ) || ! autoship_is_visible_active_shop_product( $for_product ) )
	return;

	// Render template
	autoship_include_template(
		'product/schedule-options',
		array( 'product' => $for_product )
	);
}

/**
 * Adds the Autoship Template to the frontend for variable products.
 *
 * @param WC_Product $for_product The product the template is being displayed for.
 */
function autoship_print_cart_autoship_options_variable( $for_product = null ) {
	global $product;
	if ( empty( $for_product ) ) {
		$for_product = $product;
	}

  // Only Active and Enabled Show.
	if ( ! autoship_is_visible_active_shop_product( $for_product ) )
	return;

	autoship_include_template(
		'product/schedule-options-variable',
		array( 'product' => $for_product )
	);
}

/**
 * Returns the Autoship Info dialog link html
 *
 * @param int $product_id The product id the template is being displayed for.
 */
function autoship_info_dialog_link( $product_id ) {

  $options = autoship_get_settings_fields ( array(
    'autoship_product_info_display',
    'autoship_product_info_modal_size',
    'autoship_product_info_url',
    'autoship_product_info_btn_type',
    'autoship_product_info_btn_text',
    'autoship_product_info_html'
  ) );

  if ( 'none' == $options['autoship_product_info_display'] )
  return;

  $comps = array();

  // Get the Link Type
  $comps['type'] = 'autoship-link-trigger';

  if ( 'tooltip' == $options['autoship_product_info_display'] )
  $comps['type'] = 'autoship-tooltip-trigger';

  if ( 'modal' == $options['autoship_product_info_display'] )
  $comps['type'] = 'autoship-modal-trigger';

  // Get the Value of the href
  $comps['href'] = 'link' == $options['autoship_product_info_display'] ? $options['autoship_product_info_url'] : '#';
  $comps['target'] = 'link' == $options['autoship_product_info_display'] ? 'target="_blank"' : '';

  // Get the Dialog ID that we should use
  $comps['id'] = '#autoship_schedule_info_dialog_modal';

  // Get the content for the link
  $comps['text'] = 'text' == $options['autoship_product_info_btn_type'] ? $options['autoship_product_info_btn_text'] : '<i class="autoship-iconfont autoship-icon-schedule-unknown"></i>';

  // Wrap if text
  $comps['wrap_open'] = 'text' == $options['autoship_product_info_btn_type'] ? '<div class="autoship-info-link-wrapper">' : '';
  $comps['wrap_close'] = 'text' == $options['autoship_product_info_btn_type'] ? '</div>' : '';

  // Allow devs to adjust
  $comps = apply_filters( 'autoship_info_dialog_link_comps', $comps, $product_id, $options );

  $link = sprintf( '%s<a class="%s autoship-info-link" href="%s" %s data-modal-toggle="%s">%s</a>%s', $comps['wrap_open'], $comps['type'], $comps['href'], $comps['target'], $comps['id'], $comps['text'], $comps['wrap_close'] );

  return apply_filters( 'autoship_info_dialog_link', $link, $product_id, $comps, $options );

}

/**
 * Adds the Autoship Modal and Tooltip Template to the frontend for products.
 */
function autoship_print_autoship_info_dialog() {

  $options = autoship_get_settings_fields ( array(
    'autoship_product_info_display',
    'autoship_product_info_modal_size',
    'autoship_product_info_url',
    'autoship_product_info_btn_type',
    'autoship_product_info_btn_text',
    'autoship_product_info_html'
  ) );

  // Check if the Dialog Link is even setup and don't show if it's a link and no url, or no btn text or no content
  $empty =    ( empty( $options['autoship_product_info_btn_text'] ) && 'text' == $options['autoship_product_info_btn_type'] )
           || ( 'link' == $options['autoship_product_info_display'] )
           || ( empty( $options['autoship_product_info_html'] ) );

  if ( apply_filters( 'autoship_display_info_dialog', 'none' == $options['autoship_product_info_display'] || $empty ) )
  return;

	autoship_include_template(
		'product/schedule-info-dialog',
		array(
      'options' => $options
    )
	);

}

// ==========================================================
// Schedule Cart Widget Functions
// ==========================================================

/**
 * Includes the Schedule Cart Widget Template if Enabled
 */
function autoship_include_dynamic_cart_widget() {

  // Only Load if Dynamic Cart is Enabled
  if ( autoship_get_settings_fields('autoship_dynamic_cart') )
	autoship_include_template( 'scheduled-orders-cart/schedule-cart-dialogs' );

}

// ==========================================================
// DEFAULT HOOKED ACTIONS
// ==========================================================

/**
 * Add to Cart Functions
 *
 * @see autoship_add_cart_item_data()
 *
 * -- Sets the Checkout Price
 * @see autoship_add_cart_item()
 */
add_filter( 'woocommerce_add_cart_item_data', 'autoship_add_cart_item_data', 10, 4 );
add_filter( 'woocommerce_add_cart_item', 'autoship_add_cart_item', 10, 2 );

/**
 * Load Cart from Session, accounts for any changes to Schedule Data or Price changes
 *
 * @see autoship_get_cart_item_from_session()
 */
add_filter( 'woocommerce_get_cart_item_from_session', 'autoship_get_cart_item_from_session', 10, 3 );

/**
 * Add to Cart Schedule Validation Functions
 *
 * @see autoship_validate_cart_item_schedule_data()
 */
add_filter( 'autoship_update_cart_item_data_schedule_values', 'autoship_validate_cart_item_schedule_data', 10, 5 );

/**
 * Handles the Cart Frequency Change via Ajax
 * @see autoship_ajax_change_cart_frequency()
 */
add_action( 'wp_ajax_autoship_change_cart_frequency', 'autoship_ajax_change_cart_frequency' );
add_action( 'wp_ajax_nopriv_autoship_change_cart_frequency', 'autoship_ajax_change_cart_frequency' );

/**
 * Handles the Update Cart Item Action
 * @see autoship_update_cart_action()
 */
add_filter( 'woocommerce_update_cart_action_cart_updated', 'autoship_update_cart_action' );

/**
 * Outputs the Autoship Data assigned to Cart Items
 * @see autoship_display_cart_item_data()
 */
add_filter( 'woocommerce_cart_item_name', 'autoship_display_cart_item_data', 10, 3 );

/**
 * Outputs the Autoship Options in the Cart
 * @see autoship_display_cart_item_options()
 */
add_action( 'woocommerce_after_cart_item_name', 'autoship_display_cart_item_options', 10, 2 );

/**
 * Outputs Simple Product related Autoship Data
 * @see autoship_print_cart_autoship_options()
 */
add_action( 'woocommerce_before_add_to_cart_button', 'autoship_print_cart_autoship_options' );

/**
 * Outputs Variation related Autoship Data
 * @see autoship_print_cart_autoship_options_variable()
 */
add_action( 'woocommerce_before_single_variation', 'autoship_print_cart_autoship_options_variable' );

/**
 * Includes the Autoship Cart Features in the Footer
 * @see autoship_print_autoship_info_dialog()
 * @see autoship_include_dynamic_cart_widget()
 */
add_action( 'wp_footer', 'autoship_print_autoship_info_dialog', 1000 );
add_action( 'wp_footer', 'autoship_include_dynamic_cart_widget', 1000 );
