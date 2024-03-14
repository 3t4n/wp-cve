<?php
/* Only loads when php version >= 5.3
*
* Version 8.4.01.004
*
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

add_action('init', 'wppa_load_cloudinary');
function wppa_load_cloudinary() {

	$cdn = wppa_get_option('wppa_cdn_service', 'nil');

	if ( $cdn != 'cloudinary' && $cdn != 'cloudinarymaintenance' ) return;

	require_once 'vendor/cloudinary/src/Cloudinary.php';
	require_once 'vendor/cloudinary/src/Uploader.php';
	require_once 'vendor/cloudinary/src/Api.php';

	\Cloudinary::config(array(
		"cloud_name" 	=> wppa_get_option('wppa_cdn_cloud_name'),
		"api_key" 		=> wppa_get_option('wppa_cdn_api_key'),
		"api_secret" 	=> wppa_get_option('wppa_cdn_api_secret')
	));

	global $wppa_cloudinary_api;
	$wppa_cloudinary_api = new \Cloudinary\Api();
}

function wppa_upload_to_cloudinary( $id ) {

	$prefix = ( is_multisite() && ! WPPA_MULTISITE_GLOBAL ) ? $blog_id.'-' : '';

	$args 	= array(	"public_id" 	=> $prefix.$id,
						"version"		=> wppa_get_option('wppa_photo_version', '1'),
						"invalidate" 	=> true
					);

	// Try proper oriented source
	$file 	= wppa_get_o1_source_path( $id );

	if ( ! is_file( $file )  ) {

		// Try source
		$file 	= wppa_get_source_path( $id );
	}

	if ( ! is_file( $file ) ) {

		// Use display file
		$file 	= wppa_get_photo_path( $id );
	}

	// If ImageMagick magically edited, upload the display file
	if ( wppa_get_photo_item( $id, 'magickstack' ) ) {
		$file 	= wppa_get_photo_path( $id );
	}

	// Doit
	if ( is_file ( $file ) ) {
		\Cloudinary\Uploader::upload( $file, $args );
		wppa_log( 'Dbg', $file . ' uploaded to Cloudinary' );
	}

}

function wppa_get_present_at_cloudinary_a() {
global $wppa_cloudinary_api;
global $wppa_session;

	// Init timer
	$t0 = microtime( true );

	// If no next pointer and array exists: done building up
	if ( ! isset( $wppa_session['cloudinary_next_cursor'] ) ) {
		if ( isset( $wppa_session['cloudinary_ids'] ) ) {
			return $wppa_session['cloudinary_ids']; 	// Array complete
		}

		// No next pointer and no array: First time, init array and get first chunk of data
		$wppa_session['cloudinary_ids'] = array();
		$data = $wppa_cloudinary_api->resources( array( "type" => "upload",
														"max_results" => 500 ) );
	}

	// If there is a next pointer, get successive chunk
	else {
		$data = $wppa_cloudinary_api->resources( array( "type" => "upload",
														"next_cursor" => $wppa_session['cloudinary_next_cursor'],
														"max_results" => 500));
	}

	// Process data
	$temp = get_object_vars ( $data );
	foreach ( $temp['resources'] as $res ) {
		$wppa_session['cloudinary_ids'][$res['public_id']] = true;
	}

	// See if done
	if ( isset( $temp['next_cursor'] ) ) {
		$wppa_session['cloudinary_next_cursor'] = $temp['next_cursor']; // Update next pinter
	}
	else {
		unset( $wppa_session['cloudinary_next_cursor'] ); // Indicate done
	}

	$t1 = microtime( true );

	$next = isset( $wppa_session['cloudinary_next_cursor'] ) ? $wppa_session['cloudinary_next_cursor'] : 'none';
	wppa_log( 'Obs', sprintf( 'Get present at cloudinary took %6.2f seconds. Next=' . $next, $t1-$t0 ) );

	// If next cursor exists, return false, else return true
	return ( ! isset( $wppa_session['cloudinary_next_cursor'] ) );
}

function wppa_ready_on_cloudinary() {
	if ( isset ( $wppa_session['cloudinary_ids'] ) ) unset( $wppa_session['cloudinary_ids'] );
}

function wppa_delete_from_cloudinary( $id ) {
global $wppa_cloudinary_api;

	$prefix = ( is_multisite() && ! WPPA_MULTISITE_GLOBAL ) ? $blog_id.'-' : '';
	if ( is_array( $id ) ) {
		foreach( array_keys( $id ) as $key ) {
			$id[$key] = $prefix.$id[$key];
		}
		$pub_id = implode( ',', $id );
	}
	else {
		$pub_id =  $prefix.$id;
	}

	$result = $wppa_cloudinary_api->delete_derived_resources( $pub_id );
	$result = $wppa_cloudinary_api->delete_resources( $pub_id );

	if ( isset( $result->rate_limit_allowed ) ) {
		if( $result->rate_limit_remaining < '10' ) {
			wppa_log( 'Obs', 'Running out of Cloudinary API calls' );
			wppa_echo( 'Error: Running out of allowed Cloudinary Api calls. Please try to continue in an hour' );
		}
	}
}

function wppa_delete_all_from_cloudinary() {
global $wppa_cloudinary_api;

	$data = $wppa_cloudinary_api->delete_all_resources();
	$temp = get_object_vars( $data );

	if ( isset( $temp['next_cursor'] ) ) return false;
	return true;
}

function wppa_delete_derived_from_cloudinary() {
global $wppa_cloudinary_api;

	$data = $wppa_cloudinary_api->delete_all_resources( array( "keep_original" => TRUE	) );
	$temp = get_object_vars( $data );

	if ( isset( $temp['next_cursor'] ) ) return false;
	return true;
}

function wppa_get_cloudinary_url( $id, $test_only = false ) {
global $blog_id;

	$thumb 		= wppa_cache_photo( $id );
	$ext 		= $thumb['ext'] == 'xxx' ? 'jpg' : $thumb['ext'];
	$prefix 	= ( is_multisite() && ! WPPA_MULTISITE_GLOBAL ) ? $blog_id.'-' : '';
	$size 		= $test_only ? 'h_32/' : '';
	$s 			= is_ssl() ? 's' : '';

	$url = 'http'.$s.'://res.cloudinary.com/'.wppa_get_option('wppa_cdn_cloud_name').'/image/upload/'.$size.$prefix.$id.'.'.$ext;

	return $url;
}

function wppa_get_cloudinary_usage() {
global $wppa_cloudinary_api;

	if ( $wppa_cloudinary_api ) {
		return get_object_vars( $wppa_cloudinary_api->usage() );
	}
	else {
		return false;
	}
}