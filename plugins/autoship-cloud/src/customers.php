<?php

// ==========================================================
// Scheduled Order Customer Address Functions
// ==========================================================

/**
* Gathers the Customer Shipping Data.
*
* @param int|WC_Customer $wc_customer    WooCommerce customer ID or WC Customer Object
* @param array $customer_data Optional Overrides
* @return array The shipping data
*/
function autoship_generate_customer_shipping_data ( $wc_customer, $customer_data = array() ){

  if ( is_numeric( $wc_customer ) )
  $wc_customer = new WC_Customer( $wc_customer );

  // Get the Customer Data.
  return apply_filters( 'autoship_generated_customer_shipping_data', array_merge( array(
    'shippingFirstName'      => $wc_customer->get_shipping_first_name(),
    'shippingLastName'       => $wc_customer->get_shipping_last_name(),
    'shippingStreet1'        => $wc_customer->get_shipping_address_1(),
    'shippingStreet2'        => $wc_customer->get_shipping_address_2(),
    'shippingCity'           => $wc_customer->get_shipping_city(),
    'shippingState'          => $wc_customer->get_shipping_state(),
    'shippingPostcode'       => substr( $wc_customer->get_shipping_postcode(), 0, 20 ),
    'shippingCountry'        => substr( $wc_customer->get_shipping_country(), 0, 2 ),
    'company'                => $wc_customer->get_shipping_company(),
  ), $customer_data ), $wc_customer, $customer_data );

}

/**
* Gathers the Customer Billing Data.
*
* @param int|WC_Customer $wc_customer    WooCommerce customer ID or WC Customer Object
* @param array $customer_data Optional Overrides
* @return array The billing data
*/
function autoship_generate_customer_billing_data ( $wc_customer, $customer_data = array() ){

  if ( is_numeric( $wc_customer ) )
  $wc_customer = new WC_Customer( $wc_customer );

  // Get the Customer Data.
  return apply_filters( 'autoship_generated_customer_billing_data', array_merge( array(
		'billingFirstName'       => $wc_customer->get_billing_first_name(),
		'billingLastName'        => $wc_customer->get_billing_last_name(),
		'billingStreet1'         => $wc_customer->get_billing_address_1(),
		'billingStreet2'         => $wc_customer->get_billing_address_2(),
		'billingCity'            => $wc_customer->get_billing_city(),
		'billingState'           => $wc_customer->get_billing_state(),
		'billingPostcode'        => $wc_customer->get_billing_postcode(),
		'billingCountry'         => $wc_customer->get_billing_country(),
    'phoneNumber'            => $wc_customer->get_billing_phone(),
    'company'                => $wc_customer->get_billing_company(),
  ), $customer_data ), $wc_customer, $customer_data );
}

/**
* Gathers the Customer Data.
*
* @param int|WC_Customer $wc_customer    WooCommerce customer ID or WC Customer Object
* @param array $customer_data Optional Overrides
* @return array The data
*/
function autoship_generate_customer_data ( $wc_customer, $customer_data = array() ){

  // Gather the Customer Info
  if (  is_numeric( $wc_customer ) )
  $wc_customer = new WC_Customer( $wc_customer );

  // Gather the Billing & Shipping Data
  $customer_data = autoship_generate_customer_billing_data( $wc_customer, $customer_data );
  $customer_data = autoship_generate_customer_shipping_data( $wc_customer, $customer_data );

  // Get the Customer Data.
  return apply_filters( 'autoship_generated_customer_data',array_merge( array(
    'applyToScheduledOrders' => false,
    'id'                     => strval( $wc_customer->get_id() ),
    'email'                  => $wc_customer->get_email(),
    'firstName'              => $wc_customer->get_first_name(),
    'lastName'               => $wc_customer->get_last_name(),
  ), $customer_data ), $wc_customer, $customer_data );

}


/**
 * Generates a URL to update Schedueld Orders customer info.
 * Can be modified via {@see autoship_get_update_scheduled_orders_customer_info_url}
 *
 * @param int     $wc_customer_id Optional. The WC Customer ID to view the orders for.
 *                                          Only available to admins.
 * @return string
 */
function autoship_get_scheduled_orders_update_customer_info_url( $wc_customer_id = '') {
  return apply_filters( 'autoship_get_update_scheduled_orders_customer_info_url', autoship_get_endpoint_url ( 'update-scheduled-orders-customer-info', $wc_customer_id, wc_get_page_permalink( 'myaccount' ) ), $wc_customer_id );
}

/**
* Retrieves the default setting for the apply to all scheduled orders.
* @param int $wc_customer_id. The user being updated.
* @return bool True to apply or false not to.
*/
function autoship_apply_customer_info_to_all_scheduled_order_default( $wc_customer_id ){
  return apply_filters('autoship_apply_to_scheduled_orders_by_default', false, $wc_customer_id );
}

/**
* Checks if the customer data should be updated on all autoship orders.
* and if yes then adds the appropriate flag.
*
* @param array $customer_data Any custom / added customer data
* @param int $wc_customer_id. The user being updated.
*
* @return array the filtered user data.
*/
function autoship_automatically_apply_customer_data_to_all_scheduled_orders( $customer_data, $wc_customer_id ) {

  // Get the apply to all scheduled orders setting.
  $customer_data['applyToScheduledOrders'] = autoship_apply_customer_info_to_all_scheduled_order_default( $wc_customer_id );
  return $customer_data;

}
add_filter('autoship_create_update_autoship_customer', 'autoship_automatically_apply_customer_data_to_all_scheduled_orders',10, 2 );


/**
* Hooks into the Autoship Update Scheduled Orders Customer Info endpoint
* and updates the supplied customers scheduled orders' info.
*/
function autoship_update_customer_info_on_all_scheduled_orders(){

  global $wp;

  if ( isset( $wp->query_vars['update-scheduled-orders-customer-info'] ) ) {

    wc_nocache_headers();

    $wc_customer_id = absint( $wp->query_vars['update-scheduled-orders-customer-info'] );

    // Apply Security Messures.
    if ( ( get_current_user_id() == $wc_customer_id ) || autoship_rights_checker( 'autoship_update_customer_info_on_all_scheduled_orders', array('administrator') ) ){

    $autoship_customer = autoship_update_autoship_customer( $wc_customer_id, array( 'applyToScheduledOrders' => true ) );

    $schedule_label = autoship_translate_text( 'Scheduled Orders' );

    if ( is_wp_error( $autoship_customer ) ){
    $message = sprintf( __( "There was a problem updating the information on your %s. Additional details: %s", 'autoship' ), $schedule_label, $autoship_customer->get_error_message() );
    $notice = apply_filters( 'autoship_update_customer_info_on_all_scheduled_orders_failure_notice', array( 'message' => $message, 'code' => 'error' ), $wc_customer_id );
    } else {
    $message = sprintf( __( "Your %s Have been successfully updated.", 'autoship' ), $schedule_label );
    $notice = apply_filters( 'autoship_update_customer_info_on_all_scheduled_orders_updated_notice', array( 'message' => $message, 'code' => 'success' ), $wc_customer_id );
    }
    wc_add_notice( $notice['message'], $notice['code'] );
    }

    wp_redirect( wc_get_account_endpoint_url( 'edit-address' ) );
    exit();

  }

}
add_action( 'wp', 'autoship_update_customer_info_on_all_scheduled_orders', 20 );

/**
* Fires additional autoship actions after a users account is updated.
* @param int $wc_customer_id.          The user being updated.
* @param string $load_address.  The address field type being edited.
*/
function autoship_after_update_customer_autoship_actions( $wc_customer_id, $load_address ){


  $trigger_notice = ( 'shipping' == $load_address ) && !autoship_apply_customer_info_to_all_scheduled_order_default( $wc_customer_id );

  $schedule_label = autoship_translate_text( 'Scheduled Orders' );

  // Check if we should be updating the shipping address on all orders.
  // We only make this call if it's not done already, the user is editing the shipping address and the checkbox is checked.
  if ( $trigger_notice && isset( $_POST['autoship_apply_to_scheduled_orders_by_default'] ) && absint($_POST['autoship_apply_to_scheduled_orders_by_default']) ){

    $trigger_notice = false;
    $autoship_customer = autoship_update_autoship_customer( $wc_customer_id, array( 'applyToScheduledOrders' => true ) );

    if ( is_wp_error( $autoship_customer ) ){
      $message = sprintf( __( "There was a problem updating the shipping information on your %s. Additional details: %s", 'autoship' ), $schedule_label, $autoship_customer->get_error_message() );
      $notice = apply_filters( 'autoship_update_customer_info_on_all_scheduled_orders_failure_notice', array( 'message' => $message, 'code' => 'error' ), $wc_customer_id );
      if( is_admin() && ! wp_doing_ajax() ) {
        WC_Admin_Notices::add_custom_notice( $notice['code'], $notice['message'] );
        WC_Admin_Notices::output_custom_notices();
      } else {
        wc_add_notice( $notice['message'], $notice['code'] );
      }
    }

  }

  // Only display the notice if the update was not applied by default to all
  // scheduled orders.
  if ( $trigger_notice && apply_filters('autoship_display_customer_update_actions', true, get_current_user_id() ) ){

    // Get the Set on all scheduled orders action url.
    $url = autoship_get_scheduled_orders_update_customer_info_url( $wc_customer_id );
    $message = sprintf( __( 'Update Shipping Address information for %s? <a href="%s" tabindex="1" class="button wc-forward">%s</a>', 'autoship' ), $schedule_label, esc_url( $url ), esc_html__( 'Update', 'autoship' ) );
    $notice = apply_filters( 'autoship_apply_customer_info_to_all_scheduled_order_default_action_notice', array( 'message' => $message, 'code' => 'success' ), $wc_customer_id );
    if( is_admin() && ! wp_doing_ajax() ) {
      WC_Admin_Notices::add_custom_notice( $notice['code'], $notice['message'] );
      WC_Admin_Notices::output_custom_notices();
    } else {
      wc_add_notice( $notice['message'], $notice['code'] );
    }

  }

}
add_action( 'woocommerce_customer_save_address', 'autoship_after_update_customer_autoship_actions', 11, 2 );

/**
* Outputs the Checkbox on the Edit Shipping Address Page
* @see woocommerce_form_field()
*/
function autoship_display_apply_customer_info_to_all_scheduled_orders_checkbox(){

  // If the override is set don't include the checkbox.
  if ( autoship_apply_customer_info_to_all_scheduled_order_default( get_current_user_id() ) || ! apply_filters('autoship_display_customer_update_actions', true, get_current_user_id() ) )
  return;

  $schedule_label = autoship_translate_text( 'Scheduled Orders' );

  ob_start();
  woocommerce_form_field( 'autoship_apply_to_scheduled_orders_by_default', array(
    'type'          => 'checkbox',
    'class'         => array('form-row form-row-wide autoship-default-orders'),
    'label_class'   => array('woocommerce-form__label woocommerce-form__label-for-checkbox checkbox'),
    'input_class'   => array('woocommerce-form__input woocommerce-form__input-checkbox input-checkbox'),
    'required'      => false,
    'label'         => apply_filters( 'autoship_display_apply_customer_info_to_all_scheduled_orders_checkbox_label', sprintf( __("Update the Shipping Address for all my %s", 'autoship'), $schedule_label ) ),
  ), apply_filters( 'autoship_display_apply_customer_info_to_all_scheduled_orders_checkbox_default', 0, get_current_user_id() ) );
  echo apply_filters( 'autoship_display_apply_customer_info_to_all_scheduled_orders_checkbox' , ob_get_clean(), apply_filters( 'autoship_display_apply_customer_info_to_all_scheduled_orders_checkbox_default', 0, get_current_user_id() ) );

}
add_action( 'woocommerce_after_edit_address_form_shipping','autoship_display_apply_customer_info_to_all_scheduled_orders_checkbox');


/**
* Outputs the Button link on the Addresses Page
*/
function autoship_display_apply_customer_info_to_all_scheduled_orders_btn_link( $notice ){

  // Get the Set on all scheduled orders action url.
  $url = autoship_get_scheduled_orders_update_customer_info_url( get_current_user_id() );

  ob_start();
  echo $notice .'<br/>';

  // Only display the notice if the user has scheduled orders.
  if ( apply_filters('autoship_display_customer_update_actions', true, get_current_user_id() ) ){

  echo apply_filters( 'autoship_display_apply_customer_info_to_all_scheduled_orders_btn_link_label', sprintf( __('Click <a href="%s">here</a> to update your shipping address on all %s', 'autoship'), $url, autoship_translate_text( 'Scheduled Orders' ) ), $url );

  }

  echo apply_filters( 'autoship_display_apply_customer_info_to_all_scheduled_orders_btn_label' , ob_get_clean(), $url, $notice );

}
add_filter( 'woocommerce_my_account_my_address_description','autoship_display_apply_customer_info_to_all_scheduled_orders_btn_link', 10, 1 );

/**
* Filters the global autoship_display_customer_update_actions filter
* And hides actions when the user has no scheduled orders.
* @see autoship_customer_has_scheduled_orders ()
*
* @param bool $show The current show/hide flag for the filter.
* @param int $wc_customer_id The WC Customer ID.
*
* @return bool True of the user has scheduled orders otherwise false.
*/
function autoship_hide_update_customer_info_actions_when_no_scheduled_orders( $show, $wc_customer_id ){

  // Returns true if the original value is true and the user has scheduled orders.
  return $show && autoship_customer_has_scheduled_orders ( $wc_customer_id );

}
add_filter('autoship_display_customer_update_actions','autoship_hide_update_customer_info_actions_when_no_scheduled_orders', 10, 2 );


// ==========================================================
// Update Customer Information Functions
// ==========================================================

/**
* Updates an Existing Customer's information in QPilot or
* creates the customer if they don't exist.
*
* @param int|WC_Customer  $wc_customer    The WC User ID or WC Customer Object
* @param array            $customer_data  Optional Customer Data to user instead of Default
* @param stdClass|WP_Error|false The created / updated customer object from QPilot
*                                A WP_Error on failure, or false if the user is not valid.
*/
function autoship_update_autoship_customer( $wc_customer, $customer_data = array() ) {

  if (  is_numeric( $wc_customer ) )
  $wc_customer = new WC_Customer( $wc_customer );

  $customer_data = autoship_generate_customer_data( $wc_customer, $customer_data );

  // QPilot Requires an email to create a customer.
  if ( empty( $customer_data['email'] ) )
  return false;

  // Get an Instance of the QPilot Client Class
	$client = autoship_get_default_client();

	try {

    // try to update this customers info or create if doesn't exist..
    // Customer data can be added/ filtered using the new filter.
  	$result = $client->upsert_customer( $wc_customer->get_id(), $customer_data['email'], $customer_data );

  } catch ( Exception $e ) {

    $notice = autoship_expand_http_code( $e->getCode() );
    $result = new WP_Error( 'Updating Customer Failed', __( $notice['desc'], "autoship" ) );
    autoship_log_entry( __( 'Autoship Customers', 'autoship' ), sprintf('There was an Issue Creating or Updating Customer #%d. Additional Details: %d: %s', $wc_customer->get_id(), $e->getCode(), $e->getMessage() ) );

  }

  return $result;

}

/**
* Updates an Existing Customer's information in QPilot or
* creates the customer if they don't exist.
*
* @param int|WC_Customer  $wc_customer    The WC User ID or WC Customer Object
* @param array            $customer_data  Optional Customer Data to user instead of Default
* @param stdClass|WP_Error|false The created / updated customer object from QPilot
*                                A WP_Error on failure, or false if the user is not valid.
*/
function autoship_create_update_autoship_customer( $wc_customer, $customer_data = array() ) {

  // Do not attempt to add a empty-customer
  if ( empty( $wc_customer ) )
  return false;

  // try to update this customers info or create if doesn't exist.
  // Customer data can be added / filtered using the filter.
	return autoship_update_autoship_customer( $wc_customer, apply_filters(
  'autoship_create_update_autoship_customer',
  $customer_data, $wc_customer ) );

}
/** Fires on wc checkout when a users account is created / edited.*/
add_action( 'woocommerce_checkout_update_user_meta', 'autoship_create_update_autoship_customer', 100, 1 );
/** Fires when an update to the name, email or password is made in the account page.*/
add_action( 'woocommerce_save_account_details', 'autoship_create_update_autoship_customer', 10, 1 );
/** Fires when an update to a billing or shipping address form was submitted through the user wc account page.*/
add_action( 'woocommerce_customer_save_address', 'autoship_create_update_autoship_customer', 10, 1 );

/**
* Updates an Existing Customer's information in QPilot
* Valid Customer roles can be filtered via {@see autoship_valid_customer_types}
*
* @param int|WC_Customer  $wc_customer    The WC User ID or WC Customer Object
* @param array            $customer_data  Optional Customer Data to user instead of Default
* @param stdClass|WP_Error|false The created / updated customer object from QPilot
*                                A WP_Error on failure, or false if the user is not valid.
*/
function autoship_maybe_create_update_autoship_customer( $wc_customer, $customer_data = array() ) {

  return autoship_customer_type_validation( $wc_customer ) ?
  autoship_create_update_autoship_customer( $wc_customer, $customer_data ) : false;

}
// Used when an Admin changes a users info.
add_action( 'edit_user_profile_update', 'autoship_maybe_create_update_autoship_customer', 10, 1 );
// Used when a user changes their own info.
add_action( 'profile_update', 'autoship_maybe_create_update_autoship_customer', 10, 1 );

/**
* Validates a user is a allowed role.
* Valid Customer roles can be filtered via {@see autoship_valid_customer_types}
*
* @param int|WC_Customer  $wc_customer    The WC User ID or WC Customer Object
* @return bool True if valid type else false.
*/
function autoship_customer_type_validation( $wc_customer ){

  // They currently aren't a customer so if they are a customer role then add them.
  $valid_types = apply_filters( 'autoship_valid_customer_types' , array('customer') );

  // Check if this user is valid.
  $user = get_user_by( 'ID' , is_numeric( $wc_customer ) ? $wc_customer : $wc_customer->get_id() );

  $valid = false;
  foreach ($valid_types as $type ) {

    if ( in_array( $type, (array) $user->roles ) )
    $valid = true;

  }

  return $valid;

}

/**
 * Retrieves the available customers from QPilot
 *
 * @param array $params {
 *     Optional. An array of search parameters.
 *
 *     @type int     $page                 The search results page to return. Default 1
 *     @type int     $pageSize             The default page size.  Default 100
 *     @type string  $orderBy              A product property to sort the results by
 *     @type string  $order                The Sort Direction the results should be returned ( DESC vs ASC )
 *     @type string  $search               A query string to search for.
 * }
 * @param bool $iterative True iterate through and return all results.
 *                        If False only the page and params requested returned.
 * @return array An array of stdClass Customer Objects or array of stdClass customer objects and totals
 */
function autoship_search_available_customers( $params = array(), $index = 1, $iterative = true ){

  $params = wp_parse_args( $params, array( 'pageSize' => 100, 'page' => $index ) );

  // Create QPilot client instance.
	$client = autoship_get_default_client();

	try {

    // Retrieve the page of products from QPilot.
		$customers = $client->get_customers( $params );

  } catch ( Exception $e ) {

    $notice = autoship_expand_http_code( $e->getCode() );
    $customers = new WP_Error( 'Customer Search Failed', __( $notice['desc'], "autoship" ) );
    autoship_log_entry( __( 'Autoship Customers', 'autoship' ), sprintf( 'Searching available customers failed. Additional Details: Error Code %s - %s', $e->getCode(), $e->getMessage() ) );

  }

  if ( is_wp_error( $customers ) || !$iterative )
  return $customers;

  if ( $customers->totalPages > $index ){

    $index++;
    $new_customers = autoship_search_available_customers( $params, $index );
    return !is_wp_error( $new_customers ) ? array_merge( $customers->items, $new_customers ) : $new_customers;

  }

  return $customers->items;

}

/**
 * Get the Autoship Customer
 * @param int $wc_customer_id WooCommerce customer ID
 * @return stdClass The autoship customer.
 */
function autoship_get_autoship_customer( $wc_customer_id ) {

	// Fetch the qpilot customer
	$client = autoship_get_default_client();

	try {

		$customer = $client->get_customer( $wc_customer_id );

	} catch ( Exception $e ) {

    // Filter allows Autoship to automatically generate customers when not found.
    if ( '404' == $e->getCode() ) {

      $notice = new WP_Error( 'QPilot Customer Not Found', __( "The supplied customer can not be found in QPilot", "autoship" ) );
      autoship_log_entry( __( 'Autoship Customers', 'autoship' ), sprintf( 'No QPilot Customer found for #%d: %s', $wc_customer_id, $e->getMessage() ) );
      return $notice;

    } else {

      $notice = new WP_Error( 'Finding QPilot Customer Failed', __( "A problem was encountered while attempting to find this customer in QPilot.", "autoship" ) );
      autoship_log_entry( __( 'Autoship Customers', 'autoship' ), sprintf( 'There was a problem when attempting to find a QPilot Customer for #%d: %s', $wc_customer_id, $e->getMessage() ) );
      return $notice;

    }

  }

	return $customer;
}

/**
 * Deletes a Autoship Customer
 * @param int $wc_customer_id WooCommerce customer ID
 * @return bool True on Success
 */
function autoship_delete_customer( $wc_customer_id ) {

	// Fetch the qpilot customer
	$client = autoship_get_default_client();

	try {

		$result = $client->delete_customer( $wc_customer_id );

	} catch ( Exception $e ) {

    // Filter allows Autoship to automatically generate customers when not found.
    if ( '404' == $e->getCode() ) {

      $notice = new WP_Error( 'QPilot Customer Not Found', __( "The supplied customer can not be found in QPilot", "autoship" ) );
      autoship_log_entry( __( 'Autoship Customers', 'autoship' ), sprintf( 'No QPilot Customer found for #%d: %s', $wc_customer_id, $e->getMessage() ) );
      return $notice;

    } else {

      $notice = new WP_Error( 'Deleting QPilot Customer Failed', __( "A problem was encountered while attempting to delete this customer in QPilot", "autoship" ) );
      autoship_log_entry( __( 'Autoship Customers', 'autoship' ), sprintf( 'There was a problem when attempting to delete a QPilot Customer for #%d: %s', $wc_customer_id, $e->getMessage() ) );
      return $notice;

    }

  }

  // Clear it.
  update_user_meta( $wc_customer_id, '_autoship_customer_id', '' );

	return $result;
}

/**
 * Checks if a customer exists and if not depending on the action
 * Upserts a missing customer.
 *
 * @param int|WC_Customer $wc_customer    WooCommerce customer ID or WC Customer Object
 * @param string          $action         The scenario for when this is being called
 *                                        Used for the new filter so auto-create on 404 can
 *                                        be controlled.
 * @return int The autoship customer id.
 */
function autoship_get_autoship_customer_id( $wc_customer, $action = '' ) {

  $customer = autoship_check_autoship_customer( $wc_customer, $action );
	return !$customer ? $customer : $customer->id;

}

/**
 * Checks if a customer exists and if not depending on the action
 * Upserts a missing customer.
 *
 * @param int|WC_Customer $wc_customer    WooCommerce customer ID or WC Customer Object
 * @param string          $action         The scenario for when this is being called
 *                                        Used for the new filter so auto-create on 404 can
 *                                        be controlled.
 * @return bool|stdClass  False if the customer doesn't exist or failure else the QPilot Customer Object
 */
function autoship_check_autoship_customer( $wc_customer, $action = '' ) {

  // Gather the Customer Info
  $id = is_numeric( $wc_customer ) ? $wc_customer : $wc_customer->get_id();

	// Fetch the qpilot customer
	$client = autoship_get_default_client();

  try {

  	$customer = $client->get_customer( $id );

  } catch ( Exception $e ) {

    // Filter allows Autoship to automatically generate customers when not found.
    if ( '404' == $e->getCode() ) {

      if ( apply_filters( 'autoship_auto_generate_not_found_customers', !empty( $action ) , $action, $id ) ){

        // Use the Upsert to create/update/get the customer data.
        $customer = autoship_update_autoship_customer( $wc_customer );
        return is_wp_error( $customer ) ? false : $customer;

      }

      autoship_log_entry( __( 'Autoship Customers', 'autoship' ), sprintf( 'No QPilot Customer found for #%d: %s', $id, $e->getMessage() ) );

      return false;

    }

    autoship_log_entry( __( 'Autoship Customers', 'autoship' ), sprintf( 'There was a problem when attempting to find a QPilot Customer for #%d: %s', $id, $e->getMessage() ) );

    return false;

  }

	return $customer;

}

/**
* Checks if the current user is an autoship customer.
*
* @param int $wc_customer_id WooCommerce customer ID
* @return int The autoship customer id or 0 if not found or Error
*/
function autoship_is_active_autoship_customer( $wc_customer_id ) {

	// Fetch the qpilot customer
	$client = autoship_get_default_client();

	try {

		$customer = $client->get_customer( $wc_customer_id );

  // We don't fail with this function just return negative
	} catch ( Exception $e ) {
    return 0;
	}

	return $customer->id;
}

/**
* Checks if the current user has any scheduled orders.
*
* @see autoship_is_active_autoship_customer()
*
* @param int $wc_customer_id WooCommerce customer ID
* @return bool  True if the supplied user is an autoship customer
*               and has a valid order.
*/
function autoship_customer_has_scheduled_order( $wc_customer_id ) {

	// Check if this is a QPilot customer
	$autoship_customer_id = autoship_is_active_autoship_customer( $wc_customer_id );

  if ( !$autoship_customer_id )
  return false;

  // Allow devs to default the freq and freq type params
  $filter = wp_parse_args(
  array( 'frequency' => NULL, 'frequency_type' => NULL ),
  apply_filters( 'autoship_customer_has_scheduled_order_filter', array( 'frequency' => NULL, 'frequency_type' => NULL ), $wc_customer_id ) );

  // Now check if the user has an order.
  $order = autoship_get_next_scheduled_order( $autoship_customer_id, $filter['frequency'], $filter['frequency_type'] );

	return ! ( is_wp_error( $order ) || !$order );

}

/**
* Gathers the Customer Data to send to QPilot.
*
* @param int|WC_Customer $wc_customer    WooCommerce customer ID or WC Customer Object
* @param array $customer_data Optional Overrides
* @return array The mapped Upsert Data
*/
function autoship_generate_customer_upsert_data ( $wc_customer, $customer_data = array() ){

  // Gather the Customer Info
  if (  is_numeric( $wc_customer ) ){
    $id = $wc_customer;
    $wc_customer = new WC_Customer( $wc_customer );
  } else {
    $id = $wc_customer->get_id();
  }

  // Get the Customer Data.
  $customer_data = autoship_generate_customer_data ( $wc_customer, $customer_data );

  return apply_filters( 'autoship_customer_upsert_data', $customer_data );

}

/**
 * Retrieves the customer summaries data from QPilot
 *
 * @param array   $params {
 *     Optional. An array of optional arguments.
 *
 *     @type int     $page                 The search results page to return. Default 1
 *     @type int     $pageSize             The default page size.  Default 100
 *     @type string  $orderBy              A product property to sort the results by
 *     @type string  $order                The Sort Direction the results should be returned ( DESC vs ASC )
 *     @type string  $search               A query string to search for.
 *     @type string  $startDate            The start date to filter customers by their creation date
 *     @type string  $endDate              The end date to filter customers by their creation date
 *     @type bool    isDeleted   					 True to Include soft deleted customers
 * }
 *
 * @return array An array of stdClass objects
 */
function autoship_available_customer_summaries( $params = array() ){

	// Fetch the qpilot customer
	$client = autoship_get_default_client();

	try {

		$data = $client->get_customer_summaries( $params );

	} catch ( Exception $e ) {

    // Filter allows Autoship to automatically generate customers when not found.
    if ( '404' == $e->getCode() ) {

      $notice = new WP_Error( 'QPilot Customer Summaries Not Found', __( "Customer Sumaries Data matching the supplied values can not be found in QPilot", "autoship" ) );
      autoship_log_entry( __( 'Autoship Customer Summaries', 'autoship' ), __( 'No QPilot Customer Summaries found.', 'autoship' ) );
      return $notice;

    } else {

      $notice = new WP_Error( 'Finding QPilot Customer Summaries Failed', __( "A problem was encountered while attempting to retrieve customer metrics from QPilot.", "autoship" ) );
      autoship_log_entry( __( 'Autoship Customer Summaries', 'autoship' ), sprintf( 'There was a problem when attempting to retrieve customer summaries from QPilot: %s', $e->getMessage() ) );

      return $notice;

    }

  }

	return $data;
}

// ==========================================================
// Customer Metrics Functions
// ==========================================================

/**
 * Retrieves the customer metrics data from QPilot
 *
 * @param array   $params {
 *     Optional. An array of optional search params.
 *
 *     @type string  $startDate             The start date to filter customers by their creation date
 *     @type string  $endDate               The end date to filter customers by their creation date
 *     @type int     $page                  The search results page to return. Default 1
 *     @type int     $pageSize              The default page size.  Default 100
 *		 @type array   $customerIds						An array of Customer IDs to pull
 *     @type bool    excludeEventLogsData   True to Include event logs related data.
 * }
 *
 * @return array An array of stdClass objects
 */
function autoship_available_customer_metrics( $params = array() ){

	// Fetch the qpilot customer
	$client = autoship_get_default_client();

	try {

		$data = $client->get_customer_metrics( $params );

	} catch ( Exception $e ) {

    // Filter allows Autoship to automatically generate customers when not found.
    if ( '404' == $e->getCode() ) {

      $notice = new WP_Error( 'QPilot Customer Metrics Not Found', __( "Customer Metrics Data matching the supplied values can not be found in QPilot", "autoship" ) );
      autoship_log_entry( __( 'Autoship Customer Metrics', 'autoship' ), __( 'No QPilot Customer Metrics found.', 'autoship' ) );
      return $notice;

    } else {

      $notice = new WP_Error( 'Finding QPilot Customer Metrics Failed', __( "A problem was encountered while attempting to retrieve customer metrics from QPilot.", "autoship" ) );
      autoship_log_entry( __( 'Autoship Customer Metrics', 'autoship' ), __( 'There was a problem when attempting to retrieve customer metrics from QPilot ', 'autoship' ) );
      return $notice;

    }

  }

	return $data;
}

/**
* Returns the Customer Metrics field names and corresponding WP Metafield names.
*
* @param string $field Optional. A specific field to return
* @return string|array The mapped fields or single field name
*/
function autoship_get_customer_metrics_fields( $field = '' ){

  $fields = apply_filters( 'autoship_customer_metrics_fields_mapping', array(
    'customerCreatedDateUTC'            => 'autoship_customer_created_date_utc',
    'firstScheduledOrderCreatedUTC'     => 'autoship_first_scheduled_order_created_utc',
    'totalScheduledOrdersActive'        => 'autoship_total_scheduled_orders_active',
    'totalScheduledOrdersActiveValue'   => 'autoship_total_scheduled_orders_active_value',
    'totalScheduledOrdersPaused'        => 'autoship_total_scheduled_orders_paused',
    'totalScheduledOrdersPausedValue'   => 'autoship_total_scheduled_orders_paused_value',
    'totalScheduledOrdersFailed'        => 'autoship_total_scheduled_orders_failed',
    'totalScheduledOrdersFailedValue'   => 'autoship_total_scheduled_orders_failed_value',
    'totalScheduledOrdersDeleted'       => 'autoship_total_scheduled_orders_deleted',
    'totalScheduledOrdersDeletedValue'  => 'autoship_total_scheduled_orders_deleted_value',
    'totalScheduledOrdersLifetimeValue' => 'autoship_total_scheduled_orders_lifetime_value',
    'averageProcessingCycleValue'       => 'autoship_average_processing_cycle_value',
    'lastSuccessfulProcessingCycleDate' => 'autoship_last_successful_processing_cycle_date',
    'lastFailedProcessingCycleDate'     => 'autoship_last_failed_processing_cycle_date',
    'lastPausedScheduledOrderDate'      => 'autoship_last_paused_scheduled_order_date',
    'totalSuccessfulProcessingCycles'   => 'autoship_total_successful_processing_cycles',
    'totalProductsPurchased'            => 'autoship_total_products_purchased',
    'firstProductsPurchased'            => 'autoship_first_products_purchased',
    'allProductsPurchased'              => 'autoship_all_products_purchased'
  ) );

  return !empty( $field ) ? $fields[$field] : $fields;

}

/**
* Allow Developers to Override the Get Function
**/
if ( !function_exists( 'autoship_get_customer_metrics_data' ) ){

  /**
   * Retrieves the Customer Metrics Data for a customer.
   *
   * @param int          $customer_id      WooCommerce customer ID or WC Customer Object
   * @param string|array $fields Optional. The field(s) to retrieve, if empty all fields.
   *
   * @return mixed|array The value(s) for the requested fields. If string supplied then return
   *                     value maybe single.
   */
  function autoship_get_customer_metrics_data( $customer_id, $fields = array() ){

    if ( empty( $fields ) )
    $fields = autoship_get_customer_metrics_fields();

    $metrics = array();
    if ( is_array( $fields ) ){

      $data = get_user_meta( $customer_id );

      foreach ( $fields as $field ) {
        $metrics[$field] = isset( $data[$field] ) ? $data[$field][0] : "";
      }

    } else {

      $metrics = get_user_meta( $customer_id, $fields, true );

    }

    return $metrics;

  }

}

/**
* Allow Developers to Override the Save Function
**/
if ( !function_exists( 'autoship_save_customer_metrics_data' ) ){

  /**
   * Saves the Customer Metrics Data for a customer.
   *
   * @param int $customer_id The Customer ID
   * @param array $metrics   The field(s) and data to save
   */
  function autoship_save_customer_metrics_data( $customer_id, $metrics, $translate = false ){

    if ( $translate ){

      foreach ( autoship_get_customer_metrics_fields() as $raw => $field) {

        if ( isset( $metrics->{$raw} ) )
        update_user_meta( $customer_id, $field, $metrics->{$raw} );

      }

    } else {
      foreach ( $metrics as $field => $value ){
        update_user_meta( $customer_id, $field, $value );
      }
    }

    $now = new DateTime( "now", new DateTimeZone( "UTC") );
    update_user_meta( $customer_id, 'autoship_customer_metrics_updated_date_utc', $now->format('Y-m-d H:i:s') );

  }

}


/**
 * Refresh the customer metrics for a User.
 *
 * @param int $user_id The customers id
 */
function autoship_refresh_customer_metrics_for_user( $user_id ){

  $results = autoship_available_customer_metrics( array(
    'page' => 1,
    'pageSize' => 1,
    'customerIds' => $user_id
   ) );

   if ( !is_wp_error( $results ) && !empty( $results->data ) ){

     foreach ( $results->data as $metrics_data )
     autoship_save_customer_metrics_data( $user_id, $metrics_data, true );

     do_action( 'autoship_customer_user_metrics_refreshed', $user_id, $results->data );

   }

}

/**
 * Get the Customer Metrics Data last update date for a customer.
 *
 * @param int $customer_id The Customer ID
 * @return string The last update date in UTC
 */
function autoship_get_customer_metrics_data_last_update_date( $customer_id ){
  return get_user_meta( $customer_id, 'autoship_customer_metrics_updated_date_utc', true );
}

/**
 * Includes the Customer Metrics Refresn & TimeStamp on the User Profile.
 *
 * @param WC_User $user The customer object
 */
function autoship_include_customer_metrics_on_profile( $user ){

  $date = autoship_get_customer_metrics_data_last_update_date( $user->ID );

  if ( !empty( $date ) ){
    $date = DateTime::createFromFormat( 'Y-m-d H:i:s', $date );
    $date->setTimezone( autoship_get_local_timezone() );
    $description = sprintf( __('Autoship Customer Metrics we\'re last downloaded for this user on <strong>%s</strong>.<br/><i>Check this option and click on the <strong>Update Profile</strong> button below to refresh the metrics.</i>', 'autoship'), $date->format( 'l F jS Y \a\t g:i A' ) );
  } else {
    $description = __('Autoship Customer Metrics do not exist or have not been downloaded for this user.<br/><i>Check this option and click on the <strong>Update Profile</strong> button below to refresh the metrics.</i>', 'autoship');
  }

  ?>

  <h3><?php echo __('Autoship Customer Metrics', 'autoship'); ?></h3>

  <table class="form-table">
    <tr>
      <td>
        <div id="autoship_refresh_customer_metrics_field">
          <?php
            woocommerce_form_field( 'autoship_refresh_customer_metrics_data', array(
              'type'            => 'checkbox',
              'required'        => false,
              'class'           => array('autoship-option'),
              'label'           => __('Refresh Autoship Customer Metrics', 'autoship')
            ), NULL );
          ?>
        <p><?php echo $description; ?></p>
        </div>
      </td>
    </tr>
  </table>

  <?php

}

/**
 * Processes the Option to refresh the customer metrics from the User Profile.
 *
 * @param int $user_id The customers id
 */
function autoship_process_customer_metrics_refresh_from_profile( $user_id ){

  // if the user doesn't have rights
  if ( !current_user_can( 'edit_user', $user_id ) )
  return false;

  // they do so we want to grab the value and if it changed process it
  $refresh = isset( $_POST['autoship_refresh_customer_metrics_data'] ) ?
  ( bool ) $_POST['autoship_refresh_customer_metrics_data'] : false;

  if ( $refresh )
  autoship_refresh_customer_metrics_for_user( $user_id );

}

add_action( 'edit_user_profile_update', 'autoship_process_customer_metrics_refresh_from_profile' );
add_action( 'personal_options_update', 'autoship_process_customer_metrics_refresh_from_profile' );
add_action( 'edit_user_profile', 'autoship_include_customer_metrics_on_profile' );
add_action( 'show_user_profile', 'autoship_include_customer_metrics_on_profile' );
