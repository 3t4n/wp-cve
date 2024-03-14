<?php
/*
Plugin Name: Integrate GA4 Google Analytics
Plugin URI: http://middleearmedia.com
Description: A simple, lightweight plugin to easily integrate Google Analytics GA4 tracking into your WordPress site.
Author: Obadiah Metivier
Author URI: http://middleearmedia.com/
Version: 1.2
 */

// Abort if this file is called directly 
if ( ! defined( 'WPINC' ) ) {
     die;
}

// Register the plugin settings.
function iga_register_settings() {
  add_settings_section( 'iga_main', 'Connect to Google Analytics', 'iga_render_main_section', 'integrate-ga-analytics' );
  add_settings_field( 'iga_gtag_id', 'GA4 Measurement ID:', 'iga_render_gtag_id_field', 'integrate-ga-analytics', 'iga_main' );  
  register_setting( 'iga_settings', 'iga_gtag_id', 'iga_validate_gtag_id', 'iga_sanitize_gtag_id' );
}
add_action( 'admin_init', 'iga_register_settings' );

// Enforce capability checks and nonces
function iga_enforce_capability_checks_and_nonces() {
  if ( !current_user_can( 'manage_options' ) ) {
    return;
  }
  if ( !isset( $_POST['iga_nonce']) || !wp_verify_nonce( $_POST['iga_nonce'], 'iga_settings' ) ) {
    return;
  }
  if ( !isset( $_POST['option_page'] ) || $_POST['option_page'] !== 'iga_settings' ) {
    return;
  }
  if ( !isset( $_POST['action'] ) || $_POST['action'] !== 'update' ) {
    return;
  }
  if ( !isset( $_POST['iga_gtag_id'] ) ) {
    return;
  }
}
add_action( 'admin_init', 'iga_enforce_capability_checks_and_nonces' );

// Add a settings page to the WordPress admin menu
function iga_add_settings_page() {
  add_options_page( 
    'Integrate GA4 Google Analytics Settings',
    'Integrate GA4 Google Analytics',
    'manage_options',
    'integrate-ga-analytics',
    'iga_render_settings_page'
  );
}
add_action( 'admin_menu', 'iga_add_settings_page' );

// Add a settings link to the plugins page
function iga_add_settings_link( $links ) {
  $settings_link = '<a href="' . esc_url( admin_url( 'options-general.php?page=integrate-ga-analytics' ) ) . '">' . __( 'Settings' ) . '</a>';
  array_push( $links, $settings_link );
  return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'iga_add_settings_link' );

// Render the settings page
function iga_render_settings_page() {
  ?>
  <div class="wrap">
    <h1>Integrate GA4 Google Analytics Settings</h1>
	<h4>A simple plugin to integrate Google Analytics GA4 tracking into your WordPress site.</h4>
    <form method="post" action="options.php">
      <?php wp_nonce_field( 'iga_settings', 'iga_nonce' ); ?>
      <?php settings_fields( 'iga_settings' ); ?>
      <?php do_settings_sections( 'integrate-ga-analytics' ); ?>
      <?php submit_button( 'Save Changes' ); ?>
    </form>
	<hr>
	<h3>How to find your Google Analytics GA4 Measurement ID</h3>
	<p>Your Google Analytics GA4 Measurement ID can be found by logging into your Google account.</p>
	<ol>
	     <li>Go to Admin Panel in Google Analytics 4.</li>
	     <li>Select the property that you want to get the Measurement ID for.</li>
	     <li>Click on "Data Stream", then click on the Data Stream name.</li>
	     <li>On the next screen, in the top right corner, you will find the Measurement ID that starts with G-</li>
	</ol>



  </div>
  <?php
}

// Render the main settings section
function iga_render_main_section() {
  echo '
	<p>Enter your Google Analytics GA4 Measurement ID below:</p>
	<p><strong>NOTE:</strong> <em>Do not enter the entire tracking code script. Only the ID, which looks like this: G-XXXXXXXXXX</em></p>
  ';
}

// Sanitize the measurement ID field
function iga_sanitize_gtag_id( $input ) {
  $measurement_id = sanitize_text_field( $input );
  update_option( 'iga_gtag_id', ( $measurement_id ) );
}

// Validate the measurement ID field
function iga_validate_gtag_id( $input ) {
  $measurement_id = trim( $input );
  if ( empty( $measurement_id ) ) {
    add_settings_error( 'iga_gtag_id', 'empty_measurement_id', 'Measurement ID is required.', 'error' );
	return'';
  } elseif ( !preg_match('/^G-[a-zA-Z0-9]+$/', $measurement_id ) ) {
    add_settings_error( 'iga_gtag_id', 'invalid_measurement_id', 'Measurement ID must begin with G- followed by a string of letters and numbers.', 'error' );
	return'';
  }
  return $measurement_id;
}

// Render the measurement ID field
function iga_render_gtag_id_field() {
  $measurement_id = get_option( 'iga_gtag_id' );
  echo '<input type="text" name="iga_gtag_id" value="' . esc_attr( $measurement_id ) . '" />';
  get_settings_errors( 'iga_gtag_id' );
}

// Insert Google Analytics GA4 script into the footer with user's Measurement ID, if it's not empty and is formatted correctly.
function iga_insert_tracking_script() {
  if ( !is_admin() ) {
    $measurement_id = get_option( 'iga_gtag_id' );
    if ( !empty( $measurement_id ) && preg_match( '/^G-[a-zA-Z0-9]+$/', $measurement_id ) ) {
      wp_enqueue_script( 'ga4-tracking', 'https://www.googletagmanager.com/gtag/js?id=' . esc_attr( $measurement_id ), array(), null, true );
      wp_add_inline_script( 'ga4-tracking', 'window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push( arguments) ;} gtag( \'js\', new Date() ); gtag( \'config\', \'' . esc_attr( $measurement_id ) . '\' );' );
    }
  }
}
add_action( 'wp_enqueue_scripts', 'iga_insert_tracking_script' );

// Plugin deactivation
function iga_deactivation() {
    // Code to be executed when the plugin is deactivated
}
register_deactivation_hook( __FILE__, 'iga_deactivation' );

// Plugin uninstall
function iga_uninstall() {
  // Code to be executed when the plugin is deleted
  // Remove options
  delete_option( 'iga_gtag_id' );
}
register_uninstall_hook( __FILE__, 'iga_uninstall' );