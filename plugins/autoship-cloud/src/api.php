<?php

use Automattic\WooCommerce\Utilities\OrderUtil;

if ( ! function_exists( 'qpilot_remote_post' ) ) {
  /**
   * Makes a http remote request
   * @uses wp_remote_post
   * @uses wp_remote_get
   *
   * @param string $endpoint The Endpoint URL
   * @param array $args Optional Arguments to include.
   *
   * @return array
   */
  function qpilot_remote_request( $endpoint, $args ){

    $response   = ( !isset( $args['method'] ) || 'GET' == $args['method'] ) ?
    wp_remote_get( $endpoint, $args ) : wp_remote_post( $endpoint, $args );

    if ( is_wp_error( $response ) ) {
    	return array( 'response' => array(
        'message' => $response->get_error_message(),
        'code' => (int) $response->get_error_code() ) );
    }

    return $response;
  }
}

/**
* Retrieves a new instance of the QPilot Client class
* using the current Autoship settings
* @param string $scope Optional. A scope override for the token
* @param string $source Optional. The owner of the Client ( 'Customer' or 'Merchant' )
* @return QPilotClient
*/
function autoship_get_default_client( $scope = '' ) {

  if ( empty( $scope ) )
  $scope = apply_filters( 'autoship_default_client_token_scope', 'merchant' );

	// Check token expiration & regenerate if needed.
	$token_created_at = autoship_get_token_created_at();
	$token_expires_in = autoship_get_token_expires_in();
	$one_week = 604800;
	if ( ( $token_created_at + $token_expires_in - $one_week ) < time() ) {
		try {
			// Generate a fresh access token
			autoship_refresh_token_auth();
		} catch (Exception $e) {
			// Ignore and move on
		}
	}

  $customer_id = get_current_user_id();

	// Create a new Client instance.
	$client = new QPilotClient();

  // Now we check for the token scope
  $admin = autoship_rights_checker( 'autoship_client_token_scope', array( 'edit_pages' ) );

  // Set source to Customer or Merchant based on caps
  if ( !$admin )
  $client->set_source( 'Customer' );

  // If not an admin but customer then let's set customer scope
  if ( !$admin && ( 'merchant' != $scope ) && $customer_id ){

    try {
      $token = $client->generate_customer_access_token( $customer_id, autoship_get_client_secret() );
      $client->set_token_auth( $token->tokenBearerAuth );
    } catch ( Exception $e ) {
      autoship_log_entry( __( 'Autoship Orders', 'autoship' ), sprintf( '%d Generate customer access token failed for customer %d. Additional Details: %s', $e->getCode(), $customer_id, $e->getMessage() ) );
    }

  }

	return $client;

}

/**
 * Refreshes the Oauth Token and Stored Token Data.
 */
function autoship_refresh_token_auth() {

	$client = new QPilotClient();
	$refresh_response = $client->refresh_oauth2();

	update_option( 'autoship_token_auth', $refresh_response->access_token );
	update_option( 'autoship_refresh_token', $refresh_response->refresh_token );
	update_option( 'autoship_token_expires_in', $refresh_response->expires_in );
	update_option( 'autoship_token_created_at', time() );
}


/**
 * Generates the token authorization for when loading the APP.
 */
function autoship_set_scheduled_orders_settings() {

	$site_customer_id = get_current_user_id();

  // Retireve the QPilot Customer or Create if Doesn't Exist
  $qpilot_customer = autoship_check_autoship_customer( $site_customer_id );

	if ( !is_wp_error( $qpilot_customer ) ) {

  	$client = autoship_get_default_client();

		try {
			$accessToken = $client->generate_customer_access_token( $qpilot_customer->id, autoship_get_client_secret() );
			$settings = apply_filters( 'autoship_scheduled_orders_settings', array(
				'customer_id' => $qpilot_customer->id,
				'bearer_token_auth' => $accessToken->tokenBearerAuth,
				'api_url' => autoship_get_api_url(),
				'site_customer_id' => $site_customer_id,
				'site_ajax_url' => admin_url( '/admin-ajax.php' ),
				'site_id' => autoship_get_site_id(),
				'site_currency' => get_woocommerce_currency()
			) );
			autoship_ajax_result( 200, $settings );

		} catch ( Exception $e ) {

      if ( 401 == $e->getCode() ) {
				try {
					// This token is expired
					//autoship_refresh_token_auth();
				} catch (Exception $e) {

				}
			} elseif ( 403 == $e->getCode() ) {
				// This token is invalid
				delete_user_meta( $site_customer_id, '_autoship_customer_id' );
			}
			autoship_ajax_result( 500, array( strval( $e->getCode() ) => $e->getMessage() ) );
		}
	}
	autoship_ajax_result( 500, 'No QPilot Customer ID' );
}
add_action( 'wp_ajax_autoship_get_scheduled_orders_settings', 'autoship_ajax_get_scheduled_orders_settings' );

// ==========================================================
// REST API Schedule Order Processing Support Functions
// ==========================================================

/**
 * Handle custom qpilot query vars to get orders with the _qpilot_scheduled_order_processing_id meta key.
 *
 * @param array $query - Args for WP_Query.
 * @param array $query_vars - Query vars from WC_Order_Query.
 * @return array modified $query
 */
function autoship_handle_meta_query_by_scheduled_order_processing_id( $query, $query_vars ) {

  if ( !empty( $query_vars['_qpilot_scheduled_order_processing_id'] ) ) {
    $query['meta_query'][] =
    array(
      'key' => '_qpilot_scheduled_order_processing_id',
      'value' => esc_attr( $query_vars['_qpilot_scheduled_order_processing_id'] ),
    );
  }

  return $query;
}

// ==========================================================
// REST API Sync Error Codes & Utility Functions
// ==========================================================

/**
 * Retrieves the list of default error code messages and descriptions.
 * Can be modified using {@see autoship_api_invalid_product_error_codes} filer.
 *
 * @return array of arrays. codes with corresponding message and description.
 */
function autoship_sync_invalid_product_codes (){

  $products_report_url  = autoship_admin_products_page_url();
  $error_codes = array(

    1 => array(
      'msg'   => __( 'Unsupported Product Type', "autoship" ),
      'desc'  => __( 'This product is assigned a type that is not currently supported by Autoship Cloud.') ),

    2 => array(
      'msg'   => __( 'Product Not Found', "autoship" ),
      'desc'  => sprintf( __( 'This <a href="%s">product exists in QPilot</a> but can not be found in WooCommerce.', "autoship" ), $products_report_url ) ),

    3 => array(
      'msg'   => __( 'Orphaned Product', "autoship" ),
      'desc'  => sprintf( __( 'This variation has a parent product that <a href="%s">exists in QPilot</a> but can not be found in WooCommerce.', "autoship" ), $products_report_url ) ),

    4 => array(
      'msg'   => __( 'Missing External Id', "autoship" ),
      'desc'  => sprintf( __( 'This product <a href="%s">exists in QPilot</a> but is missing an External ID that allows it to synchronize with WooCommerce.', "autoship" ), $products_report_url ) ),

    );

    $error_codes = apply_filters( 'autoship_sync_invalid_product_codes', $error_codes );

    return $error_codes;

}

/**
 * Retrieves the Message and Description associated with an error code.
 * Can be modified using the {@see autoship_expand_sync_invalid_product_code_mapping} filter.
 * @param int $code. The error code to look up.
 * @return array     An array of the message and description.
 */
function autoship_expand_sync_invalid_product_code ( $code, $key = '' ){

  $defaults = array(
    'msg'   => 'UnKnown Error',
    'desc'  => 'There was an unknown Autoship Cloud synchronization error with this product.'
  );
  $error_codes = autoship_sync_invalid_product_codes();
  $error_codes = apply_filters( 'autoship_expand_sync_invalid_product_code_mapping', $error_codes, $code );

  if ( isset( $error_codes[$code] ) ){
    return empty( $key ) ? $error_codes[$code] : $error_codes[$code][$key];
  }

  return empty( $key ) ? $defaults : $defaults[$key];

}

// ==========================================================
// REST API Data Retrieval Utility Functions
// ==========================================================

/**
 * The QPilot QPilot Merchant Center URL.
 * @return string The url.
 */
function autoship_get_merchants_url() {
	return apply_filters( 'autoship-merchants-url', 'https://merchants.qpilot.cloud' );
}

/**
 * The QPilot API URL.
 * @return string The url.
 */
function autoship_get_api_url() {
	return apply_filters( 'autoship-api-url', 'https://api.qpilot.cloud' );
}

/**
 * Retrieves the Site Connection Parameters.
 * @see autoship_get_client_id()
 * @return array containing the Client ID, Response Type, and Redirect URI.
 */
function autoship_get_site_parameters (){

  $site_parameters = array(
    'client_id'     => autoship_get_client_id(),
    'response_type' => 'code',
    'redirect_uri'  => admin_url( '/admin-ajax.php?action=autoship_oauth2' ),
    'scope'         => 'Merchant'
  );

  return $site_parameters;
}

/**
 * Retrieves the QPilot Autoship Site Meta for Upsert.
 *
 * @param array $sitemeta The current Site Meta data
 * @return array The updated data.
 */
function autoship_qpilot_get_sitemeta( $sitemeta = array() ) {

  if ( !is_array( $sitemeta ) )
  $sitemeta = array();

  global $woocommerce;

  $legacy      = autoship_has_legacy_origin();
  $frequencies = autoship_default_frequency_options();

  $defaults = array (
    "_qpilot_autoship_version"          => Autoship_Version,
    "_qpilot_wordpress_version"         => get_bloginfo( 'version' ),
    "_qpilot_woocommerce_version"       => $woocommerce->version,
    "_qpilot_wc_autoship_legacy"        => $legacy,
    "_qpilot_php_version"               => phpversion(),
    "_qpilot_extensions"                => autoship_get_custom_extensions(),
    "_qpilot_orders_display_option"     => autoship_get_scheduled_orders_display_version(),
    "_my_account_scheduled_orders_url"  => autoship_get_endpoint_url ( 'scheduled-orders', '', str_replace( home_url(), "", wc_get_page_permalink( 'myaccount' ) ) ),
    "allowedFrequencies"                => autoship_get_api_formatted_frequencies_data ( $frequencies ),
  );

  // Add any language overrides
  $language_customizations = array_filter( autoship_get_settings_fields ( array(
    'autoship_translation',
    'autoship_and_save_translation',
    'autoship_scheduled_order_translation',
    'autoship_scheduled_orders_translation'
  )) );

  $defaults['language'] = array();

  if ( !empty( $language_customizations ) ){

    if ( isset( $language_customizations['autoship_translation'] ) )
    $defaults['language']['autoship'] = $language_customizations['autoship_translation'];

    if ( isset( $language_customizations['autoship_and_save_translation'] ) )
    $defaults['language']['autoship and save'] = $language_customizations['autoship_and_save_translation'];

    if ( isset( $language_customizations['autoship_scheduled_order_translation'] ) )
    $defaults['language']['scheduled order'] = $language_customizations['autoship_scheduled_order_translation'];

    if ( isset( $language_customizations['autoship_scheduled_orders_translation'] ) )
    $defaults['language']['scheduled orders'] = $language_customizations['autoship_scheduled_orders_translation'];

    $defaults['language'] = apply_filters( 'autoship_sitemeta_language_definitions', $defaults['language'] );

  }

  return apply_filters( 'autoship_qpilot_sitemeta_upsert',
  array_merge( $sitemeta, $defaults ),
  $sitemeta,
  $defaults );

}

// ==========================================================
// REST API Endpoint Registration Functions
// ==========================================================

/**
 * Registeres a new REST API endpoint for QPilot
 * Used by QPilot to send Autoship a product to process.
 *
 * This endpoint accepts an integer which will be stored in an id parameter.
 *
 * ex. autoshipcloud/v1/product/12 the WP_REST_Request object will store
 * 12 in an id url parameter.
 */
function autoship_qpilot_availability_routes() {
    register_rest_route(
      'autoshipcloud/v1', '/product/(?P<id>\d+)', array(
        'methods' => WP_REST_Server::EDITABLE,
        'callback'=> 'autoship_qpilot_availability_update',
        'permission_callback' => 'autoship_qpilot_products_permissions'
        )
    );
}
add_action('rest_api_init', 'autoship_qpilot_availability_routes', 99);

/**
 * Registers new REST API endpoint for QPilot
 * Used for Order Creation and Duplication Check.  Endpoint must start with "wc-"
 * in order to use the WC Rest Permissions check
 *
 * Uses the {@see WP_REST_Server} class
 * READABLE   = 'GET';
 * CREATABLE  = 'POST';
 * EDITABLE   = 'POST, PUT, PATCH';
 * DELETABLE  = 'DELETE';
 * ALLMETHODS = 'GET, POST, PUT, PATCH, DELETE';
 */
function autoship_qpilot_orders_routes() {

    /**
    * @param string $namespace The first URL segment after core prefix. Should be unique to your package/plugin.
    * @param string $route     The base URL for route you are adding.
    * @param array  $args      Optional. Either an array of options for the endpoint, or an array of arrays for
    *                          multiple methods. Default empty array.
    */
    register_rest_route(
      'wc-autoshipcloud/v1', '/orders', array(
        'methods' => WP_REST_Server::CREATABLE,
        'callback'=> 'autoship_qpilot_orders_update',
        'permission_callback' => 'autoship_qpilot_orders_permissions'
        )
    );

}
add_action( 'rest_api_init', 'autoship_qpilot_orders_routes', 99 );

/**
 * Registeres new REST API endpoints for QPilot
 * Used for integration testing the PUT & POST calls.
 *
 * Uses the {@see WP_REST_Server} class
 * READABLE   = 'GET';
 * CREATABLE  = 'POST';
 * EDITABLE   = 'POST, PUT, PATCH';
 * DELETABLE  = 'DELETE';
 * ALLMETHODS = 'GET, POST, PUT, PATCH, DELETE';
 */
function autoship_qpilot_statuscheck_routes() {

    /**
    * @param string $namespace The first URL segment after core prefix. Should be unique to your package/plugin.
    * @param string $route     The base URL for route you are adding.
    * @param array  $args      Optional. Either an array of options for the endpoint, or an array of arrays for
    *                          multiple methods. Default empty array.
    */
    register_rest_route(
      'autoshipcloud/v1', '/statuscheck/put', array(
        'methods' => WP_REST_Server::EDITABLE,
        'callback'=> 'autoship_qpilot_statuscheck_put_update',
        'permission_callback' => 'autoship_qpilot_statuscheck_permission_check'
        )
    );
    register_rest_route(
      'autoshipcloud/v1', '/statuscheck/post', array(
        'methods' => WP_REST_Server::CREATABLE,
        'callback'=> 'autoship_qpilot_statuscheck_post_update',
        'permission_callback' => 'autoship_qpilot_statuscheck_permission_check'
        )
    );
    register_rest_route(
      'autoshipcloud/v1', '/statuscheck/get', array(
        'methods' => WP_REST_Server::READABLE,
        'callback'=> 'autoship_qpilot_statuscheck_get_update',
        'permission_callback' => 'autoship_qpilot_statuscheck_permission_check'
        )
    );

}
add_action('rest_api_init', 'autoship_qpilot_statuscheck_routes', 99);

// ==========================================================
// REST API Permission Callback Functions
// ==========================================================

/**
 * Checks permissions on the new Orders endpoint.
 * @see WC_REST_Orders_V2_Controller::create_item_permissions_check()
 *
 * @param  WP_REST_Request $request Request data.
 * @return bool|WP_Error
 */
function autoship_qpilot_orders_permissions( $request ) {

  // Confirm WC Rest is available
  if ( class_exists( 'WC_REST_Orders_Controller' ) ){

    // Get the rest controller and check permissions.
    $controller = new WC_REST_Orders_Controller;
    $reply = $controller -> create_item_permissions_check( $request );
    return $reply;

  }

  return new WP_Error( 'woocommerce_rest_unavailable', __( 'The WooCommerce REST API is either unavailable or not found.', 'autoship' ), array( 'status' => 500 ) );

}

/**
 * Permission Callback for the Product Availability endpoint
 * Checks if the integration can edit products
 *
 * @param WP_REST_Request $request The Request
 *
 * @return bool|WP_Error True if use has permissions else WP_Error
 */
function autoship_qpilot_products_permissions( $request ){
	return apply_filters( 'autoship_qpilot_products_permission_check', true, $request );
}

/**
 * Permission Callback for the health check endpoints
 *
 * @param WP_REST_Request $request The Request
 *
 * @return bool|WP_Error True if use has permissions else WP_Error
 */
function autoship_qpilot_statuscheck_permission_check( $request ){
	return apply_filters( 'autoship_qpilot_statuscheck_permission_check', true, $request );
}

// ==========================================================
// REST API Endpoint Callback Functions
// ==========================================================

/**
 * Creates an WC Order if it doesn't already exist,
 *
 * @param  WP_REST_Request $request Request data.
 * @return WP_Error|WP_REST_Response
 */
function autoship_qpilot_orders_update( $request ) {

  // Confirm WC Rest is available
  if ( class_exists( 'WC_REST_Orders_Controller' ) ){

    do_action( 'autoship_qpilot_orders_before_update_via_rest', $request );

    // Remove the legacy hook to prevent failure on existing orders if other plugins Save the order.
    remove_filter('woocommerce_rest_pre_insert_shop_order_object', 'autoship_woocommerce_rest_pre_insert_shop_order_object', 100 );

    $json = $request->get_json_params();
    $metas = isset( $json['meta_data'] ) && !empty( $json['meta_data'] ) ? $json['meta_data'] : array();

    $scheduled_order_processing_id = '';
    foreach ($metas as $meta ) {

      if ( '_qpilot_scheduled_order_processing_id' == $meta['key'] ){
        $scheduled_order_processing_id = $meta['value'];
        break;
      }

    }

    // If there is a Processing ID Check for Existing Orders
    if ( !empty( $scheduled_order_processing_id ) ) {
      
      // Get the orders from WC_orders.
      if( OrderUtil::custom_orders_table_usage_is_enabled() ) {
        $orders = wc_get_orders( 
          array(
            'meta_query' => array(
              array( 
                'key' => '_qpilot_scheduled_order_processing_id', 
                'value' => $scheduled_order_processing_id )
            ),
          )
         );
      } else {
        $orders = wc_get_orders( array( '_qpilot_scheduled_order_processing_id' => $scheduled_order_processing_id ) );
      }
      
      
      //the order exists, it's a duplicate
      if ( !empty( $orders ) ){
        
        // Get and Update the QPilot Site Meta
        $sitemeta = $orders[0]->get_meta( '_qpilot_site_meta' );

        // Update the Site Meta
        $sitemeta = autoship_qpilot_get_sitemeta( $sitemeta );

        // Add QPilot Autoship Version to Order Meta as well as any additional tracking.
        $orders[0]->add_meta_data( '_qpilot_site_meta', $sitemeta , true );

        return new WP_Error( 'duplicate_order', __( 'Order has already been created from scheduled order processing.', 'autoship' ),
        array( 'status' => 400,
               'order' => json_encode( array_values( $orders )[0]->get_data() ) )
        );

      }

    }

    // Allow others to hook in and modify QPilot data for new orders being created via the rest api.
    $request = apply_filters('autoship_qpilot_orders_via_rest', $request, $request );

    // Since this order doesn't exist create it using the WC Rest Controller.
    $controller = new WC_REST_Orders_Controller;
    $response = $controller -> create_item( $request );

    do_action( 'autoship_qpilot_orders_after_update_via_rest', $response, $request );

    return $response;

  } else {

    return new WP_Error( 'woocommerce_rest_unavailable', __( 'The WooCommerce REST API is either unavailable or not found.', 'autoship' ),
    array( 'status' => 500 )
    );

  }

}

/**
 * Updates the PUT status check with the current time.
 *
 * @param  WP_REST_Request $request Request data.
 * @return WP_REST_Response
 */
function autoship_qpilot_statuscheck_put_update( $request ) {

    // Get current UTC date time
    // Specified date/time in the specified time zone.
    $date = new DateTime();
    $date->setTimezone(new DateTimeZone('UTC'));
    $timestamp = $date->getTimestamp();

    // Attempt to update the integration status for PUT action.
    $result = autoship_update_integration_point_status ( 'put', $timestamp );

    if ( false === $result ) {

      // Create & return the response object
      $response = new WP_REST_Response( array( 'code' => 'autoship_put_failure', 'message' => 'Autoship PUT Update Failed. WordPress Update WP Options Record Failed.' ) );
      $response->set_status( 500 );
      return $response;

    } else {

      // Create & return the response object
      $response = new WP_REST_Response( array( 'code' => 'autoship_put_success', 'message' => 'Autoship PUT Update Success' ) );
      $response->set_status( 200 );
      return $response;

    }

}

/**
 * Updates the POST status check with the current time.
 *
 * @param  WP_REST_Request $request Request data.
 * @return WP_REST_Response
 */
function autoship_qpilot_statuscheck_post_update( $request ) {

    // Get current UTC date time
    // Specified date/time in the specified time zone.
    $date = new DateTime();
    $date->setTimezone(new DateTimeZone('UTC'));
    $timestamp = $date->getTimestamp();

    // Attempt to update the integration status for POST action.
    $result = autoship_update_integration_point_status ( 'post', $timestamp );

    if ( false === $result ) {

      // Create & return the response object
      $response = new WP_REST_Response( array( 'code' => 'autoship_post_failure', 'message' => 'Autoship POST Update Failed. WordPress Update WP Options Record Failed.' ) );
      $response->set_status( 500 );
      return $response;

    } else {

      // Create & return the response object
      $response = new WP_REST_Response( array( 'code' => 'autoship_post_success', 'message' => 'Autoship POST Update Success' ) );
      $response->set_status( 200 );
      return $response;

    }

}

/**
 * Updates the POST status check with the current time.
 *
 * @param  WP_REST_Request $request Request data.
 * @return WP_REST_Response
 */
function autoship_qpilot_statuscheck_get_update( $request ) {

    // Get current UTC date time
    // Specified date/time in the specified time zone.
    $date = new DateTime();
    $date->setTimezone(new DateTimeZone('UTC'));
    $timestamp = $date->getTimestamp();

    // Attempt to update the integration status for GET action.
    $result = autoship_update_integration_point_status ( 'get', $timestamp );

    if ( false === $result ) {

      // Create & return the response object
      $response = new WP_REST_Response( array( 'code' => 'autoship_get_failure', 'message' => 'Autoship GET Update Failed. WordPress Update WP Options Record Failed.' ) );
      $response->set_status( 500 );
      return $response;

    } else {

      // Create & return the response object
      $response = new WP_REST_Response( array( 'code' => 'autoship_get_success', 'message' => 'Autoship GET Update Success' ) );
      $response->set_status( 200 );
      return $response;

    }

}

/**
 * Updates the Availability for a product
 *
 * @param array     $data {
 *
 *     @type int     $id            The WC Product ID
 *     @type string  $availability  The availability string to enable or disable on the product.
 * }
 * @return WP_REST_Response
 */
function autoship_qpilot_availability_update( $data ) {

  if ( isset( $data['id'] ) && !empty( $data['id'] ) && isset( $data['availability'] ) ) {

      $wc_product = wc_get_product( $data['id'] );

      if ( !$wc_product ){

        // Create & return the response object
        $response = new WP_REST_Response( array( 'code' => 'autoship_availability_update_failure', 'message' => 'Autoship Update Availability Failed. The requested product could not be found or is no longer valid.' ) );
        $response->set_status( 500 );
        return $response;

      }

      $product_type     = $wc_product->get_type();
      $scheduled_order  = is_numeric(stripos($data['availability'],'AddToScheduledOrder'));
      $process_order    = is_numeric(stripos($data['availability'],'ProcessScheduledOrder'));

      if( $product_type == 'simple' || $product_type == 'variation' || $product_type == 'variable'){

        $updated = autoship_set_product_add_to_scheduled_order ( $wc_product->get_id(), $scheduled_order ? 'yes' : '' );
        $updated = autoship_set_product_process_on_scheduled_order ( $wc_product->get_id(), $process_order ? 'yes' : '' );

        $response = new WP_REST_Response( array( 'code' => 'autoship_availability_update_success', 'message' => 'The supplied product was successfully updated.' ) );
        $response->set_status( 200 );

      } else {

        $response = new WP_REST_Response( array( 'code' => 'autoship_availability_update_failed', 'message' => 'Autoship Update Availability Failed. The requested product was not a valid product type.' ) );
        $response->set_status( 500 );

      }

  }

}
