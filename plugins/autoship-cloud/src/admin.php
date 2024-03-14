<?php

// ==========================================================
// Utility Functions
// ==========================================================

/**
 * Checks the rights based on the supplied roles/caps.
 * @param string The rights filter name to apply.
 * @param array}  Optional. An array of roles or caps to check.
 *                         If left empty then defaults to true unless filtered.
 * @return bool True if they have rights, otherwise false.
 */
function autoship_rights_checker( $filter = '', $caps = array(), $user_id = 0 ){

  // Get the god role or cap
  $global_editor_role = apply_filters( 'autoship_global_editor_role_cap', array( 'administrator' ), $filter );

  // If there is a specific user check
  // only fail if the current user does not have a god role
  // and their ID doesn't match the supplied id.
  if ( $user_id && !autoship_rights_checker( $filter, $global_editor_role ) && ( get_current_user_id() != $user_id ) )
  return false;

  $valid = false;
  $caps_or_roles = !empty( $filter ) ? apply_filters( $filter , $caps ) : $caps;

  foreach ($caps_or_roles as $cap) {

    if ( current_user_can( $cap ) ){
      $valid = true;
      break;
    }

  }

  return empty( $caps_or_roles ) ? true : $valid;

}

/**
 * Outputs the Modal HTML
 * @param string $modal_id The modal id
 * @param string $content The html content for the modal.
 * @param string $classes Additional classes
 * @param string $footer_content The html content for the modal footer.
 */
function autoship_generate_modal( $modal_id, $content, $classes = '', $footer_content = '', $close = true ){

  ?>

  <!-- Autoship <?php echo $modal_id;?> Modal -->
  <div id="<?php echo $modal_id;?>" class="autoship-modal autoship-<?php echo $modal_id;?>-modal <?php echo $classes;?>">

    <!-- Autoship Modal content -->
    <div class="autoship-modal-content">

      <?php if ( !empty( $close ) ): ?>

      <span class="close">&times;</span>

      <?php endif; ?>

      <div class="autoship-modal-inner-content">
        <?php echo $content;?>
      </div>

      <?php if ( !empty( $footer_content ) ): ?>

      <div class="autoship-modal-footer-content">

        <?php echo $footer_content;?>

      </div>

      <?php endif; ?>

    </div>

  </div>

  <?php

}

/**
 * Retrieves the Start and End Times for the Sites Processing Window
 *
 * @return array|bool An array containing the start and end times else false
 *                    if Processing Window is not supported
 */
function autoship_get_api_processing_window(){
  $settings = autoship_get_remote_saved_site_settings( true );
  return !is_wp_error( $settings ) && isset( $settings['supportsProcessingWindow'] ) && $settings['supportsProcessingWindow'] ? array(
    'startTime' => empty( $settings['orderProcessingStartTime'] ) ? NULL : $settings['orderProcessingStartTime'],
    'endTime'   => empty( $settings['orderProcessingEndTime'] ) ? NULL : $settings['orderProcessingEndTime']
  ) : false;
}

// ==========================================================
// Page URL Functions
// ==========================================================

/**
 * Returns the url for the Admin Autoship Cloud > Settings tab
 * @return string The url.
 */
function autoship_admin_settings_page_url(){
  return admin_url( '/admin.php?page=autoship' );
}

/**
 * Returns the url for the Admin Autoship Cloud > Products tab
 * @return string The url.
 */
function autoship_admin_products_page_url(){
  return admin_url( '/admin.php?page=products' );
}

/**
* Returns the url for an Admin Autoship Cloud > Settings tab
* @param $tab The tab name.
* @return string The url.
*/
function autoship_admin_settings_tab_url( $tab ){
  return add_query_arg( 'tab', $tab, admin_url( '/admin.php?page=autoship' ) );
}

// ==========================================================
// Options Retrieval Functions
// ==========================================================

/**
 * Quick check to see if this is a new setup
 * Checks to see if autoship_client_id, autoship_client_secret
 * or autoship_token_auth are empty.
 *
 * @return bool Ture if new or false if not.
 */
function autoship_is_new(){

  return (
    empty( autoship_get_settings_fields( 'autoship_client_id', true ) )     ||
    empty( autoship_get_settings_fields( 'autoship_client_secret', true ) ) ||
    empty( autoship_get_settings_fields( 'autoship_token_auth', true ) )
  );

}

/**
 * Quick check to see if credentials exist
 * Checks to see if autoship_client_id or autoship_client_secret
 * are empty.
 *
 * @return bool Ture if new or false if not.
 */
function autoship_has_credentials(){

  return !(
    empty( autoship_get_settings_fields( 'autoship_client_id', true ) )     ||
    empty( autoship_get_settings_fields( 'autoship_client_secret', true ) )
  );

}

/**
 * Quick check to see if auth token exists
 * Checks to see if autoship_token_auth is empty.
 *
 * @return bool Ture if new or false if not.
 */
function autoship_has_auth_token(){

  return !empty( autoship_get_settings_fields( 'autoship_token_auth', true ) );

}

/**
 * Quick check to see if the legacy fee lines are enabled.
 *
 * @return bool Ture if Fee lines are enabled.
 */
function autoship_rest_order_fee_lines_enabled(){
  return "yes" == autoship_get_settings_fields( 'autoship_rest_order_fee_lines_enabled', true );
}

/**
 * Quick check to see if the Display Next Occurrence Offset is enabled.
 *
 * @return bool Ture if enabled.
 */
function autoship_display_next_occurrence_offset_enabled(){
  return "yes" == autoship_get_settings_fields( 'autoship_display_next_occurrence_offset', true );
}

// ==========================================================
// Data Retrieval Functions
// ==========================================================

/**
 * Retrieves the site/blog info.
 * @param string $attribute Optional. The specific value to retrieve.
 * @return string|array An array when no value is supplied.
 */
function autoship_get_site_info( $attribute = NULL ){

  $site_info = array(
    'url'     => get_bloginfo( 'url' ),
    'type'    => 'WooCommerce',
    'name'    => get_bloginfo( 'title' ),
    'email'   => get_bloginfo( 'admin_email' )
  );

  return isset( $attribute ) ? $site_info[$attribute] : $site_info;

}

/**
 * Retrieves the Autoship Oauth Redirect Page for the site.
 * @return string.
 */
function autoship_get_redirect_uri() {
	return admin_url( '/admin-ajax.php?action=autoship_oauth2' );
}

/**
 * Retrieves the Autoship API Authorization token for the site.
 * @return string.
 */
function autoship_get_token_auth() {
	return autoship_get_settings_fields( 'autoship_token_auth', true );
}

/**
 * Retrieves the Autoship API Refresh token for the site.
 * @return string.
 */
function autoship_get_refresh_token() {
    return autoship_get_settings_fields( 'autoship_refresh_token', true );
}

/**
 * Retrieves the Autoship API Client Secret for the site.
 * @return string.
 */
function autoship_get_client_secret() {
    return autoship_get_settings_fields( 'autoship_client_secret', true );
}

/**
 * Retrieves the Autoship Token Expiration Time for the site.
 * @return int The time as a Unix timestamp.
 */
function autoship_get_token_expires_in() {
    return autoship_get_settings_fields( 'autoship_token_expires_in', true );
}

/**
 * Retrieves the Autoship Token Creation Time for the site.
 * @return int The time as a Unix timestamp.
 */
function autoship_get_token_created_at() {
  return autoship_get_settings_fields( 'autoship_token_created_at', true );
}

/**
 * Retrieves the Autoship User id for the site.
 * @return int The id.
 */
function autoship_get_user_id() {
	$id = autoship_get_settings_fields( 'autoship_user_id', true );
  return !empty( $id ) ? intval( $id ) : null;
}

/**
 * Retrieves the HTML Content to Display on the Scheduled Orders page
 * header.
 * @return html The content.
 */
function autoship_get_scheduled_orders_html(){
	return autoship_get_settings_fields('autoship_scheduled_orders_html', true);
}

/**
 * Retrieves the HTML Content to Display on the Scheduled Orders page
 * when no scheduled orders exist.
 * @return html The content.
 */
function autoship_get_scheduled_orders_body_html(){
	return autoship_get_settings_fields('autoship_scheduled_orders_body_html', true);
}

/**
 * Retrieves the Author id of the current API key set.
 * @return int wc user id.
 */
function autoship_get_api_keys_author(){
  $author_id = autoship_get_settings_fields('autoship_api_keys_author', true);
  return !empty( $author_id ) ? intval( $author_id ) : null;
}

/**
 * Retrieves the Author id of the current API key set and if not found
 * sets it based on the last added key set.
 *
 * @return int wc user id.
 */
function autoship_get_refreshed_api_keys_author(){
  $author = autoship_get_api_keys_author();
  if ( empty( $author ) ){

    $author = 0;
    $keys = autoship_get_api_keys();
    if ( !empty( $keys ) ){
      $last_added = end($keys);
      $author = $last_added->user_id;
      update_option('autoship_api_keys_author', $author);
    }

  }
  return $author;
}

/**
 * Retrieves the current QPilot API Keys from the woocommerce_api_keys table
 * @return array An array of current QPilot Keys
 */
function autoship_get_api_keys(){

  global $wpdb;

  $description = __( 'Autoship - QPilot', 'autoship' );
  $table = $wpdb->prefix . 'woocommerce_api_keys';

	// Get the API keys.
  $keys = $wpdb->get_results( $wpdb->prepare(
  	"
  		SELECT key_id, user_id, description, permissions, truncated_key, last_access
      FROM $table
  		WHERE description = %s
  	",
    $description
  ) , OBJECT_K );

  return $keys;

}

/**
 * Retrieves the current QPilot Site ID
 * @return NULL|int The site id
 */
function autoship_get_site_id() {
  $site_id = autoship_get_settings_fields( 'autoship_site_id', true );
  return !empty( $site_id ) ? intval( $site_id ) : null;
}

/**
 * Retrieves the current QPilot Client ID
 * @return int The Client id
 */
function autoship_get_client_id() {
  $client_id = autoship_get_settings_fields( 'autoship_client_id', true );
  return !empty( $client_id ) ? intval( $client_id ) : null;
}

/**
 * Retrieves the current Scheduled Order UI Version
 * @return string The UI Type
 */
function autoship_get_scheduled_orders_display_version() {
  return autoship_get_settings_fields( 'autoship_scheduled_orders_display_version', true );
}

/**
 * Retrieves the current Modal Sizes
 * @return string The UI Type
 */
function autoship_get_info_modal_sizes() {

  return apply_filters('autoship_info_modal_sizes', array(
    'small'  => '300px',
    'medium' => '500px',
    'large'  => '800px',
    'full'   => 'auto'
  ));

}

/**
 * Retrieves the Editable Shipping Rate option Enabled setting
 *
 * @return string yes for enabled else false
 */
function autoship_get_editable_shipping_rate_option() {
  return autoship_get_settings_fields( 'autoship_editable_shipping_rate_enabled', true );
}

/**
 * Retrieves the PayPal Payments Support option setting
 *
 * @return string yes for enabled else false
 */
function autoship_get_support_paypal_payments_option() {
  $val = autoship_get_settings_fields( 'autoship_support_paypal_payments', true );
  return empty( $val ) ? 'no' : $val;
}

/**
 * Retrieves the COD Payments Support option setting
 *
 * @return string yes for enabled else no
 */
function autoship_get_support_cod_payments_option() {
  $val = autoship_get_settings_fields( 'autoship_support_cod_payments', true );
  return empty( $val ) ? 'no' : $val;
}

/**
 * Retrieves the Legacy Support for using of Qpilot data
 *
 * @return string yes for enabled else false
 */
function autoship_get_legacy_support_qpilot_products_data() {
  $val = autoship_get_settings_fields( 'autoship_legacy_qpilot_products_data', true );
  return empty( $val ) ? 'no' : $val;
}

// ==========================================================
// API Processing Functions
// ==========================================================

/**
 * Retrieves the current Site Processing Version
 * @return string The Version
 */
function autoship_get_saved_site_processing_version() {
  return autoship_get_settings_fields( 'autoship_saved_site_processing_version', true );
}

/**
 * Sets the current Site Processing Version
 * @param string $versino The Version to save
 */
function autoship_set_saved_site_processing_version( $version = 'v2' ) {
  return update_option( 'autoship_saved_site_processing_version', $version );
}


// ==========================================================
// API Functions
// ==========================================================

/**
  * Retrieves the current site's subscription status.
  *
  * @return string The current status.
  */
function autoship_get_subscription_status(){

  // Site status
  $subscription_status = null;
  $autoship_token = autoship_get_token_auth();
  if ( $autoship_token != null ) {
    try {
      $client = autoship_get_default_client();
      $user = $client->get_default_user();
      $subscription_status = $user->subscriptionStatus;
    } catch ( Exception $e ) {
      // No action
    }
  }

  return $subscription_status;

}

/**
 * Gets the current sites Settings from QPilot.
 * @param bool $sitehealth Optional. The current API Health flag.
 * @return array|WP_Error The site settings or an error
 */
function autoship_get_remote_site_settings( $sitehealth = true ){

  $site_data = autoship_get_site_order_settings();

  if ( !is_wp_error( $site_data ) ){

    update_option( '_autoship_site_settings_expiration', time() + apply_filters( 'autoship_site_settings_expiration_duration', 900 ) );
    update_option( '_autoship_site_settings', $site_data, false );

  }

  return $site_data;

}
add_action( 'autoship_init_integration_test_complete', 'autoship_get_remote_site_settings', 10, 1);

/**
 * Gets the current site's saved Qpilot Settings.
 * @param bool $refresh Pull the latest settings from the API
 */
function autoship_get_remote_saved_site_settings( $refresh = false, $reset_cache = false ){

  // Get the Site Settings Expiration
  $expiration = $refresh ? 0 : get_option( '_autoship_site_settings_expiration', 0 );

  // Check if it's expired and if so refresh via the API
  if ( $expiration < time() ){

    // Delete the cache for this option since it's been refreshed.
    wp_cache_delete( "_autoship_site_settings" , 'options' );

    // Since the settings need to be refreshed pull them via the API
    return autoship_get_remote_site_settings();

  }

  // Delete the cache for this option since it's been refreshed.
  if ( $reset_cache )
  wp_cache_delete( "_autoship_site_settings" , 'options' );

  // Now retrieve the settings value
  return get_option( "_autoship_site_settings", array() );

}

/**
 * Pushes site/blog Metadata to Qpilot.
 *
 * @param array $data The site metadata keys and values to push
 * @return bool|WP_Error True on success else WP_Error.
 */
function autoship_push_site_metadata( $data = array() ){

  // Create the QPilot Client
  $client = new QPilotClient();

  try {

    // Finally lets upsert the site info
    $update_response = $client->update_site_metadata( $data );

  } catch ( Exception $e ) {

    // Only log the error if the metadata can't be pushed
    autoship_log_entry( __( 'Autoship Site Metadata Push Exception', 'autoship' ), sprintf( 'An %s Exception Occurred when attempting to send Site Metadata to QPilot. Additional Details: %s', $e->getCode(), $e->getMessage() ) );

    $notice = autoship_expand_http_code( $e->getCode() );
    return new WP_Error( 'Site Settings Retrieval Failed', __( $notice['desc'], "autoship" ) );

  }

  return true;

}

// ==========================================================
// Autoship Admin Message Queue
// ==========================================================

/**
 * Retrieves the messages that were added to the Notification System
 *
 * @param string $queue. Optional. The message queue to pull from.
 *                                 Defaults to 'autoship_messages'
 * @param bool   $reset. Optional. If true clear the cookie after getting
 *                                 The messages. Default false.
 * @param bool   $perssist. Optional. True to get message to db vs cookie
 * @return array                   An array of messages.
 */
function autoship_get_messages( $queue = 'autoship_messages' , $reset = false, $persist = false ) {

  if ( $persist ){

    $all_messages = $messages = get_option( 'autoship_persistant_notices', array() );

    if ( !empty( $queue ) )
    $messages = isset( $messages[$queue] ) ? $messages[$queue] : array();

    if ( $reset && isset( $all_messages[$queue] ) ){
      unset( $all_messages[$queue] );
      update_option( 'autoship_persistant_notices', $all_messages );
    }

  } else {

    // Check for empty queue
    if ( empty( $_COOKIE[$queue] ) )
    return array();

    // Else get the cookie contents and decode them.
    $messages = json_decode( base64_decode( $_COOKIE[$queue] ), true );

    if ( $reset ){

      //empty the cookie queue and reset.
      setcookie( $queue, '', time() - 3600 );
      $_COOKIE[$queue] = '';

    }

  }

	return $messages;

}

/**
 * Adds a messages to the Notification System
 *
 * @param string $message.         The actual message to add.
 * @param string $type.  Optional  The type of notification.
 * @param bool $persist  Optional  The notice storage type.
 * @param string $queue. Optional. The message queue to add the
 *                                 Message too.
 *                                 Defaults to 'autoship_messages'
 */
function autoship_add_message( $message, $type = 'updated' , $queue = 'autoship_messages', $persist = false ) {

  // Grab the current messages from the message queue
	$messages = autoship_get_messages( $queue, false, $persist );

  // Currently limited to 4 messages to display - why?
	if ( count( $messages ) > 4 ) {

		// Message limit has been reached
		$message  = __( 'Autoship message limit reached. Some messages are not shown.', 'autoship' );
		$type     = 'error';

  }

  // Loop through the current messages to see if the message
  // Currently being added already exists so we don't repeat ourselves.
	foreach ( $messages as $existing_message ) {
  	if ( $existing_message['message'] == $message && $existing_message['type'] == $type )
  	return;
	}

	$messages[] = array(
		'message' => $message,
		'type'    => $type
	);

  if ( $persist ){

    // Grab all messages
    $all_messages = autoship_get_messages( '', false, $persist );
    $all_messages[$queue] = $messages;
    update_option( 'autoship_persistant_notices', $all_messages );

  } else {

    // Encode the message and add it to the cookie.
    $messages_cookie = base64_encode( json_encode( $messages ) );
    setcookie( $queue, $messages_cookie, time() + 30 );
    $_COOKIE[$queue] = $messages_cookie;
  
  }

}

/**
 * Resets Ajax Product Sync messages from the Notification System
 */
function autoship_reset_ajax_messages(){

  $autoship_ajax_errors = autoship_get_messages( 'ajax_autoship_sync_product', true, true );
  $wc_meta_errors = array_filter( (array) get_option( WC_Admin_Meta_Boxes::ERROR_STORE ) );

  $refresh = false;
  foreach( $autoship_ajax_errors as $key => $autoship_ajax_error ){

    // Check if the error is one of our ajax errors and clear it if it is
    foreach( $wc_meta_errors as $index => $error ){
      if ( $autoship_ajax_error['message'] == $error ){
        unset( $wc_meta_errors[$index] );
        $refresh = true;
      }
    }

  }

  if ( $refresh )
  update_option( WC_Admin_Meta_Boxes::ERROR_STORE, $wc_meta_errors );

}
add_action( 'admin_notices', 'autoship_reset_ajax_messages', 9 );

/**
 * Prints messages from the Notification System
 *
 * NOTE: The default message queue is the 'autoship_messages'
 * queue.  Since this function is tied into the
 * {@see admin_notices} hook anything in the 'autoship_messages'
 * will be displayed by default in admin notices.
 *
 * @param string $queue. Optional. The message queue to pull the
 *                                 Message from.
 *                                 Defaults to 'autoship_messages'
 */
function autoship_print_messages( $queue = 'autoship_messages', $title = NULL ) {

	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		return;
	}

  // Retrieve the messages from the queue.
	$messages = autoship_get_messages( $queue );

  // No messages bail.
	if ( empty( $messages ) )
	return;

  // loop through the messages and display them.
	foreach ( $messages as $message ) { ?>

		<div class="notice is-dismissible <?php echo esc_attr( $message['type'] ); ?>">
			<p><?php echo $message['message']; ?></p>
		</div>

  <?php	}

  //empty the cookie queue and reset.
	setcookie( $queue, '', time() - 3600 );
	$_COOKIE[$queue] = '';

}
add_action( 'admin_notices', 'autoship_print_messages' );

/**
 * Handles the addition of Autoship messages to the notice queue.
 * Notices can be filtered using the {@see autoship_notice_handler_message_code } filter.
 * Non-Default codes can be added/caught using the {@see autoship_notice_handler_default_notice } filter.
 *
 * Error log file additions can be filtered via (@see autoship_notice_handler_error_log)
 *
 * @param string $code The message to add to the queue.
 * @param string $message. Optional. An optional message to add along with the default.
 * @param bool   $log. Optional. When set to true the notice info is added to the debug.log.
 * @param string $queue. Optional. The message queue to add the notice too. Defaults to 'autoship_messages'.
 */
function autoship_notice_handler( $code , $message = '' , $log = false , $queue = 'autoship_messages'  ) {

  $notice = array(
    'message' => $message,
    'type'    => 'updated',
    'queue'   => $queue,
  );

  switch ( $code ) {

    case 'qpilot_post_denied':
      $notice['message'] = __( 'Autoship is experiencing issues connecting to QPilot. Please check your settings and test again.', 'autoship' );
      $notice['message'] .= !empty( $message ) ? '<br/>' . $message : '';
      $notice['type']    = 'error';
      break;

    case 'add_token_error':
      $notice['message'] = __( 'See the <a href="#">Autoship Quickstart Guide</a> for help connecting Autoship Cloud.', 'autoship' );
      $notice['message'] .= !empty( $message ) ? '<br/>' . $message : '';
      $notice['type']    = 'error';
      break;

    case 'disconnected':
      $notice['message'] = __( 'Autoship has been disconnected!', 'autoship' );
      break;

    case 'exception':
      $notice['message'] = $message;
      $notice['type']    = 'error';

      break;
    case 'general_error':
      $notice['message'] = $message;
      $notice['type']    = 'error';
      break;

    case 'notice':
      $notice['message'] = $message;
      $notice['type']    = 'updated';
      break;

    default:
      // The default is a catch all for any non-defined messages.
      $notice['message'] = $message;
      $notice = apply_filters( 'autoship_notice_handler_default_notice_content', $notice, $code, $message );
      break;
  }

  // Optional filter hook for adjusting / overriding default notices.
  $notice = apply_filters( 'autoship_notice_handler_all_notice_content', $notice, $code, $message );

  // Log the Notice if directed to.
  if ( $log )
  autoship_log_entry( sprintf( __( 'Autoship %s', 'autoship' ), ucfirst( strtolower( $code ) ) ), $notice['message'] );

  // Add the Notice to the Autoship Message Queue.
  autoship_add_message( $notice['message'], $notice['type'], $notice['queue'] );

}

// ==========================================================
// Menu and Settings Registration Functions
// ==========================================================

/**
 * Adds the main Autoship Cloud Options page & adds the subpages.
 * Details and Support documentation @link https://support.autoship.cloud/
 * - Autoship Cloud
 *   Native WP Screens
 *   -- Settings
 *   -- Migrations
 *   Embeded QPilot Screens
 *   -- Products
 *   -- Customers
 *   -- Scheduled Orders
 *   -- Coupons
 *   -- Shipping Rates
 *   -- Tax Rates
 *   -- Payment Integrations
 *   -- Reports
 */
function autoship_create_menu() {

  $menu_options = apply_filters(
    'autoship_admin_settings_submenu_pages',
    array(
    'autoship' => array(
      'parent_slug' => 'autoship',
      'page_title'  => __( 'Settings', 'autoship' ),
      'menu_title'  => __( 'Settings', 'autoship' ),
      'capability'  => 'administrator',
      'menu_slug'   => 'autoship',
      'function'    => ''
    ),
    'dashboard' => array(
      'parent_slug' => 'autoship',
      'page_title'  => __( 'Dashboard', 'autoship' ),
      'menu_title'  => __( 'Dashboard', 'autoship' ),
      'capability'  => 'administrator',
      'menu_slug'   => 'dashboard',
      'function'    => 'autoship_dashboard_page'
    ),
    'products' => array(
      'parent_slug' => 'autoship',
      'page_title'  => __( 'Products', 'autoship' ),
      'menu_title'  => __( 'Products', 'autoship' ),
      'capability'  => 'administrator',
      'menu_slug'   => 'products',
      'function'    => 'autoship_products_page'
    ),
    'customers' => array(
      'parent_slug' => 'autoship',
      'page_title'  => __( 'Customers', 'autoship' ),
      'menu_title'  => __( 'Customers', 'autoship' ),
      'capability'  => 'administrator',
      'menu_slug'   => 'customers',
      'function'    => 'autoship_customers_page'
    ),
    'scheduled-orders' => array(
      'parent_slug' => 'autoship',
      'page_title'  => __( 'Scheduled Orders', 'autoship' ),
      'menu_title'  => __( 'Scheduled Orders', 'autoship' ),
      'capability'  => 'administrator',
      'menu_slug'   => 'scheduled-orders',
      'function'    => 'autoship_scheduled_orders_page'
    ),
    'coupons' => array(
      'parent_slug' => 'autoship',
      'page_title'  => __( 'Coupons', 'autoship' ),
      'menu_title'  => __( 'Coupons', 'autoship' ),
      'capability'  => 'administrator',
      'menu_slug'   => 'coupons',
      'function'    => 'autoship_coupons_page'
    ),
    'shipping-rates' => array(
      'parent_slug' => 'autoship',
      'page_title'  => __( 'Shipping Rates', 'autoship' ),
      'menu_title'  => __( 'Shipping Rates', 'autoship' ),
      'capability'  => 'administrator',
      'menu_slug'   => 'shipping-rates',
      'function'    => 'autoship_shipping_rates_page'
    ),
    'tax-rates' => array(
      'parent_slug' => 'autoship',
      'page_title'  => __( 'Tax Rates', 'autoship' ),
      'menu_title'  => __( 'Tax Rates', 'autoship' ),
      'capability'  => 'administrator',
      'menu_slug'   => 'tax-rates',
      'function'    => 'autoship_tax_rates_page'
    ),
    'payment-integrations' => array(
      'parent_slug' => 'autoship',
      'page_title'  => __( 'Payment Integrations', 'autoship' ),
      'menu_title'  => __( 'Payment Integrations', 'autoship' ),
      'capability'  => 'administrator',
      'menu_slug'   => 'payment-integrations',
      'function'    => 'autoship_payment_integrations_page'
    ),
    'reports' => array(
      'parent_slug' => 'autoship',
      'page_title'  => __( 'Reports', 'autoship' ),
      'menu_title'  => __( 'Reports', 'autoship' ),
      'capability'  => 'administrator',
      'menu_slug'   => 'reports',
      'function'    => 'autoship_reports_page'
    ))
  );

  //create new top-level menu
  add_menu_page(
    __( 'Autoship Cloud', 'autoship' ),
    __( 'Autoship Cloud', 'autoship' ),
    apply_filters('autoship_cloud_main_page_security','administrator'),
    'autoship',
    'autoship_settings_page',
    'dashicons-cloud'
  );

  // Setup Sub Menu's to the Autoship Main Setting
  foreach ($menu_options as $submenu) {

    add_submenu_page(
    $submenu['parent_slug'],
    $submenu['page_title'],
    $submenu['menu_title'],
    apply_filters( 'autoship_cloud_subpage_security', $submenu['capability'], $submenu['menu_slug'] ),
    $submenu['menu_slug'],
    $submenu['function'] );

  }

  //call register settings function
  add_action( 'admin_init', 'register_autoship_settings' );
}
add_action( 'admin_menu', 'autoship_create_menu' );

/**
 * Registers the Autoship Settings.
 */
function register_autoship_settings() {

  // Setting => Settings Group
  $autoship_settings = apply_filters(
  'autoship_registered_admin_settings',
  array(
    'autoship_client_id'                        => 'autoship-settings-group',
    'autoship_client_secret'                    => 'autoship-settings-group',
    'autoship_cart_schedule_options_enabled'    => 'autoship-settings-group',
    'autoship_site_id'                          => 'autoship-settings-group',
    'autoship_user_id'                          => 'autoship-settings-group',
    'autoship_token_auth'                       => 'autoship-settings-group',
    'autoship_refresh_token'                    => 'autoship-settings-group',
    'autoship_webchat_directline_secret'        => 'autoship-settings-group',
    'autoship_product_message'                  => 'autoship-settings-group',
    //Deprecated since v1.1.31 & replaced with autoship_scheduled_orders_display_version
    'autoship_scheduled_orders_app_enabled'     => 'autoship-settings-group',
    'autoship_scheduled_orders_display_version' => 'autoship-settings-group',
    'autoship_editable_shipping_rate_enabled'   => 'autoship-settings-group',
    'autoship_support_paypal_payments'          => 'autoship-settings-group',

    'autoship_scheduled_orders_html'            => 'autoship-settings-group',
    'autoship_scheduled_orders_body_html'       => 'autoship-settings-group',
    'autoship_free_shipping'                    => 'autoship-settings-group',
    'autoship_sync_all_products_enabled'        => 'autoship-settings-group',
    'autoship_rest_order_fee_lines_enabled'     => 'autoship-settings-group',
    'autoship_display_next_occurrence_offset'   => 'autoship-settings-group',

    'autoship_translation'                      => 'autoship-settings-group',
    'autoship_and_save_translation'             => 'autoship-settings-group',
    'autoship_scheduled_order_translation'      => 'autoship-settings-group',
    'autoship_scheduled_orders_translation'     => 'autoship-settings-group',

    'autoship_product_info_display'             => 'autoship-settings-group',
    'autoship_product_info_modal_size'          => 'autoship-settings-group',
    'autoship_product_info_mobile_tooltip'      => 'autoship-settings-group',
    'autoship_product_info_url'                 => 'autoship-settings-group',
    'autoship_product_info_btn_type'            => 'autoship-settings-group',
    'autoship_product_info_btn_text'            => 'autoship-settings-group',
    'autoship_product_info_html'                => 'autoship-settings-group',
    'autoship_support_cod_payments'             => 'autoship-settings-group',
    'autoship_legacy_qpilot_products_data'      => 'autoship-settings-group',
    ) );

  //register settings
  foreach ($autoship_settings as $setting => $group ) {
  	register_setting( $group, $setting );
  }

  //Set / Update the defaults
  autoship_get_settings_fields ();
}

/**
 * Returns a list of Autoship Setting Page Tabs.
 * @return array of settings tabs with labels and callback.
 */
function autoship_settings_tabs(){

  // get the current health statuses.
  $healthy = autoship_get_integration_health_status();
  $health_ext = '<i class="icon-asc"></i>';
  $health_class = 'ahstatus';

  if ( false !== $healthy && "healthy" == $healthy ){
    $health_class .= ' valid';
  } else if ( !autoship_is_new() ){
    $health_class .= ' error';
  }

  $tabs = array(
    'autoship-connection-settings'  => array( 'label' => __( 'Connection Settings' . $health_ext ,'autoship'), 'callback' => 'autoship_connection_settings_section', 'link_class' => $health_class ),
    'autoship-options'              => array( 'label' => __( 'Options','autoship'), 'callback' => 'autoship_options_section', 'link_class' => '' ),
    'autoship-utilities'            => array( 'label' => __( 'Utilities','autoship'), 'callback' => 'autoship_utilities_section', 'link_class' => '' ),
    'autoship-extensions'           => array( 'label' => __( 'Extensions','autoship'), 'callback' => 'autoship_extensions_section', 'link_class' => '' ),
    'autoship-migrations'           => array( 'label' => __( 'Migrations','autoship'), 'callback' => 'autoship_migrations_section', 'link_class' => '' ),
    'autoship-logs'                 => array( 'label' => __( 'Logs','autoship'), 'callback' => 'autoship_logs_section', 'link_class' => '' ),
  );

  return apply_filters('autoship_admin_settings_tabs', $tabs );

}


// ==========================================================
// Settings Retrieval and Default Functions
// ==========================================================

/**
 * Retrieves the Autoship settings fields.
 *
 * @param array|string   $fields. Optional. A set of fields to get.
 * @param bool $single True to return the first value only. False for the full array.
 * @return array An array of option values.
 */
function autoship_get_settings_fields ( $fields = array(), $single = false ){

  $fields = is_array( $fields ) ? $fields : explode( ',' , $fields );

  $defaults = apply_filters(
  'autoship_registered_admin_setting_field_ids',
  array(
    'autoship_client_id',
    'autoship_client_secret',
    'autoship_token_auth',
    'autoship_user_id',
    'autoship_site_id',
    'autoship_refresh_token',
    'autoship_free_shipping',
    'autoship_sync_all_products_enabled',
    'autoship_scheduled_orders_app_enabled',
    'autoship_scheduled_orders_display_version',
    'autoship_editable_shipping_rate_enabled',
    'autoship_support_paypal_payments',
    'autoship_saved_site_processing_version',
    'autoship_scheduled_orders_html',
    'autoship_scheduled_orders_body_html',
    'autoship_cart_schedule_options_enabled',
    'autoship_rest_order_fee_lines_enabled',
    'autoship_display_next_occurrence_offset',
    'autoship_product_message',
    'autoship_dynamic_cart',
    'autoship_webchat_directline_secret',
    'autoship_health',
    'autoship_get_checked_utc',
    'autoship_put_checked_utc',
    'autoship_post_checked_utc',

    'autoship_translation',
    'autoship_and_save_translation',
    'autoship_scheduled_order_translation',
    'autoship_scheduled_orders_translation',

    'autoship_product_info_display',
    'autoship_product_info_modal_size',
    'autoship_product_info_mobile_tooltip',
    'autoship_product_info_btn_type',
    'autoship_product_info_url',
    'autoship_product_info_btn_text',
    'autoship_product_info_html',
    'autoship_support_cod_payments',

    'autoship_legacy_qpilot_products_data',
  ));

  $values = array();
  $checks = empty( $fields ) ? $defaults : $fields;

  foreach ( $checks as $field ) {
    $values[$field] = get_option( $field , apply_filters( "autoship_get_setting_{$field}_default_init", false ) );
  }

  return apply_filters( 'autoship_get_settings_fields_values_init', $single ? current( $values ) : $values );

}

/**
 * Add a default value for the autoship_saved_site_processing_version option if
 * It doesn't exist.
 * @param Mixed|bool The value.
 * @return Mixed|bool False to not include default else
 */
function autoship_saved_site_processing_version_default_init( $value ){
  return 'v2';
}
add_filter( 'autoship_get_setting_autoship_saved_site_processing_version_default_init', 'autoship_saved_site_processing_version_default_init', 10, 1 );


/**
 * Add a default value for the autoship_product_info_mobile_tooltip option
 * It doesn't exist.
 * @param Mixed|bool The value.
 * @return Mixed|bool False to not include default else
 */
function autoship_product_info_mobile_tooltip_default_init( $value ){
  return 'yes';
}
add_filter( 'autoship_get_setting_autoship_product_info_mobile_tooltip_default_init', 'autoship_product_info_mobile_tooltip_default_init', 10, 1 );

/**
 * Add a default value for the autoship_product_info_btn_text when
 * It doesn't exist.
 * @param Mixed|bool The value.
 * @return Mixed|bool False to not include default else
 */
function autoship_product_info_btn_type_default_init( $value ){
  return 'icon';
}
add_filter( 'autoship_get_setting_autoship_product_info_btn_type_default_init', 'autoship_product_info_btn_type_default_init', 10, 1 );

/**
 * Add a default value for the autoship_product_info_btn_text when
 * It doesn't exist.
 * @param Mixed|bool The value.
 * @return Mixed|bool False to not include default else
 */
function autoship_product_info_modal_size_default_init( $value ){
  return 'medium';
}
add_filter( 'autoship_get_setting_autoship_product_info_modal_size_default_init', 'autoship_product_info_modal_size_default_init', 10, 1 );

/**
 * Add a default value for the autoship_product_info_btn_text when
 * It doesn't exist.
 * @param string $value
 * @return string 'Info'
 */
function autoship_product_info_btn_text_default_init( $value ){
  return 'Info';
}
add_filter( 'autoship_get_setting_autoship_product_info_btn_text_default_init', 'autoship_product_info_btn_text_default_init', 10, 1 );

/**
 * Add a default value for the autoship_product_info_display when
 * It doesn't exist.
 * @param Mixed|bool The value.
 * @return Mixed|bool False to not include default else
 */
function autoship_product_info_display_default_init( $value ){
  return autoship_is_new() ? 'modal' : 'none';
}
add_filter( 'autoship_get_setting_autoship_product_info_display_default_init', 'autoship_product_info_display_default_init', 10, 1 );

/**
 * Add a default value for the autoship_editable_shipping_rate_enabled when
 * It doesn't exist.
 *
 * @param string $value The current value
 * @return string The default or the current value
 */
function autoship_editable_shipping_rate_enabled_default_init( $value ){
  return autoship_is_new() || empty( $value ) ? 'no' : $value;
}
add_filter( 'autoship_get_setting_autoship_editable_shipping_rate_enabled_default_init', 'autoship_editable_shipping_rate_enabled_default_init', 10, 1 );

/**
 * Add a default value for the autoship_support_paypal_payments when
 * It doesn't exist.
 *
 * @param string $value The current value
 * @return string The default or the current value
 */
function autoship_support_paypal_payments_default_init( $value ){
  return autoship_is_new() || empty( $value ) ? 'no' : $value;
}
add_filter( 'autoship_get_setting_autoship_support_paypal_payments_default_init', 'autoship_support_paypal_payments_default_init', 10, 1 );


/**
 * Add a default value for the autoship_legacy_qpilot_products_data when
 * It doesn't exist.
 *
 * @param string $value The current value
 * @return string The default or the current value
 */
function autoship_legacy_qpilot_products_data_default_init( $value ){
  return autoship_is_new() || empty( $value ) ? 'no' : $value;
}
add_filter( 'autoship_get_setting_autoship_legacy_qpilot_products_data_default_init', 'autoship_legacy_qpilot_products_data_default_init', 10, 1 );


/**
 * Add a default value for the autoship_product_info_html when
 * It doesn't exist.
 * @param Mixed|bool The value.
 * @return Mixed|bool False to not include default else
 */
function autoship_product_info_html_default_init( $value ){

  ob_start();?>

  <div style="text-align:left;">
  <strong>Why Choose to Autoship?</strong>
  <ul style="padding-left:20px;">
    <li>Automatically re-order your favorite
  products on your schedule.</li>
    <li>Easily change the products or shipping
  date for your upcoming Scheduled Orders.</li>
    <li>Pause or cancel any time.</li>
  </ul>
  </div>

  <?php
  return ob_get_clean();

}
add_filter( 'autoship_get_setting_autoship_product_info_html_default_init', 'autoship_product_info_html_default_init', 10, 1 );

/**
 * Add a default value for the autoship_scheduled_orders_body_html when
 * It doesn't exist.
 * @param Mixed|bool The value.
 * @return Mixed|bool False to not include default else
 */
function autoship_scheduled_orders_body_html_default_init( $value ){

  $label = autoship_translate_text( 'Scheduled Order' );

  ob_start();?>

  <h2><?php __('No Current Scheduled Orders', 'autoship');?></h2>
  <p><?php __("You currently have no scheduled orders to display. Click the button below to get started and create your first {$label}.", 'autoship');?></p>
  <p style="text-align:center;">[autoship-create-scheduled-order-action]</p>

  <?php
  return ob_get_clean();

}
add_filter( 'autoship_get_setting_autoship_scheduled_orders_body_html_default_init', 'autoship_scheduled_orders_body_html_default_init', 10, 1 );

/**
 * Add a default value for the autoship_support_cod_payments when
 * It doesn't exist.
 *
 * @param string $value The current value
 * @return string The default or the current value
 */
function autoship_support_cod_payments_default_init( $value ){
  return autoship_is_new() || empty( $value ) ? 'no' : $value;
}
add_filter( 'autoship_get_setting_autoship_support_cod_payments_default_init', 'autoship_support_cod_payments_default_init', 10, 1 );

/**
 * Settings Init Value, Upgrade & Migrate function
 * This allows to set the default for any new settings values.
 * Hooks into the {@see autoship_get_settings_fields_values_init} filter
 *
 * @param array $values The Autoship settings and corresponding values,
 * @return array The updated Autoship settings and corresponding values.
 */
function autoship_init_new_settings( $values ){

  $inits = array();

  // The autoship_scheduled_orders_display_version should be app, hosted or template.
  if ( isset( $values['autoship_scheduled_orders_display_version'] ) && empty( $values['autoship_scheduled_orders_display_version'] ) )
  $inits['autoship_scheduled_orders_display_version'] = "yes" == $values['autoship_scheduled_orders_app_enabled'] ? 'app' : 'template';

  // For each updated / initialized settings update
  // the option and the filtered values.
  foreach ($inits as $key => $value) {
    $values[$key] = $value;
    update_option( $key , $value );
  }

  return $values;

}
add_filter( 'autoship_get_settings_fields_values_init', 'autoship_init_new_settings', 10, 1 );


// ==========================================================
// Autoship Cloud Settings Tab Content Functions
// ==========================================================

/**
 * Hide Submit on Extensions Tab
 * @param bool $include Whether to include or exclude the submit.
 * @param string $active_tab The current tab.
 * @return bool The filtered value to include or exclude the submit.
 */
function autoship_exclude_submit_on_tab( $include, $active_tab ){

  return !in_array( $active_tab, array( 'autoship-extensions', 'autoship-utilities', 'autoship-migrations' ) );

}
add_filter('autoship_admin_settings_tab_include_submit', 'autoship_exclude_submit_on_tab', 10, 2 );

/**
 * Callback for the Autoship Admin Reports Tab Content.
 * @param string $id The tab id.
 * @param int $site_id The current site's it.
 * @param string $token_auth The current authorization token.
 */
function autoship_admin_reports_tabs_content( $id, $site_id, $token_auth ){

  $reports = array(
  'date_summary'          => urlencode($site_id) . '/reports/products-by-date-summary?tokenBearerAuth=' . urlencode($token_auth),
  'schedule_nxt_order'    => urlencode($site_id) . '/reports/scheduled-orders-by-product?tokenBearerAuth=' . urlencode($token_auth),
  'schedule_order_metrics'=> urlencode($site_id) . '/reports/scheduled-orders-metrics?tokenBearerAuth=' . urlencode($token_auth),
  'events_logs'           => urlencode($site_id) . '/reports/event-logs?tokenBearerAuth=' . urlencode($token_auth) );

  ?>

  <iframe src="<?php echo esc_attr(autoship_get_merchants_url()); ?>/widgets/dashboard/<?php echo $reports[$id]; ?>" class="autoship-admin-scheduled-orders-iframe autoship-admin-dashboard-iframe" frameborder="0"></iframe>

  <?php

}

/**
 * Generate the content to the Autoship Settings page.
 */
function autoship_settings_page() {
	autoship_include_template( 'admin/settings');
}

/**
 * Generates the content for a Autoship Cloud > Settings > Settings section.
 * @param array The current autoship Settings fields and values;
 */
function autoship_connection_settings_section ( $autoship_settings ){

  $key_author = autoship_get_refreshed_api_keys_author();
  $user_meta  = get_userdata( $key_author );
  $user_email = !empty( $user_meta ) ? $user_meta->user_email : 'Unavailable';

  // Retrieve the Autoship site connection params.
  $site_parameters = autoship_get_site_parameters ();

  // get the current health statuses.
  $healthy = autoship_get_integration_health_status();

  // Get the Autoship Health Check specific notifications from the queue.
  $messages = autoship_get_messages( 'autoship_health_checks' , true );

  // Check if one or more error's were returned by QPilot.
  // This deals with the case that the statuses were populated by error returned.
  foreach ( $messages as $message ){
    if( 'error' == $message['type'] )
    $healthy = false;
  }

  $status_class = '';

  // Check if the user has entered any connection info.
  if ( autoship_is_new() ) {

    $current_status = "<span class=\"ahstatus\">" . __( 'Not Connected', 'autoship' ) . "<i class=\"icon-asc\"></i></span>";

    $current_status_message = __( 'Your connection to QPilot cloud is not setup.', 'autoship' );
    $current_status_message .= '<br/><a href="https://merchants.QPilot.cloud/login" target="_blank">' . __( "Login to your QPilot Merchant Account", "autoship" ) . '</a>' . __( " to get your QPilot Client ID and Secret.", "autoship" );
    $current_status_message .= '<br/>' . __( "First time? See our help guide for ", 'autoship') . '<a href="https://support.autoship.cloud/article/319-3-connecting-the-woocommerce-api" target="_blank">' . __( "connecting your WooCommerce API to QPilot.", 'autoship') . '</a>';

    $status_class = 'health-notice';

  // Get the status to see if the connection is healthy or not.
  } elseif ( false !== $healthy && "healthy" == $healthy ){

    $current_status = "<span class=\"ahstatus valid\">" . __( 'Healthy', 'autoship' ) . "<i class=\"icon-asc\"></i></span>";
    $current_status_message = __( 'For help resolving common API issues, please see our help guide for ', 'autoship' ) . '<a role="button" class="wp-autoship-link" href="https://support.autoship.cloud/article/403-troubleshooting-wc-api" target="_blank">'. __( 'WooCommerce API Healthiness', 'autoship' ) . '</a>';

    $status_class = 'health-valid';

  } else {

    $current_status = "<span class=\"ahstatus error\">" . __( 'UnHealthy', 'autoship' ) . "<i class=\"icon-asc\"></i></span>";
    $current_status_message = __( 'Your Autoship connection is not currently healthy and one or more connection requirements can not be confirmed.', 'autoship' );

    if ( !empty( $messages ) )
    $current_status_message .= '<br/>' . __('Details about the connection issue(s) are listed below.', 'autoship' );

    $current_status_message .= '<br/>' . __('For help resolving common API issues, please see our help guide for ', 'autoship' );
    $current_status_message .= '<a role="button" class="wp-autoship-link" href="https://support.autoship.cloud/article/403-troubleshooting-wc-api" target="_blank">'. __( 'WooCommerce API Healthiness', 'autoship' ) . '</a>';

    $status_class = 'health-error';

  }

  ob_start();?>

    <h2><?=__('Connection Settings', 'autoship')?></h2>

    <div id="autoship-status-box-summary" class="autoship-meta-boxes-summary autoship-box autoship_general_admin_notice <?=$status_class?>">
      <div class="autoship-box-content">
      	<ul class="autoship-stats-list">
      		<li>
      			<span class="list-label title-label">
      				<?php echo __( 'API Health Check: Your Site Connection is ', 'autoship' ); ?><?=$current_status?>
            </span>
      		</li>
      		<li>
      			<span class="list-label">
      				<p class="wp-autoship-label-message"><?=$current_status_message?></p>
            </span>
      		</li>

          <?php

          // If there are any health related notices display them.
          if ( !empty( $messages ) ):

          ?>

          <li>
      			<span class="list-label">

            <?php

            // loop through the messages and display them.
          	foreach ( $messages as $message ) { ?>

          		<p class="wp-autoship-label-message <?php echo esc_attr( $message['type'] ); ?>"><?php echo $message['message']; ?></p>

            <?php	}

            ?>

            </span>
      		</li>

          <?php endif; ?>

      	</ul>
      </div>
    </div>

    <table class="form-table">


      <?php if ( !autoship_is_new() ){ ?>

      <tr valign="top">
        <th scope="row"><label class="autoship_user_id" for="autoship_user_id">Connected WP-Admin User</label></th>
        <td>
          <input type="text" id="connected_uid" name="connected_uid" class="connected_uid"
          value="<?php echo $user_email;?>"
          autocomplete="false" readonly style="background-color:#fff;" disabled/>
        </td>
      </tr>

      <?php } ?>

      <tr valign="top">
        <th scope="row"><label for="autoship_user_id">QPilot Client ID</label></th>
        <td>
          <input type="text" id="autoship_client_id" name="autoship_client_id"
          value="<?php echo esc_attr( $autoship_settings['autoship_client_id'] ); ?>"
          autocomplete="false" />
        </td>
      </tr>

      <tr valign="top">
        <th scope="row"><label for="autoship_client_secret">QPilot Client Secret</label></th>
        <td>
          <input type="password" id="autoship_client_secret" name="autoship_client_secret"
          value="<?php echo esc_attr( $autoship_settings['autoship_client_secret'] ); ?>"
          autocomplete="new-password" />
        </td>
      </tr>

      <?php if ( !autoship_is_new() ) { ?>

      <tr valign="top">
        <th scope="row">Connect Autoship</th>
        <td>
          <a href="<?php echo esc_attr( admin_url( '/admin-ajax.php?action=autoship_oauth2_disconnect') ); ?>"
          onclick="return confirm(<?php echo esc_attr( json_encode( __( 'Are you sure you want to disconnect?', 'autoship' ) ) ); ?>)"
          class="button button-secondary">Disconnect</a>

          <a href="<?php echo esc_attr( admin_url( '/admin-ajax.php?action=autoship_test_integration' ) ); ?>"
          class="button button-secondary">Test Integration</a>
        </td>
      </tr>

      <?php } else if ( autoship_has_credentials() ){ ?>

      <tr valign="top">
        <th scope="row">Connect Autoship</th>
        <td>
          <a id='autoship_connect_autoship_button' href="<?php echo esc_attr( autoship_get_merchants_url() ); ?>/oauth2?<?php echo str_replace( '+', '%20', http_build_query( $site_parameters ) ); ?>" class="button button-primary">Connect</a>
        </td>
      </tr>

      <?php } ?>

    </table>

    <?php if ( !autoship_is_new() ) { ?>

    <h2><?=__('Merchant site fields:', 'autoship')?></h2>
    <p><strong><?=__('Danger Zone: ', 'autoship')?></strong><?=__( 'These fields are populated for you after connecting your site to QPilot.','autoship')?><br/><?=__( 'Please do not change any Merchant Site Fields unless advised to do so!','autoship')?></p>

    <table class="form-table">

      <tr valign="top">
        <th scope="row"><label for="autoship_user_id">User ID</label></th>
        <td>
          <input type="text" id="autoship_user_id" name="autoship_user_id"
          value="<?php echo esc_attr( $autoship_settings['autoship_user_id'] ); ?>"
          autocomplete="false" />
        </td>
      </tr>

      <tr valign="top">
        <th scope="row"><label for="autoship_site_id">Site ID</label></th>
        <td>
          <input type="text" id="autoship_site_id" name="autoship_site_id"
          value="<?php echo esc_attr( $autoship_settings['autoship_site_id'] ); ?>"
          autocomplete="new-password" />
        </td>
      </tr>

      <tr valign="top">
        <th scope="row"><label for="autoship_token_auth">Token Auth</label></th>
        <td>
          <input type="password" id="autoship_token_auth" name="autoship_token_auth"
          value="<?php echo esc_attr( $autoship_settings['autoship_token_auth'] ); ?>"
          autocomplete="new-password" />
        </td>
      </tr>

      <tr valign="top">
        <th scope="row"><label for="autoship_refresh_token">Refresh Token</label></th>
        <td>
          <input type="password" id="autoship_refresh_token" name="autoship_refresh_token"
          value="<?php echo esc_attr( $autoship_settings['autoship_refresh_token'] ); ?>"
          autocomplete="new-password" />
        </td>
      </tr>

    </table>

    <?php } ?>

  <?php
  echo ob_get_clean();

}

/**
 * Generates the content for a Autoship Cloud > Settings > Options section.
 * @param array The current autoship Settings fields and values;
 */
function autoship_options_section ( $autoship_settings ){

  $settings = array( 'teeny' => false, 'textarea_rows' => 8, 'media_buttons' => false );

  ob_start();?>

  <h2><?php echo __('Additional Options', 'autoship'); ?></h2>

  <table class="form-table">

    <tr valign="top">
      <th scope="row"><?php echo __('Autoship Label', 'autoship'); ?></th>
      <td>
        <input type="text" id="autoship_translation" name="autoship_translation"
        value="<?php echo esc_attr( $autoship_settings['autoship_translation'] ); ?>"
        placeholder="Autoship" />
        <p class="help-text-wrapper"><label for="autoship_translation"><?php echo __('Enter a string to use in place of the <strong>Autoship</strong> string in your Shop.', 'autoship'); ?></label></p>
      </td>
    </tr>

    <tr valign="top">
      <th scope="row"><?php echo __('Autoship and Save Label', 'autoship'); ?></th>
      <td>
        <input type="text" id="autoship_and_save_translation" name="autoship_and_save_translation"
        value="<?php echo esc_attr( $autoship_settings['autoship_and_save_translation'] ); ?>"
        placeholder="Autoship" />
        <p class="help-text-wrapper"><label for="autoship_and_save_translation"><?php echo __('Enter a string to use in place of the <strong>Autoship and Save</strong> string in your Shop.  This will be used on the product and cart pages when a discount is offered on Autoship items.', 'autoship'); ?></label></p>
      </td>
    </tr>

    <tr valign="top">
      <th scope="row"><?php echo __('Scheduled Order Label', 'autoship'); ?></th>
      <td>
        <input type="text" id="autoship_scheduled_order_translation" name="autoship_scheduled_order_translation"
        value="<?php echo esc_attr( $autoship_settings['autoship_scheduled_order_translation'] ); ?>"
        placeholder="Scheduled Order" />
        <p class="help-text-wrapper"><label for="autoship_scheduled_order_translation"><?php echo __('Enter a string to use in place of the <strong>Scheduled Order</strong> string in your Shop.', 'autoship'); ?></label></p>
      </td>
    </tr>

    <tr valign="top">
      <th scope="row"><?php echo __('Scheduled Orders Label', 'autoship'); ?></th>
      <td>
        <input type="text" id="autoship_scheduled_orders_translation" name="autoship_scheduled_orders_translation"
        value="<?php echo esc_attr( $autoship_settings['autoship_scheduled_orders_translation'] ); ?>"
        placeholder="Scheduled Orders" />
        <p class="help-text-wrapper"><label for="autoship_scheduled_orders_translation"><?php echo __('Enter a string to use in place of the <strong>Scheduled Orders</strong> string in your Shop.', 'autoship'); ?></label></p>
      </td>
    </tr>

    <tr valign="top">
      <th scope="row"><?php echo __('Enable Free Shipping', 'autoship'); ?></th>
      <td>
        <input
        type="radio"
        name="autoship_free_shipping"
        id="autoship_free_shipping_disable"
        value=""
        <?php echo checked('', $autoship_settings['autoship_free_shipping']); ?>
        />
        <label for="autoship_free_shipping_disable"><?php echo __('Disable Autoship Free Shipping. You may need to delete your cache for the changes to take effect.', 'autoship'); ?></label></br></br>
        <input
        type="radio"
        name="autoship_free_shipping"
        id="autoship_free_shipping_enable"
        value="checkout+autoship"
        <?php echo checked('checkout+autoship', $autoship_settings['autoship_free_shipping']); ?>
        />
        <label for="autoship_free_shipping_enable"><?php echo __('Add the Autoship Free Shipping method to <strong>Checkout</strong>. Once enabled, add this shipping method to a Shipping Zone in order to offer Free Shipping to customers who add at least 1 item to their cart selected for Autoship.', 'autoship'); ?></label></br></br>
      </td>
    </tr>

    <tr valign="top">
      <th scope="row"><?php echo __( 'Displaying Scheduled Orders in My Account', 'autoship' ); ?></th>
      <td>
        <p><?php echo __( 'Choose how the Scheduled Orders should be displayed in the <strong>My Account > Scheduled Orders</strong> page for your customers.', 'autoship' ); ?></p><br/>

        <input
        type="radio"
        name="autoship_scheduled_orders_display_version"
        id="autoship_scheduled_orders_template"
        value="template"
        <?php echo checked( "template", $autoship_settings['autoship_scheduled_orders_display_version'] ); ?>
        />
        <label for="autoship_scheduled_orders_template"><?php echo __( '(Default) Native UI Display Option: Display Scheduled Orders in My Account using WordPress templates.', 'autoship' ); ?></label></br></br>

        <input
        type="radio"
        name="autoship_scheduled_orders_display_version"
        id="autoship_scheduled_orders_hosted"
        value="hosted"
        <?php echo checked( "hosted", $autoship_settings['autoship_scheduled_orders_display_version'] ); ?>
        />
        <label for="autoship_scheduled_orders_hosted"><?php echo __( 'Hosted iFrame Option: Display Scheduled Orders in My Account within an iframe.', 'autoship' ); ?></label></br></br>

        <input
        type="radio"
        name="autoship_scheduled_orders_display_version"
        id="autoship_scheduled_orders_app"
        value="app"
        <?php echo checked( "app", $autoship_settings['autoship_scheduled_orders_display_version'] ); ?>
        />
        <label for="autoship_scheduled_orders_app"><?php echo __( 'Embedded App Option: Display Scheduled Orders in My Account using an embedded application', 'autoship' ); ?></label></br></br>

      </td>
    </tr>

    <tr valign="top">
      <th scope="row"><?php echo __( 'Enable Selectable Shipping Rates in Native UI Display', 'autoship' ); ?></th>
      <td>
        <input type="checkbox"
        id="autoship_editable_shipping_rate_enabled"
        name="autoship_editable_shipping_rate_enabled"
        value="yes"
        <?php echo checked( 'yes', $autoship_settings['autoship_editable_shipping_rate_enabled'] ); ?>
        autocomplete="false" />
        <label for="autoship_editable_shipping_rate_enabled"><?php echo __( 'Allow Customers to choose a Preferred Shipping rate when editing a Scheduled Order in the Native UI Display.', 'autoship' ); ?></label>
      </td>
    </tr>

    <tr valign="top">
      <th scope="row"><?php echo __( 'Schedule Products in the Cart', 'autoship' ); ?></th>
      <td>
        <input type="checkbox"
        id="autoship_cart_schedule_options_enabled"
        name="autoship_cart_schedule_options_enabled"
        value="yes"
        <?php echo checked( 'yes', $autoship_settings['autoship_cart_schedule_options_enabled'] ); ?>
        autocomplete="false" />
        <label for="autoship_cart_schedule_options_enabled"><?php echo __( 'Enable to display Autoship options for each product in the Cart.', 'autoship' ); ?></label>
      </td>
    </tr>

    <tr valign="top">
      <th scope="row"><?php echo __( 'Cash On Delivery Payments at Checkout', 'autoship' ); ?></th>
      <td>
      <p><?php echo sprintf( __( 'Warning! Enabling support for Cash On Delivery (COD) Payments as an Autoship Payment method requires that you add the <a href="%s" target="_blank">"Other" Payment Type</a> to your Payment Integrations for Autoship Cloud. The "Other" Payment Type and Payment Method enable Scheduled Orders to be completely processed without involving a payment gateway.', 'autoship' ), 'https://support.autoship.cloud/article/1031-other-payment' ); ?></p>
      <p><?php echo sprintf( __( 'Please review the documentation for the <a href="%s" target="_blank">"Other" Payment Type and Method</a> to ensure you do not unexpectedly enable your customers to not pay for their Scheduled Orders', 'autoship' ), 'https://support.autoship.cloud/article/1031-other-payment' ); ?></p><br/>

      <input
        type="radio"
        name="autoship_support_cod_payments"
        id="autoship_support_cod_payments_disabled"
        value="no"
        <?php echo checked( "no", $autoship_settings['autoship_support_cod_payments'] ); ?>
        />
        <label for="autoship_support_cod_payments_disabled"><?php echo __( '(Default) Disable Cash On Delivery as a supported Autoship Payment Method', 'autoship' ); ?></label></br></br>

        <input
        type="radio"
        name="autoship_support_cod_payments"
        id="autoship_support_cod_payments_enabled"
        value="yes"
        <?php echo checked( "yes", $autoship_settings['autoship_support_cod_payments'] ); ?>
        />
        <label for="autoship_support_cod_payments_enabled"><?php echo __( 'Enable Cash On Delivery as a supported Autoship Payment Method', 'autoship' ); ?></label></br></br>

      </td>
    </tr>

    <tr valign="top">
      <th scope="row"><?php echo __( 'Display QPilot Coupons as Fee Lines', 'autoship' ); ?></th>
      <td>
        <input type="checkbox"
        id="autoship_rest_order_fee_lines_enabled"
        name="autoship_rest_order_fee_lines_enabled"
        class="autoship_hide_show_toggler"
        data-target=".virtual-coupon-override-note"
        value="yes"
        <?php echo checked( 'yes', $autoship_settings['autoship_rest_order_fee_lines_enabled'] ); ?>
        autocomplete="false" />
        <label for="autoship_rest_order_fee_lines_enabled"><?php echo __( 'Override default and include QPilot Coupons as Fee Lines on Autoship Orders.', 'autoship' ); ?></label>
        <div class="virtual-coupon-override-note" style="<?php echo 'yes' != $autoship_settings['autoship_rest_order_fee_lines_enabled'] ? 'display:none;' : ''; ?>">
          <p><strong><?php echo __( 'Important', 'autoship');?></strong></p>
          <small><?php echo __( 'By overriding this default setting, you are disabling the use of virtual coupons for WooCommerce Orders created via the REST API by your connected QPilot Site.  Developers can adjust this setting via the filter <strong>autoship_qpilot_orders_via_rest_enable_fee_lines</strong> within <strong>src\coupons.php</strong>', 'autoship');?>.</small>
        </div>
      </td>
    </tr>

    <tr valign="top">
      <th scope="row"><?php echo __( 'Display Ship by Date in Order Management', 'autoship' ); ?></th>
      <td>
        <input type="checkbox"
        id="autoship_display_next_occurrence_offset"
        name="autoship_display_next_occurrence_offset"
        value="yes"
        <?php echo checked( 'yes', $autoship_settings['autoship_display_next_occurrence_offset'] ); ?>
        autocomplete="false" />
        <label for="autoship_display_next_occurrence_offset"><?php echo __( 'Enable to include a "Ship By Date" column in the WooCommerce > Orders list table showing the calculated ship date for each order based on the Next Occurrence Offset.', 'autoship' ); ?></label>
      </td>
    </tr>

    <tr valign="top">
      <th scope="row"><?php echo __( 'Scheduled Orders Header Message', 'autoship' ); ?></th>
      <td>

        <div class="html-editor-wrapper">

        <?php wp_editor( $autoship_settings['autoship_scheduled_orders_html'], 'autoship_scheduled_orders_html', $settings);?>

        </div>

        <p class="help-text-wrapper"><label for="autoship_scheduled_orders_html"><?php echo __( 'Use the above editor to display content above the Scheduled Orders page. This field accepts text and HTML.', 'autoship' ); ?></label></p>

      </td>
    </tr>

    <tr valign="top">
      <th scope="row"><?php echo __( 'No Scheduled Orders Body Message', 'autoship' ); ?></th>
      <td>

        <div class="html-editor-wrapper">

        <?php wp_editor( $autoship_settings['autoship_scheduled_orders_body_html'], 'autoship_scheduled_orders_body_html', $settings);?>

        </div>

        <p class="help-text-wrapper"><label for="autoship_scheduled_orders_body_html"><?php echo __( 'Use the above editor to display content on the Scheduled Orders page when no Scheduled Orders exist for the user. This field accepts text and HTML.', 'autoship' ); ?></label></p>

      </td>
    </tr>


  </table>

  <h2><?php echo __('Product Page Options', 'autoship'); ?></h2>

  <table class="form-table">

    <tr valign="top">
      <th scope="row">
        <label for="autoship_product_info_display"><?php echo __( 'Product Page Autoship Info Link', 'autoship' ); ?></label>
      </th>
      <td>
        <p><?php echo __( 'Choose how the Product Page Autoship Info Link should be displayed to your customers in the product pages.', 'autoship' ); ?></p><br/>

        <select name="autoship_product_info_display" id="autoship_product_info_display">
        	<option value="none" <?php selected( 'none', $autoship_settings['autoship_product_info_display'] );?> ><?php echo __( 'Do Not Display', 'autoship' ); ?></option>
        	<option value="tooltip" <?php selected( 'tooltip', $autoship_settings['autoship_product_info_display'] );?> ><?php echo __( 'Display as a Tooltip', 'autoship' ); ?></option>
        	<option value="modal" <?php selected( 'modal', $autoship_settings['autoship_product_info_display'] );?> ><?php echo __( 'Display as a Modal', 'autoship' ); ?></option>
        	<option value="link" <?php selected( 'link', $autoship_settings['autoship_product_info_display'] );?> ><?php echo __( 'Display as a Link', 'autoship' ); ?></option>
        </select>

        <select class="autoship_product_info_modal_size" name="autoship_product_info_modal_size" id="autoship_product_info_modal_size" style="<?php echo 'modal' != $autoship_settings['autoship_product_info_display'] && 'tooltip' != $autoship_settings['autoship_product_info_display']? 'display:none' : '';?> " >
        	<option value="small" <?php selected( 'small', $autoship_settings['autoship_product_info_modal_size'] );?> ><?php echo __( 'Small Width ( 300px )', 'autoship' ); ?></option>
        	<option value="medium" <?php selected( 'medium', $autoship_settings['autoship_product_info_modal_size'] );?> ><?php echo __( 'Medium Width ( 500px )', 'autoship' ); ?></option>
        	<option value="large" <?php selected( 'large', $autoship_settings['autoship_product_info_modal_size'] );?> ><?php echo __( 'Large Width ( 800px )', 'autoship' ); ?></option>
        	<option value="full" <?php selected( 'full', $autoship_settings['autoship_product_info_modal_size'] );?> ><?php echo __( 'Auto Width', 'autoship' ); ?></option>
        </select>

      </td>
    </tr>

    <?php

    $display = 'link' != $autoship_settings['autoship_product_info_display'] ? 'display:none' : '';

    ?>

    <tr valign="top" id="autoship_product_info_url_block" style="<?php echo $display?>" >
      <th scope="row"><?php echo __('Product Page Autoship Info Link Url', 'autoship'); ?></th>
      <td>
        <input type="text" id="autoship_product_info_url" name="autoship_product_info_url"
        value="<?php echo esc_attr( $autoship_settings['autoship_product_info_url'] ); ?>"
        placeholder="www.yoursite.com/autoship-details/" />
        <p class="help-text-wrapper"><label for="autoship_product_info_url"><?php echo __('Enter the URL to use for the Product Page Autoship Info Link.', 'autoship'); ?></label></p>
      </td>
    </tr>

    <?php

    $display = 'tooltip' != $autoship_settings['autoship_product_info_display'] ? 'display:none' : '';
    $min = apply_filters( 'autoship_dialog_info_tooltip_min_browser_width', 1024 );
    $min = !$min ? 1024 : $min;

    ?>

    <tr valign="top" id="autoship_product_info_mobile_tooltip_block" style="<?php echo $display?>" >
      <th scope="row"><?php echo __( 'Display Product Page Autoship Info Tooltip Link as Modal on Mobile', 'autoship' ); ?></th>
      <td>
        <input type="checkbox"
        id="autoship_product_info_mobile_tooltip"
        name="autoship_product_info_mobile_tooltip"
        value="yes"
        <?php echo checked( 'yes', $autoship_settings['autoship_product_info_mobile_tooltip'] ); ?>
        autocomplete="false" />
        <label for="autoship_product_info_mobile_tooltip"><?php echo __( "Enable to display tooltips as Modals on Screens under {$min}px.", 'autoship' ); ?></label>
      </td>
    </tr>

    <?php

    $display = 'none' == $autoship_settings['autoship_product_info_display'] ? 'display:none' : '';

    ?>

    <tbody id="autoship_product_info_btn_type_block" style="<?php echo $display?>">

    <tr valign="top">
      <th scope="row"><?php echo __( 'Product Page Autoship Info Link Type', 'autoship' ); ?></th>
      <td>
        <p><?php echo __( 'Choose if the Link should be displayed.', 'autoship' ); ?></p><br/>

        <label for="autoship_product_info_btn_type_icon" class="autoship_trigger" data-hide-target="#autoship_product_info_btn_text_block" >
        <input
        type="radio"
        name="autoship_product_info_btn_type"
        id="autoship_product_info_btn_type_icon"
        value="icon"
        <?php echo checked( "icon", $autoship_settings['autoship_product_info_btn_type'] ); ?>
        />
        <?php echo __( 'Icon', 'autoship' ); ?></label></br></br>

        <label for="autoship_product_info_btn_type_text" class="autoship_trigger" data-show-target="#autoship_product_info_btn_text_block">
        <input
        type="radio"
        name="autoship_product_info_btn_type"
        id="autoship_product_info_btn_type_text"
        value="text"
        <?php echo checked( "text", $autoship_settings['autoship_product_info_btn_type'] ); ?>
        />
        <?php echo __( 'Text Link', 'autoship' ); ?></label>

      </td>
    </tr>

    <?php

    $display = 'icon' == $autoship_settings['autoship_product_info_btn_type']? 'display:none' : '';

    ?>

    <tr valign="top" id="autoship_product_info_btn_text_block" style="<?php echo $display;?>" >
      <th scope="row"><?php echo __('Product Page Autoship Info Link Label', 'autoship'); ?></th>
      <td>
        <input type="text" id="autoship_product_info_btn_text" name="autoship_product_info_btn_text"
        value="<?php echo esc_attr( $autoship_settings['autoship_product_info_btn_text'] ); ?>"
        placeholder="Info" />
        <p class="help-text-wrapper"><label for="autoship_product_info_btn_text"><?php echo __('Enter the Text to use for the Product Page Autoship Info Link.', 'autoship'); ?></label></p>
      </td>
    </tr>

    </tbody>

    <?php

    $display = 'none' == $autoship_settings['autoship_product_info_display'] ? 'display:none' : '';

    ?>

    <tr valign="top" id="autoship_product_info_html_block" style="<?php echo $display;?>" >
      <th scope="row"><?php echo __( 'Product Page Autoship Info Link Content', 'autoship' ); ?></th>
      <td>

        <div class="html-editor-wrapper">

        <?php wp_editor( $autoship_settings['autoship_product_info_html'], 'autoship_product_info_html', $settings);?>

        </div>

        <p class="help-text-wrapper"><label for="autoship_product_info_html"><?php echo __( 'Use the above editor to display content in the Autoship Info Dialog. This field accepts text and HTML.', 'autoship' ); ?></label></p>

      </td>
    </tr>

    <tr valign="top">
      <th scope="row"><?php echo __( 'Product Page Autoship Message', 'autoship' ); ?></th>
      <td>

        <div class="html-editor-wrapper">

        <?php wp_editor( $autoship_settings['autoship_product_message'], 'autoship_product_message', $settings);?>

        </div>

        <p class="help-text-wrapper"><label for="autoship_product_message"><?php echo __( 'Enable to display an additional message next to Autoship options on the Product page. This field accepts text and HTML.', 'autoship' ); ?></label></p>

      </td>
    </tr>

  </table>


  <h2><?php echo __('Legacy Compatibility', 'autoship'); ?></h2>

  <table class="form-table">

    <tr valign="top">
      <th scope="row"><?php echo __( 'Enable support for requesting Product Data from QPilot.', 'autoship' ); ?></th>
      <td>
        <input type="checkbox"
        id="autoship_legacy_qpilot_products_data"
        name="autoship_legacy_qpilot_products_data"
        value="yes"
        <?php echo checked( 'yes', $autoship_settings['autoship_legacy_qpilot_products_data'] ); ?>
        autocomplete="false" />
        <label for="autoship_legacy_qpilot_products_data"><?php echo __( 'Enabling this option will set the filter <strong>autoship_filter_schedulable_products_use_wc_data</strong> to return false, so that the function retrieves product data from your connected QPilot Site instead of from WooCommerce.', 'autoship' ); ?></label>
      </td>
    </tr>

  </table>

  <h2><?php echo __('Experimental ( Beta ) Options', 'autoship'); ?></h2>

  <table class="form-table">

    <tr valign="top">
      <th scope="row"><?php echo __( '(Beta) Experimental Support for WooCommerce PayPal Payments', 'autoship' ); ?></th>
      <td>
        <input type="checkbox"
        id="autoship_support_paypal_payments"
        name="autoship_support_paypal_payments"
        value="yes"
        <?php echo checked( 'yes', $autoship_settings['autoship_support_paypal_payments'] ); ?>
        autocomplete="false" />
        <label for="autoship_support_paypal_payments"><?php echo __( 'Enable support for the WooCommerce PayPal Payments plugin (https://woocommerce.com/products/woocommerce-paypal-payments/).<strong>Important: do not enable this experimental feature on your live website unless you have already tested this integration successfully!', 'autoship' ); ?></label>
      </td>
    </tr>

    <tr valign="top">
      <th scope="row"><label for="autoship_webchat_directline_secret">(Beta) WebChat Directline Secret</label></th>
      <td>
        <input type="password" id="autoship_webchat_directline_secret" name="autoship_webchat_directline_secret"
        value="<?php echo esc_attr( $autoship_settings['autoship_webchat_directline_secret'] ); ?>"
        autocomplete="new-password" />
      </td>
    </tr>

  </table>

  <?php
  echo ob_get_clean();

}

/**
 * Generates the content for a Autoship Cloud > Settings > Utilities section.
 * @param array The current autoship Settings fields and values;
 */
function autoship_utilities_section( $autoship_settings ) {
	autoship_include_template( 'admin/utilities', array( 'autoship_settings' => $autoship_settings ) );
}

/**
 * Generates the content for a Autoship Cloud > Settings > Migrations section.
 * @param array The current autoship Settings fields and values;
 */
function autoship_migrations_section( $autoship_settings ) {
	autoship_include_template( 'admin/migrations', array( 'autoship_settings' => $autoship_settings ) );
}

/**
 * Generates the content for a Autoship Cloud > Settings > Logs section.
 * @param array The current autoship Settings fields and values;
 */
function autoship_logs_section( $autoship_settings ) {
	autoship_include_template( 'admin/logs', array( 'autoship_settings' => $autoship_settings ) );
}

// ==========================================================
// Autoship Cloud Custom Extension Management Functions
// ==========================================================

/**
 * Retrieves the Current List of Extensions
 * @param array The current autoship Settings fields and values;
 */
function autoship_get_custom_extensions( $autoship_settings = array() ){
  return apply_filters('autoship_plugin_custom_extensions', array(), $autoship_settings );
}

/**
 * Generates the content for a Autoship Extensions Settings page section.
 * @param array The current autoship Settings fields and values;
 */
function autoship_extensions_section ( $autoship_settings ){

  /*
  * Extensions should be added to the extension list by plugin file name ( with or without the php extension )
  * i.e. 'autoship'
  */
  $extensions = autoship_get_custom_extensions( $autoship_settings );

  // Get the current active plugins.
  $active = get_option('active_plugins');

  $active_plugins = array();

  foreach ($active as $key => $value)
  $active_plugins[wp_basename( $value )] = $value;

  $subtitle = count( $extensions ) . ' Active Extension(s)';

  ob_start();?>

  <h2><?=__('Autoship Extensions' , 'autoship')?></h2>

  <div class="section">
    <p><?php echo __('Autoship extensions are active plugins that have been developed and added to your WordPress Plugins directory in order to extend, modify, and customize your Autoship Cloud and QPilot integration.  These plugins are not reviewed, controlled, or monitored by Patterns In the Cloud LLC and can be developed by third parties.', 'autoship'); ?></p>
  </div>

  <h4><?=__( $subtitle , 'autoship')?></h4>

  <?php foreach ( $extensions as $extension ) {

    $extension_name = '.php' == substr( $extension, -4) ?
    substr( $extension, -4) : $extension;

    if ( !isset( $active_plugins[$extension_name . '.php'] ))
    continue;

    $plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . $active_plugins[$extension_name . '.php' ] );

    $plugin_meta = array();

    if ( ! empty( $plugin_data['Version'] ) ) {
			/* translators: %s: plugin version number */
			$plugin_meta[] = sprintf( __( 'Version %s' ), $plugin_data['Version'] );
		}
		if ( ! empty( $plugin_data['Author'] ) ) {
			$author = $plugin_data['Author'];
			if ( ! empty( $plugin_data['AuthorURI'] ) ) {
				$author = '<a href="' . $plugin_data['AuthorURI'] . '">' . $plugin_data['Author'] . '</a>';
			}
			/* translators: %s: plugin version number */
			$plugin_meta[] = sprintf( __( 'By %s' ), $author );
		}

		// Details link using API info, if available
		if ( isset( $plugin_data['slug'] ) && current_user_can( 'install_plugins' ) ) {
			$plugin_meta[] = sprintf(
				'<a href="%s" class="thickbox open-plugin-details-modal" aria-label="%s" data-title="%s">%s</a>',
				esc_url(
					network_admin_url(
						'plugin-install.php?tab=plugin-information&plugin=' . $plugin_data['slug'] .
						'&TB_iframe=true&width=600&height=550'
					)
				),
				/* translators: %s: plugin name */
				esc_attr( sprintf( __( 'More information about %s' ), $plugin_data['name'] ) ),
				esc_attr( $plugin_data['name'] ),
				__( 'View details' )
			);
		} elseif ( ! empty( $plugin_data['PluginURI'] ) ) {
			$plugin_meta[] = sprintf(
				'<a href="%s">%s</a>',
				esc_url( $plugin_data['PluginURI'] ),
				__( 'Visit plugin site' )
			);
		}

		/**
		 * Filters the array of row meta for each plugin in the Autoship Extension Plugins list table.
		 *
		 * @param string[] $plugin_meta An array of the plugin's metadata,
		 *                              including the version, author,
		 *                              author URI, and plugin URI.
		 * @param array    $plugin_data An array of plugin data.
		 */
		$plugin_meta = apply_filters( 'autoship_plugin_row_meta', $plugin_meta, $plugin_data );

  ?>

    <div class="discriptor">
      <div class="title"><?php echo $plugin_data['Name']; ?></div>
      <div class="description">
        <p><?php echo $plugin_data['Description']; ?></p>
        <div class="additional-content"><?php do_action("autoship_{$extension_name}_plugin_row_additional_content", $extension_name, $plugin_meta, $plugin_data ); ?></div>
        <div class="plugin-version-author-uri"><?php echo implode( ' | ', $plugin_meta );?></div>
      </div>
    </div>

  <?php } ?>

  <?php
  echo ob_get_clean();

}

// ==========================================================
// Autoship Cloud Non-Settings Page Content Functions
// ==========================================================

/**
 * Generates the content for a Autoship Cloud > Products page.
 */
function autoship_products_page() {
	$token_auth = autoship_get_token_auth();
	if ( ! empty( $token_auth ) ) {
		$args = array(
			'token_auth' => $token_auth,
			'site_id' => autoship_get_site_id()
		);
		autoship_include_template( 'admin/products', $args );
	} else {
		autoship_include_template( 'admin/no-token' );
	}
}

/**
 * Generates the content for a Autoship Cloud > Coupons page.
 */
function autoship_coupons_page() {
	$token_auth = autoship_get_token_auth();
	if ( ! empty( $token_auth ) ) {
		$args = array(
			'token_auth' => $token_auth,
			'site_id' => autoship_get_site_id()
		);
		autoship_include_template( 'admin/coupons', $args );
	} else {
		autoship_include_template( 'admin/no-token' );
	}
}

/**
 * Generates the content for a Autoship Cloud > Customers page.
 */
function autoship_customers_page() {
    $token_auth = autoship_get_token_auth();
    if ( ! empty( $token_auth ) ) {
        $args = array(
            'token_auth' => $token_auth,
            'site_id' => autoship_get_site_id()
        );
        autoship_include_template( 'admin/customers', $args );
    } else {
        autoship_include_template( 'admin/no-token' );
    }
}

/**
 * Generates the content for a Autoship Cloud > Orders page.
 */
function autoship_scheduled_orders_page() {
	$token_auth = autoship_get_token_auth();
	if ( ! empty( $token_auth ) ) {
		$args = array(
			'token_auth' => $token_auth,
			'site_id' => autoship_get_site_id()
		);
		autoship_include_template( 'admin/scheduled-orders', $args );
	} else {
		autoship_include_template( 'admin/no-token' );
	}
}

/**
 * Generates the content for a Autoship Cloud > Shipping Rates page.
 */
function autoship_shipping_rates_page() {
	$token_auth = autoship_get_token_auth();
	if ( ! empty( $token_auth ) ) {
		$args = array(
			'token_auth' => $token_auth,
			'site_id' => autoship_get_site_id()
		);
		autoship_include_template( 'admin/shipping-rates', $args );
	} else {
		autoship_include_template( 'admin/no-token' );
	}
}

/**
 * Generates the content for a Autoship Cloud > Tax Rates page.
 */
function autoship_tax_rates_page() {
	$token_auth = autoship_get_token_auth();
	if ( ! empty( $token_auth ) ) {
		$args = array(
			'token_auth' => $token_auth,
			'site_id' => autoship_get_site_id()
		);
		autoship_include_template( 'admin/tax-rates', $args );
	} else {
		autoship_include_template( 'admin/no-token' );
	}
}

/**
 * Generates the content for a Autoship Cloud > Integrations page.
 */
function autoship_payment_integrations_page() {
	$token_auth = autoship_get_token_auth();
	if ( ! empty( $token_auth ) ) {
		$args = array(
			'token_auth' => $token_auth,
			'site_id' => autoship_get_site_id()
		);
		autoship_include_template( 'admin/payment-integrations', $args );
	} else {
		autoship_include_template( 'admin/no-token' );
	}
}

/**
 * Generates the content for a Autoship Cloud > Reports page.
 */
function autoship_reports_page() {
    $token_auth = autoship_get_token_auth();
    if ( ! empty( $token_auth ) ) {
        $args = array(
            'token_auth' => $token_auth,
            'site_id' => autoship_get_site_id()
        );
        autoship_include_template( 'admin/reports', $args );
    } else {
        autoship_include_template( 'admin/no-token' );
    }
}

/**
 * Generates the content for a Autoship Cloud > Dashboard page.
 */
function autoship_dashboard_page() {
	$token_auth = autoship_get_token_auth();
	if ( ! empty( $token_auth ) ) {
		$args = array(
			'token_auth' => $token_auth,
			'site_id' => autoship_get_site_id()
		);
		autoship_include_template( 'admin/dashboard', $args );
	} else {
		autoship_include_template( 'admin/no-token' );
	}
}

// ==========================================================
// Autoship Cloud Non-Settings Page Display Functions
// ==========================================================

/**
 * The version checks for major upgrades
 * @param string $version The version to which was just upgraded to.
 */
function autoship_display_invalid_products_panel(){

  if( !autoship_check_site_settings_for_invalid_products() )
  return;

  ?>

  <div class="autoship_general_admin_notice health-error is-dismissible">
    <?php echo sprintf( __( '<h2>Autoship Cloud Invalid Products Notice</h2><hr/><p style="max-width: 880px;">1 or more WooCommerce Products enabled for Autoship have become invalid.  This may be caused by the deletion of the product(s) in WooCommerce, or a change to the product Id(s) in your WordPress Database.<br/><br/>Please review the products sync’d with Autoship Cloud below and enable the <strong>“Invalid”</strong> filter to see which product(s) have become invalid.<br/><br/>You can use the “Update Product Synchronization” button to re-check the status of all of your products.</p><a class="button button-primary" href="%s">Update Product Synchronization</a>'), esc_attr( admin_url( '/admin-ajax.php?action=autoship_retest_invalid_products' ) ) ); ?>
  </div>

  <?php
}
add_action( 'autoship_before_autoship_admin_products', 'autoship_display_invalid_products_panel', 99 );

// ==========================================================
// Oauth Connection Functions
// ==========================================================

/**
 * Connects to the QPilot API to retrieve the connection information
 * Checks for the code returned from QPilot
 *
 * {@see autoship_qpilot_statuscheck_routes()}
 * Prior to this the following checks are run by QPilot.
 * - A GET request it sent to the WC REST API to retrieve a product.
 * - ( new ) A PUT request is sent to the autoship statuscheck endpoint
 * - { new } A POST request is sent tp the autoship statuscheck endpoint
 *
 */
function autoship_oauth2() {

  // Confirm the current user has rights.
  if ( ! current_user_can( 'manage_woocommerce' ) ) {
    autoship_ajax_result( 403 );
    die();
  }

  // Check for a return code from QPilot
  if ( !isset( $_REQUEST['code'] ) || empty( $_REQUEST['code'] ) ) {

    $msg = __( 'No response code was received from QPilot.', 'autoship' );
  	autoship_notice_handler( 'add_token_error' ,  $msg );
    autoship_log_entry( __( 'Autoship Oauth Error', 'autoship' ), $msg );

    wp_redirect( admin_url( '/admin.php?page=autoship' ) );
    die();

  }

  // Gather the returned code.
  $code   = $_REQUEST['code'];

  // Create the QPilot Client
  $client = new QPilotClient();

  try {

    $token_response = $client->oauth2( $code );

    // Update the Settinsg based on the QPilot Endpoint response
    update_option( 'autoship_token_auth', $token_response->access_token );
    update_option( 'autoship_user_id', $token_response->user_id );
    update_option( 'autoship_refresh_token', $token_response->refresh_token );
    update_option( 'autoship_token_expires_in', $token_response->expires_in );

    // Init the creation time - used for checking expired tokens
    $token_created_at = time();
    update_option( 'autoship_token_created_at', $token_created_at );

    // Now since we have a QPilot token lets create the WC API keys and Site in QPilot
    autoship_oauth2_connect_site();

    // Connecting Tests the ability to POST to the QPilot endpoint but not
    // QPilot connecting with this Autoship instance
    // Automatically run the Test Connection functionality
    if ( 'healthy' == autoship_init_integration_test() ){

      // Finally lets upsert the site info
      autoship_push_site_metadata( autoship_qpilot_get_sitemeta() );

    }

  } catch ( Exception $e ) {

    // If an error occurs here is's due to issues POSTing to QPilot.
    autoship_notice_handler( 'qpilot_post_denied', $e->getMessage() );
    autoship_log_entry( __( 'Autoship Oauth Exception', 'autoship' ), sprintf( 'An %s Exception Occurred when attempting Oauth connection to QPilot. Additional Details: %s', $e->getCode(), $e->getMessage() ) );

  }

  wp_redirect( admin_url( '/admin.php?page=autoship' ) );
  die();
}
add_action( 'wp_ajax_autoship_oauth2', 'autoship_oauth2' );

/**
 * Creates a Site in QPilot using the current blog info, and generated API Keys.
 *
 * @param int $user_id  Optional. The WP User's id.
 * @return stdClass The created site object.
 */
function autoship_oauth2_connect_site( $user_id = NULL ) {

  if ( !isset( $user_id ) || !$user_id )
  $user_id = get_current_user_id();

  // Create API keys
  $api_keys = autoship_oauth2_create_wc_api_keys( $user_id );

  $client = new QPilotClient();

  $site = NULL;

  try {

    // Create site
    $site = $client->create_site( $api_keys['consumer_key'], $api_keys['consumer_secret'] );

    update_option( 'autoship_site_id', $site->id );
    do_action( 'autoship_api_site_created', $site );

  } catch ( Exception $e ) {

    // If an error occurs here is's due to issues POSTing to QPilot.
    autoship_notice_handler( 'qpilot_post_denied', $e->getMessage() );
    autoship_log_entry( __( 'Autoship Oauth Exception', 'autoship' ), sprintf( 'An %s Exception Occurred when attempting to update the Site Connection in QPilot. Additional Details: %s', $e->getCode(), $e->getMessage() ) );

  }

  return $site;
}

/**
 * Creates the WC API Keys.
 *
 * @param int $user_id  Optional. The WP User's id.
 * @return array An array containing the Consumer Key and Secret
 */
function autoship_oauth2_create_wc_api_keys( $user_id = NULL ) {

  if ( !isset( $user_id ) || !$user_id )
  $user_id = get_current_user_id();

  global $wpdb;

	$description     = __( 'Autoship - QPilot', 'autoship' );
	$consumer_key    = 'ck_' . wc_rand_hash();
	$consumer_secret = 'cs_' . wc_rand_hash();
	$permissions     = 'read_write';

	$data = array(
		'user_id'         => $user_id,
		'description'     => $description,
		'permissions'     => $permissions,
		'consumer_key'    => wc_api_hash( $consumer_key ),
		'consumer_secret' => $consumer_secret,
		'truncated_key'   => substr( $consumer_key, -7 ),
	);

	$wpdb->insert(
		$wpdb->prefix . 'woocommerce_api_keys',
		$data,
		array(
			'%d',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
		)
	);

  // Now save the user who generated the keys
  update_option( 'autoship_api_keys_author', $user_id );

	return array(
    'consumer_key'    => $consumer_key,
    'consumer_secret' => $consumer_secret
  );
}

/**
 * Disconnects the Integration with QPilot
 * Removes and Deletes the settings.
 */
function autoship_oauth2_disconnect() {

	if ( ! current_user_can( 'manage_woocommerce' ) ) {
		autoship_ajax_result( 403 );
		die();
	}

  autoship_clear_integration_point_statuses ( true );

	delete_option( 'autoship_token_auth' );
	delete_option( 'autoship_user_id' );
	delete_option( 'autoship_site_id' );
	delete_option( 'autoship_refresh_token' );
	delete_option( 'autoship_token_expires_in' );
	delete_option( 'autoship_token_created_at' );

	autoship_notice_handler('disconnected');
  autoship_log_entry( __( 'Autoship Oauth Status', 'autoship' ), __( 'Autoship Cloud connection has been disconnected!', 'autoship' ) );

	wp_redirect( admin_url( '/admin.php?page=autoship' ) );
	die();
}
add_action( 'wp_ajax_autoship_oauth2_disconnect', 'autoship_oauth2_disconnect' );

/**
 * Adds the Token Error Default Message to the notices
 */
function autoship_oauth_add_token_error_help_message() {
	autoship_notice_handler( 'add_token_error' );
}
