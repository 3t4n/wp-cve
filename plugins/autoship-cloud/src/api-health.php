<?php

/**
* Checks if the supplied Error Code is a User Message Code.
*
* @param int $code The HTTP Error Code
* @return bool True if it is else false.
*/
function autoship_is_user_http_message( $code ){
  return QPilotClient::$customer_http_code == $code;
}

/**
* Retrieves the list of default error code messages and descriptions.
* Can be modified using {@see autoship_api_error_codes} filer.
*
* @return array of arrays. codes with corresponding message and description.
*/
function autoship_http_codes (){

  $error_codes = array(

    301 => array(
      'msg'   => 'The requested resource has been moved permanently.',
      'desc'  => 'The web page or resource has been permanently replaced with a different resource. This code is used for permanent URL redirection.'),
    302 => array(
      'msg'   => 'The requested resource has moved, but was found.',
      'desc'  => 'The requested resource was found, just not at the location where it was expected. This code is used for temporary URL redirection.'),
    400 => array(
      'msg'   => 'Bad Request',
      'desc'  => 'The server cannot or will not process the request due to an apparent client error (e.g., malformed request syntax, size too large, invalid request message framing, or deceptive request routing).'),
    401 => array(
      'msg'   => 'Unauthorized.',
      'desc'  => 'This is returned by the server becuase authentication is required and has failed.' ),
    403 => array(
      'msg'   => 'Access to that resource is forbidden.',
      'desc'  => 'The request was valid, but the server is refusing action. The user might not have the necessary permissions for a resource, or may need an account of some sort.' ),
    404 => array(
      'msg'   => 'The requested resource was not found.',
      'desc'  => 'The requested resource could not be found but may be available in the future. Subsequent requests by the client are permissible.' ),
    405 => array(
      'msg'   => 'Method not allowed.',
      'desc'  => 'A request method is not supported for the requested resource; for example, a GET request on a form that requires data to be presented via POST, or a PUT request on a read-only resource.' ),
    406 => array(
      'msg'   => 'Not acceptable response.',
      'desc'  => 'The requested resource is capable of generating only content not acceptable according to the Accept headers sent in the request.' ),
    408 => array(
      'msg'   => 'The server timed out waiting for the rest of the request from the browser.',
      'desc'  => 'The server timed out waiting for the request. According to HTTP specifications: "The client did not produce a request within the time that the server was prepared to wait. The client MAY repeat the request without modifications at any later time."' ),
    410 => array(
      'msg'   => 'The requested resource is gone and won’t be coming back.',
      'desc'  => 'Indicates that the resource requested is no longer available and will not be available again.' ),
    429 => array(
      'msg'   => 'Too many requests.',
      'desc'  => 'The user has sent too many requests in a given amount of time.' ),
    499 => array(
      'msg'   => 'Client closed request.',
      'desc'  => 'This is returned by NGINX when the client closes the request while NGINX is still processing it.' ),
    500 => array(
      'msg'   => 'There was an error on the server and the request could not be completed.',
      'desc'  => 'A generic error was returned by your server, an unexpected condition was encountered.' ),
    501 => array(
      'msg'   => 'Not Implemented.',
      'desc'  => 'The server either does not recognize the request method, or it lacks the ability to fulfil the request.' ),
    503 => array(
      'msg'   => 'The server is unavailable to handle this request right now.',
      'desc'  => 'The server is currently unavailable (because it is overloaded or down for maintenance). Generally, this is a temporary state.' ),
    504 => array(
      'msg'   => 'The server, acting as a gateway, timed out waiting for another server to respond.',
      'desc'  => 'The server was acting as a gateway or proxy and did not receive a timely response from the upstream server' ),
    );

    $error_codes = apply_filters( 'autoship_api_error_codes', $error_codes );

    return $error_codes;

}

/**
* Retrieves the Message and Description associated with an error code.
* Can be modified using the {@see autoship_api_error_code_mapping} filter.
* @param int $code. The error code to look up.
* @return array     An array of the message and description.
*/
function autoship_expand_http_code ( $code, $key = '', $message = '' ){

  $defaults = array(
    'msg'   => 'UnKnown Error',
    'desc'  => 'There was an unknown error connecting to the server.'
  );
  $error_codes = autoship_http_codes();
  $error_codes = apply_filters( 'autoship_api_error_code_mapping', $error_codes, $code );

  // Check if this error is a User Facing Error and pass back with out converting.
  if ( !empty( $message ) && autoship_is_user_http_message( $code ) ){

    $general_error = array( 'msg' => 'User Message', 'desc' => $message );

    return empty( $key ) ? $general_error : $general_error[$key];

  } else if ( isset( $error_codes[$code] ) ){

    return empty( $key ) ? $error_codes[$code] : $error_codes[$code][$key];

  }

  return empty( $key ) ? $defaults : $defaults[$key];

}

/**
* Conditional Clearing of the Integration Status for when saving
* Any settings that effect The Health Status checks.
*
* @param mixed  $old_value The old option value.
* @param mixed  $_newvalue The new option value.
*/
function autoship_refresh_integration_status_on_save( $oldvalue, $_newvalue ){

  $effected_settings = array(
  'autoship_client_id',
  'autoship_client_secret',
  'autoship_site_id',
  'autoship_user_id',
  'autoship_token_auth',
  'autoship_refresh_token',
  );

  // If any of these values change clear the statuses so
  // they get tested again.
  if ( $oldvalue !== $_newvalue )
  autoship_clear_integration_point_statuses();

}
add_action( "update_option_autoship_client_id",     "autoship_refresh_integration_status_on_save", 10, 2 );
add_action( "update_option_autoship_client_secret", "autoship_refresh_integration_status_on_save", 10, 2 );
add_action( "update_option_autoship_site_id",       "autoship_refresh_integration_status_on_save", 10, 2 );
add_action( "update_option_autoship_user_id",       "autoship_refresh_integration_status_on_save", 10, 2 );
add_action( "update_option_autoship_token_auth",    "autoship_refresh_integration_status_on_save", 10, 2 );
add_action( "update_option_autoship_refresh_token", "autoship_refresh_integration_status_on_save", 10, 2 );

/**
* Retrieves a list of the integration status timestamp
* fields and corresponding REST actions.
* @return array The fields to actions.
*/
function autoship_integration_status_fields(){

  return array(
  'autoship_wc_get_checked_utc' => 'wc_get',
  'autoship_put_checked_utc'    => 'put',
  'autoship_post_checked_utc'   => 'post',
  );

}

/**
* Updates the integrations status flag
*
* @param bool|string   $health. True or 'healthy' if HEALTHY else UNHEALTHY
*                               Default 'healthy'
*/
function autoship_update_integration_health_status ( $health = 'healthy', $notice = '' ){

  $val = ( true === $health ) || ( 'healthy' == strtolower( $health ) ) ? 'healthy' : 'unhealthy';

  // Set the option value.
  update_option( "autoship_health", $val , false );
  update_option( "autoship_health_details", $notice , false );

  return $val;

}


/**
* Gets the integrations status flag
*
* @return bool False if UNHEALTHY or true if HEALTHY
*/
function autoship_get_integration_health_status (){

  // Delete the cache for this option first.
  wp_cache_delete("autoship_health", 'options' );

  // Get the option value.
  return get_option("autoship_health");

}


/**
* Retrieves the integrations status for the supplied type
*
* @param string $type.  The integration status to get
*                       Valid types are GET, POST, PUT
* @return string        The UTC Unix Timestamp or empty string.
*/
function autoship_get_integration_point_status ( $type ){

  $type = strtolower( $type );
  $option = "autoship_{$type}_checked_utc";

  // Delete the cache for this option first.
  wp_cache_delete($option, 'options' );

  // Now get the option
  $val = get_option($option);

  return empty( $val ) ? '' : $val;

}


/**
* Checks if the supplied integration status is fresh.
* Stale duration can be modified via {@see autoship_qpilot_integration_check_duration}
* filter which takes the current $type & default duration.
*
* @param string $type.  The integration status to check
*                       Valid types are GET, POST, PUT
* @return bool          True if the supplied integration has checked out ok.
*                       false if it's stale, doesn't exist or not valid.
*/
function autoship_check_integration_status_freshness (){

  $limit  = apply_filters('autoship_qpilot_integration_check_duration', 90 );
  $fields = autoship_integration_status_fields();

  foreach ($fields as $field => $action) {

    // Delete the cache for this option first.
    wp_cache_delete($field, 'options' );

    $val = get_option($field);

    // If the status does not exist or is empty
    // Not valid
    if ( ( false === $val ) || empty( $val ) )
    return false;

    // Get current UTC date time
    // Specified date/time in the specified time zone.
    $date = new DateTime();
    $date->setTimezone(new DateTimeZone('UTC'));
    $now = $date->getTimestamp();

    // Determine the diff of the two UNIX timestamps in minutes
    $dteDiff  = abs( $now - $val ) / 60;

    // Check if it's stale.
    if ( $dteDiff > $limit )
    return false;

  }

  return true;

}

/**
* Updates the integrations status for the supplied type
*
* @param string $type.      The integration status to update
*                           Valid types are GET, POST, PUT
* @param string $timestamp  The UTC Unix Timestamp to update it to.
*
* @return bool              True if option value has changed,
*                           false if not or if update failed.
*/
function autoship_update_integration_point_status ( $type, $timestamp ){

  // Set the option value.
  return update_option("autoship_{$type}_checked_utc", $timestamp, false );

}

/**
* Clears the values in the integrations status fields
*
* @param bool $delete_all. When set to True all integrations
*                          health status fields are deleted.
*                          Inlcudes the 'autoship_health' field.
*/
function autoship_clear_integration_point_statuses ( $delete_all = false ){

  $fields = autoship_integration_status_fields();

  if ( $delete_all ){

    $fields['autoship_health'] = 'true';
    foreach ( $fields as $field => $action ){
    delete_option($field);}

  } else {

    foreach ( $fields as $field => $action ){
    update_option($field, '', false );}

  }


}

/**
* Initiates the Autoship <> QPilot integration tests.
* Hits the QPilot test endpoint where QPilot then checks
* POST, GET, and PUT calls to WC and Autoship Endpoints.
* If successfull the corresponding status timestamps are populated.
*
*/
function autoship_init_integration_test(){

  // Clear the Status timestamps in prep for testing.
  autoship_clear_integration_point_statuses();

  // Create the QPilot client
  // Grab the client and only continue if connection exists.
  if ( empty( $client = autoship_get_default_client() ) || empty( $client->get_token_auth() ) || empty( $client->get_site_id() ))
  return false;

  try {

    /**
    * Initiate QPilot's integration tests.
    * When initiated QPilot uses the api autoshipcloud/v1/statuscheck/put
    * and autoshipcloud/v1/statuscheck/post as well as the WC REST API
    * to Check if the connection is fully valid.
    *
    */
    $result = $client->check_integration_status();

    // Run the Status checks to ensure all green lights
    $health = autoship_integration_statuses_check( $result );

  } catch ( Exception $e ) {

    // Run the Status checks to see where the failure occurred.
    $health = autoship_integration_statuses_check( NULL, true, $e->getCode(), $e->getMessage() );

  }

  do_action( 'autoship_init_integration_test_complete', $health );

  return $health;

}

/**
* Tests the QPilot connection with WC REST API
* Hooked into the Ajax action autoship_test_integration
* Uses {@see autoship_init_integration_test}
*/
function autoship_ajax_test_integration() {

	if ( ! current_user_can( 'manage_woocommerce' ) ) {
		autoship_ajax_result( 403 );
		die();
	}

  // Run the integration test.
  autoship_init_integration_test();

  wp_redirect( admin_url( 'admin.php?page=autoship' ) );
	die();

}
add_action( 'wp_ajax_autoship_test_integration', 'autoship_ajax_test_integration', 10, 0 );

/**
* Tests the QPilot connection with WC REST API
* Hooked into the Ajax action autoship_retest_invalid_products
*/
function autoship_ajax_retest_invalid_products_integration() {

	if ( ! current_user_can( 'manage_woocommerce' ) ) {
		autoship_ajax_result( 403 );
		die();
	}

  // Run the integration test.
  autoship_init_integration_test();

  wp_redirect( autoship_admin_products_page_url() );
	die();

}
add_action( 'wp_ajax_autoship_retest_invalid_products', 'autoship_ajax_retest_invalid_products_integration', 10, 0 );

/**
* Checks the Integration Status timestamps and adds notices to the Queue
* If the timestamps are empty. Status Timestamps are emptied on init testing.
*
* @param stdClass           The Result from the Status API Call
* @return bool              True if everything is healthy or false if not.
*/
function autoship_integration_statuses_check( $result = NULL , $log = true, $e_code = '520', $e_message = 'An Unknown Error was encountered.' ){

  /*
    QPilot Codes
    SiteDoesNotExist  = 1000,
    Create            = 1100,
    Get               = 1200,
    GetProducts       = 1210,
    Put               = 1300
  */

  // Since an exception occurred we need to check the
  // POST, GET, PUT Health stamps to see where it failed.
  // NOTE: Array is in order of QPilot Calls
  // autoship_get_integration_point_status returns true if healthy or false if sick.
  $checks = apply_filters('autoship_integration_status_check_messages', array(
    'post'=> array(
      'updated' => __( "POST: Orders can be created" , "autoship"),
      'error'   => __( "FAILED POST: Orders cannot be created" , "autoship"),
      'code'    => 1100
    ),
    'wc_get' => array(
      'updated' => __( "GET: Product Data can be synchronized" , "autoship"),
      'error'   => __( "FAILED GET: Product Data cannot be synchronized" , "autoship"),
      'code'    => 1210
    ),
    'put' => array(
      'updated' => __( "PUT: Data can be updated" , "autoship"),
      'error'   => __( "FAILED PUT: Data cannot be updated" , "autoship"),
      'code'    => 1300
    ),
  ) );

  // Check if QPilot ran into any issues.
  $qpilot_errors = array();
  if ( isset( $result ) && !$result->isSuccess && !empty( $result->errors ) ){
    foreach ($result->errors as $error) {
      $qpilot_errors[$error->type] = array(
        'code'        => $error->statusCode,
        'code_notice' => autoship_expand_http_code ( $error->statusCode, 'desc' ),
        'message'     => $error->message,
        'details'     => $error->detail
      );
    }
  }

  // Check if a WC Get Error occurred
  if (  !isset( $qpilot_errors[1210] ) ){

    // Get current UTC date time
    // Specified date/time in the specified time zone.
    $date = new DateTime();
    $date->setTimezone(new DateTimeZone('UTC'));
    $timestamp = $date->getTimestamp();

    // Attempt to update the integration status for WC_GET action.
    autoship_update_integration_point_status ( 'wc_get', $timestamp );

  }

  // Iterate through the statuses and add to the admin notices.
  $healthy   = true;
  $notices   = array();
  $notice_details = '';
  foreach ($checks as $action => $values ) {

    $timestamp = autoship_get_integration_point_status ( $action );
    if ( empty( $timestamp ) || isset( $qpilot_errors[$values['code']] ) ) {

      $msg = '<span class="autoship-health-msg">' . $checks[$action]['error'] . '</span>';

      // Check if message is included.
      if ( isset( $qpilot_errors[$values['code']] ) ){

        $e_code         = $qpilot_errors[$values['code']]['code'];
        $e_message      = empty( $qpilot_errors[$values['code']]['message'] ) ?
        $qpilot_errors[$values['code']]['code_notice'] : $qpilot_errors[$values['code']]['message'];
        $notice_details = $qpilot_errors[$values['code']]['details'];

      }

      // only attach the error to the first failed status.
      $msg .= $healthy ? "<br/><span class=\"autoship-health-error\"> Details: " . $e_code . " // " . $e_message . "</span>" : '';
      $notices[] = array(
        'type'=> 'exception',
        'msg' => $msg,
        'api' => $notice_details,
        'log' => true );
      $healthy = false;

    } else {

      $notices[] = array(
        'type' => 'notice',
        'msg' => '<span class="autoship-health-msg">' . $checks[$action]['updated']. '</span>',
        'api' => $notice_details,
        'log' => false
      );

    }

  }

  /**
  * IMPORTANT: All Notifications for the Autoship Health Checks are added to
  * the autoship_health_checks message queue so they need to be pulled from there.
  */

  // Add the overall health status
  $healthy = autoship_update_integration_health_status( $healthy, $notice_details );

  if ( $log ){

    // Iterate through the individual statuses and add notices.
    foreach ($notices as $values) {

      autoship_notice_handler( $values['type'], $values['msg'], false, 'autoship_health_checks' );

      if ( apply_filters( 'autoship_log_api_health_error_details', true, $values ) )
      autoship_log_entry( __( 'Autoship Health Check', 'autoship' ), sprintf( __( '%s Additional API Error Details: %s', 'autoship' ), $values['msg'], $values['api'] ) );

    }

  }


  return $healthy;

}

/**
* Adds a Health Check notification to the Autoship Menu Option.
* uses {@see autoship_get_integration_health_status()} to get
* the current health.
*
*/
function autoship_admin_health_status_menu_bubble() {
  global $menu;

  // If this is a new setup don't run any checks
  if ( autoship_is_new() || apply_filters( 'autoship_disable_admin_health_checks', false ) )
  return;

  $healthy = autoship_get_integration_health_status();

  // Check if connection is healthy & if the current stamps are fresh else
  // run the test integration.
  if ( !autoship_check_integration_status_freshness () || ( 'healthy' !== $healthy ) ){
    $healthy = autoship_init_integration_test();
  }

  // Allow Badge to be filtered.
  if ( ! apply_filters( 'autoship_show_admin_health_status_menu_bubble', 'healthy' !== $healthy ) )
  return;

  foreach ( $menu as $key => $value ) {

    if ( ( $menu[$key][2] == 'autoship' ) ){

      $menu[$key][0] .= '<span class="autoship-health"><span class="health-error">!</span></span>';
      return;

    }

  }

}
add_action( 'admin_menu', 'autoship_admin_health_status_menu_bubble' );

/**
* Shows a Status Bubble on the Autoship Cloud > Settings SubMenu Option when unhealthy exist.
* @param array $menu_options An array of the current Autosihip Menu Options.
*/
function autoship_admin_settings_submenu_health_status_menu_bubble( $menu_options ) {

  // Check if connection is unhealthy.
  $healthy = autoship_get_integration_health_status();

  // Add the Badge if to the menu title if it exists. Might now if customized.
  if ( 'healthy' !== $healthy )
  $menu_options['autoship']['menu_title']  .= '<span class="autoship-health"><span class="health-error">!</span></span>';

  return $menu_options;

}
add_filter( 'autoship_admin_settings_submenu_pages', 'autoship_admin_settings_submenu_health_status_menu_bubble', 10, 1 );

/**
* Filter any User Messages returned by the QPilot Client Request
* @param array $messages The messages to filter
* @param array $args Additional Call Details
*/
function autoship_filter_qpilot_client_response_usermessages( $messages, $args ) {

  $filtered_notices = array();

  // Allow Merchants to filter these via language files
  foreach ( $messages as $message )
  $filtered_notices[] = __( autoship_search_for_translate_text( $message ), 'autoship' );

  return $filtered_notices;

}
add_filter( 'qpilot_remote_request_response_messages', 'autoship_filter_qpilot_client_response_usermessages', 10, 2 );

/**
* Logs any technical errors returned by the QPilot Client Request
* @param array $args
*/
function autoship_log_qpilot_client_response_errors( $args ) {
  autoship_log_entry(
    sprintf( '%d %s Request Error: ', $args['code'], $args['callArgs']['method'], $args['response_error'] ),
    sprintf( 'Error Details: %s // Endpoint Details: %s', implode( ' ', $args['errors'] ), $args['callArgs']['endpoint'] ) );
}
add_action( 'qpilot_remote_request_response_errors', 'autoship_log_qpilot_client_response_errors', 10, 1 );
