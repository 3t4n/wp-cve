<?php

/**
* Makes the call to convert the Site from v2 to v3 Processing
*/
function autoship_convert_site_processing( $version = 'v3' ){

  // Upgrades a Sites Processing Version
  if ( 'v3' == $version ){

    $client = autoship_get_default_client();

    try {

      $migrate = $client->migrate_processing_version();

    } catch ( Exception $e ) {
      $notice = autoship_expand_http_code( $e->getCode() );

      $migrate  = autoship_is_user_http_message( $e->getCode() ) ?
      new WP_Error( $notice['msg'], $notice['desc'] ) : new WP_Error( 'Site Processing Version Migration Failed', __( $notice['desc'], "autoship" ) );

      autoship_log_entry( __( 'Autoship Upgrade', 'autoship' ), sprintf( '%d Upgrade to Order Processing in Qpilot Failed. Additional Details: %s', $e->getCode(), $e->getMessage() ) );

      return $migrate;

    }

    // Set the processing version.
    autoship_set_saved_site_processing_version( 'v3' );
    return true;

  // Support for downgrades
  } else if ( 'v2' == $version ){

    // Set the processing version.
    autoship_set_saved_site_processing_version( 'v2' );
    return true;

  }

}

/**
* Checks if a site needs to be upgraded
* @param
*/
function autoship_maybe_convert_site_processing( $site ){

  if ( 'vX' == autoship_get_saved_site_processing_version() )
  autoship_convert_site_processing( 'v3' );

}
add_action( 'autoship_api_site_created', 'autoship_maybe_convert_site_processing', 1 );

/**
* Checks if the install has the original WooCommerce Autoship tables.
*/
function autoship_has_legacy_origin(){

  global $wpdb;

  $table_name = $wpdb->prefix.'wc_autoship_schedules';
  $legacy = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name;
  return $legacy;

}

/**
 * The version checks for upgrades, installs and changes.
 * Hooked into admin_init for all types of updates.
 */
function autoship_version_trigger(){

  // Check for custom transition version key
	$versions = get_option( '_autoship_transition_version' );
	$existing = get_option( 'autoship_site_id' );

  // If empty check for existing post meta to see if this is a new
  // or existing setup.
  if ( false === $versions || empty( $versions ) ){
    // We're a < 1.2.32 version change
    if ( false === $existing ){
      //We're a new install
      do_action( 'autoship_new_install_completed', Autoship_Version );
    } else {
      //We're an existing upgrade from pre
      do_action( 'autoship_upgrade_from_null_to_', Autoship_Version );
    }
    // Set transition version.
    update_option( '_autoship_transition_version', Autoship_Version . '_' . Autoship_Version );

  } else {
    // Existing user
    $current_version = explode( '_', $versions);
    if ( version_compare( $current_version[1], Autoship_Version, '<' ) ) {
      //We're an upgrade
      update_option( '_autoship_transition_version', $current_version[1] . '_' . Autoship_Version );
      do_action( "autoship_upgrade_from_{$current_version[1]}_to_", Autoship_Version );
      do_action( 'autoship_upgrade_completed', $current_version[1], Autoship_Version );
    }

  }

}
add_action( 'admin_init', 'autoship_version_trigger' );

/**
 * The legacy upgrade handler.
 * @param string $version The version to which was just upgraded to.
 * @param bool $init_global True to set the global sync option.
 */
function autoship_legacy_version_trigger_handler( $version, $init_global = true ){

  if ( $init_global )
  autoship_set_global_sync_active_enabled('yes');

  // Get the upgrade link
  $link = add_query_arg( 'autoship_activate_sync_upgrade','upgrade', autoship_admin_settings_tab_url( 'autoship-utilities' ) );

  set_transient( 'autoship_sync_upgrade_activated',
  sprintf(
    __( '<h2>Autoship Cloud Upgrade Notice</h2><hr/><p style="max-width: 880px;"><strong>Important Action Required:</strong> You\'ve upgraded to a version of Autoship Cloud powered by QPilot that requires an update to your WooCommerce Product Data to fully utilize the latest enhancements to Product data and synchronization.</p><p><a class="button button-primary" href="%s">%s</a></p>'),
    $link , __( 'Update Product Synchronization', 'autoship') )
  );
}
add_action( 'autoship_upgrade_from_null_to_', 'autoship_legacy_version_trigger_handler', 10, 1);

/**
 * Toggle legacy upgrade handler when Global Option switched..
 * @param string $enabled The global value.
 */
function autoship_legacy_version_trigger_toggler( $enabled ){

  if ( 'no' === $enabled ){
    delete_transient( 'autoship_sync_upgrade_activated' );
  } else {
    autoship_legacy_version_trigger_handler( Autoship_Version, false );
  }

}
add_action( 'autoship_set_global_sync_active_enabled', 'autoship_legacy_version_trigger_toggler', 10, 1 );

/**
 * The new install handler.
 * @param string $version The version to which was just upgraded to.
 */
function autoship_new_install_trigger_handler( $version ){
  autoship_set_global_sync_active_enabled('no');
}
add_action( 'autoship_new_install_completed', 'autoship_new_install_trigger_handler', 10, 1);

/**
 * The new install handler that sets the processing version for brand new sites.
 * @param string $version The version to which was just upgraded to.
 */
function autoship_new_install_set_processing_version( $version ){
  autoship_set_saved_site_processing_version( 'vX');
}
add_action( 'autoship_new_install_completed', 'autoship_new_install_set_processing_version', 11, 1);

/**
 * The version checks for major upgrades
 * @param string $version The version to which was just upgraded to.
 */
function autoship_legacy_version_notice_trigger(){

  $notice = get_transient('autoship_sync_upgrade_activated');
  if ( ( false === $notice ) || isset( $_GET['autoship_activate_sync_upgrade'] ) )
  return;

  ?>

  <div class="autoship-admin-notice notice autoship-action-notice is-dismissible">
    <?php echo $notice; ?>
  </div>

  <?php
}
add_action( 'admin_notices', 'autoship_legacy_version_notice_trigger', 99 );

/**
 * Displays the Notice for Processing Version Upgrades
 */
function autoship_legacy_processing_version_notice_trigger(){

  $processing_version = autoship_get_saved_site_processing_version();
  if ( ( 'vX' == $processing_version ) || ( 'v3' == $processing_version ) || isset( $_GET['autoship_activate_legacy_processing_upgrade'] ) || autoship_is_new() )
  return;

  // Get the upgrade link
  $link = add_query_arg( 'autoship_activate_legacy_processing_upgrade','upgrade', autoship_admin_settings_tab_url( 'autoship-utilities' ) );

  $notice = sprintf( __( '<h2>Important: Update to Autoship Scheduled Orders Required!</h2><hr/><p style="max-width: 880px;">Action Required: Your new version of Autoship Cloud requires an upgrade to your existing Scheduled Orders in order to continue processing Scheduled Orders successfully.</p><p>Using the “Upgrade Processing Version” will update the Date and Time format of your existing Scheduled Orders.  After running this update, you should not roll back or revert to a previous version of Autoship Cloud.</p><p><a class="button button-primary" href="%s">%s</a></p>'),
    $link , __( 'Upgrade Processing Version', 'autoship') );

  ?>

  <div class="autoship-admin-notice notice autoship-action-notice is-dismissible">
    <?php echo $notice; ?>
  </div>

  <?php
}
add_action( 'admin_notices', 'autoship_legacy_processing_version_notice_trigger', 99 );

/**
 * Displays the warning notice that PayPal Payments isn't supported.
 *
 * @param string $plugin_file Path to the plugin file relative to the plugins directory.
 * @param array $plugin_data An array of plugin data.
 * @param string $status Status filter currently applied to the plugin list.
 */
function autoship_ppc_gateway_upgrade_warning_notice($plugin_file, $plugin_data, $status ) {

  // Only display notice if the version of Papal Checkout displays the Payments notice
  if ( !isset( $plugin_data['Version'] ) ||
        version_compare( $plugin_data['Version'], '2.1.2', '<') ||
       !isset( $plugin_data['plugin'] ) ||
       !is_plugin_active( $plugin_data['plugin'] ) )
       return;

  ?>
  <tr class="plugin-update-tr active notice-error notice-alt"  id="autoship-ppec-migrate-notice-warning">
  	<td colspan="4" class="plugin-update colspanchange">
  		<div class="update-message notice inline notice-error notice-alt">
  			<div class='autoship-ppec-migrate-notice-title autoship-ppec-migrate-notice-section'>
  				<p><?php echo __( '<strong>IMPORTANT!</strong> The new PayPal Payments plugin is not currently supported by Autoship Cloud.  Please do not attempt to replace WooCommerce PayPal Checkout Gateway with WooCommerce PayPal Payments.', 'autoship');?></p>
  			</div>
      </div>
  	</td>
  </tr>
  <?php
}
add_action( 'after_plugin_row_woocommerce-gateway-paypal-express-checkout/woocommerce-gateway-paypal-express-checkout.php', 'autoship_ppc_gateway_upgrade_warning_notice', 9, 3 );

/* Endpoint Handler Wrappers
====================================== */

/**
 * Upgrades a Sites Processing Version
 */
function autoship_upgrade_site_processing_version_endpoint_wrapper (){

  // Check if this is our upgrade action & if not bail
  if ( !isset( $_POST['autoship-action'] ) || !isset( $_POST['upgrade_processing'] )  )
  return;

  // Retrieve & validate the Nonce
	$nonce_value = wc_get_var( $_POST['autoship-activate-legacy-processing-upgrade-nonce'], wc_get_var( $_POST['_wpnonce'], '' ) );
	if ( ! wp_verify_nonce( $nonce_value, 'autoship-activate-legacy-processing-upgrade' ) ){

    autoship_add_message( __( '<strong>Invalid or Expired Call.</strong>', 'autoship' ), 'notice-error', 'autoship_upgrade' );
    $success = false;

  // Validate that the user confirmation check box is checked
  } else if ( !isset( $_POST['autoship_upgrade_site_processing_version'] ) || 'confirm' != $_POST['autoship_upgrade_site_processing_version'] ){

    autoship_add_message( __( '<strong>Invalid Upgrade Submission. Please confirm you would like to upgrade processing to v3 and submit your request again.</strong>', 'autoship' ), 'notice-error', 'autoship_upgrade' );
    $success = false;

  // All checks out so let's upgrade to the new processing version
  } else {

    $success = autoship_convert_site_processing();

    // Check for any errors upgrading
    if ( is_wp_error( $success ) ){

      autoship_add_message( sprintf( __( '<strong>Error Upgrading Processing Version:</strong> Error Code %s - %s', 'autoship' ), $success->get_error_code(), $success->get_error_message() ), 'notice-error', 'autoship_upgrade' );
      $success = false;

    } else {

      autoship_add_message( __( '<strong>Autoship Cloud: Scheduled Orders Upgraded Successfully!</strong> You have completed the upgrade to the latest version of Autoship Cloud.', 'autoship' ),  'notice-success', 'autoship_upgrade' );

      // Now Refresh any saved site setting values
      autoship_get_remote_saved_site_settings( true );

    }

  }

  // Get the redirect, if errors send back to upgrade page else to site setting spage
  $link = $success ? autoship_admin_settings_tab_url( 'autoship-connection-settings' ) : add_query_arg( 'autoship_activate_legacy_processing_upgrade', 'error', autoship_admin_settings_tab_url( 'autoship-utilities' ) );

	wp_safe_redirect( $link );
	exit();

}
add_action( 'admin_init', 'autoship_upgrade_site_processing_version_endpoint_wrapper', 20 );

/**
 * Downgrades a sites Processing Version
 */
function autoship_downgrade_site_processing_version_endpoint_wrapper (){

  // Check if this is our downgrade action & if not bail or if the user doesn't have rights
  if ( !isset( $_GET['autoship-action'] ) || !autoship_rights_checker( 'autoship_admin_downgrade_processing_version', array('administrator') ) || !isset( $_GET['autoship_downgrade_site_processing_version'] ) || 'confirm' != $_GET['autoship_downgrade_site_processing_version'] )
  return;

  // All checks out so let's downgrade to the legacy processing version
  $success = autoship_convert_site_processing( 'v2' );

  // Check for any errors upgrading
  if ( is_wp_error( $success ) ){

    autoship_add_message( sprintf( __( '<strong>Error Downgrading Processing Version:</strong> Error Code %s - %s', 'autoship' ), $success->get_error_code(), $success->get_error_message() ), 'notice-error', 'autoship_downgrade' );


  } else {

    autoship_add_message( __( '<strong>Autoship Cloud: Plugin Processing Version Downgraded Successfully!</strong>.', 'autoship' ),  'notice-success', 'autoship_downgrade' );

  }

	wp_safe_redirect( autoship_admin_settings_tab_url( 'autoship-connection-settings' ) );
	exit();

}
add_action( 'admin_init', 'autoship_downgrade_site_processing_version_endpoint_wrapper', 20 );

/**
 * Display Stored Notices on Orders screen
 *
 * @param  array $messages Array of messages.
 * @return array
 */
function autoship_print_upgrade_notices() {
  autoship_print_messages( 'autoship_upgrade' );
}
add_action( 'admin_notices', 'autoship_print_upgrade_notices' );
