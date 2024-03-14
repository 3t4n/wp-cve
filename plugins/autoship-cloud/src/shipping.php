<?php


/**
 * Retrieves the shipping address form fields.
 * @return array The shipping address fields.
 */
function autoship_shipping_address_form_fields( $defaults = array() ){

  $defaults = wp_parse_args(
    $defaults,
    array(
      'country' => WC()->countries->get_base_country()
    )
  );

  if ( false ):

  // Retrieve the allowed countries.
	$shipping_country  = $defaults['country'];
	$allowed_countries = WC()->countries->get_shipping_countries();

  // If the current country isn't allowed default it.
	if ( ! array_key_exists( $shipping_country, $allowed_countries ) )
	$defaults['country'] = current( array_keys( $allowed_countries ) );

  endif;

  // Get the default values for the shipping address fields based on
  // the country
	$address_fields = WC()->countries->get_address_fields( $defaults['country'], '' );

  // Our Base Scheduled Order Shipping Fields.
  $base_fields = array(
    'first_name'  => array(
      'label'        => __( 'First name', 'autoship' ),
      'class'        => array( 'form-row-first' ),
      'value'        => isset( $defaults['first_name'] ) ? $defaults['first_name'] : '',
      'api_key'     => 'shippingFirstName',
    ),
    'last_name'  => array(
      'label'        => __( 'Last name', 'autoship' ),
      'class'        => array( 'form-row-last' ),
      'value'        => isset( $defaults['last_name'] ) ? $defaults['last_name'] : '',
      'api_key'     => 'shippingLastName',
    ),
    'company'    => array(
      'label'        => __( 'Company name', 'autoship' ),
      'class'        => array( 'form-row-wide' ),
      'value'        => isset( $defaults['company'] ) ? $defaults['company'] : '',
      'api_key'     => 'company',
    ),
    'country'    => array(
      'type'         => 'country',
      'label'        => __( 'Country', 'autoship' ),
      'class'        => array( 'form-row-wide', 'address-field' ),
      'value'        => isset( $defaults['country'] ) ? $defaults['country'] : '',
      'api_key'     => 'shippingCountry',
    ),
    'address_1'  => array(
      'label'        => __( 'Street address', 'autoship' ),
      'placeholder'  => esc_attr__( 'House number and street name', 'autoship' ),
      'class'        => array( 'form-row-wide', 'address-field' ),
      'value'        => isset( $defaults['address_1'] ) ? $defaults['address_1'] : '',
      'api_key'     => 'shippingStreet1',
    ),
    'address_2'  => array(
      'placeholder'  => esc_attr__( 'Apartment, suite, unit etc. (optional)', 'autoship' ),
      'class'        => array( 'form-row-wide', 'address-field' ),
      'value'        => isset( $defaults['address_2'] ) ? $defaults['address_2'] : '',
      'api_key'     => 'shippingStreet2',
    ),
    'city'       => array(
      'label'        => __( 'Town / City', 'autoship' ),
      'class'        => array( 'form-row-wide', 'address-field' ),
      'value'        => isset( $defaults['city'] ) ? $defaults['city'] : '',
      'api_key'     => 'shippingCity',
    ),
    'state'      => array(
      'type'         => 'state',
      'label'        => __( 'State / County', 'autoship' ),
      'class'        => array( 'form-row-wide', 'address-field' ),
      'value'        => isset( $defaults['state'] ) ? $defaults['state'] : '',
      'api_key'     => 'shippingState',
    ),
    'postcode'   => array(
      'label'        => __( 'Postcode / ZIP', 'autoship' ),
      'class'        => array( 'form-row-wide', 'address-field' ),
      'value'        => isset( $defaults['postcode'] ) ? $defaults['postcode'] : '',
      'api_key'     => 'shippingPostcode',
    ),
  );

  // Only keep WC Shipping Fields that exist in our base
  foreach ( $base_fields as $field => $values ) {

    if( isset( $address_fields[$field] ) )
    $base_fields[$field] = wp_parse_args( $base_fields[$field], $address_fields[$field] );

    // Add the type value in case it's not there
		if ( ! isset( $base_fields[$field]['type'] ) ) {
			$base_fields[$field]['type'] = 'text';
		}

  }

  $fields = apply_filters('autoship_default_shipping_address_form_fields', $base_fields, $defaults );

	return $fields;

}

/**
 * Modifies the available shipping methods for the existing zones by adding custom shipping methods where needed.
 * called from {@see update_option_wc_autoship_free_shipping} hook
 *
 * @param string  $old_value. The previos value for the Autoship Free Shipping.
 *                            Either checkout+autoship = on or empty if off
 * @param string  $new_value. The new value for the Autoship Free Shipping.
 *                            Either checkout+autoship = on or empty if off
 */
function autoship_populate_shipping_zones_shipping_methods( $old_value, $new_value ){

    $autoship_method = 'autoship_free_shipping';

    // Query DB for the zones
    // Can't use WC_Shipping_Zones since it triggers
    // woocommerce_shipping_zone_shipping_methods hook
    global $wpdb;

    $shipping_methods_table = $wpdb->prefix . 'woocommerce_shipping_zone_methods';
    $values = $wpdb->get_results("
    SELECT method_id, zone_id, instance_id
    FROM {$shipping_methods_table}");

    $shipping_zones = array();
    // Loop through the current records to see how many instances
    foreach ($values as $method_records) {
    $shipping_zones[$method_records->zone_id][$method_records->instance_id] = $method_records->method_id;}


    // Loop through the current zones and check for
    // Autoship methods
    foreach ( $shipping_zones as $zone_id => $zone_data ) {

      // If Enabled check if method doesn't exist and add it.
      if ( 'checkout+autoship' == $new_value ) {

        if ( !in_array( $autoship_method , $zone_data ) ){
          $zone = new WC_Shipping_Zone( $zone_id );
          $instance = $zone->add_shipping_method( $autoship_method );

          // Now Update any existing records to dissabled.
         $wpdb->update(
           "{$wpdb->prefix}woocommerce_shipping_zone_methods",
           array( 'is_enabled' => 0 ), array( 'method_id' => 'autoship_free_shipping' )
         );

        }

      // If the option is being dissabled remove all the methods.
      } elseif ( in_array( $autoship_method , $zone_data ) ){

        // Using a straight db delete since support for
        // delete_shipping_method wasn't add_method
        // until 3.0.
        $wpdb->delete( $shipping_methods_table, array( 'method_id' => 'autoship_free_shipping' ) );

      }



    }


}
add_action( 'update_option_autoship_free_shipping', 'autoship_populate_shipping_zones_shipping_methods', 10, 2 );

/**
 * Checks for missing Autoship shipping zones and populate them if needed.
 * Modifies the available shipping methods for the existing zone by adding custom shipping method
 * if the option is enabled and the zone doesn't exist.
 *
 * If the option is not enabled then don't waste time since the
 * autoship_populate_shipping_zones_shipping_methods hooked into {@see update_option_autoship_free_shipping}
 * hook will deal with removing or add methods initially.
 *
 * @see woocommerce_shipping_zone_shipping_methods
 * @param array  $methods shipping methods linked to this zone
 * @param array  $raw_methods array of instances of shipping methods, as well may containe classes as strings
 * @param array  $allowed_classes array of shipping class names. Can be filted by other plugins.
 * @param object $shipping_zone instance of the current WC_Shipping_Zone class.
 */
function autoship_check_shipping_zone_shipping_methods( $methods, $raw_methods, $allowed_classes, $shipping_zone ){

  // Grab the current Autoship option
  $free_shipping_option = get_option( 'autoship_free_shipping' );
  $autoship_method = 'autoship_free_shipping';

  $zone_id = $shipping_zone->get_id();

  // If the option isn't enabled don't worry be happy
  if ( ( 'checkout+autoship' != $free_shipping_option ) || ( !$zone_id ) )
  return  $methods;

  // We have to query the db to avoid an infinite loop.
  // Since the $shipping_zone might only include enabled zones.
  // Pulling the zone or zones again would be infinite loop.
  global $wpdb;
  $values = $wpdb->get_col(
  "
  SELECT method_id, instance_id
  FROM {$wpdb->prefix}woocommerce_shipping_zone_methods
  WHERE zone_id = {$zone_id};
  ");

  // Check for existance of Autoship method.
  $existing_instances = false;
  foreach ($values as $method) {

    // If this is not an autoship method continue.
    if ( $method !== $autoship_method )
    continue;

    $existing_instances = true;

  }

  // If the method don't exist for this zone
  // add it.
  if ( !$existing_instances ) {

    $instance = $shipping_zone->add_shipping_method( $autoship_method );

    // Force the Dissable
    $wpdb->update(
      "{$wpdb->prefix}woocommerce_shipping_zone_methods",
      array( 'is_enabled' => 0 ), array( 'instance_id' => absint( $instance ) )
    );

  }

  return $methods;

}
add_filter( 'woocommerce_shipping_zone_shipping_methods', 'autoship_check_shipping_zone_shipping_methods', 100, 4 );

/**
 *
 * @param array $rates Array of rates found for the package.
 * @return array
 */
function autoship_package_rates( $rates ) {

    $cart_has_autoship_items = autoship_cart_has_valid_autoship_items();
    $shipping_method = get_option( 'autoship_free_shipping' );

    foreach($rates as $single_method){
        if($single_method->method_id == 'autoship_free_shipping'){
            $instance_id = $single_method->instance_id;
        }
    }
    $free_shipping_option = false;
    global $wpdb;
    if(isset($instance_id)){
        $status = $wpdb->get_row( "SELECT is_enabled FROM {$wpdb->prefix}woocommerce_shipping_zone_methods WHERE instance_id = $instance_id", OBJECT );
        $free_shipping_option = $status->is_enabled;

    }
    $free_shipping_enabled = false;
    if ($free_shipping_option && $cart_has_autoship_items ) {
            $free_shipping_enabled = true;
    }
    if ( ! $free_shipping_enabled ) {
            foreach ( $rates as $rate_id => $rate ) {
                    if ( 'autoship_free_shipping' === $rate->method_id ) {
                            unset( $rates[ $rate_id ] );
                            break;
                    }
            }
    }
    return $rates;
}
add_filter( 'woocommerce_package_rates', 'autoship_package_rates', 100 );


/**
 * Returns the array of Patched US Territories
 * @return array The patched territories
 */
function autoship_patched_shipping_counrties(){

  return apply_filters( 'autoship_patched_customer_shipping_countries', array(
    'AS' => 'American Samoa',                             // American Samoa
    'FM' => 'Federated States Of Micronesia', // Federated States Of Micronesia
    'GU' => 'Guam',                           // Guam
    'MH' => 'Marshall Islands',               // Marshall Islands
    'MP' => 'Northern Mariana Islands',       // Northern Mariana Islands
    'PR' => 'Puerto Rico',                    // Puerto Rico
    'PW' => 'Palau',                          // Palau
  ) );

}

/**
 * Patch US Territories and translate countries to states
 *
 * @param array       $shipping_data Array of shipping data
 * @return array
 */
function autoship_patch_customer_shipping_countries( $shipping_data ){

  // Territories PATCH
  // AS | American Samoa
  // FM | Federated States Of Micronesia
  // GU | Guam
  // MH | Marshall Islands
  // MP | Northern Mariana Islands
  // PR | Puerto Rico
  // PW | Palau

  // WooCommerce treats these territories as countries
  // though US Shipping & QPilot treat them as States
  $territories = autoship_patched_shipping_counrties();

  if ( isset( $territories[$shipping_data['shippingCountry']] ) ){

    // Make the territory the State
    $shipping_data['shippingState'] = $shipping_data['shippingCountry'];

    // Make the country US
    $shipping_data['shippingCountry'] = 'US';

  }

  return $shipping_data;

}
add_filter( 'autoship_generated_customer_shipping_data', 'autoship_patch_customer_shipping_countries', 10, 1 );
add_filter( 'autoship_generated_customer_data', 'autoship_patch_customer_shipping_countries', 10, 1 );
add_filter( 'autoship_generated_scheduled_order_updated_shipping_data', 'autoship_patch_customer_shipping_countries', 10, 1 );

/**
 * Reverses the Patched US Territories and translate states back to countries
 * when pulled Shipping Address info from the Scheduled Orders to display
 *
 * @param array       $autoship_order The original Autoship Order Data
 * @return array
 */
function autoship_patch_customer_displayed_shipping_countries( $shipping_data ){

  // Territories PATCH
  // AS | American Samoa
  // FM | Federated States Of Micronesia
  // GU | Guam
  // MH | Marshall Islands
  // MP | Northern Mariana Islands
  // PR | Puerto Rico
  // PW | Palau

  // WooCommerce treats these territories as countries
  // though US Shipping & QPilot treat them as States
  // For display we need to normalize these back to countries for WC
  $territories = autoship_patched_shipping_counrties();

  if ( isset( $territories[$shipping_data['shippingState']] ) ){

    // Make the country the territory
    $shipping_data['shippingCountry'] = $shipping_data['shippingState'];

    // Make the country US
    $shipping_data['shippingState'] = ''; //$territories[$shipping_data['shippingState']];

  }

  return $shipping_data;

}

/**
 * Reverses the Patched US Territories and translate states back to countries
 * when pulled Shipping Address info from the Scheduled Orders to display
 *
 * @param array       $address Array of shipping data
 * @param array       $autoship_order The original Autoship Order Data
 * @param string      $type The type of address being pulled (i.e. shipping, payment etc )
 * @return array
 */
function autoship_patch_form_and_displayed_shipping_countries( $address, $autoship_order, $type = 'shipping' ){

  if ( 'shipping' !== $type )
  return $address;

  // Now get the WC Normalized Address for Form Display
  $autoship_order = autoship_patch_customer_displayed_shipping_countries( $autoship_order );

  // Re-translate the QPilot fields to WC Form Fields
  return autoship_order_get_woocommerce_shipping_address_values( $autoship_order );

}
add_filter( 'autoship_order_address_values', 'autoship_patch_form_and_displayed_shipping_countries', 10, 99 );
