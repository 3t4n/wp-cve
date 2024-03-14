<?php
/**
* Plugin Name: Remove Web Field From Comments Form
* Plugin URI: https://carlosmr.com/plugin/eliminar-web-en-comentarios/
* Description: This plugin creates a new setting at the end of Settings > General that allows to check if you want to remove the web field from the comments form.
* Version: 1.0.1
* Author: Carlos Mart&iacute;nez Romero
* Author URI: https://carlosmr.com
* License: GPL+2
* Text Domain: remove-web-field-from-comments-form
* Domain Path: /languages
*/
// Load translations
add_action( 'plugins_loaded', 'cmr_rwffcf_load_textdomain' );
function cmr_rwffcf_load_textdomain(){
  load_plugin_textdomain( 'remove-web-field-from-comments-form', false, dirname( plugin_basename(__FILE__)).'/languages' );
}
// Starts the plugin
add_action( 'admin_init', 'cmr_rwffcf_init' );
add_action( 'plugins_loaded', 'cmr_rwffcf_execute' );
function cmr_rwffcf_init(){
  // Section record
  register_setting( 'general', 'cmr_rwffcf_settings', 'cmr_rwffcf_sanitize_validate_settings' );
  // Adding the fields
  $settings = get_option('cmr_rwffcf_settings');
  add_settings_field( 'cmr_rwffcf_field_one', esc_html__('Remove web field from comments form', 'remove-web-field-from-comments-form'), 'cmr_rwffcf_fields_callback', 'general', 'default', array(
    'name' => 'cmr_rwffcf_settings[one]',
    'value' => $settings['one']
  ) );
}
// Checking and validating the fields
function cmr_rwffcf_sanitize_validate_settings( $input ){
  $output = get_option( 'cmr_rwffcf_settings' );
  // Sanitizing the value
  if( $input['one'] == 'on'):
    $output['one'] = $input['one'];
  else:
    $output['one'] = '';
  endif;
  return $output;
}
// Field load
function cmr_rwffcf_fields_callback( $args ){
	if(esc_attr( $args['value'] ) == 'on'):
		$cmrremovewebfieldstatus = 'checked';
	else:
		$cmrremovewebfieldstatus = '';
	endif;
  echo '<input value="on" type="checkbox" name="'.esc_attr( $args['name'] ).'"'.$cmrremovewebfieldstatus.'>';
  echo '</br>';
  echo '<p class="description">' . __('Check this checkbox to disable the web field on the comments form.', 'remove-web-field-from-comments-form' ) . '</p>';
}
function cmr_rwffcf_execute(){
  // Getting the value
  $settings = get_option( 'cmr_rwffcf_settings' );
    // Getting first value of the array
  if( $settings['one'] ):
    $cmrdeletewebfield = $settings['one'];
    // Checking if is active
    if($cmrdeletewebfield == 'on'):
    // Removing web field
    add_filter( 'comment_form_default_fields', 'cmr_rwffcf_remove_url_comment_form' );
    function cmr_rwffcf_remove_url_comment_form($fields){
      unset($fields['url']);
      return $fields;
    }
    endif;
  endif;
}

