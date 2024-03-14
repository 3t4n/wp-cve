<?php

namespace ImageComply;

if (!defined('ABSPATH')) {
  exit; 
}

use WP_REST_Request;

class ImageComply_Custom_Route extends \WP_REST_Controller
{

	public function __construct() {
		add_action('rest_api_init', [$this, 'register_routes']);
	}

  public function register_routes()
  {
		$version = '1';
		$namespace = 'imagecomply/v' . $version;

		register_rest_route($namespace, '/images-processed', array(
			'methods' => 'POST',
			'callback' => array($this, 'images_processed'),
			'permission_callback' => array($this, 'permission_callback'),
		));

		// register_rest_route($namespace, '/image-optimized', array(
		// 	'methods' => 'POST',
		// 	'callback' => array($this, 'image_optimized'),
		// 	'permission_callback' => array($this, 'permission_callback'),
		// ));

		register_rest_route($namespace, '/images-failed', array(
			'methods' => 'POST',
			'callback' => array($this, 'images_failed'),
			'permission_callback' => array($this, 'permission_callback'),
		));

		register_rest_route($namespace, '/disconnect', array(
			'methods' => 'POST',
			'callback' => array($this, 'disconnect'),
			'permission_callback' => array($this, 'permission_callback'),
		));
	}

	public function disconnect(WP_REST_Request $request) {
		$json = $request->get_json_params();

		if(!is_array($json) || !isset($json['licenseKey']) || $json['licenseKey'] === '') {
			$data = array(
				'error' => 'Invalid JSON data.'
			);

			return new \WP_REST_Response($data, 400);
		}

		$licenseKey = $json['licenseKey'];

		$licenseKey = sanitize_text_field($licenseKey);

		$hereLicenseKey = get_option('imagecomply_license_key', '');

		if($hereLicenseKey === '') {
			$data = array(
				'code' => 100,
				'error' => 'Already disconnected.'
			);

			return new \WP_REST_Response($data, 400);
		}

		if($licenseKey !== $hereLicenseKey) {
			$data = array(
				'error' => 'Invalid license key.'
			);

			return new \WP_REST_Response($data, 400);
		}

		$hereLicenseKey = '';

		$updated = update_option('imagecomply_license_key', $hereLicenseKey);

		$data = array(
			'success' => $updated,
		);

		return new \WP_REST_Response($data, 200);
	}

	public function images_failed(WP_REST_Request $request){
		//
		$json = $request->get_json_params();

		if (
				!is_array($json) || 
				!isset($json['failed'])
			)
		{
			$data = array(
				'error' => 'Invalid JSON data.'
			);

			return new \WP_REST_Response($data, 400);
		}

		$failed = $json['failed'];

		foreach($failed as $image_id) {
			update_post_meta($image_id, 'imagecomply_alt_text_status', 'error');
		}
	}

	public function images_processed(WP_REST_Request $request)
	{
		$json = $request->get_json_params();
	
		if (
			!is_array($json) || 
			!isset($json['success']) || 
			!isset($json['images']) ||
			!is_array($json['images'])
		) {
			$data = array(
				'error' => 'Invalid JSON data.'
			);

			return new \WP_REST_Response($data, 400);
		}

		$success = $json['success'];

		if(!$success) {
			$data = array(
				'error' => 'ImageComply API error.'
			);

			return new \WP_REST_Response($data, 400);
		}

		$images = $json['images'];

		foreach($images as $image) {

			$imageId = $image['id'];
			// $imageUrl = $image['url'];
			$imageAlt = $image['caption'];
			
			$error = isset($image['error']) ? $image['error'] : false;
			
			if ($error) {
				// Sanitize the error message for database operation
				$sanitized_error = sanitize_text_field($error);
				
				update_post_meta($imageId, 'imagecomply_alt_text_status', 'error');
				
				// TODO: Add $sanitized_error to a `imagecomply_alt_text_status_msg` meta field so we can see why certain images have failed in dashboard.
				
				continue;
				
				// $data = array(
				// 	'error' => $sanitized_error
				// );
				
				// return new \WP_REST_Response($data, 200);
			}
				
			$imageLanguage = $image['language'];
			$imageKeywords = $image['keywords'];
			$imageNegKeywords = $image['negKeywords'];

			// Sanitize the imageAlt for database operation
			$sanitized_image_alt = sanitize_text_field($imageAlt);

			update_post_meta($imageId, '_wp_attachment_image_alt', $sanitized_image_alt);
			do_action("imagecomply_post_save_alt_text", $image['id'], $sanitized_image_alt);

			$serialized_data = get_post_meta($imageId, 'imagecomply', true);

			// Polylang translations return an object not a string, so skip unserializing
			if(is_string($serialized_data)){
				$serialized_data = unserialize($serialized_data);
			}
			$data_array = $serialized_data;
			$data_array['generated_alt'] = $sanitized_image_alt;

			if($imageLanguage) {
				$data_array['language'] = $imageLanguage;
			}
			if($imageKeywords) {
				$data_array['keywords'] = $imageKeywords;
			}
			if($imageNegKeywords) {
				$data_array['negKeywords'] = $imageNegKeywords;
			}

			$modified_serialized_data = serialize($data_array);

			update_post_meta($imageId, 'imagecomply', $modified_serialized_data);
			update_post_meta($imageId, 'imagecomply_alt_text_status', 'complete');

		}

		

		$data = array(
			'success' => true,
			'image' => array(
				'id' => $imageId,
			)
		);

		return new \WP_REST_Response($data, 200);
	}


	// public function image_optimized(WP_REST_Request $request) {
	// 	$json = $request->get_json_params();

	// 	if (
	// 		!is_array($json)
	// 	) {
	// 		$data = array(
	// 			'error' => 'Invalid JSON data.'
	// 		);

	// 		return new \WP_REST_Response($data, 400);
	// 	}
		
	// 	//base64 buffer
	// 	$image = $json['image'];
	// 	$image_id = $json['id'];
	// 	$error  	= $json['error'];

	// 	$includes_url = ABSPATH . WPINC;

	// 	if($error === true) {
	// 		update_post_meta($image_id, 'imagecomply_optimization_status', 'error');

	// 		$data = array(
	// 			'error' => $error
	// 		);

	// 		return new \WP_REST_Response($data, 200);
	// 	}

	// 	include_once($includes_url.'/post.php');		

	// 	$image_data = wp_get_attachment_metadata($image_id, true);

	// 	$base_dir = pathinfo($image_data['file'], PATHINFO_DIRNAME);
	// 	$file_name = pathinfo($image_data['file'], PATHINFO_FILENAME);
	// 	$file_type = pathinfo($image_data['file'], PATHINFO_EXTENSION);

	// 	$old_path = wp_upload_dir()['url'].'/'.$file_name.'.'.$file_type;

	// 	$wp_content_dir = wp_upload_dir();

	// 	$target_file = $wp_content_dir['basedir'].'/optimized/'.$base_dir;
		
	// 	$relative_path = str_replace(wp_normalize_path(ABSPATH), '', wp_normalize_path($target_file));

	// 	if(!file_exists(ABSPATH.$relative_path)){
	// 		mkdir(ABSPATH.$relative_path, 0755, true);
	// 	}

	// 	$new_path = ABSPATH.$relative_path.'/'.$file_name.'.webp';
		
	// 	file_put_contents($new_path, base64_decode($image));
		
	// 	$empty_attachment_data = array();
	// 	wp_update_attachment_metadata($image_id, $empty_attachment_data);

	// 	//Insert the following attachment here
	// 	update_attached_file(
	// 		$image_id, 
	// 		'optimized/'.$base_dir.'/'.$file_name.'.webp'
	// 	);

	// 	/* 
			
	// 		https://wordpress.org/support/topic/reliable-way-to-include-files-under-the-wp-admin-directory/ 
		
	// 		TODO - unsure if this will work in every wp instance but it seems to be the recommended way to include files under the wp-admin dir
			
	// 		***********************************************************************************************
	// 	*/
	// 	include_once( ABSPATH . 'wp-admin/includes/admin.php' );
		
	// 	//Get the new attachment metadata for the image
	// 	$attached_file = get_attached_file($image_id);
	// 	$new_attachment_data =  wp_generate_attachment_metadata($image_id, $attached_file);

	// 	wp_update_attachment_metadata($image_id, $new_attachment_data);

	// 	//Update the ImageComply metadata
	// 	$old_file_size = $image_data['filesize'];
	// 	$new_file_size = $new_attachment_data['filesize'];
		
	// 	$serialized_data = get_post_meta($image_id, 'imagecomply', true);
		
	// 	$data_array = unserialize($serialized_data);

	// 	$data_array['old_path'] = $old_path;
	// 	$data_array['old_file_size'] = $old_file_size;
	// 	$data_array['new_file_size'] = $new_file_size;
	// 	$data_array['memory_saved'] = 100 - (($new_file_size / $old_file_size) * 100);

	// 	$modified_serialized_data = serialize($data_array);		

	// 	update_post_meta($image_id, 'imagecomply', $modified_serialized_data);
	// 	update_post_meta($image_id, 'imagecomply_optimization_status', 'optimized');

	// 	$data = array(
	// 		'success' => true,
	// 		'image' => array(
	// 			'id' => $image_id,
	// 		)
	// 	);

	// 	return new \WP_REST_Response($data, 200);
	// }

	public function permission_callback() {
		return true;
	}
}

new ImageComply_Custom_Route();