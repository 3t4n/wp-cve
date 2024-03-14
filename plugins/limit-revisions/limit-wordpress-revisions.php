<?php
/**
* Plugin Name: Limit Revisions
* Plugin URI: https://carlosmr.com/plugin/limitar-las-revisiones-en-wordpress/
* Description: This plugin creates a new setting at the end of Settings > General that allows to select limit of revisions that WordPress stores in the database.
* Version: 1.0.0
* Author: Carlos Mart&iacute;nez Romero
* Author URI: https://carlosmr.com
* License: GPL+2
* Text Domain: limit-revisions
* Domain Path: /languages
*/
// Load translations
add_action( 'plugins_loaded', 'cmr_lwr_load_textdomain' );
function cmr_lwr_load_textdomain(){
  load_plugin_textdomain( 'limit-wordpress-revisions', false, dirname( plugin_basename(__FILE__)).'/languages' );
}
// Starts the plugin
add_action( 'admin_init', 'cmr_lwr_init' );
add_action( 'plugins_loaded', 'cmr_lwr_execute' );
function cmr_lwr_init(){
  // Section record
  register_setting( 'general', 'cmr_lwr_settings', 'cmr_lwr_sanitize_validate_settings' );
  // Adding the fields
  $settings = get_option('cmr_lwr_settings');
  add_settings_field( 'cmr_lwr_field_one', esc_html__('Limit of revisions'), 'cmr_lwr_fields_callback', 'general', 'default', array(
    'name' => 'cmr_lwr_settings[one]',
    'value' => $settings['one']
  ) );
}
// Checking and validating the fields
function cmr_lwr_sanitize_validate_settings( $input ){
  $output = get_option( 'cmr_lwr_settings' );
  // Sanitizing the number
  $output['one'] = absint( $input['one'] );
  return $output;
}
// Field load
function cmr_lwr_fields_callback( $args ){
  echo '<input type="text" name="'.esc_attr( $args['name'] ).'" value="'.esc_attr( $args['value'] ).'">';
  echo '</br>';
  echo '<p class="description">' . __('Keep in mind that this plugin wont work if the number is already defined elsewhere.', 'limit-wordpress-revisions' ) . '</p>';
}
function cmr_lwr_execute(){
  // Getting the value
  $settings = get_option( 'cmr_lwr_settings' );
  // Getting first value of the array
  if (isset($settings['one'])){
    if ( !defined( 'WP_POST_REVISIONS' ) ){
      $cmrrevisionlimit = $settings['one'];
      // Definition of value
      define('WP_POST_REVISIONS', $cmrrevisionlimit );
    }
    else{
    }
  }
}