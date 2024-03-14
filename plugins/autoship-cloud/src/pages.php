<?php

/**
 * Adds the scheduled orders menu item.
 *
 * @param array $items The current My Account menu items.
 * @return array.
 */
function autoship_account_menu_items( $items ) {
    $autoship_items = array( 'scheduled-orders' => autoship_translate_text( 'Scheduled Orders', true ) );
    $item_count = count( $items );
    $front = array_slice( $items, 0, $item_count - 1, true );
    $back = array_slice( $items, $item_count - 1, 1, true );
    $items = array_merge( $front, $autoship_items, $back );
    return $items;
}

/**
 * Sets the Page Title for the Scheduled Orders My Account endpoint.
 * @param string $title the current title.
 * @return string the new title.
 */
function autoship_account_autoship_scheduled_orders_title( $title ) {
  global $wp_query;
  // Only make the update if we're on the scheduled orders endpoint, in the loop and the main query.
  if ( ! is_null( $wp_query ) && isset( $wp_query->query_vars[ 'scheduled-orders' ] ) && ! is_admin() && is_main_query() && in_the_loop() && is_page() ) {
    $title  = autoship_translate_text( 'Scheduled Orders', true );
    // Remove the filter after it's been applied so that it doesn't continue to be applied.
    remove_filter( 'the_title', 'autoship_account_autoship_scheduled_orders_title' );
  }
  return $title;
}

/**
 * Removes the Scheduled Orders endpoint title filter before any Navs get created.
 * @see wp_nav_menu()
 *
 * @param string|null $output Nav menu output to short-circuit with.
 * @param stdClass    $args   An object containing wp_nav_menu() arguments.
 *
 * @return string|null Nav menu output
 */
function autoship_scheduled_orders_remove_title_filter_nav_menu( $nav_menu, $args ) {
    // we are working with menu, so remove the title filter
    remove_filter( 'the_title', 'autoship_account_autoship_scheduled_orders_title', 10, 2 );
    return $nav_menu;
}

/**
 * After the Nav has been created adds the scheduled Orders endpoint title filter
 *
 * @see wp_nav_menu()
 *
 * @param string   $items The HTML list content for the menu items.
 * @param stdClass $args  An object containing wp_nav_menu() arguments.
 *
 * @return string  The HTML list content for the menu items.
 */
function autoship_scheduled_orders_add_title_filter_non_menu( $items, $args ) {
    // we are done working with menu, so add the title filter back
    add_filter( 'the_title', 'autoship_account_autoship_scheduled_orders_title', 10, 2 );
    return $items;
}

/**
 * Sets the Page Title for the Scheduled Order ( Singular ) My Account endpoint.
 * @param string $title the current title.
 * @return string the new title.
 */
function autoship_account_autoship_scheduled_order_title( $title ) {
  global $wp_query;
  // Only make the update if we're on the scheduled orders endpoint, in the loop and the main query.
  if ( ! is_null( $wp_query ) && isset( $wp_query->query_vars[ 'scheduled-order' ] ) && ! is_admin() && is_main_query() && in_the_loop() && is_page() ) {
    $title  = autoship_translate_text( 'Scheduled Order', true );
    // Remove the filter after it's been applied so that it doesn't continue to be applied.
    remove_filter( 'the_title', 'autoship_account_autoship_scheduled_order_title' );
  }
  return $title;
}

/**
 * Removes the Scheduled Order ( Singular ) endpoint title filter before any Navs get created.
 * @see wp_nav_menu()
 *
 * @param string|null $output Nav menu output to short-circuit with.
 * @param stdClass    $args   An object containing wp_nav_menu() arguments.
 *
 * @return string|null Nav menu output
 */
function autoship_scheduled_order_remove_title_filter_nav_menu( $nav_menu, $args ) {
    // we are working with menu, so remove the title filter
    remove_filter( 'the_title', 'autoship_account_autoship_scheduled_order_title', 10, 2 );
    return $nav_menu;
}

/**
 * After the Nav has been created adds the scheduled Order ( Singular ) endpoint title filter
 *
 * @see wp_nav_menu()
 *
 * @param string   $items The HTML list content for the menu items.
 * @param stdClass $args  An object containing wp_nav_menu() arguments.
 *
 * @return string  The HTML list content for the menu items.
 */
function autoship_scheduled_order_add_title_filter_non_menu( $items, $args ) {
    // we are done working with menu, so add the title filter back
    add_filter( 'the_title', 'autoship_account_autoship_scheduled_order_title', 10, 2 );
    return $items;
}

/* Endpoint Handlers
====================================== */

/**
 * Flushes the Rewrite rules if needed.
 */
function autoship_maybe_flush_rewriterules(){

  if ( get_option( 'autoship_flush_rewrite_rules_flag' ) ) {
      flush_rewrite_rules();
      delete_option( 'autoship_flush_rewrite_rules_flag' );
  }

}

/**
 * Sets Up the autoship scheduled-orders My Account endpoint.
 * This endpoint allows for admins to see the scheduled orders page for any customer.
 * Rights can be filtered via {@see autoship_customer_accounts_scheduled_orders_rights}
 */
function autoship_account_autoship_scheduled_orders_endpoint( $value ) {

    $customer_id = absint( $value );

    if ( $customer_id ){
      $user = get_userdata( $customer_id );
      if ( $user === false )
      $customer_id = 0;
    }

    echo $customer_id ? do_shortcode( '[autoship-scheduled-orders customer_id="'. $customer_id .'"]' ) : do_shortcode( '[autoship-scheduled-orders]' );
}

/**
 * Sets Up the autoship view/edit-scheduled-order My Account > Scheduled Orders > Order 123 endpoint.
 */
function autoship_account_autoship_scheduled_order_endpoint( $value ) {

    $scheduled_order_id = absint( $value );

    if ( $scheduled_order_id )
    echo do_shortcode( '[autoship-scheduled-order autoship_order_id="'. $scheduled_order_id .'"]' );

}

/**
 * Sets Up the autoship view-scheduled-order My Account > View Scheduled Orders > Order 123 endpoint.
 */
function autoship_account_autoship_view_scheduled_order_endpoint( $value ) {

    $scheduled_order_id = absint( $value );

    if ( $scheduled_order_id )
    echo do_shortcode( '[autoship-view-scheduled-order autoship_order_id="'. $scheduled_order_id .'"]' );

}

/**
 * Sets Up the autoship Add to Scheduled Orders endpoint redirect.
 * @param string $redirect The url redirect. Optional. Default null
 * @param WP_User $user The user who logged in.
 * @return string|NULL The redirect string or null.
 */
function autoship_add_to_scheduled_order_endpoint_redirect( $redirect = NULL, $user = NULL  ){

  global $wp_query;

  // Check for woocommerce endpoint, user logged in and addtoscheduled order var.
  if ( ! apply_filters( 'autoship_add_to_scheduled_order_endpoint_redirect_validation', isset( $wp_query->query_vars['scheduled-orders'] ) && is_account_page() && isset( $_GET['action'] ) && !empty( $_GET['action'] ) ) ){

    return $redirect;

  // If the user isn't logged in let the existing controls deal with it.
  } else if ( ! is_user_logged_in() ) {

    return $redirect;

  // Ahhh it's ours - fire away.
  } else if ( apply_filters( 'autoship_is_add_to_scheduled_order_endpoint_redirect_action', true )){

    do_action( 'autoship_pre_add_to_scheduled_order_link_handler' );

    $success = autoship_add_to_scheduled_order_endpoint_wrapper( $_GET['action'] );

    do_action( 'autoship_post_add_to_scheduled_order_link_handler', $success );

    wp_redirect( autoship_get_scheduled_orders_url() );
		exit();

  }

}

/**
 * Sets Up the autoship Create to Scheduled Orders endpoint redirect.
 * @param bool True
 * @return bool True if the current action is for the add to scheduled order else false.
 */
function autoship_add_to_scheduled_order_endpoint_redirect_is_valid_action( $redirect ){
  return isset( $_GET['action'] ) && !( 'create-scheduled-order' == $_GET['action'] );
}

/**
 * Sets Up the autoship Create to Scheduled Orders endpoint redirect.
 * @param string $redirect The url redirect. Optional. Default null
 * @param WP_User $user The user who logged in.
 * @return string|NULL The redirect string or null.
 */
function autoship_create_scheduled_order_endpoint_redirect( $redirect = NULL, $user = NULL  ){

  global $wp_query;

  // Check for woocommerce endpoint, user logged in and createscheduledorder order var.
  if ( ! apply_filters( 'autoship_create_scheduled_order_endpoint_redirect_validation', isset( $wp_query->query_vars['scheduled-orders'] ) && is_account_page() && isset( $_GET['action'] ) && !empty( $_GET['action'] ) ) ){

    return $redirect;

  // If the user isn't logged in let the existing controls deal with it.
  } else if ( ! is_user_logged_in() ) {

    return $redirect;

  // Ahhh it's ours - fire away.
  } else if ( apply_filters( 'autoship_is_create_scheduled_order_endpoint_redirect_action', true )){

    do_action( 'autoship_pre_create_scheduled_order_link_handler' );

    $success = autoship_create_scheduled_order_endpoint_wrapper( $_GET['action'] );

    do_action( 'autoship_post_create_scheduled_order_link_handler', $success );

    wp_redirect( autoship_get_scheduled_orders_url() );
		exit();

  }


}

/* Endpoint Setups
====================================== */

/**
 * Returns the valid Endpoints for Autoship
 * Can be modified via {@see autoship_valid_endpoints} filer.
 * Uses {@see autoship_valid_query_vars}
 *
 * @return array The valid Endpoints.
 */
function autoship_valid_endpoints(){

  $endpoints = autoship_valid_query_vars();

  return apply_filters( 'autoship_valid_endpoints', $endpoints );

}

/**
 * Add Autoship Endpoints
 * Uses {@see autoship_valid_endpoints}
 * Endpoint Masks can be filtered via 'autoship_account_add_endpoint_masks'
 */
function autoship_account_add_endpoints() {

    $endpoints = autoship_valid_endpoints();

    foreach ($endpoints as $key => $endpoint ) {

      add_rewrite_endpoint( $endpoint, apply_filters(
        'autoship_account_add_endpoint_masks',
        EP_ROOT | EP_PAGES,
        $key,
        $endpoint
      ));

    }

    // Maybe flush the rewrite rules
    autoship_maybe_flush_rewriterules();

}

/**
 * Returns the valid Query Vars for Autoship
 * Can be modified via {@see autoship_valid_query_vars} filer.
 * Individual query_vars can be modified via filters.
 *
 * @return array The valid Query Vars.
 */
function autoship_valid_query_vars(){

  $vars = array(
			'scheduled-orders'                      => apply_filters( 'autoship_view_scheduled_orders_endpoint', 'scheduled-orders' ),
      'scheduled-order'                       => apply_filters( 'autoship_edit_scheduled_order_endpoint', 'scheduled-order' ),
      'view-scheduled-order'                  => apply_filters( 'autoship_view_scheduled_order_endpoint', 'view-scheduled-order' ),
      'update-scheduled-orders-customer-info' => apply_filters( 'autoship_update_scheduled_orders_customer_info_endpoint', 'update-scheduled-orders-customer-info' ),
      'update-scheduled-orders-payment-method'=> apply_filters( 'autoship_update_scheduled_orders_payment_method_endpoint', 'update-scheduled-orders-payment-method' ),
      'delete-scheduled-order-confirm'        => apply_filters( 'autoship_delete_scheduled_order_endpoint', 'delete-scheduled-order-confirm' ),
      'update-scheduled-order-status'         => apply_filters( 'autoship_update_scheduled_order_status_endpoint', 'update-scheduled-order-status' ),
      'remove-scheduled-order-item'           => apply_filters( 'autoship_remove_scheduled_order_item_endpoint', 'remove-scheduled-order-item' ),
      'remove-scheduled-order-coupon'         => apply_filters( 'autoship_remove_scheduled_order_coupon_endpoint', 'remove-scheduled-order-coupon' ),
      'refresh-scheduled-order'               => apply_filters( 'autoship_refresh_scheduled_order_data_endpoint', 'refresh-scheduled-order' ),
      //'add-to-scheduled-order'                => apply_filters( 'autoship_remove_scheduled_order_coupon_endpoint', 'add-to-scheduled-order' )
  );

  return apply_filters( 'autoship_valid_query_vars', $vars );

}

/**
 * Add Autoship Query Variables
 * Uses {@see autoship_valid_query_vars}
 */
function autoship_account_add_query_vars( $vars ) {

  $autoship_vars = autoship_valid_query_vars();
	foreach ( $autoship_vars as $key => $var ) {
		$vars[] = $key;
	}
	return $vars;

}

/**
 * Add Autoship Non-Endpoint Specific Query Variables
 */
function autoship_non_endpoint_query_vars( $vars ) {

  $autoship_vars = apply_filters( 'autoship_non_endpoint_query_vars', array( 'autoship_method_id', 'autoship_method_type' ) );
	foreach ( $autoship_vars as $key ) {
		$vars[] = $key;
	}
	return $vars;

}

/**
 * Get endpoint URL.
 *
 * Gets the URL for an endpoint, which varies depending on permalink settings.
 *
 * @param  string $endpoint  Endpoint slug.
 * @param  string $value     Query param value.
 * @param  string $permalink Permalink.
 *
 * @return string
 */
function autoship_get_endpoint_url( $endpoint, $value = '', $permalink = '' ) {

    if ( ! $permalink ) {
        $permalink = get_permalink();
    }

    // Map endpoint to options.
    $query_vars = autoship_valid_query_vars();
    $endpoint   = ! empty( $query_vars[ $endpoint ] ) ? $query_vars[ $endpoint ] : $endpoint;

    if ( get_option( 'permalink_structure' ) ) {
        if ( strstr( $permalink, '?' ) ) {
            $query_string = '?' . wp_parse_url( $permalink, PHP_URL_QUERY );
            $permalink    = current( explode( '?', $permalink ) );
        } else {
            $query_string = '';
        }
        $url = trailingslashit( $permalink ) . $endpoint . '/' . $value . $query_string;
    } else {
        $url = add_query_arg( $endpoint, $value, $permalink );
    }

    return apply_filters( 'autoship_get_endpoint_url', $url, $endpoint, $value, $permalink );
}

/* Utilities
====================================== */

/**
 * Returns true when on the Schedule Order page.
 * Uses WC {@see  wc_get_page_id}
 *
 * @return bool
 */
function is_scheduled_order_page() {
  global $wp;

  $page_id = wc_get_page_id( 'myaccount' );
  return ( $page_id && is_page( $page_id ) && isset( $wp->query_vars['scheduled-order'] ) );
}

/**
 * Returns the Relative Uri based on the supplied absolute.
 * @param string $absolute_uri The absolute URI
 * @return string The Relative URI if found.
 */
function autoship_get_relative_uri( $absolute_uri ) {
	try {
		$protocol_position = strpos( $absolute_uri, '//' );
		if ( $protocol_position === false || $protocol_position < 0 ) {
			// This uri is badly formatted, so return the default
			return $absolute_uri;
		}
		$relative_position = strpos( $absolute_uri, '/', $protocol_position + 2 );
		if ( $relative_position === false || $relative_position < 0 ) {
			// This uri is badly formatted, so return the default
			return $absolute_uri;
		}
		// Return the relative uri
		return substr( $absolute_uri, $relative_position );
	} catch ( Exception $e ) {
		// Something is wrong, so return the default
		return $absolute_uri;
	}
}

// ==========================================================
// DEFAULT HOOKED ACTIONS & FILTERS
// ==========================================================

/**
 * Adds the Scheduled Orders menu item(s) to My Account
 *
 * @see autoship_account_menu_items()
 */
add_filter( 'woocommerce_account_menu_items', 'autoship_account_menu_items' );

/**
 * Adjusts the Page Titles for the Scheduled Orders and Scheduled Order pages.
 *
 * @see autoship_account_autoship_scheduled_orders_title()
 * @see autoship_account_autoship_scheduled_order_title()
 */
add_filter( 'the_title', 'autoship_account_autoship_scheduled_orders_title' );
add_filter( 'the_title', 'autoship_account_autoship_scheduled_order_title' );

/**
 * Prevent WP from changing the Nav menu title in addition to the page title
 * for My Account > Scheduled Orders and My Account > Scheduled Order
 * NOTE this filter fires just before the nav menu item creation process
 *
 * @see autoship_scheduled_orders_remove_title_filter_nav_menu()
 * @see autoship_scheduled_order_remove_title_filter_nav_menu()
 */
add_filter( 'pre_wp_nav_menu', 'autoship_scheduled_orders_remove_title_filter_nav_menu', 10, 2 );
add_filter( 'pre_wp_nav_menu', 'autoship_scheduled_order_remove_title_filter_nav_menu', 10, 2 );

/**
 * Add the My Account > Scheduled Orders title back after menu is created
 * NOTE this filter fires after nav menu item creation is done
 *
 * @see autoship_scheduled_orders_add_title_filter_non_menu()
 */
add_filter( 'wp_nav_menu_items', 'autoship_scheduled_orders_add_title_filter_non_menu', 10, 2 );

/**
 * Adds the Callback for the Scheduled Orders Endpoint
 *
 * @see autoship_account_autoship_scheduled_orders_endpoint()
 */
add_action( 'woocommerce_account_scheduled-orders_endpoint', 'autoship_account_autoship_scheduled_orders_endpoint' );

/**
 * Adds the Callback for the Scheduled Order Endpoint
 *
 * @see autoship_account_autoship_scheduled_order_endpoint()
 */
add_action( 'woocommerce_account_scheduled-order_endpoint', 'autoship_account_autoship_scheduled_order_endpoint' );

/**
 * Adds the Callback for the View Scheduled Order Endpoint
 *
 * @see autoship_account_autoship_view_scheduled_order_endpoint()
 */
add_action( 'woocommerce_account_view-scheduled-order_endpoint', 'autoship_account_autoship_view_scheduled_order_endpoint' );

/**
 * Handles Needed Redirects for the Scheduled Order(s) endpoints
 * NOTE Specifically for the Add to and Create URL methods.
 *
 * @see autoship_add_to_scheduled_order_endpoint_redirect()
 * @see autoship_create_scheduled_order_endpoint_redirect()
 */
add_action( 'template_redirect', 'autoship_add_to_scheduled_order_endpoint_redirect' );
add_action( 'template_redirect', 'autoship_create_scheduled_order_endpoint_redirect' );

/**
 * Handles Login Redirects for the Scheduled Order(s) endpoints
 * NOTE Specifically for the Add to and Create URL methods.
 *
 * @see autoship_add_to_scheduled_order_endpoint_redirect()
 * @see autoship_create_scheduled_order_endpoint_redirect()
 */
add_filter( 'woocommerce_login_redirect', 'autoship_add_to_scheduled_order_endpoint_redirect', 10, 2 );
add_filter( 'woocommerce_login_redirect', 'autoship_create_scheduled_order_endpoint_redirect', 10, 2 );

/**
 * Handles the check for if the url method action is valid for
 * the Add to Scheduled Order endpoint
 *
 * @see autoship_add_to_scheduled_order_endpoint_redirect_is_valid_action()
 */
add_filter( 'autoship_is_add_to_scheduled_order_endpoint_redirect_action', 'autoship_add_to_scheduled_order_endpoint_redirect_is_valid_action', 10, 1 );

/**
 * Adds the My Account Scheduled Order(s) endpoints
 *
 * @see autoship_account_add_endpoints()
 */
add_action( 'init', 'autoship_account_add_endpoints' );

/**
 * Adds the Autoship Endpoint Query variables and non-endpoint
 * Autoship Query variables.
 *
 * @see autoship_account_add_query_vars()
 * @see autoship_non_endpoint_query_vars()
 */
add_filter( 'query_vars', 'autoship_account_add_query_vars' );
add_filter( 'query_vars', 'autoship_non_endpoint_query_vars' );
