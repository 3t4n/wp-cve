<?php
include_once 'defaults.php';
include_once 'settings.php';

/**
 * @param \WP_REST_Request $req
 */
function srizon_mortgage_save_instance( $req ) {
	$json_data = json_decode( $req->get_body() );
	$title     = $json_data->title;

	$payload            = [ ];
	$payload['title']   = $title;
	$payload['options'] = serialize( srizon_mortgage_get_global_settings() );

	SrizonMortgageDB::saveInstance( $payload );

	$ret['result'] = 'saved';
	$ret['instances'] = srizon_mortgage_get_instance_index();
	$ret['api']    = $payload;

	return $ret;
}

function srizon_mortgage_get_instance_index() {
	return SrizonMortgageDB::getAllInstances();
}


/**
 * @param array $req
 *
 * @return mixed
 */
function srizon_mortgage_delete_instance( $req ) {
	SrizonMortgageDB::deleteInstance( $req['id'] );
	$ret['result'] = 'deleted';
	$ret['instances'] = srizon_mortgage_get_instance_index();

	return $ret;
}

/**
 * @param array $req
 *
 * @return mixed
 */
function srizon_mortgage_get_instance( $req ) {
	$instance = SrizonMortgageDB::getInstance( (int) $req['id'] );
	if ( $instance ) {
		$ret['result'] = 'success';
		$ret['instance']  = $instance;

		return $ret;
	}

	return new WP_Error( 'instance_not_found', 'Instance Not Found. Make sure that the shortcode matches and existing instance', [ 'status' => 404 ] );
}

/**
 * @param \WP_REST_Request $req
 *
 * @return mixed
 */
function srizon_mortgage_update_instance_settings( $req ) {
	$json_data = json_decode( $req->get_body() );

	SrizonMortgageDB::updateInstanceSettings( $json_data->id, $json_data->settings );
	$ret['result'] = 'updated';
	$ret['instances'] = srizon_mortgage_get_instance_index();

	return $ret;
}


add_action( 'rest_api_init', function () {
	register_rest_route( 'srizon-mortgage/v1', '/instance/', [
		'methods'             => 'POST',
		'callback'            => 'srizon_mortgage_save_instance',
		'permission_callback' => 'srizon_mortgage_permission_admin',
	] );

	register_rest_route( 'srizon-mortgage/v1', '/instance/', [
		'methods'  => 'GET',
		'callback' => 'srizon_mortgage_get_instance_index',
	] );

	register_rest_route( 'srizon-mortgage/v1', '/instance/(?P<id>[\d]+)', [
		'methods'             => 'DELETE',
		'callback'            => 'srizon_mortgage_delete_instance',
		'permission_callback' => 'srizon_mortgage_permission_admin',
	] );
	register_rest_route( 'srizon-mortgage/v1', '/instance/(?P<id>[\d]+)', [
		'methods'  => 'GET',
		'callback' => 'srizon_mortgage_get_instance',
	] );
	
	register_rest_route( 'srizon-mortgage/v1', '/instance-settings/', [
		'methods'             => 'POST',
		'callback'            => 'srizon_mortgage_update_instance_settings',
		'permission_callback' => 'srizon_mortgage_permission_admin',
	] );
} );
