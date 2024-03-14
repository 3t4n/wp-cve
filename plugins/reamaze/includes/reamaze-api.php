<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Reamaze API Functions
 *
 * @author      Reamaze
 * @package     Reamaze
 * @version     2.3.2
 */

function get_option_api( $option ) {
  return new WP_REST_Response( get_option( $option ), 200 );
}

function update_option_api( $option, $new_val ) {
  $current_val = get_option( $option );

  if ( $current_val == $new_val ) {
    return new WP_REST_Response( $current_val, 200 );
  } else if ( update_option( $option, $new_val ) ) {
    return get_option_api( $option );
  } else {
    return new WP_Error( 'update_failed', 'Something went wrong!', array( 'status' => 404 ) );
  }
}

function check_permission() {
  return is_super_admin();
}

function get_reamaze_account_id( $request ) {
  return get_option_api( 'reamaze_account_id' );
}

function update_reamaze_account_id( $request ) {
  $new_val = $request->get_json_params()['reamaze_account_id'];
  return update_option_api( 'reamaze_account_id', $new_val );
}

function update_reamaze_account_sso_key( $request ) {
  $new_val = $request->get_json_params()['reamaze_account_sso_key'];
  return update_option_api( 'reamaze_account_sso_key', $new_val );
}

function get_reamaze_widget_code( $request ) {
  return get_option_api( 'reamaze_widget_code' );
}

function update_reamaze_widget_code( $request ) {
  $new_val = $request->get_json_params()['reamaze_widget_code'];
  return update_option_api( 'reamaze_widget_code', $new_val );
}

function get_reamaze_cue_code( $request ) {
  return get_option_api( 'reamaze_cue_code' );
}

function update_reamaze_cue_code( $request ) {
  $new_val = $request->get_json_params()['reamaze_cue_code'];
  return update_option_api( 'reamaze_cue_code', $new_val );
}

add_action( 'rest_api_init', function () {
  register_rest_route( 'reamaze/v1', '/options/reamaze_account_id', array(
    array(
      'methods'   => WP_REST_Server::READABLE,
      'callback'  => 'get_reamaze_account_id',
      'permission_callback' => 'check_permission',
    ),
    array(
      'methods'   => WP_REST_Server::EDITABLE,
      'callback'  => 'update_reamaze_account_id',
      'permission_callback' => 'check_permission',
      'args' => array(
        'reamaze_account_id' => array(
          'required' => true,
        ),
      ),
    ),
  ) );

  register_rest_route( 'reamaze/v1', '/options/reamaze_account_sso_key', array(
    array(
      'methods'   => WP_REST_Server::EDITABLE,
      'callback'  => 'update_reamaze_account_sso_key',
      'permission_callback' => 'check_permission',
      'args' => array(
        'reamaze_account_sso_key' => array(
          'required' => true,
        ),
      ),
    ),
  ) );

  register_rest_route( 'reamaze/v1', '/options/reamaze_widget_code', array(
    array(
      'methods'   => WP_REST_Server::READABLE,
      'callback'  => 'get_reamaze_widget_code',
      'permission_callback' => 'check_permission',
    ),
    array(
      'methods'   => WP_REST_Server::EDITABLE,
      'callback'  => 'update_reamaze_widget_code',
      'permission_callback' => 'check_permission',
      'args' => array(
        'reamaze_widget_code' => array(
          'required' => true,
        ),
      ),
    ),
  ) );

  register_rest_route( 'reamaze/v1', '/options/reamaze_cue_code', array(
    array(
      'methods'   => WP_REST_Server::READABLE,
      'callback'  => 'get_reamaze_cue_code',
      'permission_callback' => 'check_permission',
    ),
    array(
      'methods'   => WP_REST_Server::EDITABLE,
      'callback'  => 'update_reamaze_cue_code',
      'permission_callback' => 'check_permission',
      'args' => array(
        'reamaze_cue_code' => array(
          'required' => true,
        ),
      ),
    ),
  ) );
});
