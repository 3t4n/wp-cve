<?php
include_once "defaults.php";

function srizon_mortgage_get_settings() {
	$settings['global'] = srizon_mortgage_get_global_settings();
	return $settings;
}

function srizon_mortgage_get_global_settings() {
	$global_settings = get_option( 'srizon_mortgage_global_settings', false );
	if ( $global_settings ) {
		return array_merge( srizon_mortgage_global_defaults(), (array) $global_settings );
	} else {
		return srizon_mortgage_global_defaults();
	}
}

/**
 * @param \WP_REST_Request $req
 *
 * @return mixed
 */
function srizon_mortgage_save_global_settings( $req ) {
	$json_data = json_decode( $req->get_body() );
	update_option( 'srizon_mortgage_global_settings', $json_data );

	$resp           = [ ];
	$resp['result'] = 'saved';
	$resp['data']   = $json_data;

	return $resp;
}

add_action( 'rest_api_init', function () {

	register_rest_route( 'srizon-mortgage/v1', '/settings/', [
		'methods'             => 'GET',
		'callback'            => 'srizon_mortgage_get_settings',
		'permission_callback' => 'srizon_mortgage_permission_admin',
	] );

	register_rest_route( 'srizon-mortgage/v1', '/save-global-settings/', [
		'methods'             => 'POST',
		'callback'            => 'srizon_mortgage_save_global_settings',
		'permission_callback' => 'srizon_mortgage_permission_admin',
	] );
} );

